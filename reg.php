<?php
session_start();

include("connection.php");
include("function.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];


    if (isset($_POST['sign-in'])) {
        // Sign-In
        $user_name = $_POST['user_name'];
        $password = $_POST['password'];
    
        if (!empty($user_name) && !empty($password)) {
            // Assuming $con is defined and represents your database connection
            // Check if the user exists in the 'users' table using prepared statements
            $query = "SELECT * FROM users WHERE user_name = ? AND password = ?";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "ss", $user_name, $password);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
    
            // Check if the user exists in the 'admin' table using prepared statements
            $query2 = "SELECT * FROM admins WHERE AD_user = ? AND AD_pass = ?";
            $stmt2 = mysqli_prepare($con, $query2);
            mysqli_stmt_bind_param($stmt2, "ss", $user_name, $password);
            mysqli_stmt_execute($stmt2);
            $result2 = mysqli_stmt_get_result($stmt2);
    
            if (mysqli_num_rows($result) == 1) {
                // Successful sign-in for a regular user
                $_SESSION['user_name'] = $user_name;
        
                // You can use $user_name, $password, or other data here
        
                header("Location: index.php");
                die;
            } elseif (mysqli_num_rows($result2) == 1) {
                // Successful sign-in for an admin
                $_SESSION['user_name'] = $user_name;
                header("Location: admin.php");
                die;
            } else {
                
            }
    
            mysqli_close($con);
        }
    }
    elseif (isset($_POST['sign-up'])) {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $user_name = $_POST['user_name'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        // Sign-Up
        if (!empty($user_name) && !empty($email) && !empty($password) && !is_numeric($user_name)) {
            // Save sa database
            $user_id = random_num(20);
            $query = "INSERT INTO users (user_id, user_name, password, email) VALUES ('$user_id', '$user_name', '$hashedPassword', '$email')";  
            mysqli_query($con, $query);
            header("Location: signup.php");
            die;
        
        } 
    }
}
}    

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="reg.css">
    <title>Login Page</title>
</head>

<body>
<div class="container" id="container">
    <!-- Sign-up form with reCAPTCHA and password policy validation -->
    <div class="form-container sign-up">
        <form action="#" class="sign-up-form" method="POST">
            <h1>Create Account</h1>
            <div class="social-icons">
                <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
            </div>
            <span>or use your email for registration</span>
            <input type="text" name="user_name" placeholder="Username" />
            <input type="text" name="email" placeholder="Email" />
            <input type="password" name="password" placeholder="Password" />
            <span class="password-policy-error" id="signUpPasswordPolicy"></span>
            <div class="g-recaptcha" data-sitekey="6LdR4OApAAAAAL_ZBVwp7Gw_RbJD-4_L2PxeODJ-"></div>
            <input type="submit" name="sign-up" id="registerBtn" class="btn solid" value="Sign up" />
        </form>
    </div>

    <!-- Sign-in form with reCAPTCHA -->
    <div class="form-container sign-in">
        <form action="#" class="sign-in-form" method="POST">
            <h1>Sign In</h1>
            <div class="social-icons">
                <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
            </div>
            <span>or use your email password</span>
            <input type="text" name="user_name" placeholder="Username" />
            <input type="password" name="password" placeholder="Password" />
            <div class="g-recaptcha" data-sitekey="6LdR4OApAAAAAL_ZBVwp7Gw_RbJD-4_L2PxeODJ-"></div>
            <a href="#">Forget Your Password?</a>
            <input type="submit" name="sign-in" id="loginBtn" value="Login" class="btn solid" />
        </form>
    </div>

    <div class="toggle-container">
        <div class="toggle">
            <div class="toggle-panel toggle-left">
            <img src="img/Codehelixlogo.png" alt="Your Logo" class="logo"> <br>
                <h1>Welcome Back!</h1>
                <p>Enter your personal details to use all of site features</p>
                <button class="hidden" id="signInButton">Sign In</button>
            </div>
            <div class="toggle-panel toggle-right">
                <img src="img/Codehelixlogo.png" alt="Your Logo" class="logo"> <br>
                <h1>CodeHelixCorp</h1>
                <p>a E-Learning Platform</p>
                <button class="hidden" id="signUpButton">Sign Up</button>
            </div>
        </div>
    </div>
</div>

    
    <script src="reg.js"></script>

</body>

</html>