<?php
// API بسيطة وآمنة نسبياً باستخدام SQLite وPDO.
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
$dbDir = __DIR__ . '/data';
if (!is_dir($dbDir)) mkdir($dbDir, 0750, true);
$pdo = new PDO('sqlite:' . $dbDir . '/students.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("CREATE TABLE IF NOT EXISTS students (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, address TEXT NOT NULL, phone TEXT NOT NULL, grade TEXT NOT NULL, stage TEXT NOT NULL CHECK(stage IN ('ابتدائي','متوسط')), guardian TEXT DEFAULT '', birth_date TEXT DEFAULT '', notes TEXT DEFAULT '', created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP)");
function out($data, $status=200) { http_response_code($status); echo json_encode($data, JSON_UNESCAPED_UNICODE); exit; }
function body() { $raw = file_get_contents('php://input'); $data = json_decode($raw, true); return is_array($data) ? $data : $_POST; }
function clean($key, $required=true, $max=255) { global $data; $value = trim((string)($data[$key] ?? '')); if ($required && $value === '') out(['error'=>"الحقل $key مطلوب"], 422); if (mb_strlen($value) > $max) out(['error'=>"الحقل $key طويل جداً"], 422); return $value; }
$method = $_SERVER['REQUEST_METHOD'];
try {
  if ($method === 'GET') {
    $search = trim($_GET['search'] ?? ''); $stage = trim($_GET['stage'] ?? '');
    $sql = 'SELECT * FROM students WHERE 1=1'; $params=[];
    if ($search !== '') { $sql .= ' AND (name LIKE :q OR phone LIKE :q OR address LIKE :q OR guardian LIKE :q)'; $params[':q'] = "%$search%"; }
    if (in_array($stage, ['ابتدائي','متوسط'], true)) { $sql .= ' AND stage = :stage'; $params[':stage']=$stage; }
    $sql .= ' ORDER BY id DESC'; $stmt=$pdo->prepare($sql); $stmt->execute($params); out(['students'=>$stmt->fetchAll(PDO::FETCH_ASSOC)]);
  }
  $data=body();
  if ($method === 'POST') {
    $name=clean('name',true,120); $address=clean('address',true); $phone=clean('phone',true,30); $grade=clean('grade',true,30); $stage=clean('stage',true,20); if (!in_array($stage,['ابتدائي','متوسط'],true)) out(['error'=>'المرحلة غير صحيحة'],422);
    $stmt=$pdo->prepare('INSERT INTO students(name,address,phone,grade,stage,guardian,birth_date,notes) VALUES(?,?,?,?,?,?,?,?)'); $stmt->execute([$name,$address,$phone,$grade,$stage,clean('guardian',false,120),clean('birthDate',false,10),clean('notes',false,1000)]); out(['message'=>'تمت إضافة الطالب بنجاح','id'=>$pdo->lastInsertId()],201);
  }
  $id=(int)($_GET['id'] ?? $data['id'] ?? 0); if ($id < 1) out(['error'=>'معرّف غير صحيح'],422);
  if ($method === 'PUT') {
    $name=clean('name',true,120); $address=clean('address',true); $phone=clean('phone',true,30); $grade=clean('grade',true,30); $stage=clean('stage',true,20); if (!in_array($stage,['ابتدائي','متوسط'],true)) out(['error'=>'المرحلة غير صحيحة'],422);
    $stmt=$pdo->prepare('UPDATE students SET name=?,address=?,phone=?,grade=?,stage=?,guardian=?,birth_date=?,notes=?,updated_at=CURRENT_TIMESTAMP WHERE id=?'); $stmt->execute([$name,$address,$phone,$grade,$stage,clean('guardian',false,120),clean('birthDate',false,10),clean('notes',false,1000),$id]); out(['message'=>'تم تحديث بيانات الطالب']);
  }
  if ($method === 'DELETE') { $stmt=$pdo->prepare('DELETE FROM students WHERE id=?'); $stmt->execute([$id]); out(['message'=>'تم حذف الطالب']); }
  out(['error'=>'طريقة الطلب غير مدعومة'],405);
} catch (Throwable $e) { error_log($e->getMessage()); out(['error'=>'حدث خطأ في الخادم'],500); }
