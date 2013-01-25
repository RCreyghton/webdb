<?php

if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Views_User_Loginform extends Views_Base {
	
	public $errormessage = "";
	
	public function render() {

		$this->title = "Registreren";
		if( ! empty( $this->errormessage ) ) {
			echo "<h3>Fout: {$this->errormessage} </h3>";
		}
?>
<form action="./users/login" method="post">
	<table>
		<tr>
			<td>
				Gebruikersnaam
			</td>
			<td>
				<input name="username" type="text"/>
			</td>
		</tr>
		<tr>
			<td>
				Wachtwoord
			</td>
			<td>
				<input name="password" type="password"/>
			</td>
		</tr>
		<tr>
			<td colspan='2'>
				<input type='submit' name='login_submit' value='Inloggen' />
			</td>
		</tr>
	</table> 
</form>
<div>
     <a href="./users/register"> Nog geen WebdbOverflow account?</a> 
</div>
<?php
	}
}
