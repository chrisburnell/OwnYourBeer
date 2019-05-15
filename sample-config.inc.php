<?php

class Config {
    public static $name = "OwnYourPint";
    public static $lede = "An IndieWeb tool to help you pull check-ins from Untappd. Coming soon…";
    public static $author = "Chris Burnell";
    public static $color = "#cf722e";
    public static $site_url = "https://chrisburnell.com/";
    public static $host_url = "https://ownyourpint.chrisburnell.com/";
    public static $dashboard_url = "https://ownyourpint.chrisburnell.com/dashboard";
    public static $authentication_url = "https://ownyourpint.chrisburnell.com/auth";
    public static $indieauth_url = "https://indieauth.com/auth";
    public static $indielogin_url = "https://indielogin.com/auth";
    public static $state = "";
    public static $code = "";

    public static $navigation = [
        "Dashboard" => "/dashboard/",
        "Documentation" => "/documentation/",
        "Source Code" => "#_"
    ];

    public static $untappd = [
        "api" =>           "https://api.untappd.com/v4",
        "token" =>         "",
        "client_id" =>     "",
        "client_secret" => ""
    ];

    public static $twitter = [
        "username" => "iamchrisburnell"
    ];
};

?>
