<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Login</title>
    <link rel='stylesheet' href='../style/style.css'/>
</head>
<body>
<?php
    require_once('../include/db.php');
    session_start();
    // When form submitted, check and create user session.
    $errorMsg = "";
    $username = "";
    $password = "";
    if (isset($_POST['username'])) {
        $username = add_slash($_REQUEST['username']);    // removes backslashes
        $password = add_slash($_REQUEST['password']);
        // Check user is exist in the database
        $result   = db_query("SELECT id FROM `users` WHERE username='$username'
                     AND password='" . md5($password) . "'");
        $rows = mysqli_num_rows($result);
        if ($rows == 1) {
            $row = mysqli_fetch_row($result);
            $_SESSION['id'] = $row[0];
            $_SESSION['username'] = $username;
            // Redirect to user dashboard page
            header("Location: ../src/dashboard.php");
        }
        else{
            $errorMsg = "Incorrect username/password";
        }
        mysqli_free_result($result);
    }
    else if(isset($_SESSION['id'])){
        header("Location: ../src/dashboard.php");
    }
?>
    <div class = "logo-image" >
        <div id= "oval"></div>
        <img src="../resources/logo-name1.png">
    </div>
    <div>
        <form class = "form" id="form" method="post" name="login">
            <h1 class="login-title">Welcome!</h1>
            
            <input type="text" class="login-input" name="username" placeholder="Username" autofocus="true"
                <?php if(!empty($username)) echo("value='".add_html_chars($username)."'")?>/>
            
            <input type="password" class="login-input" name="password" placeholder="Password"/>
            
            <?php
                if(!empty($errorMsg)) echo("<p style='color:red;text-align:center'>$errorMsg</p>");
            ?>
            
            <button type="submit" value="Login" name="submit" class="button login-button">Login</button>
        </form>
        <div class="register-op" id="form" >
            <p class="linkX">Don't have an account? </p>
            <a class='link-button' href="../src/registration.php">Register Here!</a>
        </div> 
    </div>
</body>
</html>
