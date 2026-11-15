<?php
require_once "includes/db.php";
require_once "includes/auth.php";
require_once "includes/conversions.php";
require_once "includes/rates.php";

require_login();
$user_id = current_user()["id"];

// Fits DECIMAL(15,2) and keeps the converted amount inside DECIMAL(18,2).
const MAX_AMOUNT = 1000000000;

$amount = "100";
$from = "USD";
$to = "EUR";
$error = "";
$result = null;

if (isset($_POST["amount"], $_POST["from"], $_POST["to"])) {
    csrf_check();
    $amount = trim($_POST["amount"]);
    $from = $_POST["from"];
    $to = $_POST["to"];
    $value = is_numeric($amount) ? round((float) $amount, 2) : 0;

    if ($value <= 0) {
        $error = "Please enter an amount greater than zero.";
    } elseif ($value > MAX_AMOUNT) {
        $error = "Please enter an amount up to " . number_format(MAX_AMOUNT) . ".";
    } elseif (!isset(CURRENCIES[$from], CURRENCIES[$to])) {
        $error = "Please choose currencies from the list.";
    } elseif ($from === $to) {
        $error = "Please choose two different currencies.";
    } else {
        $rate = get_rate($from, $to);
        if ($rate === null) {
            $error = "Exchange rates are unavailable right now. Please try again in a moment.";
        } else {
            $converted = round($value * $rate, 2);
            if (add_conversion($conn, $user_id, $from, $to, $value, $rate, $converted)) {
                $result = ["amount" => $value, "converted" => $converted, "rate" => $rate, "time" => date("M j, Y g:i A")];
            } else {
                $error = "The conversion could not be saved. Please try again.";
            }
        }
    }
}

$rows = recent_conversions_for_user($conn, $user_id);
$show_user = false;

// Renders one currency <select> with its flag; flags travel in data-flag for script.js.
function currency_select($id, $name, $selected)
{
    $flag = CURRENCIES[$selected][1] ?? CURRENCIES[array_key_first(CURRENCIES)][1];
    echo '<div class="select-wrapper">';
    echo '<span class="currency-flag" id="' . $id . 'Flag">' . htmlentities($flag) . '</span>';
    echo '<select id="' . $id . '" name="' . $name . '" data-flag-target="' . $id . 'Flag">';
    foreach (CURRENCIES as $code => [$label, $code_flag]) {
        $attr = $code === $selected ? " selected" : "";
        echo '<option value="' . htmlentities($code) . '" data-flag="' . htmlentities($code_flag) . '"' . $attr . '>'
            . htmlentities("$code - $label") . '</option>';
    }
    echo '</select></div>';
}

$page_title = "Converter";
include "includes/header.php";
?>

<section class="hero">
    <h1>Convert Currencies Instantly</h1>
    <p>Get real-time exchange rates and track your conversion history.</p>
</section>

<section class="converter-card">
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlentities($error) ?></div>
    <?php endif; ?>

    <form method="post" action="index.php" class="converter-inputs">
        <?= csrf_field() ?>

        <div class="input-group amount-group">
            <label for="amount">Amount</label>
            <input type="number" id="amount" name="amount" value="<?= htmlentities($amount) ?>"
                   min="0.01" step="0.01" placeholder="Enter amount" required>
        </div>

        <div class="input-group currency-group">
            <label for="fromCurrency">From</label>
            <?php currency_select("fromCurrency", "from", $from); ?>
        </div>

        <button class="swap-button" id="swapButton" title="Swap currencies" type="button">⇄</button>

        <div class="input-group currency-group">
            <label for="toCurrency">To</label>
            <?php currency_select("toCurrency", "to", $to); ?>
        </div>

        <button class="convert-button" type="submit"><span>⇄</span> Convert</button>
    </form>

    <?php if ($result): ?>
        <div class="result-box">
            <div class="result-label">Conversion Result</div>
            <div class="result-main">
                <span><?= htmlentities(number_format($result["amount"], 2) . " " . $from) ?></span>
                <span class="equals">=</span>
                <span class="converted-result"><?= htmlentities(number_format($result["converted"], 2) . " " . $to) ?></span>
            </div>
            <div class="result-details">
                <span><?= htmlentities("Exchange Rate: 1 $from = " . number_format($result["rate"], 4) . " $to") ?></span>
                <span>◷ <?= htmlentities($result["time"]) ?></span>
            </div>
        </div>
    <?php endif; ?>
</section>

<section class="history-card">
    <div class="history-header">
        <h2>Recent Conversions</h2>
        <a class="history-button" href="logs.php">View All History <span>›</span></a>
    </div>
    <?php include "includes/history_table.php"; ?>
</section>

<script src="assets/script.js"></script>

<?php include "includes/footer.php"; ?>
