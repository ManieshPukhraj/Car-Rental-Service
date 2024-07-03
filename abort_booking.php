<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Aborted</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url("bg.png");
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      padding: 20px;
    }

    .container {
      max-width: 600px;
      margin: 0 auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    p {
      margin-bottom: 10px;
    }
  </style>
</head>

<body>
  <div class="container">
    <?php
    if (isset($_GET['order_id'])) {
      $orderId = $_GET['order_id'];

      unset($_SESSION['bookingDetails']);

      echo "<h2>Booking Aborted</h2>";
      echo "<p>Your booking for Order ID: $orderId has been aborted.</p>";
      echo "<a href='dashboard.php' style='display: block; margin-top: 30px; text-align: center;'>Go To Dashboard</a>
";
    } else {
      echo "<p>Invalid request.</p>";
    }
    ?>
  </div>
</body>

</html>