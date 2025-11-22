<?php
require_once __DIR__ . '/config.php';
$current = $_SERVER['REQUEST_URI'];

?>

<header>
    
    <nav>
        <a href="/lab1/index.php" class="<?php echo $current==='/lab1/index.php'?'selected_menu':''; ?>">Лаб 1</a>
        <a href="/lab2/index.php" class="<?php echo $current==='/lab2/index.php'?'selected_menu':''; ?>">Лаб 2</a>
        <a href="/lab3/index.php" class="<?php echo $current==='/lab3/index.php'?'selected_menu':''; ?>">Лаб 3</a>
        <a href="/lab4/index.php" class="<?php echo $current==='/lab4/index.php'?'selected_menu':''; ?>">Лаб 4</a>
        <a href="/lab5/index.php" class="<?php echo $current==='/lab5/index.php'?'selected_menu':''; ?>">Лаб 5</a>
        <a href="/lab6/index.php" class="<?php echo $current==='/lab6/index.php'?'selected_menu':''; ?>">Лаб 6</a>
        <a href="/lab7/index.php" class="<?php echo $current==='/lab7/index.php'?'selected_menu':''; ?>">Лаб 7</a>
        <a href="/lab8/index.php" class="<?php echo $current==='/lab8/index.php'?'selected_menu':''; ?>">Лаб 8</a>
        <a href="/lab9/index.php" class="<?php echo $current==='/lab9/index.php'?'selected_menu':''; ?>">Лаб 9</a>
        <a href="/lab10/index.php" class="<?php echo $current==='/lab10/index.php'?'selected_menu':''; ?>">Лаб 10</a>
    </nav>
</header>