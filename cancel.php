<html>

<body>
  <div class="button-container">
    <?php
    require 'vendor/autoload.php';

    use MongoDB\Client as MongoClient;
    use MongoDB\BSON\ObjectId;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $orderId = strtolower($_POST['order_id']);

      try {
        $mongoClient = new MongoClient("mongodb://localhost:27017");
        $bookingsCollection = $mongoClient->Car_Rental_Service->Confirmed_Bookings;

        $numericOrderId = $orderId;

        $existingBooking = $bookingsCollection->findOne(['order_id' => $numericOrderId]);

        if ($existingBooking) {

          echo '<script>
                  function confirmCancellation() {
                    if (confirm("Do you really want to cancel this booking?")) {
                      window.location.href = "cancel_logic.php?order_id=' . $orderId . '";
                    }
                  }
                  confirmCancellation()
                </script>';
        } else {
          echo '<h2 class="error-message">Booking Not Found</h2>';
          echo '<p>No booking found with Order ID: ' . $orderId . '</p>';
        }
      } catch (Exception $e) {
        echo '<p class="error-message">Failed to connect to MongoDB: ' . $e->getMessage() . '</p>';
      }
    }

    ?>


  </div>
</body>

</html>