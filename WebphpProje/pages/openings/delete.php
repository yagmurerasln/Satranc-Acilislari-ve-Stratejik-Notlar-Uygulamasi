<?php
require_once '../../includes/config.php';
require_once '../../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Geçersiz açılış ID'si.";
    exit;
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Sadece sahibi ise silebilir
$stmt = $pdo->prepare("DELETE FROM openings WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);

header("Location: index.php");
exit;
