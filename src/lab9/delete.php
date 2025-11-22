<?php
require_once __DIR__ . '/db.php';

// Модуль удаления записи
function render_delete(): string {
    $pdo = db();
    $list = $pdo->query('SELECT id, last_name, first_name FROM contacts ORDER BY last_name ASC, first_name ASC')->fetchAll(PDO::FETCH_ASSOC);

    $message = '';
    $id = (int)($_GET['id'] ?? 0);

    if ($id) {
        // Получаем фамилию перед удалением
        $stmt = $pdo->prepare('SELECT last_name FROM contacts WHERE id=:id');
        $stmt->execute([':id' => $id]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($r) {
            $pdo->prepare('DELETE FROM contacts WHERE id=:id')->execute([':id'=>$id]);
            $message = '<p style="color:green;">Запись с фамилией ' . htmlspecialchars($r['last_name']) . ' удалена</p>';
        } else {
            $message = '<p style="color:red;">Запись не найдена</p>';
        }
    }

    if (empty($list)) {
        return $message . '<p style="color:#888;">Нет записей для удаления</p>';
    }

    // Список ссылок для удаления
    $links = '<div style="margin-bottom:10px;">';
    foreach ($list as $p) {
        $style = 'display:inline-block;margin:4px;padding:6px 10px;border:1px solid #1e90ff;color:#fff;background:#1e90ff;text-decoration:none;border-radius:4px;';
        $initial = mb_substr($p['first_name'], 0, 1);
        $links .= "<a style=\"$style\" href=\"?view=delete&id={$p['id']}\">" 
                . htmlspecialchars($p['last_name']) . ' ' . htmlspecialchars($initial) . ".</a>";
    }
    $links .= '</div>';

    return $message . $links;
}
?>