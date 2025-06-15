<?php
// pages/openings/view.php
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/functions.php';
include '../../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

// Açılış bilgilerini getir
$stmt = $pdo->prepare("SELECT o.*, u.username FROM openings o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
$stmt->execute([$id]);
$opening = $stmt->fetch();

if (!$opening) {
    echo "Açılış bulunamadı.";
    include '../../includes/footer.php';
    exit;
}

// Yorumları getir
$stmt = $pdo->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.opening_id = ? ORDER BY c.created_at DESC");
$stmt->execute([$id]);
$comments = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h2><?= htmlspecialchars($opening['name']) ?> (<?= htmlspecialchars($opening['eco_code']) ?>)</h2>

    <!-- Satranç tahtası -->
    <div class="row mt-4 justify-content-center">
        <div class="col-md-6 mb-4" style="position: relative; left: -60px;">
            <?php include '../../assets/css/chessboard.php'; ?>
        </div>

        <!-- Açılış detayları -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Açılış Detayları</h5>
                    <p class="card-text"><strong>Hamleler:</strong> <?= htmlspecialchars($opening['moves']) ?></p>
                    <p class="card-text"><strong>Açıklama:</strong> <?= nl2br(htmlspecialchars($opening['description'])) ?></p>
                    <p class="card-text"><strong>Ekleyen:</strong> <?= htmlspecialchars($opening['username']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <!-- Yorumlar -->
    <h3 class="mt-4">Yorumlar</h3>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="card mt-3">
            <div class="card-body">
                <form method="post" action="add_comment.php">
                    <input type="hidden" name="opening_id" value="<?= $id ?>">
                    <div class="mb-3">
                        <label for="comment" class="form-label">Yorumunuz:</label>
                        <textarea class="form-control" id="comment" name="content" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gönder</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info mt-3">
            Yorum yapmak için <a href="../../auth/login.php" class="alert-link">giriş yapmalısınız</a>.
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <?php foreach ($comments as $comment): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($comment['username']) ?></h5>
                    <p class="card-text"><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                    <p class="card-text"><small class="text-muted"><?= date('d.m.Y H:i', strtotime($comment['created_at'])) ?></small></p>
                    <?php if ($comment['user_id'] == $_SESSION['user_id']): ?>
                        <div class="text-end">
                            <a href="delete_comment.php?id=<?= $comment['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yorumu silmek istediğinize emin misiniz?')">Sil</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($comments)): ?>
            <div class="alert alert-secondary">Henüz yorum yapılmamış.</div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof applyMoves === 'function') {
        const moves = <?= json_encode(explode(' ', $opening['moves'])) ?>;
        applyMoves(moves);
    }
});
</script>

<?php include '../../includes/footer.php'; ?>
