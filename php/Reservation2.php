<?php
$error_message = '';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$con = mysqli_connect("localhost", "webadmin@gmail.com", "admin1234", "rooms");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Check if the reservation form is submitted
if (isset($_POST['reserve'])) {
    $selectedRoom = isset($_POST['room_id']) ? intval($_POST['room_id']) : null;
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
        
    }

    mysqli_stmt_close($stmt);
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../Reservation.css">
    <?php if (isset($message)): ?>
        <meta http-equiv="refresh" content="3;url=clientpanel.php">
    <?php endif; ?>
</head>

<body>

    <section class="reservation" id="reservation">
        
            <h1 class="heading">Reservation Form</h1>
            <form action="reservation.php" method="POST">
                <div class="container">
                    <div class="box">
                        <p>Name <span>*</span></p>
                        <input type="text" class="input" placeholder="Your Name" name="name" required>
                    </div>

                    <div class="box">
                        <p>Email <span>*</span></p>
                        <input type="text" class="input" placeholder="Your Email" name="email" required>
                    </div>

                    <div class="box">
                        <p>Check-in Date <span>*</span></p>
                        <input type="date" class="input" name="check_in" required>
                    </div>

                    <div class="box">
                        <p>Check-out Date<span>*</span></p>
                        <input type="date" class="input" name="check_out" required>
                    </div>

                    <div class="box">
                        <p>Reservation Type <span>*</span></p>
                        <select class="input" name="type" required>
                            <option value="Meeting">Meeting</option>
                            <option value="Exam">Exam</option>
                            <option value="Classroom">Classroom</option>
                        </select>
                    </div>

                    <div class="box">
                        <p>Reservation Notes <span>*</span></p>
                        <textarea name="notes" cols="100" rows="3" placeholder="Write your notes..." style="color: gray;"></textarea>
                    </div>

                    <div class="box">
                        <p>Number of Attendees <span>*</span></p>
                        <input type="number" min="1" max="30" class="input" style="width: 40px;" name="attendance" required>
                    </div>

                    <div class="box">
                        <p>Time:</p>
                        <input type="time" name="time" id="time">
                    </div>

            


                </div>
                <br>
                <input type="submit" value="Submit" class="btn" style="align-items: center; text-align: center; margin-left:auto" name="reserve">
                <?php if (!empty($error_message)) { ?>
                    <div style="background-color: #FAC898; color: #00000; font-size:20px; padding: 10px; margin-top: 10px;"><?php echo $error_message; ?></div>
                <?php } ?>
            </form>
       
    </section>
    <section class="review" id="review">
        <div class="swiper review-slide">
            <div class="swiper-wrap">
                <div class="swiper-slide slide">
                    <h2 class="heading">We are here to hear your comments..</h2>
                    <i class="fas fa-quote-right"></i>
                    <textarea class="text" rows="5" cols="80" placeholder="Send feedback regarding your experience..."></textarea>
                    <i class="fas fa-quote-right"></i>
                    <input type="submit" value="Submit" class="btn2">
                </div>
            </div>
        </div>
    </section>

    <section class="footer">
        <div class="box-container">
            <div class="box">
                <h3>contact info</h3>
                <a href="#"><i class="fas fa-phone"></i> +90 5318962154</a>
                <a href="#"><i class="fas fa-envelope"></i> admin@gmail.com</a>
                <a href="#"><i class="fas fa-map"></i> Istanbul/Turkey</a>
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
