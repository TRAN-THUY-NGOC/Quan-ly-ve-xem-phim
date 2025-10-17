<?php
require '../config.php';
$stmt = $pdo->query("SELECT * FROM movies ORDER BY id DESC");
$movies = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý phim</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h2>Quản lý phim</h2>
  <a href="add_movie.php" class="btn btn-success mb-3">+ Thêm phim</a>
  <table class="table table-bordered">
    <tr><th>ID</th><th>Poster</th><th>Tên phim</th><th>Thể loại</th><th>Ngày chiếu</th><th>Hành động</th></tr>
    <?php foreach ($movies as $m): ?>
      <tr>
        <td><?=$m['id']?></td>
        <td>
  <img src="../uploads/posters/b_20250401144223.webp/<?= htmlspecialchars($m['poster']) ?>" 
       alt="Poster phim" style="height:60px; border-radius:6px;">
        </td>
        <td><?=$m['title']?></td>
        <td><?=$m['genre']?></td>
        <td><?=$m['release_date']?></td>
        <td>
          <a href="edit_movie.php?id=<?=$m['id']?>" class="btn btn-sm btn-primary">Sửa</a>
          <a href="delete_movie.php?id=<?=$m['id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa phim này?')">Xóa</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
</body>
</html>
