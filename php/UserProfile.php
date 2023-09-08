<?php
session_start();
$con = mysqli_connect("localhost", "webadmin", "admin1234", "login");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$username = "";
$email = ""; 
$password = ""; 

if (isset($_SESSION['email'])) {
    $userID = $_SESSION['email'];

    $sql = "SELECT username, email, password FROM logindata WHERE email = '$userID'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $email = $row['email'];
        $password = $row['password'];
    } else {
       
    }
} else {

}

$con->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

	<title>User Profile</title>
	<link rel="stylesheet" href="../UserProfile.css">
	<script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
</head>
<body>
<a href="ClientPanel.php" class="logo" style="background-color: pink; font-size:20px;"> ● Home </a>
<div class="wrapper">

    <div class="left">
      
        <img src="../img/prof.png" 
        alt="user" width="100">
        
        <h1>User Profile</h1>
        <h2 style="font-size: 30px"><?php echo $username; ?></h2>
        
         
    </div>
    <div class="right">
        <div class="info">
            <h3>Information</h3>
            <div class="info_data">
              <div class="firstrow">
                 <div class="email">
                    <h4>Email</h4>
                    <p><?php echo $email ?></p>
                 </div>
                 </div>
                 <div class="firstrow">
            </div>
              <div class="secondrow">
                <div class="birth">
                  <h4>Password</h4>
                  <p><?php echo $password ?> </p>
                </div>
                </div>
                <div class="secondrow">
              </div>
            
             </div>
           </div>
            </div>
            
        </div>
        

</body>
</html>