<?php
require_once "includes/db.php";
require_once "includes/auth.php";
require_once "includes/conversions.php";

require_login();
$show_user = current_user()["role"] === "admin";
$rows = list_conversions($conn, $show_user ? null : current_user()["id"]);

$page_title = "Logs";
include "includes/header.php";
?>

<section class="hero">
    <h1>Conversion Logs</h1>
    <p><?= $show_user ? "All conversions by all users, newest first." : "Your conversions, newest first." ?></p>
</section>

<section class="history-card">
    <?php include "includes/history_table.php"; ?>
</section>

<?php include "includes/footer.php"; ?>
