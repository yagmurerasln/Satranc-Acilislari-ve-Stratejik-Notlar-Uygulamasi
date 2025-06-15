<?php
session_start();

if (isset($_SESSION['user_id'])) {
    // Kullanıcı giriş yaptıysa açılışlara yönlendir
    header("Location: pages/openings/index.php");
    exit;
} else {
    // Giriş yapılmadıysa giriş/kayıt ekranını göster
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Satranç Açılışları</title>
        <link rel="stylesheet" href="/WebphpProje/assets/css/style.css">
        <link rel="stylesheet" href="/WebphpProje/assets/css/index2.css">

    </head>
    <body>
        <div class="container">
            <h1>🧠 Satranç Açılışları & Strateji Notları</h1>
            <p>Yorumları görmek ve açılış eklemek için giriş yapmalısınız.</p>

            <a href="/WebphpProje/pages/auth/login.php">🔐 Giriş Yap</a> |
            <a href="/WebphpProje/pages/auth/register.php">📝 Kayıt Ol</a>
        </div>
    </body>
    </html>

    <?php
}
?>
