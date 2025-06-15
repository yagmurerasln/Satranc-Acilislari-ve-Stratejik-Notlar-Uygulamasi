<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Satranç Açılışları</title>
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/WebphpProje/assets/css/style.css">
    <link rel="stylesheet" href="/WebphpProje/assets/css/home.css">
    <link rel="stylesheet" href="/WebphpProje/assets/css/register.css">
    <link rel="stylesheet" href="/WebphpProje/assets/css/index.css">
    <link rel="stylesheet" href="/WebphpProje/assets/css/edit.css">
    <link rel="stylesheet" href="/WebphpProje/assets/css/login.css">

    <style>
        header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: #f0f0f0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        header nav {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            font-weight: 600;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 1rem;
            user-select: none;
        }
        header nav a {
            color: #333;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }
        header nav a:hover {
            background-color: #007bff;
            color: white;
        }
        nav > a.user-link {
            color: rgb(50, 96, 155);
            font-weight: 600;
        }
    </style>
</head>

<body>

<?php if (isset($_SESSION['user_id'])): ?>
<header>
    <nav>
        <a class="user-link" href="/WebphpProje/pages/auth/profile.php?id=<?= $_SESSION['user_id'] ?>">
            <?= htmlspecialchars($_SESSION['username']) ?>
        </a> |
        <a href="/WebphpProje/pages/home.php">Ana Sayfa</a> |
        <a href="/WebphpProje/pages/openings/index.php">Açılışlar</a> |
        <a href="/WebphpProje/pages/auth/logout.php">Çıkış</a>
    </nav>
</header>
<?php else: ?>
<header>
    <nav>
        <a href="/WebphpProje/pages/home.php">Ana Sayfa</a> |
        <a href="/WebphpProje/pages/auth/login.php">Giriş Yap</a> |
        <a href="/WebphpProje/pages/auth/register.php">Kayıt Ol</a>
    </nav>
</header>
<?php endif; ?>

<main>
