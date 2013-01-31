<?php

class Views_Categories_Form extends Views_Threads_Base {

	public $thread;
	public $form;
	
	public function render() {
		if( ! Helpers_User::isLoggedIn() ) {
			echo "Om een nieuwe vraag te stellen moet u ingelogd zijn!";
			return;
		}
		
		echo "<form method='post' action='./categories/categoryform'>";
		echo "<table>";
		foreach ( $this->form as $name => $e ) {
			echo "<tr>";
			
			echo "<td>";
			echo $e["description"];
			echo "</td>";
			
			echo "<td>";
			switch( $e ['type'] ) {
				
				case "select":
					echo "<select name='" . $name . "'>";
					foreach( $e['values'] as $id => $v ) {
						echo "<option value='$id' " . ( $e['value'] == $id ? "selected='selected'":"") . ">$v</option>";
					}
					echo "</select>";
					break;
				
				case "textarea":
					echo isset( $e['original'] ) ? nl2br( $e['original'] ) . "<hr/>" : "";
					echo "<textarea name='" . $name . "'>" . $e['value'] . "</textarea>";
					break;
				
				default:
					echo "<input type='" . $e['type'] . "' name='" . $name . "' value='" . $e['value'] . "' />";
					break;
					
			}
			if( !empty($e['errormessage']) ) echo "<span class='errormessage'>" . $e['errormessage'] . "</span>";
			echo "</td>";
			
			echo "</tr>";
		}
		
		echo "<tr><td colspan='2'><input type='submit' name='categoryform_submit' value='Opslaan' /></td></td>";
		echo "</table>";
		echo "</form>";
	}

}
