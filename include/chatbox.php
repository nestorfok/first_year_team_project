<?php include_once('../include/auth_session.php'); ?>

<div id='chatbox' class='chatbox'>
<?php
    $_SESSION['last_msg_id'] = -1;
    include_once('../include/msgs.php');
?>
</div>
<div class="msg_input_box">
    <input type="text" id="msg" class="msg_input">
    <button id='msg_button' class="msg_button" onclick='send_message()'>Send</button>
</div>

<script>
    var input = document.getElementById("msg");
    input.addEventListener("keyup", event => {
        if(event.keyCode === 13) {
            event.preventDefault();
            document.getElementById("msg_button").click();
        }
    });
</script>
