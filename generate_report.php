<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Analytics Report</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f0f0;
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
      color: #333;
      text-align: center;
    }

    p {
      margin-bottom: 10px;
      line-height: 1.6;
    }

    .error-message {
      color: #c00;
      font-weight: bold;
      text-align: center;
    }

    a {
      display: block;
      margin-top: 30px;
      text-align: center;
      padding: 10px 20px;
      text-decoration: none;
      background-color: #007bff;
      color: #fff;
      border-radius: 4px;
      transition: background-color 0.3s ease;
    }

    a:hover {
      background-color: #0056b3;
    }
  </style>
</head>

<body>
  <div class="container">
    <?php
    require 'vendor/autoload.php';

    use MongoDB\Client as MongoClient;
    use MongoDB\BSON\UTCDateTime;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $carType = strtolower($_POST['carType']);
      $startDate = $_POST['startDate'];
      $endDate = $_POST['endDate'];


      try {
        $mongoClient = new MongoClient("mongodb://localhost:27017");
        $bookingsCollection = $mongoClient->Car_Rental_Service->Confirmed_Bookings;

        $pipeline = [
          [
            '$match' => [
              'car_type' => $carType,
              'date_of_travel' => [
                '$gte' => $startDate,
                '$lte' => $endDate
              ]

            ]
          ],
          [
            '$group' => [
              '_id' => '$car_type',
              'total_earnings' => ['$sum' => ['$toDouble' => '$total_amount']]
            ]
          ]
        ];

        $result = $bookingsCollection->aggregate($pipeline)->toArray();



        $totalEarnings = $result[0]['total_earnings'] ?? 0;

        echo "<div class='container'>";
        echo "<h2>Analytics Report</h2>";
        echo "<p>Type of Car: $carType</p>";
        echo "<p>Start Date: $startDate</p>";
        echo "<p>End Date: $endDate</p>";
        echo "<p>Total Earnings: $" . number_format($totalEarnings, 2) . "</p>";
        echo "<a href='analytics.php'>Go Back</a>";
        echo "</div>";
      } catch (Exception $e) {
        echo "<p class='error-message'>Failed to connect to MongoDB: " . $e->getMessage() . "</p>";
      }
    } else {
      echo "<p class='error-message'>Invalid request.</p>";
    }
    ?>
  </div>
</body>

</html>