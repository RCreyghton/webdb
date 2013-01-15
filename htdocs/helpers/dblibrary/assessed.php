<?php
/*
-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
FILE: assessed.php
AUTHORS: W. Kaper
DATE: januari 2007
STATE: production

Beoordelingen laten zien aan de beoordeelde.
Getoond worden:
- Het groepscijfer, gegeven door de docenten
- De som van de (herschaalde) percentages, gegeven door de teamgenoten
- Het eigen eindcijfer:= het produkt van de twee voorgaande / 100

Er verschijnen passende meldingen als sommige gegevens nog niet compleet zijn.
- "De docenten hebben nog geen cijfer gegeven aan de groep"
- "N van de M teamgenoten hebben een beoordeling gegeven"
- "Je eindcijfer is nog niet bekend"
*/

include 'inc/inc.php';
$mli = new mymysqli(TRUE);

//haal beoordelingen van teamgenoten
$stmt = $mli->sprepare(
    'SELECT SUM(percentage), COUNT(studentstudent_id) FROM studentstudent '.
    'WHERE student_beoordeeld_id=? '
    );
$stmt->sbind_param1('s', $_SESSION['collegekrt']);
$stmt->sbind_result2($sumPercent, $countBeoordelaars);
$stmt->sexecute();
if (! $stmt->sfetch()) {
    //nog 0 beoordelingen
    $sumPercent = 0;
    $countBeoordelaars = 0;
}

//hoeveel teamgenoten zijn er in totaal?
$stmt = $mli->sprepare(
    'SELECT COUNT(collegekrt) FROM claimwcollegekrt '.
    'WHERE groep_id=? '
    );
$stmt->sbind_param1('i', $_SESSION['groupid']);
$stmt->sbind_result1($countGroepsleden);
$stmt->sexecute();
if (! $stmt->sfetch()) {
    //student is individueel
    $countGroepsleden = 1;
}

//wat is het groepscijfer gegeven door de docent?
$stmt = $mli->sprepare(
    'SELECT nummer, groepscijfer FROM groep '.
    'WHERE groep_id=? '
    );
$stmt->sbind_param1('i', $_SESSION['groupid']);
$stmt->sbind_result2($groepsnummer, $groepscijfer);
$stmt->sexecute();
if (! $stmt->sfetch()) {
    //student is individueel
    $groepsnummer = 0;
    $groepscijfer = -1;
}

//nu dit aan de beoordeelde vertellen
$teamgenotenklaar = ($countBeoordelaars==$countGroepsleden-1);
$individueelcijfer = $sumPercent * $groepscijfer / 100;
$groepscijferTxt = ($groepscijfer>0)? 
    ($groepscijfer.' voor groep '.$groepsnummer) : 
    ('Onbekend! De docenten hebben nog geen cijfer gegeven aan groep '.$groepsnummer.'.');
$sumPercentTxt = ($teamgenotenklaar)?
    (sprintf('%02.2f%%', $sumPercent)) :
    ('Onbekend! '.$countBeoordelaars.' van de '.($countGroepsleden-1).' teamgenoten hebben een beoordeling gegeven.') ;
$individueelTxt = ($groepscijfer>0 && $teamgenotenklaar) ?
    (sprintf('%02.2f%% van %01.1f = %01.1f', $sumPercent, $groepscijfer, $individueelcijfer)) :
    ('Onbekend!') ;

?><?php echo '<?xml version="1.0"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <title><?php echo APPNAME; ?></title>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
   <style type="text/css">
    .result {margin-left: 5em; background-color: green; color: white}
   </style>
</head>
<body>

<p>Je bent ingelogd als: <?php echo $_SESSION['firstname'].' '.$_SESSION['lastname']; ?></p>
<p><a href="index.php">Menu</a></p>
<h1><?php echo APPNAME; ?> - </h1>
<h2>Beoordelingen van jou, door teamgenoten en docenten</h2>

<?php if ($groepsnummer>0) { ?>
    <p>Groepscijfer, gegeven door de docenten:</p>
        <p><span class="result"><?php echo $groepscijferTxt; ?></span></p>
    <p>Som van percentages, gegeven door teamgenoten:</p>
        <p><span class="result"><?php echo $sumPercentTxt; ?></span></p>
    <p>Je cijfer is dus:</p>
        <p><span class="result"><?php echo $individueelTxt; ?></span></p>
<?php } else { ?>
    <p>Er zijn geen teamgenoten bekend.<br />
    Werk je individueel? Neem contact op met <?php echo WEBMASTER_EMAIL_LINK; ?> als je denkt dat dit bericht onjuist is.
    </p>
<?php } ?>

</body>
</html>
