<?php
require 'vendor/autoload.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $orderId = $_POST['orderId'];
  $carId = $_POST['carId'];
  $carType = $_POST['carType'];
  $driverName = $_POST['driverName'];
  $carName = $_POST['carName'];
  $regNumber = $_POST['regNumber'];
  $licenseNo = $_POST['licenseNo'];
  $noOfDays = $_POST['noOfDays'];
  $age = $_POST['age'];
  $phoneNo = $_POST['phoneNo'];
  $dateOfTravel = $_POST['dateOfTravel'];
  $dateOfReturn = $_POST['dateOfReturn'];
  $totalAmountWithGst = $_POST['totalAmountWithGst'];
  $_SESSION['bookingDetails'] = [
    'order_id' => $orderId,
    'car_id' => $carId,
    'car_type' => $carType,
    'car_name' => $carName,
    'reg_number' => $regNumber,
    'driver_name' => $driverName,
    'license_no' => $licenseNo,
    'no_of_days' => $noOfDays,
    'age' => $age,
    'phone_no' => $phoneNo,
    'date_of_travel' => $dateOfTravel,
    'date_of_return' => $dateOfReturn,
    'total_amount' => $totalAmountWithGst
  ];
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Interface</title>
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
      }

      h2 {
        text-align: center;
        margin-bottom: 20px;
      }

      p {
        margin-bottom: 10px;
      }

      .submit-button {
        display: block;
        width: 100%;
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        font-size: 16px;
        margin-top: 20px;
        text-decoration: none;
      }

      .submit-button:hover {
        background-color: #45a049;
      }
    </style>
  </head>

  <body>
    <div class="container">
      <h2>Payment Interface</h2>
      <form action="send_confirmation_email.php" method="POST">
        <p>Select Payment Method:</p>
        <label>
          <input type="radio" name="paymentMethod" value="credit_card" required> Credit Card
        </label><br>
        <label>
          <input type="radio" name="paymentMethod" value="debit_card" required> Debit Card
        </label><br>
        <label>
          <input type="radio" name="paymentMethod" value="net_banking" required> Net Banking
        </label><br>
        <label>
          <input type="radio" name="paymentMethod" value="upi" required> UPI
        </label><br>
        <input type="hidden" name="orderId" value="<?= $orderId ?>">
        <button type="submit" class="submit-button">Proceed to Pay</button>
      </form>
      <a href="dashboard.php" style="display: block; margin-top: 30px; text-align: center;">Go To Dashboard</a>

    </div>
  </body>

  </html>
<?php
} else {
  echo "<p>Invalid request.</p>";
}
?>