<?php
$con = mysqli_connect("localhost", "webadmin@gmail.com", "admin1234", "rooms");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["check_in"], $_GET["check_out"], $_GET["projector"], $_GET["speaker"], $_GET["guests"])) {    $checkInDate = $_GET["check_in"];
    $checkOutDate = $_GET["check_out"];
    $projector = $_GET["projector"];
    $speaker = $_GET["speaker"];
    $guests = $_GET["guests"];

    $query = "SELECT * FROM filtering WHERE `check_in` >= '$checkInDate' AND `check_out` <= '$checkOutDate'";


    if ($projector !== "0") {
        $query .= " AND projector = '$projector'";
    }

    if ($speaker !== "0") {
        $query .= " AND speaker = '$speaker'";
    }

    if ($guests !== "0") {
        if ($guests === "1-4") {
            $query .= " AND guests >= 1 AND guests <= 4";
        } elseif ($guests === "4-10") {
            $query .= " AND guests >= 4 AND guests <= 10";
        } elseif ($guests === "10-20") {
            $query .= " AND guests >= 10 AND guests <= 20";
        } elseif ($guests === "20-30") {
            $query .= " AND guests >= 20 AND guests <= 30";
        }
    }

    $result = mysqli_query($con, $query);

    if ($result === false) {
        echo "Error executing query: " . mysqli_error($con);
        exit();
    }

    if (mysqli_num_rows($result) > 0) {
      session_start();
      $filtered_rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
  
  
      $_SESSION["filtered_rooms"] = $filtered_rooms; // Store the filtered rooms in the session
      
  
      // Add the following line to assign the selected room to the session variable
      $_SESSION["selected_room"] = $_GET["selected_room"];
  
      header("Location: Reservation.php?selected_room=" . urlencode($_SESSION["selected_room"]) );
  
      exit();
  } else {
      $error_message = "No rooms found";
  }
  mysqli_free_result($result);
}
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["feedback-comment"])) {
   $feedbackComment = $_POST["feedback-comment"];

   // Insert the feedback comment into the "feedbacks" table
   $insertQuery = "INSERT INTO feedbacks (feedback) VALUES ('$feedbackComment')";

   if (mysqli_query($con, $insertQuery)) {
       $successMessage = "Thank you for your feedback!";
   } else {
       $errorMessage = "Error saving the feedback: " . mysqli_error($con);
   }
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["selected_room"])) {
   $selectedRoom = $_POST["selected_room"];
   $projector = $_POST["projector"];
   $speaker = $_POST["speaker"];
   $guests = $_POST["guests"];

   // Insert the room's information into the favorite_table
   $insertQuery = "INSERT INTO favorite(room_id, projector, speaker, guests) VALUES ('$selectedRoom', '$projector', '$speaker', '$guests')";

   if (mysqli_query($con, $insertQuery)) {
       $successMessage = "Room added to favorites!";
   } else {
       $errorMessage = "Error adding room to favorites: " . mysqli_error($con);
   }
}


mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Rooms</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../RoomsPage.css">
</head>
<body>

   <header class="header">
   <a href="ClientPanel.php" class="logo"> ●Home </a>
      <a href="#" class="logo"> ●Room Reservation </a>

   </header>

   <section class="available">

   <form action="RoomsPage.php" method="GET">

         <div class="box">
            <p>check in <span>*</span></p>
            <input type="date" class="input" name="check_in">
         </div>

         <div class="box">
            <p>check out <span>*</span></p>
            <input type="date" class="input" name="check_out">
         </div>

         <div class="box">
            <p>Projector <span>*</span></p>
            <select name="projector" id="" class="input">
   <option value="DLP">DLP</option>
   <option value="LCD">LCD</option>
   <option value="Laser">Laser</option>
   <option value="Without Projector">Without Projector</option>
</select>
         </div>

         <div class="box">
            <p>Speaker System <span>*</span></p>
            <select name="speaker" id="" class="input">
   <option value="Mono">Mono</option>
   <option value="Stereo">Stereo</option>
   <option value="LCR">LCR</option>
   <option value="Without Speaker">Without Speaker</option>
</select>

         </div>

         <div class="box">
            <p>Number of Guests?<span>*</span></p>
            <select name="guests" id="" class="input">
   <option value="1-4">1-4</option>
   <option value="4-10">4-10</option>
   <option value="10-20">10-20</option>
   <option value="20-30">20-30</option>
</select>

          <input type="submit" value="check availability" class="btn" style="margin-top: 36px;" href="RoomsPage.php" >
          <?php if (!empty($error_message)) { ?>
        <div style="background-color: #FAC898; color: #00000; font-size:20px; padding: 10px; margin-top: 10px;"><?php echo $error_message; ?></div>
    <?php } ?>
      </form>

   </section>

   <section class="room" id="room">

      <h1 class="heading"> Rooms >></h1>

      <div class="swiper room-slider">

         <div class="wrapper">
            
         <div class="slide">
   <!-- Room 1 -->
   <!-- Add a form to wrap the heart button and hidden input field -->
   <form action="RoomsPage.php" method="POST">
      <div class="image">
         <img src="../images/room-1.jpg" name="img" alt="">
      </div>
      <div class="content">
         <h3>Room1</h3>
         <p>Available from 2023-05-01/2023-05-31</p>
         <p>Projector: LCD</p>
         <p>Speaker: Mono</p>
         <p>Guests: 4-10</p>
         <<input type="hidden" name="selected_room" value="1">
