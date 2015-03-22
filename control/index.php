<?php
    require_once('../templates/config.php');

    $login = false;
    if (array_key_exists('key',$_POST)) {
        if (base64_decode($_POST['key']) == $secretkey) {
            $login = true;
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Mailtition Control</title>
        <style type="text/css">
            html, body{
                width: 100%;
                height: 100%;
                margin: 0;
                font-family: Arial, Helvetica, sans-serif;
            }
            .contain{
                margin-left: auto;
                margin-right: auto;
                max-width: 1008px;
            }
            
            a{
                cursor: pointer;
                text-decoration: underline;
            }
            
            header{
                margin-top: 8px;
                margin-bottom: 8px;
            }
            header ul{
                margin: 0px 0px 0px 0px;
                padding: 0px 0px 0px 0px;
            }
            header ul li{
                display: inline-block;
                margin-right: 10px;
                list-style-type: none;
            }
            
            .loginform{
                text-align: center;
            }
            .loginform label{
                padding-right: 16px;
            }
            
            table{
                border-collapse: collapse;
            }
            
            th, td{
                border: 1px solid #000000;
            }
            
            td a{
                margin-right: 10px;
            }
            td a:last-of-type{
                margin-right: 0px;
            }
            
            .title{
                font-weight: bold;
            }
            .disclaimer{
                font-size: 0.6em;
            }
        </style>
        <?php if (!$login){ ?>
        <script type="text/javascript">
            function encodeKey(){
                window.document.getElementById('key').value = btoa(btoa(window.document.getElementById('key').value));
            }
        </script>
        <?php } else { ?>
        <script type="text/javascript">
            var secretkey = '<?php echo base64_encode($secretkey); ?>';
            
            function ajaxRequest() {
                if (window.XMLHttpRequest) {
                    return new XMLHttpRequest();
                } else {
                    return new ActiveXObject('Microsoft.XMLHTTP');
                }
            }
            
            function contentReq(windowtype, parameters){
                var ajax = ajaxRequest();
                ajax.open('POST',windowtype + '.php', false);
                ajax.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                var postData = 'key=' + encodeURIComponent(secretkey);
                if (parameters) {
                    postData += '&' + parameters;
                }
                ajax.send(postData);
                return ajax.responseText;
            }
            
            function loadContent(windowtype, parameters) {
                var content = contentReq(windowtype, parameters);
                if(content != 'FAIL'){
                    window.document.getElementById('main').innerHTML = content;
                    //window.setTimeout('pageLoaded();',100);
                } else {
                    alert('Your request could not be completed');
                }
            }
            
            function loadWindowedContent(windowtype, parameters) {
                var content = contentReq(windowtype, parameters);
                if(content != 'FAIL'){
                    var windowHtml = '<!DOCTYPE html><html><head><title>Mailtition</title><style type="text/css">body{font-family: Arial, Helvetica, sans-serif;}.title{font-weight: bold;}.disclaimer{font-size: 0.6em;}</style></head><body>' + content + '</body></html>';
                    var newwin = window.open('about:blank','_blank','height=800, width=500, menubar=0, status=0, toolbar0');
                    newwin.document.write(windowHtml);
                } else {
                    alert('Your request could not be completed');
                }
            }
            
            function setBreadcrumb(num, content) {
                window.document.getElementById('breadcrumb' + num).innerHTML = content;
            }
            
            function deleteLetter(id) {
                if (confirm("Letter " + id + " will now be deleted.")) {
                    loadContent('delete','id=' + id);
                }
            }
            
            function removeModerated() {
                var ids = "";
                for (var i = 0; i < window.document.getElementsByTagName("input").length; i++) {
                    if (window.document.getElementsByTagName("input")[i].checked) {
                        ids += window.document.getElementsByTagName("input")[i].id.split("-")[0] + ",";
                    }
                }
                if (ids.length > 0) {
                    ids = ids.substring(0, ids.length - 1);
                }
                loadContent("manmod","ids=" + ids);
            }
        </script>
        <?php } ?>
    </head>
    <body>
        <header class="contain"><ul>
            <li><h1><a title="Mailtition Control" onclick="loadContent('main',null);">Mailtition Control</a></h1></li>
            <li id="breadcrumb1"></li>
            <li id="breadcrumb2"></li>
        </ul></header>
        <?php
            if (!$login) {
                //Show login form
                echo '<div class="loginform contain"><form name="login" method="post" onsubmit="encodeKey();"><label for="key">Secret Key:</label><input type="password" name="key" id="key" /><br /><button type="submit" name="login">Log-in</button></form></div>';
            } else {
                echo '<div id="main" class="contain"></div><script type="application/javascript">loadContent("main",null);</script>';
            }
        ?>
        <footer class="contain">
            <!-- TODO: Use final licence -->
            <p>&copy; Owen Hurford 2015</p>
        </footer>
    </body>
</html>