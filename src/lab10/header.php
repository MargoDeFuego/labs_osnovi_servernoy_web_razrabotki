<?php
require_once __DIR__ . '/config.php';
$current = basename($_SERVER['PHP_SELF']);
$title = "Малявкина Маргарита Александровна Гр 241-321 Вариант 3";
?>
<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding("UTF-8");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

