<?php

include_once('../include/auth_session.php');
require_once('../include/db.php');

$last_member_id = add_slash($_SESSION['last_member_id']);
$chat_id = add_slash($_SESSION['chat_id']);

$result = db_query("
    SELECT user_id, member_id
    FROM `chat_members`
    WHERE chat_id='$chat_id' AND member_id>'$last_member_id'
    ORDER BY member_id ASC
");

$id = $_SESSION['id'];

$result2 = db_query("
    SELECT is_admin
    FROM `chat_members`
    WHERE chat_id='$chat_id' AND user_id='$id'
");

$is_admin = mysqli_fetch_row($result2)[0];

while($row = mysqli_fetch_row($result)){
    $user_id = add_slash($row[0]);
    $last_member_id = $row[1];
    
    $result2 = db_query("
        SELECT username
        FROM `users`
        WHERE id='$user_id'
    ");
    
    $username = mysqli_fetch_row($result2)[0];
    echo("<p id='$row[0]'>");
    echo(add_html_chars($username));
    if($is_admin == "1" && $row[0] != $id)
        echo("  <button id='$row[0]' onclick='del_user($row[0], $chat_id)'>x</button>");
    echo("</p>");
}

$_SESSION['last_member_id'] = $last_member_id;
?>
