<?php

if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Views_User_Loginsuccess extends Views_Base {
	
	public function render() {
		$this->title = "Ingelogd";
		echo "<h2>U bent succesvol ingelogd</h2>\n<p>U kunt nu terug naar bijvoorbeeld de <a href=\"./\">homepage</a>.</p>";
	}

}
