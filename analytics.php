<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Generate Analytics Report</title>
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

    form {
      margin-top: 20px;
    }

    label {
      display: block;
      margin-bottom: 10px;
      text-align: left;
      font-weight: bold;
    }

    input[type="text"],
    input[type="date"],
    select {
      width: calc(100% - 22px);
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    button {
      padding: 10px 20px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Generate Analytics Report</h2>
    <form action="generate_report.php" method="POST">
      <label for="carType">Type of Car:</label>
      <select name="carType" id="carType" required>
        <option value="">Select a car type</option>
        <option value="Sedan">Sedan</option>
        <option value="SUV">SUV</option>
        <option value="Hatchback">Hatchback</option>
        <option value="Convertible">Convertible</option>
        <option value="Coupe">Coupe</option>
        <option value="Minivan">Minivan</option>
        <option value="Pickup Truck">Pickup Truck</option>
        <option value="Van">Van</option>
      </select>

      <label for="startDate">Start Date:</label>
      <input type="date" name="startDate" id="startDate" required>

      <label for="endDate">End Date:</label>
      <input type="date" name="endDate" id="endDate" required>

      <button type="submit">Generate Report</button>
    </form>
    <a href='admin_dashboard.html'>Go To admin_dashboard</a>
  </div>
</body>

</html>