<?php
/*
-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+
FILE: assessing.php
AUTHORS: W. Kaper
DATE: januari 2007
STATE: production

Form + script to create assessments by user of other team members.

Het script heeft 3 hoofd-vertakkingen:
- Als beoordelingen eerder werden verwerkt:
  Toon de eerder gegeven beoordelingen (deze zijn definitief)
- Als nog geen beoordelingen gegeven werden:
  Presenteer formulier
- Als zojuist beoordelingen gesubmit:
  Check deze en als ze kloppen: bewaar ze
  
3 vooraf gedefinieerde functies zorgen voor output van stukken html
*/

include 'inc/inc.php';

/* Echo de kop van het html-document
*/
function docHead() {
    ?><?php echo '<?xml version="1.0"?>' ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo APPNAME ?> - Beoordelingen door mij</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    </head>
    <body>
    <p>Je bent ingelogd als: <?php echo $_SESSION['firstname'].' '.$_SESSION['lastname']; ?></p>
    <p><a href="index.php">Menu</a></p>
    <h1><?php echo APPNAME ?> - Beoordelingen door jou</h1>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <table>
        <col><col>
        <tr><th>Teamgenoot</th><th>Percentage van groepscijfer</th></tr>
    <?php
}//end function dochead

/* Echo het einde van tabel en formulier
*/
function tableBottom($submit=FALSE) {
    ?>
    </table>
    <?php if ($submit) { ?>
        <input type="hidden" name="collegekrt" 
            value="<?php echo $_SESSION['collegekrt']; ?>" />
        <input type="submit" value="OK" />
    <?php } ?>
    </form>
    <?php
}

/* Echo het eind van het document, 
Met eventuele meldingen, afkomstig van de vorige run
*/
function docBottom() {
    if ($_GET['error']==="0") {
        ?>
        <p class="error">Je beoordelingen zijn bewaard!</p>
        <?php
    }
    if ($_GET['error']==1) {
        ?>
        <p class="error">Percentages moeten tussen 0 en 100 liggen.</p>
        <?php
    }
    if ($_GET['error']==2) {
        ?>
        <p class="error">De som van de percentages moet 100 zijn, plus of min 1.</p>
        <?php
    }
    ?>
</body>
</html>
<?php
}

/* Nu gaan we echt aan het werk
*/
$mli = new mymysqli(TRUE);
if ($_SESSION['klaar']) {
    //Beoordelingen zijn al eerder verwerkt, toon de gegeven beoordelingen
    docHead();  //start document
    $stmt = $mli->sprepare(
        'SELECT achternaam, voornaam, percentage '.
        'FROM studentstudent '.
        'INNER JOIN claimwcollegekrt '.
        '   ON studentstudent.student_beoordeeld_id=claimwcollegekrt.collegekrt '.
        'WHERE student_beoordelaar_id=? '.
        'ORDER BY achternaam '
        );
    $stmt->sbind_param1('s', $_SESSION['collegekrt']);
    $stmt->sbind_result3($lastname, $initals, $percent);
    $stmt->rexecute();
    while ($stmt->sfetch()) {
        ?>
        <tr>
            <td><?php echo $lastname.', '.$firstname; ?></td>
            <td><?php echo $percent; ?></td>
        </tr>    
    <?php 
    }
    tableBottom();
    ?> 
    <p>Je bent klaar met beoordelen van je teamgenoten.</p>
    <?php
    docBottom();
    exit();
}
if (
    !isset($_POST['collegekrt']) || $_POST['collegekrt']!=$_SESSION['collegekrt']
) {
    //Nog niets ingevoerd, presenteer het formulier
    docHead();  //start document    
    $stmt = $mli->sprepare(
        'SELECT achternaam, voornaam, collegekrt '.
        'FROM claimwcollegekrt '.
        'INNER JOIN groep USING (groep_id) '.
        'WHERE groep_id=? AND collegekrt<>? '.
        'ORDER BY achternaam '
        );
    $stmt->sbind_param2('is', $_SESSION['groupid'], $_SESSION['collegekrt']);
    $stmt->sbind_result3($lastname, $firstname, $ckbeoordeeld);
    $stmt->sexecute();
    $i = 0;
    while ($stmt->sfetch()) { 
        $i++;
        ?>
        <tr>
            <td><?php echo $lastname.', '.$firstname; ?></td>
            <td>
                <input type="text" name="percent[]" id="percent<?php echo i; ?>" />
                <input type="hidden" name="ckbeoordeeld[]" value="<?php echo $ckbeoordeeld; ?>"/>
            </td>
        </tr>
        <?php 
    }
    tableBottom(TRUE); 
    ?>
    <script type="text/javascript">
        var n = <?php echo $i; ?>; //aantal in te vullen percentages!
    </script>    
    <?php
    docBottom();
}
else {
    //Er zijn waarden gepost, check deze.
    $error = 0;
    $ckbeoordeeld = array();
    $percent = array();
    $som = 0;
    foreach ($_POST['ckbeoordeeld'] As $i => $ck) {
        $ckbeoordeeld[$i] = $ck;
        $percent[$i] = $_POST['percent'][$i];
        $som += $percent[$i];
        if ($percent[$i]<0 || $percent[$i]>100) $error=1; //fout: invalide waarde
        //echo 'i:'.$i.',ckbeoordeeld:'.$ckbeoordeeld[$i].',percent:'.$percent[$i].'<br />';
    }
    if (abs($som-100)>1.1) $error=2; //fout: som moet ongeveer op 100 uitomen
    if ($error >0) {
        //echo 'Location: '.$_SERVER['PHP_SELF'].'?error='.$error;
        header('Location: '.$_SERVER['PHP_SELF'].'?error='.$error);
        exit();
    }
    $schaalfactor = 100/$som;
    
    //geposte waarden zijn OK, bewaar deze, met her-schaling
    $stmt = $mli->sprepare(
        'INSERT INTO studentstudent '.
        'SET student_beoordelaar_id=?, student_beoordeeld_id=?, percentage=?, groep_id=?'
        );
    foreach ($ckbeoordeeld AS $i => $ckb) {
        $pctgeschaald = $percent[$i] * $schaalfactor;
        $stmt->sbind_param4('ssdi', $_SESSION['collegekrt'], $ckb, $pctgeschaald, $_SESSION['groupid']);
        $stmt->sexecute();
        //echo 'i:'.$i.',$_SESSION["collegekrt"]:'.$_SESSION['collegekrt'].
        //    ',ckb:'.$ckb.',pctgeschaald:'.$pctgeschaald.'<br />';
    }
    //beoordelaar is klaar, leg dit vast
    $stmt = $mli->sprepare('UPDATE claimwcollegekrt SET klaar=1 WHERE collegekrt=?');
    $stmt->sbind_param1('s', $_SESSION['collegekrt']);
    $stmt->sexecute();
    $_SESSION['klaar'] = 1;
    
    //Meldt dat het bewaren gelukt is
    $error = 0;
    //echo 'Location: '.$_SERVER['PHP_SELF'].'?error='.$error;
    header('Location: '.$_SERVER['PHP_SELF'].'?error='.$error);
}

