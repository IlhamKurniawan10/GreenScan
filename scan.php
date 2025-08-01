<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Scan Tanaman</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #fff;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 40px 20px;
    }

    h2 {
      color: #2e7d32;
      margin-bottom: 20px;
    }

    .scan-box {
      background: #ffffff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 100%;
      text-align: center;
    }

    video {
      width: 100%;
      border-radius: 8px;
      margin-bottom: 20px;
    }

    canvas {
      display: none;
    }

    button {
      background-color: #43a047;
      color: white;
      border: none;
      padding: 12px 24px;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
    }

    button:hover {
      background-color: #388e3c;
    }
  </style>
</head>
<body>
  <div class="scan-box">
    <h2>📷 Arahkan Kamera ke Tanaman</h2>
    <video id="camera" autoplay playsinline></video>
    <canvas id="snapshot"></canvas>
    <button onclick="captureAndUpload()">🔍 Scan Sekarang</button>
  </div>

  <script>
    const video = document.getElementById('camera');
    const canvas = document.getElementById('snapshot');
    const context = canvas.getContext('2d');

    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
      .then(stream => {
        video.srcObject = stream;
      })
      .catch(err => {
        alert('Gagal mengakses kamera: ' + err.message);
      });

    function captureAndUpload() {
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      context.drawImage(video, 0, 0, canvas.width, canvas.height);

      canvas.toBlob(function(blob) {
        const formData = new FormData();
        const fileName = 'tanaman_' + Date.now() + '.jpg';
        formData.append('plant_image', blob, fileName);

        fetch('upload.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.text())
        .then(filePath => {
          window.location.href = 'hasil.php?image=' + encodeURIComponent(filePath);
        })
        .catch(err => {
          alert('Gagal mengupload gambar: ' + err.message);
        });
      }, 'image/jpeg', 0.9);
    }
  </script>
</body>
</html>
