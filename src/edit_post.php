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
    $id = $_SESSION["id"];
    if (isset($_REQUEST['new_title'])){
        $ad_id = $_REQUEST['ad_id'];
        $title = stripslashes($_REQUEST['new_title']);
        $content = stripslashes($_REQUEST['new_content']);
        db_query("
            UPDATE `ads`
            SET ad_title='$title', ad_content='$content'
            WHERE user_id='$id' AND ad_id='$ad_id'
        ");
        header('Location: my_posts.php');
    }
    else if(!isset($_REQUEST['ad_id'])){
        header('Location: dashboard.php');
    }
    else{
        $ad_id = $_REQUEST['ad_id'];
        $sql = db_query("
            SELECT ad_id, ad_title, ad_content
            FROM `ads`
            WHERE ad_id='$ad_id'
        ");
        $rows = convert_to_assoc($sql);
?>
    <div class="container-contact100">
        <div class="wrap-contact100">
            <h1 class="contact100-form-title">Edit a post</h1>
                <script>
                    var text = <?php echo (json_encode($rows)); ?>;
                    document.write(`

                                <form class="contact100-form validate-form" action="" method="post">
                                    
                                    <input  type='number' name='ad_id' style='display:none' value='${text[0].ad_id}'>
                                    <div class='wrap-input100 validate-input'>
                                        <span class="label-input100">Title</span>
                                        <input type="text" name="new_title" class='input100' value="${text[0].ad_title}"> 
                                    </div>
                                    <div class='wrap-input100 validate-input'>
                                        <span class="label-input100">Content</span>
                                        <textarea type="text" name="new_content" class='input100'>${text[0].ad_content}</textarea>
                                    </div>
                                    <input type="submit" name="submit" value="Save" class="button btn">
                                </form>
                       
                    `);
                </script>
            </form>     
        </div>
    </div>
<?php
    }
?>
    </body>
</html>
