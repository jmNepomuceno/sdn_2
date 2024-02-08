<?php
    include("../database/connection2.php");

    $search_lname = $_POST['search_lname'];
    $search_fname = $_POST['search_fname'];
    $search_mname = $_POST['search_mname'];

    $sql = "none";
    $search_lname = filter_input(INPUT_POST, 'search_lname');
    $search_fname = filter_input(INPUT_POST, 'search_fname');
    $search_mname = filter_input(INPUT_POST, 'search_mname');


    $conditions = array();

    if (!empty($search_lname)) {
        $conditions[] = "patlast LIKE :search_lname";
    }

    if (!empty($search_fname)) {
        $conditions[] = "patfirst LIKE :search_fname";
    }

    if (!empty($search_mname)) {
        $conditions[] = "patmiddle LIKE :search_mname";
    }

    $sql = "SELECT patfirst, patlast, patmiddle, hpercode, patbdate, status FROM hperson";

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $pdo->prepare($sql);

    if (!empty($search_lname)) {
        $search_lname_param = "%$search_lname%";
        $stmt->bindParam(':search_lname', $search_lname_param, PDO::PARAM_STR);
    }

    if (!empty($search_fname)) {
        $search_fname_param = "%$search_fname%";
        $stmt->bindParam(':search_fname', $search_fname_param, PDO::PARAM_STR);
    }

    if (!empty($search_mname)) {
        $search_mname_param = "%$search_mname%";
        $stmt->bindParam(':search_mname', $search_mname_param, PDO::PARAM_STR);
    }

    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $finalJsonString = json_encode($data);
    echo $finalJsonString;

    // if($sql != "none"){
        
    // }else{
    //     echo "No User Found";
    // }
?>