<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Form</title>
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
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    input[type="text"],
    input[type="number"],
    input[type="date"],
    input[type="tel"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-sizing: border-box;
    }

    .submit-button {
      width: 100%;
      padding: 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-align: center;
      font-size: 16px;
    }

    .submit-button:hover {
      background-color: #45a049;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 20px;
    }
  </style>
  <script>
    function validateForm() {
      const today = new Date().toISOString().split('T')[0];
      const travelDate = document.getElementById('dateOfTravel').value;
      if (travelDate < today) {
        alert('Date of travel must be today or later.');
        return false;
      }
      return true;
    }
  </script>
</head>

<body>
  <div class="container">
    <h2>Booking Form</h2>
    <form action="process_booking.php" method="POST" onsubmit="return validateForm()">
      <input type="hidden" name="carId" value="<?php echo $_POST['carId']; ?>">
      <input type="hidden" name="carType" value="<?php echo $_POST['carType']; ?>">
      <input type="hidden" name="passengers" value="<?php echo $_POST['passengers']; ?>">

      <label for="driverName">Name of Driver:</label>
      <input type="text" id="driverName" name="driverName" required>

      <label for="licenseNo">License Number:</label>
      <input type="text" id="licenseNo" name="licenseNo" pattern="[A-Za-z0-9]{15}" title="License number should be 15 alphanumeric characters" required>

      <label for="noOfDays">Number of Days Needed:</label>
      <input type="number" id="noOfDays" name="noOfDays" min="1" required>

      <label for="age">Age:</label>
      <input type="number" id="age" name="age" min="18" required>

      <label for="phoneNo">Phone Number:</label>
      <input type="tel" id="phoneNo" name="phoneNo" pattern="[0-9]{10}" title="Phone number should be 10 digits" required>

      <label for="dateOfTravel">Date of Travel:</label>
      <input type="date" id="dateOfTravel" name="dateOfTravel" required>

      <button type="submit" class="submit-button">Submit Booking</button>
    </form>
    <a href="dashboard.php" style="display: block; margin-top: 30px; text-align: center;">Go To Dashboard</a>

    <a class="back-link" href="available_cars.php">Back to Available Cars</a>
  </div>
</body>

</html>