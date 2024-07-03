<?php
session_start();
require 'vendor/autoload.php';

use MongoDB\Client as MongoClient;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$client = new MongoClient("mongodb://localhost:27017");
$collection = $client->Car_Rental_Service->Users;


$response = [
  'status' => 'error',
  'message' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];

  $filter = ['username' => $username];
  $cursor = $collection->find($filter);

  $userFound = false;

  foreach ($cursor as $user) {

    $temporaryPassword = generateTemporaryPassword();

    $hashedPassword = password_hash($temporaryPassword, PASSWORD_DEFAULT);
    $updateResult = $collection->updateOne(
      ['username' => $user['username']],
      ['$set' => ['password' => $hashedPassword]]
    );

    if ($updateResult->getModifiedCount() === 1) {

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
        $mail->addAddress($user['email']);

        $mail->isHTML(true);
        $mail->Subject = 'Temporary Password Reset';
        $mail->Body = "Hello {$user['name']},<br><br>Your temporary password is: {$temporaryPassword}<br><br>Please use this password to login and reset your password.<br><br>Regards,<br>The Car Rental Service Team";

        $mail->send();
        $response['status'] = 'success';
        $response['message'] = "Temporary password sent to your email. Please check your inbox.";
      } catch (Exception $e) {
        $response['message'] = "Failed to send email. Mailer Error: {$mail->ErrorInfo}";
      }
      $userFound = true;
      break;
    }
  }

  if (!$userFound) {
    $response['message'] = "Username not found.";
  }
}

function generateTemporaryPassword($length = 8)
{
  $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $numbers = '0123456789';
  $specialChars = '!@#$%^&*()_-+=<>?';

  $password = '';
  $password .= $letters[rand(0, strlen($letters) - 1)];
  $password .= $numbers[rand(0, strlen($numbers) - 1)];
  $password .= $specialChars[rand(0, strlen($specialChars) - 1)];

  $allChars = $letters . $numbers . $specialChars;
  for ($i = 3; $i < $length; $i++) {
    $password .= $allChars[rand(0, strlen($allChars) - 1)];
  }

  return str_shuffle($password);
}

$_SESSION['reset_password_response'] = $response;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
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
      max-width: 400px;
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
      color: red;
    }

    .success p {
      color: green;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Forgot Password</h2>

    <?php if (!empty($response['message'])) : ?>
      <div class="message <?php echo $response['status'] === 'success' ? 'success' : ''; ?>">
        <p><?php echo $response['message']; ?></p>
        <br>
        <a href="forgot_password.php">Go To Previous page</a>
      </div>
    <?php endif; ?>

  </div>
</body>

</html>