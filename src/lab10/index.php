<?php
require_once __DIR__ . '/config.php';
?>
<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '../../nav.php'; ?>

<main>
<h2>Лабораторная работа №10</h2>


    <?php
/* index.php — единый HTML-документ с интегрированным PHP
   Калькулятор для целых чисел и десятичных дробей.
   Требования:
   - Выражения с + - * / : и скобками ()
   - Валидация: посторонние символы → ошибка
   - Запрещены функции eval и подобные; свой парсер
   - История в подвале: прошлые вычисления (текущий результат не показывать, но в историю добавить для следующего открытия)
*/

// ---------- Конфигурация истории ----------
$HISTORY_FILE = __DIR__ . '/history.log';

// ---------- Утилиты валидации ----------
function isnum(string $s): bool {
    // Разрешаем форматы:
    //  - "0"
    //  - без ведущих нулей: "123", "-123", "+123"
    //  - десятичные: "0.5", "-0.5", "+0.5", "12.34" (без ведущих нулей для целой части: "012.3" — запрещено)
    //  - запрещено: "048", "46в62", ".", "+.", "-.", "1.", ".5"
    $s = trim($s);
    if ($s === '') return false;

    // Знак
    $sign = '';
    if ($s[0] === '+' || $s[0] === '-') {
        $sign = $s[0];
        $s = substr($s, 1);
        if ($s === '') return false;
    }

    // Десятичная точка допускается одна
    $parts = explode('.', $s);
    if (count($parts) === 1) {
        $int = $parts[0];
        if (!ctype_digit($int)) return false;
        if ($int === '0') return true;
        // без ведущих нулей
        return $int[0] !== '0';
    } elseif (count($parts) === 2) {
        [$int, $frac] = $parts;
        if ($int === '' || $frac === '') return false;
        if (!ctype_digit($int) || !ctype_digit($frac)) return false;
        // целая часть: либо "0", либо без ведущих нулей
        if (!($int === '0' || $int[0] !== '0')) return false;
        return true;
    } else {
        return false;
    }
}

function normalizeOperators(string $expr): string {
    // Приводим альтернативные операторы
    // ":" трактуем как деление
    return str_replace(':', '/', $expr);
}

function tokenize(string $expr): array {
    // Разбираем выражение в токены: числа и операторы (+ - * /) без скобок
    // Пробелы игнорируем
    $expr = normalizeOperators($expr);
    $expr = trim($expr);

    $tokens = [];
    $i = 0;
    $n = strlen($expr);

    while ($i < $n) {
        $ch = $expr[$i];

        if ($ch === ' ' || $ch === "\t" || $ch === "\r" || $ch === "\n") {
            $i++;
            continue;
        }

        // Число (учитываем унарный +/-, если стоит в начале или после оператора)
        if ($ch === '+' || $ch === '-') {
            $prev = end($tokens);
            $isUnary = (empty($tokens) || (is_string($prev) && in_array($prev, ['+', '-', '*', '/'], true)));
            if ($isUnary) {
                // начинаем собирать число со знаком
                $start = $i;
                $i++;
                // далее цифры и одна точка
                $num = $ch;
                while ($i < $n && (ctype_digit($expr[$i]) || $expr[$i] === '.')) {
                    $num .= $expr[$i];
                    $i++;
                }
                if (!isnum($num)) {
                    throw new RuntimeException("Ошибка: некорректное число '$num'");
                }
                $tokens[] = $num;
                continue;
            }
            // иначе это бинарный оператор
        }

        if (ctype_digit($ch)) {
            $num = '';
            while ($i < $n && (ctype_digit($expr[$i]) || $expr[$i] === '.')) {
                $num .= $expr[$i];
                $i++;
            }
            if (!isnum($num)) {
                throw new RuntimeException("Ошибка: некорректное число '$num'");
            }
            $tokens[] = $num;
            continue;
        }

        // Операторы
        if (in_array($ch, ['+', '-', '*', '/'], true)) {
            $tokens[] = $ch;
            $i++;
            continue;
        }

        // Скобки здесь запрещены — для них есть calculateSq
        if ($ch === '(' || $ch === ')') {
            throw new RuntimeException("Ошибка: скобки недопустимы в calculate() (используйте calculateSq)");
        }

        throw new RuntimeException("Ошибка: недопустимый символ ''");
    }

    return $tokens;
}

