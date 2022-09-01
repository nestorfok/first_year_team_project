<?php
    require_once('../include/db.php');
    include_once('../include/auth_session.php');
?>

<html>
    <head>
        <meta charset="utf-8">
        <title>My posts</title>
        <link rel='stylesheet' href='../style/style.css'>
    </head>
    <body>


<?php
    if(!isset($_POST['adv_id']))
        header('Location: ../src/dashboard.php');

    include_once('../include/nav_bar.php');
    $adv_id = add_slash($_POST['adv_id']);
    $user_id = $_SESSION["id"];

    // take the advertisement information from the database
    $result = db_query("SELECT ad_content,ad_title FROM `ads` WHERE ad_id='$adv_id'");
    $rows = mysqli_fetch_array($result);
    echo("

      
        <div class='container11'>
        <p class='commenttitle'>".add_html_chars($rows[1])."</p>
        <div class='wrap-contact11'>
        <div class='wrap-input101 validate-input'>
        
        </div>
        <div class='wrap-input101 validate-input'>
        <p class='commenttext'>".add_html_chars($rows[0])."</p>
        </div>
        </div><div class='wrap-input101 validate-input' style='margin-bottom: 0px;'></div>");
        

    echo("<div class='wrap-input101 validate-input'style='margin-top: 0px;
    margin-bottom: 0px;'
    >
        <div >
         <p class='commenttitle'style='
        margin-top: 0px; margin-bottom: 0px;'>Comments</p>
        </div></div>

        ");
    // find all the comments made for this specific post and create an associative array of them
    $result = db_query("SELECT * FROM `comments` WHERE ad_id='$adv_id' ORDER BY comment_timestamp DESC");
    if(mysqli_num_rows($result) == 0){
        echo("There are no comments yet.");
    } else {
        $rows = convert_to_assoc($result);
    }
?>
<div class='wrap-wrap'>
    <script>
        function testResults (form, advertisement_id) {
            var comment_content = form.comment_content.value;
            var user_id = <?php echo json_encode($user_id); ?>;
            var ad_id = advertisement_id;
            var xhttp;
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    location.reload();
                }
            };
            xhttp.open("GET", "../include/writeCommentAJAX.php?user_id="+user_id+"&ad_id="+ad_id+"&comment_content="+comment_content, false);
            xhttp.send();
    }   




        // list all the posts
        var text = <?php echo json_encode($rows); ?>;
        //Making AJAX requests to take the full name of the person who commented
        var user_name_full;
        var xhttp;
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                user_name_full = this.responseText;
            }
        };
        //Outputting every comment
        for(var i = 0; i < text.length; i++){
            xhttp.open("GET", "../include/getFullUserNameAJAX.php?q="+text[i].user_id, false);
            xhttp.send();
            document.write(`
            
               <div class='wrap-contact12' style='margin-left: 25px;''>
                    <p class='commentname'>${user_name_full} commented:</p>
                    <p class='commenttext'>${text[i].comm_content}</p>
                    <p class='commentname'>Commented on ${text[i].comment_timestamp}</p>
                </div>
           
            `);
        }
        var ad_id = <?php echo json_encode($adv_id); ?>;
        document.write("<form class='wrap-contact13' method='GET' name='write_a_comment'><h1>Write your comment here:</h1><textarea type='text' class='input11' style='margin: 0px;height:200; width: 700px;' name='comment_content' placeholder='...'/></textarea><br><input class='kash' type='button' style = 'margin-top:10px;'value='Post' onClick='testResults(this.form, ad_id)'></form>");
    </script> 
</div>
    </div>
    </body>
<html>
