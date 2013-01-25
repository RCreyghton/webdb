<?php

class Views_Errors_Notfound extends Views_Base {
	
	
	/**
	 * Renders the contents of this view, simply outputting a 404 message.
	 * 
	 * @author	Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function render() {
		$this->title = "Pagina niet gevonden";
		echo "\t\t\t\t\t<h2>404</h2>\n\t\t\t\t\t<p>De opgegeven pagina kon niet gevonden worden</p>";
	}
	
}
