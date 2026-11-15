<?php

function find_user_by_username($conn, $username)
{
    return db_fetch_one(db_run($conn, "SELECT * FROM users WHERE username = ?", "s", [$username]));
}

function find_user_by_email($conn, $email)
{
    return db_fetch_one(db_run($conn, "SELECT * FROM users WHERE email = ?", "s", [$email]));
}

function list_users($conn)
{
    return db_fetch_all(db_run($conn, "SELECT id, username, email, role, created_at FROM users ORDER BY username"));
}

function create_user($conn, $username, $email, $password, $role)
{
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)";
    return db_run($conn, $sql, "ssss", [$username, $email, $hash, $role]) !== false;
}

// Expiry is computed in MySQL so it is compared against the same clock.
function set_reset_token($conn, $user_id, $token_hash)
{
    $sql = "UPDATE users SET reset_token_hash = ?, reset_expires = NOW() + INTERVAL 30 MINUTE WHERE id = ?";
    return db_run($conn, $sql, "si", [$token_hash, $user_id]) !== false;
}

function find_user_by_reset_token($conn, $token_hash)
{
    $sql = "SELECT * FROM users WHERE reset_token_hash = ? AND reset_expires > NOW()";
    return db_fetch_one(db_run($conn, $sql, "s", [$token_hash]));
}

function update_password($conn, $user_id, $password)
{
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET password_hash = ?, reset_token_hash = NULL, reset_expires = NULL WHERE id = ?";
    return db_run($conn, $sql, "si", [$hash, $user_id]) !== false;
}
