<?php
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/functions.php';
include '../../includes/header.php';

// Tüm açılışları çek (ekleyen kullanıcı ile birlikte)
$stmt = $pdo->query("SELECT o.*, u.username FROM openings o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
$openings = $stmt->fetchAll();
?>

<div class="container">
    <h2>Açılışlar</h2>

    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="add.php" class="btn btn-primary mb-3">➕ Yeni Açılış Ekle</a>
    <?php endif; ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Açılış İsmi</th>
                <th>ECO Kodu</th>
                <th>Hamleler</th>
                <th>Ekleyen Kullanıcı</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($openings as $open): ?>
            <tr>
                <td><?= htmlspecialchars($open['name']) ?></td>
                <td><?= htmlspecialchars($open['eco_code']) ?></td>
                <td><?= htmlspecialchars($open['moves']) ?></td>
                <td><?= htmlspecialchars($open['username']) ?></td>
                <td>
                    <a href="view.php?id=<?= $open['id'] ?>" class="btn btn-sm btn-info">Görüntüle</a>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $open['user_id']): ?>
                        <a href="edit.php?id=<?= $open['id'] ?>" class="btn btn-sm btn-warning">Düzenle</a>
                        <a href="delete.php?id=<?= $open['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>