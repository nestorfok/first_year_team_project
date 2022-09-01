var last_msg_id = -1;

function load_new_stuff(){
    // update chat messages
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            document.getElementById('chatbox').innerHTML= document.getElementById('chatbox').innerHTML + this.responseText;
        }
    }
    xmlhttp.open('POST', '../include/msgs.php', true);
    xmlhttp.send();

    // update chat members
    var xmlhttp2 = new XMLHttpRequest();
    xmlhttp2.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            document.getElementById('members_box').innerHTML= document.getElementById('members_box').innerHTML + this.responseText;
        }
    }
    xmlhttp2.open('POST', '../include/chat_members.php', true);
    xmlhttp2.send();
}

function send_message(){
    var msg = document.getElementById("msg");
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            if(this.responseText != "")
                alert("Your message contains an inappropriate phrase: '"+this.responseText+"'");
            else{
                var obj = document.getElementById("chatbox");
                obj.scrollTop = obj.scrollHeight;
            }
        }
    }
    xmlhttp.open('POST', '../include/send_msg.php?msg='+msg.value, true);
    xmlhttp.send();
    msg.value = "";
}

function del_user(user_id, chat_id){
    var confirmed = confirm("Are You sure You want to delete the user from the chat?");
    if(confirmed){
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                document.getElementById(user_id).style.display = 'none';
            }
        }
        xmlhttp.open('POST', '../include/delete_user.php?user_id='+user_id+'&chat_id='+chat_id, true);
        xmlhttp.send();
    }
}

setInterval(function(){
    load_new_stuff();
}, 1000);
