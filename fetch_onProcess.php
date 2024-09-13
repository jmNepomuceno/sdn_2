<?php 
    include ('../../session.php');
    include('../db/mysqlconnection.php');

    $_SESSION['running_timer'] = $_POST['timer']; 
    $_SESSION['running_bool'] = $_POST['running_bool'];
    $_SESSION['running_startTime'] = $_POST['startTime'];

    $_SESSION['running_hpercode'] = $_POST['hpercode'];
    $_SESSION['running_index'] = $_POST['index'];

    $time =  $_SESSION['running_timer'];

    $sql = "UPDATE incoming_interdept SET curr_time=:formattedTime WHERE hpercode=:hpercode";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':formattedTime', $time, PDO::PARAM_STR);
    $stmt->bindParam(':hpercode', $_SESSION['running_hpercode'], PDO::PARAM_STR);
    $stmt->execute();

    echo $_SESSION['running_timer'];
?>