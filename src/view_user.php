<?php 
    include_once('../include/auth_session.php');
    require_once('../include/db.php');
    include_once('../include/nav_bar.php');
?>
<!DOCTYPE html>
<html>
<head>
	<link rel='stylesheet' type='text/css' href='../style/detail.css'>
    <link rel='stylesheet' href='../style/style.css'/>
	<title>View User</title>
</head>
<body>
	<?php 
    # opening this page should be available only via a POST request
    if(!isset($_REQUEST['id']) || !isset($_SESSION['id']))
        header('Location: ../src/dashboard.php');
    $id = add_slash($_REQUEST['id']);
    $result = db_query("SELECT * FROM `users` WHERE id='$id'");
    $result = mysqli_fetch_assoc($result);
    $first_name = $result['first_name'];
    $last_name = $result['last_name'];
    $username = $result['username'];
    $email = $result['email'];
    $date_of_birth = $result['date_of_birth'];
    $gender = $result['gender'];
    $school = $result['school'];
    $file = $result['profile_pic'];
    ?>
<div class="container-contact10">
        <div class="wrap-contact10">
                <form class="contact100-form validate-form" action="" method="POST">
                    <h3 class="contact100-form-title" style="color:black;font-size: 50px;">Profile Information</h3>
                    <div class='wrap-input100 validate-input'>
                    <div class="profile-image">
                        <?php echo '<img src="data:image;base64,' .base64_encode($file).'" alt="Image" style="width:150px; height:150px ; ">';?>    
                    </div>
                    </div>
                     <div class='wrap-input100 validate-input'>
                        <div class='label-centre'>
                        <label class="label-input10">Username:</label>
                        <label class="label-input10"><?php echo htmlspecialchars($username);?></label>
                        </div>
                    </div>
                    <div class="wrap-input100 validate-input">
                        <div class='label-centre'>
                        <label class="label-input10">Email:</label>
                        <label class="label-input10"><?php echo htmlspecialchars($email);?></label>
                        </div>
                    </div>
                    <div class="wrap-input100 validate-input">
                        <div class='label-centre'>
                        <label class="label-input10">First name:</label>
                        <label class="label-input10"><?php echo htmlspecialchars($first_name);?></label>
                        </div>
                    </div>
                    <div class="wrap-input100 validate-input">
                        <div class='label-centre'>
                        <label class="label-input10">Last name:</label>
                        <label class="label-input10"><?php echo htmlspecialchars($last_name);?></label>
                        </div>
                    </div>
                    <div class="wrap-input100 validate-input">
                        <div class='label-centre'>
                        <label class="label-input10">Date of birth:</label>
                        <label class="label-input10"><?php echo htmlspecialchars($date_of_birth);?></label>
                        </div>
                    </div>
                    <div class="wrap-input100 validate-input">
                        <div class='label-centre'>
                        <label class="label-input10">Gender:</label>
                        <label class="label-input10"><?php echo htmlspecialchars($gender);?></label>
                        </div>
                    </div>
                    <div class="wrap-input100 validate-input">
                        <div class='label-centre'>
                        <label class="label-input10">School:</label>
                        <label class="label-input10"><?php echo htmlspecialchars($school);?></label>
                        </div>
                    </div>
                </form>
        </div>
    </div>

</body>
</html>
