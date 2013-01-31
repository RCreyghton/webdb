<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Controllers_Users extends Controllers_Base {

	public function login() {
		if ($this->getString("login_submit")) {

			$user = $this->getString("username");
			$pass = $this->getString("password");


			if (empty($user) || empty($pass)) {

				$this->view = new Views_User_Loginform();
				$this->view->errormessage = "U dient uw e-mailadres en uw wachtwoord in te vullen ! ";
			} else {

				$query = Models_User::getSelect();

				//get the salt for the pass
				$config = BASE . "../mysqlconfig.xml";
				if( ! is_file( $config ) ) {
					throw new Exception( "Could not load config file" );
				}
				$config = simplexml_load_file( $config );
				$salt = $config->salt;
				
				$pass = md5( $salt . $pass . $salt );
				$query .= " WHERE `email` = '$user' AND `pass` = '$pass' ";

				$result = Models_User::fetchByQuery($query);

				if (empty($result)) {

					$this->view = new Views_User_Loginform();
					$this->view->errormessage = "Deze combinatie van gegevens is niet correct. Probeert het nog eens.";
				} else {

					Helpers_User::login($result[0]);

					$this->view = new Views_User_Loginsuccess();
				}
			}
		} else {
			$this->view = new Views_User_Loginform();
		}

		$this->display();
	}
	
	public function logout() {
		if ( Helpers_User::isLoggedIn() ) {
			Helpers_User::logout();
		}
		
		$controller = new Controllers_Threads();
		$parts		= array( "threads", "unanswered");
		$controller->parseParts( $parts );
		$controller->execute( "unanswered" );
	}
		
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
			$formresult ["email"] ["errormessage"] = "Dit e-mailadres is al geregistreerd op deze site";
			return $formresult;
		}
		
		//get the salt for the pass
		$config = BASE . "../mysqlconfig.xml";
		if( ! is_file( $config ) ) {
			throw new Exception( "Could not load config file" );
		}
		$config = simplexml_load_file( $config );
		$salt = $config->salt;
		
		
		//now we know that all is well, finally build the user
		$u = new Models_User();
		$u->role		= Models_User::ROLE_USER;
		$u->email		= $email;
		$u->firstname	= $firstname;
		$u->lastname	= $lastname;
		$u->pass		= md5( $salt . $pass1 . $salt );
		$u->save();
		
		if( isset( $u->id ) ) {
			Helpers_User::sendWelcome( $u );
			return true;
		} else {
			return false;
		}
	}
	
	
	/**
	 * Fetches all users from the database and assambles a listing.
	 * Allowes for editing of the Users's role if admin is logged in.
	 */
	public function overview() {
		$this->view = new Views_Users_Overview();
		
		//Get evantualities: is there some edititing to do on this user?
		$make_admin = $this->getInt('make_admin');
		$make_user = $this->getInt('make_user');
		$make_block = $this->getInt('make_block');
		
		if( $make_admin ) {
			$user = Helpers_User::getLoggedIn();
			if( $user != NULL && $user->role == Models_User::ROLE_ADMIN ) {
				$edituser = Models_Category::fetchById($make_admin);
				if ( $edituser != NULL ) {
					$edituser->role = 1;
					$edituser->save();
				}
			}
		}
		if( $make_user ) {
			$user = Helpers_User::getLoggedIn();
			if( $user != NULL && $user->role == Models_User::ROLE_ADMIN ) {
				$edituser = Models_Category::fetchById($make_user);
				if ( $edituser != NULL ) {
					$edituser->role = 0;
					$edituser->save();
				}
			}
		}
		if( $make_block ) {
			$user = Helpers_User::getLoggedIn();
			if( $user != NULL && $user->role == Models_User::ROLE_ADMIN ) {
				$edituser = Models_Category::fetchById($make_block);
				if ( $edituser != NULL ) {
					$edituser->role = 0;
					$edituser->save();
				}
			}
		}
		
		//Now we can acuatually fetch all categories and display them.
		$query = Models_User::getSelect() . "ORDER BY `ts_registered`;";
		$users = Models_User::fetchByQuery( $query );
		$this->view->users = $users;
		
		$this->display();
	}
	
	
}
