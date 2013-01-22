<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Helpers_User {

	public static function sendWelcome(Models_User $u) {
		//send email
		$email = $u->email;
		$subject = "Welkom wij webDBOverflow!";
		$message = "Beste {$u->firstname} {$u->lastname}, 
			
		Wij heten u van harte welkom.... U kunt hier vanalles doen....";
		
		mail("{$u->firstname} {$u->lastname} <{$email}>", $subject, $message, "From: noreply@webdboverflow.nl");
		echo "Thank you for using our mail form";
	}

}