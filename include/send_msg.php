<?php
    include_once('../include/auth_session.php');
    require_once('../include/db.php');

    $msg_content = add_slash($_REQUEST['msg']);
    $user_id = add_slash($_SESSION['id']);
    $chat_id = add_slash($_SESSION['chat_id']);
    
    if(is_swear($msg_content)){
        echo(get_swear($msg_content));
    }
    else{
        $msg_content = encrypt($msg_content);
        db_query("
            INSERT INTO `messages` (chat_id, user_id, mess_content)
            VALUES ('$chat_id', '$user_id', '$msg_content')
        ");    
    }
?>
