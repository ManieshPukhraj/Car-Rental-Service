<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Change Password</title>
  <link rel="stylesheet" href="index2.css">
</head>

<body>
  <div class="container">
    <?php
    session_start();
    require 'vendor/autoload.php';

    use MongoDB\Client as MongoClient;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $mongoClient = new MongoClient("mongodb://localhost:27017");
    $collection = $mongoClient->Car_Rental_Service->Users;

    function fetchUserDetails($username, $collection)
    {
      $userDetails = $collection->findOne(['username' => $username]);
      return $userDetails;
    }

    if (!isset($_SESSION['username'])) {
      header('Location: login_form.html');
      exit;
    }

    $username = $_SESSION['username'];
    $userDetails = fetchUserDetails($username, $collection);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $newPassword = $_POST['newPassword'];

      if (preg_match('/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/', $newPassword)) {

        $otp = generateOTP();
        $_SESSION['otp'] = $otp;
        $_SESSION['newPassword'] = password_hash($newPassword, PASSWORD_DEFAULT);

        try {
          $mail = new PHPMailer(true);
          $mail->isSMTP();
          $mail->Host = 'smtp.gmail.com';
          $mail->SMTPAuth = true;
          $mail->Username = 'manieshp27@gmail.com';
          $mail->Password = 'gbov bgjp kjcv cokd';
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
          $mail->Port = 587;

          $mail->setFrom('manieshp27@gmail.com', 'Car Rental Services');
          $mail->addAddress($userDetails['email'], $userDetails['username']);

          $mail->isHTML(true);
          $mail->Subject = 'Verification Code for Password Change';
          $mail->Body = "Your OTP for password change is: <strong>{$otp}</strong>";

          $mail->send();
          echo 'An OTP has been sent to your registered email address.';
          header('Location: verify_otp.php');
        } catch (Exception $e) {
          echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }


        exit;
      } else {
        echo "New password must be at least 6 characters long and contain at least one letter, one number, and one special character.";
      }
    }

    function generateOTP()
    {

      return rand(100000, 999999);
    }
    ?>

    <h2>Change Password</h2>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
      <label for="newPassword">New Password:</label>
      <input type="password" id="newPassword" name="newPassword" required pattern="(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}" title="Password must be at least 6 characters long and contain at least one letter, one number, and one special character">

      <input type="submit" value="Change Password">
    </form>
  </div>
</body>

</html>