<?php
session_start();
require 'vendor/autoload.php';

use MongoDB\Client as MongoClient;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['order_id'])) {
  $orderId = $_GET['order_id'];

  if (isset($_SESSION['bookingDetails'])) {
    $bookingDetails = $_SESSION['bookingDetails'];

    try {
      $mongoClient = new MongoClient("mongodb://localhost:27017");
      $bookingsCollection = $mongoClient->Car_Rental_Service->Confirmed_Bookings;

      $document = [
        'order_id' => $orderId,
        'car_id' => $bookingDetails['car_id'],
        'car_type' => $bookingDetails['car_type'],
        "car_name" => $bookingDetails['car_name'],
        "reg_number" => $bookingDetails['reg_number'],
        'driver_name' => $bookingDetails['driver_name'],
        'license_no' => $bookingDetails['license_no'],
        'no_of_days' => $bookingDetails['no_of_days'],
        'age' => $bookingDetails['age'],
        'phone_no' => $bookingDetails['phone_no'],
        'date_of_travel' => $bookingDetails['date_of_travel'],
        'date_of_return' => $bookingDetails['date_of_return'],
        'total_amount' => $bookingDetails['total_amount'],
        'status' => 'Confirmed'
      ];

      $insertResult = $bookingsCollection->insertOne($document);

      if ($insertResult->getInsertedCount() > 0) {
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Confirm Booking</title>
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
            <h2>Booking Confirmed</h2>
            <p>Your payment for Order ID: <?php echo $orderId; ?> has been confirmed.</p>
            <p>Download your receipt: <a href="generate_receipt.php?order_id=<?php echo $orderId; ?>">Download Receipt</a></p>
            <a href="dashboard.php" style="display: block; margin-top: 30px; text-align: center;">Go To Dashboard</a>

          </div>
        </body>

        </html>
<?php
      } else {
        echo "<p>Failed to insert booking details into Confirmed_Bookings collection. Please contact support.</p>";
      }
    } catch (Exception $e) {
      echo "<p>Failed to connect to MongoDB: " . $e->getMessage() . "</p>";
    }
  } else {
    echo "<p>Booking details not found in session. Please try again.</p>";
  }
} else {
  echo "<p>Invalid request.</p>";
}
?>