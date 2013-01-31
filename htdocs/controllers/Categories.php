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
		
		//When not logged in as admin, you cannot see hidden items.
		$where = "WHERE `status` > '-1' ";
		
		//Get evantualities: is there some edititing to do on this user?
		//This is relevant for a logged-in admin only.
		$user = Helpers_User::getLoggedIn();
		if( $user != NULL && $user->role == Models_User::ROLE_ADMIN ) {
			$status = $this->getInt('status');
			$cat = $this->getInt('cat');
			//If both parameters are set, than we can fetch the Category and try to change it.
			if ( $status != NULL && $cat != NULL ) {
				$category = Models_Category::fetchById($cat);
				if ( $category ) {
					$category->status = $status;
					$category->save();
				}
			}
			//Also, admins must be able to view ALL categories.
			$where = "";
		}
		
		//Now we can acuatually fetch all categories and display them
		$query = Models_Category::getSelect() . $where . "ORDER BY `name`;";
		$categories = Models_Category::fetchByQuery( $query );
		$this->view->categories = $categories;
		
		$this->display();
	}
	
}
