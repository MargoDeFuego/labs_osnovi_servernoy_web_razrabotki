<?php
function db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;

    $host = 'db';             // имя сервиса из docker-compose.yml
    $dbname = 'baseserve';    // MYSQL_DATABASE
    $user = 'user';           // MYSQL_USER
    $pass = 'pass';           // MYSQL_PASSWORD
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;port=3306;dbname=$dbname;charset=$charset";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        die("Ошибка подключения к базе данных: " . $e->getMessage());
    }

    return $pdo;
}
?>
