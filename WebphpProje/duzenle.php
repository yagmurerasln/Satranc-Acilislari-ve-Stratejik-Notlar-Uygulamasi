<?php
require_once 'includes/db.php';
include 'includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: pages/auth/profile.php");
    exit;
}

$id = intval($_GET['id']);

// Kullanıcı bilgilerini çek
$stmt = $pdo->prepare("SELECT username, email, password FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<div class='alert alert-danger'>Kullanıcı bulunamadı.</div>";
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $new_password_confirm = $_POST['new_password_confirm'] ?? '';

    if ($username === '' || $email === '') {
        $error = "Kullanıcı adı ve email boş bırakılamaz.";
    } else {
        // Email başka kullanıcıda var mı kontrol et
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $id]);
        if ($stmt->rowCount() > 0) {
            $error = "Bu email zaten başka bir kullanıcı tarafından kullanılıyor.";
        } else {
            // Şifre değiştirme kontrolleri
            if (!empty($old_password) || !empty($new_password) || !empty($new_password_confirm)) {
                if (empty($old_password) || empty($new_password) || empty($new_password_confirm)) {
                    $error = "Şifre değiştirmek için tüm alanları doldurmalısınız.";
                } elseif (!password_verify($old_password, $user['password'])) {
                    $error = "Eski şifre yanlış.";
                } elseif ($new_password !== $new_password_confirm) {
                    $error = "Yeni şifre ve tekrar aynı olmalı.";
                } elseif (strlen($new_password) < 6) {
                    $error = "Yeni şifre en az 6 karakter olmalı.";
                }
            }

            if (!$error) {
                if (!empty($new_password)) {
                    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
                    $stmt->execute([$username, $email, $new_password_hashed, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
                    $stmt->execute([$username, $email, $id]);
                }

                // ✅ Oturum bilgisi de güncelleniyor
                $_SESSION['username'] = $username;

                // ✅ Yönlendirme
                header("Location: pages/auth/profile.php?id=$id");
                exit;
            }
        }
    }
}
?>

<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4 text-center">Kullanıcı Düzenle</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Kullanıcı Adı:</label>
            <input type="text" id="username" name="username" class="form-control" 
                   value="<?= htmlspecialchars($_POST['username'] ?? $user['username']) ?>" required>
        </div>

        <div class="mb-4">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" class="form-control" 
                   value="<?= htmlspecialchars($_POST['email'] ?? $user['email']) ?>" required>
        </div>

        <hr>

        <h5>Şifre Değiştir</h5>

        <div class="mb-3">
            <label for="old_password" class="form-label">Eski Şifre:</label>
            <input type="password" id="old_password" name="old_password" class="form-control">
        </div>

        <div class="mb-3">
            <label for="new_password" class="form-label">Yeni Şifre:</label>
            <input type="password" id="new_password" name="new_password" class="form-control">
        </div>

        <div class="mb-4">
            <label for="new_password_confirm" class="form-label">Yeni Şifre (Tekrar):</label>
            <input type="password" id="new_password_confirm" name="new_password_confirm" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary w-100">Güncelle</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
