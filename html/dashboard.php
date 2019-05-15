<?php

    $title = "Dashboard";

    date_default_timezone_set('Europe/London');
    $now = new DateTime();

    session_start();

    require_once "../config.inc.php";
    require_once "../../api.chrisburnell.com/functions/helpers.php";
    require_once "../functions/untappd.php";
    require_once "../includes/authorisation.php";

    $json = file_get_contents("../users/" . $_SESSION["domain"] . ".json");
    $json_decoded = json_decode($json, true);
    $json_string = json_encode($json_decoded, JSON_PRETTY_PRINT);

    require_once "../../api.chrisburnell.com/layouts/header.php";

?>

<p>This is where you can force a poll, eventually.</p>
<div aria-labelledby="code-toggle-button--1" class="code-toggle" id="code-toggle--1">
    <input class="code-toggle-input" id="code-toggle-input--1" name="code-toggle-input--1" role="checkbox" tabindex="-1" type="checkbox">
    <figure class="highlight">
        <pre><code class="language-json" data-lang="json"><?php echo $json_string ?></code></code>
    </figure>
    <label id="code-toggle-label--1" class="code-toggle-label" for="code-toggle-input--1">
        <button id="code-toggle-button--1" aria-controls="code-toggle--1">View Full JSON</button>
    </label>
</div>

<?php
    require_once "../../api.chrisburnell.com/layouts/footer.php";
?>
