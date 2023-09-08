<?php
$error_message = '';
session_start();
$con = mysqli_connect("localhost", "webadmin@gmail.com", "admin1234", "login");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = mysqli_real_escape_string($con, $_POST["email"]); // Escape user input
    $password = mysqli_real_escape_string($con, $_POST["password"]); // Escape user input
    $query = "SELECT * FROM logindata WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $role = $row['role'];

        $_SESSION['email'] = $email;

        if ($role === "client") {
            header("Location: ClientPanel.php");
            exit();
        } elseif ($role === "admin") {
            header("Location: AdminPanel.php");
            exit();
        }
    } else {
        $error_message = '<div class="error-box">This user does not exist. Please register.</div>';
    }
}

mysqli_close($con); 
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../login.css">
    <style>
        .error-box {
            
            
            color: #721c24;
            padding: 10px;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navigation"> 
        </nav>
    </header>
    <div class="wrapper">
        <div class="error-box login">
            <?php echo $error_message; ?>
    
            <h2>Login</h2>
            
            <form action="" method="POST">
                <div class="input-box">
                    <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                      </svg></span>
                    <input type="email" name="email" required autocomplete="off">
                    <label></label>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                            <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                          </svg></span>
                    <input type="password" name="password" required autocomplete="off">
                    <label></label>
                </div>
                <button type="submit" class="btn">Login</button>
                <div class="login-register">
                    <p>Don't you have an account?<a href="register.php" class="register-link">Register</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
