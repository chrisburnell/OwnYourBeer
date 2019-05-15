<?php

date_default_timezone_set('Europe/London');
$now = new DateTime();

session_start();

require_once "../config.inc.php";
require_once "../functions/helpers.php";
require_once "../functions/untappd.php";

if ($_GET["code"] !== Config::$code) {
    exit;
}

$path = "../users";
$files = scandir($path);
$files = array_diff(scandir($path), array('.', '..'));

foreach ($files as $file) {
    $json = file_get_contents($path . "/" . $file);
    $json_decoded = json_decode($json, true);
    $username = $json_decoded[0]["user"]["username"];
    file_put_contents($path . "/" . $file, get_checkins($username, $now, true));
}

?>
