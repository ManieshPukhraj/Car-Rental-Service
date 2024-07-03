<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Status</title>
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
    }

    .success {
      color: green;
    }

    .error {
      color: red;
    }

    .success-symbol {
      color: green;
      font-weight: bold;
    }

    .error-symbol {
      color: red;
      font-weight: bold;
    }

    .alt-usernames {
      margin-top: 10px;
      text-align: left;
    }

    .alt-usernames p {
      margin: 5px 0;
    }

    a {
      display: block;
      text-align: center;
      margin-top: 20px;
      text-decoration: none;
      color: #007bff;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Registration Status</h2>
    <div class="message">
      <?php
      require 'vendor/autoload.php';

      use MongoDB\Client as MongoClient;

      $client = new MongoClient("mongodb://localhost:27017");
      $collection = $client->Car_Rental_Service->Users;

      $response = [
        'status' => 'error',
        'message' => [],
        'alternatives' => []
      ];

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $cfm_password = $_POST['cfm_password'];
        $email = $_POST['email'];
        $name = $_POST['name'];
        $age = $_POST['age'];
        $phone_no = $_POST['phone_no'];

        if ($age < 18) {
          $response['message'][] = "Age must be above 18";
        }

        if (!preg_match("/^\d{10}$/", $phone_no)) {
          $response['message'][] = "Phone number must be 10 digits";
        }

        if (strlen($password) < 6) {
          $response['message'][] = "Password must be at least 6 characters long";
        } elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z\d]).{6,16}$/', $password)) {
          $response['message'][] = "Password must be 6-16 characters long with at least one letter, one number, and one special character";
        }

        if ($password !== $cfm_password) {
          $response['message'][] = "Passwords do not match";
        }

        $filter = ['username' => $username];
        $existingUser = $collection->findOne($filter);

        if ($existingUser) {
          $response['message'][] = "Username already exists";

          $cursor = $collection->find([], ['projection' => ['username' => 1]]);
          $existingUsernames = [];
          foreach ($cursor as $doc) {
            $existingUsernames[] = $doc['username'];
          }

          $alphabet = 'abcdefghijklmnopqrstuvwxyz';
          for ($i = 0; $i < 5; $i++) {
            do {
              $altUsername = $username . '_' . substr(str_shuffle($alphabet), 0, 4);
            } while (in_array($altUsername, $existingUsernames));

            $response['alternatives'][] = $altUsername;
          }
        }

        if (empty($response['message'])) {
          $result = $collection->insertOne([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'email' => $email,
            'name' => $name,
            'age' => $age,
            'phone_no' => $phone_no
          ]);

          if ($result->getInsertedCount() === 1) {
            $response['status'] = 'success';
            $response['message'][] = "User registered successfully Go To Main Page To Login And Enjoy!";
          } else {
            $response['message'][] = "Registration failed";
          }
        }
      }

      if ($response['status'] === 'success') {
        echo "<p class='success'>{$response['message'][0]} <span class='success-symbol'>✔</span></p>";
      } else {
        foreach ($response['message'] as $message) {
          echo "<p class='error'>$message <span class='error-symbol'>✘</span></p>";
        }
      }

      if (!empty($response['alternatives'])) {
        echo "<div class='alt-usernames'>";
        echo "<p>Suggested alternative usernames:</p>";
        foreach ($response['alternatives'] as $altUsername) {
          echo "<p>$altUsername</p>";
        }
        echo "</div>";
      }
      ?>
    </div>
    <a href="register_form.html">Go back to registration</a>
    <a href="main.html">Go back to Main</a>
  </div>
</body>

</html>