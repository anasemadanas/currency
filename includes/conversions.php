<?php

function add_conversion($conn, $user_id, $from, $to, $amount, $rate, $converted)
{
    $sql = "INSERT INTO conversions (user_id, from_currency, to_currency, amount, rate, converted_amount)
            VALUES (?, ?, ?, ?, ?, ?)";
    return db_run($conn, $sql, "issddd", [$user_id, $from, $to, $amount, $rate, $converted]) !== false;
}

function recent_conversions_for_user($conn, $user_id)
{
    $sql = "SELECT * FROM conversions WHERE user_id = ? ORDER BY created_at DESC, id DESC LIMIT 5";
    return db_fetch_all(db_run($conn, $sql, "i", [$user_id]));
}

// Pass null as $user_id to get everyone's conversions (admin view).
// shortcut: no pagination, add LIMIT/OFFSET once the table grows large.
function list_conversions($conn, $user_id)
{
    $sql = "SELECT c.*, u.username FROM conversions c JOIN users u ON u.id = c.user_id";
    $order = " ORDER BY c.created_at DESC, c.id DESC";
    if ($user_id === null) {
        return db_fetch_all(db_run($conn, $sql . $order));
    }
    return db_fetch_all(db_run($conn, $sql . " WHERE c.user_id = ?" . $order, "i", [$user_id]));
}
