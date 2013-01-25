<?php

if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Views_User_Loginform extends Views_Base {
	
	public $formresult = array();
	
	public function render() {
		$this->title = "Registreren";
		echo "Hier komt de html";
	}
}
