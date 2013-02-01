<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Helpers_User {

	/**
	 * Emails this user a welcom-message.
	 * 
	 * @param Models_User $u
	 */
	public static function sendWelcome(Models_User $u) {
		//send email
		$email = $u->email;
		$subject = "Welkom wij webDBOverflow!";
		$message = "Beste {$u->firstname} {$u->lastname}, 
			
		Welkom als gebruiker op WebDBOverflow. U kunt nu vragen plaatsen, antwoorden geven en deze beoordelen.";

		mail("{$u->firstname} {$u->lastname} <{$email}>", $subject, $message, "From: noreply@webdboverflow.nl");
	}

	/**
	 * Sets a session, thereby logging the currend user in.
	 * 
	 * Storing both user-id and ip-address, basic check against session-hijacking.
	 * 
	 * @param Models_User $user
	 */
	public static function login($user) {
		$_SESSION['user'] = $user->id;
		$_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
	}

	/**
	 * Checks of a user is logged in.
	 * 
	 * @return boolean	True if logged in.
	 */
	public static function isLoggedIn() {
		return isset($_SESSION['user'])
						&& isset($_SESSION['user_ip'])
						&& $_SESSION['user_ip'] == $_SERVER['REMOTE_ADDR'];
	}

	/**
	 * Returns the current user, or NULL if none logged-in.
	 * 
	 * @return Models_User|null
	 */
	public static function getLoggedIn() {
		if (!self::isLoggedIn())
			return NULL;

		return Models_User::fetchById($_SESSION['user']);
	}

	/**
	 * Destroys the session, thereby logging the user out.
	 * 
	 * @todo Does have a delay?
	 */
	public static function logout() {
		session_destroy();
	}

}