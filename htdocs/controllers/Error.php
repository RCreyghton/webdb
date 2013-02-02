<?php


class Controllers_Error extends Controllers_Base {
	
	public function notfound() {
	    $this->view = new Views_Errors_Notfound();
	    $this->display();
	}
	
	public function internal() {
		//maybe log something here...
		$this->view = new Views_Errors_Internal();
		$this->display();
	}
	
}
