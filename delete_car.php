<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete Car</title>
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
    <h2>Delete Car</h2>
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

        $regNumber = strtoupper(htmlspecialchars($_POST['reg_number']));

        $carDetails = $collection->findOne(['reg_number' => $regNumber]);

        if ($carDetails) {

          $deleteResult = $collection->deleteOne(['reg_number' => $regNumber]);

          if ($deleteResult->getDeletedCount() === 1) {
            $response['status'] = 'success';
            $response['message'] = "Car with registration number '{$regNumber}' deleted successfully.";
          } else {
            $response['message'] = "Failed to delete car. Please try again.";
          }
        } else {
          $response['message'] = "Car with registration number '{$regNumber}' not found.";
        }
      }

      $_SESSION['delete_car_response'] = $response;
      ?>
      <div class="message">
        <?php
        if (isset($_SESSION['delete_car_response'])) {
          $response = $_SESSION['delete_car_response'];
          $messageClass = ($response['status'] === 'success') ? 'success' : 'error';
          echo "<p class='{$messageClass}'>{$response['message']}</p>";
          echo "<a href='admin_dashboard.html'>Return to Admin Dashboard</a>";
          unset($_SESSION['delete_car_response']);
        }
        ?>
      </div>
    </div>
  </div>
</body>

</html>