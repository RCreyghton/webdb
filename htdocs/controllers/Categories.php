<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Controls the viewing, making and editing of Categories.
 * In many ways like User (lising, status, admin-overrule...)
 * 
 * @author Ramon Creyghton <r.creyghton@gmail.com>
 */
class Controllers_Categories extends Controllers_Base {

	/**
	 * Fetches all categories from the database and assambles a listing.
	 * Allowes for editing of the Category's status if admin.
	 */
	public function overview() {
		$this->view = new Views_Categories_Overview();
		
		//Get evantualities: is there some edititing to do on this thread?
		$restrict = $this->getInt('restrict_status');
		$open = $this->getInt('open_status');
		
		if( $restrict ) {
			$user = Helpers_User::getLoggedIn();
			if( $user != NULL && $user->role == Models_User::ROLE_ADMIN ) {
				$category= Models_Category::fetchById($restrict);
				if ( $category != NULL ) {
					$category->status = 0;
					$category->save();
				}
			}
		}
		if( $open ) {
			$user = Helpers_User::getLoggedIn();
			if( $user != NULL && $user->role == Models_User::ROLE_ADMIN ) {
				$category= Models_Category::fetchById($open);
				if ( $category != NULL ) {
					$category->status = 1;
					$category->save();
				}
			}
		}
		
		//Now we can acuatually fetch all categories and display them
		$query = Models_Category::getSelect() . "ORDER BY `name`;";
		$categories = Models_Category::fetchByQuery( $query );
		$this->view->categories = $categories;
		
		$this->display();
	}
	
}
