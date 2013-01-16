<?php
/*
-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
FILE: changepassw.php
AUTHORS: W. Kaper
DATE: januari 2007
STATE: production

Form + script to change the password.

This time, form validation is done using javascript, as well as in php.
Validation in javascript is a courtesy to the user (it´s quicker), also it frees your
server from unnecessary work (running a script with wrong parameters).
However the essential checks MUST (also) be done in php.
*/

include 'inc/inc.php';
if (!isset($_POST['oldpassw']) || !isset($_POST['passw']) ) {
    //Nothing posted, so: present the form

    ?><?php echo '<?xml version="1.0"?>' ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <title><?php echo APPNAME ?> - Wachtwoord wijzigen</title>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
   <script type="text/javascript">
    function checkform() {
        message = '';
        if (document.getElementById('oldpassw').value=='') {
            message += 'Uw huidige wachtwoord is niet ingevuld.\n';
        }    
        if (document.getElementById('passw').value.length<6) {
            message += 'Uw nieuwe wachtoord is niet ingevuld, of het is kleiner dan 6 tekens.\n';
        }    
        if (document.getElementById('passw2').value=='') {
            message += 'Uw nieuwe wachtoord is niet voor de 2e keer ingevuld.\n';
        }    
        if (
            document.getElementById('passw').value!=
            document.getElementById('passw2').value
            ) {
            message += 'De twee versies van het nieuwe wachtwoord zijn ongelijk!\n';
        }
        if (message.length>0) {
            alert('Fout:\n'+message);
            return false;
        }
        else {
            return true;
        }    
    }
   </script>
</head>
<body>
<p>Je bent ingelogd als: <?php echo $_SESSION['firstname'].' '.$_SESSION['lastname']; ?></p>
<p><a href="index.php">Menu</a></p>
<h1><?php echo APPNAME ?> - Wachtwoord wijzigen</h1>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return checkform();">
<table>
    <tr><td>Huidig wachtwoord</td><td><input type="password" name="oldpassw" id="oldpassw" /></td></tr>
    <tr><td>Nieuw wachtwoord</td><td><input type="password" name="passw" id="passw" /></td></tr>
    <tr><td>Nieuw wachtwoord (2x)</td><td><input type="password" name="passw2" id="passw2" /></td></tr>
</table>
<input type="submit" value="login" />
</form>
<?php if ($_GET['error']==1) { ?>
    <p class="error">Je hebt met het formulier zitten donderjagen! Maakt me niet uit: je nieuwe wachtwoord is toch te kort.</p>
<?php } ?>
<?php if ($_GET['error']==2) { ?>
    <p class="error">Huidige wachtwoord niet correct opgegegeven, probeer het nog eens!</p>
<?php } ?>
<?php if ($_GET['error']==="0") { ?>
    <p>Je wordt verzocht een persoonlijk wachtwoord te kiezen, in plaats van het gegeven algemene wachtwoord.</p>
<?php } ?>
</body>
</html><?php
    
}
else {
    //Check posted values.
    $error = 0;
    if (strlen($_POST['passw'])<6) {
        //password too short, return to the form
        header('Location: '.$_SERVER['PHP_SELF'].'?error=1');
        exit();
    } 
    $mli = new mymysqli(TRUE);
    $stmt = $mli->sprepare(
        'SELECT collegekrt FROM claimwcollegekrt WHERE collegekrt=? AND passw=MD5(?)'
        );
    $stmt->sbind_param2('ss', $_SESSION['collegekrt'], $_POST['oldpassw']);
    $stmt->sexecute();
    if ($stmt->snum_rows()==0) {
        //old password incorrect, return to the form
        header('Location: '.$_SERVER['PHP_SELF'].'?error=2');
        exit();
    }
    
    //posted values are OK, proceed with password change
    $stmt = $mli->sprepare(
        'UPDATE claimwcollegekrt SET passw=MD5(?), aangemeld=1 WHERE collegekrt=? LIMIT 1'
        );
    $stmt->sbind_param2('ss', $_POST['passw'], $_SESSION['collegekrt']);
    $stmt->sexecute();
    $_SESSION['aangemeld'] = 1;
    header('Location: '.URLROOT.'/index.php?msg=passwordchanged');
}

?>
