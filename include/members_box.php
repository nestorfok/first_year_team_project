<?php include_once('../include/auth_session.php'); ?>

<div id='members_box' class='members_box'>
<?php
    $_SESSION['last_member_id'] = -1;
    include_once('../include/chat_members.php');
?>
</div>
