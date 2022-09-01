<?php
include_once('../include/auth_session.php');
require_once('../include/db.php');

$last_msg_id = add_slash($_SESSION['last_msg_id']);
$chat_id = add_slash($_SESSION['chat_id']);

$result = db_query("
    SELECT mess_content, mess_timestamp, user_id, message_id
    FROM `messages`
    WHERE chat_id='$chat_id' AND message_id>'$last_msg_id'
    ORDER BY mess_timestamp ASC"
);

while($row = mysqli_fetch_row($result)){
    $msg_content = decrypt($row[0]);
    $timestamp = $row[1];
    $user_id = add_slash($row[2]);
    $last_msg_id = $row[3];

    $result2 = db_query("
        SELECT username
        FROM `users`
        WHERE id='$user_id'
    ");

    $result3 = db_query("
        SELECT user_id
        FROM `chat_members`
        WHERE user_id='$user_id'
        AND chat_id='$chat_id'
    ");
    
    $username = mysqli_fetch_row($result2)[0];
    if($user_id == $_SESSION['id'])
        echo("<p><i>".add_html_chars($timestamp)."</i> <b>[You]</b> ".add_html_chars($msg_content)."</p>");
    else if(empty(mysqli_fetch_row($result3)[0]))
        echo("<p><i>".add_html_chars($timestamp)."</i> <b>[User deleted]</b> *This message has been deleted.*</p>");
    else
        echo("<p><i>".add_html_chars($timestamp)."</i> <b>[".add_html_chars($username)."]</b> ".add_html_chars($msg_content)."</p>");
    echo("<hr>");
}

$_SESSION['last_msg_id'] = $last_msg_id;

if(!isset($_SESSION['id']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest'){
    echo "<script>window.location.reload()</script>";
}
?>
