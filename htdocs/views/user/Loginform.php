<?php

if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Views_User_Loginform extends Views_Base {
	
	public $formresult = array();
	
	public function render() {
		$this->title = "Registreren";
		echo "<form action="login.php" method="post">
    <fieldset>
        <div class="control-group">
            <input autofocus name="username" placeholder="Username" type="text"/>
        </div>
        <div class="control-group">
            <input name="password" placeholder="Password" type="password"/>
        </div>
        <div class="control-group">
            <button type="inloggen" class="btn">inloggen</button>
        </div>
    </fieldset>
</form>
<div>
     <a href="register.php"> Nog geen WebdbOverflow account?</a> 
</div>";
	}
}
