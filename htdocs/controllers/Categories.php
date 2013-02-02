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
		if ($user != NULL && $user->role == Models_User::ROLE_ADMIN) {
			$status = $this->getInt('status');
			$cat = $this->getInt('cat');
			//If both parameters are set, than we can fetch the Category and try to change it.
			if ($status != NULL && $cat != NULL) {
				$category = Models_Category::fetchById($cat);
				if ($category) {
					$category->status = $status;
					$category->save();
				}
			}
			//Also, admins must be able to view ALL categories.
			$where = "";
		}

		//Now we can acuatually fetch all categories and display them
		$query = Models_Category::getSelect() . $where . "ORDER BY `name`;";
		$categories = Models_Category::fetchByQuery($query);
		$this->view->categories = $categories;

		$this->display();
	}

	/**
	 * Controls the category-form. Lets the either make a new one, or edit the existing one.
	 * 
	 * @author Frank van Luijn <frank@accode.nl>
	 */
	public function categoryform() {
		$user = Helpers_User::getLoggedIn();
		if (!$user || $user->role != Models_User::ROLE_ADMIN) {
			$this->view = new Views_Errors_Notfound();
			$this->display();
			return;
		}

		if ($this->getString("categoryform_submit")) {
			$result = $this->saveCategory();

			//if succesfull, show the Registrationcomplete view
			//else show the form with the faulty entered data
			if (is_numeric($result)) {
				$c = new Controllers_Categories();
				$c->execute("overview");
				return;
			} elseif (is_array($result)) {
				$this->view = new Views_Categories_Form();
				$this->view->form = $result;
			} else {
				//if the script reaches this point, something whent wrong
				//while saving the user
				$this->view = new Views_Error_Internal();
			}
		} else {
			$this->view = new Views_Categories_Form();
			$this->view->form = $this->getCategoryForm();
		}

		$this->display();
	}

	/**
	 * Tries to save a new or edited category, based on the form-input, that is checked for consitency.
	 * 
	 * @author Frank van Luijn <frank@accode.nl>
	 */
	private function saveCategory() {
		$user = Helpers_User::getLoggedIn();

		if (!$user || $user->role != Models_User::ROLE_ADMIN) {
			return;
		}

		$form = $this->getCategoryForm();
		$failure = false;
		foreach ($form as $name => &$e) {
			switch ($e['type']) {
				case 'select':
					$val = $this->getInt($name);
					break;
				default:
					$val = strip_tags( str_replace("<br/>", "\n", $this->getString($name) ) );
			}

			if (empty($val) && $name != 'id') {
				$failure = true;
				$e['errormessage'] = 'Dit veld mag niet leeg zijn.';
			} else {
				$e ['value'] = $val;
			}
		}

		//any errors or user is not logged in
		if ($failure || !Helpers_User::isLoggedIn())
			return $form;

		
		$id = $this->getInt('id');
		if ($id) {
			$c = Models_Category::fetchById($id);
		} else {
			$c = new Models_Category();
		}

		$c->name = $form ['name'] ['value'];
		$c->description = $form ['description'] ['value'];

		if ($c->save()) {
			return $c->id;
		} else {
			return false;
		}
	}

	/**
	 * Assambles all the proporties needed to make {@link Views_Categories_Form} display the form needed.
	 * Here input fields an their defaults are defined.
	 * 
	 * @return string[]	Array with fields and values of input-types.
	 * @author Frank van Luijn <frank@accode.nl>
	 */
	private function getCategoryForm() {
		$elements = array();

		$elements['id'] = array(
			'type' => 'hidden',
			'description' => ''
		);

		$elements['name'] = array(
			'type' => 'text',
			'description' => 'Titel'
		);

		$elements['description'] = array(
			'type' => 'text',
			'description' => 'Omschrijving'
		);


		foreach ($elements as &$e) {
			$e['value'] = '';
		}

		//now if it is an edit, load up all the known values
		$id = $this->getInt('id');
		$user = Helpers_User::getLoggedIn();

		if ($id) {
			$t = Models_Category::fetchById($id);
			$elements ['id'] ['value'] = $t->id;
			$elements ['name'] ['value'] = $t->name;
			$elements ['description'] ['value'] = $t->description;
		}

		return $elements;
	}

}
