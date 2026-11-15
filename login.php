<?php
require_once "includes/db.php";
require_once "includes/auth.php";
require_once "includes/users.php";

if (current_user()) {
    header("Location: index.php");
    exit;
}

$username = "";
$error = "";

if (isset($_POST["username"], $_POST["password"])) {
    csrf_check();
    $username = trim($_POST["username"]);
    $found = find_user_by_username($conn, $username);
    if ($found && password_verify($_POST["password"], $found["password_hash"])) {
        login_user($found);
        header("Location: index.php");
        exit;
    }
    $error = "Invalid username or password.";
}

$page_title = "Login";
include "includes/header.php";
?>

<section class="hero">
    <h1>Welcome Back</h1>
    <p>Log in to convert currencies and see your history.</p>
</section>

<section class="converter-card auth-card">
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlentities($error) ?></div>
    <?php endif; ?>

    <form method="post" action="login.php" class="form-stack">
        <?= csrf_field() ?>
        <div class="input-group amount-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?= htmlentities($username) ?>"
                   autocomplete="username" required autofocus>
        </div>
        <div class="input-group amount-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" autocomplete="current-password" required>
        </div>
        <button type="submit" class="convert-button">Log In</button>
    </form>

    <p class="form-links"><a href="forgot.php">Forgot your password?</a></p>
</section>

<?php include "includes/footer.php"; ?>
