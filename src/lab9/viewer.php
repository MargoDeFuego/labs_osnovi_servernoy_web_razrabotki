<?php
require_once __DIR__ . '/db.php';

// Таблица + пагинация по 10 записей, сортировка
function render_viewer(string $sort = 'created', int $page = 1): string {
    $pdo = db();
    $perPage = 10;
    $offset = ($page - 1) * $perPage;

    switch ($sort) {
        case 'last_name':  $orderBy = 'last_name ASC, first_name ASC'; break;
        case 'birth_date': $orderBy = 'birth_date ASC'; break;
        default:           $orderBy = 'created_at ASC';
    }

    $total = (int)$pdo->query('SELECT COUNT(*) FROM contacts')->fetchColumn();

    $stmt = $pdo->prepare("SELECT * FROM contacts ORDER BY $orderBy LIMIT :lim OFFSET :off");
    $stmt->bindValue(':lim', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $th = 'style="border:1px solid #ccc;padding:6px;"';
    $td = 'style="border:1px solid #ccc;padding:6px;"';

    $html = '<table style="border-collapse:collapse;width:100%;">'
          . '<thead><tr>'
          . "<th $th>Фамилия</th><th $th>Имя</th><th $th>Отчество</th><th $th>Пол</th>"
          . "<th $th>Дата рождения</th><th $th>Телефон</th><th $th>Адрес</th>"
          . "<th $th>E-mail</th><th $th>Комментарий</th>"
          . '</tr></thead><tbody>';

    foreach ($rows as $r) {
        $html .= '<tr>'
              . "<td $td>" . htmlspecialchars($r['last_name']) . '</td>'
              . "<td $td>" . htmlspecialchars($r['first_name']) . '</td>'
              . "<td $td>" . htmlspecialchars($r['middle_name']) . '</td>'
              . "<td $td>" . htmlspecialchars($r['gender']) . '</td>'
              . "<td $td>" . htmlspecialchars($r['birth_date']) . '</td>'
              . "<td $td>" . htmlspecialchars($r['phone']) . '</td>'
              . "<td $td>" . htmlspecialchars($r['address']) . '</td>'
              . "<td $td>" . htmlspecialchars($r['email']) . '</td>'
              . "<td $td>" . nl2br(htmlspecialchars($r['comment'])) . '</td>'
              . '</tr>';
    }
    if (empty($rows)) {
        $html .= '<tr><td colspan="9" style="border:1px solid #ccc;padding:10px;text-align:center;color:#888;">Нет записей</td></tr>';
    }
    $html .= '</tbody></table>';

    $pages = (int)ceil($total / $perPage);
    if ($pages > 1) {
        $html .= '<div style="margin-top:12px;">';
        for ($p = 1; $p <= $pages; $p++) {
            $style = 'display:inline-block;margin:4px;padding:6px 10px;border:1px solid #ccc;text-decoration:none;color:#333;';
            $hover = 'onmouseover="this.style.border=\'2px solid #333\'" onmouseout="this.style.border=\'1px solid #ccc\'"';
            $html .= "<a $hover style=\"$style\" href=\"?view=viewer&sort=$sort&page=$p\">$p</a>";
        }
        $html .= '</div>';
    }

    return $html;
}
?>