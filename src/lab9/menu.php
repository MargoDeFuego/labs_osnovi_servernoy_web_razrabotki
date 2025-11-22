<?php
// Генерация основного меню и доп. меню сортировок
function render_menu(): string {
    $active = $_GET['view'] ?? 'viewer';       // viewer|add|edit|delete
    $sort   = $_GET['sort'] ?? 'created';      // created|last_name|birth_date
    $page   = max(1, (int)($_GET['page'] ?? 1));

    $btn = function(string $label, string $view) use ($active) {
        $isActive = $active === $view;
        $style = 'display:inline-block;padding:8px 12px;margin:4px;border:1px solid #1e90ff;color:#fff;background:#1e90ff;text-decoration:none;border-radius:4px;';
        if ($isActive) $style = str_replace('#1e90ff', '#ff4d4f', $style); // красный для активного
        return "<a style=\"$style\" href=\"?view=$view\">$label</a>";
    };

    $html = '<div style="margin-bottom:10px;">'
          . $btn('Просмотр', 'viewer')
          . $btn('Добавление записи', 'add')
          . $btn('Редактирование записи', 'edit')
          . $btn('Удаление записи', 'delete')
          . '</div>';

    if ($active === 'viewer') {
        $smallBtn = function(string $label, string $sortKey) use ($sort, $page) {
            $isActive = $sort === $sortKey;
            $style = 'font-size:13px;display:inline-block;padding:6px 10px;margin:2px;border:1px solid #1e90ff;color:#fff;background:#1e90ff;text-decoration:none;border-radius:4px;';
            if ($isActive) $style = str_replace('#1e90ff', '#ff4d4f', $style);
            return "<a style=\"$style\" href=\"?view=viewer&sort=$sortKey&page=$page\">$label</a>";
        };
        $html .= '<div style="margin-bottom:16px;">'
              . $smallBtn('По времени добавления', 'created')
              . $smallBtn('По фамилии', 'last_name')
              . $smallBtn('По дате рождения', 'birth_date')
              . '</div>';
    }

    return $html;
}
?>