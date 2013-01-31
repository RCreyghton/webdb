<?php

class Views_Errors_Internal extends Views_Base {
	
	
	/**
	 * Renders the contents of this view, simply outputting a 404 message.
	 * 
	 * @author	Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function render() {
		$this->title = "Pagina niet gevonden";
		echo "\t\t\t\t\t<h2>Interne fout</h2>\n Er is een interne fout opgetreden. Welicht probeert u een pagina te bezoeken die niet (meer) beschikbaar is.<p></p>";
	}
	
}
