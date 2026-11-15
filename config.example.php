<?php
// Copy this file to config.php and adjust the database settings.

const DB_HOST = "localhost";
const DB_USER = "root";
const DB_PASS = "";
const DB_NAME = "smart_currency";

// XAMPP's php.ini defaults to Europe/Berlin; set your local zone so times display correctly.
date_default_timezone_set("Asia/Amman");

// Supported currencies: code => [name, flag]. Add a line here to support a new one.
const CURRENCIES = [
    "USD" => ["US Dollar", "🇺🇸"],
    "EUR" => ["Euro", "🇪🇺"],
    "GBP" => ["British Pound", "🇬🇧"],
    "JPY" => ["Japanese Yen", "🇯🇵"],
    "CAD" => ["Canadian Dollar", "🇨🇦"],
    "JOD" => ["Jordanian Dinar", "🇯🇴"],
];
