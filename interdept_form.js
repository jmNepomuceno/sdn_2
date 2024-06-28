$(document).ready(function(){
    $('#myDataTable').DataTable({
        "bSort": false,
        "paging": true, 
        "pageLength": 6, 
        "lengthMenu": [ [6, 10, 25, 50, -1], [6, 10, 25, 50, "All"] ],
    });

    var dataTable = $('#myDataTable').DataTable();
    $('#myDataTable thead th').removeClass('sorting sorting_asc sorting_desc');
    dataTable.search('').draw(); 

    let global_index = 0, global_paging = 1, global_timer = "", global_breakdown_index = 0;
    const myModal = new bootstrap.Modal(document.getElementById('pendingModal'));
    const defaultMyModal = new bootstrap.Modal(document.getElementById('myModal-incoming'));
    // myModal.show()

    let startTime;
    let elapsedTime = 0;
    let running = false;
    let requestId;
    let lastLoggedSecond = 0;

    let userIsActive = true;

    function handleUserActivity() {
        userIsActive = true;
        // console.log('active')
    }

    function handleUserInactivity() {
        console.log('inactive')
        userIsActive = false;
        $.ajax({
            url: '../Includes/sdn_php/fetch_interval.php',
            method: "POST",
            data : {
                from_where : 'incoming_interdept'
            },
            success: function(response) {
                // console.log(response)

                dataTable.clear();
                dataTable.rows.add($(response)).draw();

                const pencil_elements = document.querySelectorAll('.pencil-btn');
                    pencil_elements.forEach(function(element, index) {
                    element.addEventListener('click', function() {
                        console.log('den')
                        ajax_method(index)
                    });
                });
            }
        });
    }

    document.addEventListener('mousemove', handleUserActivity);

    const inactivityInterval = 1115000; 

    function startInactivityTimer() {
        inactivityTimer = setInterval(() => {
            if (!userIsActive) {
                handleUserInactivity();
            }
            userIsActive = false;
            
        }, inactivityInterval);
    }

    startInactivityTimer();



    const ajax_method = (index, event) => {
        global_index = index
        const data = {
            hpercode: document.querySelectorAll('.hpercode')[index].value,
            from:'incoming'
        }
        console.log(data)
        $.ajax({
            url: './process_pending.php',
            method: "POST", 
            data:data,
            success: function(response){
                document.querySelector('.ul-div').innerHTML = ''
                document.querySelector('.ul-div').innerHTML += response

                if(document.querySelectorAll('.pat-status-incoming')[index].textContent == 'Pending'){
                    runTimer(index) // secs, minutes, hours
                    let data = {
                        hpercode : document.querySelectorAll('.hpercode')[index].value,
                        from : 'interdept'
                    }
                    $.ajax({
                        url: './pendingToOnProcess.php',
                        method: "POST", 
                        data:data
                    })
                }

                // checking if the patient is already referred interdepartamentally
                // console.log(data)
                // $.ajax({
                //     url: '../php_2/check_interdept_refer.php',
                //     method: "POST", 
                //     data:data,
                //     success: function(response){
                //         console.log(response)
                //         if(response === '1'){
                //             $('#approval-form').css('display','none')
                //             $('.interdept-div-v2').css('display','flex')
                //             $('#cancel-btn').css('display','block')
                //         }
                //     }
                // })

                myModal.show();
            }
        })
    }

    const pencil_elements = document.querySelectorAll('.pencil-btn');
        pencil_elements.forEach(function(element, index) {
        element.addEventListener('click', function() {
            console.log('den')

            ajax_method(index)

            // lobal_index = index

            // possible redundant
            // const data = {
            //     hpercode: document.querySelectorAll('.hpercode')[index].value,
            //     from: 'incoming'
            // }
            // $.ajax({
            //     url: '../php/process_pending.php',
            //     method: "POST", 
            //     data:data,
            //     success: function(response){
            //         document.querySelector('.ul-div').innerHTML = ''
            //         document.querySelector('.ul-div').innerHTML += response
                    
            //         // if(document.querySelectorAll('.pat-status-incoming')[index].textContent == 'Pending'){
            //         //     console.log('here')
            //         //     runTimer(index, 0, 0, 0) // secs, minutes, hours
            //         // }
            //         myModal.show();

            //     }
            // })
        });
    });

    function loadStateFromSession() {
        console.log(post_value_reload, typeof post_value_reload)
        // upon logout
        if(post_value_reload === 'true'){
            console.log('366')
            $.ajax({
                url: '../save_process_time.php',
                method: "POST",
                data : {what: 'continue'},
                dataType : 'JSON',
                success: function(response){
                    running_timer_var = response[0].progress_timer
                    post_value_reload_bool = (post_value_reload === "true") ? true : false;

                    running_bool_var =  (running_bool_var === "true") ? true : false;
                    // initialize mo na lang na false agad yung running na session, tanggalin mo na yung global variable sa taas(?)
                    // tapos ayusin mo yung code mo, nakadepende pa din sa hpercode, depende mo sa referral ID dapat pag multiple referral per account.
                    // running_bool_var = true

                    elapsedTime = (running_timer_var || 0) * 1000; // Convert seconds to milliseconds
                    startTime = running_startTime_var ? running_startTime_var : performance.now() - elapsedTime;
                    running = running_bool_var || false;

                    startTime = performance.now() - elapsedTime;
                    requestId = requestAnimationFrame(runTimer(0).updateTimer);
                }
            })
        }else{
            console.log('390')
            running_bool_var =  (running_bool_var === "true") ? true : false;
            elapsedTime = (running_timer_var || 0) * 1000; // Convert seconds to milliseconds
            startTime = running_startTime_var ? running_startTime_var : performance.now() - elapsedTime;
            running = running_bool_var || false;
    
    
            // if (running && previous_loadcontent === "incoming_ref") {
            //     startTime = performance.now() - elapsedTime;
            //     requestId = requestAnimationFrame(runTimer(0).updateTimer);
            // }

            if (running) {
                startTime = performance.now() - elapsedTime;
                requestId = requestAnimationFrame(runTimer(0).updateTimer);
            }
        }
    }
    // sevich
    // on load
    loadStateFromSession()

    // when refresh
    function saveTimeSession(){
        // look only for the status that is On-Process

        let curr_index = 0;
        for(let i = 0; i < document.querySelectorAll('.pat-status-incoming').length; i++){
            if(document.querySelectorAll('.pat-status-incoming')[i].textContent === "On-Process"){
                curr_index = i;
            }
        }

        console.log({
            timer : elapsedTime / 1000,
            running_bool : running,
            startTime : running ? performance.now() : startTime,
            hpercode: document.querySelectorAll('.hpercode')[curr_index].value,
            index: curr_index // questionable)
        })
        
        $.ajax({
            url: '../php_2/fetch_onProcess.php',
            method: "POST", 
            data:{
                // timer: document.querySelectorAll('.stopwatch')[curr_index].textContent,
                timer : elapsedTime / 1000,
                running_bool : running,
                startTime : running ? performance.now() : startTime,
                hpercode: document.querySelectorAll('.hpercode')[curr_index].value,
                index: curr_index // questionable
            },
            success: function(response){
                // console.log(response)
            }
        })
    }
        
    window.addEventListener('beforeunload', function(event) {
        // e.preventDefault()
        saveTimeSession()
    });

    function pad(num) {
        return (num < 10 ? '0' : '') + num;
    }

    let interval_db = 0;
    
    function runTimer(index) {
        function formatTime(milliseconds) {
            const totalSeconds = Math.floor(milliseconds / 1000);
            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }

        function updateTimer() {
            if (!running) return;

            const now = performance.now();
            elapsedTime = now - startTime;
            const secondsElapsed = Math.floor(elapsedTime / 1000);

            if (secondsElapsed > lastLoggedSecond) {
                // console.log(secondsElapsed);
                lastLoggedSecond = secondsElapsed;

                global_timer = formatTime(elapsedTime);

                if(document.querySelectorAll('.pat-status-incoming').length > 0){
                    if (global_paging === 1) {
                        // console.log(document.querySelectorAll('.stopwatch').length, index)
                        document.querySelectorAll('.stopwatch')[index].textContent = formatTime(elapsedTime);

                        document.querySelectorAll('.pat-status-incoming')[index].textContent = 'On-Process';
                    }
        
                    // console.log("global_timer: " + global_timer);
                    let curr_index = 0;
                    for(let i = 0; i < document.querySelectorAll('.pat-status-incoming').length; i++){
                        if(document.querySelectorAll('.pat-status-incoming')[i].textContent === "On-Process"){
                            curr_index = i;
                        }
                    }

                    $.ajax({
                        url: './fetch_onProcess.php',
                        method: "POST", 
                        data:{
                            // timer: document.querySelectorAll('.stopwatch')[curr_index].textContent,
                            timer : elapsedTime / 1000,
                            running_bool : running,
                            startTime : running ? performance.now() : startTime,
                            hpercode: document.querySelectorAll('.hpercode')[curr_index].value,
                            index: curr_index // questionable
                        },
                        success: function(response){
                            // console.log(response)
                        }
                    })

                }else{
                    if (global_paging === 1) {
                        document.querySelectorAll('.stopwatch')[index].textContent = formatTime(elapsedTime);
                    }
                }
            }
            requestId = requestAnimationFrame(updateTimer);
        }

        function start() {
            if (running) return;

            running = true;
            startTime = performance.now() - elapsedTime;
            requestId = requestAnimationFrame(updateTimer);
            // saveStateToSession(); // Save state whenever the timer is started
        }

        function stop() {
            running = false;
            cancelAnimationFrame(requestId);
            // saveStateToSession(); // Save state whenever the timer is stopped
        }

        function reset() {
            running = false;
            elapsedTime = 0;
            document.getElementById('timer').textContent = '00:00:00';
            lastLoggedSecond = 0;
            cancelAnimationFrame(requestId);
            saveStateToSession(); // Save state whenever the timer is reset
        }
    
        // Start the timer
        start();
    
        // Expose control functions
        return { start, stop, reset, updateTimer };
    }
    $('.pre-emp-text').on('click' , function(event){
        var originalString = event.target.textContent;
        // Using substring
        var stringWithoutPlus = originalString.substring(2);

        // Or using slice
        // var stringWithoutPlus = originalString.slice(2);
        $('#eraa').val($('#eraa').val() + " " + stringWithoutPlus  + " ")
    })

    $('#inter-approval-btn').on('click' , function(event){
        defaultMyModal.show()
    })

    // yes-modal-btn-incoming
    $('#yes-modal-btn-incoming').on('click' , function(event){
        clearInterval(running_timer_interval)
        let data = {
            hpercode: document.querySelectorAll('.hpercode')[0].value,
            final_time : global_timer
            // wala pang approved by
        }
        console.log(data)
        $.ajax({
            url: './Includes/sdn_php/approve_pending_interdept.php',
            method: "POST", 
            data:data,
            success: function(response){
                // response = JSON.parse(response);   
                // console.log(response)
                
                myModal.hide()
                document.querySelectorAll('.pat-status-incoming')[global_index].textContent = 'Approved'

                dataTable.clear();
                dataTable.rows.add($(response)).draw();
                
                length_curr_table = $('.tr-incoming').length
                for(let i = 0; i < length_curr_table; i++){
                    toggle_accordion_obj[i] = true
                }
                
                const pencil_elements = document.querySelectorAll('.pencil-btn');
                    pencil_elements.forEach(function(element, index) {
                    element.addEventListener('click', function() {
                        console.log('den')
                        ajax_method(index)
                    });
                });
            }
        })
    })

    // styling
    // .interdept-container .interdept-main-container .interdept-side-bar #interdept-sub-side-bar #incoming-req-div h3:hover,
    // .interdept-container .interdept-main-container .interdept-side-bar #interdept-sub-side-bar #history-div h3:hover{
    //     color:#009688;
    // }

    $('#incoming-req-div').on('mouseenter' , function(event){
        console.log('here')
        $('#incoming-req-div').css('background' , 'white')
        $('#incoming-req-div i').css('color' , '#009688')
        $('#incoming-req-div h3').css('color' , '#009688')
    })

    $('#incoming-req-div').on('mouseleave' , function(event){
        $('#incoming-req-div').css('background' , '#009688')
        $('#incoming-req-div i').css('color' , 'white')
        $('#incoming-req-div h3').css('color' , 'white')
    })

    $('#history-div').on('mouseenter' , function(event){
        $('#history-div').css('background' , 'white')
        $('#history-div i').css('color' , '#009688')
        $('#history-div h3').css('color' , '#009688')
    })

    $('#history-div').on('mouseleave' , function(event){
        $('#history-div').css('background' , '#009688')
        $('#history-div i').css('color' , 'white')
        $('#history-div h3').css('color' , 'white')
    })


})