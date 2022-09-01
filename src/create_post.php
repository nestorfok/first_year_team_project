<?php
    include_once('../include/auth_session.php');
    require_once('../include/db.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Create a post</title>
        <link rel='stylesheet' href='../style/style.css'/>
    </head>
    <body>
<?php
    include_once('../include/nav_bar.php');
    $errorMsg = "";
    $title = "";
    $content = "";
    $category = "";
    $maxsize = "";
    if (isset($_REQUEST['title'])) {
        $title = stripslashes($_REQUEST['title']);
        $content = stripslashes($_REQUEST['content']);
        $category = stripslashes($_REQUEST['category']);
        $id = $_SESSION["id"];
        $type = $_REQUEST['type'];
        if(is_swear($title)){
            $errorMsg = "Your title contains an inappropriate phrase: ".get_swear($title);
        }
        else if(is_swear($content)){
            $errorMsg = "Your post contains an inappropriate phrase: ".get_swear($content);
        }
        else{
            if($type == "group") {
                $maxsize = add_slash($_REQUEST['maxsize']);
                db_query("
                    INSERT into `chats` (chat_title, max_members)
                    VALUES ('$title','$maxsize')
                ");
                $chatid = db_query("
                    SELECT LAST_INSERT_ID()
                ");
                $chat_id =  add_slash(mysqli_fetch_row($chatid)[0]);
                db_query("
                    INSERT into `ads` (user_id,chat_id, ad_title, ad_content, ad_category)
                    VALUES ('$id',$chat_id,'$title','$content','$category')
                ");
                db_query("
                    INSERT INTO `chat_members` (chat_id, user_id, is_admin)
                    VALUES('$chat_id', '$id', 1)
                ");
            }
            else{
                db_query("
                    INSERT into `ads` (user_id, ad_title, ad_content, ad_category)
                    VALUES ('$id','$title','$content','$category')
                ");
            }
            
            echo("
                <div class='form'>
                    <h3>You have successfully created a post.</h3><br/>
                    <p class='link'>Click here to <a href='../src/dashboard.php'>Main page</a></p>
                </div>
            ");
        }
    }else{
?>
    <script>

    function TypeCheck() {
        if (document.getElementById('clickedcheck').checked) {
            document.getElementById('ifchecked').style.display = 'block';
        }
        else document.getElementById('ifchecked').style.display = 'none';

    }

    </script>
    <div class='container-contact100'>
        <div class="wrap-contact">
            <form class="contact100-form validate-form" action="" method="post">
                <h1 class="login-title">Create a post</h1>
                <input type="text" class="login-input" name="title" placeholder="Title" 
                    <?php if(!empty($title)) echo("value='".add_html_chars($title)."'"); ?> required /><br>
                <textarea type="text" class="login-input" name="content" placeholder="Content"
                    <?php if(!empty($content)) echo("value='".add_html_chars($content)."'"); ?> required ></textarea><br>
                <div style="text-align:center; font-size: 18px;">
                    <input type="radio" name="type" value="Question" name= "question" onclick="TypeCheck();" name="yesno" id="noCheck" required>
                    <label>Ask a question</label>
                </div><br>
                <div style="text-align:center; font-size: 18px;">
                    <input type="radio" name="type" value="group" name="chat" id = "clickedcheck" onclick="TypeCheck()" required>
                    <label>Create a revision group</label><br>

                </div><br>
                <div id="ifchecked" style="display:none;text-align:center">
                    Max number of members : <input type='text' id='Maxnumber' name='maxsize'><br>
                </div><br>
                <form method="post">
                    <input type="radio" id="cs" name="category" value="Computer Science">
                    <label for="cs">Computer Science</label>
                    <input type="radio" id="med" name="category" value="Medicine">
                    <label for="med">Medicine</label>
                    <input type="radio" id="Biochemistry" name="category" value="Biochemistry">
                    <label for="Biochemistry">Biochemistry</label>
                    <input type="radio" id="Maths" name="category" value="Maths">
                    <label for="Maths">Maths</label>
                    <input type="radio" id="English" name="category" value="english">
                    <label for="English">English</label>
                    <input type="radio" id="Languages" name="category" value="Languages">
                    <label for="Languages">Languages</label>
                    <input type="radio" id="Business" name="category" value="Business">
                    <label for="Business">Business</label><br><br>
                    <input type="radio" id="Other" name="category" value="Other">
                    <label for="Other">Other</label><br><br>
                    <input type="submit" name="submit" value="Create" class="login-button">
                </form>
                <p style='color:red'><?php echo($errorMsg); ?></p>
            </form>
    </div>
    </div>
    </body>
</html>
<?php } ?>