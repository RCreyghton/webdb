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
	 * For URL-making and other linking purposes its good the know the calling object.
	 * The values for these will be stored in de controllers' params list, and can be loaded usingt {@link loadParams()}.
	 * 
	 * @var string
	 */
	public $controller;
	public $task;
	
	/**
	 * 
	 * @param Controllers_Base	$controller
	 */
	public function loadParams( $controller ) {
		foreach ($controller->params as $key => $value) {
			if ( array_key_exists( $key, get_object_vars( $this )) ) {
				$this->$key = $value;
			}
		}
	}

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
		$template = str_replace("<!-- STATISTICS -->", $this->getStatistics(), $template);
		return $template;
	}

	/**
	 * Concatenates the given parameters to a relative URL to, presumably, the current view.
	 * 
	 * @return string
	 */
	public function getURL() {
		return "./" . $this->controller . "/" . $this->task;
	}

	/**
	 * Assembles html for the menu, to be put in the header of each view.
	 * 
	 * @return string Correctly indented xhtml in the context of assets/template.html
	 * @author Frank van Luijn <frank@accode.nl>
	 */
	public function getMenu() {
		$items = array(
			"Home"					=> "threads/unanswered",
			"Categorieën"			=> "categories/overview",
			"Meest gestelde vragen" => "threads/answered",
			"Zoeken"				=> "search/new",
			"Stel een vraag"		=> "threads/new"
		);
		
		$rv = "<ul class='menu'>";
		foreach( $items as $name => $link ) {
			$active = ($this->controller . "/" . $this->task == $link) ? " active":"";
			$rv .= "<li><a href='./{$link}' class='menulink{$active}'>{$name}</a></li>\n";
		}
		$rv .= "</ul>\n";
		return $rv;
	}
	
	public function getStatistics() {
		$base_q = Models_Thread::getSelect();
		$threads_cnt = Models_Thread::getCount( $base_q );
		$threads_ans = Models_Thread::getCount( $base_q . "WHERE `answer_id` IS NOT NULL" );
		$threads_una = $threads_cnt - $threads_ans;
		$threads_rat = number_format( $threads_ans / $threads_cnt, 2);
		
		$cat_cnt = Models_Category::getCount( Models_Category::getSelect() );
		$usr_cnt = Models_User::getCount( Models_User::getSelect() );
		$rpl_cnt = Models_Reply::getCount( Models_Reply::getSelect() );
		
		return "<div class='statistics_container'>
						<h3><span class='hero_number'>{$threads_cnt}</span> vragen</h3>
						<h3><span class='hero_number'>{$threads_ans}</span> beantwoord</h3>
						<h3><span class='hero_number'>{$threads_una}</span> onbeantwoord</h3>
						<h3><span class='hero_number'>{$threads_rat} %</span> verhouding</h3>
					</div>

					<div class='statistics_container'>
						<h3><span class='hero_number'>{$cat_cnt}</span> categorie&euml;n</h3>
						<h3><span class='hero_number'>{$usr_cnt}</span> gebruikers</h3>
						<h3><span class='hero_number'>{$rpl_cnt}</span> reacties</h3>
					</div>
		";
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
								<a href="./users/login">Login</a>
							</li>
							<li>
								<a href="./users/register">Registreer</a>
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