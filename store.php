<?php
    require_once('templates/config.php');

    if ($dbport == null) {
        $dbport = 3306;
    }
    
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname, $dbport);
    
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);

    //Generate JSON string containing letter
    $letter = '{"customisations" : [';
    $i = 0;
    while(array_key_exists($i . '-inc', $_POST)){
        if($_POST[$i . '-inc'] == 'true'){
            if(substr($letter, -1) != '['){
                $letter .= ',';
            }
            $letter .= '{"section" : ' . $i . ', "value" : ';
            if(array_key_exists($i . '-val', $_POST)){
                $letter .= '"' . str_replace('"','\'', filter_var($_POST[$i . '-val'], FILTER_SANITIZE_STRING)) . '"';
            } else {
                $letter .= '""';
            }
            $letter .= '}';
        }
        $i++;
    }
    $letter .= ']}';

    $sql = "INSERT INTO mailtition (firstname, lastname, email, letter, status) VALUES ('" . $firstname . "','" . $lastname . "','" . $email . "','" . $letter . "',1)";

    if($conn->query($sql) === true) {
        echo 'QUEUE';
    } else {
        echo 'FAIL';
    }
?>