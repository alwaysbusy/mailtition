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
        $sql = "SELECT * FROM mailtition WHERE id=" . $_POST['id'];
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $letterformat = json_decode($row['letter']);
        $lettertemplate = json_decode(file_get_contents('../templates/letter.json'));
        foreach($letterformat->customisations as $section){
            switch ($lettertemplate->sections[$section->section]->type) {
                case 'title':
                    echo '<p class="title">';
                    break;
                case 'disclaimer':
                    echo '<p class="disclaimer">';
                    break;
                default:
                    echo '<p>';
            }
            
            if ($section->value == '') {
                echo $lettertemplate->sections[$section->section]->value;
            } else {
                echo $section->value;
            }
            echo '</p>';
        }
        
        $conn->close();
    }
?>