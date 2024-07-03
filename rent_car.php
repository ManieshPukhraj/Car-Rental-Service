<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rent a Car</title>
  <link rel="stylesheet" href="index4.css">
</head>

<body>
  <div class="container">
    <h2>Rent a Car</h2>

    <form action="available_cars.php" method="POST">
      <label for="carType">Select Car Type:</label>
      <select id="carType" name="carType">
        <option value="SUV">SUV</option>
        <option value="Sedan">Sedan</option>
        <option value="Hatchback">Hatchback</option>
      </select>

      <label for="passengers">Number of Passengers:</label>
      <input type="number" id="passengers" name="passengers" min="1" required>

      <input type="submit" value="Show Available Cars">
    </form>
    <a href="dashboard.php" style="display: block; margin-top: 30px; text-align: center;">Go To Dashboard</a>

  </div>
</body>

</html>