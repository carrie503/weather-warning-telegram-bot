<?php
    function getUserLanguage($chat_id) {
        global $conn;
        $sqlCheckIdExist = "SELECT language FROM subscribers WHERE user_id = $chat_id AND subscribe = 1";
        $result = $conn->query($sqlCheckIdExist);
        $subUser = false;
        if ($result->num_rows > 0) {
            $subUser = true;
            $itemResult = $result->fetch_assoc();
            $language = $itemResult['language'];
        }
        if(!$subUser) $language = "English";
        return $language;

    }

    function urlLanguage($functName, $language) {
        $url = "";
        switch ($functName) {
            case 'current':
                if($language == "English") $url = "http://rss.weather.gov.hk/rss/CurrentWeather.xml";
                else if($language == "Trad. Chinese") $url = "http://rss.weather.gov.hk/rss/CurrentWeather_uc.xml";
                //Since the xml of Simplifed Chinese is not working, it replaces by the xml of Traditional Chinese
                else if($language == "Simple. Chinese") $url = "http://rss.weather.gov.hk/rss/CurrentWeather_uc.xml";
                break;
            
            case 'warning':
                if($language == "English") $url = "http://rss.weather.gov.hk/rss/WeatherWarningBulletin.xml";
                else if($language == "Trad. Chinese") $url = "http://rss.weather.gov.hk/rss/WeatherWarningBulletin_uc.xml";
                else if($language == "Simple. Chinese") $url = "http://gbrss.weather.gov.hk/rss/WeatherWarningBulletin_uc.xml";
                break;
        }
        return $url;
    }

    function replyLanguage($language) {
        if($language == "English") $reply = "OK";
        else if($language == "Trad. Chinese" || $language == "Simple. Chinese") $reply = "知道了";
        return $reply;
    }
?>