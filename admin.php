<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login Status</title>
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
  </style>
</head>

<body>
  <div class="container">
    <h2>Admin Login Status</h2>

    <?php
    session_start();

    $response = [
      'status' => 'error',
      'message' => ''
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $username = $_POST['username'];
      $password = $_POST['password'];

      if ($username === 'admin123' && $password === 'Maniesh') {
        $response['status'] = 'success';
        $response['message'] = "Admin login successful.";

        header('Location: admin_dashboard.html');
        exit();
      } else {
        $response['message'] = "Incorrect username or password.";
      }
    }

    if (!empty($response['message'])) {
      $messageClass = ($response['status'] === 'success') ? 'success' : 'error';
      echo "<div class='message'>";
      echo "<p class='{$messageClass}'>{$response['message']}</p>";
      echo "<a href='admin.html'>Back To Login</a>";
      echo "</div>";
    }
    ?>
  </div>
</body>

</html>