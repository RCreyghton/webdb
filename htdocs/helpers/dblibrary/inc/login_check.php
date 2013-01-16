<?php
//check if user is logged-in
//    logged-in users have a variable loggedinpass in their session set to a special secret value

/* Debug
echo 'loginrequired 2:'.(($loginrequired)?(1):(0)).'<br />';
echo 'SESSION["loggedinpass"]:'.$_SESSION['loggedinpass'].'<br />';
echo 'LOGGEDINPASS:'.LOGGEDINPASS.'<br />';
*/
if (
    $loginrequired &&
    (!isset($_SESSION['loggedinpass']) || $_SESSION['loggedinpass']!=LOGGEDINPASS)
) {
    //user is not logged in, but should be
    echo 'redirect naar login<br />';
    header('Location: '.URLROOT.'/login.php');
}
?>
