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
	}
	
	/**
	 * 
	 * @param Models_User $user
	 * @todo Seed opslaan bij sessie zoals Robert Belleman wil.
	 * @todo lifetime?
	 */
	public static function login($user) {
		$_SESSION['user'] = $user->id;
		$_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
	}

	public static function isLoggedIn() {
		return isset( $_SESSION['user'] ) 
				&& isset( $_SESSION['user_ip'] ) 
				&& $_SESSION['user_ip'] == $_SERVER['REMOTE_ADDR'];
	}
	public static function getLoggedIn() {
		if ( ! self::isLoggedIn() )
			return NULL;
		
		return Models_User::fetchById( $_SESSION['user'] );
	}
	
	public static function logout() {
		session_destroy();
	}

}