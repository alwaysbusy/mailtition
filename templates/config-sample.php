<?php
//Database configuration
$dbhost = ''; //Database Host

$dbport = null; //Databse Host port number (Default if null)

$dbuser = ''; //Database Username (Must have full rights over database $dbname)

$dbpass = ''; //Databse user password

$dbname = ''; //Database Name


//Organisation configuration
$orgname = 'Sample'; //Organisation Name


//Configuration for the letter to be sent
$lettertitle = 'Test'; //Title to refer to letter when being seen by user

$metatags = ''; //HTML Meta Tags for use on search engines.  If giving a social card then store images in the templates folder.

$introduction = 'Testing'; //Introductory paragraph about the letter and why people should send it.  Can contain HTML.

$confirm = 'Yes'; //Message to show on final screen before the user commits to sending.

$sent = 'No!'; //Message to show after their message has been queued to be sent.

//Letter template is stored in letter-sample.json and is documented there

?>