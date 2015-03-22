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
    if(!$prelogin){
        echo 'FAIL';
    } else {
?>

<form name="sendout">
    <p><label for="status">Send with status</label>: <input type="checkbox" name="status-1" />Pending&nbsp;&nbsp;<input type="checkbox" name="status-2" />Ready</p>
    <p><label for="num">Send out</label> <input type="number" name="num" min="1" /> emails <label for="frequency">every</label> <input type="number" name="frequency" min="0" /> seconds</p>
    <p><button type="button" name="send" onclick="sendLetters();">Start Sending</button></p>
</form>

<?php
    }
?>