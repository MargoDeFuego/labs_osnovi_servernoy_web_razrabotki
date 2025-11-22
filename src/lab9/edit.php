<?php
require_once __DIR__ . '/db.php';

// Список ссылок + форма редактирования
function render_edit(): string {
    $pdo = db();
    $list = $pdo->query('SELECT id, last_name, first_name FROM contacts ORDER BY last_name ASC, first_name ASC')->fetchAll(PDO::FETCH_ASSOC);
    if (empty($list)) return '<p style="color:#888;">Нет записей для редактирования</p>';

    $id = (int)($_GET['id'] ?? $list[0]['id']);
    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        $data = [
            'id'          => $id,
            'last_name'   => trim($_POST['last_name'] ?? ''),
            'first_name'  => trim($_POST['first_name'] ?? ''),
            'middle_name' => trim($_POST['middle_name'] ?? ''),
            'gender'      => ($_POST['gender'] ?? 'М') === 'Ж' ? 'Ж' : 'М',
            'birth_date'  => trim($_POST['birth_date'] ?? ''),
            'phone'       => trim($_POST['phone'] ?? ''),
            'address'     => trim($_POST['address'] ?? ''),
            'email'       => trim($_POST['email'] ?? ''),
            'comment'     => trim($_POST['comment'] ?? ''),
        ];
        try {
            $stmt = $pdo->prepare('UPDATE contacts
                                   SET last_name=:last_name, first_name=:first_name, middle_name=:middle_name,
                                       gender=:gender, birth_date=:birth_date, phone=:phone, address=:address,
                                       email=:email, comment=:comment
                                   WHERE id=:id');
            $ok = $stmt->execute($data);
            $message = $ok ? '<p style="color:green;">Изменения сохранены</p>' : '<p style="color:red;">Ошибка сохранения</p>';
        } catch (Throwable $e) {
            $message = '<p style="color:red;">Ошибка сохранения</p>';
        }
    }

    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE id=:id');
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) return '<p style="color:red;">Запись не найдена</p>';

    $links = '<div style="margin-bottom:10px;">';
    foreach ($list as $p) {
        $isCurrent = ($p['id'] == $id);
        $style = 'display:inline-block;margin:4px;padding:6px 10px;border:1px solid #1e90ff;color:#1e90ff;text-decoration:none;border-radius:4px;';
        if ($isCurrent) $style .= 'background:#e6f7ff;';
        $links .= "<a style=\"$style\" href=\"?view=edit&id={$p['id']}\">" . htmlspecialchars($p['last_name']) . ' ' . htmlspecialchars($p['first_name']) . "</a>";
    }
    $links .= '</div>';

    $form = '
    <form method="post">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" value="' . (int)$row['id'] . '">
      <div><label>Фамилия</label><br><input type="text" name="last_name" value="' . htmlspecialchars($row['last_name']) . '" required></div>
      <div><label>Имя</label><br><input type="text" name="first_name" value="' . htmlspecialchars($row['first_name']) . '" required></div>
      <div><label>Отчество</label><br><input type="text" name="middle_name" value="' . htmlspecialchars($row['middle_name']) . '"></div>
      <div><label>Пол</label><br>
        <select name="gender">
          <option ' . ($row['gender']==='М'?'selected':'') . ' value="М">М</option>
          <option ' . ($row['gender']==='Ж'?'selected':'') . ' value="Ж">Ж</option>
        </select>
      </div>
      <div><label>Дата рождения</label><br><input type="date" name="birth_date" value="' . htmlspecialchars($row['birth_date']) . '"></div>
      <div><label>Телефон</label><br><input type="text" name="phone" value="' . htmlspecialchars($row['phone']) . '"></div>
      <div><label>Адрес</label><br><input type="text" name="address" value="' . htmlspecialchars($row['address']) . '"></div>
      <div><label>E-mail</label><br><input type="email" name="email" value="' . htmlspecialchars($row['email']) . '"></div>
      <div><label>Комментарий</label><br><textarea name="comment" rows="3">'. htmlspecialchars($row['comment']) .'</textarea></div>
      <div style="margin-top:10px;"><button type="submit">Сохранить</button></div>
    </form>';

    return $links . $message . $form;
}
?>