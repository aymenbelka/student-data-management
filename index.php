<?php
session_start();
if (!empty($_SESSION['admin'])) { header('Location: index.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = trim($_POST['username'] ?? '');
  $pass = $_POST['password'] ?? '';
  if ($user === 'admin' && $pass === 'admin123') {
    session_regenerate_id(true);
    $_SESSION['admin'] = true;
    header('Location: index.php'); exit;
  }
  $error = 'اسم المستخدم أو كلمة المرور غير صحيحة';
}
?><!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>نظام إدارة الطلاب - تسجيل الدخول</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    :root { --primary: #2563eb; --primary-dark: #1d4ed8; --primary-light: #dbeafe; --success: #10b981; --danger: #ef4444; --bg-dark: #0f172a; --bg-light: #f8fafc; --text-dark: #1e293b; --text-gray: #64748b; --border: #e2e8f0; }
    html, body { width: 100%; height: 100%; }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%);
      display: flex; align-items: center; justify-content: center; min-height: 100vh; position: relative; overflow: hidden;
    }
    body::before, body::after {
      content: ''; position: fixed; width: 400px; height: 400px; background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, transparent 70%); border-radius: 50%; z-index: 1;
    }
    body::before { top: -50px; right: -50px; animation: float 6s ease-in-out infinite; }
    body::after { width: 300px; height: 300px; bottom: -30px; left: -30px; animation: float 8s ease-in-out infinite reverse; }
    @keyframes float { 0%,100% { transform: translateY(0px) translateX(0px); } 50% { transform: translateY(-30px) translateX(20px); } }
    .login-container { position: relative; z-index: 10; width: min(480px, 100% - 40px); display: flex; flex-direction: column; gap: 30px; }
    .login-header { text-align: center; color: white; animation: slideDown 0.6s ease-out; }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
    .login-header .logo { font-size: 48px; margin-bottom: 15px; display: block; animation: bounce 0.6s ease-out 0.2s both; }
    @keyframes bounce { 0% { transform: scale(0.5) translateY(20px); opacity: 0; } 100% { transform: scale(1) translateY(0); opacity: 1; } }
    .login-header h1 { font-size: 28px; font-weight: 700; margin-bottom: 8px; letter-spacing: -0.5px; }
    .login-header p { font-size: 14px; opacity: 0.9; color: rgba(255,255,255,0.8); }
    .login-card { background: white; border-radius: 20px; padding: 45px; box-shadow: 0 20px 60px rgba(0,0,0,.3); animation: slideUp 0.6s ease-out 0.1s both; border: 1px solid rgba(255,255,255,.2); }
    @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .login-form { display: flex; flex-direction: column; gap: 20px; }
    .form-group { display: flex; flex-direction: column; gap: 10px; }
    .form-group label { font-weight: 600; font-size: 13px; color: var(--text-dark); text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px; }
    .form-group label span { font-size: 16px; }
    .form-group input { padding: 14px 16px; border: 2px solid var(--border); border-radius: 12px; font-size: 15px; font-family: inherit; transition: all 0.3s ease; background: var(--bg-light); }
    .form-group input:focus { outline: none; border-color: var(--primary); background: white; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); }
    .form-group input::placeholder { color: var(--text-gray); }
    .error-message { display: flex; align-items: center; gap: 10px; background: #fee2e2; border: 2px solid var(--danger); color: #991b1b; padding: 14px 16px; border-radius: 12px; font-size: 14px; font-weight: 500; animation: shake 0.3s ease-out; }
    .error-message::before { content: '⚠️'; font-size: 18px; }
    @keyframes shake { 0%,100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
    .submit-btn { padding: 14px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; border: none; border-radius: 12px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 10px; position: relative; overflow: hidden; }
    .submit-btn::before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: rgba(255,255,255,.2); transition: left 0.3s ease; }
    .submit-btn:hover::before { left: 100%; }
    .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(37,99,235,.4); }
    .submit-btn:active { transform: translateY(0); }
    .login-footer { text-align: center; font-size: 12px; color: rgba(255,255,255,.7); animation: fadeIn 0.8s ease-out 0.5s both; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .login-footer .credential-box { background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.2); padding: 16px; border-radius: 12px; margin-top: 15px; font-size: 13px; color: rgba(255,255,255,.9); }
    .login-footer .credential-box strong { color: #fbbf24; display: block; margin-bottom: 8px; }
    .login-footer .credential-box code { background: rgba(0,0,0,.3); padding: 2px 6px; border-radius: 4px; font-family: 'Courier New', monospace; white-space: nowrap; }
    @media (max-width: 600px) { .login-card { padding: 30px 20px; } .login-header h1 { font-size: 24px; } .form-group input { padding: 12px 14px; font-size: 14px; } .submit-btn { padding: 12px; font-size: 14px; } body::before { width: 300px; height: 300px; } body::after { width: 250px; height: 250px; } }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-header">
      <span class="logo">🎓</span>
      <h1>نظام إدارة الطلاب</h1>
      <p>تسجيل الدخول الآمن</p>
    </div>
    <div class="login-card">
      <form method="post" class="login-form">
        <div class="form-group">
          <label for="username"><span>👤</span>اسم المستخدم</label>
          <input id="username" type="text" name="username" placeholder="أدخل اسم المستخدم" required autofocus autocomplete="username">
        </div>
        <div class="form-group">
          <label for="password"><span>🔒</span>كلمة المرور</label>
          <input id="password" type="password" name="password" placeholder="أدخل كلمة المرور" required autocomplete="current-password">
        </div>
        <?php if ($error): ?><div class="error-message"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
        <button type="submit" class="submit-btn">تسجيل الدخول</button>
      </form>
    </div>
    <div class="login-footer">
      <p>🔐 بيانات تسجيل الدخول الافتراضية:</p>
      <div class="credential-box">
        <strong>اسم المستخدم:</strong><code>admin</code>
        <br><br>
        <strong>كلمة المرور:</strong><code>admin123</code>
        <br><br>
        <small style="opacity:0.8; display:block; margin-top:8px;">⚠️ يرجى تغيير بيانات الدخول قبل النشر على الإنترنت</small>
      </div>
    </div>
  </div>
</body>
</html>
