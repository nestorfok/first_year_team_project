<?php
require_once('../include/db.php');
$ad_id = add_slash($_REQUEST['ad_id']);
$chat_id = db_query("SELECT chat_id FROM `ads` WHERE ad_id='$ad_id'");
$chat_id = add_slash(mysqli_fetch_row($chat_id)[0]);

db_query("DELETE FROM `ads` WHERE ad_id='$ad_id'");
if($chat_id != NULL){
    db_query("DELETE FROM `chats` WHERE chat_id='$chat_id'");
    db_query("DELETE FROM `chat_members` WHERE chat_id='$chat_id'");
    db_query("DELETE FROM `messages` WHERE chat_id='$chat_id'");
}
else{
    db_query("DELETE FROM `comments` WHERE ad_id='$ad_id'");
}
?>
