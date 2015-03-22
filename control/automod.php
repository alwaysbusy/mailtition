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
    if(!$prelogin || !array_key_exists('profanity',$_POST) || !array_key_exists('blacklist',$_POST) || !array_key_exists('approval',$_POST)){
        echo 'FAIL';
    } else {
        //If approval on then build list
        $approval = $_POST['approval'];
        if ($approval) {
            echo '<table><thead><tr><th>Remove?</th><th>ID</th><th>Person</th><th>Options</th></tr></thead><tbody>';
        }
        
        //Build removal dictionary
        $profanity = array('anal','anus','ass','bastard','bitch','boob','cock','cum','cunt','dick','dildo','dyke','fag','faggot','fuck','fuk','handjob','homo','jizz','kike','kunt','muff','nigger','penis','piss','poop','pussy','queer','rape','semen','sex','shit','slut','titties','twat','vagina','vulva','wank','analplug','analsex','arse','assassin','balls','bimbo','bloody','bloodyhell','blowjob','bollocks','boner','boobies','boobs','bugger','bukkake','bullshit','chink','clit','clitoris','cocksucker','coon','crap','cumshot','dickhead','doggystyle','f0ck','fags','fanny','fck','fcker','fckr','fcku','fcuk','fucker','fuckface','fuckr','fuct','glory hole','gloryhole','gobshite','godammet','godammit','goddammet','goddammit','goddamn','gypo','hitler','hooker','hore','horny','jizzum','kaffir','lesbo','masturbate','milf','molest','motherfuck','mthrfckr','nazi','negro','nigga','niggah','paedo','paedophile','paki','pecker','pedo','pedofile','pedophile','phuk','pimp','poof','porn','prick','pron','prostitute','raped','rapes','rapist','schlong','shag','shite','slag','spastic','spaz','spunk','stripper','tits','tittyfuck','tosser','turd','vibrator','wanker','wetback','whor','whore','wog','wtf','xxx');
        
        if ($_POST['blacklist'] != '') {
            $blacklist = explode('\n',filter_var($_POST['blacklist'], FILTER_SANITIZE_STRING));
            $profanity = array_merge($profanity, $blacklist);
        }
        $regex = '/(';
        foreach($profanity as $word){
            $regex .= $word . '|';
        }
        $regex = str_replace('|)',')', $regex . ')/');
        
        //Retrieve all status 1 from database
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname, $dbport);
        $sql = "SELECT * FROM mailtition WHERE status=1";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            //Test if offensive words in letter values, names or email
            if (preg_match($regex, $row['firstname']) || preg_match($regex, $row['lastname']) || preg_match($regex, $row['email']) || preg_match($regex, $row['letter'])) {
                //Perform correct action (mod/review)
                if ($approval) {
                    echo '<tr><td><input type="checkbox" id="' . $row['id'] . '-mod" checked /></td><td>' . $row['id'] . '</td><td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td><td><a title="View letter" onclick="loadWindowedContent(\'preview\',\'id=' . $row['id'] . '\')">View Letter</a></td></tr>';
                } else {
                    $sql = "UPDATE mailtition SET status=5 WHERE id=" . $row['id'];
                    $conn->query($sql);
                }
            } else {
                //Item approved - Set status to 2
                $sql = "UPDATE mailtition SET status=2 WHERE id=" . $row['id'];
                $conn->query($sql);
            }
        }
        
        if ($approval) {
            echo '</tbody></table><p><button type="button" name="submit" onclick="removeModerated();">Remove Selected</button></p>';
        } else {
            echo '<p>All letters contravening policy have been removed</p>';
        }
        
        $conn->close();
    }
?>