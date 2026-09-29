<?php
require __DIR__ . '/auth.php';
?><!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>نظام إدارة الطلاب والدرجات</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <!-- Navigation Header -->
  <header class="topbar">
    <div class="container">
      <div class="brand">
        <span class="brand-icon">🎓</span>
        <div class="brand-text">
          <h1>نظام إدارة الطلاب</h1>
          <p>إدارة شاملة للطلاب والدرجات</p>
        </div>
      </div>
      <div class="header-actions">
        <a href="export.php" class="btn-icon" title="تصدير" aria-label="تصدير">⬇️</a>
        <button id="printBtn" class="btn-icon" title="طباعة" aria-label="طباعة">🖨️</button>
        <a href="logout.php" class="btn-icon logout" title="خروج" aria-label="خروج">🚪</a>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="container main-content">
    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-content">
        <h2>مرحباً بك في لوحة الإدارة</h2>
        <p>إدارة متكاملة لبيانات الطلاب والدرجات والتقارير</p>
      </div>
      <button id="newStudent" class="btn btn-primary btn-lg">
        <span>➕</span> إضافة طالب جديد
      </button>
    </section>

    <!-- Statistics Section -->
    <section class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-info">
          <span class="stat-label">إجمالي الطلاب</span>
          <strong class="stat-value" id="totalStudents">0</strong>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">📚</div>
        <div class="stat-info">
          <span class="stat-label">الابتدائي</span>
          <strong class="stat-value" id="primaryStudents">0</strong>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">📖</div>
        <div class="stat-info">
          <span class="stat-label">المتوسط</span>
          <strong class="stat-value" id="middleStudents">0</strong>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">🎯</div>
        <div class="stat-info">
          <span class="stat-label">الثانوي</span>
          <strong class="stat-value" id="secondaryStudents">0</strong>
        </div>
      </div>
    </section>

    <!-- Filters & Search Section -->
    <section class="filters-section">
      <div class="filter-wrapper">
        <input 
          id="search" 
          type="search" 
          class="search-input" 
          placeholder="🔍 ابحث عن طالب بالاسم أو الهاتف أو العنوان..."
          aria-label="بحث"
        >
      </div>
      <div class="filter-wrapper">
        <select id="stageFilter" class="select-input" aria-label="فلترة حسب المرحلة">
          <option value="">📊 كل المراحل</option>
          <optgroup label="الابتدائي">
            <option value="أولى ابتدائي">أولى ابتدائي</option>
            <option value="ثانية ابتدائي">ثانية ابتدائي</option>
            <option value="ثالثة ابتدائي">ثالثة ابتدائي</option>
            <option value="رابعة ابتدائي">رابعة ابتدائي</option>
            <option value="خامسة ابتدائي">خامسة ابتدائي</option>
          </optgroup>
          <optgroup label="المتوسط">
            <option value="أولى متوسط">أولى متوسط</option>
            <option value="ثانية متوسط">ثانية متوسط</option>
            <option value="ثالثة متوسط">ثالثة متوسط</option>
            <option value="رابعة متوسط">رابعة متوسط</option>
          </optgroup>
          <optgroup label="الثانوي">
            <option value="أولى ثانوي">أولى ثانوي</option>
            <option value="ثانية ثانوي">ثانية ثانوي</option>
            <option value="ثالثة ثانوي">ثالثة ثانوي</option>
          </optgroup>
        </select>
      </div>
      <button id="refresh" class="btn btn-secondary" aria-label="تحديث">🔄 تحديث</button>
    </section>

    <!-- Chart Section -->
    <section class="chart-section">
      <h3>📊 توزيع الطلاب حسب المرحلة</h3>
      <div id="stageChart" class="chart-container"></div>
    </section>

    <!-- Students Table Section -->
    <section class="table-section">
      <div class="table-header">
        <h3>📋 قائمة الطلاب</h3>
        <span class="result-count" id="resultCount">0 نتيجة</span>
      </div>
      
      <div id="loading" class="loading-state">
        <div class="spinner"></div>
        <p>جارٍ تحميل البيانات...</p>
      </div>
      
      <div id="empty" class="empty-state hidden">
        <p>📭 لا توجد بيانات متطابقة</p>
      </div>

      <div class="table-wrapper">
        <table id="studentsTable" class="students-table hidden">
          <thead>
            <tr>
              <th>اسم الطالب</th>
              <th>المرحلة</th>
              <th>الصف</th>
              <th>رقم الهاتف</th>
              <th>العنوان</th>
              <th>ولي الأمر</th>
              <th>الإجراءات</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </section>
  </main>

  <!-- Modal: Add/Edit Student -->
  <div id="modal" class="modal hidden">
    <div class="modal-overlay"></div>
    <div class="modal-content">
      <div class="modal-header">
        <h2 id="modalTitle">إضافة طالب جديد</h2>
        <button id="closeModal" class="close-btn" aria-label="إغلاق">✕</button>
      </div>
      
      <form id="studentForm" class="form">
        <input type="hidden" id="studentId">
        
        <div class="form-grid">
          <div class="form-group">
            <label for="name">اسم الطالب *</label>
            <input id="name" type="text" required maxlength="120" placeholder="أدخل اسم الطالب كاملاً">
          </div>
          
          <div class="form-group">
            <label for="stage">المرحلة الدراسية *</label>
            <select id="stage" required>
              <option value="">اختر المرحلة</option>
              <optgroup label="الابتدائي">
                <option value="أولى ابتدائي">أولى ابتدائي</option>
                <option value="ثانية ابتدائي">ثانية ابتدائي</option>
                <option value="ثالثة ابتدائي">ثالثة ابتدائي</option>
                <option value="رابعة ابتدائي">رابعة ابتدائي</option>
                <option value="خامسة ابتدائي">خامسة ابتدائي</option>
              </optgroup>
              <optgroup label="المتوسط">
                <option value="أولى متوسط">أولى متوسط</option>
                <option value="ثانية متوسط">ثانية متوسط</option>
                <option value="ثالثة متوسط">ثالثة متوسط</option>
                <option value="رابعة متوسط">رابعة متوسط</option>
              </optgroup>
              <optgroup label="الثانوي">
                <option value="أولى ثانوي">أولى ثانوي</option>
                <option value="ثانية ثانوي">ثانية ثانوي</option>
                <option value="ثالثة ثانوي">ثالثة ثانوي</option>
              </optgroup>
            </select>
          </div>

          <div class="form-group">
            <label for="grade">رقم/اسم الصف *</label>
            <input id="grade" type="text" required maxlength="30" placeholder="مثال: 4-ب">
          </div>

          <div class="form-group">
            <label for="phone">رقم الهاتف *</label>
            <input id="phone" type="tel" required maxlength="30" placeholder="مثال: 05xxxxxxxx">
          </div>

          <div class="form-group full-width">
            <label for="address">العنوان *</label>
            <input id="address" type="text" required maxlength="255" placeholder="عنوان سكن الطالب">
          </div>

          <div class="form-group">
            <label for="guardian">اسم ولي الأمر</label>
            <input id="guardian" type="text" maxlength="120" placeholder="الأب أو الأم أو الوصي">
          </div>

          <div class="form-group">
            <label for="guardianEmail">بريد ولي الأمر</label>
            <input id="guardianEmail" type="email" maxlength="100" placeholder="example@email.com">
          </div>

          <div class="form-group">
            <label for="birthDate">تاريخ الميلاد</label>
            <input id="birthDate" type="date">
          </div>

          <div class="form-group full-width">
            <label for="notes">ملاحظات</label>
            <textarea id="notes" maxlength="1000" rows="3" placeholder="أي معلومات إضافية عن الطالب..."></textarea>
          </div>
        </div>

        <div id="formError" class="form-error hidden"></div>

        <div class="form-actions">
          <button type="button" id="cancel" class="btn btn-secondary">إلغاء</button>
          <button type="submit" class="btn btn-primary">💾 حفظ البيانات</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal: Student Grades -->
  <div id="gradesModal" class="modal hidden">
    <div class="modal-overlay"></div>
    <div class="modal-content modal-lg">
      <div class="modal-header">
        <h2 id="gradesModalTitle">درجات الطالب</h2>
        <button id="closeGradesModal" class="close-btn" aria-label="إغلاق">✕</button>
      </div>
      <div id="gradesContainer" class="grades-container"></div>
    </div>
  </div>

  <!-- Toast Notifications -->
  <div id="toast" class="toast"></div>

  <script src="assets/app.js"></script>
</body>
</html>
