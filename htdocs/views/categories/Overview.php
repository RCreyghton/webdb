<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Produces a list of categories. Possibly adding admin-options like hiding, opening and restricting categories.
 * 
 * @author Ramon Creyghton <r.creyghton@gmail.com>
 */
class Views_Categories_Overview extends Views_Base {

	/**
	 * Array met alle category-objecten. 
	 * 
	 * @var Models_Category[]
	 */
	public $categories;

	/**
	 * Rendering the Categories_Overview view.
	 * 
	 * @uses Models_Base::getForeignCount()
	 */
	public function render() {
		$this->title = "Alle categorieen";
		$user = Helpers_User::getLoggedIn();
		$user_id = $user ? $user->id : 0;
		$user_role = $user ? $user->role : 0;
		$admin = false;
		if ($user_role == Models_User::ROLE_ADMIN) {
			$admin = true;
			echo "<a class='threads_add_thread' href='./categories/categoryform'>Nieuwe categorie</a>";
		}


		echo "<h2>Alle categorieen:</h2>\n";

		foreach ($this->categories as $c) {
			$noThreads = $c->getForeignCount("Models_Thread");

			echo "					<div class=\"categories_overview_container\">\n";

			//start actionbar, if admin only.
			if ($admin) {
				$status = "categories_overview_statusopen";
				if ($c->status < 0)
					$status = "categories_overview_statusblocked";
				if ($c->status == 0)
					$status = "categories_overview_statusrestricted";

				echo "						<div class='categories_overview_actionbar {$status}'>";

				if ($c->status == 1)
					echo "<a href='./categories/overview/?status=0&cat={$c->id}'><img src='./assets/images/icons/16x16/accept.png' width='16' height='16' alt='restrict' title='Huidige status: open. Klik om te restricten.' /></a>";
				else
					echo "<a href='./categories/overview/?status=1&cat={$c->id}'><img src='./assets/images/icons/16x16/delete.png' width='16' height='16' alt='open' title='Huidige status: restricted. Klik om te openen.' /></a>";
				if ($c->status == -1)
					echo "<a href='./categories/overview/?status=0&cat={$c->id}'><img src='./assets/images/icons/16x16/block.png' width='16' height='16' alt='hide' title='Categorie nu verborgen. Klik hier om zichtbaar te maken.' /></a>";
				else
					echo "<a href='./categories/overview/?status=-1&cat={$c->id}'><img src='./assets/images/icons/16x16/accept.png' width='16' height='16' alt='hide' title='Categorie nu zichtbaar. Klik hier om te verbergen.' /></a>";
				echo "<a href='./categories/categoryform/?id={$c->id}'><img src='./assets/images/icons/16x16/application_edit.png' width='16' height='16' alt='bewerk' title='Bewerk titel en omsschrijving.' /></a>";

				echo "</div>\n";
				//end actionbar
			}

			echo "						<h3 class='categories_overview_header'><a href=\"./threads/category/{$c->id}\" class='headerlink'>{$c->name}</a></h3>
						<p class='categories_overview_content'>{$c->description} | {$noThreads} vra" . ($noThreads == 1 ? "ag" : "gen") . " in deze categorie.</p>
					</div>\n";
		}
	}

}
