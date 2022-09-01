<?php
    // Enter your host name, database username, password, and database name.
    // If you have not set database password on localhost then set empty.
    date_default_timezone_set("UTC");
    $con = mysqli_connect("dbhost.cs.man.ac.uk","x61715mm","revisify123","2020_comp10120_y15");
    // Check connection
    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $swear_words = array();
    create_list();

    function db_query($query){
        global $con;
        $result = mysqli_query($con, $query) or die(mysqli_error($con));
        return $result;
    }

    function convert_to_assoc($array){
        $rows = array();
        while($row = mysqli_fetch_assoc($array))
            $rows[] = $row;
        return $rows;
    }

    function add_slash($str){
        global $con;
        return mysqli_real_escape_string($con, $str);
    }

    function add_html_chars($str){
        return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5);
    }

    function create_list(){
        global $swear_words;
        $file = fopen("../resources/words.txt", "r");
        while(!feof($file)){
            $swear_words[] = trim(fgets($file));
        }
        fclose($file);
    }

    function is_swear($str){
        global $swear_words;
        for($i = 0; $i < count($swear_words)-1; $i++){
            if(preg_match("/\b".$swear_words[$i]."\b/i", $str)){
                return true;
            }
        }
        return false;
    }

    function get_swear($str){
        global $swear_words;
        for($i = 0; $i < count($swear_words)-1; $i++){
            if(preg_match("/\b".$swear_words[$i]."\b/i", $str)){
                return $swear_words[$i];
            }
        }
        return "";
    }

    $cipher = "AES-128-CTR";
    $iv_length = openssl_cipher_iv_length($cipher);
    $options = 0;
    $iv = "5869405968490124";
    $key = "WHATISGOINGONIDONTGETIT";
    function encrypt($str){
        global $cipher;
        global $iv_length;
        global $options;
        global $iv;
        global $key;
        return openssl_encrypt($str, $cipher, $key, $options, $iv);
    }

    function decrypt($str){
        global $cipher;
        global $iv_length;
        global $options;
        global $iv;
        global $key;
        return openssl_decrypt($str, $cipher, $key, $options, $iv);
    }
?>
