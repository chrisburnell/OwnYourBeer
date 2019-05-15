<?php

date_default_timezone_set('Europe/London');
$now = new DateTime();

session_start();

require_once "../config.inc.php";
require_once "../functions/helpers.php";
require_once "../functions/untappd.php";
require_once "../includes/authorisation.php";

// $old_json = file_get_contents("../users/$domain.json");
// $old_json_decoded = json_decode($old_json, true);
// $json = get_checkins($old_json_decoded["username"], $now, false);
// file_put_contents("../users/$domain.json", $json);

?>
