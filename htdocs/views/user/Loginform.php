<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Renders the login-form
 * 
 * @author Shafiq Ahmadi <s.ah@live.nl>
 */
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
				E-mailadres:<br /><small>(waarmee u zich registreerde)</small>
			</td>
			<td>
				<input name="username" type="text"/>
			</td>
		</tr>
		<tr>
			<td>
				Wachtwoord:
			</td>
			<td>
				<input name="password" type="password"/>
			</td>
		</tr>
		<tr>
			<td colspan='2'>
				<input type='submit' name='login_submit' value='.' class='loginbutton bbutton' />
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
