
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$con = mysqli_connect("localhost", "webadmin@gmail.com", "admin1234", "rooms");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$reservationQuery = "SELECT * FROM favorite";
$reservationResult = mysqli_query($con, $reservationQuery);

if (!$reservationResult) {
    echo "Error executing query: " . mysqli_error($con);
    exit;
}

$reservations = mysqli_fetch_all($reservationResult, MYSQLI_ASSOC);
// Check if the reservation form is submitted
if (isset($_POST['reserve'])) {
    $selectedRoom = isset($_GET['room_id']) ? mysqli_real_escape_string($con, $_GET['room_id']) : null;
    // Retrieve user input from the form
    $name = !empty($_POST['name']) ? mysqli_real_escape_string($con, $_POST['name']) : null;
    $email = !empty($_POST['email']) ? mysqli_real_escape_string($con, $_POST['email']) : null;
    $checkInDate = !empty($_POST['check_in']) ? mysqli_real_escape_string($con, $_POST['check_in']) : null;
    $checkOutDate = !empty($_POST['check_out']) ? mysqli_real_escape_string($con, $_POST['check_out']) : null;
    $notes = !empty($_POST['notes']) ? mysqli_real_escape_string($con, $_POST['notes']) : null;
    $type = !empty($_POST['type']) ? mysqli_real_escape_string($con, $_POST['type']) : null;
    $time = !empty($_POST['time']) ? mysqli_real_escape_string($con, $_POST['time']) : null;
    $attendance = !empty($_POST['attendance']) ? mysqli_real_escape_string($con, $_POST['attendance']) : null;


    $query = "INSERT INTO reserved (room, name, email, check_in, check_out, notes, type, time, attendance) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);

    if (!$stmt) {
        die('Error: ' . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, "sssssssss", $selectedRoom, $name, $email, $checkInDate, $checkOutDate, $notes, $type, $time, $attendance);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $message = "Reservation was successful. The confirmation email was sent to $email.
                     Redirecting to the Client panel...";
        echo "<div style='background-color: #FAC898; color: #00000; font-size:20px; padding: 10px; margin-top: 10px;'>$message</div>";
        echo "<meta http-equiv='refresh' content='3;url=clientpanel.php'>";
        exit;
    } else {
        $error_message = "Reservation Failed. Please recheck your information";
    }

    mysqli_stmt_close($stmt);
}


?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Favorite</title>
    

    <link rel="stylesheet" href="../favorite.css" />
  </head>
  <body>


    <header class="header">
      <a href="#" class="logo"> <i class="fas fa-hotel"></i>  ●YAYHotels </a>

      <nav class="navbar">
        <a href="ClientPanel.php">home</a>
        
      </nav>

      <div id="menu-btn" class="fas fa-bars"></div>
    </header>

    <section class="room" id="room">
      <br> <br> <br>
            <h1 class="heading">  Favorite Rooms</h1>

            <div class="swiper room-slider">
  <div class="swiper-wrapper">
  <?php foreach ($reservations as $reservation) { ?>
    <div class="swiper-slide slide">
        <div class="image">
            <img src="../images/grad.jpg" alt="" />
        </div>
        <div class="content">
            <h3>Room <?php echo $reservation['room_id']; ?></h3>
            <h1>Projector: <?php echo $reservation['projector']; ?></h1>
            <h1>Speaker: <?php echo $reservation['speaker']; ?></h1>
            <h1>Attendance: <?php echo $reservation['guests']; ?></h1>
            <form action="reservation2.php" method="POST">
                <input type="hidden" name="room_id" value="<?php echo $reservation['room_id']; ?>">
                <input type="submit" name="reserve" value="Reserve Room" class="btn">
            </form>
        </div>
    </div>
<?php } ?>

  </div>
  <div class="swiper-pagination"></div>
</div>

    </section>>
    <section class="footer">

      <div class="box-container">

         <div class="box">
            <h3>contact info</h3>
            <a href="#"> <i class="fas fa-phone"></i> +90 5318962154 </a>
            <a href="#"> <i class="fas fa-envelope"></i> admin@gmail.com</a>
            <a href="#"> <i class="fas fa-map"></i> Istanbul/Turkey</a>
         </div>

      </div>

   </section>
</body>
</html>