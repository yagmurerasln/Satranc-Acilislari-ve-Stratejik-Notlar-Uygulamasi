<?php
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/functions.php';
session_start();

// Güvenlik kontrolleri
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

$comment_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Yorumu silme sorgusu (content sütununa göre)
$stmt = $pdo->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
$success = $stmt->execute([$comment_id, $user_id]);

// Geri bildirim mesajı
if ($success && $stmt->rowCount() > 0) {
    $_SESSION['success'] = "Yorum başarıyla silindi";
} else {
    $_SESSION['error'] = "Yorum silinirken bir hata oluştu veya yorum bulunamadı";
}

// Önceki sayfaya yönlendir
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>