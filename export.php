<?php
require __DIR__ . '/auth.php';
$db = __DIR__ . '/data/students.sqlite';
if (!is_file($db)) { header('Content-Type:text/plain; charset=utf-8'); exit('لا توجد بيانات لتصديرها.'); }
$pdo = new PDO('sqlite:' . $db);
$rows = $pdo->query('SELECT name,stage,grade,phone,address,guardian,guardian_email FROM students ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=students.csv');
$out = fopen('php://output','w');
fwrite($out, "\xEF\xBB\xBF");
fputcsv($out,['الاسم','المرحلة','الصف','الهاتف','العنوان','ولي الأمر','بريد ولي الأمر']);
foreach($rows as $row) fputcsv($out,$row);
fclose($out);
