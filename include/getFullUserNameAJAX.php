<?php
require_once('../include/db.php');

$user_id = add_slash($_GET['q']);

$result = db_query("SELECT first_name, last_name FROM users WHERE id = $user_id");
$arr =  mysqli_fetch_array($result);
echo(add_html_chars($arr[0]) . " " . add_html_chars($arr[1]));
?>
