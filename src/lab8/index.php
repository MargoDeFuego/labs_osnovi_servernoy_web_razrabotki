<?php
require_once __DIR__ . '/config.php';
?>
<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '../../nav.php'; ?>

<main>
<h2>Лабораторная работа №8</h2>
<h1>Введите текст для анализа</h1>
  <form method="post" action="result.php">
    <textarea name="text" rows="11" cols="115"></textarea><br><br>
    <button type="submit">Анализировать</button>
  </form>
</main>

<?php include __DIR__ . '/footer.php'; ?>

