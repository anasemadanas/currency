<?php
// Expects $rows (conversion rows) and $show_user (bool: add a username column).
// Currencies removed from config.php later still render, with a globe instead of a flag.
?>
<?php if (!$rows): ?>
    <p class="empty-state">No conversions yet.</p>
<?php else: ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <?php if ($show_user): ?><th>User</th><?php endif; ?>
                    <th>From</th>
                    <th>To</th>
                    <th>Amount</th>
                    <th>Converted Amount</th>
                    <th>Exchange Rate</th>
                    <th>Date &amp; Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row):
                    $from = $row["from_currency"];
                    $to = $row["to_currency"];
                    [$from_name, $from_flag] = CURRENCIES[$from] ?? [$from, "🌐"];
                    [$to_name, $to_flag] = CURRENCIES[$to] ?? [$to, "🌐"];
                ?>
                    <tr>
                        <?php if ($show_user): ?><td><?= htmlentities($row["username"]) ?></td><?php endif; ?>
                        <td>
                            <div class="currency-cell">
                                <span class="table-flag"><?= htmlentities($from_flag) ?></span>
                                <div>
                                    <strong><?= htmlentities($from) ?></strong>
                                    <small><?= htmlentities($from_name) ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="currency-cell">
                                <span class="arrow-right">→</span>
                                <span class="table-flag"><?= htmlentities($to_flag) ?></span>
                                <div>
                                    <strong><?= htmlentities($to) ?></strong>
                                    <small><?= htmlentities($to_name) ?></small>
                                </div>
                            </div>
                        </td>
                        <td><?= htmlentities(number_format($row["amount"], 2) . " " . $from) ?></td>
                        <td class="success-text"><?= htmlentities(number_format($row["converted_amount"], 2) . " " . $to) ?></td>
                        <td><?= htmlentities("1 $from = " . number_format($row["rate"], 4) . " $to") ?></td>
                        <td><?= htmlentities(date("M j, Y g:i A", strtotime($row["created_at"]))) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
