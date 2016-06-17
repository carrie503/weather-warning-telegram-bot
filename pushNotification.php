<?php

    function pushNotification() {
        apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "hi"));
        $url = "http://rss.weather.gov.hk/rss/WeatherWarningBulletin.xml";
        $xml = simplexml_load_file($url);
        $pubDate = $xml->channel->pubDate;
        $sql = "SELECT * FROM rssLastPubDate";
        $result = $conn->query($sql);
        print_r($result);
        if($result->num_rows == 0) {
            $sql = "INSERT INTO rssLastPubDate (last_pub_date) VALUES ('$pubDate')";
            $execute = $conn->query($sql);
        }
        $pubDate = new DateTime($xml->channel->pubDate);
        checkUpdate();
    }

    function checkUpdate() {
        global $xml;
        global $conn;

        $sql = "SELECT last_pub_date FROM rssLastPubDate";
        $result = $conn->query($sql);
        $fetchResult = $result->fetch_assoc();
        $lastPubDateString = $fetchResult["last_pub_date"];
        $lastPubDate = new DateTime($lastPubDateString);
        print_r($lastPubDate);
        $item = $xml->channel->item;
        $i = 0;
        $update = false;
        $content = "";
        while($item[$i] -> pubDate != "") {
            $itemPubDateString = $item[$i]->pubDate;
            //$itemPubDateString = "Fri, 17 Jun 2016 19:30:47 +0800";
            $itemPubDate = new DateTime($itemPubDateString);
            if($itemPubDate > $lastPubDate) {
                $update = true;
                $title = $item[$i]->title;
                $content .= "$title\n";
            }
            $i++;
        }
        if($update) {
            $sqlFindSubscribeUser = "SELECT user_id FROM subscribers WHERE subscribe = 1";
            $result = $conn->query($sqlFindSubscribeUser);
            if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $chat_id = $row["user_id"];
                apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "$content"));
            }
            //$pubDate = $item[0]->pubDate;
            $sql = "UPDATE rssLastPubDate SET last_pub_date = '$itemPubDateString' WHERE last_pub_date = '$lastPubDateString'";
            $result = $conn->query($sql);
            }
        }
    }
?>