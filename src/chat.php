<?php
    include_once('../include/auth_session.php');
    require_once('../include/db.php');
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Chat</title>
        <link rel='stylesheet' href='../style/style.css'>
        <script src='../include/chats.js'></script>
    </head>
    <body>
<?php
    
    if(!isset($_REQUEST['chat_id']))
        header('Location: ../src/dashboard.php');
    
    include_once('../include/nav_bar.php');
    $chat_id = $_REQUEST['chat_id'];

    # -- MY CHANGE --
    $id = $_SESSION['id'];
    $result = db_query("
        SELECT user_id
        FROM `chat_members`
        WHERE chat_id='$chat_id'
        AND user_id='$id'
    ");
    if(mysqli_num_rows($result) == 0){
        db_query("
            INSERT INTO `chat_members` (chat_id, user_id, is_admin)
            VALUES ('$chat_id', '$id', 0)
        ");
    }
    # ---------------
    
    $_SESSION['chat_id'] = add_slash($chat_id);
    $result = db_query("
        SELECT chat_title
        FROM `chats`
        WHERE chat_id='$chat_id'
    ");
    $chat_title = mysqli_fetch_row($result)[0];

    echo("<h1 style='text-align:center;color:white;font-size:50px;'>".add_html_chars($chat_title)."</h1>");
    echo("<h2 style='text-align:center;color:white;'>Members</h2>");
    include_once('../include/members_box.php');
    echo("<h2 style='text-align:center;color:white;'>Messages</h2>");
    include_once('../include/chatbox.php');
?>
    </body>
</html>
