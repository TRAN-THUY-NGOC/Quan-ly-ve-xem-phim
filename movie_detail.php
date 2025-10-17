<?php
require 'config.php';
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM movies WHERE id=?");
$stmt->execute([$id]);
$m = $stmt->fetch();
if (!$m) die("Không tìm thấy phim");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title><?=htmlspecialchars($m['title'])?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <a href="index.php" class="btn btn-link">&larr; Quay lại</a>
  <div class="row">
    <div class="col-md-4"><img src="<?=htmlspecialchars($m['poster'])?>" class="img-fluid rounded"></div>
    <div class="col-md-8">
      <h2><?=htmlspecialchars($m['title'])?></h2>
      <p><b>Thể loại:</b> <?=htmlspecialchars($m['genre'])?></p>
      <p><b>Khởi chiếu:</b> <?=$m['release_date']?></p>
      <p><b>Thời lượng:</b> <?=$m['duration']?> phút</p>
      <p><b>Diễn viên:</b><br><?=nl2br(htmlspecialchars($m['cast']))?></p>
      <p><b>Mô tả:</b><br><?=nl2br(htmlspecialchars($m['description']))?></p>
      <?php if ($m['trailer']): ?>
      <hr><h5>Trailer</h5>
      <video controls width="100%"><source src="<?=htmlspecialchars($m['trailer'])?>"></video>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
