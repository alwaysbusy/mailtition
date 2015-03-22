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
    if(!$prelogin || !array_key_exists('id',$_POST)){
        echo 'FAIL';
    } else {
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname, $dbport);
        $sql = "SELECT * FROM mailtition WHERE id=" . $_POST['id'];
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $letterformat = json_decode($row['letter']);
        $lettertemplate = json_decode(file_get_contents('../templates/letter.json'));
        
        $firstto = true;
        foreach($lettertemplate->to as $to){        
            $letterhtml = '<html><body>';
            foreach($letterformat->customisations as $section){
                switch ($lettertemplate->sections[$section->section]->type) {
                    case 'title':
                        $letterhtml .= '<p><strong>';
                        break;
                    case 'disclaimer':
                        $letterhtml .= '<p style="font-size: 0.6em">';
                        break;
                    default:
                        $letterhtml .= '<p>';
                }

                $sectionvalue = '';
                if ($section->value == '') {
                    $sectionvalue = $lettertemplate->sections[$section->section]->value;
                } else {
                    $sectionvalue = $section->value;
                }
                //Replace Tags
                if ($lettertemplate->sections[$section->section]->type == 'introduction') {
                    $sectionvalue = str_replace('||title||', $to->title, $sectionvalue);
                    $sectionvalue = str_replace('||firstname||', $to->firstname, $sectionvalue);
                    $sectionvalue = str_replace('||lastname||', $to->lastname, $sectionvalue);
                    $sectionvalue = str_replace('||postnom||', $to->postnom, $sectionvalue);
                } else if ($lettertemplate->sections[$section->section]->type == 'close') {
                    $sectionvalue = str_replace('||firstname||', $row['firstname'], $sectionvalue);
                    $sectionvalue = str_replace('||lastname||', $row['lastname'], $sectionvalue);
                }
                $letterhtml .= $sectionvalue;
                
                if ($lettertemplate->sections[$section->section]->type == 'title') {
                    $letterhtml .= '</strong>';
                }
                $letterhtml .= '</p>';
            }
            $letterhtml .= '</body></html>';
            
            date_default_timezone_set('Europe/London');
            $headers = "From: " . $row['firstname'] . ' ' . $row['lastname'] . " <" .  $row['email'] . ">\r\n";
            if ($firstto) {
                //Include CC and BCCs
                $cc = array();
                foreach ($lettertemplate->cc as $ccentry) {
                    $cc[] = $ccentry->firstname . ' ' . $ccentry->lastname . ' <' . $ccentry->email . '>';
                }
                if (count($cc) > 0){
                    $headers .= "Cc: " . implode(', ', $cc) . "\r\n";
                }
                $bcc = array($row['firstname'] . ' ' . $row['lastname'] . ' <' . $row['email'] . '>');
                foreach ($lettertemplate->bcc as $bccentry) {
                    $bcc[] = $bccentry->firstname . ' ' . $bccentry->lastname . ' <' . $bccentry->email . '>';
                }
                $headers .= "Bcc: " . implode(', ', $bcc) . "\r\n";
            }
            $headers .= "Sender: " . $row['firstname'] . ' ' . $row['lastname'] . " <" . $row['email'] . ">\r\n";
            $headers .= "X-Mailer: " . split('@', $orgemail)[1] . "\r\n";
            $headers .= "Reply-To: " . $row['email'] . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $toaddress = $to->firstname . ' ' . $to->lastname . ' <' . $to->email . '>';
            mail($toaddress, $lettertemplate->subject, $letterhtml, $headers);
            
            echo 'Message sent from ' . $row['firstname'] . ' ' . $row['lastname'] . ' to ' . $toaddress . "\r\n";
            
            $firstto = false;
        }
        
        $sql = "UPDATE mailtition SET status=3 WHERE id=" . $_POST['id'];
        $conn->query($sql);
        $conn->close();
    }
?>