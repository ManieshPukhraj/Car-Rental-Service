<?php
session_start();
require 'vendor/autoload.php';

use MongoDB\Client as MongoClient;

$mongoClient = new MongoClient("mongodb://localhost:27017");
$collection = $mongoClient->Car_Rental_Service->Users;

function fetchUserDetails($username, $collection)
{
  $userDetails = $collection->findOne(['username' => $username]);
  return $userDetails;
}

if (!isset($_SESSION['otp']) || !isset($_SESSION['newPassword'])) {
  header('Location: dashboard.php');
  exit;
}

$username = $_SESSION['username'];
$userDetails = fetchUserDetails($username, $collection);

$message = '';
$messageClass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $enteredOTP = $_POST['otp'];


  if ($enteredOTP == $_SESSION['otp']) {

    $newPassword = $_SESSION['newPassword'];
    $updateResult = $collection->updateOne(
      ['username' => $username],
      ['$set' => ['password' => $newPassword]]
    );

    if ($updateResult->getModifiedCount() > 0) {

      $message = "Password updated successfully.";
      $messageClass = "success-message";
    } else {
      $message = "Failed to update password. Please try again.";
      $messageClass = "error-message";
    }


    unset($_SESSION['otp']);
    unset($_SESSION['newPassword']);
  } else {
    $message = "Invalid OTP. Please try again.";
    $messageClass = "error-message";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify OTP</title>
  <link rel="stylesheet" href="index3.css">
  <style>
    .success-message {
      color: green;
    }

    .error-message {
      color: red;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Verify OTP</h2>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
      <label for="otp">Enter OTP:</label>
      <input type="text" id="otp" name="otp" required>
      <span class="<?php echo $messageClass; ?>"><?php echo $message; ?></span>

      <input type="submit" value="Verify OTP">
      <a href="dashboard.php">Go To Dashboard</a>
    </form>
  </div>
</body>

</html>