<?php
require_once "includes/db.php";
require_once "includes/auth.php";
require_once "includes/users.php";

require_admin();

$form = ["username" => "", "email" => "", "role" => "user"];
$error = "";
$success = "";

if (isset($_POST["username"], $_POST["email"], $_POST["password"], $_POST["role"])) {
    csrf_check();
    $form = ["username" => trim($_POST["username"]), "email" => trim($_POST["email"]), "role" => $_POST["role"]];
    $password = $_POST["password"];

    if ($form["username"] === "" || $form["email"] === "" || $password === "") {
        $error = "All fields are required.";
    } elseif (strlen($form["username"]) > 50) {
        $error = "Username must be 50 characters or fewer.";
    } elseif (!filter_var($form["email"], FILTER_VALIDATE_EMAIL) || strlen($form["email"]) > 128) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } elseif (!in_array($form["role"], ["admin", "user"], true)) {
        $error = "Please choose a valid role.";
    } elseif (find_user_by_username($conn, $form["username"])) {
        $error = "That username is already taken.";
    } elseif (find_user_by_email($conn, $form["email"])) {
        $error = "That email is already in use.";
    } elseif (!create_user($conn, $form["username"], $form["email"], $password, $form["role"])) {
        $error = "The user could not be created. Please try again.";
    } else {
        $success = "User " . $form["username"] . " was created.";
        $form = ["username" => "", "email" => "", "role" => "user"];
    }
}

$users = list_users($conn);

$page_title = "Admin";
include "includes/header.php";
?>

<section class="hero">
    <h1>User Management</h1>
    <p>Create accounts and see who can use the converter.</p>
</section>

<section class="converter-card auth-card">
    <div class="history-header"><h2>Create User</h2></div>
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlentities($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlentities($success) ?></div>
    <?php endif; ?>

    <form method="post" action="admin.php" class="form-stack">
        <?= csrf_field() ?>
        <div class="input-group amount-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" maxlength="50" value="<?= htmlentities($form["username"]) ?>" required>
        </div>
        <div class="input-group amount-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" maxlength="128" value="<?= htmlentities($form["email"]) ?>" required>
        </div>
        <div class="input-group amount-group">
            <label for="password">Password (min 8 characters)</label>
            <input type="password" id="password" name="password" minlength="8" autocomplete="new-password" required>
        </div>
        <div class="input-group">
            <label for="role">Role</label>
            <div class="select-wrapper">
                <select id="role" name="role">
                    <option value="user"<?= $form["role"] === "user" ? " selected" : "" ?>>User</option>
                    <option value="admin"<?= $form["role"] === "admin" ? " selected" : "" ?>>Admin</option>
                </select>
            </div>
        </div>
        <button type="submit" class="convert-button">Create User</button>
    </form>
</section>

<section class="history-card">
    <div class="history-header">
        <h2>Users</h2>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr><th>Username</th><th>Email</th><th>Role</th><th>Created</th></tr>
            </thead>
            <tbody>
                <?php foreach ($users as $row): ?>
                    <tr>
                        <td><?= htmlentities($row["username"]) ?></td>
                        <td><?= htmlentities($row["email"]) ?></td>
                        <td><?= htmlentities($row["role"]) ?></td>
                        <td><?= htmlentities(date("M j, Y g:i A", strtotime($row["created_at"]))) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include "includes/footer.php"; ?>
