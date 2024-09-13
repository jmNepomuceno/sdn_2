<?php
    include 'session.php';
    include('./Includes/db/mysqlconnection.php');
    include './Includes/db/dbcredentials.php';

    $post_value_reload = '';

    $sql = "SELECT * FROM incoming_interdept WHERE curr_time IS NOT NULL";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($data) > 0){
        $_SESSION['post_value_reload'] = 'true';
        $post_value_reload = $_SESSION['post_value_reload'];
    }

    // echo count($data);

    // $sql = "UPDATE incoming_interdept SET unRead='0', interdept_status='On-Process', referring_seenTime=null, referring_seenBy=null, curr_time=null WHERE hpercode='PAT000023'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // $sql = "DELETE FROM incoming_interdept";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // $sql = "UPDATE incoming_interdept SET unRead='0', interdept_status='Pending', final_progress_date=null, final_progress_time=null WHERE hpercode='PAT000023'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // $sql = "UPDATE incoming_referrals SET status_interdept='On-Process' WHERE hpercode='PAT000023'";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();
    
?>

 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDN INTERDEPARTAMENTAL</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link rel="stylesheet" href="./Includes/css/interdept_form.css">
</head>
<body>
    <div class="interdept-container">
        
        <!-- <button class="btn btn-primary" type="button" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Loading...
        </button> -->

        <header class="interdept-header">
            <div class="interdept-title">
                SDN Interdepartament Referral
            </div>

            <div class="account-header-div">
                <div id="nav-account-div" class="header-username-div">
                    <i id="user-icon" class="fa-solid fa-user"></i>
                    <h1 id="dept-name-h1"> <?php echo $_SESSION['department_name'] ?> Department</h1>    
                    <i id="caret-icon" class="fa-solid fa-caret-down"></i>
                </div> 
            </div>
        </header>

    
        <main class="interdept-main-container">
            <aside class="interdept-side-bar">
                <div id="interdept-sub-side-bar">
                    
                    <div id="side-bar-title-bgh">
                        <!-- <img src="./includes/img/main_bg.png" alt="logo-img"> -->
                        <p id="bgh-name">Bataan General Hospital and Medical Center</p>
                    </div>

                    <div id="incoming-req-div">
                        <i class="fa-solid fa-retweet"></i>
                        <h3>Incoming Request</h3>
                    </div>

                    <div id="history-div">
                        <i class="fa-solid fa-retweet"></i>
                        <h3>History</h3>
                    </div>
                </div>
            </aside>

            <div class="interdept-main-div">
                
                <div class="search-main-div">
                    <!-- <div class="refer-no-div">
                        <label for="incoming-referral-no-search">Referral No.</label>
                        <input id="incoming-referral-no-search" type="textbox">
                    </div> -->
                
                    <div class="lname-search-div">
                        <label for="incoming-last-name-search">Last Name</label>
                        <input id="incoming-last-name-search" type="textbox">
                    </div>

                    <div class="fname-search-div">
                        <label  for="incoming-first-name-search">First Name</label>
                        <input id="incoming-first-name-search" type="textbox">
                    </div>

                    <div class="mname-search-div">
                        <label  for="incoming-middle-name-search">Middle Name</label>
                        <input id="incoming-middle-name-search" type="textbox">
                    </div>

                    <div class="status-search-div">
                        <label for="incoming-status-select">Status</label>
                        <select id='incoming-status-select'>
                            <option value="default">Select</option>
                            <option value="Pending">Pending</option>
                            <option value="All"> All</option>
                            <option value="On-Process"> On-Process</option>
                            <option value="Deferred"> Deferred</option>
                            <option value="Approved"> Approved</option>
                            <option value="Cancelled"> Cancelled</option>
                            <option value="Arrived"> Arrived</option>
                            <option value="Checked"> Checked</option>
                            <option value="Admitted"> Admitted</option>
                            <option value="Discharged"> Discharged</option>
                            <option value="For follow"> For follow up</option>
                            <option value="Referred"> Referred Back</option>
                        </select>
                    </div>

                    <div class="search-clear-btns-div">
                        <button id='incoming-clear-search-btn'>Clear</button>
                        <button id='incoming-search-btn'>Search</button>
                    </div>
                </div>

                <section class="interdept-incoming-table">
                    <table id="myDataTable" class="display table table-bordered custom-search-modal" style="width: 100%; border-spacing: -1px;">
                        <thead>
                            <tr class="interdept-tr">
                                <th id="refer-no">Reference No. </th>
                                <th>Patient's Name</th>
                                <th>Date/Time</th>
                                <th>Response Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="interdept-incoming-tbody">
                            <?php
                                // SQL query to fetch data from your table
                                // echo  "here";
                                try{
                                    // $sql = "SELECT * FROM incoming_interdept WHERE (interdept_status='Pending' OR interdept_status='On-Process') AND department = :data_dept ORDER BY recept_time ASC";
                                    $sql = "SELECT * FROM incoming_interdept WHERE (interdept_status='Pending' OR interdept_status='On-Process') AND department='IHOMP' ORDER BY recept_time ASC";
                                    $stmt = $pdo->prepare($sql);
                                    // $stmt->bindParam(':data_dept', $_SESSION['department_name'], PDO::PARAM_STR);
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
                                                        <label class="pat-status-incoming">'.$row['interdept_status'].'</label>
                                                        <i class="pencil-btn fa-solid fa-pencil" style="color:#cc9900;"></i>
                                                        <input class="hpercode" type="hidden" name="hpercode" value= ' . $row['hpercode'] . '>
                                                    </div>
                                                </td>
                                            </tr>';

                                        $previous = $data[0]['reference_num'];
                                        $loop += 1;
                                    }

                                    // Close the database connection
                                    $pdo = null;
                                }
                                catch(PDOException $e){
                                    echo "asdf";
                                }
                            ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </main>
    </div>

    <div class="modal fade" id="pendingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button>Print</button>
                    <button id="close-pending-modal" data-bs-dismiss="modal">Close</button>
                    <!-- <span aria-hidden="true">&times;</span> --> 
                </div>
                <div  class="modal-body-incoming">
                    <div class="status-form-div">
                        <label id="status-bg-div">Status: </label>
                        <label  id="pat-status-form">Pending</label>
                    </div>
                    
                    <div id='approval-form'>
                        <div id="inter-dept-stat-form-div" class="status-form-div">
                            <label id="status-bg-div">Approval Form </label>
                        </div>
                            
                        <div class="approval-main-content">

                            <label id="case-cate-title">Case Category</label>
                            <select id="approve-classification-select">
                                <option value="">Select</option>
                                <option value="Primary">Primary</option>
                                <option value="Secondary">Secondary</option>
                                <option value="Tertiary">Tertiary</option>
                            </select>

                            <label id="admin-action-title">Emergency Room Administrator Action</label>
                            <textarea id="eraa"></textarea>

                            <div id="pre-text">
                                <label class="pre-emp-text">+ May transfer patient once stable.</label>
                                <label class="pre-emp-text">+ Please attach imaging and laboratory results to the referral letter.</label>
                                <label class="pre-emp-text">+ Hook to oxygen support and maintain saturation at >95%.</label>
                                <label class="pre-emp-text">+ Start venoclysis with appropriate intravenous fluids.</label>
                                <label class="pre-emp-text">+ Insert nasogastric tube(NGT).</label>
                                <label class="pre-emp-text">+ Insert indwelling foley catheter(IFC).</label>
                                <label class="pre-emp-text">+ Thank you for your referral.</label>
                            </div>

                        </div> 
                    </div>

                    <div class="referral-details">
                        <div id="inter-dept-stat-form-div" class="status-form-div">
                            <label id="status-bg-div">Referral Details </label>
                        </div>
                        <div class="ul-div"></div>
                        <div id="approval-form-btns">
                            <button id="inter-dept-reject-btn"> Reject </button>
                            <button id="inter-approval-btn"> Approve </button>
                        </div>
                        <!-- timer stops -->
                        <!-- <ul class="list-none flex flex-col space-y-2">
                            <li><label class="font-bold">Referring Agency:</label><span id="refer-agency" class="break-words"></span></li>
                            <li><label class="font-bold">Reason for Referral:</label><span id="refer-reason" class="break-words"></span></li><br>
                
                            <li><label class="font-bold">Name:</label><span id="pending-name"  class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Birthday:</label><span id="pending-bday" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Age:</label><span id="pending-age" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Sex:</label><span id="pending-sex" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Civil Status:</label><span id="pending-civil" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Religion:</label><span id="pending-religion" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Address:</label><span id="pending-address" class="break-words">This is where you put the data</span></li><br>
                
                            <li><label class="font-bold">Parent/Guardian:</label><span id="pending-parent" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">PHIC Member:</label><span id="pending-phic" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Mode of Transport:</label><span id="pending-transport" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Date/Time Admitted:</label><span id="pending-admitted" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Referring Doctor:</label><span id="pending-referring-doc" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Contact #:</label><span id="pending-contact-no" class="break-words">This is where you put the data</span></li><br>

                            <li class="pending-type-ob hidden"><label class="font-bold underline">OB-Gyne</label><span id="pending-ob" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Last Menstrual Period:</label><span id="pending-last-mens" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Age of Gestation</label><span id="pending-gestation" class="break-words">This is where you put the data</span></li><br>
                
                            <li><label class="font-bold">Chief Complaint and History:</label><span id="pending-complaint-history" class="break-words">This is where you put the data</span></li><br>
                
                            <li><label class="font-bold">Physical Examination</label><span id="pending-pe" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Blood Pressure:</label><span id="pending-bp" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Heart Rate:</label><span id="pending-hr" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Respiratory Rate:</label><span id="pending-rr" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Temperature:</label><span id="pending-temp" class="break-words">This is where you put the data</span></li>
                            <li><label class="font-bold">Weight:</label><span id="pending-weight" class="break-words">This is where you put the data</span></li><br>

                            <li class="pending-type-ob hidden"><label class="font-bold">Fetal Heart Tone:</label><span id="pending-heart-tone" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Fundal Height:</label><span id="pending-fundal-height" class="break-words">This is where you put the data</span></li><br>

                            <li class="pending-type-ob hidden"><label class="font-bold underline">Internal Examination</label><span id="pending-ie" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Cervical Dilatation:</label><span id="pending-cd" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Bag of Water:</label><span id="pending-bag-water" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Presentation:</label><span id="pending-presentation" class="break-words">This is where you put the data</span></li>
                            <li class="pending-type-ob hidden"><label class="font-bold">Others:</label><span id="pending-others" class="break-words">This is where you put the data</span></li><br>
                
                            <li><label class="font-bold">Pertinent PE Findings:</label><span id="pending-p-pe-find" class="break-words">This is where you put the data</span></li><br>
                
                            <li><label class="font-bold">Impression / Diagnosis:</label><span id="pending-diagnosis" class="break-words">This is where you put the data</span></li>
                        </ul> -->
                    </div>

                    <!-- <div id='approval-form'>
                        <label id="approval-title-div">Approval Form</label>
                    </div> -->

                </div>

                <div class="modal-footer">
                    <!-- <button id="ok-modal-btn-incoming" type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">OK</button>
                    <button id="yes-modal-btn-incoming" type="button" class="hidden bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">Yes</button>
                 -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal-incoming" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header flex flex-row justify-between items-center">
                <div class="flex flex-row justify-between items-center">
                    <h5 id="modal-title-incoming" class="modal-title-incoming" id="exampleModalLabel">Confirmation</h5>
                    <i id="modal-icon" class="fa-solid fa-triangle-exclamation ml-2"></i>
                    <!-- <i class="fa-solid fa-circle-check"></i> -->
                </div>
                <button type="button" class="close text-3xl" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modal-body-incoming" class="modal-body-incoming ml-2">
                Are you sure you want to approve this patient?
            </div>
            <div class="modal-footer">
                <button id="ok-modal-btn-incoming" type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">No</button>
                <button id="yes-modal-btn-incoming" type="button" class="hidden bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2" data-bs-dismiss="modal">Yes</button>
            </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script type="text/javascript"  charset="utf8" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

    <script src="./Includes/js/interdept_form.js?v= <?php echo time(); ?>"></script>

    <script>                        
        var post_value_reload  = <?php echo json_encode($post_value_reload); ?>;

        var running_timer_var = <?php echo json_encode(floatval($_SESSION['running_timer'])); ?>;
        var running_bool_var = <?php echo json_encode($_SESSION['running_bool']); ?>;
        var running_startTime_var = <?php echo json_encode($_SESSION['running_startTime']); ?>;
    </script>
</body>
</html>
