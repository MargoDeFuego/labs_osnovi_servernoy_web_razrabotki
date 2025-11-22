<?php
require_once __DIR__ . '/config.php';
$title = "Главная страница";
$current = basename($_SERVER['PHP_SELF']);
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
    <nav>
        <a href="/">Главная</a>
        <a href="/lab1/index.php" class="<?php echo $current==='index.php'?'selected_menu':''; ?>">Первая страница</a>
        <a href="/lab1/page2.php" class="<?php echo $current==='page2.php'?'selected_menu':''; ?>">Вторая страница</a>
        <a href="/lab1/page3.php" class="<?php echo $current==='page3.php'?'selected_menu':''; ?>">Третья страница</a>
    </nav>
    <h2>Лабораторная работа №1</h2>
    <h1>Главная страница</h1>
    <h2>Простейшая программа на PHP</h2>
    <p>Это пример лабораторной работы: часть HTML-кода формируется динамически средствами PHP.</p>

    <table>
        <?php echo "<tr><th>Колонка 1</th><th>Колонка 2</th><th>Колонка 3</th></tr>"; ?>
        <tr>
            <td><?php echo "Данные 1"; ?></td>
            <td><?php echo "Данные 2"; ?></td>
            <td><?php echo "Данные 3"; ?></td>
        </tr>
    </table>

    <?php
    echo '<img src="/lab1/fotos/foto'.(date('s') % 2 + 1).'.jpg?'.time().'" alt="Меняющаяся фотография" width="300">';
    ?>
</main>

<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
