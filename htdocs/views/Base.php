<?php

if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * All the basic functionality of any view is in here: we need a basic template with menu, login and base url's filled in.
 * 
 * @author Frank van Luijn <frank@accode.nl>
 * @author Ramon Creyghton <r.creyghton@gmail.com>
 */
abstract class Views_Base {

	/**
	 * Every view needs a title, in addition to the global site name, so we define it here in Views_Base
	 * @var string  
	 */
	public $title;

	/**
	 * Every view should be able to render itself. 
	 */
	abstract public function render();

	/**
	 * Makes the template ready as far as any view goes. The actual contents of any are still to be injected (see {@link Controllers_Base::display()} in the string this method returns.
	 * 
	 * @uses assets/template.html The lay-out template, dynamic elements left out.
	 * @return string	A huge string containing the preprocessed template, missing only the contents.
	 */
	public function getTemplate() {
		$template = file_get_contents(BASE . "assets/template.html");
		$template = str_replace("<!-- TITLE -->", $this->title, $template);
		$template = str_replace("<!-- BASEURL -->", $this->getBaseURL(), $template);
		$template = str_replace("<!-- MENU -->", $this->getMenu(), $template);
		$template = str_replace("<!-- LOGIN -->", $this->getLogin(), $template);
		return $template;
	}

	/**
	 * Concatenates the given parameters to a relative URL to, presumably, the current view.
	 * 
	 * @param string $controller
	 * @param string $task
	 * @return string
	 */
	public function getURL($controller, $task) {
		return "./" . $controller . "/" . $task;
	}

	/**
	 * Assembles html for the menu, to be put in the header of each view.
	 * 
	 * @todo Dynamisch maken, huidige view arceren?
	 * @return string Correctly indented xhtml in the context of assets/template.html
	 */
	public function getMenu() {
		return <<<MENU
					<ul class="menu">
						<li><a href="index.php?forum=1" class="menulink">Forum 1</a></li>
						<li><a href="index.php?forum=2" class="menulink">Forum 2</a></li>
						<li><a href="index.php?forum=3" class="menulink">Forum 3</a></li>
					</ul>
MENU;
	}

	/**
	 * Assembles html for the login / register or logout / dashboard block any pages' header.
	 * 
	 * @todo Sessie-afhankelijk maken.
	 * @return string Correctly indented xhtml in the context of assets/template.html
	 */
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

	/**
	 * Assembles a HTML BASE tag, to be injected in the template.
	 * 
	 * @return string A base URL for the site, depending on the current server and PHP_SELF location.
	 */
	public function getBaseURL() {
		return "<base href=\"" . substr($_SERVER["PHP_SELF"], 0, strlen($_SERVER["PHP_SELF"]) - 9) . "\" />";
	}

}