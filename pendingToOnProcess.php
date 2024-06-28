<?php
    include('./connection2.php');
    session_start();
    $hpercode = $_POST['hpercode'];

    // if($_POST['from'] === 'incoming'){
    //     $sql = "UPDATE incoming_referrals SET status='On-Process' WHERE hpercode= '". $hpercode ."' ";
    // }else{
    //     $sql = "UPDATE incoming_referrals SET status_interdept='On-Process' WHERE hpercode= '". $hpercode ."' ";
    // }

    $sql = "UPDATE incoming_referrals SET status_interdept='On-Process' WHERE hpercode= '". $hpercode ."' ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "UPDATE incoming_interdept SET interdept_status='On-Process' WHERE hpercode= '". $hpercode ."' ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
?>