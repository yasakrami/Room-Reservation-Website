<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize the session
session_start();

$con = mysqli_connect("localhost", "webadmin@gmail.com", "admin1234", "rooms");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit;
}

// Retrieve the selected room information from the session
$filteredRooms = $_SESSION["filtered_rooms"] ?? [];

// Retrieve future reservations from the "reserved" table
$reservationQuery = "SELECT * FROM reserved ORDER BY time DESC";
$reservationResult = mysqli_query($con, $reservationQuery);
$reservations = mysqli_fetch_all($reservationResult, MYSQLI_ASSOC);

// Check if there are reservations
$reservationCount = count($reservations);
$currentReservation = $reservationCount > 0 ? $reservations[$reservationCount - 1] : null;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the reservation ID from the form
    $reservationId = $_POST['reservation_id'];
    $action = $_POST['action'];

    if ($action === 'modify') {
        // Retrieve the reservation details from the database
        $reservationQuery = "SELECT * FROM reserved WHERE id = $reservationId";
        $reservationResult = mysqli_query($con, $reservationQuery);
        $reservation = mysqli_fetch_assoc($reservationResult);

        // Update the reservation details based on the submitted form data
        $newCheckIn = $_POST['check_in'];
        $newCheckOut = $_POST['check_out'];
        $newTime = $_POST['time'];
        $newType = $_POST['type'];
        $newNotes = $_POST['notes'];

        // Perform the update query
        $updateQuery = "UPDATE reserved SET check_in = '$newCheckIn', check_out = '$newCheckOut', time = '$newTime', type = '$newType', notes = '$newNotes' WHERE id = $reservationId";
        $updateResult = mysqli_query($con, $updateQuery);

        if ($updateResult) {
            // Show success message
            $successMessage = "Reservation modified successfully!";
        } else {
            // Show error message
            $errorMessage = "Failed to modify reservation.";
        }
    } elseif ($action === 'cancel') {
        // Perform the cancellation query
        $cancelQuery = "DELETE FROM reserved WHERE id = $reservationId";
        $cancelResult = mysqli_query($con, $cancelQuery);

        if ($cancelResult) {
            // Show success message
            $successMessage = "Reservation canceled successfully!";
            // Remove the canceled reservation from the $reservations array
            foreach ($reservations as $key => $reservation) {
                if ($reservation['id'] == $reservationId) {
                    unset($reservations[$key]);
                    break;
                }
            }
        } else {
            // Show error message
            $errorMessage = "Failed to cancel reservation.";
        }
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
    <title>Past Reservation</title>
    <link rel="stylesheet" href="../PastReservation.css">
</head>
<body>

    <header class="header">
        <a href="#" class="logo"> <i class="fas fa-hotel"></i>  ●YAYHotels </a>
        <nav class="navbar">
            <a href="AdminPanel.php">home</a>
        </nav>
        <div id="menu-btn" class="fas fa-bars"></div>
    </header>

    <section class="room" id="room">
        <br> <br> <br>
        <?php if (isset($successMessage)) { ?>
            <p style="color: green; font-size:20px; align-item:center;"><?php echo $successMessage; ?></p>
            <script>
                setTimeout(function () {
                    window.location.href = 'AdminPanel.php';
                }, 2000); // Redirect after 3 seconds
            </script>
        <?php } else { ?>
            <?php if (isset($errorMessage)) { ?>
                <p style="color: red;"><?php echo $errorMessage; ?></p>
            <?php } ?>
        <?php } ?>
        <h1 class="heading">User Reservations</h1>

        <div class="swiper room-slider">
            <div class="swiper-wrapper">
            <?php foreach ($reservations as $key => $reservation) { ?>
    <div class="swiper-slide slide">
        <div class="image">
            <img src="../images/grad.jpg" alt="" />
        </div>
        <div class="content">
            <h3>Room <?php echo $reservation['room']; ?></h3>
            <h2>Reserved by <?php echo $reservation['email'];?></h2>
            <p>Check In: <?php echo $reservation['check_in']; ?></p>
            <p>Check Out:<?php echo $reservation['check_out']; ?></p>
            <p>Type: <?php echo $reservation['type']; ?></p>
            <p>Notes: <?php echo $reservation['notes']; ?></p>
            <p>Time: <?php echo $reservation['time']; ?></p>
            <p>Number of Attendance: <?php echo $reservation['attendance']; ?></p>
            <a href="#reservation-<?php echo $reservation['id']; ?>" class="btn">Modify Reservation</a>
            <form method="POST" style="display: inline-block;">
                <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                <input type="hidden" name="action" value="cancel">
                <button type="submit" class="btn" onclick="return confirm('Are you sure you want to cancel this reservation?')">Cancel Reservation</button>
            </form>
        </div>
    </div>

    <section class="reservation" id="reservation-<?php echo $reservation['id']; ?>">
        <h1 class="heading">Modify Reservation</h1>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="container">
                <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                <input type="hidden" name="action" value="modify">
                <div class="box">
                    <p>Change Check In Date <span>*</span></p>
                    <input type="date" name="check_in" class="input" value="<?php echo $reservation['check_in']; ?>">
                </div>
                <div class="box">
                    <p>Change Check Out Date<span>*</span></p>
                    <input type="date" name="check_out" class="input" value="<?php echo $reservation['check_out']; ?>">
                </div>
                <div class="box">
                    <p>Change Time: <span>*</span></p>
                    <input type="time" name="time" value="<?php echo $reservation['time']; ?>">
                </div>
                <div class="box">
                    <p>Change Reservation Type <span>*</span></p>
                    <select name="type" class="input">
                        <option value="Meeting" <?php if ($reservation['type'] === 'Meeting') echo 'selected'; ?>>Meeting</option>
                        <option value="Exam" <?php if ($reservation['type'] === 'Exam') echo 'selected'; ?>>Exam</option>
                        <option value="Classroom" <?php if ($reservation['type'] === 'Classroom') echo 'selected'; ?>>Classroom</option>
                    </select>
                </div>
                <div class="box">
                    <p>More Notes to Add? <span>*</span></p>
                    <textarea name="notes" cols="100" rows="3" placeholder="Write your notes..." style="color: gray;"><?php echo $reservation['notes']; ?></textarea>
                </div>
                <input type="submit" value="Modify" class="btn" style="background-color: white; align-items: center; text-align: center; width: 50%; height: 50%; margin-left: 25%; color: #000;">
            </div>
        </form>
    </section>
<?php } ?>

                    
            </div>
        </form>
    </section>

    <section class="footer">
        <div class="box-container">
            <div class="box">
                
            </div>
        </div>
    </section>
</body>
</html>
