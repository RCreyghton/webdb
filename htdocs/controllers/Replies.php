<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Controllers_Replies extends Controllers_Base {

	/**
	 * Controls the replies-form. Lets the either make a new one, or edit the existing one.
	 * 
	 * @author Frank van Luijn <frank@accode.nl>
	 */
	public function replyform() {
		if ($this->getString("replyform_submit")) {
			$result = $this->saveReply();

			//if succesfull, show the Registrationcomplete view
			//else show the form with the faulty entered data
			if (is_numeric($result)) {
				$c = new Controllers_Threads();
				$c->setParam("id", $result);
				$c->execute("single");
				return;
			} elseif (is_array($result)) {
				$this->view = new Views_Replies_Form();
				$this->view->form = $result;
			} else {
				//if the script reaches this point, something whent wrong
				//while saving the user
				$this->view = new Views_Error_Internal();
			}
		} else {

			$threadid = $this->getInt('tid');
			if (!$threadid) {
				throw new Exception("No thread id specified");
			}
			$this->view = new Views_Replies_Form();
			$this->view->form = $this->getReplyForm();
		}

		$this->display();
	}

	/**
	 * Tries to save a new or edited Reply, based on the form-input, that is checked for consitency.
	 * 
	 * @author Frank van Luijn <frank@accode.nl>
	 */
	private function saveReply() {
		$form = $this->getReplyForm();
		$failure = false;
		foreach ($form as $name => &$e) {
			switch ($e['type']) {
				case 'hidden':
					$val = $this->getInt($name);
					break;
				default:
					$val = strip_tags(str_replace("<br/>", "\n", $this->getString($name)));
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
		$user = Helpers_User::getLoggedIn();
		if ($id) {
			$r = Models_Reply::fetchById($id);

			if ($r->user_id != $user->id && $user->role != Models_User::ROLE_ADMIN) {
				//user does not own this reply!
				return $form;
			}
			$r->content .= "\n\n\n Bijgewerkt op " . date("d-m-Y", time()) . ":\n -----------------------------------\n";
		} else {
			$r = new Models_Reply();
			$r->content = '';
		}

		$r->user_id = $user->id;
		$r->title = $form ['title'] ['value'];
		$r->content .= $form ['content'] ['value'];
		$r->ts_created = time();
		$r->visibility = 1; //visibility on by default
		$r->credits = 0; //visibility on by default
		$r->thread_id = $form ['tid'] ['value'];


		/**
		 * MUST RETURN THE THREAD ID!!!
		 */
		if ($r->save()) {
			return $r->thread_id;
		} else {
			return false;
		}
	}

	/**
	 * Assambles all the proporties needed to make {@link Views_Replies_Form} display the form needed.
	 * Here input fields an their defaults are defined.
	 * 
	 * @return string[]	Array with fields and values of input-types.
	 * @author Frank van Luijn <frank@accode.nl>
	 */
	private function getReplyForm() {
		$elements = array();

		$elements['id'] = array(
				'type' => 'hidden',
				'description' => ''
		);

		$elements['tid'] = array(
				'type' => 'hidden',
				'description' => ''
		);

		$elements['title'] = array(
				'type' => 'text',
				'description' => 'Titel'
		);

		$elements['content'] = array(
				'type' => 'textarea',
				'description' => 'Uw antwoord'
		);

		foreach ($elements as &$e) {
			$e['value'] = '';
		}

		//set the thread id
		$elements ['tid'] ['value'] = $this->getInt("tid");



		//now if it is an edit, load up all the known values
		$id = $this->getInt('id');
		if ($id)
			$reply = Models_Reply::fetchById($id);

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
