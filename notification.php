<?php
    include ('../../session.php');
    include('../db/mysqlconnection.php');
    
    $dept_name;
    $sql = "SELECT unRead FROM incoming_interdept WHERE department=:dept_name AND unRead='1'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dept_name', $_SESSION['department_name'], PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = json_encode($data);
    echo $response;
    // echo '<pre>'; print_r($data); echo '</pre>';


?>