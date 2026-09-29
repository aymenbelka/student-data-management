<?php
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
$dir=__DIR__.'/data'; if(!is_dir($dir)) mkdir($dir,0750,true); $pdo=new PDO('sqlite:'.$dir.'/students.sqlite'); $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); $pdo->exec("CREATE TABLE IF NOT EXISTS students(id INTEGER PRIMARY KEY AUTOINCREMENT,name TEXT NOT NULL,address TEXT NOT NULL,phone TEXT NOT NULL,grade TEXT NOT NULL,stage TEXT NOT NULL,guardian TEXT DEFAULT '',guardian_email TEXT DEFAULT '',birth_date TEXT DEFAULT '',notes TEXT DEFAULT '',created_at TEXT DEFAULT CURRENT_TIMESTAMP,updated_at TEXT DEFAULT CURRENT_TIMESTAMP)"); $pdo->exec("CREATE TABLE IF NOT EXISTS grades(id INTEGER PRIMARY KEY AUTOINCREMENT,student_id INTEGER NOT NULL,semester INTEGER NOT NULL,arabic REAL DEFAULT 0,french REAL DEFAULT 0,english REAL DEFAULT 0,math REAL DEFAULT 0,science REAL DEFAULT 0,history_geo REAL DEFAULT 0,civic REAL DEFAULT 0,physical_ed REAL DEFAULT 0,arts REAL DEFAULT 0,islamic REAL DEFAULT 0,tech REAL DEFAULT 0,UNIQUE(student_id,semester),FOREIGN KEY(student_id) REFERENCES students(id) ON DELETE CASCADE)");
$subjects_by_stage = [
  'أولى ابتدائي' => [
    'arabic' => ['name' => 'اللغة العربية', 'coef' => 3],
    'french' => ['name' => 'اللغة الفرنسية', 'coef' => 2],
    'math' => ['name' => 'الرياضيات', 'coef' => 3],
    'science' => ['name' => 'العلوم والتكنولوجيا', 'coef' => 2],
    'civic' => ['name' => 'التربية المدنية', 'coef' => 1],
    'physical_ed' => ['name' => 'التربية البدنية', 'coef' => 1],
    'arts' => ['name' => 'التربية الفنية', 'coef' => 1],
    'islamic' => ['name' => 'التربية الإسلامية', 'coef' => 1],
  ],
  'ثانية ابتدائي' => [
    'arabic' => ['name' => 'اللغة العربية', 'coef' => 3],
    'french' => ['name' => 'اللغة الفرنسية', 'coef' => 2],
    'english' => ['name' => 'اللغة الإنجليزية', 'coef' => 2],
    'math' => ['name' => 'الرياضيات', 'coef' => 3],
    'science' => ['name' => 'العلوم والتكنولوجيا', 'coef' => 2],
    'civic' => ['name' => 'التربية المدنية', 'coef' => 1],
    'physical_ed' => ['name' => 'التربية البدنية', 'coef' => 1],
    'arts' => ['name' => 'التربية الفنية', 'coef' => 1],
    'islamic' => ['name' => 'التربية الإسلامية', 'coef' => 1],
  ],
  'ثالثة ابتدائي' => [
    'arabic' => ['name' => 'اللغة العربية', 'coef' => 3],
    'french' => ['name' => 'اللغة الفرنسية', 'coef' => 2],
    'english' => ['name' => 'اللغة الإنجليزية', 'coef' => 2],
    'math' => ['name' => 'الرياضيات', 'coef' => 3],
    'science' => ['name' => 'العلوم والتكنولوجيا', 'coef' => 2],
    'civic' => ['name' => 'التربية المدنية', 'coef' => 1],
    'physical_ed' => ['name' => 'التربية البدنية', 'coef' => 1],
    'arts' => ['name' => 'التربية الفنية', 'coef' => 1],
    'islamic' => ['name' => 'التربية الإسلامية', 'coef' => 1],
  ],
  'رابعة ابتدائي' => [
    'arabic' => ['name' => 'اللغة العربية', 'coef' => 3],
    'french' => ['name' => 'اللغة الفرنسية', 'coef' => 2],
    'english' => ['name' => 'اللغة الإنجليزية', 'coef' => 2],
    'math' => ['name' => 'الرياضيات', 'coef' => 3],
    'science' => ['name' => 'العلوم والتكنولوجيا', 'coef' => 2],
    'civic' => ['name' => 'التربية المدنية', 'coef' => 1],
    'physical_ed' => ['name' => 'التربية البدنية', 'coef' => 1],
    'arts' => ['name' => 'التربية الفنية', 'coef' => 1],
    'islamic' => ['name' => 'التربية الإسلامية', 'coef' => 1],
  ],
  'خامسة ابتدائي' => [
    'arabic' => ['name' => 'اللغة العربية', 'coef' => 3],
    'french' => ['name' => 'اللغة الفرنسية', 'coef' => 2],
    'english' => ['name' => 'اللغة الإنجليزية', 'coef' => 2],
    'math' => ['name' => 'الرياضيات', 'coef' => 3],
    'science' => ['name' => 'العلوم والتكنولوجيا', 'coef' => 2],
    'history_geo' => ['name' => 'التاريخ والجغرافيا', 'coef' => 2],
    'civic' => ['name' => 'التربية المدنية', 'coef' => 1],
    'physical_ed' => ['name' => 'التربية البدنية', 'coef' => 1],
    'arts' => ['name' => 'التربية الفنية', 'coef' => 1],
    'islamic' => ['name' => 'التربية الإسلامية', 'coef' => 1],
  ],
  'أولى متوسط' => [
    'arabic' => ['name' => 'اللغة العربية', 'coef' => 3],
    'french' => ['name' => 'اللغة الفرنسية', 'coef' => 3],
    'english' => ['name' => 'اللغة الإنجليزية', 'coef' => 2],
    'math' => ['name' => 'الرياضيات', 'coef' => 3],
    'science' => ['name' => 'العلوم الفيزيائية والتكنولوجيا', 'coef' => 2],
    'history_geo' => ['name' => 'التاريخ والجغرافيا', 'coef' => 2],
    'civic' => ['name' => 'التربية المدنية', 'coef' => 1],
    'physical_ed' => ['name' => 'التربية البدنية', 'coef' => 1],
    'arts' => ['name' => 'التربية الفنية', 'coef' => 1],
    'islamic' => ['name' => 'التربية الإسلامية', 'coef' => 1],
  ],
  'ثانية متوسط' => [
    'arabic' => ['name' => 'اللغة العربية', 'coef' => 3],
    'french' => ['name' => 'اللغة الفرنسية', 'coef' => 3],
    'english' => ['name' => 'اللغة الإنجليزية', 'coef' => 2],
    'math' => ['name' => 'الرياضيات', 'coef' => 3],
    'science' => ['name' => 'العلوم الفيزيائية والتكنولوجيا', 'coef' => 2],
    'history_geo' => ['name' => 'التاريخ والجغرافيا', 'coef' => 2],
    'civic' => ['name' => 'التربية المدنية', 'coef' => 1],
    'physical_ed' => ['name' => 'التربية البدنية', 'coef' => 1],
    'arts' => ['name' => 'التربية الفنية', 'coef' => 1],
    'islamic' => ['name' => 'التربية الإسلامية', 'coef' => 1],
  ],
  'ثالثة متوسط' => [
    'arabic' => ['name' => 'اللغة العربية', 'coef' => 3],
    'french' => ['name' => 'اللغة الفرنسية', 'coef' => 3],
    'english' => ['name' => 'اللغة الإنجليزية', 'coef' => 2],
    'math' => ['name' => 'الرياضيات', 'coef' => 3],
    'science' => ['name' => 'العلوم الفيزيائية والتكنولوجيا', 'coef' => 2],
    'history_geo' => ['name' => 'التاريخ والجغرافيا', 'coef' => 2],
    'civic' => ['name' => 'التربية المدنية', 'coef' => 1],
    'physical_ed' => ['name' => 'التربية البدنية', 'coef' => 1],
    'arts' => ['name' => 'التربية الفنية', 'coef' => 1],
    'islamic' => ['name' => 'التربية الإسلامية', 'coef' => 1],
  ],
  'رابعة متوسط' => [
    'arabic' => ['name' => 'اللغة العربية', 'coef' => 4],
    'french' => ['name' => 'اللغة الفرنسية', 'coef' => 4],
    'english' => ['name' => 'اللغة الإنجليزية', 'coef' => 2],
    'math' => ['name' => 'الرياضيات', 'coef' => 4],
    'science' => ['name' => 'العلوم الفيزيائية والتكنولوجيا', 'coef' => 3],
    'history_geo' => ['name' => 'التاريخ والجغرافيا', 'coef' => 2],
    'civic' => ['name' => 'التربية المدنية', 'coef' => 2],
    'physical_ed' => ['name' => 'التربية البدنية', 'coef' => 1],
    'arts' => ['name' => 'التربية الفنية', 'coef' => 1],
    'islamic' => ['name' => 'التربية الإسلامية', 'coef' => 1],
  ],
  'أولى ثانوي' => [
    'arabic' => ['name' => 'اللغة العربية وآدابها', 'coef' => 3],
    'french' => ['name' => 'اللغة الفرنسية', 'coef' => 3],
    'english' => ['name' => 'اللغة الإنجليزية', 'coef' => 2],
    'math' => ['name' => 'الرياضيات', 'coef' => 3],
    'science' => ['name' => 'العلوم الفيزيائية', 'coef' => 2],
    'history_geo' => ['name' => 'التاريخ والجغرافيا', 'coef' => 2],
    'islamic' => ['name' => 'الفلسفة والدين', 'coef' => 2],
    'civic' => ['name' => 'التربية المدنية', 'coef' => 1],
    'physical_ed' => ['name' => 'التربية البدنية والرياضية', 'coef' => 1],
  ],
  'ثانية ثانوي' => [
    'arabic' => ['name' => 'اللغة العربية وآدابها', 'coef' => 4],
    'french' => ['name' => 'اللغة الفرنسية', 'coef' => 4],
    'english' => ['name' => 'اللغة الإنجليزية', 'coef' => 3],
    'math' => ['name' => 'الرياضيات', 'coef' => 4],
    'science' => ['name' => 'العلوم الفيزيائية', 'coef' => 3],
    'history_geo' => ['name' => 'التاريخ والجغرافيا', 'coef' => 2],
    'islamic' => ['name' => 'الفلسفة والدين', 'coef' => 2],
    'civic' => ['name' => 'التربية المدنية', 'coef' => 1],
  ],
  'ثالثة ثانوي' => [
    'arabic' => ['name' => 'اللغة العربية وآدابها', 'coef' => 5],
    'french' => ['name' => 'اللغة الفرنسية', 'coef' => 4],
    'english' => ['name' => 'اللغة الإنجليزية', 'coef' => 3],
    'math' => ['name' => 'الرياضيات', 'coef' => 5],
    'science' => ['name' => 'العلوم الفيزيائية', 'coef' => 3],
    'history_geo' => ['name' => 'التاريخ والجغرافيا', 'coef' => 2],
    'islamic' => ['name' => 'الفلسفة والدين', 'coef' => 2],
    'civic' => ['name' => 'التربية المدنية', 'coef' => 1],
  ],
];

