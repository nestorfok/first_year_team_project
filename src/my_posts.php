<?php
    include_once('../include/auth_session.php');
    require_once('../include/db.php');
?>

<html>
    <head>
        <meta charset="utf-8">
        <title>My posts</title>
        <link rel='stylesheet' href='../style/style.css'>
        <script> 
            function del(ad_id){
                var confirmed = confirm("Are You sure You want to delete the post?");
                if(confirmed){
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function(){
                        if(this.readyState == 4 && this.status == 200){
                            document.getElementById(ad_id).style.display = 'none';
                        }
                    };
                    xmlhttp.open('POST', '../include/delete_post.php?ad_id='+ad_id, true);
                    xmlhttp.send();
                }
                else{
                    location.replace('../src/my_posts.php');
                }
            }
        </script>
    </head>
    <body>
<?php
    include_once('../include/nav_bar.php');
    // find all the ads created by the user and create an associative array of them
    $id = add_slash($_SESSION["id"]);
    $result = db_query("SELECT * FROM `ads` WHERE user_id='$id' ORDER BY ad_timestamp DESC");
    $rows = convert_to_assoc($result);

    echo("<h1 class='my_posts_title'>My posts</h1>");
    foreach($rows as $row){
        $result = db_query("SELECT * FROM `users` WHERE id = $id");
        $result = mysqli_fetch_array($result);
        echo("
            <div style='margin-left: 600px;' class='ad' id='".$row['ad_id']."'>");
        echo '<img style="width: 70px; height: 70px; border-radius: 50%; margin-top: 10px;" src="data:image/jpeg;base64,'.base64_encode( $result['profile_pic'] ).'"/>';
        echo("<h1 style='float: right; margin-right: 350px; margin-top: 30px;'>".$result['first_name']." ".$result['last_name']. " has posted:</h1>");
        echo("
                <div class='title'>
                    <p class='title_text'>".add_html_chars($row['ad_title'])."</p>");
        echo("          <div class='dropdown'>
                        <button style='float: right;' class='dropdown_button dropdown_button1'>Options</button>
                        <div class='dropdown_content'>
                            <form action='../src/edit_post.php' method='post'>
                                <button style='float: right;' class='dropdown_button dropdown_button2' type='submit' name='ad_id' value='".$row['ad_id']."'>Edit</button>
                            </form>
                                <button style='float: right;' class='dropdown_button dropdown_button2' onclick='del(".$row['ad_id'].")'>Delete</button>
                        </div>
                    </div>
                </div>
                <p class='ad_content'>".add_html_chars($row['ad_content'])."</p>
                <p class='ad_info'>Published on ".add_html_chars($row['ad_timestamp'])." | Category: ".add_html_chars($row['ad_category'])."</p>");
        # -- MY CHANGE --
            $chat_id = $row['chat_id'];
            if(!empty($chat_id)){
                echo("<div style='display: flex;'>
                <form action='../src/comments.php' method='post'>
                    <button style='margin-left: 70px; width: 340px;' class='btn' id='adv_id' name='adv_id' type='submit' value='".$row['ad_id']."'>Comments</button>
                </form>
                    <form action='../src/chat.php' method='post'>
                        <input type='number' style='display:none;' name='chat_id' value='$chat_id' />
                        <button style='width: 340px;' class='btn' type='submit'>Join chat</button>
                    </form>
                    </div>
                ");
            }
            else{
                echo("<div style='display: flex; margin-left: 150px;'>
                <form action='../src/comments.php' method='post'>
                    <button style='width: 700px;' class='btn' id='adv_id' name='adv_id' type='submit' value='".$row['ad_id']."'>Comments</button>
                    </form>
                    </div>");
            }
            #----------------
            echo("</div>");
    }
?> 
    </body>
<html>
