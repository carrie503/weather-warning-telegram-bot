<?php
    $ip =  getenv("REMOTE_ADDR");
    $port = "3306";
    $username = "carrie503";
    $password = "";
    $dbname = "weatherWarningDb";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname) or die("Connection failed");
?>
