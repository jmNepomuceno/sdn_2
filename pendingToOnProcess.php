<?php
    include ('../../session.php');
    include('../db/mysqlconnection.php');

    $hpercode = $_POST['hpercode'];

    // if($_POST['from'] === 'incoming'){
    //     $sql = "UPDATE incoming_referrals SET status='On-Process' WHERE hpercode= '". $hpercode ."' ";
    // }else{
    //     $sql = "UPDATE incoming_referrals SET status_interdept='On-Process' WHERE hpercode= '". $hpercode ."' ";
    // }
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    $sql = "UPDATE incoming_referrals SET status_interdept='On-Process' WHERE hpercode= '". $hpercode ."' ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "UPDATE incoming_interdept SET interdept_status='On-Process' WHERE hpercode= '". $hpercode ."' ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
?>