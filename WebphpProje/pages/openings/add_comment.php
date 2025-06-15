<?php
// pages/openings/add_comment.php
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/functions.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['opening_id'])) {
    header("Location: index.php");
    exit;
}

$opening_id = intval($_POST['opening_id']);
$user_id = $_SESSION['user_id'];
$content = trim($_POST['content']);

if (empty($content)) {
    $_SESSION['error'] = "Yorum boş olamaz.";
    header("Location: view.php?id=$opening_id");
    exit;
}

// Yorumu veritabanına ekle
$stmt = $pdo->prepare("INSERT INTO comments (opening_id, user_id, content) VALUES (?, ?, ?)");
$stmt->execute([$opening_id, $user_id, $content]);

$_SESSION['success'] = "Yorumunuz eklendi.";
header("Location: view.php?id=$opening_id");
exit;
?>