<?php
$user = current_user();
$page = basename($_SERVER["SCRIPT_NAME"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlentities($page_title) ?> - Smart Currency Converter</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header class="navbar">
    <div class="brand">
        <span class="brand-name">Smart Currency Converter</span>
    </div>

    <?php if ($user): ?>
        <nav class="navigation">
            <a href="index.php" class="nav-link<?= $page === "index.php" ? " active" : "" ?>">
                <span class="nav-icon">⌂</span> Home
            </a>
            <a href="logs.php" class="nav-link<?= $page === "logs.php" ? " active" : "" ?>">
                <span class="nav-icon">◷</span> Logs
            </a>
            <?php if ($user["role"] === "admin"): ?>
                <a href="admin.php" class="nav-link<?= $page === "admin.php" ? " active" : "" ?>">
                    <span class="nav-icon">⚙</span> Admin
                </a>
            <?php endif; ?>
            <span class="nav-divider"></span>
            <span class="user-menu">
                <span class="user-icon">👤</span> <?= htmlentities($user["username"]) ?>
            </span>
            <form method="post" action="logout.php">
                <?= csrf_field() ?>
                <button type="submit" class="nav-link">Logout</button>
            </form>
        </nav>
    <?php endif; ?>
</header>

<main class="main-content">
