<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f0f2f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      background-image: url("bg.png");
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }

    .container {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
      max-width: 400px;
      width: 100%;
      text-align: center;
      height: 225px;
    }

    h2 {
      margin-bottom: 20px;
      color: #333333;
    }

    label {
      display: block;
      margin: 10px 0 5px;
      color: #555555;
      text-align: left;
    }

    input[type="text"] {
      width: calc(100% - 20px);
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
      font-size: 16px;
    }

    input[type="submit"] {
      background-color: #007bff;
      color: #ffffff;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
      background-color: #0056b3;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Forgot Password</h2>
    <form action="reset_password.php" method="POST">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required><br><br>

      <input type="submit" value="Get Temporary Password">
      <br>
      <a href="login_form.html">Go Back To Login</a>
    </form>
  </div>
</body>

</html>