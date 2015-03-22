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
    if(!$prelogin || !array_key_exists('id', $_POST)) {
        echo 'FAIL';
    } else {
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname, $dbport);
        $sql = "UPDATE mailtition SET status=6 WHERE id=" . $_POST['id'];
        if ($conn->query($sql) === true){
            echo '<p>Record ' . $_POST['id'] . ' Deleted</p>';
        } else {
            echo '<p>Record Not Deleted</p>';
        }
        
        $conn->close();
    }
?>