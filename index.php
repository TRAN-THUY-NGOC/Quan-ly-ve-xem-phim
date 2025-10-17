<?php
require 'config.php';

$q = $_GET['q'] ?? '';
$genre = $_GET['genre'] ?? '';
$date = $_GET['release_date'] ?? '';

$sql = "SELECT * FROM movies WHERE 1=1";
$params = [];
if ($q) { $sql .= " AND title LIKE :q"; $params[':q'] = "%$q%"; }
if ($genre) { $sql .= " AND genre LIKE :g"; $params[':g'] = "%$genre%"; }
if ($date) { $sql .= " AND release_date = :d"; $params[':d'] = $date; }

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$movies = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Danh sách phim</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.poster {height:300px;object-fit:cover;border-radius:8px;}
</style>
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">🎬 Movie Booking</a>
    <a href="admin/movies.php" class="btn btn-outline-light">Quản lý phim</a>
  </div>
</nav>
<div class="container py-4">
  <form class="row g-2 mb-4" method="get">
    <div class="col-md-4"><input type="text" name="q" class="form-control" placeholder="Tên phim" value="<?=htmlspecialchars($q)?>"></div>
    <div class="col-md-3"><input type="text" name="genre" class="form-control" placeholder="Thể loại" value="<?=htmlspecialchars($genre)?>"></div>
    <div class="col-md-3"><input type="date" name="release_date" class="form-control" value="<?=htmlspecialchars($date)?>"></div>
    <div class="col-md-2"><button class="btn btn-primary w-100">Tìm</button></div>
  </form>

  <div class="row g-3">
    <?php if (empty($movies)): ?>
      <div class="alert alert-warning">Không tìm thấy phim nào.</div>
    <?php endif; ?>

    <?php foreach ($movies as $m): ?>
      <div class="col-md-3">
        <div class="card h-100">
          <img src="<?=htmlspecialchars($m['poster'])?>" class="poster card-img-top" alt="">
          <div class="card-body">
            <h5 class="card-title"><?=htmlspecialchars($m['title'])?></h5>
            <p class="small mb-2"><?=htmlspecialchars($m['genre'])?></p>
            <a href="movie_detail.php?id=<?=$m['id']?>" class="btn btn-sm btn-primary">Chi tiết</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>
