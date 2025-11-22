<?php
require_once __DIR__ . '/config.php';
?>
<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '../../nav.php'; ?>

<main>
<h2>Лабораторная работа №5</h2>
<h1>Таблица умножения</h1>

   <?php
// Параметры
$html_type = $_GET['html_type'] ?? 'TABLE';
$content = $_GET['content'] ?? 'all';

// Заголовок
$title = ($content === 'all') ? 'Полная таблица умножения' : "Таблица умножения на $content";

// Ссылки на числа
function linkTo($num) {
    return "<a href='?content=$num'>$num</a>";
}

// Строка умножения
function generateRow($i, $j) {
    $result = $i * $j;
    return linkTo($i) . " × " . linkTo($j) . " = " . linkTo($result);
}

// Главное меню
echo "<div class='main-menu'>";
echo "<a href='?html_type=TABLE&content=$content' class='" . ($html_type === 'TABLE' ? 'selected' : '') . "'>Табличная верстка</a>";
echo "<a href='?html_type=DIV&content=$content' class='" . ($html_type === 'DIV' ? 'selected' : '') . "'>Блочная верстка</a>";
echo "</div>";

// Контейнер
echo "<div class='container'>";

// Боковое меню
echo "<div class='side-menu'>";
$items = ['all', 2, 3, 4, 5, 6, 7, 8, 9];
foreach ($items as $item) {
    $label = ($item === 'all') ? 'Всё' : $item;
    $class = ($content == $item) ? 'selected' : '';
    echo "<a href='?content=$item' class='$class'>$label</a>";
}
echo "</div>";

// Таблица
echo "<div class='table-area'>";
$rows = [];

if ($content === 'all') {
    for ($i = 2; $i <= 9; $i++) {
        $col = [];
        for ($j = 2; $j <= 9; $j++) {
            $col[] = generateRow($i, $j);
        }
        $rows[] = $col;
    }
} else {
    $i = (int)$content;
    for ($j = 2; $j <= 9; $j++) {
        $rows[] = [generateRow($i, $j)];
    }
}

if ($html_type === 'TABLE') {
    echo "<table>";
    foreach ($rows as $row) {
        echo "<tr>";
        foreach ($row as $cell) {
            echo "<td>$cell</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    foreach ($rows as $row) {
        echo "<div class='row'>";
        foreach ($row as $cell) {
            echo "<div class='cell'>$cell</div>";
        }
        echo "</div>";
    }
}
echo "</div>"; // .table-area
echo "</div>"; // .container

?>

</main>

<?php include __DIR__ . '/footer.php'; ?>