<input type="hidden" name="projector" value="LCD">
<input type="hidden" name="speaker" value="Mono">
<input type="hidden" name="guests" value="4-10">
         <button type="submit" class="heart-button"><i class="fas fa-heart"></i></button>
         <!-- Add a success message if needed -->
         <?php if (!empty($successMessage) && $_POST["selected_room"] === "1") { ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
         <?php } ?>
      </div>
   </form>
   <div class="stars">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
   </div>
</div>

<div class="slide">
   <form action="RoomsPage.php" method="POST">
      <div class="image">
         <img src="../images/room-2.jpg" name="img" alt="">
      </div>
      <div class="content">
         <h3>Room2</h3>
         <p>Available from 2023-06-01/2023-06-31</p>
         <p>Projector: DLP</p>
         <p>Speaker: LCR</p>
         <p>Guests: 20-30</p>
         <input type="hidden" name="selected_room" value="2">
         <input type="hidden" name="projector" value="DLP">
         <input type="hidden" name="speaker" value="LCR">
         <input type="hidden" name="guests" value="20-30">
         <button type="submit" class="heart-button"><i class="fas fa-heart"></i></button>
         <!-- Add a success message if needed -->
         <?php if (!empty($successMessage) && $_POST["selected_room"] === "2") { ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
         <?php } ?>
      </div>
   </form>
   <div class="stars">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star-half-alt"></i>
   </div>
</div>

<div class="slide2">
   <form action="RoomsPage.php" method="POST">
      <div class="image">
         <img src="../images/room3.jpg" name="img" alt="">
      </div>
      <div class="content">
         <h3>Room3</h3>
         <p>Available from 2023-05-01/2023-05-31</p>
         <p>Projector: LCD</p>
         <p>Speaker: Mono</p>
         <p>Guests: 4-10</p>
         <input type="hidden" name="selected_room" value="3">
         <input type="hidden" name="projector" value="LCD">
         <input type="hidden" name="speaker" value="Mono">
         <input type="hidden" name="guests" value="4-10">
         <button type="submit" class="heart-button"><i class="fas fa-heart"></i></button>
         <!-- Add a success message if needed -->
         <?php if (!empty($successMessage) && $_POST["selected_room"] === "3") { ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
         <?php } ?>
      </div>
   </form>
   <div class="stars">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star-half-alt"></i>
   </div>
</div>

<div class="slide2">
   <form action="RoomsPage.php" method="POST">
      <div class="image">
         <img src="../images/room4.jpg" name="img" alt="">
      </div>
      <div class="content">
         <h3>Room4</h3>
         <p>Available from 2023-06-01/2023-06-31</p>
         <p>Projector: Laser</p>
         <p>Speaker: Mono</p>
         <p>Guests: 1-4</p>
         <input type="hidden" name="selected_room" value="4">
         <input type="hidden" name="projector" value="Laser">
         <input type="hidden" name="speaker" value="Mono">
         <input type="hidden" name="guests" value="1-4">
         <button type="submit" class="heart-button"><i class="fas fa-heart"></i></button>
         <!-- Add a success message if needed -->
         <?php if (!empty($successMessage) && $_POST["selected_room"] === "4") { ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
         <?php } ?>
      </div>
   </form>
   <div class="stars">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star-half-alt"></i>
   </div>
</div>

        </div>
      </div>
   </section>
   <section class="review" id="review">
    <div class="swiper review-slide">
        <div class="wrapper">
            <div class="swiper-slide slide">
                <h1 class="heading">We are here to hear your comments..</h1>
                
                <form action="RoomsPage.php" method="POST">
                <i class="fas fa-quote-right"></i>
                    <textarea class="text" rows="3" cols="80" placeholder="Send feedback regarding your experience..." name="feedback-comment"></textarea>
                    <i class="fas fa-quote-right"></i>
                    <input type="submit" value="Submit" class="btn2">
                </form>
                <?php if (!empty($successMessage)) { ?>
                    <div style="background-color: #FAC898; color: #00000; font-size:20px; padding: 10px; margin-top: 10px;"><?php echo $successMessage; ?></div>
                <?php } ?>
                <?php if (!empty($errorMessage)) { ?>
                    <div style="background-color: #FAC898; color: #00000; font-size:20px; padding: 10px; margin-top: 10px;"><?php echo $errorMessage; ?></div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>


   <section class="footer">

      <div class="box-container">

         <div class="box">
            <h3>contact info</h3>
            <a href="#"> <i class="fas fa-phone"></i> +90 5318962154 </a>
            <a href="#"> <i class="fas fa-envelope"></i> admin@gmail.com</a>
            <a href="#"> <i class="fas fa-map"></i> Istanbul/Turkey</a>
         </div>

      </div>

      <div class="share">
         <a href="#" class="fab fa-facebook-f"></a>
         <a href="#" class="fab fa-instagram"></a>
         <a href="#" class="fab fa-twitter"></a>
         <a href="#" class="fab fa-pinterest"></a>
      </div>

   </section>
</body>
</html>