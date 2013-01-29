<?php

class Views_Threads_Form extends Views_Threads_Base {

	public $thread;
	public $form;
	
	public function render() {
		echo "<form method='post' action='./threads/threadform'>";
		echo "<table>";
		foreach ( $this->form as $name => $e ) {
			echo "<tr>";
			
			echo "<td>";
			echo $e["description"];
			echo "</td>";
			
			echo "<td>";
			switch( $e ['type'] ) {
				default:
					echo "<input type='" . $e['type'] . "' name='" . $name . "' value='" . $e['value'] . "' />";
					break;
			}
			echo "</td>";
			
			echo "</tr>";
		}
		echo "</table>";
		echo "</form>";
	}

}
