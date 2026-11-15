<?php
require_once __DIR__ . "/../config.php";

// Return values are checked by hand, so mysqli should not throw.
mysqli_report(MYSQLI_REPORT_OFF);

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Database connection failed. Is MySQL running?");
}
mysqli_set_charset($conn, "utf8mb4");
// Keep MySQL's NOW() in the same time zone as PHP's date().
mysqli_query($conn, "SET time_zone = '" . date("P") . "'");

// Runs a prepared statement and returns it, or false on failure.
function db_run($conn, $sql, $types = "", $params = [])
{
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        return false;
    }
    if ($types !== "") {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    return mysqli_stmt_execute($stmt) ? $stmt : false;
}

function db_fetch_one($stmt)
{
    return $stmt ? mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) : null;
}

function db_fetch_all($stmt)
{
    return $stmt ? mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC) : [];
}
