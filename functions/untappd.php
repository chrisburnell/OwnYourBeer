<?php

function get_checkins($username, $now, $full) {
    // Check $username
    if (is_null($username)) {
        header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
        echo "Missing 'username'.";
        exit;
    }
    // Check $now and apply default
    if (is_null($now)) {
        $now = new DateTime();
    }
    // Check $full and apply default
    if (is_null($full)) {
        $full = false;
    }

    $api_call = curl_init();
    curl_setopt($api_call, CURLOPT_URL, "https://api.untappd.com/v4/user/checkins/$username?limit=5&client_id=" . Config::$untappd["client_id"] . "&client_secret=" . Config::$untappd["client_secret"]);
    curl_setopt($api_call, CURLOPT_RETURNTRANSFER, 1);
    $api_response = curl_exec($api_call);
    curl_close($api_call);
    $api_response_decoded = json_decode($api_response, true);

    // Make sure we get the right data back from the Untappd API
    if ($api_response_decoded["meta"]["code"] !== 200) {
        header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
        echo "500 Internal Service Error";
        exit;
    }

    // Iterate over the checkins
    $checkins = array();
    foreach ($api_response_decoded["response"]["checkins"]["items"] as $checkin) {
        // Build Data object
        $data = new stdClass();
        // Populate Data object
        $data->id = !empty($checkin["checkin_id"]) ? $checkin["checkin_id"] : null;
        $data->date = !empty($checkin["created_at"]) ? $checkin["created_at"] : null;
        $data->rating = !empty($checkin["rating_score"]) ? $checkin["rating_score"] : null;
        $data->user = new stdClass();
        $data->user->id = !empty($checkin["user"]["uid"]) ? $checkin["user"]["uid"] : null;
        $data->user->username = !empty($checkin["user"]["user_name"]) ? $checkin["user"]["user_name"] : null;
        $data->user->first_name = !empty($checkin["user"]["first_name"]) ? $checkin["user"]["first_name"] : null;
        $data->user->last_name = !empty($checkin["user"]["last_name"]) ? $checkin["user"]["last_name"] : null;
        $data->user->photo = !empty($checkin["user"]["user_avatar"]) ? $checkin["user"]["user_avatar"] : null;
        $data->user->bio = !empty($checkin["user"]["bio"]) ? $checkin["user"]["bio"] : null;
        $data->user->location = !empty($checkin["user"]["location"]) ? $checkin["user"]["location"] : null;
        $data->user->twitter = !empty($checkin["user"]["contact"]["twitter"]) ? $checkin["user"]["contact"]["twitter"] : null;
        $data->user->foursquare = !empty($checkin["user"]["contact"]["foursquare"]) ? $checkin["user"]["contact"]["foursquare"] : null;
        $data->user->facebook = !empty($checkin["user"]["contact"]["facebook"]) ? $checkin["user"]["contact"]["facebook"] : null;
        $data->beer = new stdClass();
        $data->beer->id = !empty($checkin["beer"]["bid"]) ? $checkin["beer"]["bid"] : null;
        $data->beer->name = !empty($checkin["beer"]["beer_name"]) ? $checkin["beer"]["beer_name"] : null;
        $data->beer->slug = !empty($checkin["beer"]["beer_slug"]) ? $checkin["beer"]["beer_slug"] : null;
        $data->beer->photo = !empty($checkin["beer"]["beer_label"]) ? $checkin["beer"]["beer_label"] : null;
        $data->beer->style = !empty($checkin["beer"]["beer_style"]) ? $checkin["beer"]["beer_style"] : null;
        $data->beer->abv = !empty($checkin["beer"]["beer_abv"]) ? $checkin["beer"]["beer_abv"] : null;
        $data->brewery = new stdClass();
        $data->brewery->id = !empty($checkin["brewery"]["brewery_id"]) ? $checkin["brewery"]["brewery_id"] : null;
        $data->brewery->name = !empty($checkin["brewery"]["brewery_name"]) ? $checkin["brewery"]["brewery_name"] : null;
        $data->brewery->slug = !empty($checkin["brewery"]["brewery_slug"]) ? $checkin["brewery"]["brewery_slug"] : null;
        $data->brewery->photo = !empty($checkin["brewery"]["brewery_label"]) ? $checkin["brewery"]["brewery_label"] : null;
        $data->brewery->type = !empty($checkin["brewery"]["brewery_type"]) ? $checkin["brewery"]["brewery_type"] : null;
        $data->brewery->contact = new stdClass();
        $data->brewery->contact->twitter = !empty($checkin["brewery"]["contact"]["twitter"]) ? $checkin["brewery"]["contact"]["twitter"] : null;
        $data->brewery->contact->facebook = !empty($checkin["brewery"]["contact"]["facebook"]) ? $checkin["brewery"]["contact"]["facebook"] : null;
        $data->brewery->contact->instagram = !empty($checkin["brewery"]["contact"]["instagram"]) ? $checkin["brewery"]["contact"]["instagram"] : null;
        $data->brewery->contact->url = !empty($checkin["brewery"]["contact"]["url"]) ? $checkin["brewery"]["contact"]["url"] : null;
        $data->brewery->location = new stdClass();
        $data->brewery->location->city = !empty($checkin["brewery"]["location"]["brewery_city"]) ? $checkin["brewery"]["location"]["brewery_city"] : null;
        $data->brewery->location->state = !empty($checkin["brewery"]["location"]["brewery_state"]) ? $checkin["brewery"]["location"]["brewery_state"] : null;
        $data->brewery->location->country = !empty($checkin["brewery"]["country_name"]) ? $checkin["brewery"]["country_name"] : null;
        $data->brewery->location->lat = !empty($checkin["brewery"]["location"]["lat"]) ? $checkin["brewery"]["location"]["lat"] : null;
        $data->brewery->location->lng = !empty($checkin["brewery"]["location"]["lng"]) ? $checkin["brewery"]["location"]["lng"] : null;
        $data->venue = new stdClass();
        $data->venue->id = !empty($checkin["venue"]["venue_id"]) ? $checkin["venue"]["venue_id"] : null;
        $data->venue->name = !empty($checkin["venue"]["venue_name"]) ? $checkin["venue"]["venue_name"] : null;
        $data->venue->slug = !empty($checkin["venue"]["venue_slug"]) ? $checkin["venue"]["venue_slug"] : null;
        $data->venue->contact = new stdClass();
        $data->venue->contact->twitter = !empty($checkin["venue"]["contact"]["twitter"]) ? $checkin["venue"]["contact"]["twitter"] : null;
        $data->venue->contact->url = !empty($checkin["venue"]["contact"]["venue_url"]) ? $checkin["venue"]["contact"]["venue_url"] : null;
        $data->venue->location = new stdClass();
        $data->venue->location->address = !empty($checkin["venue"]["location"]["venue_address"]) ? $checkin["venue"]["location"]["venue_address"] : null;
        $data->venue->location->city = !empty($checkin["venue"]["location"]["venue_city"]) ? $checkin["venue"]["location"]["venue_city"] : null;
        $data->venue->location->state = !empty($checkin["venue"]["location"]["venue_state"]) ? $checkin["venue"]["location"]["venue_state"] : null;
        $data->venue->location->country = !empty($checkin["venue"]["location"]["venue_country"]) ? $checkin["venue"]["location"]["venue_country"] : null;
        $data->venue->location->lat = !empty($checkin["venue"]["location"]["lat"]) ? $checkin["venue"]["location"]["lat"] : null;
        $data->venue->location->lng = !empty($checkin["venue"]["location"]["lng"]) ? $checkin["venue"]["location"]["lng"] : null;
        $data->venue->foursquare = new stdClass();
        $data->venue->foursquare->id = !empty($checkin["venue"]["foursquare"]["foursquare_id"]) ? $checkin["venue"]["foursquare"]["foursquare_id"] : null;
        $data->venue->foursquare->url = !empty($checkin["venue"]["foursquare"]["foursquare_url"]) ? $checkin["venue"]["foursquare"]["foursquare_url"] : null;
        $data->badges = array();
        foreach ($checkin["badges"]["items"] as $badge_data) {
            $badge = new stdClass();
            $badge->id = $badge_data["badge_id"];
            $badge->user_id = $badge_data["user_badge_id"];
            $badge->name = $badge_data["badge_name"];
            $badge->description = $badge_data["badge_description"];
            $badge->photo = $badge_data["badge_image"]["sm"];
            $badge->photos = new stdClass();
            $badge->photos->small = $badge_data["badge_image"]["sm"];
            $badge->photos->medium = $badge_data["badge_image"]["md"];
            $badge->photos->large = $badge_data["badge_image"]["lg"];
            array_push($data->badges, $badge);
        }
        $data->media = array();
        foreach ($checkin["media"]["items"] as $media_data) {
            $media = new stdClass();
            $media->id = $media_data["photo_id"];
            $media->photo = $media_data["photo"]["photo_img_sm"];
            $media->photos = new stdClass();
            $media->photos->sm = $media_data["photo"]["photo_img_sm"];
            $media->photos->md = $media_data["photo"]["photo_img_md"];
            $media->photos->lg = $media_data["photo"]["photo_img_lg"];
            $media->photos->og = $media_data["photo"]["photo_img_og"];
            array_push($data->media, $media);
        }
        $data->comments = array();
        foreach ($checkin["comments"]["items"] as $comment_data) {
            $comment = new stdClass();
            $comment->id = $comment_data["comment_id"];
            $comment->date = $comment_data["created_at"];
            $comment->user = new stdClass();
            $comment->user->username = $comment_data["user"]["user_name"];
            $comment->user->first_name = $comment_data["user"]["first_name"];
            $comment->user->last_name = $comment_data["user"]["last_name"];
            $comment->user->photo = $comment_data["user"]["user_avatar"];
            $comment->user->bio = $comment_data["user"]["bio"];
            $comment->user->location = $comment_data["user"]["location"];
            $comment->user->relationship = $comment_data["user"]["relationship"];
            array_push($data->comments, $comment);
        }
        $data->toasts = array();
        foreach ($checkin["toasts"]["items"] as $toast_data) {
            $toast = new stdClass();
            $toast->id = $toast_data["like_id"];
            $toast->date = $toast_data["created_at"];
            $toast->user = new stdClass();
            $toast->user->username = $toast_data["user"]["user_name"];
            $toast->user->first_name = $toast_data["user"]["first_name"];
            $toast->user->last_name = $toast_data["user"]["last_name"];
            $toast->user->photo = $toast_data["user"]["user_avatar"];
            $toast->user->bio = $toast_data["user"]["bio"];
            $toast->user->location = $toast_data["user"]["location"];
            $toast->user->relationship = $toast_data["user"]["relationship"];
            array_push($data->toasts, $toast);
        }
        array_push($checkins, $data);
    }
    if ($full) {
        return json_encode($checkins, JSON_UNESCAPED_SLASHES);
    }
    else {
        $file_contents = new stdClass();
        $file_contents->username = $username;
        $file_contents->updated = $now->format("U");
        $file_contents->checkins = array();
        foreach ($checkins as $checkin) {
            array_push($file_contents->checkins, $checkin->id);
        }
        return json_encode($file_contents, JSON_UNESCAPED_SLASHES);
    }
}

?>
