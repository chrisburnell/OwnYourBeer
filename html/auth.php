<?php

date_default_timezone_set('Europe/London');
$now = new DateTime();

session_start();

require_once "../config.inc.php";
require_once "../../api.chrisburnell.com/functions/helpers.php";
require_once "../functions/untappd.php";

if (isset($_GET["username"])) {
    $username = $_GET["username"];
    $_SESSION["username"] = $_GET["username"];
}
if (isset($_GET["code"])) {
    $code = $_GET["code"];
}
// else {
//     header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
//     header("Location: " . Config::$host_url);
//     exit;
// }

// require_once "../../api.chrisburnell.com/layouts/header.php";

if (isset($_GET["state"]) and $_GET["state"] !== Config::$state) {
    header($_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
    echo "Failed to authenticate state.";
    exit;
}

if (!isset($_SESSION["domain"])) {
    $AUTH_curl = curl_init(Config::$indieauth_url);
    curl_setopt($AUTH_curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($AUTH_curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($AUTH_curl, CURLOPT_POSTFIELDS, "code=$code&client_id=" . Config::$host_url . "&redirect_uri=" . Config::$authentication_url . "&state=" . Config::$state);
    $AUTH_response = curl_exec($AUTH_curl);
    curl_close($AUTH_curl);
    $AUTH_response_decoded = array();
    parse_str($AUTH_response, $AUTH_response_decoded);
    // If we don’t get a response, something’s wrong with the auth server
    if (!$AUTH_response) {
        header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
        echo "Failed to reach authentication server.";
        exit;
    }
    // Get the cleaned-up domain for filepath
    $domain = $AUTH_response_decoded["me"];
    $domain = explode("//", $domain)[1];
    $domain = rtrim($domain, "/");
    // and... BOOM, SESSIONS
    $_SESSION["domain"] = $domain;
    $_SESSION["state"] = Config::$state;
}

header($_SERVER["SERVER_PROTOCOL"] . " 302 Found");
// If the file exists pull data from the user file
if (file_exists("../users/" . $_SESSION["domain"] . ".json") and !empty(file_get_contents("../users/" . $_SESSION["domain"] . ".json"))) {
    header($_SERVER["SERVER_PROTOCOL"] . " 202 Accepted");
    header("Location: " . Config::$dashboard_url);
}
// If there’s no username SESSION, send to Untappd authorize
elseif (!isset($_SESSION["username"])) {
    header($_SERVER["SERVER_PROTOCOL"] . " 202 Accepted");
    echo '<form action="' . Config::$authentication_url . '" method="get">
        <label for="username">Untappd Username:</label>
        <input id="username" type="text" name="username" placeholder="johndoe" required />
        <p><button type="submit">Submit</button></p>
        <input type="hidden" name="code" value="' . $code . '" />
        <input type="hidden" name="client_id" value="<' . Config::$host_url . '" />
        <input type="hidden" name="redirect_uri" value="' . Config::$authentication_url . '" />
        <input type="hidden" name="state" value="' . Config::$state . '" />
    </form>';
    exit;
    // header($_SERVER["SERVER_PROTOCOL"] . " 307 Temporary Redirect");
    // header("Location: https://untappd.com/oauth/authenticate/?client_id=" . Config::$untappd["client_id"] . "&response_type=code&redirect_url=" . Config::$authentication_url . "&state=" . Config::$state);
}
// Else poll their data from Untappd API then update the file
elseif (isset($_SESSION["username"])) {
    $json = get_checkins($_SESSION["username"], $now, true);
    file_put_contents("../users/" . $_SESSION["domain"] . ".json", $json);
    header($_SERVER["SERVER_PROTOCOL"] . " 201 Created");
    header("Location: " . Config::$dashboard_url);
}
// And process that information regardless of path
else {
    header($_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
    echo "Failed to pass all authentication.";
    exit;
}

// require_once "../../api.chrisburnell.com/layouts/footer.php";

?>
