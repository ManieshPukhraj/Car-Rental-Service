<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Car Details</title>
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
  </style>
</head>

<body>
  <div class="container">
    <h2>Update Car Details</h2>
    <div class="message">
      <?php
      session_start();
      require 'vendor/autoload.php';

      use MongoDB\Client as MongoClient;

      $client = new MongoClient("mongodb://localhost:27017");
      $collection = $client->Car_Rental_Service->Cars;

      $response = [
        'status' => 'error',
        'message' => ''
      ];

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $oldRegNumber = strtoupper(htmlspecialchars($_POST['old_reg_number']));
        $newRegNumber = strtoupper(htmlspecialchars($_POST['new_reg_number']));
        $carName = htmlspecialchars($_POST['car_name']);
        $carType = htmlspecialchars($_POST['car_type']);
        $mileage = floatval($_POST['mileage']);
        $rentPerDay = floatval($_POST['rent_per_day']);

        $carDetails = $collection->findOne(['reg_number' => $oldRegNumber]);

        if ($carDetails) {

          $updateData = [
            'car_name' => $carName,
            'car_type' => $carType,
            'mileage' => $mileage,
            'rent_per_day' => $rentPerDay
          ];


          if (!empty($newRegNumber) && $newRegNumber !== $oldRegNumber) {
            $updateData['reg_number'] = $newRegNumber;
          }

          $updateResult = $collection->updateOne(
            ['reg_number' => $oldRegNumber],
            ['$set' => $updateData]
          );

          if ($updateResult->getModifiedCount() === 1) {
            $response['status'] = 'success';
            $response['message'] = "Car details updated successfully.";
          } else {
            $response['message'] = "Failed to update car details due to same data given again. Please try again with .";
          }
        } else {
          $response['message'] = "Car with registration number '{$oldRegNumber}' not found.";
        }
      }


      $messageClass = ($response['status'] === 'success') ? 'success' : 'error';
      echo "<p class='{$messageClass}'>{$response['message']}</p>";
      echo "<a href='admin_dashboard.html'>Return to Admin Dashboard</a>";
      ?>
    </div>
  </div>
</body>

</html>