<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $paymentMethod = $_POST['paymentMethod'];
  $bookingDetails = $_SESSION['bookingDetails'];

  if (isset($_SESSION['user_email'])) {
    $userEmail = $_SESSION['user_email'];
  } else {
    echo "User email not found. Please log in.";
    exit();
  }

  $mail = new PHPMailer(true);

  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'manieshp27@gmail.com';
    $mail->Password = 'gbov bgjp kjcv cokd';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('manieshp27@gmail.com', 'Car Rental Service');
    $mail->addAddress($userEmail);

    $mail->isHTML(true);
    $mail->Subject = 'Confirm Your Payment';
    $mail->Body = "<p>Please confirm your payment for Order ID: {$bookingDetails['order_id']}.</p>
                   <p><a href='http://localhost/Car_Rental_Service/confirm_payment.php?order_id={$bookingDetails['order_id']}'>Yes</a></p>
                   <p><a href='http://localhost/Car_Rental_Service/abort_booking.php?order_id={$bookingDetails['order_id']}'>No</a></p>";

    $mail->send();
    echo '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Payment</title>
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
        <h2>Confirm Payment</h2>
        <p>Confirmation email has been sent. Please check your email and Click Yes to confirm your booking.</p>
        <a href="dashboard.php" style="display: block; margin-top: 30px; text-align: center;">Go To Dashboard</a>

    </div>
</body>

</html>';
  } catch (Exception $e) {
    echo "<p>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
  }
} else {
  echo "<p>Invalid request.</p>";
}
