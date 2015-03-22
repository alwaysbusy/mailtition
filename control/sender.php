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
    if(!$prelogin || !array_key_exists('status-1',$_POST) || !array_key_exists('status-2',$_POST) || !array_key_exists('num',$_POST) || !array_key_exists('frequency',$_POST)){
        echo 'FAIL';
    } else {
        echo '<!DOCTYPE html><html><head><title>Mailtition Sender</title><style type="text/css">body{font-family: Arial, Helvetica, sans-serif;}aside{position: fixed; bottom: 0; left: 0; width: 100%; text-align: center; background-color: rgba(255,255,255,0.8); height: 100px;}table{
        margin-bottom: 100px;}</style><script type="application/javascript">var secretkey = "' . base64_encode($secretkey) . '"; function ajaxRequest() {if (window.XMLHttpRequest) {return new XMLHttpRequest();} else {return new ActiveXObject("Microsoft.XMLHTTP");}} function sendMessage(id) {var ajax = ajaxRequest(); ajax.open("POST","sendmsg.php", false); ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded"); ajax.send("key=" + encodeURIComponent(secretkey) + "&id=" + id); if(ajax.responseText == "FAIL"){window.document.getElementById(id + "-status").innerHTML = "Failed";}else{window.document.getElementById(id + "-status").innerHTML = "Sent"; window.document.getElementById("totalsent").innerHTML = (parseInt(window.document.getElementById("totalsent").innerHTML) + 1);}} function startSending(idstring) {var ids = idstring.split(","); var currelem = 0; var num = ' . $_POST['num'] . '; var frequency = ' . $_POST['frequency'] . '*1000; while (currelem < ids.length) {var runstring = ""; for (var i = currelem; i < currelem + num; i++) {if(window.document.getElementById(ids[currelem] + "-status")) {runstring += "sendMessage(" + ids[currelem] + ");";}} window.setTimeout(runstring, frequency * (currelem/num)); currelem += num;} window.setTimeout("window.document.getElementById(\"status\").innerHTML = \"Complete\";", frequency * ((currelem-num)/num));}</script></head><body><table><thead><tr><th>ID</th><th>Status</th></tr></thead><tbody>';
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname, $dbport);
        $allowstatus = '';
        if ($_POST['status-1']) {
            $allowstatus = '1';
        }
        if ($_POST['status-2']) {
            if($allowstatus != '') {
                $allowstatus .= ',';
            }
            $allowstatus .= '2';
        }
        $sql = "SELECT * FROM mailtition WHERE status IN (" . $allowstatus . ")";
        $result = $conn->query($sql);
        $ids = array();
        while ($row = $result->fetch_assoc()) {
            echo '<tr><td>' . $row['id'] . '</td><td id="' . $row['id'] . '-status">Queued</td></tr>';
            $ids[] = $row['id'];
        }
        
        echo '</tbody></table><aside><p id="status">Sending - Do NOT close window</p><p><span id="totalsent">0</span>/' . count($ids) . '</p></aside><script type="application/javascript">startSending("' . implode(',', $ids) . '");</script></body></html>';
    }
?>