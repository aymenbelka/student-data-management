<?php
// واجهة تطبيق إدارة بيانات الطلاب
?><!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>سجل الطلاب</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <header class="topbar">
    <div class="container brand"><span class="brand-mark">🎓</span><div><h1>سجل الطلاب</h1><p>إدارة بيانات المرحلة الابتدائية والمتوسطة</p></div></div>
  </header>
  <main class="container">
    <section class="hero"><div><h2>لوحة إدارة الطلاب</h2><p>أضف بيانات الطلاب، ابحث عنها، وعدّلها أو احذفها بسهولة.</p></div><button id="newStudent" class="btn primary">＋ طالب جديد</button></section>
    <section class="stats"><div class="stat"><span>إجمالي الطلاب</span><strong id="totalStudents">0</strong></div><div class="stat"><span>الابتدائي</span><strong id="primaryStudents">0</strong></div><div class="stat"><span>المتوسط</span><strong id="middleStudents">0</strong></div></section>
    <section class="panel toolbar"><label class="search">🔎<input id="search" type="search" placeholder="ابحث بالاسم أو الهاتف أو العنوان..."></label><select id="stageFilter"><option value="">كل المراحل</option><option value="ابتدائي">ابتدائي</option><option value="متوسط">متوسط</option></select><button id="refresh" class="btn secondary">تحديث</button></section>
    <section class="panel table-wrap"><div class="panel-title"><h2>قائمة الطلاب</h2><span id="resultCount">0 نتيجة</span></div><div id="loading" class="empty">جارٍ تحميل البيانات...</div><div id="empty" class="empty hidden">لا توجد بيانات مطابقة.</div><table id="studentsTable" class="hidden"><thead><tr><th>الاسم</th><th>المرحلة</th><th>الصف</th><th>الهاتف</th><th>العنوان</th><th>ولي الأمر</th><th>إجراءات</th></tr></thead><tbody></tbody></table></section>
  </main>
  <div id="modal" class="modal hidden"><div class="modal-card"><button id="closeModal" class="close">×</button><h2 id="modalTitle">إضافة طالب</h2><form id="studentForm"><input type="hidden" id="studentId"><div class="form-grid"><label>اسم الطالب *<input id="name" required maxlength="120"></label><label>المرحلة *<select id="stage" required><option value="">اختر المرحلة</option><option value="ابتدائي">ابتدائي</option><option value="متوسط">متوسط</option></select></label><label>رقم الصف *<input id="grade" required maxlength="30" placeholder="مثال: 4/ب"></label><label>رقم الهاتف *<input id="phone" required maxlength="30" type="tel"></label><label>العنوان *<input id="address" required maxlength="255"></label><label>اسم ولي الأمر<input id="guardian" maxlength="120"></label><label>تاريخ الميلاد<input id="birthDate" type="date"></label><label class="full">ملاحظات<textarea id="notes" maxlength="1000"></textarea></label></div><div id="formError" class="form-error"></div><div class="form-actions"><button type="button" id="cancel" class="btn secondary">إلغاء</button><button class="btn primary" type="submit">حفظ البيانات</button></div></form></div></div>
  <div id="toast" class="toast"></div>
  <script src="assets/app.js"></script>
</body>
</html>
