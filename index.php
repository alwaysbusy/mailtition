<?php

require_once('templates/config.php');

echo '<!DOCTYPE html><html><head><title>' . $lettertitle . ' - ' . $orgname . '</title><link rel="stylesheet" type="text/css" href="templates/style.css"><script type="application/javascript" src="templates/script.js"></script>' . $metatags;
if (file_exists('templates/custom.css')) {
    echo '<link rel="stylesheet" type="text/css" href="templates/custom.css">';
}
echo '</head><body><header>';
include('templates/header.php');
echo '</header>';

//Add Sections
echo '<form name="letter">';

echo '<article id="intro"><h2>' . $lettertitle . '</h2>' . $introduction . '<p>Press Start to begin</p></article>';
echo '<article id="personal"><h2>Your details</h2><p>Please provide some information about you</p><p><label for="firstname">First name</label><input type="text" name="firstname" /></p><p><label for="lastname">Last name</label><input type="text" name="lastname" /></p><p><label for="email">Email address</label><input type="email" name="email" /></p></article>';
echo '<article id="customise"><h2>Customise letter</h2><p>Please review and customise the letter we\'ll be sending on your behalf.  You can edit some areas of text, and remove bit\'s which you don\'t like, although we need to keep some of the core message so you won\'t be able to remove those bits.</p><p>Text inside bars will be replaced with the correct words when your message is sent.</p><table id="customise-table"><tr><th>Include</th></tr></table></article>';
echo '<article id="confirm"><h2>Almost there</h2><p id="confirm-preaction">' . $confirm . '</p><p>When your message is sent, you\'ll also receive a copy of it.  The representative you\'ve sent it to will be able to respond to you directly, and ' . $orgname . ' won\'t receive a copy.</p><p>Press Confirm to schedule you\'r message to be sent.</p></article>';
echo '<article id="sent"><h2>Your Message is Ready to go</h2><p>Great.  We\'ll send your message out along with everyone else\'s on our day of action.  Thanks for being a part of ' . $lettertitle . '!</p><p>' . $sent . '</p></article>';

echo '</form>';

//Add navigation bar
echo '<nav><ul><li id="nav-back" onclick="navBack();">Back</li><li id="nav-next" onclick="navNext();">Next</li></ul></nav>';

echo '<footer>';
include('templates/footer.php');
echo '</footer><script type="application/javascript">moveStage(0);</script></body></html>';

?>