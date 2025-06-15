<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
ob_start();

require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/functions.php';
include '../../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM openings WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);
$opening = $stmt->fetch();

if (!$opening) {
    echo "Bu açılışı düzenleme yetkiniz yok veya açılış bulunamadı.";
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim($_POST['description']);
    if ($description !== '') {
        $stmt = $pdo->prepare("UPDATE openings SET description = ? WHERE id = ?");
        $stmt->execute([$description, $id]);
        header("Location: index.php?id=$id");
        exit;
    } else {
        $error = "Açıklama boş olamaz.";
    }
}
?>

<!-- Stil -->
<link rel="stylesheet" href="../../assets/css/style.css">


<!-- Başlık -->
<h2 style="text-align: center;">Açılış Açıklamasını Güncelle</h2>

<!-- Ana Alan -->
<div class="container">
    <!-- Sol: Tahta -->
    <div class="chessboard-wrapper">
        <?php include '../../assets/css/chessboard.php'; ?>
    </div>

    <!-- Sağ: Bilgiler ve Form -->
    <div class="form-section">
        <p><strong>Açılış İsmi:</strong> <?= htmlspecialchars($opening['name']) ?></p>
        <p><strong>ECO Kodu:</strong> <?= htmlspecialchars($opening['eco_code']) ?></p>
        <p><strong>Hamleler:</strong> <?= htmlspecialchars($opening['moves']) ?></p>

        <?php if ($error): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <label>Açıklama:</label><br>
            <textarea name="description" rows="5"><?= htmlspecialchars($opening['description']) ?></textarea><br><br>
            <button type="submit">Kaydet</button>
        </form>
    </div>
</div>

<!-- JS: Hamleleri tahtaya uygula -->
<script>
// PHP'den gelen hamleler
const moveString = <?= json_encode($opening['moves']) ?>;
const moveList = moveString.trim().split(/\s+/);

document.addEventListener('DOMContentLoaded', function() {
  if (typeof applyMoves === 'function') {
    applyMoves(moveList);
  }
});
</script>

<?php include '../../includes/footer.php'; ?>
<?php ob_end_flush(); ?>
