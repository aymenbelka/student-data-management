<?php
session_start();
if (!empty($_SESSION['admin'])) { header('Location: index.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = trim($_POST['username'] ?? '');
  $pass = $_POST['password'] ?? '';
  // غيّر بيانات الدخول قبل النشر
  if ($user === 'admin' && $pass === 'admin123') {
    session_regenerate_id(true);
    $_SESSION['admin'] = true;
    header('Location: index.php'); exit;
  }
  $error = 'اسم المستخدم أو كلمة المرور غير صحيحة';
}
?><!doctype html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>تسجيل الدخول</title><link rel="stylesheet" href="assets/style.css"></head><body class="login-page"><form class="login-card" method="post"><div class="brand-mark">🎓</div><h1>سجل الطلاب</h1><p>تسجيل دخول المدير</p><?php if($error): ?><div class="form-error"><?=htmlspecialchars($error,ENT_QUOTES,'UTF-8')?></div><?php endif; ?><label>اسم المستخدم<input name="username" required autocomplete="username"></label><label>كلمة المرور<input name="password" type="password" required autocomplete="current-password"></label><button class="btn primary" type="submit">دخول</button><small>الافتراضي: admin / admin123</small></form></body></html>
