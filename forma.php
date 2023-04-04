<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
  if (!empty($_GET['save'])) {
    // Если есть параметр save, то выводим сообщение пользователю.
    print('Форма успешно отправлена.');
  }
  // Включаем содержимое файла form.php.
  include('index.php');
  // Завершаем работу скрипта.
  exit();
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.

// Проверяем ошибки.
$errors = FALSE;
if (empty($_POST['name'])) {
  print('Введите имя.<br/>');
  $errors = TRUE;
}

if (empty($_POST['email']) or !(strpos($_POST['email'], '@'))) {
  print('Введите вашу почту.<br/>');
  $errors = TRUE;
}

if (empty($_POST['year'])) {
  print('Выберите год рождения.<br/>');
  $errors = TRUE;
}

if (empty($_POST['sex'])) {
  print('Укажите ваш пол.<br/>');
  $errors = TRUE;
}


if (empty($_POST['legs'])){
    print ('Укажите количество конечностей.<br>');
    $errors = true;

}

if (empty($_POST['powers'])){
    print ('Выберите одну или несколько сверхспособностей.<br>');
    $errors = true;

}
else {
  $super = serialize($_POST['powers']);
}

if (empty($_POST['bio'])){
    print ('Придумайте свою биографию...<br>');
    $errors = true;
}

if (empty($_POST['check-1'])){
    print ('Пожалуйста, ознакомтесь с контрактом.<br>');
    $errors = true;
}

if ($errors) {
  // При наличии ошибок завершаем работу скрипта.
  exit();
}

// Сохранение в базу данных.

$user = 'u52988';
$pass = '4622873';
$db = new PDO('mysql:host=localhost;dbname=u52988', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

// Подготовленный запрос. Не именованные метки.

try {
  $stmt = $db->prepare("INSERT INTO person SET name = ?, email = ?, year = ?, sex = ?, legs = ?, biography = ?");
  $stmt -> execute(array(
		$_POST['name'],
        $_POST['email'],
        $_POST['year'],
        $_POST['sex'],
        $_POST['legs'],
        $_POST['bio'],
	));
	
  $stmt = $db->prepare("INSERT INTO superpower SET name = ?, superpower = ?");
  $stmt -> execute(array(
	 	 $_POST['name'],
		$_POST['powers'] = implode(', ', $_POST['powers']),
	));
}
catch(PDOException $e){
  print('Error: ' . $e->getMessage());
  exit();
}
header('Location: ?save=1');
