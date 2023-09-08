<?php
    $con = mysqli_connect("localhost", "webadmin@gmail.com", "admin1234", "login");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Panel</title>
    <link rel="stylesheet" type="text/css"
        href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../ClientPanel.css">
</head>

<body> 
    <div class="container">
        <div class="navigationbar">
            <div class="logo">
                <img style="width: 40px;" src="../img/home.png" alt="">
                <span class="logoname">Client Panel</span>
            </div>
            
            <div class="searchbox">
                <form action="">
                    <input type="text" placeholder="Search in Website...">
                    <i class="fa fa-search"></i>
                </form>
            </div>

            <div class="item">
                <div class="icons">
                    <ul>
                        <li><i class="fa fa-envelope"></i></li>
                        <li><i class="fa fa-bell"></i></li>
                    </ul>
                </div>

                <div class="account">
                    <img width="25px" height="25px" src="../img/prof.png" alt="">
                    <span class="name">Username</span>
                </div>
            </div>
        </div>
        
        <div class="con-body">
            <div class="sidebar">
                <ul>
                    <li class="dashboard">
                        <i class="fa fa-user"></i>
                        <a href="UserProfile.php">User</a>
                    </li>
                    <li>
                        <i class="fa fa-heart"></i>
                        <a href="favorite.php">Favorite Rooms</a>
                    </li>
                    <li>
                        <i class="fa fa-envelope-o"></i>
                        <a href="messageUser.php">Messages</a>
                    </li>
                    <li>
                        <i class="fa fa-times"></i>
                        <a href="login.php">Log Out</a>
                    </li>
                </ul>
            </div>

            <div class="main-body">
                <div class="headtittle">
                    <span class="welcome">Hello User!</span>
                    <h2>Let's Start Reservation...</h2>
                </div>

                <div class="menu">
                    <div class="row">
                        <a href="PastReservation.php">
                        <div class="col">
                            <div class="reserve">
                                <h2 class="titling">➪ Reservations</h2>
                                <img width="100px" height="100px" src="../img/room.png" alt="">
                            </div>
                        </div>
                    </a>
                    <a href="RoomsPage.php">
                        <div class="col">
                            <div class="roomsearch">
                                <h2 class="titling">➪ Searching For a Room</h2>
                                <img width= "100px" src="../img/reservation.png" alt="">
                            </div>
                        </div>
                        </a>
                        
                    </div>
                    <div class="last">
                        <img width=80% src="../img/last.png" alt="">
                    </div>
                </div>
                <img class="test" src="../img/moving.png" alt="" width="40px">
            </div>
        </div>
    </div>
</body>

</html>
