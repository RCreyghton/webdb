<?php

if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Controllers_Users extends Controllers_Base {
	
	public function register() {
		
		//if we have a submitted form, process it
		if( $this->getString( "register_submit" ) ) {
			$result = $this->registerUser();
			
			//if succesfull, show the Registrationcomplete view
			//else show the form with the faulty entered data
			if( $result === true ) {
				$this->view = new Views_User_Registrationcomplete();
			} elseif( is_array( $result ) ) {
				$this->view = new Views_User_Registrationform();
				$this->view->formresult = $result;
			} else {
				//if the script reaches this point, something whent wrong
				//while saving the user
				$this->view = new Views_Error_Internal();
			}
		} else {
			$this->view = new Views_User_Registrationform();
		}
		
	    $this->display();
	}
	
	/**
	 * Function that will attempt to register a user
	 * returns true if succesfull
	 * array with formelement-related values if incorrect values where passed
	 */
	private function registerUser() {
		//if there are any errors on any of the fields of the registration form
		//they will be stored here
		$formresult = array();
		
		$validated = true;
		
		//get the values
		$firstname	= $this->getString("firstname");
		$lastname	= $this->getString("lastname");
		$pass1		= $this->getString("pass1");
		$pass2		= $this->getString("pass2");
		$email		= $this->getString("email");
		
		//now insert the values back into the elements for display
		//in case there is an error
		$formresult ["firstname"]	["value"] = $firstname; 
		$formresult ["lastname"]	["value"] = $lastname; 
		$formresult ["email"]		["value"] = $email; 
		
		###### Begining of validation block
		//firstname
		if( strlen( $firstname ) < 3 ) {
			$formresult ["firstname"] ["errormessage"] = "Uw voornaam dient minstens 3 letters te bevatten";
			$validated = false;
		}
		
		//lastname
		if( strlen( $lastname ) < 3 ) {
			$formresult ["lastname"] ["errormessage"] = "Uw achternaam dient minstens 3 letters te bevatten";
			$validated = false;
		}
		
		//lastname
		if( ! filter_var($email, FILTER_VALIDATE_EMAIL) ) {
			$formresult ["email"] ["errormessage"] = "Geef een geldig email adres op";
			$validated = false;
		}
		
		if( strlen( $pass1 ) < 6 ) {
			$formresult ["pass1"] ["errormessage"] = "Uw wachtwoord moet minstens 6 tekens lang zijn";
			$validated = false;
		}
		
		if( $pass1 != $pass2 ) {
			$formresult ["pass1"] ["errormessage"] = "Uw wachtwoorden komen niet overeen";
			$validated = false;
		}
		###### End of validation block
		
		
		//if we found any errors, return with the list of invalied elements
		if( !$validated )
			return $formresult;
		
		//if we didn't, it's now time to create a new user :D
		
		//first see if the email-adres exists, if it does, let the user know and return the form
		$query = Models_User::getSelect() . " WHERE `email`='" . Helpers_Db::escape( $email ) . "';";
		$result = Models_User::fetchByQuery($query);
		if( !empty( $result ) ) {
			$formresult ["email"] ["errormessage"] = "Geef een geldig email adres op";
			return $formresult;
		}
		
		//now we know that all is well, finally build the user
		$u = new Models_User();
		$u->role		= Models_User::ROLE_USER;
		$u->email		= Helpers_Db::escape( $email );
		$u->firstname	= Helpers_Db::escape( $firstname );
		$u->lastname	= Helpers_Db::escape( $lastname );
		$u->pass		= md5( $pass1 );
		$u->save();
		
		if( isset( $u->id ) ) {
			Helpers_User::sendWelcome( $u );
			return true;
		} else {
			return false;
		}
	}
	
}
