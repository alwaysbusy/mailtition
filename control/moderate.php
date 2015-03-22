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

<form name="moderate">
    <p><label for="profanity">Profanity filter</label>: <input type="checkbox" name="profanity" checked value="true" /></p>
    <p><label for="blacklist">Backlist (one entry per line)</label>: <textarea name="blacklist"></textarea></p>
    <p><label for="approval">Allow review of auto-moderator decisions before removing</label>: <input type="checkbox" name="approval" value="true" /></p>
    <p><button type="button" name="submit" onclick="loadContent('automod','profanity=' + window.document.forms.moderate.profanity.checked + '&blacklist=' + encodeURIComponent(window.document.forms.moderate.blacklist.value) + '&approval=' + window.document.forms.moderate.approval.checked);">Moderate</button></p>
</form>

<?php
    }
?>