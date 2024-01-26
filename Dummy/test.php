<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    .resizable-div {
      height: 60px; /* Initial height */
      background-color: #f0f0f0;
      overflow: hidden; /* Hide content that exceeds the height */
      transition: height 0.3s ease; /* Smooth transition for the height property */
      display: none; /* Initially hide the content */
    }

    .resizable-div.expanded {
      height: 200px; /* New height when expanded */
      display: block; /* Show the content when expanded */
    }

    /* Optional: Style for better visibility */
    .content {
      padding: 20px;
    }
  </style>
</head>
<body>

  <div class="resizable-div" onclick="toggleHeight()">
    <div class="content">
      <!-- Your content goes here -->
      Click to toggle height
    </div>
  </div>

  <script>
    function toggleHeight() {
      const resizableDiv = document.querySelector('.resizable-div');
      resizableDiv.classList.toggle('expanded');
    }
  </script>

</body>
</html>