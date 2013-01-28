<?php

$username= $_POST['username'];
$password= $_POST['password'];

if (password&&username) {

$connect= mysql_connect("localhost", "root","") or die (couldn't not connect);

mysql_select_db("phplogin") or die (could not find db);

}

else 
    die ("Please enter your password and username");
    

?>
