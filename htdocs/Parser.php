<?php
/**
 * Description of Parser
 *
 * @author Ramon Creyghton	<r.creyghton@gmail.com>
 */
class Parser {
	public static $viewLinks = array(
			//By changing the value of key default, it's possible to change the sites' home page view.
			"default" => "TopThreads",
			"topthreads" => "TopThreads",
			"topreplies" => "TopReplies",
			"thread" => "Thread",
			"reply" => "Reply",
			"user" => "User",
			"users" => "User",
			"category" => "Category",
			"categories" => "Category" );
			
	
	/**
	 * Checks if GET has parameter q and parses it to a view-object.
	 * 
	 * @author Ramon Creyghton <r.creyghton@gmailcom>
	 * @return Views_Base An instance of any of the Views_Base child-classes.
	 */
	public function parser() {
		$viewParams = (isset($_GET['q']) && !empty($_GET['q'])) ? 
			implode("/", $_GET['q']) : 
			array("default");

		if (array_key_exists($viewParams[0], self::$viewLinks)) {
			$className = "Views_" . self::$viewLinks[$viewParams[0]];
			$view = new $className($viewParams);
		} else {
			$view = new Views_Error(array("404"));
		}
		return $view;
	}
}

?>
