<?php
/*
-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
FILE: login.php
AUTHORS: W. Kaper
DATE: januari 2007
STATE: production

Login form + script for peer-assessment app.

If nothing was posted, this script presents the form.
The form calls this script again, with $_POST['collegekrt'] and $_POST['passw'] set
Then the second part of the script is run: the password is checked.
    If all is OK, the $_SESSION['loggedinpass'] is set to a secret value
    Else, the form is called again with $_GET['incorrect'] set, to cause a message.
*/

$loginrequired = false; //you don´t need to be loggedin to access this script!
include 'inc/inc.php';
if (!isset($_POST['collegekrt']) || !isset($_POST['passw']) ) {
    //Nothing posted, so: present the form

    ?><?php echo '<?xml version="1.0"?>' ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <title><?php echo APPNAME ?> - Login</title>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<h1><?php echo APPNAME ?> - Login</h1>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table>
    <tr><td>studentnummer</td><td><input type="text" name="collegekrt" /></td></tr>
    <tr><td>wachtwoord</td><td><input type="password" name="passw" /></td></tr>
</table>
<input type="submit" value="login" />
</form>
<?php if ($_GET['incorrect']==1) { ?>
    <p class="error">Login fout, probeer het nog eens!</p>
<?php } ?>
</body>
</html><?php
    
}
else {
    //Check posted username and password.
    $mli = new mymysqli(TRUE);
    $stmt = $mli->sprepare(
        'SELECT collegekrt, achternaam, voornaam, groep_id, aangemeld, klaar FROM claimwcollegekrt WHERE collegekrt=? AND passw=MD5(?)'
        );
    $stmt->sbind_param2('ss', $_POST['collegekrt'], $_POST['passw']);
    //More than 5 result variables, so the long syntax is necessary
    //(Or I could have written 1 extra method in the "mystatement" class: sbind_result6)
    $stmt->reset_result();
    $stmt->set_resultvar($collegekrt);
    $stmt->set_resultvar($lastname);
    $stmt->set_resultvar($firstname);
    $stmt->set_resultvar($groupid);
    $stmt->set_resultvar($aangemeld);
    $stmt->set_resultvar($klaar);
    $stmt->sbind_result();
    $stmt->sexecute();
    if ($stmt->snum_rows()==0) {
        //login incorrect, return to the form
        header('Location: '.$_SERVER['PHP_SELF'].'?incorrect=1');
    }
    else {
        //login OK, set the users properties in the session
        //along with the secret LOGGEDINPASS
        echo 'Goedgekeurd! dadelijk redirect naar /index.php<br />';
        $stmt->sfetch();
        $_SESSION['loggedinpass'] = LOGGEDINPASS;
        $_SESSION['collegekrt'] = $collegekrt;
        $_SESSION['lastname'] = $lastname;
        $_SESSION['firstname'] = $firstname;
        $_SESSION['groupid'] = $groupid;
        $_SESSION['aangemeld'] = $aangemeld;
        $_SESSION['klaar'] = $klaar;
        //
        header('Location: '.URLROOT.'/index.php');
    }
}

?>
