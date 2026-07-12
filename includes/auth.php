<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function current_user() {
    return $_SESSION['user'] ?? null;
}

function require_login($redirect = 'login.php') {
    if (!current_user()) {
        header("Location: $redirect");
        exit;
    }
}

function require_role($role, $redirect = 'login.php') {
    require_login($redirect);
    if (current_user()['role'] !== $role) {
        header("Location: $redirect");
        exit;
    }
}

function h($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}
