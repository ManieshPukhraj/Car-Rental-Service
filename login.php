<?php
session_start();
require 'vendor/autoload.php';

use MongoDB\Client as MongoClient;

$client = new MongoClient("mongodb://localhost:27017");
$collection = $client->Car_Rental_Service->Users;

$response = [
  'status' => 'error',
  'message' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $filter = ['username' => $username];
  $cursor = $collection->find($filter);

  $userFound = false;
  foreach ($cursor as $user) {
    if (password_verify($password, $user['password'])) {
      $response['status'] = 'success';
      $response['message'] = "Login successful";
      $_SESSION['username'] = $username;
      $_SESSION['user_email'] = $user['email'];
      $userFound = true;
      break;
    }
  }

  if (!$userFound) {
    $response['message'] = "Incorrect username or password";
  }
}

if ($response['status'] === 'success') {
  header("Location: dashboard.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
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
      border: 5px solid greenyellow;
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
      font-weight: bold;
    }

    .message .success {
      color: green;
    }

    .message .error {
      color: red;
    }

    label {
      display: block;
      margin-bottom: 5px;
    }

    input[type="text"],
    input[type="password"],
    input[type="submit"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Login Status</h2>

    <?php
    if (!empty($response['message'])) {
      $messageClass = ($response['status'] === 'success') ? 'success' : 'error';
      echo "<div class='message'>";
      echo "<p class='{$messageClass}'>{$response['message']}</p>";
      echo "<a href='login_form.html'>Back To Login</a>";
      echo "</div>";
    }
    ?>
  </div>
</body>

</html>