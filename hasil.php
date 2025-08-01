<?php
$imagePath = $_GET['image'] ?? '';
include 'api/plant_id_api.php';
$result = identify_plant($imagePath);

$plantName = $result['name'] ?? 'Tidak diketahui';
$description = $result['description'] ?? 'Deskripsi tidak tersedia.';
$plantImage = $result['image'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Hasil Scan - <?= htmlspecialchars($plantName) ?></title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 700px;
      margin: auto;
      padding: 20px;
    }
    .hasil-img {
      border-radius: 12px;
      margin-bottom: 10px;
    }
    .deskripsi-box {
      text-align: justify;
      background: #e8f5e9;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 30px;
    }
    #chat-container {
      background: #f5f5f5;
      padding: 15px;
      border-radius: 10px;
      margin-top: 30px;
    }
    .chat-message {
      margin-bottom: 15px;
    }
    .chat-message.user {
      text-align: right;
      color: #2e7d32;
    }
    .chat-message.ai {
      text-align: left;
      background: #fff;
      border-left: 4px solid #4caf50;
      padding: 10px;
      border-radius: 6px;
    }
    #chat-form {
      margin-top: 20px;
      display: flex;
      gap: 10px;
      flex-direction: column;
    }
    textarea {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
    }
    button {
      align-self: flex-end;
      padding: 8px 16px;
      background: #4caf50;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    button:hover {
      background: #45a049;
    }
  </style>
</head>
<body>

  <h2>Hasil Identifikasi Tanaman</h2>

  <img src="<?= htmlspecialchars($imagePath) ?>" width="200" class="hasil-img" alt="Foto tanaman"><br>
  <?php if (!empty($plantImage)): ?>
    <img src="<?= htmlspecialchars($plantImage) ?>" width="200" class="hasil-img" alt="Tanaman teridentifikasi"><br>
  <?php endif; ?>

  <p><strong>Nama Tanaman:</strong> <?= htmlspecialchars($plantName) ?></p>

  <div class="deskripsi-box">
    <h3>Deskripsi Tanaman</h3>
    <p><?= $description ?></p>
  </div>

  <!-- Chat AI -->
  <div id="chat-container">
    <h3>Chat AI tentang <?= htmlspecialchars($plantName) ?>:</h3>

    <div id="chat-response"></div>

    <form id="chat-form">
      <input type="hidden" name="plant" id="plant" value="<?= htmlspecialchars($plantName) ?>">
      <textarea name="question" id="question" rows="3" placeholder="Tanyakan sesuatu..." required></textarea>
      <button type="submit">Kirim</button>
    </form>
  </div>

  <script>
    document.getElementById('chat-form').addEventListener('submit', function (e) {
      e.preventDefault();

      const textarea = document.getElementById('question');
      const plant = document.getElementById('plant').value;
      const question = textarea.value.trim();
      const chatBox = document.getElementById('chat-response');

      if (question === '') return;

      chatBox.innerHTML += `
        <div class="chat-message user"><strong>Kamu:</strong> ${question}</div>
      `;

      chatBox.innerHTML += `
        <div class="chat-message ai"><em>⏳ AI sedang menjawab...</em></div>
      `;

      fetch('ai_chat.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'question=' + encodeURIComponent(question) + '&plant=' + encodeURIComponent(plant)
      })
      .then(response => response.text())
      .then(data => {
        const loaders = chatBox.querySelectorAll('em');
        loaders.forEach(e => e.parentElement.remove());

        chatBox.innerHTML += `
          <div class="chat-message ai"><strong>AI:</strong><br>${data}</div>
        `;
        chatBox.scrollTop = chatBox.scrollHeight;
      })
      .catch(err => {
        chatBox.innerHTML += `<div class="chat-message ai">❌ Gagal menghubungi AI.</div>`;
      });

      textarea.value = '';
    });
  </script>
</body>
</html>
