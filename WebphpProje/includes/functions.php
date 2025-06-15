<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Opening.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function displayError($message) {
    echo '<div class="alert alert-danger">' . $message . '</div>';
}

function displaySuccess($message) {
    echo '<div class="alert alert-success">' . $message . '</div>';
}

// Diğer yardımcı fonksiyonlar...
