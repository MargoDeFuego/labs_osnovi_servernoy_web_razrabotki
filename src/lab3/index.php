<?php
require_once __DIR__ . '/config.php';
$title = "Малявкина Маргарита Александровна Гр 241-321 Вариант 3";
session_start();

// Инициализация хранилища и счётчика
if (!isset($_SESSION['STORE'])) {
    $_SESSION['STORE'] = "";
    $_SESSION['COUNT'] = 0;
}

// Обработка нажатий
if (isset($_GET['digit'])) {
    $_SESSION['STORE'] .= $_GET['digit'];
    $_SESSION['COUNT']++;
}

if (isset($_GET['reset'])) {
    $_SESSION['STORE'] = "";
    $_SESSION['COUNT']++;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include __DIR__ . '/header.php';  ?>

<main>
<h2>Лабораторная работа №3</h2>
<h1>Виртуальная клавиатура.</h1>
<div class="lock">
    <!-- Окно просмотра результата -->
    <div class="display"><?php echo $_SESSION['STORE']; ?></div>

    <!-- Первый ряд кнопок -->
    <div class="row">
        <a href="?digit=1" class="btn">1</a>
        <a href="?digit=2" class="btn">2</a>
        <a href="?digit=3" class="btn">3</a>
        <a href="?digit=4" class="btn">4</a>
        <a href="?digit=5" class="btn">5</a>
    </div>

    <!-- Второй ряд кнопок -->
    <div class="row">
        <a href="?digit=6" class="btn">6</a>
        <a href="?digit=7" class="btn">7</a>
        <a href="?digit=8" class="btn">8</a>
        <a href="?digit=9" class="btn">9</a>
        <a href="?digit=0" class="btn">0</a>
    </div>

    <!-- Кнопка сброса -->
    <a href="?reset=1" class="reset">СБРОС</a>
</div>

</main>

<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
