<?php
require_once __DIR__ . '/config.php';
?>
<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '../../nav.php'; ?>

<main>
<h2>Лабораторная работа №6</h2>
<h1>Тест математических знаний</h1>

<div class="wrap">
<main>

<?php
// -----------------------------
// Вспомогательные функции
// -----------------------------

// Случайное число от 0 до 100 (с дробной частью)
function rand_float_0_100(): float {
    return mt_rand(0, 100) + mt_rand(0, 99)/100;
}

// Приведение строки к числу (поддержка точки и запятой)
function normalize_number(string $raw): ?float {
    $s = str_replace(',', '.', trim($raw));
    return is_numeric($s) ? (float)$s : null;
}

// Вычисление результата выбранной задачи
function compute_result(string $task, float $A, float $B, float $C): ?float {
    switch ($task) {
        case 'triangle_area_heron':
            // Формула Герона
            $p = ($A + $B + $C) / 2;
            $under = $p * ($p - $A) * ($p - $B) * ($p - $C);
            return $under >= 0 ? sqrt($under) : null;

        case 'triangle_perimeter':
            return $A + $B + $C;

        case 'box_volume':
            return $A * $B * $C;

        case 'mean_arithmetic':
            return ($A + $B + $C) / 3;

        case 'mean_geometric':
            if ($A < 0 || $B < 0 || $C < 0) return null;
            return pow($A * $B * $C, 1/3);

        case 'mean_harmonic':
            if ($A == 0 || $B == 0 || $C == 0) return null;
            return 3.0 / (1/$A + 1/$B + 1/$C);

        case 'sum':
            return $A + $B + $C;

        case 'max':
            return max($A, $B, $C);

        default:
            return null;
    }
}

// Форматирование числа для вывода
function format_num(?float $x): string {
    return $x === null ? 'н/д' : number_format($x, 4, '.', '');
}

// -----------------------------
// Инициализация переменных
// -----------------------------

$repeat    = isset($_GET['repeat']);
$submit    = isset($_POST['submit']);
$view_mode = $_POST['view_mode'] ?? $_GET['view_mode'] ?? 'browser';

// Генерация случайных чисел при первой загрузке или повторе
if ($repeat || !$submit) {
    $A = rand_float_0_100();
    $B = rand_float_0_100();
    $C = rand_float_0_100();
} else {
    $A = $_POST['A'];
    $B = $_POST['B'];
    $C = $_POST['C'];
}

// Данные пользователя
$fio          = $_POST['fio']   ?? ($_GET['fio']   ?? '');
$group        = $_POST['group'] ?? ($_GET['group'] ?? '');
$about        = $_POST['about'] ?? '';
$task         = $_POST['task']  ?? 'triangle_area_heron';
$answer_raw   = $_POST['answer'] ?? '';
$email_checked = isset($_POST['send_email']);
$email        = $_POST['email'] ?? '';

// -----------------------------
// Обработка теста
// -----------------------------

$computed = null;
$passed   = null;

if ($submit) {
    $A_num = normalize_number($A);
    $B_num = normalize_number($B);
    $C_num = normalize_number($C);

    $computed = compute_result($task, $A_num, $B_num, $C_num);
    $answer_num = normalize_number($answer_raw);

    if ($computed !== null && $answer_num !== null) {
        $passed = abs($computed - $answer_num) < 1e-6;
    }
}
?>


<?php if(!$submit || $repeat): ?>
    <!-- Форма -->
    <form class="form" method="post" action="">
        <label>ФИО</label><input type="text" name="fio" value="<?=htmlspecialchars($fio)?>">
        <label>Группа</label><input type="text" name="group" value="<?=htmlspecialchars($group)?>">
        <label>A</label><input type="text" name="A" value="<?=htmlspecialchars($A)?>">
        <label>B</label><input type="text" name="B" value="<?=htmlspecialchars($B)?>">
        <label>C</label><input type="text" name="C" value="<?=htmlspecialchars($C)?>">

        <label>Задача</label>
        <select name="task">
            <option value="triangle_area_heron">Площадь треугольника</option>
            <option value="triangle_perimeter">Периметр треугольника</option>
            <option value="box_volume">Объем параллелепипеда</option>
            <option value="mean_arithmetic">Среднее арифметическое</option>
            <option value="mean_geometric">Среднее геометрическое</option>
            <option value="mean_harmonic">Среднее гармоническое</option>
            <option value="sum">Сумма</option>
            <option value="max">Максимум</option>
        </select>

        <label>Ваш ответ</label><input type="text" name="answer" value="<?=htmlspecialchars($answer_raw)?>">

        <div class="checkbox-row">
            <input type="checkbox" id="send_email" name="send_email" <?=$email_checked?'checked':''?>>
            <label for="send_email">Отправить результат по e‑mail</label>
        </div>
        <label id="email_label" class="<?=$email_checked?'':'hidden'?>">Ваш e‑mail</label>
        <input class="<?=$email_checked?'':'hidden'?>" type="text" name="email" value="<?=htmlspecialchars($email)?>">

        <label>О себе</label><textarea name="about"><?=htmlspecialchars($about)?></textarea>

        <label>Режим</label>
        <select name="view_mode">
            <option value="browser">Для браузера</option>
            <option value="print">Для печати</option>
        </select>

        <div class="actions">
            <button class="submit-btn" type="submit" name="submit" value="1">Проверить</button>
        </div>
    </form>

    <script>
    const cb=document.getElementById('send_email');
    const emailLabel=document.getElementById('email_label');
    const emailInput=document.querySelector('input[name="email"]');
    function toggleEmail(){let show=cb.checked;emailLabel.classList.toggle('hidden',!show);emailInput.classList.toggle('hidden',!show);}
    cb.addEventListener('change',toggleEmail);toggleEmail();
    </script>

<?php else: ?>
    <!-- Отчет -->
    <div class="section <?=$view_mode==='print'?'print':''?>">
        <h2>Результаты теста</h2>
        <p><strong>ФИО:</strong> <?=htmlspecialchars($fio)?></p>
        <p><strong>Группа:</strong> <?=htmlspecialchars($group)?></p>
        <p><strong>О себе:</strong><br><?=nl2br(htmlspecialchars($about))?></p>
        <p><strong>Тип задачи:</strong> <?=$task?></p>
        <p><strong>Входные данные:</strong> A=<?=$A?>, B=<?=$B?>, C=<?=$C?></p>
        <p><strong>Ваш ответ:</strong> <?=htmlspecialchars($answer_raw)?></p>
        <p><strong>Результат программы:</strong> <?=format_num($computed)?></p>
        <p><strong>Итог:</strong> <?=$passed?'<span class="ok">Тест пройден</span>':'<span class="err">Ошибка: тест не пройден</span>'?></p>

        <?php if($email_checked && $email!==''): ?>
            <p class="ok">Результаты теста были автоматически отправлены на e‑mail: <?=htmlspecialchars($email)?></p>
        <?php endif; ?>

        <p><em>Дата и время:</em> <?=date('d.m.Y H:i:s')?></p>
        <p><em>Режим:</em> <?=$view_mode==='print'?'Версия для печати':'Версия для браузера'?></p>
    </div>

    <?php if($view_mode==='browser'): ?>
        <?php $repeat_url = sprintf('?repeat=1&fio=%s&group=%s&view_mode=browser',urlencode($fio),urlencode($group)); ?>
        <div class="section">
            <a class="btn-link" href="<?=$repeat_url?>">Повторить тест</a>
        </div>
    <?php endif; ?>
<?php endif; ?>

</div>

</main>

<?php include __DIR__ . '/footer.php'; ?>

