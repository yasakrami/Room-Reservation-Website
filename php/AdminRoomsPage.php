<?php
$con = mysqli_connect("localhost", "webadmin@gmail.com", "admin1234", "rooms");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Handle the room availability update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["room_id"], $_POST["availability"])) {
    $roomId = $_POST["room_id"];
    $availability = $_POST["availability"];

    $updateQuery = "UPDATE filtering SET availability = '$availability' WHERE id = '$roomId'";
    $updateResult = mysqli_query($con, $updateQuery);

    if ($updateResult === false) {
        echo "Error updating room availability: " . mysqli_error($con);
        exit();
    }
}

// Handle adding a new room
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["room_number"], $_POST["guests"], $_POST["speaker"], $_POST["projector"], $_POST["check_in"],$_POST["check_out"])) {
    $roomNumber = $_POST["room_number"];
    $speaker = $_POST["speaker"];
    $guests = $_POST["guests"];
    $check_in = $_POST["check_in"];
    $check_out = $_POST["check_out"];
    $projector= $_POST['projector'];

    $insertQuery = "INSERT INTO filtering (room_number, projector, speaker, guests,check_in,check_out, availability) VALUES ('$roomNumber', '$projector', '$speaker', '$guests','$check_in', '$check_out', '?')";
    $insertResult = mysqli_query($con, $insertQuery);

    if ($insertResult === false) {
        echo "Error adding a new room: " . mysqli_error($con);
        exit();
    }
    // Redirect to refresh the page and show the new room
    header("Location: AdminRoomsPage.php");
    exit();
}

// Build the room query based on user input
$roomQuery = "SELECT * FROM filtering WHERE 1";

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["projector"])) {
    $projector = $_GET["projector"];
    $roomQuery .= " AND projector = '$projector'";
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["speaker"])) {
    $speaker = $_GET["speaker"];
    $roomQuery .= " AND speaker = '$speaker'";
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["guests"])) {
    $guests = $_GET["guests"];
    $roomQuery .= " AND guests = '$guests'";
}

$roomResult = mysqli_query($con, $roomQuery);

if ($roomResult === false) {
    echo "Error fetching room details: " . mysqli_error($con);
    exit();
}

// Fetch all room rows as an associative array
$rooms = mysqli_fetch_all($roomResult, MYSQLI_ASSOC);

mysqli_free_result($roomResult);
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
   <style>
     .add-room {
         max-width: 400px;
         background-color: #fff;
         padding: 20px;
         border-radius: 5px;
         margin: auto;
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }
      .add-room h2 {
         text-align: center;
         margin-bottom: 20px;
      }
      .add-room .box {
         margin-bottom: 20px;
      }
      .add-room .box p {
         margin: 0;
         font-weight: bold;
      }
      .add-room .box input,
      .add-room .box select {
         width: 100%;
         padding: 8px;
         border: 1px solid #ccc;
         border-radius: 3px;
         font-size: 16px;
      }
      .add-room .box select {
         cursor: pointer;
      }
      .add-room .btn {
         width: 100%;
         padding: 10px;
         border: none;
         border-radius: 3px;
         background-color: #4caf50;
         color: #fff;
         font-size: 16px;
         font-weight: bold;
         cursor: pointer;
      }
   </style>
</head>
<body>

   <header class="header">
      <a href="AdminPanel.php" class="logo"> ●Home </a>
      <br>
      <a href="AdminRoomsPage.php" class="logo"> ●Show All Rooms </a>
   </header>

   <section class="available">
      <form action="AdminRoomsPage.php" method="GET">
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
            <input type="submit" value="check availability" class="btn" style="margin-top: 36px;" href="AdminRoomsPage.php">
            <?php if (empty($rooms)) { ?>
               <div class="no-rooms-found" style="font-size:20px;">No rooms found with this information.</div>
            <?php } ?>
         </div>
      </form>
   </section>

   <section class="room" id="room">
      <h1 class="heading"> Rooms >></h1>
      <div class="swiper room-slider">
         <div class="wrapper">
            <?php foreach ($rooms as $room) { ?>
               <div class="slide">
                  <div class="content">
                     <h3>Room <?php echo $room['room_number']; ?></h3>
                     <img src="../images/room-1.jpg" name=img alt="">
                     <p>Available from <?php echo $room['check_in']; ?>/<?php echo $room['check_out']; ?></p>
                     <p>Projector: <?php echo $room['projector']; ?></p>
                     <p>Speaker: <?php echo $room['speaker']; ?></p>
                     <p>Guests: <?php echo $room['guests']; ?></p>
                     <?php if ($room['availability'] === '1') { ?>
                        <p>Availability: <span style="color: green;">Available</span></p>
                        <form action="AdminRoomsPage.php" method="POST">
                           <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                           <input type="hidden" name="availability" value="0">
                           <input type="submit" value="Make Unavailable">
                        </form>
                     <?php } else { ?>
                        <p>Availability: <span style="color: red;">Unavailable</span></p>
                        <form action="AdminRoomsPage.php" method="POST">
                           <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                           <input type="hidden" name="availability" value="1">
                           <input type="submit" value="Make Available">
                        </form>
                     <?php } ?>
                  </div>
               </div>
            <?php } ?>
         </div>
      </div>
   </section>

   <section class="add-room" id="reservation-<?php echo $reservation['id']; ?>">
        <h1 class="heading">Add a New Room</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="container">
                <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                <input type="hidden" name="action" value="modify">
                <div class="box">
    
                    <p>Room Number(Floor):<span>*</span></p>
                    <input type="number" name="room_number" class="input">
                </div>
                <div class="box">
            <p>Available for check in <span>*</span></p>
            <input type="date" class="input" name="check_in">
         </div>

         <div class="box">
            <p>Available for check out <span>*</span></p>
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
<div>
<div class="box">
            <p>Capacity<span>*</span></p>
            <select name="guests" id="" class="input">
   <option value="1-4">1-4</option>
   <option value="4-10">4-10</option>
   <option value="10-20">10-20</option>
   <option value="20-30">20-30</option>
</select>
            
                
            </div>
            <input type="submit" value="Add" class="btn" style="background-color: white; align-items: center; text-align: center; width: 50%; height: 50%; margin-left: 25%; color: #000;">
        </form>
    </section>