


session_start();
$session=session_id();
$time=time();
$time_check=$time-600; //SET TIME 10 Minute


$count=mysql_num_rows($result);

$result3=mysql_query($sql3);


$sql3="SELECT * FROM $tbl_name";
$count_user_online=mysql_num_rows($result3);
echo "User online : $count_user_online ";

$result4=mysql_query($sql4);

// if over 10 minute, delete session 
$sql4="DELETE FROM $tbl_name WHERE time<$time_check";
echo "User online : $count_user_online ";
// Open multiple browser page for result

$count=mysql_num_rows($result);

<?php



if($count=="0"){

$result1=mysql_query($sql1);
}

else {
}


// Close connection
mysql_close();
?>
