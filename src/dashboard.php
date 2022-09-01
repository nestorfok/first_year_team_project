<?php
    include_once('../include/auth_session.php');
    require_once('../include/db.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Main page</title>
        <link href='../style/extra.css' type='text/css' rel='stylesheet'>
        <link href='../style/style.css' type='text/css' rel='stylesheet'>
    </head>
    <body>
<?php
    include_once('../include/nav_bar.php');
    $id = add_slash($_SESSION["id"]);
    // find all the chats the user belongs to
    $result = db_query("
        SELECT chats.chat_id, chats.chat_title
        FROM `chat_members`, `chats`
        WHERE chat_members.user_id='$id'
        AND chat_members.chat_id=chats.chat_id
    ");
    $rows = convert_to_assoc($result);
    // find all question ads
    $result22 = db_query("
        SELECT ads.ad_id, ads.chat_id, ads.ad_title, ads.ad_content, ads.ad_category, ads.ad_timestamp, users.username, users.id, users.first_name, users.last_name, users.profile_pic
        FROM `ads`, `users`
        WHERE ads.chat_id IS NULL
        AND ads.user_id = users.id
        ORDER BY ads.ad_timestamp DESC");
    $rows22 = convert_to_assoc($result22);

    //find all chat ads
    $result33 = db_query("
        SELECT ads.ad_id, ads.chat_id, ads.ad_title, ads.ad_content, ads.ad_category, ads.ad_timestamp, users.username, users.id, users.first_name, users.last_name, users.profile_pic
        FROM `ads`, `users`
        WHERE ads.chat_id IS NOT NULL
        AND ads.user_id = users.id
        ORDER BY ads.ad_timestamp DESC");
    $rows33 = convert_to_assoc($result33);

    if (isset($_POST['category'])) {
        $category = $_POST['category'];
        // find all question ads
        $result22 = db_query("
            SELECT ads.ad_id, ads.chat_id, ads.ad_title, ads.ad_content, ads.ad_category, ads.ad_timestamp, users.username, users.id, users.first_name, users.last_name, users.profile_pic
            FROM `ads`, `users`
            WHERE ads.chat_id IS NULL
            AND ads.user_id = users.id
            AND ads.ad_category = '$category'
            ORDER BY ads.ad_timestamp DESC");
        $rows22 = convert_to_assoc($result22);
        //find all chat ads
        $result33 = db_query("
            SELECT ads.ad_id, ads.chat_id, ads.ad_title, ads.ad_content, ads.ad_category, ads.ad_timestamp, users.username, users.id, users.first_name, users.last_name, users.profile_pic
            FROM `ads`, `users`
            WHERE ads.chat_id IS NOT NULL
            AND ads.user_id = users.id
            AND ads.ad_category = '$category'
            ORDER BY ads.ad_timestamp DESC");
        $rows33 = convert_to_assoc($result33);
    }
?>
    <section>
        <main>
            <section class="sidebar">
                <h1 style="text-align: center; font-family: 'Montserrat', sans-serif; font-weight: bold; color: white; font-size: 170%;">Filters</h1>
                <form class="categories" method="post" style="display: flex; flex-direction: column;">
                    <input style="margin-top: 30px;" type="radio" id="cs" name="category" value="Computer Science">
                    <label for="cs">Computer Science</label>
                    <input style="margin-top: 30px;" type="radio" id="med" name="category" value="Medicine">
                    <label for="med">Medicine</label>
                    <input style="margin-top: 30px;" type="radio" id="Biochemistry" name="category" value="Biochemistry">
                    <label for="Biochemistry">Biochemistry</label>
                    <input style="margin-top: 30px;" type="radio" id="Maths" name="category" value="Maths">
                    <label for="Maths">Maths</label>
                    <input style="margin-top: 30px;" type="radio" id="English" name="category" value="english">
                    <label for="English">English</label>
                    <input style="margin-top: 30px;" type="radio" id="Languages" name="category" value="Languages">
                    <label for="Languages">Languages</label>
                    <input style="margin-top: 30px;" type="radio" id="Business" name="category" value="Business">
                    <label for="Business">Business</label><br><br>
                    <input type="radio" id="Other" name="category" value="Other">
                    <label for="Other">Other</label><br><br>
                    <input style="margin-top: 30px; margin-left: 120px;" type="submit" name="submit" value="Search" class="btn">
                </form>
            </section>
            <section class="tab--container">
                <div id="tab_nav" class="tab-header  jc-between">
                    <div class="tabl-click"><a href="javascript:void(0)">Chats</a></div>
                    <div><a href="javascript:void(0)">Questions</a></div>
                </div>
                <div class="context">
                    <div class="tab_child_context show">
                        <?php 
                            if(count($rows22) == 0){
                                echo("<p style='font-size: 50px; text-align: center;'>There are no group chats in this category yet.</p>");
                            }else{
                            foreach($rows33 as $row){
                            $account_id = $row['id'];
                            $result = db_query("SELECT * FROM `users` WHERE id = $account_id");
                            $result = mysqli_fetch_array($result);
                                echo("
                                    <div class='ad' id='".$row['ad_id']."' style='height:auto;'>");
                                echo '<img style="border-radius: 45%; width: 90px; height: 90px;" src="data:image/jpeg;base64,'.base64_encode( $result['profile_pic'] ).'"/>';
                                echo("<form style='width: 50px;' action='../src/view_user.php' method='post'>
                                        <input type='number' style='display:none' name='id' value='".$row['id']."'>
                                        <input style='font-size: 25px;background: none!important;border: none;'    class='user_button' type='submit' value='".add_html_chars($row['first_name'])." ".add_html_chars($row['last_name'])."'>
                                      </form>
                                      <h1 style='font-size: 25px;'>has posted:</h1>");
                                echo("
                                        <div class='title'>
                                            <p class='title_text'>".add_html_chars($row['ad_title'])."</p>
                                        </div>
                                        <p class='ad_content'>".add_html_chars($row['ad_content'])."</p>
                                         <p class='ad_info'>on ".add_html_chars($row['ad_timestamp'])." | Category: ".add_html_chars($row['ad_category'])."</p>
                                        <form action='../src/comments.php' method='post'>
                                        <button class='btn' id='adv_id' name='adv_id' type='submit' value='".$row['ad_id']."'>Comments</button>
                                        </form>");
                                # -- MY CHANGE --
                            $chat_id = $row['chat_id'];
                            
                                echo("
                                    <form action='../src/chat.php' method='post'>
                                        <input type='number' style='display:none;' name='chat_id' value='$chat_id' />
                                        <button class='btn' type='submit'>Join chat</button>
                                    </form>
                                ");
                            # ---------------
                                echo("</div>");
                            }
                        }
                        ?>
                    </div>
                    <div class="tab_child_context hiden">
                        <?php
                            if(count($rows33) == 0){
                                echo("<p style='font-size: 50px; text-align: center;'>There are no questions in this category yet.</p>");
                            }else{
                            foreach($rows22 as $row){
                            $account_id = $row['id'];
                            $result = db_query("SELECT * FROM `users` WHERE id = $account_id");
                            $result = mysqli_fetch_array($result);
                                echo("
                                    <div class='ad' id='".$row['ad_id']."' style='height:auto;'>");
                                echo '<img style="border-radius: 45%; width: 90px; height: 90px;" src="data:image/jpeg;base64,'.base64_encode( $result['profile_pic'] ).'"/>';
                                echo("<form style='width: 50px;' action='../src/view_user.php' method='post'>
                                        <input type='number' style='display:none' name='id' value='".$row['id']."'>
                                        <input style='font-size: 25px;background: none!important;border: none;padding: 0!important;'    class='user_button' type='submit' value='".add_html_chars($row['first_name'])." ".add_html_chars($row['last_name'])."'>
                                      </form>
                                      <h1 style='font-size: 25px;'>has posted:</h1>");
                                echo("
                                        <div class='title'>
                                            <p class='title_text'>".add_html_chars($row['ad_title'])."</p>
                                        </div>
                                        <p class='ad_content'>".add_html_chars($row['ad_content'])."</p>
                                         <p class='ad_info'>on ".add_html_chars($row['ad_timestamp'])." | Category: ".add_html_chars($row['ad_category'])."</p>
                                        <form action='../src/comments.php' method='post'>
                                        <button style='left: 47.1%; width: 100%;' class='btn' id='adv_id' name='adv_id' type='submit' value='".$row['ad_id']."'>Comments</button>
                                        </form>
                                    </div>
                                ");
                            }
                        }
                        ?>
                    </div>
                </div>
            </section>
            <section class="right-container ">
                <!-- whatever the right sidebar is supposed to contain -->
                <div class="popular" style="padding: 0px;">
                    <div class="title">
                        <h1 style="padding-left: 24.5%; font-size: 150%; margin-bottom: 40px;">My Groups</h1>
                    </div>
                    <div style="margin-left: 10%;">
                    <?php
                        foreach($rows as $row){
                            echo("
                                <div style='margin-top: 5%;'>
                                <form style='datalist d4' action='../src/chat.php'>
                                <input style='display:none' type='number' name='chat_id' value='".$row['chat_id']."'>
                                <input type='submit' value='".add_html_chars($row['chat_title'])."'>
                                </form>
                                </div>
                            ");
                        }
                    ?>
                </div>
                </div>
            </section>
        </main>
    </section>
    <script type="text/javascript" src="tab.js"></script>
</body>
</html>
