<?php
require_once __DIR__ . '/config.php';
$title = "Главная страница";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>

<main>
    <h1>Главная страница лабораторных работ</h1>
</main>

<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
