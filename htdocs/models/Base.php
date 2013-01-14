<?php

if( ! defined("WEBDB_EXEC") ) die("No direct access!");

/*
 * Base class
 * 
 * This class sits atop all classes that need database interaction.
 * When a class extends this one it automatically inherits all db functions such
 * as save, fetchById and fetchByQuery.
 */

class Models_Base {
	
	function test() {
		
		$db = new Helpers_Db();
		echo "wiiiiiii";
	}
		
}