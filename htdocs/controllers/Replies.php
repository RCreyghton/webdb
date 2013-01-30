<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Controllers_Replies extends Controllers_Base {
		
	public function replyform () {
		if( $this->getString( "replyform_submit" ) ) {
			$result = $this->saveReply();
			
			//if succesfull, show the Registrationcomplete view
			//else show the form with the faulty entered data
			if( is_numeric( $result ) ) {
				$c = new Controllers_Threads();
				$c->setParam( "id" , $result );
				$c->execute( "single" );
				return;
			} elseif( is_array( $result ) ) {
				$this->view = new Views_Threads_Form();
				$this->view->form = $result;
			} else {
				//if the script reaches this point, something whent wrong
				//while saving the user
				$this->view = new Views_Error_Internal();
			}
		} else {
			$this->view = new Views_Threads_Form();
			$this->view->form = $this->getReplyForm();
		}
		
		$this->display();
	}
	
	private function saveReply() {
		$form = $this->getReplyForm();
		$failure = false;
		foreach( $form as $name => &$e ) {
			switch( $e['type']) {
				case 'select':
					$val = $this->getInt( $name );
					break;
				default:
					$val = $this->getString( $name );
			}
			
			if( empty( $val ) && $name != 'id' ) {
				$failure = true;
				$e[ 'errormessage' ] = 'Dit veld mag niet leeg zijn.';
			} else {
				$e ['value'] = $val;
			}
		}
		
		//any errors or user is not logged in
		if( $failure || ! Helpers_User::isLoggedIn() )
			return $form;
		
		
		
		die();
		
		$id =  $this->getInt('id');
		$user = Helpers_User::getLoggedIn();
		if( $id ) {
			$t = Models_Thread::fetchById ( $id );
			
			if($t->user_id != $user->id && $user->role != Models_User::ROLE_ADMIN) {
				//user does not own this thread!
				return $form;
			}
			$t->content .= "\n\n Bijgewerkt op " . date("d-m-Y", time() ) . ":\n";
		} else {
			$t				= new Models_Thread();
			$t->content		= '';
		}
		
		$t->user_id		= $user->id;
		$t->title		= $form ['title'] ['value'];
		$t->category_id = $form ['category'] ['value'];
		$t->content		.= $form ['content'] ['value'];
		$t->ts_created	= time();
		$t->status		= 1; //visibility on
		$t->answer_id	= "NULL";
		
		if( $t->save() ) {
			return $t->id;
		} else {
			return false;
		}
	}
	
	private function getReplyForm() {
		$elements = array();
		
		$elements[ 'id' ] = array(
			'type'			=>	'hidden',
			'description'	=>	''
		);
		
		$elements[ 'title' ] = array(
			'type'			=>	'text',
			'description'	=>	'Titel'
		);
		
		$elements[ 'category' ] = array(
			'type'			=>	'select',
			'description'	=>	'Categorie'
		);
		
		$query = Models_Category::getSelect() . " WHERE `status` = '1'";
		$cats = Models_Category::fetchByQuery($query);
		foreach( $cats as $c ) { 
			$elements[ 'category' ] [ 'values' ] [ $c->id ] = $c->name;
		};
		
		$elements[ 'content' ] = array(
			'type'			=>	'textarea',
			'description'	=>	'Uw vraag'
		);
		
		
		foreach( $elements as &$e ) {
			$e['value'] = ''; 
		}
		
		//now if it is an edit, load up all the known values
		$id = $this->getInt('id');
		$user = Helpers_User::getLoggedIn();
		if( $id && ( $id == $user->id || $user->role == Models_User::ROLE_ADMIN ) ) {
			$t = Models_Thread::fetchById( $id );
			$elements ['id']		['value'] = $t->id;
			$elements ['title']		['value'] = $t->title;
			$elements ['category']	['value'] = $t->category_id;
			$elements ['content']	['original'] = $t->content;
		}
		
		return $elements;
	}
}
