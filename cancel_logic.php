<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cancel Booking</title>
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
      text-align: center;
    }

    h2 {
      color: #333;
    }

    p {
      margin-bottom: 10px;
      line-height: 1.6;
    }

    .success-message {
      color: #4caf50;
      font-weight: bold;
    }

    .error-message {
      color: #c00;
      font-weight: bold;
    }

    .button-container {
      margin-top: 20px;
    }

    .button-container a {
      display: inline-block;
      margin-right: 10px;
      padding: 10px 20px;
      text-decoration: none;
      background-color: #007bff;
      color: #fff;
      border-radius: 4px;
      transition: background-color 0.3s ease;
    }

    .button-container a:hover {
      background-color: #0056b3;
    }
  </style>
</head>

<body>
  <div class="container">
    <?php
    require 'vendor/autoload.php';

    use MongoDB\Client as MongoClient;
    use MongoDB\BSON\ObjectId;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['order_id'])) {
      $orderId = $_GET['order_id'];

      try {
        $mongoClient = new MongoClient("mongodb://localhost:27017");
        $bookingsCollection = $mongoClient->Car_Rental_Service->Confirmed_Bookings;

        $existingBooking = $bookingsCollection->findOne(['order_id' => $orderId]);

        if ($existingBooking) {
          $deleteResult = $bookingsCollection->deleteOne(['order_id' => $orderId]);

          if ($deleteResult->getDeletedCount() > 0) {

            $userEmail = $_SESSION['user_email'];
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
              $mail->Subject = 'Booking Cancellation Confirmation';
              $mail->Body = "<p>Your booking with Order ID: {$orderId} has been successfully canceled.</p>";

              $mail->send();

              echo '<h2 class="success-message">Booking Cancelled</h2>';
              echo '<p>The booking with Order ID: ' . $orderId . ' has been canceled.</p>';
              echo '<p>Cancellation email sent to: ' . $userEmail . '</p>';
            } catch (Exception $e) {
              echo '<p class="error-message">Message could not be sent. Mailer Error: ' . $mail->ErrorInfo . '</p>';
            }
          } else {
            echo '<p class="error-message">Failed to cancel booking. Please try again.</p>';
          }
        } else {
          echo '<h2 class="error-message">Booking Not Found</h2>';
          echo '<p>No booking found with Order ID: ' . $orderId . '</p>';
        }
      } catch (Exception $e) {
        echo '<p class="error-message">Failed to connect to MongoDB: ' . $e->getMessage() . '</p>';
      }
    } else {
      echo '<p class="error-message">Invalid request.</p>';
    }
    ?>

    <div class="button-container">
      <a href="dashboard.php">Back to Dashboard</a>
    </div>
  </div>
</body>

</html>