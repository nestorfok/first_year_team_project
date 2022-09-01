<?php 
    include_once('../include/auth_session.php');
    require_once('../include/db.php');
    include_once('../include/nav_bar.php');
    $id = add_slash($_SESSION['id']);
    $errorMsg = "";
    $new_auth = "";
    $new_username = "";
    $new_email = "";
    $new_password = "";
    $new_password2 = "";
    $new_first_name = "";
    $new_last_name = "";
    $new_gender = "";
    $new_school = "";
    $new_file = "";
    if(isset($_REQUEST['update'])) 
    {
         // declare variable
        $new_auth = md5(add_slash($_REQUEST['oldpassword']));
        $new_username = add_slash($_REQUEST['username']);
        $new_email = add_slash($_REQUEST['email']);
        $new_password = add_slash($_REQUEST['password']);
        $new_password2 = add_slash($_REQUEST['password2']);
        $new_first_name = add_slash($_REQUEST['first_name']);
        $new_last_name = add_slash($_REQUEST['last_name']);
        $new_gender = $_REQUEST['gender'];
        $new_school = add_slash($_REQUEST['school']);
        $new_file = false;
        if(!empty($_FILES["image"]["tmp_name"]))
            $new_file = add_slash(file_get_contents($_FILES["image"]["tmp_name"]));
            
        //fetching the password
        $result = db_query("SELECT password FROM `users` WHERE id='$id'");
        $result = mysqli_fetch_assoc($result);
        $auth = $result['password'];

        // check if there are same username and email
        $res_u = db_query("
            SELECT id
            FROM `users`
            WHERE username='$new_username'
            AND id<>'$id'
        ");
        $res_e = db_query("
            SELECT id
            FROM `users`
            WHERE email='$new_email'
            AND id<>'$id'
        ");
        
        if($auth <> $new_auth){
            $errorMsg = "Wrong password!";
        }
        else if($new_password <> $new_password2){
            $errorMsg = "The passwords do not match!";
        }
        else{
            // update username
            if(empty($errorMsg) && !empty($new_username)){
                if (mysqli_num_rows($res_u) > 0){
                    $errorMsg = "The username has already been taken";
                }
                else if(!ctype_alnum($new_username)){
                    $errorMsg = "The username can contain only alphanumeric characters!";
                }
                else{
                    db_query("UPDATE `users` SET username='$new_username' WHERE id='$id'");
                }
            }
            // update email
            if(empty($errorMsg) && !empty($new_email)) 
            {
                if(mysqli_num_rows($res_e) > 0){
                    $errorMsg = "The email has already been taken!";    
                }
                else{
                    db_query("UPDATE `users` SET email='$new_email' WHERE id='$id'");
                }
            }
            // update first name
            if(empty($errorMsg) && !empty($new_first_name)){
                db_query("UPDATE `users` SET first_name='$new_first_name' WHERE id='$id'");
            }
            // update last name
            if(empty($errorMsg) && !empty($new_last_name)){
                db_query("UPDATE `users` SET last_name='$new_last_name' WHERE id='$id'");
            }
            // update gender
            if(empty($errorMsg) && !empty($new_gender)){
                db_query("UPDATE `users` SET gender='$new_gender' WHERE id='$id'");
            }
            // update university
            if(empty($errorMsg) && !empty($new_school)){
                db_query("UPDATE `users` SET school='$new_school' WHERE id='$id'");
            }
            // update new password
            if(empty($errorMsg) && !empty($new_password)){
                db_query("UPDATE `users` SET password='" . md5($new_password) . "' WHERE id='$id'");
            }
            // update profile pic
            if(empty($errorMsg) && !empty($new_file)){
                if($_FILES['image']['type'] <> "image/jpeg" &&
                    $_FILES['image']['type'] <> "image/jpg" &&
                    $_FILES['image']['type'] <> "image/png"){
                    $errorMsg = "The given file type is not supported! Use .jpg, .jpeg or .png";
                }
                else{
                    db_query("UPDATE `users` SET profile_pic='$new_file' WHERE id='$id'");
                }
            }
        }
    }
    if(isset($_REQUEST['update']) && $errorMsg == ""){
        header('Location: ../src/detail.php');
    }
    else{
        $result = db_query("SELECT * FROM `users` WHERE id='$id'");
        $result = mysqli_fetch_assoc($result);
        $first_name = $result['first_name'];
        $last_name = $result['last_name'];
        $username = $result['username'];
        $email = $result['email'];
        $password = $result['password'];
        $date_of_birth = $result['date_of_birth'];
        $gender = $result['gender'];
        $school = $result['school'];
        $file = $result['profile_pic'];
?>

<!DOCTYPE html>
<html>
<head>
    <link rel='stylesheet' type='text/css' href='../style/mypost.css'>
    <link rel='stylesheet' type='text/css' href='../style/style.css'>
    <title>My Post</title>
</head>
<body>
    <br><br><br><br>
    <div class="container-contact10">
        <div class="wrap-contact10">
            <h3 class="contact100-form-title" style="color:black;font-size: 50px;">My Profile</h3>
            <form class="contact100-form validate-form" method="POST" action="" enctype="multipart/form-data">
                <div class="wrap-input100 validate-input">
                <?php
                    if($file){
                        echo('
                            <div style="text-align:center">
                            <img src="data:image;base64,' .base64_encode($file).'" alt="Image" style="border-radius = 50%;width:100px;height:100px;">
                            </div>
                        ');
                    }
                ?>
                </div>
                <div class="wrap-input100 validate-input">
                <div class="label-input100">
                    <label>Choose a Profile Picture:</label><br>
                    <input type="file" name="image" id="image"><br>
                </div>
                </div>
                <div class="wrap-input100 validate-input">
                <div class="label-input100">
                    <label>Username:</label>
                    <input class='input100' type="text" id="username" name="username" 
                    placeholder="<?php echo add_html_chars($username);?>"
                    <?php if(!empty($new_username)) echo("value='".add_html_chars($new_username)."'");?>>
                </div>
                </div>
                <div class="wrap-input100 validate-input">
                <div class="label-input100">
                    <label>Email:</label>
                    <input class='input100' type="email" id="email" name="email" 
                    placeholder="<?php echo add_html_chars($email)?>"
                    <?php if(!empty($new_email)) echo("value='".add_html_chars($new_email)."'");?>>
                </div>
                </div>
                <div class="wrap-input100 validate-input">
                <div class="label-input100">
                    <label>First name:</label>
                    <input class='input100' type="text" id="first_name" name="first_name"
                    placeholder="<?php echo add_html_chars($first_name)?>"
                    <?php if(!empty($new_first_name)) echo("value='".add_html_chars($new_first_name)."'");?>>
                </div>
                </div>
                <div class="wrap-input100 validate-input">
                <div class="label-input100">
                    <label>Last name:</label>
                    <input class='input100' type="text" id="last_name" name="last_name"
                    placeholder="<?php echo add_html_chars($last_name)?>"
                    <?php if(!empty($new_last_name)) echo("value='".add_html_chars($new_last_name)."'");?>>
                </div>
                </div>
                <div class="wrap-input100 validate-input">
                <div class="label-input100">
                    <label>Gender:</label>
                    <div class="select_gender">
                        <select name="gender">
                            <?php
                            $base_gender = $gender;
                            if(!empty($new_gender))
                                $base_gender = $new_gender;
                            ?>
                            <option value="Male" <?php if($base_gender=="Male") echo("selected");?>>Male</option>
                            <option value="Female" <?php if($base_gender=="Female") echo("selected");?>>Female</option>
                            <option value="Other" <?php if($base_gender=="Other") echo("selected");?>>Other</option>
                        </select>
                    </div>
                </div>
                </div>
                <div class="wrap-input100 validate-input">
                <div class="label-input100">
                    <label>School:</label>
                    <input class='input100' type="text" id="school" name="school"
                    placeholder="<?php echo add_html_chars($school)?>"
                    <?php if(!empty($new_school)) echo("value='".add_html_chars($new_school)."'");?>>
                </div>
                </div>
                <div class="wrap-input100 validate-input">
                <div class="label-input100">
                    <label>New password:</label>
                    <input class='input100' type="password" id="password" name="password">
                </div>
                </div>
                <div class="wrap-input100 validate-input">
                <div class="label-input100">
                    <label>Repeat new password:</label>
                    <input class='input100' type="password" id="password2" name="password2">
                </div>
                </div>    
                <div class="wrap-input100 validate-input">
                <div class="label-input100">
                    <label>Current password:</label>
                    <input class='input100' type="password" id="oldpassword" name="oldpassword" required>
                </div>
                <?php
                    if(!empty($errorMsg)) echo("<p style='color:red;text-align:center;'>$errorMsg</p>");
                ?></div>
                <br>
                <div class="label-input100"> 
                    <input name="update" type="submit" id="update" value="Save" class="kash">
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php
    }
?>
