<?php
require_once __DIR__ . '/db.php';

// Форма добавления записи + обработка
function render_add(): string {
    $pdo = db();
    $msg = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
        $birth_date = trim($_POST['birth_date'] ?? '');
if ($birth_date === '') {
    $birth_date = null; // пустое поле -> NULL
}

$data = [
    'last_name'  => trim($_POST['last_name'] ?? ''),
    'first_name' => trim($_POST['first_name'] ?? ''),
    'middle_name'=> trim($_POST['middle_name'] ?? ''),
    'gender'     => ($_POST['gender'] ?? 'М') === 'Ж' ? 'Ж' : 'М',
    'birth_date' => $birth_date,
    'phone'      => trim($_POST['phone'] ?? ''),
    'address'    => trim($_POST['address'] ?? ''),
    'email'      => trim($_POST['email'] ?? ''),
    'comment'    => trim($_POST['comment'] ?? ''),
];

        try {
$stmt = $pdo->prepare('INSERT INTO contacts 
    (last_name, first_name, middle_name, gender, birth_date, phone, address, email, comment)
    VALUES (:last_name, :first_name, :middle_name, :gender, :birth_date, :phone, :address, :email, :comment)');



            $ok = $stmt->execute($data);
            $msg = $ok ? '<p style="color:green;">Запись добавлена</p>' : '<p style="color:red;">Ошибка: запись не добавлена</p>';
        } catch (Throwable $e) {
    $msg = '<p style="color:red;">Ошибка: запись не добавлена<br>'
         . htmlspecialchars($e->getMessage()) . '</p>';
}

    }

    return $msg . '
    <form method="post">
      <input type="hidden" name="action" value="add">
      <div><label>Фамилия</label><br><input type="text" name="last_name" required></div>
      <div><label>Имя</label><br><input type="text" name="first_name" required></div>
      <div><label>Отчество</label><br><input type="text" name="middle_name"></div>
      <div><label>Пол</label><br>
        <select name="gender"><option value="М">М</option><option value="Ж">Ж</option></select>
      </div>
      <div><label>Дата рождения</label><br><input type="date" name="birth_date"></div>
      <div><label>Телефон</label><br><input type="text" name="phone"></div>
      <div><label>Адрес</label><br><input type="text" name="address"></div>
      <div><label>E-mail</label><br><input type="email" name="email"></div>
      <div><label>Комментарий</label><br><textarea name="comment" rows="3"></textarea></div>
      <div style="margin-top:10px;"><button type="submit">Сохранить</button></div>
    </form>';
}
?>