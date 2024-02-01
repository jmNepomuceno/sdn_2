<?php
    session_start();
    include("../database/connection2.php");

    $classification = $_POST['classification'];
    echo $classification;

    $sql = "INSERT INTO classifications (classifications) VALUES (?)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(1, $classification, PDO::PARAM_STR);
    $stmt->execute();

    

?>