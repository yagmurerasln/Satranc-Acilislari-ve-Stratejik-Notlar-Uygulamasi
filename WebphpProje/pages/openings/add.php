<?php 
require_once '../../includes/config.php'; 
require_once '../../includes/db.php'; 
require_once '../../includes/functions.php'; 
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = trim($_POST['name']);
    $eco_code = trim($_POST['eco_code']);
    $moves = trim($_POST['moves']);
    $description = trim($_POST['description']);

    if ($name && $eco_code && $moves) {
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM openings WHERE user_id = ? AND (name = ? OR moves = ?)");
        $checkStmt->execute([$user_id, $name, $moves]);
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            $error = "Bu açılış zaten eklenmiş.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO openings (user_id, name, eco_code, moves, description) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $name, $eco_code, $moves, $description]);
            header("Location: index.php");
            exit;
        }
    } else {
        $error = "Lütfen gerekli alanları doldurun.";
    }
}


include '../../includes/header.php';
?>

<link rel="stylesheet" href="../../assets/css/style.css">

<h2>Yeni Açılış Ekle</h2>

<?php if (!empty($error)): ?>
<p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<!-- Satranç tahtasını buraya ekliyoruz -->

<body style="padding-top: 60px;"> <!-- Header yüksekliği kadar üst boşluk -->
<div class="container">
    <!-- Sol Taraf: Satranç Tahtası -->
    <div class="chessboard-wrapper">
        <?php include '../../assets/css/chessboard.php'; ?>
    </div>

    <!-- Sağ Taraf: Form -->
    <form method="post" action="add.php" onsubmit="return validateForm();">
        <h2>Yeni Açılış Ekle</h2>

        <label for="moves">Hamleler (örn: e2e4 e7e5 g1f3 b8c6):</label>
        <div style="display: flex; gap: 10px; align-items: center;">
            <input type="text" id="moves" name="moves" required style="flex: 1;">
            <button type="button" onclick="fetchOpeningInfo()">Açılışı Getir</button>
        </div>

        <label for="name">Açılış Adı:</label>
        <input type="text" id="name" name="name" readonly required>

        <label for="eco_code">ECO Kodu:</label>
        <input type="text" id="eco_code" name="eco_code" readonly required>

        <label for="description">Açıklama:</label>
        <textarea id="description" name="description" rows="4"></textarea>

        <button type="submit" style="margin-top: 15px;">Ekle</button>
    </form>
</div>

<script>
function fetchOpeningInfo() {
  const movesInput = document.getElementById('moves').value.trim();
  if (!movesInput) {
    alert("Lütfen hamleleri girin.");
    return;
  }

  let moves = movesInput
      .split(/[\s,]+/)
      .map(m => m.toLowerCase())
      .filter(m => m.length === 4 && /^[a-h][1-8][a-h][1-8]$/.test(m));

  if(moves.length === 0) {
    alert("Geçerli hamleleri UCI formatında girin. Örnek: e2e4 e7e5 g1f3 b8c6");
    return;
  }

  const moveList = moves.join(',');
  const url = `https://explorer.lichess.ovh/masters?play=${encodeURIComponent(moveList)}`;

  fetch(url)
    .then(res => {
      if(!res.ok) throw new Error(`HTTP status ${res.status}`);
      return res.json();
    })
    .then(data => {
      if(data.opening) {
        document.getElementById('name').value = data.opening.name;
        document.getElementById('eco_code').value = data.opening.eco;

        // Eğer chessboard.js'de applyMoves fonksiyonun varsa burada çağırabilirsin
        if(typeof applyMoves === "function") {
          applyMoves(moves);
        }

      } else {
        alert("Açılış bulunamadı.");
        document.getElementById('name').value = '';
        document.getElementById('eco_code').value = '';
      }
    })
    .catch(err => {
      console.error('API hatası:', err);
      alert("API çağrısında hata oluştu: " + err.message);
    });
}

function validateForm() {
  const name = document.getElementById('name').value.trim();
  const eco = document.getElementById('eco_code').value.trim();
  const moves = document.getElementById('moves').value.trim();

  if (!moves) {
    alert("Lütfen hamleleri girin.");
    return false;
  }
  if (!name) {
    alert("Lütfen önce 'Açılışı Getir' butonuna tıklayarak açılış bilgisini alın.");
    return false;
  }
  if (!eco) {
    alert("ECO kodu boş olamaz.");
    return false;
  }
  return true;
}
</script>

<?php include '../../includes/footer.php'; ?>
