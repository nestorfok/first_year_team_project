<?php
require_once('../include/db.php');
$id = add_slash($_SESSION["id"]);
$result = db_query("SELECT * FROM `users` WHERE id = $id");
$result = mysqli_fetch_array($result);
?>


<div class="nav">
    <img src="../resources/navbarlogo.png" alt="REVISIFY">
    <a href="../src/dashboard.php">Main Page</a>
    <a class="create_post_nav" href="../src/create_post.php">CREATE A POST</a>
    <a href="../src/my_posts.php">My Posts</a>
    <a class="myacc" href="../src/detail.php">My Account</a>
    <div style="display: flex;">
    <?php echo ('<img style="width: 70px; height: 70px; border-radius: 50%;" src="data:image/jpeg;base64,'.base64_encode( $result['profile_pic'] ).'"/>
    	<p class="nav_username">'.$result['username'].'</p>');?>
    </div>
    <a class="right" href="../src/logout.php">Logout</a>
</div>
