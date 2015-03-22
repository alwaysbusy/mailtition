<?php
    require_once('../templates/config.php');

    if ($dbport == null) {
        $dbport = 3306;
    }

    $prelogin = false;
    if (array_key_exists('key',$_POST)) {
        if (base64_decode($_POST['key']) == $secretkey) {
            $prelogin = true;
        }
    }
    if(!$prelogin || !array_key_exists('ids',$_POST) || !array_key_exists('approved',$_POST)){
        echo 'FAIL';
    } else {
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname, $dbport);
        if($_POST['ids'] != ''){
            $ids = explode(',', $_POST['ids']);
            foreach ($ids as $id) {
                $sql = "UPDATE mailtition SET status=5 WHERE id=" . $id;
                $conn->query($sql);
            }
        }
        if($_POST['approved'] != ''){
            $ids = explode(',', $_POST['approved']);
            foreach ($ids as $id) {
                $sql = "UPDATE mailtition SET status=2 WHERE id=" . $id;
                $conn->query($sql);
            }
        }
        
        
        $conn->close();
        echo '<p>All selected letters have been removed</p>';
    }
?>