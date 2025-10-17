<?php
require '../config.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM movies WHERE id=?");
$stmt->execute([$id]);
$m = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $genre = $_POST['genre'];
  $desc = $_POST['description'];
  $duration = $_POST['duration'];
  $cast = $_POST['cast'];
  $date = $_POST['release_date'];

  $poster = $m['poster'];
  if (!empty($_FILES['poster']['name'])) {
    $ext = pathinfo($_FILES['poster']['name'], PATHINFO_EXTENSION);
    $name = uniqid('poster_').".$ext";
    move_uploaded_file($_FILES['poster']['tmp_name'], "../uploads/posters/$name");
    $poster = "uploads/posters/$name";
  }

  $trailer = $m['trailer'];
  if (!empty($_FILES['trailer']['name'])) {
    $ext = pathinfo($_FILES['trailer']['name'], PATHINFO_EXTENSION);
    $name = uniqid('trailer_').".$ext";
    move_uploaded_file($_FILES['trailer']['tmp_name'], "../uploads/trailers/$name");
    $trailer = "uploads/trailers/$name";
  } elseif (!empty($_POST['trailer_url'])) {
    $trailer = $_POST['trailer_url'];
  }

  $stmt = $pdo->prepare("UPDATE movies SET title=?,genre=?,description=?,duration=?,cast=?,poster=?,trailer=?,release_date=? WHERE id=?");
  $stmt->execute([$title,$genre,$desc,$duration,$cast,$poster,$trailer,$date,$id]);
  header("Location: movies.php");
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Sửa phim</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h3>Sửa phim</h3>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-2"><label>Tên phim</label><input name="title" class="form-control" value="<?=$m['title']?>"></div>
    <div class="mb-2"><label>Thể loại</label><input name="genre" class="form-control" value="<?=$m['genre']?>"></div>
    <div class="mb-2"><label>Ngày chiếu</label><input type="date" name="release_date" class="form-control" value="<?=$m['release_date']?>"></div>
    <div class="mb-2"><label>Thời lượng (phút)</label><input type="number" name="duration" class="form-control" value="<?=$m['duration']?>"></div>
    <div class="mb-2"><label>Diễn viên</label><textarea name="cast" class="form-control"><?=$m['cast']?></textarea></div>
    <div class="mb-2"><label>Mô tả</label><textarea name="description" class="form-control"><?=$m['description']?></textarea></div>
    <div class="mb-2"><label>Poster</label><input type="file" name="poster" class="form-control"><br><img src="../<?=$m['poster']?>" style="height:120px"></div>
    <div class="mb-2"><label>Trailer (file hoặc URL)</label><input type="file" name="trailer" class="form-control mb-1"><input type="text" name="trailer_url" class="form-control" placeholder="Hoặc URL" value="<?=$m['trailer']?>"></div>
    <button class="btn btn-success">Cập nhật</button>
    <a href="movies.php" class="btn btn-secondary">Hủy</a>
  </form>
</div>
</body>
</html>
