<?php

if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Views_User_Registrationcomplete extends Views_Base {
	
	public $formresult = array();
	
	public function render() {
		$this->title = "Geregistreerd";
		echo "<h2>U bent succesvol geregistreerd</h2>\n<p>U kunt nu <a href=\"users/login/\">inloggen</a> met uw e-mailadres en wachtwoord.</p>";
	}

}
