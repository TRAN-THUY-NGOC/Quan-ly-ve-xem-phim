<?php
$DB_HOST = 'localhost';
$DB_NAME = 'movie_booking';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

define('POSTER_DIR', __DIR__ . '/uploads/posters/');
define('TRAILER_DIR', __DIR__ . '/uploads/trailers/');
?>
