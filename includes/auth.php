<?php
session_set_cookie_params(["httponly" => true, "samesite" => "Lax"]);
session_start();

if (empty($_SESSION["csrf"])) {
    $_SESSION["csrf"] = bin2hex(random_bytes(32));
}

function current_user()
{
    return $_SESSION["user"] ?? null;
}

function login_user($user)
{
    session_regenerate_id(true);
    $_SESSION["user"] = [
        "id" => (int) $user["id"],
        "username" => $user["username"],
        "role" => $user["role"],
    ];
}

function logout_user()
{
    $_SESSION = [];
    session_destroy();
}

function require_login()
{
    if (!current_user()) {
        header("Location: login.php");
        exit;
    }
}

function require_admin()
{
    require_login();
    if (current_user()["role"] !== "admin") {
        header("Location: index.php");
        exit;
    }
}

function csrf_field()
{
    return '<input type="hidden" name="csrf" value="' . htmlentities($_SESSION["csrf"]) . '">';
}

function csrf_check()
{
    if (!isset($_POST["csrf"]) || !hash_equals($_SESSION["csrf"], $_POST["csrf"])) {
        http_response_code(400);
        die("Invalid or expired form. Please go back, refresh the page and try again.");
    }
}
