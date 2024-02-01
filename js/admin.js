$(document).ready(function(){
    let intervalHistoryLog;
  
    let inactivityTimer;
    let userIsActive = true;
    function handleUserActivity() {
        userIsActive = true;
        // Additional code to handle user activity if needed
        // console.log('active')
        clearInterval(intervalHistoryLog)
  
    }
  
    function handleUserInactivity() {
        // console.log('inactive')
        userIsActive = false;
        // Additional code to handle user inactivity if needed
        // intervalHistoryLog = setInterval(fetchHistoryLog, 10000);
    }
  
    // Attach event listeners
    document.addEventListener('mousemove', handleUserActivity);
  
    // Set up a timer to check user inactivity periodically
    const inactivityInterval = 10000; // Execute every 5 seconds (adjust as needed)
  
    function startInactivityTimer() {
        inactivityTimer = setInterval(() => {
            if (!userIsActive) {
                handleUserInactivity();
            }
            userIsActive = false; // Reset userIsActive after each check
            
        }, inactivityInterval);
    }
  
    function resetInactivityTimer() {
        clearInterval(inactivityTimer);
  
        startInactivityTimer();
    }
  
    // Start the inactivity timer when the page loads
    startInactivityTimer();
  
    //----------------------------------------------------------------------------
  
      $('#total-processed-refer').text($('#total-processed-refer-inp').val())
    // console.log($('#total-processed-refer-inp').val())
    const playAudio = () =>{
      let audio = document.getElementById("notif-sound")
      audio.muted = false;
      audio.play().catch(function(error){
          'Error playing audio: ' , error
      })
    }
  
    $('#history-select').change(function() {
      var selectedValue = $(this).val();
  
      if(selectedValue === 'login'){
        selectedValue = 'user_login'
      }else if(selectedValue === 'incoming'){
        selectedValue = 'pat_refer'
      }else if(selectedValue === 'register'){
        selectedValue = 'pat_form'
      }else if(selectedValue === 'outgoing'){
        selectedValue = 'pat_defer'
      }else{
        selectedValue = 'all'
      }
  
      $.ajax({
        url: '../php/history_filter.php',
        method: "POST",
        data : {
          option : selectedValue
        },
        success: function(response) {
            let historyDiv = document.querySelector('.history-container')
  
            if (historyDiv) {
                while (historyDiv.firstChild) {
                    historyDiv.removeChild(historyDiv.firstChild);
                }
            }
  
            document.querySelector('.history-container').innerHTML = response
        }
      });
    });
  
    const loadContent = (url) => {
      $.ajax({
          url:url,
          success: function(response){
              // console.log(response)
              $('#container').html(response);
          }
      })
    }
  
    
    function fetchMySQLData() {
      $.ajax({
          url: '../php/fetch_interval.php',
          method: "POST",
          data : {
              from_where : 'bell'
          },
          success: function(data) {
              $('#notif-span').text(data);
              if (parseInt(data) >= 1) {
                  $('#notif-circle').removeClass('hidden');
                  
                  playAudio();
              } else {
                  $('#notif-circle').addClass('hidden');
              }
              
              setTimeout(fetchMySQLData, 10000);
          }
      });
    }
  
    fetchMySQLData(); 
  
      $('#side-bar-mobile-btn').on('click' , function(event){
        document.querySelector('#side-bar-div').classList.toggle('hidden');
      })
  
    $('#logout-btn').on('click' , function(event){
      event.preventDefault(); // Prevent the default behavior (navigating to the link)
      console.log('den')
  
      $('#modal-title-main').text('Warning')
      // $('#modal-body').text('Are you sure you want to logout?')
      $('#ok-modal-btn-main').text('No')
  
      $('#yes-modal-btn-main').text('Yes');
      $('#yes-modal-btn-main').removeClass('hidden')
  
      $('#myModal-main').modal('show');
    })
    
    $('#yes-modal-btn-main').on('click' , function(event){
      console.log('here')
      document.querySelector('#nav-drop-account-div').classList.toggle('hidden');
  
      let currentDate = new Date();
  
      let year = currentDate.getFullYear();
      let month = currentDate.getMonth() + 1; // Adding 1 to get the month in the human-readable format
      let day = currentDate.getDate();
  
      let hours = currentDate.getHours();
      let minutes = currentDate.getMinutes();
      let seconds = currentDate.getSeconds();
  
      let final_date = year + "/" + month + "/" + day + " " + hours + ":" + minutes + ":" + seconds
  
      $.ajax({
          url: '../php/save_process_time.php',
          data : {  
              what: 'save',
              date : final_date,
              sub_what: 'history_log'
          },
          method: "POST",
          success: function(response) {
              // response = JSON.parse(response);  
              console.log(response , " here")
              window.location.href = "http://192.168.42.222:8035/index.php" 
          }
      });
  })
  
    $('#nav-account-div').on('click' , function(event){
      event.preventDefault();
      document.querySelector('#nav-drop-account-div').classList.toggle('hidden');
    })
  
    $('#dashboard-incoming-btn').on('click' , function(event){
      event.preventDefault();
      window.location.href = "../php/dashboard_incoming.php";
    })
  
    $('#dashboard-outgoing-btn').on('click' , function(event){
        event.preventDefault();
        window.location.href = "../php/dashboard_outgoing.php";
    })
  
    $('#sdn-title-h1').on('click' , function(event){
      event.preventDefault();
      window.location.href = "../main.php";
    })
  
    $('#incoming-sub-div-id').on('click' , function(event){
      event.preventDefault();
      window.location.href = "../main.php";
    })

    $('#history-log-btn').on('click' , function(event){
        event.preventDefault();
        window.location.href = "../php/history_log.php";
    })


    // add new classification
    $('#add-classification-btn').on('click' , function(event){
      console.log($('#add-classification-input').val())
      $.ajax({
          url: '../php/add_classification.php',
          data : {  
              classification : $('#add-classification-input').val()
          },
          method: "POST",
          success: function(response) {
              // response = JSON.parse(response); 
              console.log(response)
              $('#add-classification-icon').removeClass('hidden')
            $('#add-classification-input').addClass('hidden')
          }
      });
  })

  $('#add-classification-icon').on('click' , function(event){
    $('#add-classification-icon').addClass('hidden')
    $('#add-classification-input').removeClass('hidden')
  })

  let toggle_accordion_obj = {}
  let global_breakdown_index = 0
  for(let i = 0; i < document.querySelectorAll('.table-tr').length; i++){
      toggle_accordion_obj[i] = true
  }
  console.log(toggle_accordion_obj)

  const expand_elements = document.querySelectorAll('.see-more-btn');
  expand_elements.forEach(function(element, index) {
      element.addEventListener('click', function() {
          global_breakdown_index = index;
      });
  });

  const edit_info_elements = document.querySelectorAll('.edit-info-btn');
  edit_info_elements.forEach(function(element, index) {
      element.addEventListener('click', function() {
          global_breakdown_index = index;
      });
  });

  $('.see-more-btn').on('click' , function(event){
    console.log(document.querySelectorAll('.number_users')[global_breakdown_index])
      if(toggle_accordion_obj[global_breakdown_index]){
          document.querySelectorAll('.table-tr')[global_breakdown_index].style.height = "350px"
          document.querySelectorAll('.breakdown-div')[global_breakdown_index].style.display = 'flex'
          document.querySelectorAll('.number_users')[global_breakdown_index].style.display = 'none'
          toggle_accordion_obj[global_breakdown_index] = false
      }else{
          document.querySelectorAll('.table-tr')[global_breakdown_index].style.height = "50px"
          document.querySelectorAll('.breakdown-div')[global_breakdown_index].style.display = 'none'
          document.querySelectorAll('.number_users')[global_breakdown_index].style.display = 'flex'
          toggle_accordion_obj[global_breakdown_index] = true
      }
  })

  
  $('.edit-info-btn').on('click' , function(event){
    console.log('global_breakdown_index: ' + global_breakdown_index + " ------  ")
    console.log($('.edit-users-info').eq(global_breakdown_index + 4).val());
  })

  $('#myModal-hospitalAndUsers').modal('show');

})





























// Get the elements
const dynamicWidthDiv = document.getElementById('dynamic-width-div');
const inputField = document.getElementById('add-classification-input');

// Listen for input events on the input field
inputField.addEventListener('input', function(event) {
    if (event.inputType === 'deleteContentBackward') {
      dynamicWidthDiv.style.width = inputField.scrollWidth - 8 + 'px';
      inputField.style.width = inputField.scrollWidth - 8 + 'px';
    }else{
      dynamicWidthDiv.style.width = inputField.scrollWidth + 'px';
      inputField.style.width = inputField.scrollWidth + 'px';
    }
});
