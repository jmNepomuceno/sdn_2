<?php
    include ('../../session.php');
    include('../db/mysqlconnection.php');
    date_default_timezone_set('Asia/Manila');

    $currentDateTime = date('Y-m-d H:i:s');
    $hpercode = $_POST['hpercode'];
    $final_time = $_POST['final_time'];
    $approved_by = $_SESSION['name'];


    $sql = 'UPDATE incoming_referrals SET status_interdept="Approved" WHERE hpercode=:hpercode';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hpercode', $hpercode, PDO::PARAM_STR);
    $stmt->execute();

    $sql = 'UPDATE incoming_interdept SET final_progress_date=:final_progress_date , final_progress_time=:final_progress_time, approved_by=:approved_by, interdept_status="Approved" WHERE hpercode=:hpercode';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':hpercode', $hpercode, PDO::PARAM_STR);
    $stmt->bindParam(':final_progress_date', $currentDateTime, PDO::PARAM_STR);
    $stmt->bindParam(':final_progress_time', $final_time, PDO::PARAM_STR);
    $stmt->bindParam(':approved_by', $approved_by, PDO::PARAM_STR);
    $stmt->execute();

    $sql = "UPDATE incoming_referrals SET last_update=:currentDateTime WHERE hpercode=:hpercode";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':currentDateTime', $currentDateTime, PDO::PARAM_STR);
    $stmt->bindParam(':hpercode', $hpercode, PDO::PARAM_STR);
    $stmt->execute();

    $_SESSION['update_current_date'] = $currentDateTime;

    // SQL query to fetch data from your table
    // echo  "here"; 
    $sql = "SELECT * FROM incoming_interdept WHERE (interdept_status='Pending' OR interdept_status='On-Process') AND department = :data_dept ORDER BY recept_time ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':data_dept', $_SESSION['department_name'], PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // echo json_encode($data);
    $index = 0;
    $previous = 0;
    $loop = 0;
    // Loop through the data and generate table rows
    foreach ($data as $row) {
        if($previous == 0){
            $index += 1;
        }else{
            if($data[0]['reference_num'] == $previous){
                $index += 1;
            }else{
                $index = 1;
            }  
        }

        $style_tr = '';
        if($loop != 0 ){
            $style_tr = 'opacity:0.5; pointer-events:none;';
        }

        $sql = "SELECT reference_num, patlast, patfirst, patmiddle, status_interdept FROM incoming_referrals WHERE hpercode='". $row['hpercode'] ."' ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $stopwatch = "00:00:00";
        if($row['final_progress_time'] != ""){
            $stopwatch = $row['final_progress_time'];
        }
        echo '<tr class="tr-incoming-interdept" style="'. $style_tr .'">
                <td id="dt-refer-no"> ' . $data[0]['reference_num'] . ' - '.$index.' </td>
                <td id="dt-patname">' . $data[0]['patlast'] , ", " , $data[0]['patfirst'] , " " , $data[0]['patmiddle']  . '</td>
                <td id="dt-turnaround"> 
                    '.$row['recept_time'].'
                </td>
                <td id="dt-stopwatch">
                    <div id="stopwatch-sub-div">
                        Processing: <span class="stopwatch">'.$stopwatch.'</span>
                    </div>
                </td>
                
                <td id="dt-status">
                    <div> 
                        <label class="pat-status-incoming">'.$data[0]['status_interdept'].'</label>
                        <i class="pencil-btn fa-solid fa-pencil"></i>
                        <input class="hpercode" type="hidden" name="hpercode" value= ' . $row['hpercode'] . '>
                    </div>
                </td>
            </tr>';

        $previous = $data[0]['reference_num'];
        $loop += 1;
    }

    // refresh the value of the session timers
    $_SESSION['running_timer'] = 0; // elapsedTime
    $_SESSION['running_bool'] = false;
    $_SESSION['running_startTime'] = null;

    $_SESSION['running_hpercode'] = "";
    $_SESSION['running_index'] = null;
?>  