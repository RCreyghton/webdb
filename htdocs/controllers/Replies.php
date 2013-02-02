<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Controllers_Replies extends Controllers_Base {



		$user = Helpers_User::getLoggedIn();
		if ($id && ( $reply->user_id == $user->id || $user->role == Models_User::ROLE_ADMIN )) {
			$t = Models_Reply::fetchById($id);
			$elements ['id'] ['value'] = $t->id;
			$elements ['title'] ['value'] = $t->title;
			$elements ['content'] ['original'] = $t->content;
		}

		return $elements;
	}

}
