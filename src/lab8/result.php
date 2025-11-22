<?php
require_once __DIR__ . '/config.php';
?>
<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '../../nav.php'; ?>

<?php
header('Content-Type: text/html; charset=UTF-8');

$text = $_POST['text'] ?? '';

echo "<h2>Результат анализа</h2>";

if (trim($text) === '') {
    echo "<p style='color:red'>Нет текста для анализа</p>";
    echo '<p><a href="index.php">Другой анализ</a></p>';
    exit;
}

// Исходный текст
echo "<p><em style='color:blue'>".htmlspecialchars($text)."</em></p>";

// Подсчёты
$length = mb_strlen($text);
$letters = preg_match_all('/[a-zA-Zа-яА-ЯёЁ]/u', $text);
$lower   = preg_match_all('/[a-zа-яё]/u', $text);
$upper   = preg_match_all('/[A-ZА-ЯЁ]/u', $text);
$punct   = preg_match_all('/[[:punct:]]/u', $text);
$digits  = preg_match_all('/[0-9]/u', $text);
$words   = preg_split('/[\s]+/u', trim($text));
$wordCount = count($words);

// Частота символов (без регистра)
$chars = [];
foreach (mb_str_split(mb_strtolower($text)) as $ch) {
    if (trim($ch) === '') continue;
    if (!isset($chars[$ch])) $chars[$ch] = 0;
    $chars[$ch]++;
}

// Частота слов
$wordFreq = [];
foreach ($words as $w) {
    $w = mb_strtolower($w);
    if ($w === '') continue;
    if (!isset($wordFreq[$w])) $wordFreq[$w] = 0;
    $wordFreq[$w]++;
}
ksort($wordFreq);

// Вывод таблицы
echo "<table border='1' cellpadding='5'>";
echo "<tr><td>Количество символов (включая пробелы)</td><td>$length</td></tr>";
echo "<tr><td>Количество букв</td><td>$letters</td></tr>";
echo "<tr><td>Строчные буквы</td><td>$lower</td></tr>";
echo "<tr><td>Заглавные буквы</td><td>$upper</td></tr>";
echo "<tr><td>Знаки препинания</td><td>$punct</td></tr>";
echo "<tr><td>Цифры</td><td>$digits</td></tr>";
echo "<tr><td>Количество слов</td><td>$wordCount</td></tr>";
echo "</table><br>";

// Таблица символов
echo "<h3>Вхождения символов</h3>";
echo "<table border='1' cellpadding='5'><tr><th>Символ</th><th>Количество</th></tr>";
foreach ($chars as $ch=>$cnt) {
    echo "<tr><td>".htmlspecialchars($ch)."</td><td>$cnt</td></tr>";
}
echo "</table><br>";

// Таблица слов
echo "<h3>Список слов</h3>";
echo "<table border='1' cellpadding='5'><tr><th>Слово</th><th>Количество</th></tr>";
foreach ($wordFreq as $w=>$cnt) {
    echo "<tr><td>".htmlspecialchars($w)."</td><td>$cnt</td></tr>";
}
echo "</table><br>";

echo '<p><a href="index.php">Другой анализ</a></p>';
?>

<?php include __DIR__ . '/footer.php'; ?>