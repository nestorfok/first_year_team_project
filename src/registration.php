<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Registration</title>
    <link rel='stylesheet' href='../style/style.css'/>
</head>
<body>
<?php
    require_once('../include/db.php');
    session_start();
    // When form submitted, insert values into the database.
    $errorMsg = "";
    $username = "";
    $first_name = "";
    $last_name = "";
    $email = "";
    $password = "";
    $gender = "";
    $date_of_birth = "";
    $school = "";

    if (isset($_REQUEST['username'])) {
        $username = add_slash($_REQUEST['username']);
        $first_name = add_slash($_REQUEST['first_name']);
        $last_name = add_slash($_REQUEST['last_name']);
        $email    = add_slash($_REQUEST['email']);
        $password = add_slash($_REQUEST['password']);
        $password2 = add_slash($_REQUEST['password2']);
        $gender = add_slash($_REQUEST['gender']);
        $date_of_birth = add_slash($_REQUEST['date_of_birth']);
        $create_datetime = add_slash(date("Y-m-d H:i:s"));
        $school = add_slash($_REQUEST['school']);
        $result    = db_query("SELECT * FROM `users` WHERE email='$email'");
        $rows = mysqli_num_rows($result);
        $result2    = db_query("SELECT * FROM `users` WHERE username='$username'");
        $rows2 = mysqli_num_rows($result2);
        
        if($password <> $password2){
            $errorMsg = "The passwords do not match!";
        }
        else if(strlen($password) < 8){
            $errorMsg = "The password must contain at least 8 characters!";
        }
        else if((substr($create_datetime, 0, 4) + 0) - (substr($date_of_birth, 0, 4)  + 0) < 18){
            $errorMsg = "The user you are trying to register is under 18.";
        }
        else if(((substr($create_datetime, 5, 2)  + 0) < (substr($date_of_birth, 5, 2)  + 0)) && (substr($create_datetime, 0, 4) + 0) - (substr($date_of_birth, 0, 4)  + 0) == 18){
            $errorMsg = "The user you are trying to register is under 18.";
        }
        else if(((substr($create_datetime, 8, 2)  + 0) < (substr($date_of_birth, 8, 2)  + 0)) && ((substr($create_datetime, 5, 2)  + 0) == (substr($date_of_birth, 5, 2)  + 0)) && ((substr($create_datetime, 0, 4) + 0) - (substr($date_of_birth, 0, 4)  + 0) == 18)){
            $errorMsg = "The user you are trying to register is under 1833.";
        }
        else if(!ctype_alnum($username)){
            $errorMsg = "The username can contain only alphanumeric characters! (letters and numbers)";
        }
        else if($rows != 0 and $rows2 != 0){
            $errorMsg = "Both the username and email are already in use.";
        }
        else if ($rows2 != 0){
            $errorMsg = "The username is already in use.";
        }
        else if ($rows != 0){
            $errorMsg = "The email is already linked to an account.";
        }
        else{
            $new_file = add_slash(file_get_contents("../resources/default_profile_pic.jpg"));
            $result = db_query("
                INSERT into `users` (first_name, last_name, username, password, email, gender, date_of_birth,
                                                  create_datetime, school, profile_pic)
                VALUES ('$first_name', '$last_name', '$username', '" . md5($password) . "', '$email',
                                     '$gender', '$date_of_birth', '$create_datetime', '$school', '$new_file')
            ");
            header('Location: login.php');
        }
    }
    else if(isset($_SESSION['id'])){
        header('Location: dashboard.php');
    }
?>
    <form class="form-register" id="form" action="" method="post">
        <h1 class="login-title">Registration</h1>
        <input type="text" class="login-input" name="username" placeholder="Username" required
            <?php echo("value='".add_html_chars($username)."'");?>/>
        <input type="text" class="login-input" name="first_name" placeholder="First name" required
            <?php echo("value='".add_html_chars($first_name)."'");?>/>
        <input type="text" class="login-input" name="last_name" placeholder="Last name" required
            <?php echo("value='".add_html_chars($last_name)."'");?>/>
        <input type="email" class="login-input" name="email" placeholder="Email Adress" required
            <?php echo("value='".add_html_chars($email)."'");?>>
        <input type="password" class="login-input" name="password" placeholder="Password" required>
        <input type="password" class="login-input" name="password2" placeholder="Repeat password" required>
        <label>Gender:</label><br>
        <select name="gender" required>
            <option value="Male" <?php if($gender=="Male") echo("selected");?>>Male</option>
            <option value="Female" <?php if($gender=="Female") echo("selected");?>>Female</option>
            <option value="Other" <?php if($gender=="Other") echo("selected");?>>Other</option>
        </select>
        <br>
        <label for="start">Date of birth:</label><br>
        <input type="date" name="date_of_birth"
            <?php echo("value='".add_html_chars($date_of_birth)."'");?> required>
        <input type="text" class="login-input" name="school" placeholder="School (Optional)"
            <?php echo("value='".add_html_chars($school)."'");?>/>
        <input type="submit" name="submit" value="Register" class="button register-button">
        <?php echo("<p style='color:red;text-align:center;'>$errorMsg</p>");?>
        <p class="linkX">Already have an account? <a href="../src/login.php">Login here</a></p>
    </form>
</body>
</html>
