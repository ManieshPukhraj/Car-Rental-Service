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

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
    }

    th,
    td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: left;
    }

    th {
      background-color: #007bff;
      color: #fff;
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

    $mongoClient = new MongoClient("mongodb://localhost:27017");
    $bookingsCollection = $mongoClient->Car_Rental_Service->Confirmed_Bookings;

    $pipeline = [
      [
        '$group' => [
          '_id' => '$car_type',
          'total_earnings' => ['$sum' => ['$toDouble' => '$total_amount']]
        ]
      ]
    ];

    $result = $bookingsCollection->aggregate($pipeline)->toArray();

    echo "<div class='container'>";
    echo "<h2>Analytics Report</h2>";
    echo "<table>";
    echo "<tr><th>Type of Car</th><th>Total Earnings</th></tr>";

    foreach ($result as $row) {
      $carType = $row['_id'];
      $totalEarnings = $row['total_earnings'] ?? 0;
      echo "<tr><td>$carType</td><td>$" . number_format($totalEarnings, 2) . "</td></tr>";
    }

    echo "</table>";
    echo "<a href='admin_dashboard.html'>Go Back to Admin Dashboard</a>";
    echo "</div>";
    ?>
  </div>
</body>

</html>