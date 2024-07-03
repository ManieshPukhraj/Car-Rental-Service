<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Cars</title>
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
      max-width: 800px;
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

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th,
    td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>View Cars</h2>
    <table>
      <thead>
        <tr>
          <th>Car Name</th>
          <th>Car Type</th>
          <th>Mileage (km/l)</th>
          <th>Registration Number</th>
          <th>Seats</th>
          <th>Rent per Day</th>
          <th>Available</th>
        </tr>
      </thead>
      <tbody>
        <?php
        require 'vendor/autoload.php';

        use MongoDB\Client as MongoClient;

        $client = new MongoClient("mongodb://localhost:27017");
        $collection = $client->Car_Rental_Service->Cars;

        $cars = $collection->find();

        foreach ($cars as $car) {
          echo "<tr>
                  <td>{$car['car_name']}</td>
                  <td>{$car['car_type']}</td>
                  <td>{$car['mileage']}</td>
                  <td>{$car['reg_number']}</td>
                  <td>{$car['seats']}</td>
                  <td>{$car['rent_per_day']}</td>
                  <td>" . ($car['available'] ? 'Yes' : 'No') . "</td>
                </tr>";
        }
        ?>
      </tbody>
    </table>
    <a href="admin_dashboard.html" style="margin-left: 35%;">Back to Admin Dashboard</a>
  </div>
</body>

</html>