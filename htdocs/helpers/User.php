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
		//TODO checken of sessie al ergens gestart is.. Dit in index doen?
		if (! session_start() )
			throw new Exception("Unable to start a session");
		$_SESSION['user'] = $user->id . "_" . "seed";
	}

	public static function getLoggedIn() {
		//TODO checken of sessie al ergens gestart is.. Dit in index doen?
		if (! session_start() )
			throw new Exception("Unable to start a session");
		if ( ! isset($_SESSION['user']) )
			return NULL;
		$sessionstrings = explode("_", $_SESSION['user'] );
		$user = Models_User::fetchById($sessionstrings[0]);
		//seed afhandelen?
		return $user;
	}
	
	public static function logout() {
		session_destroy();
	}

}