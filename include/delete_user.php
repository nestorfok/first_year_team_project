<?php
include_once('../include/db.php');

$user_id = add_slash($_REQUEST['user_id']);
$chat_id = add_slash($_REQUEST['chat_id']);

db_query("
    DELETE FROM `chat_members`
    WHERE user_id='$user_id'
    AND chat_id='$chat_id'
");

?>