function out($x,$s=200){http_response_code($s);echo json_encode($x,JSON_UNESCAPED_UNICODE);exit;}
function input(){return json_decode(file_get_contents('php://input'),true)?:$_POST;}
function val($d,$k,$req=true){$v=trim((string)($d[$k]??''));if($req&&$v==='')out(['error'=>"الحقل $k مطلوب"],422);return $v;}

$method=$_SERVER['REQUEST_METHOD'];
$d=input();
$stages=['أولى ابتدائي','ثانية ابتدائي','ثالثة ابتدائي','رابعة ابتدائي','خامسة ابتدائي','أولى متوسط','ثانية متوسط','ثالثة متوسط','رابعة متوسط','أولى ثانوي','ثانية ثانوي','ثالثة ثانوي'];

try {
  if ($method === 'GET') {
    if (($_GET['action']??'') === 'grades') {
      $q = $pdo->prepare('SELECT * FROM grades WHERE student_id=? ORDER BY semester');
      $q->execute([(int)$_GET['id']]);
      out(['grades' => $q->fetchAll(PDO::FETCH_ASSOC)]);
    }
    if (($_GET['action']??'') === 'subjects') {
      $stage = trim($_GET['stage']??'');
      out(['subjects' => $subjects_by_stage[$stage] ?? []]);
    }
    $sql = 'SELECT * FROM students WHERE 1=1';
    $p = [];
    if (($q = trim($_GET['search']??'')) !== '') {
      $sql .= ' AND(name LIKE :q OR phone LIKE :q OR address LIKE :q OR guardian LIKE :q)';
      $p[':q'] = "%$q%";
    }
    if (in_array($_GET['stage']??'',$stages,true)) {
      $sql .= ' AND stage=:stage';
      $p[':stage'] = $_GET['stage'];
    }
    $q = $pdo->prepare($sql.' ORDER BY id DESC');
    $q->execute($p);
    out(['students' => $q->fetchAll(PDO::FETCH_ASSOC)]);
  }

  if ($method === 'POST') {
    $stage = val($d,'stage');
    if (!in_array($stage,$stages,true)) out(['error'=>'المرحلة غير صحيحة'],422);
    $q = $pdo->prepare('INSERT INTO students(name,address,phone,grade,stage,guardian,guardian_email,birth_date,notes) VALUES(?,?,?,?,?,?,?,?,?)');
    $q->execute([val($d,'name'),val($d,'address'),val($d,'phone'),val($d,'grade'),$stage,val($d,'guardian',false),val($d,'guardianEmail',false),val($d,'birthDate',false),val($d,'notes',false)]);
    $id = $pdo->lastInsertId();
    $q = $pdo->prepare('INSERT INTO grades(student_id,semester) VALUES(?,?)');
    for($i=1;$i<=3;$i++) $q->execute([$id,$i]);
    out(['message'=>'تمت إضافة الطالب بنجاح'],201);
  }

  $id = (int)($_GET['id']??$d['id']??0);
  if ($id<1) out(['error'=>'معرّف غير صحيح'],422);

  if ($method === 'PUT' && ($_GET['action']??'') === 'grades') {
    $s = (int)($d['semester']??0);
    if ($s<1||$s>3) out(['error'=>'الفصل يجب أن يكون 1 أو 2 أو 3'],422);
    
    $stage_result = $pdo->prepare('SELECT stage FROM students WHERE id=?');
    $stage_result->execute([$id]);
    $student = $stage_result->fetch(PDO::FETCH_ASSOC);
    if (!$student) out(['error'=>'الطالب غير موجود'],404);
    $stage = $student['stage'];
    $subjects = $subjects_by_stage[$stage] ?? [];
    
    $updates = [];
    $values = [];
    foreach(array_keys($subjects) as $key) {
      $updates[] = "$key=?";
      $values[] = (float)($d[$key]??0);
    }
    $values[] = $id;
    $values[] = $s;
    
    $sql = 'UPDATE grades SET ' . implode(',', $updates) . ' WHERE student_id=? AND semester=?';
    $q = $pdo->prepare($sql);
    $q->execute($values);
    out(['message'=>'تم حفظ الدرجات']);
  }

  if ($method === 'PUT') {
    $stage = val($d,'stage');
    if (!in_array($stage,$stages,true)) out(['error'=>'المرحلة غير صحيحة'],422);
    $q = $pdo->prepare('UPDATE students SET name=?,address=?,phone=?,grade=?,stage=?,guardian=?,guardian_email=?,birth_date=?,notes=?,updated_at=CURRENT_TIMESTAMP WHERE id=?');
    $q->execute([val($d,'name'),val($d,'address'),val($d,'phone'),val($d,'grade'),$stage,val($d,'guardian',false),val($d,'guardianEmail',false),val($d,'birthDate',false),val($d,'notes',false),$id]);
    out(['message'=>'تم تحديث بيانات الطالب']);
  }

  if ($method === 'DELETE') {
    $q = $pdo->prepare('DELETE FROM students WHERE id=?');
    $q->execute([$id]);
    out(['message'=>'تم حذف الطالب']);
  }

  out(['error'=>'طريقة غير مدعومة'],405);
} catch(Throwable $e) {
  error_log($e->getMessage());
  out(['error'=>'حدث خطأ في الخادم'],500);
}
