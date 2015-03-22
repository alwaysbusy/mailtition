<?php
    require_once('../templates/config.php');
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

<ul>
    <li><a title="View Submissions" onclick="loadContent('submissions',null);">View Submissions</a></li>
    <li><a title="Auto-moderate" onclick="loadContent('moderate',null);">Auto-moderate</a></li>
    <li><a title="Begin send-out" onclick="loadContent('sendout',null);">Begin send-out</a></li>
</ul>

<?php
    }
?>