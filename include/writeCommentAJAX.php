<?php
require_once('../include/db.php');

$user_id = add_slash($_GET['user_id']);
$ad_id = add_slash($_GET['ad_id']);
$comment_content = add_slash($_GET['comment_content']);
if(is_swear($comment_content))
    $comment_content = "***";
$current_date = add_slash(date("Y-m-d H:i:s"));

$result = db_query("INSERT into `comments` (ad_id, user_id, comm_content, comment_timestamp)
                             VALUES ('$ad_id', '$user_id', '$comment_content', '$current_date')");
$arr =  mysqli_fetch_array($result);
echo(add_html_chars($arr[0]) . " " . add_html_chars($arr[1]));
?>
