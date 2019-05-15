<?php

if (!isset($_SESSION["domain"]) or !isset($_SESSION["state"]) or (isset($_SESSION["state"]) and $_SESSION["state"] !== Config::$state)) {
    header("Location: " . Config::$host_url);
}

?>
