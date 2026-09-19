<?php
session_start();

// Trocar pela sua senha antes de subir no servidor
define('ADMIN_PASS', password_hash('T9/;RmXbpbR!AN>', PASSWORD_DEFAULT));
define('SESSION_KEY', 'jac_admin_auth');

function is_logged_in(): bool {
    return !empty($_SESSION[SESSION_KEY]) && $_SESSION[SESSION_KEY] === true;
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: /admin/index.php');
        exit;
    }
}

function do_login(string $senha): bool {
    if (password_verify($senha, ADMIN_PASS)) {
        session_regenerate_id(true);
        $_SESSION[SESSION_KEY] = true;
        return true;
    }
    return false;
}

function do_logout(): void {
    session_destroy();
    header('Location: /admin/index.php');
    exit;
}
