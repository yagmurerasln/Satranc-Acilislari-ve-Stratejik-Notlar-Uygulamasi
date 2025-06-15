<?php
require_once '../../includes/config.php';
require_once '../../includes/db.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: /WebphpProje/pages/home.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!$username || !$password) {
        $error = "Lütfen kullanıcı adı ve şifre girin.";
    } else {
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            header("Location: /WebphpProje/pages/home.php");
            exit;
        } else {
            $error = "Kullanıcı adı veya şifre yanlış.";
        }
    }
}

include '../../includes/header.php';
?>

<!-- login.css dosyasını Bootstrap'ten sonra dahil et -->
<link href="/WebphpProje/assets/css/login.css" rel="stylesheet">

<div class="container my-5" style="max-width: 420px;">

    <nav class="mb-4 d-flex justify-content-center gap-3">
        <a href="/WebphpProje/index.php" class="btn btn-outline-secondary">🏠 Ana Sayfa</a>
        <a href="/WebphpProje/pages/auth/register.php" class="btn btn-outline-primary">📝 Kayıt Ol</a>
    </nav>

    <h2 class="mb-4 text-center">Giriş Yap</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="mb-3">
            <label for="username" class="form-label">Kullanıcı Adı:</label>
            <input type="text" id="username" name="username" class="form-control" required autofocus>
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">Şifre:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
