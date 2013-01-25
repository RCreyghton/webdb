<?php

if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Views_User_Registrationform extends Views_Base {
	
	public $formresult = array();
	
	public function render() {
		$this->title = "Registreren";
		echo "<form method='post' action='" . parent::getURL("users", "register") . "' >";
		echo "<table>";
	
		$elements = $this->getElements();
		foreach( $elements as $field => $e ) {
			echo "<tr>";
			
			echo "<td>";
			echo $e['description'];
			echo "</td>";
			
			echo "<td>";
			echo "<input 
					type='"  . $e['type']   . "' 
					name='"  . $field		. "' 
					value='" . $e['value']  . "'
					class='" . ( empty( $e['errormessage'] ) ? "":"inputerror") . "'
			/>";
			echo !empty( $e['errormessage'] ) ? 
				"<span class='errormessage'>" . $e['errormessage'] . "</span>"
				: '';
			echo "</td>";
			
			echo "</tr>";
		}
		
			echo "<tr>";
			
			echo "<td colspan='2'>";
			echo "<input type='submit' name='register_submit' value='registreer mij' /> ";
			echo "</td>";
			
			echo "</tr>";
		echo "</table>";
		echo "</form>";
	}
	
	
	private function getElements() {
		$elements = array();
		
		//first name
		$elements ['firstname'] = array(
			'description'	=> 'Voornaam',
			'type'			=> 'text'
		);
		
		//last name
		$elements ['lastname'] = array(
			'description'	=> 'Achternaam',
			'type'			=> 'text'
		);
		
		//Email
		$elements ['email'] = array(
			'description'	=> 'Email',
			'type'			=> 'text'
		);
		
		//password
		$elements ['pass1'] = array(
			'description'	=> 'Wachtwoord',
			'type'			=> 'password'
		);
		
		//password
		$elements ['pass2'] = array(
			'description'	=> 'Herhaal wachtwoord',
			'type'			=> 'password'
		);
		
		//load the array up with empty valued for strict
		foreach( $elements as &$e ) {
			$e [ 'value' ]			= '';
			$e [ 'errormessage' ]	= '';
		}
		
		//map all results onto these elements
		foreach( $this->formresult as $field => &$e ) {
			if( ! isset( $elements[ $field ]) )
				continue;
			
			if( isset( $e ['value'] ) )
				$elements[ $field ] [ 'value' ]			= $e ['value'];
			
			if( isset( $e ['errormessage'] ) )
				$elements[ $field ] [ 'errormessage' ]	= $e ['errormessage'];	
		}
		
		return $elements;
	}
}
