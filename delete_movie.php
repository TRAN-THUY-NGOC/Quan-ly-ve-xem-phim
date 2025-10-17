<?php
require '../config.php';
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM movies WHERE id=?");
$stmt->execute([$id]);
header("Location: movies.php");
