<?php

date_default_timezone_set('Europe/London');
$now = new DateTime();

require_once "../config.inc.php";
require_once "../functions/helpers.php";
require_once "../functions/untappd.php";

$username = isset($_GET['username']) ? $_GET['username'] : 'chrisburnell';

header('Content-Type: application/json');
echo get_checkins($username, $now, true);

?>
