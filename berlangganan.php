<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Langganan Premium</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f4f8;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 500px;
      margin: 60px auto;
      background: #fff;
      padding: 30px 25px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.05);
      text-align: center;
    }

    h2 {
      color: #2c3e50;
      margin-bottom: 10px;
    }

    .price {
      font-size: 22px;
      font-weight: bold;
      color: #27ae60;
      margin-bottom: 20px;
    }

    ul {
      list-style-type: none;
      padding: 0;
      margin-bottom: 25px;
      text-align: left;
    }

    ul li {
      margin: 10px 0;
      padding-left: 20px;
      position: relative;
    }

    ul li::before {
      content: '✓';
      position: absolute;
      left: 0;
      color: #27ae60;
      font-weight: bold;
    }

    .btn {
      background-color: #27ae60;
      color: white;
      padding: 12px 25px;
      text-decoration: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: bold;
      display: inline-block;
      transition: background-color 0.3s;
    }

    .btn:hover {
      background-color: #219150;
    }

    @media (max-width: 600px) {
      .container {
        margin: 30px 15px;
        padding: 25px 20px;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Langganan Premium</h2>
    <p class="price">Rp 40.000 / bulan</p>
    <p><strong>Premium:</strong></p>
    <ul>
      <li>Analisis tanaman tanpa gangguan iklan</li>
      <li>Data tanaman lebih lengkap</li>
      <li>Chat AI tak terbatas</li>
    </ul>
    <p><strong>Pilih metode pembayaran:</strong></p>
    <a href="https://wa.me/6281234560" target="_blank" class="btn">Pilih Menu Bayar</a>
  </div>

</body>
</html>
