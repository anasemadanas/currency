<?php
require_once "includes/db.php";
require_once "includes/auth.php";
require_once "includes/users.php";

$token = $_POST["token"] ?? $_GET["token"] ?? "";
$found = $token !== "" ? find_user_by_reset_token($conn, hash("sha256", $token)) : null;
$error = "";
$done = false;

if ($found && isset($_POST["password"], $_POST["confirm"])) {
    csrf_check();
    if (strlen($_POST["password"]) < 8) {
        $error = "Password must be at least 8 characters.";
    } elseif ($_POST["password"] !== $_POST["confirm"]) {
        $error = "Passwords do not match.";
    } elseif (update_password($conn, $found["id"], $_POST["password"])) {
        $done = true;
    } else {
        $error = "The password could not be changed. Please try again.";
    }
}

$page_title = "Reset Password";
include "includes/header.php";
?>

<section class="hero">
    <h1>Reset Password</h1>
    <p>Choose a new password for your account.</p>
</section>

<section class="converter-card auth-card">
    <?php if ($done): ?>
        <div class="alert alert-success">Your password has been changed.</div>
        <p class="form-links"><a href="login.php">Go to login</a></p>
    <?php elseif (!$found): ?>
        <div class="alert alert-error">This reset link is invalid or has expired. Please request a new one.</div>
        <p class="form-links"><a href="forgot.php">Request a new link</a></p>
    <?php else: ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlentities($error) ?></div>
        <?php endif; ?>
        <form method="post" action="reset.php" class="form-stack">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= htmlentities($token) ?>">
            <div class="input-group amount-group">
                <label for="password">New password (min 8 characters)</label>
                <input type="password" id="password" name="password" minlength="8" autocomplete="new-password" required>
            </div>
            <div class="input-group amount-group">
                <label for="confirm">Confirm new password</label>
                <input type="password" id="confirm" name="confirm" minlength="8" autocomplete="new-password" required>
            </div>
            <button type="submit" class="convert-button">Change Password</button>
        </form>
    <?php endif; ?>
</section>

<?php include "includes/footer.php"; ?>
