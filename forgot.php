<?php
require_once "includes/db.php";
require_once "includes/auth.php";
require_once "includes/users.php";

$submitted = false;
$link = "";

if (isset($_POST["identifier"])) {
    csrf_check();
    $identifier = trim($_POST["identifier"]);
    $found = find_user_by_username($conn, $identifier) ?? find_user_by_email($conn, $identifier);
    if ($found) {
        $token = bin2hex(random_bytes(32));
        // Only the hash is stored, so a leaked database cannot be used to reset passwords.
        if (set_reset_token($conn, $found["id"], hash("sha256", $token))) {
            $link = "reset.php?token=" . $token;
        }
    }
    $submitted = true;
}

$page_title = "Forgot Password";
include "includes/header.php";
?>

<section class="hero">
    <h1>Forgot Password</h1>
    <p>Enter your username or email to get a password reset link.</p>
</section>

<section class="converter-card auth-card">
    <?php if ($submitted): ?>
        <div class="alert alert-success">If that account exists, a reset link has been created. It is valid for 30 minutes.</div>
        <?php if ($link): ?>
            <div class="alert alert-success">
                Demo mode: in production this link is emailed.<br>
                <a href="<?= htmlentities($link) ?>"><?= htmlentities($link) ?></a>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <form method="post" action="forgot.php" class="form-stack">
        <?= csrf_field() ?>
        <div class="input-group amount-group">
            <label for="identifier">Username or email</label>
            <input type="text" id="identifier" name="identifier" required autofocus>
        </div>
        <button type="submit" class="convert-button">Get Reset Link</button>
    </form>

    <p class="form-links"><a href="login.php">Back to login</a></p>
</section>

<?php include "includes/footer.php"; ?>
