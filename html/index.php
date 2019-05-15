<?php

date_default_timezone_set('Europe/London');
$now = new DateTime();

session_start();

require_once "../config.inc.php";
require_once "../../api.chrisburnell.com/functions/helpers.php";

if (!isset($_GET["q"])) {
    require_once "../../api.chrisburnell.com/layouts/header.php";
}

?>

<form action="<?php echo Config::$indieauth_url ?>" method="get">
    <label for="indie_auth_url">Web Address:</label>
    <input id="indie_auth_url" type="url" name="me" placeholder="yourdomain.com" required />
    <input id="indie_auth_url_auto_scheme" type="hidden" name="me_auto_scheme" />
    <p><button type="submit">Sign In</button></p>
    <input type="hidden" name="client_id" value="<?php echo Config::$host_url ?>" />
    <input type="hidden" name="redirect_uri" value="<?php echo Config::$authentication_url ?>" />
    <input type="hidden" name="state" value="<?php echo Config::$state ?>" />
</form>
<h2 class="gamma" id="the-plan">So what’s the plan here?</h2>
<p>The plan is to build out a simple API to syndicate your check-ins on <em>Untappd</em> back to your own website using Micropub. I also plan to pull in Toasts (likes) and Comments using Webmentions.</p>
<h2 class="gamma" id="timeline">When can I start using this?</h2>
<p style="color: var(--color-liquid)"><strong>Update! I have an API key!</strong></p>
<p>I’ve got a good start on the API so far. I’m not sure when I plan on releasing it, but it sort of takes your data in now. Need to still work on the parts that compare the live vs. cached data, compare for new checkins and check live checkins against cached checkins for new comments and/or toasts, which, when found, will live on a page that will then be sent as a webmention to your source.</p>

<script>
    /* add https:// to URL fields on blur */
    document.addEventListener('DOMContentLoaded', function() {
        function addDefaultScheme(target) {
            var auto_scheme = false;
            var default_scheme = "http";
            var auto_scheme_field = document.querySelector("input[name="+target.getAttribute('name')+"_auto_scheme]");
            if(target.value.match(/^(?!https?:).+\..+/)) {
                target.value = (auto_scheme_field ? "https" : "http")+"://"    +target.value;
                if(auto_scheme_field) {
                    auto_scheme_field.value = "1";
                }
            }
        }
        var elements = document.querySelectorAll("input[type=url]");
        Array.prototype.forEach.call(elements, function(el, i){
            el.addEventListener("blur", function(e){
                addDefaultScheme(e.target);
            });
            el.addEventListener("keydown", function(e){
            if(e.keyCode == 13) {
                addDefaultScheme(e.target);
            }
            });
        });
    });
</script>

<?php
    if (!isset($_GET["q"])) {
        require_once "../../api.chrisburnell.com/layouts/footer.php";
    }
?>
