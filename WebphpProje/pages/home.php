<?php
require_once '../includes/header.php';
?>

<style>
.auth-buttons {
    display: flex !important;
    flex-direction: column !important;
    align-items: center;
    gap: 10px;
    text-align: center;
}

.auth-buttons p {
    margin: 0;
    font-size: 1.2em;
}

.btn-purple {
    background-color: rebeccapurple !important;
    color: white !important;
    border: none !important;
}

.btn-purple:hover {
    background-color: indigo !important;
    color: white !important;
}

</style>

<div class="home-container">
    <div class="hero-section">
        <h1>Satranç Açılışları ve Strateji Notları</h1>
        
        <div class="auth-buttons">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>pages/auth/login.php" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Giriş Yap
                </a>
                <a href="<?= BASE_URL ?>pages/auth/register.php" class="btn btn-secondary">
                    <i class="fas fa-user-plus"></i> Kayıt Ol
                </a>
            <?php else: ?>
                <p>Tekrar Hoşgeldiniz: <?= htmlspecialchars($_SESSION['username']) ?></p>

                <a href="/WebphpProje/duzenle.php?id=<?= $_SESSION['user_id'] ?>" 
                   class="btn btn-purple" 
                   style="width: max-content;">
                   <i class="fas fa-user-edit"></i> Bilgilerini Düzenle
                </a>

                <a href="<?= BASE_URL ?>pages/openings/index.php" class="btn btn-success">
                    <i class="fas fa-chess"></i> Açılışlara Git
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
