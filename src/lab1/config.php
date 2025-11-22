<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Определяем базовый путь: для localhost/lab1_php → /lab1_php/, для lab1_php.test → /
$host = $_SERVER['HTTP_HOST'] ?? '';
$BASE = (strpos($host, 'localhost:8080') !== false) ? '/lab1/' : '/';
