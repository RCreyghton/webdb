<?php
/*
-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
FILE: index.php
AUTHORS: W. Kaper
DATE: januari 2007
STATE: production

Main menu for peer-assessment app.

Check if 'aangemeld', otherwise: password needs to be changed.
Otherwise: show the menu
*/

include 'inc/inc.php';
if (! $_SESSION['aangemeld']) {
    //1e keer verplicht wachtwoord wijzigen
    header('Location: '.URLROOT.'/changepassw.php?error=0');
    exit();
}
?><?php echo '<?xml version="1.0"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <title><?php echo APPNAME; ?></title>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<p>Je bent ingelogd als: <?php echo $_SESSION['firstname'].' '.$_SESSION['lastname']; ?></p>
<p><?php echo (($_SESSION['klaar'])? 
    ('Je hebt je teamgenoten beoordeeld'): 
    ('Je hebt je teamgenoten nog niet beoordeeld')); ?></p>
<h1><?php echo APPNAME; ?> - Menu</h1>
<p><a href="assessing.php">Beoordelingen door mij van teamgenoten</a></p>
<p><a href="assessed.php">Beoordelingen van mij, door teamgenoten en docenten</a></p>
<p><a href="changepassw.php">Wachtwoord wijzigen</a></p>
<?php if ($_GET['msg']=='passwordchanged') { ?>
    <p class="msg">Je wachtwoord is gewijzigd!</p>
<?php } ?>
</body>
</html>
