<?php
require_once __DIR__ . '/config.php';
?>

<?php include __DIR__ . '/header.php'; ?>

<main>
<h2>Лабораторная работа №4</h2>

<?php
// Количество колонок (можно менять)
$COLS = 3;

// Массив структур таблиц (не менее 10 элементов)
$tables = [
    "Яблоко*Груша*Слива#Вишня*Абрикос*Персик",
    "Москва*Питер*Казань#Новосибирск*Екатеринбург*Самара",
    "PHP*HTML*CSS#JavaScript*Python*C++",
    "Красный*Синий*Зелёный#Жёлтый*Фиолетовый*Оранжевый",
    "Зима*Весна*Лето#Осень*Холод*Тепло",
    "Кошка*Собака*Мышь#Лев*Тигр*Медведь",
    "Один*Два*Три#Четыре*Пять*Шесть",
    "Книга*Тетрадь*Ручка#Карандаш*Ластик*Линейка",
    "Море*Горы*Лес#Река*Озеро*Поле",
    "Авто*Поезд*Самолёт#Корабль*Трамвай*Метро"
];

// Функция для вывода строки таблицы
function getTR($row, $COLS) {
    $cells = explode("*", $row);
    if (count($cells) == 0 || (count($cells) == 1 && trim($cells[0]) == "")) {
        return ""; // пустая строка не выводится
    }
    $html = "<tr>";
    for ($i = 0; $i < $COLS; $i++) {
        $html .= "<td>" . ($cells[$i] ?? "") . "</td>";
    }
    $html .= "</tr>";
    return $html;
}

// Функция для вывода таблицы
function printTable($structure, $COLS, $num) {
    if ($COLS <= 0) {
        echo "<p>Неправильное число колонок</p>";
        return;
    }

    $rows = explode("#", $structure);
    if (count($rows) == 0) {
        echo "<p>В таблице нет строк</p>";
        return;
    }

    $tableHTML = "";
    foreach ($rows as $row) {
        $tr = getTR($row, $COLS);
        if ($tr != "") {
            $tableHTML .= $tr;
        }
    }

    if ($tableHTML == "") {
        echo "<p>В таблице нет строк с ячейками</p>";
        return;
    }

    echo "<h2>Таблица №$num</h2>";
    echo "<table border='1'>$tableHTML</table>";
}
?>
<h1>Пример вывода таблиц: вариант задания №3</h1>

<?php
$num = 1;
foreach ($tables as $structure) {
    printTable($structure, $COLS, $num);
    $num++;
}
?>

</main>

<?php include __DIR__ . '/footer.php'; ?>

