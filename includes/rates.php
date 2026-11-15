<?php

// Returns how many $to one $from buys, or null if the API call fails.
function get_rate($from, $to)
{
    $url = "https://open.er-api.com/v6/latest/" . urlencode($from);
    $context = stream_context_create(["http" => ["timeout" => 10]]);
    $json = @file_get_contents($url, false, $context);
    if ($json === false) {
        return null;
    }
    $data = json_decode($json, true);
    if (($data["result"] ?? "") !== "success" || !isset($data["rates"][$to])) {
        return null;
    }
    return (float) $data["rates"][$to];
}
