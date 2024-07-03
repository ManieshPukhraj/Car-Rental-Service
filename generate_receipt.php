<?php
require 'vendor/autoload.php';

use MongoDB\Client as MongoClient;
use MongoDB\BSON\ObjectId;
use Dompdf\Dompdf;

if (isset($_GET['order_id'])) {
  $orderId = $_GET['order_id'];

  $mongoClient = new MongoClient("mongodb://localhost:27017");
  $bookingsCollection = $mongoClient->Car_Rental_Service->Confirmed_Bookings;

  $bookingDetails = $bookingsCollection->findOne(['order_id' => $orderId]);

  if ($bookingDetails) {
    $carId = $bookingDetails['car_id'];

    // Fetch car details from Cars collection
    $carsCollection = $mongoClient->Car_Rental_Service->Cars;
    $carDetails = $carsCollection->findOne(['_id' => new ObjectId($carId)]);

    if ($carDetails) {
      $carName = $carDetails['car_name'];
      $regNumber = $carDetails['reg_number'];
    } else {
      $carName = 'N/A'; // Provide default or error handling if car details are not found
      $regNumber = 'N/A';
    }

    $dompdf = new Dompdf();
    $html = "
      <h2>Booking Receipt</h2>
      <p>Order ID: {$bookingDetails['order_id']}</p>
      <p>Driver Name: {$bookingDetails['driver_name']}</p>
      <p>License Number: {$bookingDetails['license_no']}</p>
      <p>Number of Days: {$bookingDetails['no_of_days']}</p>
      <p>Age: {$bookingDetails['age']}</p>
      <p>Phone Number: {$bookingDetails['phone_no']}</p>
      <p>Date of Travel: {$bookingDetails['date_of_travel']}</p>
      <p>Date of Return: {$bookingDetails['date_of_return']}</p>
      <p>Car Type: {$bookingDetails['car_type']}</p>
      <p>Car Name: {$carName}</p>
      <p>Registration Number: {$regNumber}</p>
      <p>Total Amount (including 18% GST): $" . number_format($bookingDetails['total_amount'], 2) . "</p>
    ";

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $output = $dompdf->output();
    $receiptFilename = "receipt_{$orderId}.pdf";


    $dompdf->stream($receiptFilename, array("Attachment" => false));


    // file_put_contents("/path/to/save/{$receiptFilename}", $output);

  } else {
    echo "<p>Booking not found.</p>";
  }
} else {
  echo "<p>Invalid request.</p>";
}
