<?php
require_once '../../includes/config.php';
require_once '../../includes/db.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: ../home.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (!$username || !$email || !$password || !$password_confirm) {
        $error = "Lütfen tüm alanları doldurun.";
    } elseif (strlen($password) < 6) {
        $error = "Şifre en az 6 karakter olmalıdır.";
    } elseif ($password !== $password_confirm) {
        $error = "Şifreler uyuşmuyor.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $error = "Bu kullanıcı adı veya email zaten alınmış.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $password_hash]);

            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            header("Location: ../home.php");
            exit;
        }
    }
}

include '../../includes/header.php';
?>

<main>
    <h2>Kayıt Ol</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="">
    <label for="username">Kullanıcı Adı:</label>
    <input id="username" type="text" name="username" required>

    <label for="email">Email:</label>
    <input id="email" type="email" name="email" required>

    <label for="password">Şifre:</label>
    <input id="password" type="password" name="password" required minlength="6">

    <label for="password_confirm">Şifre Tekrar:</label>
    <input id="password_confirm" type="password" name="password_confirm" required minlength="6">

    <button type="submit">Kayıt Ol</button>
</form>

</main>

<?php include '../../includes/footer.php'; ?>
