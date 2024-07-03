<?php
session_start();
require 'vendor/autoload.php';

use MongoDB\Client as MongoClient;

// MongoDB connection
$client = new MongoClient("mongodb://localhost:27017");
$collection = $client->Car_Rental_Service->Cars;

// Initialize response array
$response = [
  'status' => 'error',
  'message' => ''
];

// Validate form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Sanitize and validate inputs
  $carName = htmlspecialchars($_POST['car_name']);
  $carType = htmlspecialchars($_POST['car_type']);
  $mileage = floatval($_POST['mileage']);
  $regNumber = strtoupper(htmlspecialchars($_POST['reg_number']));
  $seats = intval($_POST['seats']);
  $rentPerDay = floatval($_POST['rent_per_day']);
  $available = ($_POST['available'] === 'true'); // Convert to boolean

  // Check if registration number already exists
  $filter = ['reg_number' => $regNumber];
  $existingCar = $collection->findOne($filter);

  if ($existingCar) {
    $response['message'] = "Car with registration number '{$regNumber}' already exists.";
  } else {
    // Insert document into MongoDB collection
    $insertResult = $collection->insertOne([
      'car_name' => $carName,
      'car_type' => $carType,
      'mileage' => $mileage,
      'reg_number' => $regNumber,
      'seats' => $seats,
      'rent_per_day' => $rentPerDay,
      'available' => $available
    ]);

    if ($insertResult->getInsertedCount() === 1) {
      $response['status'] = 'success';
      $response['message'] = "Car added successfully.";
    } else {
      $response['message'] = "Failed to add car. Please try again.";
    }
  }
}

// Store response in session to display in add_car.php
$_SESSION['add_car_response'] = $response;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Car</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      padding: 20px;
      background-image: url("bg.png");
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }

    .container {
      max-width: 600px;
      margin: 0 auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .message {
      margin-top: 20px;
      text-align: center;
    }

    .message p {
      margin: 5px 0;
      font-weight: bold;
    }

    .message .success {
      color: green;
    }

    .message .error {
      color: red;
    }

    label {
      display: block;
      margin-bottom: 5px;
    }

    input[type="text"],
    input[type="number"],
    select,
    input[type="submit"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Add Car</h2>
    <div class="message">
      <?php
      if (isset($_SESSION['add_car_response'])) {
        $response = $_SESSION['add_car_response'];
        $messageClass = ($response['status'] === 'success') ? 'success' : 'error';
        echo "<p class='{$messageClass}'>{$response['message']}</p>";
        echo "<a href=admin_dashboard.html>Return To Admin Dashboard</a>";
        unset($_SESSION['add_car_response']);
      }
      ?>
    </div>
  </div>
</body>

</html>