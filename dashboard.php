<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
  <link rel="stylesheet" href="index2.css">
</head>

<body>
  <div class="container">
    <h2>Welcome to Your Dashboard</h2>

    <div class="menu">
      <div>
        <a href="rent_car.php">Rent a Car</a>
      </div>

      <div>
        <a href="cancel_booking.php">Cancel My Booking</a>
      </div>
      <div>
        <a href="#" id="profileLink">Profile</a>
      </div>
      <div>
        <a href='main.html'>Logout</a>
      </div>
    </div>


    <div class="profile-details" id="profileDetails">
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

      if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $userDetails = fetchUserDetails($username, $collection);

        echo "<p><strong>Username:</strong> {$userDetails['username']}</p>";
        echo "<p><strong>Email:</strong> {$userDetails['email']}</p>";

        echo "<hr>";
        echo "<form action='change_oldpassword.php' method='POST' id='changePasswordForm'>";
        echo "<label for='newPassword'>New Password:</label>";
        echo "<input type='password' id='newPassword' name='newPassword' required pattern='(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,12}' title='Password must be 6-12 characters long, containing at least one letter, one number, and one special character'>";
        echo "<input type='submit' value='Change Password'>";
        echo "</form>";
      } else {
        echo "<p>Logged in as: Guest</p>";
        echo "<a href='login_form.html'>Login</a>";
      }
      ?>
    </div>

  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var profileLink = document.getElementById('profileLink');
      var profileDetails = document.getElementById('profileDetails');

      profileLink.addEventListener('click', function(event) {
        event.preventDefault();
        profileDetails.style.display = profileDetails.style.display === 'block' ? 'none' : 'block';
      });


      document.addEventListener('click', function(event) {
        if (!event.target.matches('#profileLink') && !event.target.closest('.profile-details')) {
          profileDetails.style.display = 'none';
        }
      });
    });
  </script>
</body>

</html>