<?php
    include ('../../session.php');
    include('../db/mysqlconnection.php');
    date_default_timezone_set('Asia/Manila');

    // insert the data into incoming_interdept
    $currentDateTime = date('Y-m-d H:i:s');
    
    $name = $_SESSION["name"];

    $sql = "UPDATE incoming_interdept SET referring_seenBy=:referring_seenBy, referring_seenTime=:referring_seenTime, unRead=0 WHERE unRead = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':referring_seenBy', $name, PDO::PARAM_STR);
    $stmt->bindParam(':referring_seenTime', $currentDateTime, PDO::PARAM_STR);
    $stmt->execute();

    // $response = json_encode($data);
    echo $name;
?>