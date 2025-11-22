<?php
require_once __DIR__ . '/config.php';
$title = "Вторая страница";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="<?php echo $BASE; ?>style.css">
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
    <h1>Вторая страница</h1>
    <h2>Пример динамического контента</h2>
    <p>Здесь мы продолжаем лабораторную работу. Контент формируется динамически.</p>

    <table>
        <?php echo "<tr><th>Параметр</th><th>Значение</th></tr>"; ?>
        <tr>
            <td><?php echo "Температура"; ?></td>
            <td><?php echo rand(15, 25)." °C"; ?></td>
        </tr>
    </table>

    <?php
    echo '<img src="/lab1/fotos/foto'.(date('s') % 2 + 1).'.jpg?'.time().'" alt="Меняющаяся фотография" width="300">';
    ?>
</main>

<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
