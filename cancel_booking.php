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

    .error-message {
      color: #c00;
      font-weight: bold;
    }

    .success-message {
      color: #4caf50;
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

    .form-container {
      margin-top: 20px;
    }

    .form-container input[type="text"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    .form-container button {
      padding: 10px 20px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .form-container button:hover {
      background-color: #0056b3;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Cancel Booking</h2>

    <div class="form-container">
      <form action="cancel.php" method="POST" onsubmit="confirmCancellation()">
        <label for="order_id">Enter Order ID to Cancel (Format: order_xxxxxxxxxxxxx):</label><br>
        <input type="text" id="order_id" name="order_id" pattern="^order_[a-zA-Z0-9]{13}$" title="Please enter a valid Order ID in the format of: order_+followed by 13 letters/numbers" required><br>
        <button type="submit">Cancel Booking</button>

      </form>
      <br>
      <a href='dashboard.php'>Go To Dashboard</a>
    </div>


  </div>
</body>

</html>