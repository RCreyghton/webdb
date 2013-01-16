<?php
/*
-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
FILE: inc.php
AUTHORS: W. Kaper
DATE: januari 2007
STATE: production

Master include file for peer-assessment app.
*/

//constants
define('APPNAME', 'Peer-Assessment');
define('WEBMASTER_EMAIL', 'kaper@science.uva.nl');
define('WEBMASTER_EMAIL_LINK', 
	'<a href="mailto:'.WEBMASTER_EMAIL.'">'.WEBMASTER_EMAIL.'</a>');
define('ROOT', '/home/kaper/public_php5/peerassess');
define('URLROOT', '/~kaper/peerassess');
define('LOGGEDINPASS', '12349876');

//alle pagina´s gebruiken sessies
session_start();

//login is required unless the including page overrules it
$loginrequired = (isset($loginrequired))? ($loginrequired) : (true);

//includes
include_once ROOT.'/inc/fatalerror.inc.php';
include_once ROOT.'/inc/mymysqli.class.php';
include_once ROOT.'/inc/login_check.php';        //this checks if user is logged-in
?>
