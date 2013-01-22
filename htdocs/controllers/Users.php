<?php

if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Controllers_Users extends Controllers_Base {
	
	public function register() {
		
		//if we have a submitted form, process it
		if( $this->getString( "register_submit" ) ) {
			$result = registerUser();
			
			//if succesfull, show the Registrationcomplete view
			//else show the form with the faulty entered data
			if( $result === true ) {
				$this->view = new Views_User_Registrationcomplete();
			} else {
				$this->view = new Views_User_Registrationform();
				$this->view->formresult = $result;
			}
		} else {
			$this->view = new Views_User_Registrationform();
		}
		
	    $this->display();
	}
	
	private function registerUser() {
		
	}
	
}
