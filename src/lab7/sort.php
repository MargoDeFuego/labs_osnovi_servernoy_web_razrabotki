<?php
require_once __DIR__ . '/config.php';
?>
<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '../../nav.php'; ?>


<main>
<?php
$start = microtime(true);

// Получаем данные из формы
$arr = $_POST['arr'] ?? [];
$algorithm = $_POST['algorithm'] ?? '';

echo "<h2>Алгоритм: $algorithm</h2>";

if (empty($arr)) {
    echo "<p style='color:red'>Нет входных данных</p>";
    exit;
}

// Проверка на числа
foreach ($arr as $val) {
    if (!is_numeric(str_replace(',', '.', $val))) {
        echo "<p style='color:red'>Ошибка: все элементы должны быть числами</p>";
        exit;
    }
}

// Преобразуем в числа
$arr = array_map(function($v){ return (float)str_replace(',', '.', $v); }, $arr);
echo "<p>Входные данные: ".implode(", ", $arr)."</p>";

$iterations = 0;

// -----------------------------
// Алгоритмы сортировки
// -----------------------------

function selectionSort($arr, &$iterations) {
    $n = count($arr);
    for ($i=0; $i<$n-1; $i++) {
        $min = $i;
        for ($j=$i+1; $j<$n; $j++) {
            $iterations++;
            if ($arr[$j] < $arr[$min]) $min = $j;
            echo "Итерация $iterations: ".implode(", ", $arr)."<br>";
        }
        [$arr[$i], $arr[$min]] = [$arr[$min], $arr[$i]];
    }
    return $arr;
}

function bubbleSort($arr, &$iterations) {
    $n = count($arr);
    for ($i=0; $i<$n; $i++) {
        for ($j=0; $j<$n-$i-1; $j++) {
            $iterations++;
            if ($arr[$j] > $arr[$j+1]) {
                [$arr[$j], $arr[$j+1]] = [$arr[$j+1], $arr[$j]];
            }
            echo "Итерация $iterations: ".implode(", ", $arr)."<br>";
        }
    }
    return $arr;
}

function shellSort($arr, &$iterations) {
    $n = count($arr);
    for ($gap = intdiv($n,2); $gap > 0; $gap = intdiv($gap,2)) {
        for ($i=$gap; $i<$n; $i++) {
            $temp = $arr[$i];
            $j = $i;
            while ($j >= $gap && $arr[$j-$gap] > $temp) {
                $iterations++;
                $arr[$j] = $arr[$j-$gap];
                $j -= $gap;
                echo "Итерация $iterations: ".implode(", ", $arr)."<br>";
            }
            $arr[$j] = $temp;
        }
    }
    return $arr;
}

function gnomeSort($arr, &$iterations) {
    $n = count($arr);
    $i = 1;
    while ($i < $n) {
        $iterations++;
        if ($i == 0 || $arr[$i] >= $arr[$i-1]) {
            $i++;
        } else {
            [$arr[$i], $arr[$i-1]] = [$arr[$i-1], $arr[$i]];
            $i--;
        }
        echo "Итерация $iterations: ".implode(", ", $arr)."<br>";
    }
    return $arr;
}

function quickSortStep(&$arr, $low, $high, &$iterations) {
    if ($low < $high) {
        $pivot = $arr[$high];
        $i = $low - 1;
        for ($j=$low; $j<$high; $j++) {
            $iterations++;
            if ($arr[$j] <= $pivot) {
                $i++;
                [$arr[$i], $arr[$j]] = [$arr[$j], $arr[$i]];
            }
            echo "Итерация $iterations: ".implode(", ", $arr)."<br>";
        }
        [$arr[$i+1], $arr[$high]] = [$arr[$high], $arr[$i+1]];
        $pi = $i+1;
        quickSortStep($arr, $low, $pi-1, $iterations);
        quickSortStep($arr, $pi+1, $high, $iterations);
    }
}
function quickSort($arr, &$iterations) {
    quickSortStep($arr, 0, count($arr)-1, $iterations);
    return $arr;
}

function phpSort($arr, &$iterations) {
    sort($arr);
    $iterations = 1;
    echo "Итерация $iterations: ".implode(", ", $arr)."<br>";
    return $arr;
}

// -----------------------------
// Выбор алгоритма
// -----------------------------
switch ($algorithm) {
    case 'selection': $sorted = selectionSort($arr, $iterations); break;
    case 'bubble':    $sorted = bubbleSort($arr, $iterations); break;
    case 'shell':     $sorted = shellSort($arr, $iterations); break;
    case 'gnome':     $sorted = gnomeSort($arr, $iterations); break;
    case 'quick':     $sorted = quickSort($arr, $iterations); break;
    case 'php_sort':  $sorted = phpSort($arr, $iterations); break;
    default: echo "<p style='color:red'>Алгоритм не выбран</p>"; exit;
}

$time = microtime(true) - $start;
echo "<p><strong>Сортировка завершена, проведено $iterations итераций. Сортировка заняла ".round($time,6)." секунд.</strong></p>";
?>
</main>
<?php include __DIR__ . '/footer.php'; ?>