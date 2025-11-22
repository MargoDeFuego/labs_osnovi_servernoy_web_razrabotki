<?php
require_once __DIR__ . '/config.php';
?>
<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '../../nav.php'; ?>

<main>
<h2>Лабораторная работа №2</h2>
<h1>Вариант задания №3</h1>
<div class="horizontal-scrollbar overflow-x-auto whitespace-pre pb-3 pt-4 text-center"><span initial="start" animate="end" variants="[object Object]" custom="0.126"><span class="katex"><math xmlns="http://www.w3.org/1998/Math/MathML" display="block"><mrow><mi>f</mi><mo stretchy="false">(</mo><mi>x</mi><mo stretchy="false">)</mo><mo>=</mo><mrow><mo fence="true">{</mo><mtable rowspacing="0.36em"><mtr><mtd><mstyle scriptlevel="0" displaystyle="false"><mrow><mn>3</mn><mo>⋅</mo><msup><mi>x</mi><mn>3</mn></msup><mo>+</mo><mn>2</mn><mo separator="true">,</mo></mrow></mstyle></mtd><mtd><mstyle scriptlevel="0" displaystyle="false"><mrow><mi>x</mi><mo>≤</mo><mn>10</mn></mrow></mstyle></mtd></mtr><mtr><mtd><mstyle scriptlevel="0" displaystyle="false"><mrow><mn>5</mn><mo>⋅</mo><mi>x</mi><mo>+</mo><mn>7</mn><mo separator="true">,</mo></mrow></mstyle></mtd><mtd><mstyle scriptlevel="0" displaystyle="false"><mrow><mn>10</mn><mo>&lt;</mo><mi>x</mi><mo>&lt;</mo><mn>20</mn></mrow></mstyle></mtd></mtr><mtr><mtd><mstyle scriptlevel="0" displaystyle="false"><mrow><mfrac><mi>x</mi><mrow><mn>22</mn><mo>−</mo><mi>x</mi></mrow></mfrac><mo>−</mo><mi>x</mi><mo separator="true">,</mo></mrow></mstyle></mtd><mtd><mstyle scriptlevel="0" displaystyle="false"><mrow><mi>x</mi><mo>≥</mo><mn>20</mn></mrow></mstyle></mtd></mtr></mtable></mrow></mrow>
</math></span></span></div>

<h2>Решение:</h2>

    <?php
// 1. Инициализация переменных
$start = 5;       // начальное значение аргумента
$count = 10;      // количество вычисляемых значений
$step = 2;        // шаг изменения аргумента
$layoutType = 'D'; // тип верстки: A, B, C, D, E

// 2. Определение функции f(x)
function f($x) {
    if ($x <= 10) {
        return 3 * pow($x, 3) + 2;
    } elseif ($x > 10 && $x < 20) {
        return 5 * $x + 7;
    } elseif ($x >= 20) {
        return ($x / (22 - $x)) - $x;
    }
    return null;
}

// 3. Вычисление значений
$results = [];
$x = $start;
for ($i = 0; $i < $count; $i++, $x += $step) {
    $y = f($x);
    if ($y === null) break;
    $results[] = ['x' => $x, 'y' => $y];
}

// 4. Вывод в зависимости от типа верстки
switch ($layoutType) {
    case 'A': // простая верстка
        foreach ($results as $r) {
            echo "f({$r['x']})={$r['y']}<br>";
        }
        break;

    case 'B': // маркированный список
        echo "<ul>";
        foreach ($results as $r) {
            echo "<li>f({$r['x']})={$r['y']}</li>";
        }
        echo "</ul>";
        break;

    case 'C': // нумерованный список
        echo "<ol>";
        foreach ($results as $r) {
            echo "<li>f({$r['x']})={$r['y']}</li>";
        }
        echo "</ol>";
        break;

    case 'D': // таблица
        echo "<table border='1' style='border-collapse:collapse;'>";
        echo "<tr><th>#</th><th>x</th><th>f(x)</th></tr>";
        $i = 1;
        foreach ($results as $r) {
            echo "<tr><td>$i</td><td>{$r['x']}</td><td>{$r['y']}</td></tr>";
            $i++;
        }
        echo "</table>";
        break;

    case 'E': // блочная верстка
        foreach ($results as $r) {
            echo "<div style='float:left; border:2px solid red; margin-right:8px; padding:4px;'>";
            echo "f({$r['x']})={$r['y']}";
            echo "</div>";
        }
        break;
}

// 5. Статистика
$values = array_column($results, 'y');
if (!empty($values)) {
    $max = max($values);
    $min = min($values);
    $sum = array_sum($values);
    $avg = $sum / count($values);

    echo "<hr>";
    echo "Максимум: $max<br>";
    echo "Минимум: $min<br>";
    echo "Сумма: $sum<br>";
    echo "Среднее: $avg<br>";
}

// 6. Подвал
echo "<hr>Тип верстки: $layoutType";
?>
</main>

<?php include __DIR__ . '/footer.php'; ?>

