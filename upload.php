<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['plant_image'])) {
    $targetDir = 'uploads/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = time() . '_' . basename($_FILES["plant_image"]["name"]);
    $targetPath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["plant_image"]["tmp_name"], $targetPath)) {
        echo $targetPath; 
    } else {
        http_response_code(500);
        echo "Gagal menyimpan gambar.";
    }
} else {
    http_response_code(400);
    echo "Tidak ada file gambar yang diterima.";
}
