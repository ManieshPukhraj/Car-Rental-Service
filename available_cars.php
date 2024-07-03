<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Available Cars</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f0f0;
      padding: 20px;
    }

    .container {
      max-width: 800px;
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

    .car-option {
      padding: 10px;
      border: 1px solid #ddd;
      margin-bottom: 10px;
      border-radius: 5px;
      background-color: #fafafa;
    }

    .car-option:hover {
      background-color: #f1f1f1;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 20px;
    }

    .book-button {
      display: block;
      width: 100%;
      padding: 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      text-align: center;
      cursor: pointer;
      text-decoration: none;
    }

    .book-button:hover {
      background-color: #45a049;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Available Cars</h2>

    <?php
    require 'vendor/autoload.php';

    use MongoDB\Client as MongoClient;

    try {
      $mongoClient = new MongoClient("mongodb://localhost:27017");
      $collection = $mongoClient->Car_Rental_Service->Cars;

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $type = strtolower($_POST['carType']);
        $passengers = $_POST['passengers'];

        function fetchCars($type, $passengers, $collection)
        {
          $filter = [
            'car_type' => $type,
            'seats' => ['$gte' => (int)$passengers],
            'available' => true
          ];
          $cars = $collection->find($filter);

          $carsArray = iterator_to_array($cars);
          $numCars = count($carsArray);

          if ($numCars > 0) {
            echo "<form action='booking_form.php' method='POST'>";
            foreach ($carsArray as $car) {
              echo "<div class='car-option'>";
              echo "<input type='radio' id='" . $car['_id'] . "' name='carId' value='" . $car['_id'] . "' required>";
              echo "<label for='" . $car['_id'] . "'>" . $car['car_name'] . " - Mileage: " . $car['mileage'] . ", Seats: " . $car['seats'] . ", Rent per day: " . $car['rent_per_day'] . "</label>";
              echo "</div>";
            }
            echo "<input type='hidden' name='carType' value='$type'>";
            echo "<input type='hidden' name='passengers' value='$passengers'>";
            echo "<button type='submit' class='book-button'>Continue to Book</button>";
            echo "</form>";
          } else {
            echo "<p>No cars available matching your criteria.</p>";
          }
        }

        fetchCars($type, $passengers, $collection);
      } else {
        echo "<p>No data submitted.</p>";
      }
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
    }
    ?>
    <a href="dashboard.php" style="display: block; margin-top: 30px; text-align: center;">Go To Dashboard</a>

  </div>
</body>

</html>