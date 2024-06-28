<?php 
    include('./connection2.php');
    session_start();

    $_SESSION['running_bool'] = false;
    $_SESSION['running_startTime'] = "";
    $_SESSION['running_timer'] = "";
    $_SESSION['running_hpercode'] = "";
    $_SESSION['running_index'] = "";
    $_SESSION['name'] = "John Marvin Gomez Nepomuceno";
    $_SESSION['post_value_reload'] = "";
    
    // header('Location: ./incoming_interdept.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="/assets/main_imgs/favicon/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>Document</title>

    <style>
        #notif-circle{
            /* position: absolute;
            top:15%; */
            text-align: center;
            width: 30px;
            height: 30px;
            border-radius: 100%;
            background: red;
            margin-left: 1rem;
            color:white;
            font-size: 0.75rem;
        }

        #notif-span{
            text-align: center;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <h1 id="notif-circle" style="display:block;"><span id="notif-span">0</span></h1>
    <button>Check</button>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script type="text/javascript"  charset="utf8" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

    <script>

        $('button').on('click' , () =>{
            $.ajax({
                url: './seenBy.php',
                method: "POST",
                success: function(response) {
                    console.log(response)
                    window.location.href = "./incoming_interdept.php"
                }
            });

            // if(curr_notif_length === 0){ 
            //     $('#sdn-referralunread').text("No Active Referrals");
            // }

            // // if unreadNotif == 0
            // if ($('#sdn-referraldropdownmenu').css('display') === 'none') {
            //     $('#sdn-referraldropdownmenu').css('display', 'block');
            // } else {
            //     $('#sdn-referraldropdownmenu').css('display', 'none');
            // }

            // // $('#sdn-referralunread').text(response.length + " Referral(s)");

            // // reset the value of the referralIcon when clicked
            // if(curr_notif_length > 0){
            //     $('#sdn-referralunread').text(curr_notif_length + " Referral(s) - Notification will be gone, so please on the request.")
            //     curr_notif_length = 0;
            //     $('#sdn-referralbadge').text(0).hide();

            //     $.ajax({
            //         url: 'Includes/sdn_php/seenBy.php',
            //         method: "POST",
            //         success: function(response) {
            //             console.log(response)
            //         }
            //     });
            // }
        })
    </script>
</body>
</html>