<?php
require 'vendor/autoload.php';

use MongoDB\Client as MongoClient;
use MongoDB\BSON\ObjectId;

session_start();

try {
  $mongoClient = new MongoClient("mongodb://localhost:27017");
  $bookingsCollection = $mongoClient->Car_Rental_Service->Confirmed_Bookings;
  $carsCollection = $mongoClient->Car_Rental_Service->Cars;

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carId = $_POST['carId'];
    $carType = $_POST['carType'];
    $passengers = $_POST['passengers'];
    $driverName = $_POST['driverName'];
    $licenseNo = $_POST['licenseNo'];
    $noOfDays = $_POST['noOfDays'];
    $age = $_POST['age'];
    $phoneNo = $_POST['phoneNo'];
    $dateOfTravel = $_POST['dateOfTravel'];

    $orderId = uniqid('order_');

    $dateOfTravelDate = new DateTime($dateOfTravel);
    $dateOfReturnDate = clone $dateOfTravelDate;
    $dateOfReturnDate->modify("+$noOfDays days");
    $dateOfReturn = $dateOfReturnDate->format('Y-m-d');

    $carDetails = $carsCollection->findOne(['_id' => new ObjectId($carId)]);

    if ($carDetails) {

      $existingBookings = $bookingsCollection->find([
        'car_id' => $carId,
        'date_of_travel' => ['$lte' => $dateOfReturn],
        'date_of_return' => ['$gte' => $dateOfTravel]
      ])->toArray();

      if (empty($existingBookings)) {
        $carName = $carDetails['car_name'];
        $regNumber = $carDetails['reg_number'];
        $rentPerDay = $carDetails['rent_per_day'];
        $totalAmount = $rentPerDay * $noOfDays;
        $gst = $totalAmount * 0.18;
        $totalAmountWithGst = $totalAmount + $gst;

        $bookingDetails = [
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

        // Store booking details in session
        $_SESSION['bookingDetails'] = $bookingDetails;
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Booking Confirmation</title>
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

            .container h2 {
              margin-bottom: 20px;
            }

            .container p {
              margin-bottom: 10px;
            }

            .submit-button {
              background-color: #4CAF50;
              color: white;
              padding: 10px 20px;
              text-align: center;
              text-decoration: none;
              display: inline-block;
              font-size: 16px;
              margin-top: 20px;
              cursor: pointer;
              border-radius: 5px;
              border: none;
              transition: background-color 0.3s;
            }

            .submit-button:hover {
              background-color: #45a049;
            }
          </style>
        </head>

        <body>
          <div class="container">
            <h2>Booking Confirmation</h2>
            <p>Order ID: <?php echo $orderId; ?></p>
            <p>Driver Name: <?php echo $driverName; ?></p>
            <p>License Number: <?php echo $licenseNo; ?></p>
            <p>Number of Days: <?php echo $noOfDays; ?></p>
            <p>Age: <?php echo $age; ?></p>
            <p>Phone Number: <?php echo $phoneNo; ?></p>
            <p>Date of Travel: <?php echo $dateOfTravel; ?></p>
            <p>Date of Return: <?php echo $dateOfReturn; ?></p>
            <p>Car Type: <?php echo $carType; ?></p>
            <p>Car Name: <?php echo $carName; ?></p>
            <p>Registration Number: <?php echo $regNumber; ?></p>
            <p>Total Amount (including 18% GST): $<?php echo number_format($totalAmountWithGst, 2); ?></p>
            <form action="confirm_booking.php" method="POST">
              <input type="hidden" name="orderId" value="<?php echo $orderId; ?>">
              <input type="hidden" name="carId" value="<?php echo $carId; ?>">
              <input type="hidden" name="carType" value="<?php echo $carType; ?>">
              <input type="hidden" name="carName" value="<?php echo $carName; ?>">
              <input type="hidden" name="regNumber" value="<?php echo $regNumber; ?>">
              <input type="hidden" name="driverName" value="<?php echo $driverName; ?>">
              <input type="hidden" name="licenseNo" value="<?php echo $licenseNo; ?>">
              <input type="hidden" name="noOfDays" value="<?php echo $noOfDays; ?>">
              <input type="hidden" name="age" value="<?php echo $age; ?>">
              <input type="hidden" name="phoneNo" value="<?php echo $phoneNo; ?>">
              <input type="hidden" name="dateOfTravel" value="<?php echo $dateOfTravel; ?>">
              <input type="hidden" name="dateOfReturn" value="<?php echo $dateOfReturn; ?>">
              <input type="hidden" name="totalAmountWithGst" value="<?php echo $totalAmountWithGst; ?>">
              <button type="submit" class="submit-button">Confirm Booking</button>
            </form>
            <a href="dashboard.php" style="display: block; margin-top: 30px; text-align: center;">Go To Dashboard</a>

          </div>
        </body>

        </html>
<?php
      } else {
        echo "<p>The selected car is not available for the chosen dates.</p>";
      }
    } else {
      echo "<p>Car details not found.</p>";
    }
  } else {
    echo "<p>Invalid request.</p>";
  }
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}
?>