// ---------- Вычисление без скобок ----------
function calculate(string $expr): float {
    // Разбираем в токены
    $tokens = tokenize($expr);
    if (empty($tokens)) {
        throw new RuntimeException("Ошибка: пустое выражение");
    }

    // Проверка: выражение должно чередовать число/оператор, начинаться и заканчиваться числом
    if (!isnum($tokens[0]) || !isnum($tokens[count($tokens) - 1])) {
        throw new RuntimeException("Ошибка: выражение должно начинаться и заканчиваться числом");
    }
    for ($i = 1; $i < count($tokens) - 1; $i++) {
        $prevIsNum = isnum($tokens[$i - 1]);
        $currIsNum = isnum($tokens[$i]);
        $nextIsNum = isnum($tokens[$i + 1]);
        if ($currIsNum && ($prevIsNum || $nextIsNum)) {
            // два числа подряд — ошибка
            throw new RuntimeException("Ошибка: ожидается оператор между числами");
        }
        if (!$currIsNum && !$prevIsNum) {
            throw new RuntimeException("Ошибка: два оператора подряд");
        }
    }

    // 1-й проход: обработка * и /
    $stackVals = [];
    $stackOps = [];

    $i = 0;
    while ($i < count($tokens)) {
        $t = $tokens[$i];
        if (isnum($t)) {
            $stackVals[] = (float)$t;
            $i++;
        } else {
            $op = $t;
            if ($op === '*' || $op === '/') {
                if ($i + 1 >= count($tokens) || !isnum($tokens[$i + 1])) {
                    throw new RuntimeException("Ошибка: отсутствует операнд для '$op'");
                }
                $right = (float)$tokens[$i + 1];
                $left = array_pop($stackVals);
                if ($op === '*') {
                    $stackVals[] = $left * $right;
                } else {
                    if ($right == 0.0) throw new RuntimeException("Ошибка: деление на ноль");
                    $stackVals[] = $left / $right;
                }
                $i += 2; // пропустили оператор и правый операнд
            } else {
                // плюс/минус — отложим
                $stackOps[] = $op;
                $i++;
            }
        }
    }

    // 2-й проход: обработка + и -
    if (empty($stackVals)) {
        throw new RuntimeException("Ошибка: нет чисел для вычисления");
    }
    $result = $stackVals[0];
    $cursor = 1;
    foreach ($stackOps as $op) {
        if ($cursor >= count($stackVals)) {
            throw new RuntimeException("Ошибка: отсутствует операнд для '$op'");
        }
        $val = $stackVals[$cursor++];
        if ($op === '+') $result += $val;
        elseif ($op === '-') $result -= $val;
        else throw new RuntimeException("Ошибка: недопустимый оператор '$op'");
    }

    return $result;
}

// ---------- Вычисление со скобками ----------
function calculateSq(string $expr): float {
    $expr = normalizeOperators($expr);

    // Валидируем корректно (UTF-8)
    if (!preg_match('/^[0-9.+\-*\/() \t\r\n]+$/u', $expr)) {
        $len = mb_strlen($expr, 'UTF-8');
        for ($i = 0; $i < $len; $i++) {
            $ch = mb_substr($expr, $i, 1, 'UTF-8');
            if (!preg_match('/[0-9.+\-*\/() \t\r\n]/u', $ch)) {
                throw new RuntimeException("Ошибка: недопустимый символ '$ch'");
            }
        }
    }

    // Баланс скобок
    $balance = 0;
    for ($i = 0, $n = strlen($expr); $i < $n; $i++) {
        if ($expr[$i] === '(') $balance++;
        elseif ($expr[$i] === ')') $balance--;
        if ($balance < 0) throw new RuntimeException("Ошибка: лишняя закрывающая скобка");
    }
    if ($balance !== 0) throw new RuntimeException("Ошибка: несбалансированные скобки");

    // Рекурсивная обработка скобок …

    // Рекурсивная обработка самых внутренних скобок
    while (strpos($expr, '(') !== false) {
        $lastOpen = strrpos($expr, '(');
        $close = strpos($expr, ')', $lastOpen);
        if ($close === false) throw new RuntimeException("Ошибка: несбалансированные скобки");

        $inner = substr($expr, $lastOpen + 1, $close - $lastOpen - 1);
        $val = calculate($inner); // внутри скобок нет скобок (потому что берем самый внутренний участок)
        // Подставляем результат назад
        $expr = substr($expr, 0, $lastOpen) . (string)$val . substr($expr, $close + 1);
    }
    // Остаток — выражение без скобок
    return calculate($expr);
}

// ---------- Обработка формы ----------
$exprInput = '';
$resultMsg = '';
$hadPost = ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';

if ($hadPost) {
    $exprInput = trim($_POST['val'] ?? '');
    try {
        if ($exprInput === '') {
            throw new RuntimeException('Ошибка: пустое выражение');
        }
        $res = calculateSq($exprInput);
        // Формируем вывод результата
        // Целые выводим как целое, дробные — как есть
        $resultMsg = '<p class="result ok">Результат: ' . htmlspecialchars((fmod($res, 1.0) === 0.0) ? (string)(int)$res : (string)$res) . '</p>';
        $historyLine = $exprInput . ' = ' . ((fmod($res, 1.0) === 0.0) ? (string)(int)$res : (string)$res);
    } catch (Throwable $e) {
        $resultMsg = '<p class="result err">' . htmlspecialchars($e->getMessage()) . '</p>';
        $historyLine = $exprInput . ' = ' . $e->getMessage();
    }

    // Прочитаем историю "до" — чтобы текущий результат не отобразился, но был добавлен "после"
    $historyBefore = [];
    if (is_file($HISTORY_FILE)) {
        $historyBefore = file($HISTORY_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    }

    // Сохраним текущий в историю (для следующего открытия)
    // Записываем аккуратно построчно
    $fp = fopen($HISTORY_FILE, 'ab');
    if ($fp) {
        fwrite($fp, $historyLine . PHP_EOL);
        fclose($fp);
    }

    // Переиспользуем историю "до"
    $history = $historyBefore;
} else {
    // Просто читаем историю
    $history = [];
    if (is_file($HISTORY_FILE)) {
        $history = file($HISTORY_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    }
}

?>
<div class="wrap">
    <h1>Арифметический калькулятор</h1>

    <?php if ($hadPost): ?>
        <?= $resultMsg ?>
    <?php endif; ?>

    <form method="post" class="calc-form" autocomplete="off" novalidate>
        <label for="val"><span>Выражение:</span></label>
        <input type="text" id="val" name="val" required placeholder="Например: (12.5+3)*2-4/2" value="<?= htmlspecialchars($exprInput) ?>">
        <button type="submit">Вычислить</button>
        <p class="hint">
            Разрешены: числа без ведущих нулей (кроме 0 и 0.xxx), десятичная точка, операторы + - * / или :, скобки ( ).
        </p>
    </form>

</div>
</main>

<?php include __DIR__ . '/footer.php'; ?>

