<?php
$conn = new mysqli("localhost", "root", "", "green_scan");
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT * FROM history ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Galeri Riwayat Scan</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      max-width: 1000px;
      margin: auto;
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
    }
    .galeri {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      background: #ffffffff;
    }
    .item {
      background: #2E7D32;
      padding: 10px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 2px 5px rgba(255, 255, 255, 0.1);
    }
    .item img {
      max-width: 100%;
      border-radius: 8px;
      height: 180px;
      object-fit: cover;
    }
    .item p {
      margin: 10px 0 5px;
      font-weight: bold;
      color: #fff;
    }
    .item small {
      color: #fff;
    }
  </style>
</head>
<body>
  <h2>Galeri Riwayat Scan Tanaman</h2>
  <div class="galeri">
    <?php if ($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="item">
          <img src="<?= htmlspecialchars($row['image']) ?>" alt="Tanaman">
          <p><?= htmlspecialchars($row['plant_name']) ?></p>
          <small><?= date('d M Y H:i', strtotime($row['created_at'])) ?></small>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>Tidak ada riwayat scan tanaman.</p>
    <?php endif; ?>
  </div>
</body>
</html>

<?php $conn->close(); ?>
