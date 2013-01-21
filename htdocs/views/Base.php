<?php


abstract class Views_Base {
	
	public $title;

	abstract public function render();
	
    public function getTemplate() {
		$template = file_get_contents(BASE . "assets/template.php");
		$template = str_replace("<!-- TITLE -->", $this->title, $template);
		$template = str_replace("<!-- BASEURL -->", getBaseURL(), $template);
		$template = str_replace("<!-- MENU -->", getMenu(), $template);
		$template = str_replace("<!-- LOGIN -->", getLogin(), $template);
    }
    
    
    public function getMenu() {
    	return <<<MENU
					<ul class="menu">
						<li><a href="index.php?forum=1" class="menulink">Forum 1</a></li>
						<li><a href="index.php?forum=2" class="menulink">Forum 2</a></li>
						<li><a href="index.php?forum=3" class="menulink">Forum 3</a></li>
					</ul>
MENU;
    }
	
	
	public function getLogin() {
    	return <<<LOGIN
						<ul>
							<li>
								<a href="login.php">Login</a>
							</li>
							<li>
								<a href="register.php">Registreer</a>
							</li>
						</ul>
LOGIN;
    }
    
    public function getBaseURL() {
		return "<base href=" . substr($_SERVER["PHP_SELF"], 0, strlen($_SERVER["PHP_SELF"]) - 9) . " target="_blank" />";
	}
	
}
