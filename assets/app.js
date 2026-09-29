const $ = id => document.getElementById(id);
let students = [];
let currentStudentGrades = {};

const modal = $('modal');
const gradesModal = $('gradesModal');

function escapeHtml(value='') { 
  return String(value).replace(/[&<>'"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c])); 
}

function toast(message, error=false) { 
  const el = $('toast'); 
  el.textContent = message; 
  el.className = 'toast show ' + (error ? 'error' : ''); 
  setTimeout(() => el.className = 'toast', 3000); 
}

// حساب المعدل والتقدير
function calcAverage(grades) {
  const subjects = ['arabic', 'english', 'math', 'science', 'social', 'religion'];
  const sum = subjects.reduce((acc, subj) => acc + (parseFloat(grades[subj]) || 0), 0);
  return (sum / subjects.length).toFixed(2);
}

function getGrade(average) {
  const avg = parseFloat(average);
  if (avg >= 90) return 'ممتاز';
  if (avg >= 80) return 'جيد جداً';
  if (avg >= 70) return 'جيد';
  if (avg >= 60) return 'مقبول';
  return 'ضعيف';
}

async function loadStudents() { 
  $('loading').classList.remove('hidden'); 
  $('empty').classList.add('hidden'); 
  $('studentsTable').classList.add('hidden');
  
  const p = new URLSearchParams({
    search: $('search').value,
    stage: $('stageFilter').value
  }); 
  
  try { 
    const r = await fetch('api.php?' + p); 
    const data = await r.json(); 
    if (!r.ok) throw Error(data.error); 
    students = data.students; 
    render(); 
  } catch (e) { 
    toast(e.message || 'تعذر تحميل البيانات', true); 
  } finally { 
    $('loading').classList.add('hidden'); 
  } 
}

function render() { 
  const body = $('studentsTable').querySelector('tbody'); 
  body.innerHTML = ''; 
  
  students.forEach(s => {
    const tr = document.createElement('tr'); 
    tr.innerHTML = `
      <td><strong>${escapeHtml(s.name)}</strong></td>
      <td><span class="badge">${escapeHtml(s.stage)}</span></td>
      <td>${escapeHtml(s.grade)}</td>
      <td dir="ltr">${escapeHtml(s.phone)}</td>
      <td>${escapeHtml(s.address)}</td>
      <td>${escapeHtml(s.guardian || '—')}</td>
      <td class="actions">
        <button class="icon grades" data-id="${s.id}" title="درجات">📊</button>
        <button class="icon edit" data-id="${s.id}" title="تعديل">✏️</button>
        <button class="icon delete" data-id="${s.id}" title="حذف">🗑️</button>
      </td>
    `; 
    body.appendChild(tr); 
  }); 
  
  $('resultCount').textContent = `${students.length} نتيجة`;
  $('totalStudents').textContent = students.length;
  $('primaryStudents').textContent = students.filter(s => s.stage === 'ابتدائي').length;
  $('middleStudents').textContent = students.filter(s => s.stage.includes('متوسط')).length;
  $('secondaryStudents').textContent = students.filter(s => s.stage.includes('ثانوي')).length;
  
  $('empty').classList.toggle('hidden', students.length !== 0); 
  $('studentsTable').classList.toggle('hidden', students.length === 0); 
}

function openForm(s = null) { 
  $('studentForm').reset(); 
  $('studentId').value = s?.id || ''; 
  $('modalTitle').textContent = s ? 'تعديل بيانات الطالب' : 'إضافة طالب'; 
  
  if (s) { 
    $('name').value = s.name; 
    $('address').value = s.address; 
    $('phone').value = s.phone; 
    $('grade').value = s.grade; 
    $('stage').value = s.stage; 
    $('guardian').value = s.guardian || ''; 
    $('birthDate').value = s.birth_date || ''; 
    $('notes').value = s.notes || ''; 
  } 
  
  $('formError').textContent = ''; 
  modal.classList.remove('hidden'); 
  $('name').focus(); 
}

function closeForm() { 
  modal.classList.add('hidden'); 
}

async function openGradesForm(studentId) {
  const student = students.find(s => s.id == studentId);
  if (!student) return;
  
  $('gradesModalTitle').textContent = `درجات ${escapeHtml(student.name)} - ${escapeHtml(student.stage)}`;
  
  try {
    const r = await fetch(`api.php?action=grades&id=${studentId}`);
    const data = await r.json();
    if (!r.ok) throw Error(data.error);
    
    currentStudentGrades = {};
    const gradesBysemester = {};
    
    data.grades.forEach(g => {
      gradesBysemester[g.semester] = g;
      if (!currentStudentGrades[g.semester]) {
        currentStudentGrades[g.semester] = g;
      }
    });
    
    let html = '';
    for (let semester = 1; semester <= 3; semester++) {
      const g = gradesBysemester[semester] || {arabic:0, english:0, math:0, science:0, social:0, religion:0, semester};
      const avg = calcAverage(g);
      const grade = getGrade(avg);
      
      html += `
        <div class="semester-section">
          <h3>الفصل الدراسي ${semester}</h3>
          <div class="grades-grid">
            <label>اللغة العربية<input type="number" class="grade-input" data-semester="${semester}" data-subject="arabic" value="${g.arabic || 0}" min="0" max="100" step="0.5"></label>
            <label>اللغة الإنجليزية<input type="number" class="grade-input" data-semester="${semester}" data-subject="english" value="${g.english || 0}" min="0" max="100" step="0.5"></label>
            <label>الرياضيات<input type="number" class="grade-input" data-semester="${semester}" data-subject="math" value="${g.math || 0}" min="0" max="100" step="0.5"></label>
            <label>العلوم<input type="number" class="grade-input" data-semester="${semester}" data-subject="science" value="${g.science || 0}" min="0" max="100" step="0.5"></label>
            <label>الدراسات الاجتماعية<input type="number" class="grade-input" data-semester="${semester}" data-subject="social" value="${g.social || 0}" min="0" max="100" step="0.5"></label>
            <label>التربية الدينية<input type="number" class="grade-input" data-semester="${semester}" data-subject="religion" value="${g.religion || 0}" min="0" max="100" step="0.5"></label>
          </div>
          <div class="grade-summary">
            <div>المعدل: <strong>${avg}</strong></div>
            <div>التقدير: <strong class="grade-badge">${grade}</strong></div>
          </div>
        </div>
      `;
    }
    
    $('gradesContainer').innerHTML = html + `
      <div class="form-actions">
        <button type="button" id="closeGradesBtn" class="btn secondary">إغلاق</button>
        <button id="saveGrades" class="btn primary" data-student-id="${studentId}">حفظ الدرجات</button>
      </div>
    `;
    
    $('closeGradesBtn').onclick = closeGradesForm;
    $('saveGrades').onclick = saveGrades;
    
    // تحديث المعدل والتقدير عند التغيير
    document.querySelectorAll('.grade-input').forEach(input => {
      input.addEventListener('change', updateGradeSummary);
    });
    
    gradesModal.classList.remove('hidden');
  } catch (e) {
    toast(e.message || 'تعذر تحميل الدرجات', true);
  }
}

function updateGradeSummary(e) {
  const semester = e.target.dataset.semester;
  const container = e.target.closest('.semester-section');
  const inputs = container.querySelectorAll('.grade-input');
  
  const grades = {};
  inputs.forEach(inp => {
    grades[inp.dataset.subject] = inp.value;
  });
  
  const avg = calcAverage(grades);
  const grade = getGrade(avg);
  
  const summary = container.querySelector('.grade-summary');
  summary.innerHTML = `<div>المعدل: <strong>${avg}</strong></div><div>التقدير: <strong class="grade-badge">${grade}</strong></div>`;
}

function closeGradesForm() {
  gradesModal.classList.add('hidden');
}

async function saveGrades(e) {
  const studentId = e.target.dataset.studentId;
  const changed = [];
  
  document.querySelectorAll('.grade-input').forEach(input => {
    const semester = input.dataset.semester;
    const subject = input.dataset.subject;
    const value = parseFloat(input.value) || 0;
    
    if (!changed[semester]) changed[semester] = {arabic:0, english:0, math:0, science:0, social:0, religion:0, semester};
    changed[semester][subject] = value;
  });
  
  try {
    for (let semester = 1; semester <= 3; semester++) {
      if (changed[semester]) {
        const r = await fetch(`api.php?action=grades&id=${studentId}`, {
          method: 'PUT',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify(changed[semester])
        });
        const d = await r.json();
        if (!r.ok) throw Error(d.error);
      }
    }
    
    toast('تم حفظ الدرجات بنجاح');
    closeGradesForm();
  } catch (e) {
    toast(e.message || 'خطأ في حفظ الدرجات', true);
  }
}

$('studentForm').addEventListener('submit', async e => {
  e.preventDefault(); 
  const id = $('studentId').value; 
  
  const payload = {
    name: $('name').value,
    address: $('address').value,
    phone: $('phone').value,
    grade: $('grade').value,
    stage: $('stage').value,
    guardian: $('guardian').value,
    birthDate: $('birthDate').value,
    notes: $('notes').value
  }; 
  
  try { 
    const r = await fetch('api.php' + (id ? '?id=' + id : ''), {
      method: id ? 'PUT' : 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(payload)
    }); 
    
    const d = await r.json(); 
    if (!r.ok) throw Error(d.error); 
    
    closeForm(); 
    toast(d.message); 
    loadStudents(); 
  } catch (e) {
    $('formError').textContent = e.message || 'تحقق من البيانات';
  } 
});

document.addEventListener('click', async e => {
  const grades = e.target.closest('.grades');
  const edit = e.target.closest('.edit');
  const del = e.target.closest('.delete');
  
  if (grades) {
    openGradesForm(grades.dataset.id);
  }
  
  if (edit) {
    openForm(students.find(s => s.id == edit.dataset.id));
  }
  
  if (del && confirm('هل أنت متأكد من حذف هذا الطالب وجميع درجاته؟')) {
    try {
      const r = await fetch('api.php?id=' + del.dataset.id, {method: 'DELETE'});
      const d = await r.json();
      if (!r.ok) throw Error(d.error);
      toast(d.message);
      loadStudents();
    } catch (x) {
      toast(x.message, true);
    }
  }
});

$('newStudent').onclick = () => openForm();
$('closeModal').onclick = closeForm;
$('cancel').onclick = closeForm;
$('closeGradesModal').onclick = closeGradesForm;
$('refresh').onclick = loadStudents;
$('stageFilter').onchange = loadStudents;

let timer;
$('search').oninput = () => {
  clearTimeout(timer);
  timer = setTimeout(loadStudents, 300);
};

modal.onclick = e => {
  if (e.target === modal) closeForm();
};

gradesModal.onclick = e => {
  if (e.target === gradesModal) closeGradesForm();
};

loadStudents();
