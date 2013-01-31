<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Controllers_Threads extends Controllers_Base {
	
	public function parseParts( $parts ) {
		parent::parseParts( $parts );
		if ( array_key_exists(2, $parts ))
			$this->setParam ("id", $parts[2]);
		if ( array_key_exists(3, $parts ))
			$this->setParam ("order", $parts[3]);
		if ( array_key_exists(4, $parts ))
			$this->setParam ("p", $parts[4]);
		if ( array_key_exists(5, $parts ))
			$this->setParam ("ps", $parts[5]);
	}
	

	/**
	 * Calculatas all parameters needed to make a correct Pagination in a Threads-View.
	 * 
	 * @uses Controllers_Base::getInt()
	 * @uses Controllers_Base::setParam()
	 * @uses Models_Thread::getSelectCount()
	 * @uses Models_Thread::getCount()
	 * @param string $where The same WHERE-clause of the SQL query is responsible for fetching the acutals threads, is needed here to calculate the number of threads we are to distribute over this pagination.
	 */
	private function calcPagination($where) {
		$page = $this->getInt("p", 1);
		$pagesize = $this->getInt("ps", 25);

		$countquery = Models_Thread::getSelectCount() . $where;
		$nothreads = Models_Thread::getCount($countquery);
		
		//Pagination should not give negative results when there are no threads to display.
		$nopages = ( $nothreads > 0 ) ? ceil($nothreads / $pagesize) : 1;

		//Limit the page number to the heighest possible page.
		if ($page > $nopages)
			$page = $nopages;

		$this->setParam("page", $page);
		$this->setParam("pagesize", $pagesize);
		$this->setParam("nopages", $nopages);
		$this->setParam("nothreads", $nothreads);
	}


	/**	
 * Allowing the user to influence the ordering of the threads, and thus the SQL-query, introduces a need for rigorous parsing and checking, to avoid SQL-injection.
	 * 
	 * @param string $orderstring
	 * @return string|null
	 */
	private function parseOrder( $orderstring ) {
		switch ($orderstring) {
			case "views":
				return "views";
				break;
			case "date":
				return "ts_created";
				break;
			default:
				return NULL;
				break;
		}
	}
	
	/**
	 * 
	 * @uses Models_Thread::getSelect()
	 * @uses calcPagination()
	 * @todo Al die parameters wat meer automatisch overzetten middels loopje.
	 * @uses Models_Thread::fetchByQuery()
	 * @param string $where The where-clause of the query wich will fetch our threads.
	 */
	private function setupView( $where , $defaultorder) {
		
		//as this is the base view for mulitple threadlistings, we'll handle thread actions here
		//only if admin
		$user = Helpers_User::getLoggedIn();
		if( $user && $user->role == Models_User::ROLE_ADMIN) {
			if( $this->getInt('hide_thread') ) {
				$t = Models_Thread::fetchById( $this->getInt('hide_thread') );
				if( $t ) {
					$t->status = Models_Thread::INVISIBLE;
					$t->save();
				}
			}
			
			if( $this->getInt('unhide_thread') ) {
				$t = Models_Thread::fetchById( $this->getInt('unhide_thread') );
				if( $t ) {
					$t->status = Models_Thread::VISIBLE;
					$t->save();
				}
			}
			
			if( $this->getInt('open_thread') ) {
				$t = Models_Thread::fetchById( $this->getInt('open_thread') );
				if( $t ) {
					$t->open = Models_Thread::OPEN;
					$t->save();
				}
			}
			
			if( $this->getInt('close_thread') ) {
				$t = Models_Thread::fetchById( $this->getInt('close_thread') );
				if( $t ) {
					$t->open = Models_Thread::CLOSED;
					$t->save();
				}
			}
		}
		
		
		$this->calcPagination($where);
		
		//An ordering for these threads could be given as string in many places, or default from the task.
		$orderparam = $this->getString( "order", $defaultorder);
		//Put it back in param's list, so we're sure the view will get it.
		$this->setParam("order", $orderparam);
		//Now we can parse it.
		$orderarray = explode("_", $orderparam );
		$orderparsed = $this->parseOrder( $orderarray[0] );
		//De gebruiker kan een niet valide order-string hebben meegegeven. In dat geval moeten we alnog de default gebruiken.
		if ( $orderparsed == NULL ) {
			$this->setParam("order", $defaultorder);
			$orderarray = explode("_", $defaultorder );
			$orderparsed = $this->parseOrder( $orderarray[0] );
		}
		
		//Now we can work it, to be ready for an SQL-query.
		$order = "`" . $orderparsed . "`";
		if ( count($orderarray) > 1 && $orderarray[1] == "d" )
			$order .= " DESC";
		
		//In addition we need the pagination in the SQL.
		$limit = $this->params["pagesize"];
		$offset = ( $this->params["page"] - 1 ) * $limit;
		
		//Now we can assable the query, excecute it, put the results in the view and display the view. 
		$query = Models_Thread::getSelect() . $where . " ORDER BY " . $order . " LIMIT {$offset}, {$limit}";
		$threads = Models_Thread::fetchByQuery($query);
		$this->view->threads = $threads;
		
		//now we can safely execute the view's specific render function, calling it via $this->display(). This will load the param's list in the view as well.
		$this->display();
	}

	/**
	 * 
	 * @uses setupView()
	 */
	public function unanswered() {
		$this->view = new Views_Threads_Unanswered();
		//We need a dummy id to be able to have uniform pagination and ordering functions
		$this->setParam("id", 0);
		$where = " WHERE ((`status` = " . Models_Thread::VISIBLE . ") AND (`answer_id` IS NULL))";
		$defaultorder = "views_d";
		$this->setupView( $where, $defaultorder );
	}
	
	/**
	 * 
	 * @uses setupView()
	 */
	public function invisible() {
		$u = Helpers_User::getLoggedIn();
		
		//only allow admins
		if( $u->role != Models_User::ROLE_ADMIN ) {
			$this->view = new Views_Error_Internal();
			$this->display();
			return;
		}
		
		$this->view = new Views_Threads_Invisible();
		$where = " WHERE `status` = '" . Models_Thread::INVISIBLE . "'";
		$defaultorder = "date_a";
		$this->setupView( $where, $defaultorder );
	}

	/**
	 * 
	 * @uses setupView()
	 */
	public function category() {
		$this->view = new Views_Threads_Category();
		$category_id = $this->getInt("id");
		if ($category_id == NULL)
			throw new Exception ("Geen categorie opgegeven.");
		$category = Models_Category::fetchById($category_id);
		if ($category == NULL)
			throw new Exception ("Deze categorie bestaat niet op deze site.");
		
		$this->setParam("category", $category);
		
		$where = " WHERE ((`status` > 0) AND (`category_id` = {$category_id}))";
		$defaultorder = "views_d";
		$this->setupView( $where, $defaultorder );
	}

	public function user() {
		$this->view = new Views_Threads_User();
		$user_id = $this->getInt("id");
		if ($user_id == NULL)
			throw new Exception ("Geen user opgegeven.");
		$user = Models_User::fetchById($user_id);
		if ($user == NULL)
			throw new Exception ("Deze user bestaat niet op deze site.");
		
		$this->setParam("user", $user);
		
		$where = " WHERE ((`status` > 0) AND (`user_id` = {$user_id}))";
		$defaultorder = "date_a";
		$this->setupView( $where, $defaultorder );
	}
	
	public function single() {
		$threadId = $this->getInt("id");
		if( ! $threadId ||  ! Models_Thread::exists( $threadId ) ) {
			throw new Exception( "Unkown thread!" );
		}
		
		
		//load the thread
		$thread = Models_Thread::fetchById( $threadId );
		$thread->loadConnections();
		
		//perform accept and deaccept actions
		$accept = $this->getInt('accept');
		$deaccept = $this->getInt('deaccept');
		
		if( $accept ) {
			$user = Helpers_User::getLoggedIn();
			if( $user != NULL && ( $thread->user_id == $user->id || $user->role == Models_User::ROLE_ADMIN ) ) {
				$thread->answer_id = $accept;
				$thread->save();
			}
		}
		
		if( $deaccept ) {
			$user = Helpers_User::getLoggedIn();
			if( $user != NULL && ( $thread->user_id == $user->id || $user->role == Models_User::ROLE_ADMIN ) ) {
				$thread->answer_id = 'NULL';
				$thread->save();
				$thread->answer_id = NULL; //ugly i know....
			}
		}
		
		//load the view
		$this->view = new Views_Threads_Single();
		$this->view->thread = $thread;
		
		
		$replies = $thread->getForeignModels( 'Models_Reply' );
		if( $thread->answer_id ) {
			$answer = Models_Reply::fetchById( $thread->answer_id );
			foreach( $replies as $key => $r ) {
				if( $r->id == $answer->id ) {
					unset( $replies[$key] );
				}
			}
			$replies = array_merge( array( $answer), $replies );
		}
		
		$this->view->replies = $replies;
		
		//update the number of views
		if( ! $thread->answer_id )
			$thread->answer_id = "NULL";
		
		$thread->views++;
		$thread->save();
		
		$this->display();
	}
	
	public function threadform () {
		if( $this->getString( "threadform_submit" ) ) {
			$result = $this->saveThread();
			
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
			$this->view->form = $this->getThreadForm();
		}
		
		$this->display();
	}
	
	private function saveThread() {
		$form = $this->getThreadForm();
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
		
		$id =  $this->getInt('id');
		$user = Helpers_User::getLoggedIn();
		
		if( $id ) {
			$t = Models_Thread::fetchById ( $id );
			
			if($t->user_id != $user->id && $user->role != Models_User::ROLE_ADMIN) {
				//user does not own this thread!
				return $form;
			}
			$t->content .= "\n\n\n Bijgewerkt op " . date("d-m-Y", time() ) . ":\n -----------------------------------\n";
		} else {
			$t				= new Models_Thread();
			$t->content		= '';
		}
		
		$t->user_id		= $user->id;
		$t->title		= $form ['title'] ['value'];
		$t->category_id = $form ['category'] ['value'];
		$t->content		.= $form ['content'] ['value'];
		$t->ts_created	= time();
		$t->answer_id	= "NULL";
		
		//A new or edited threads inherits its status from its category, of 0 if something wrong with status.
		$cat = Models_Category::fetchById($t->category_id);
		$t->status = ( $cat->status ) ? $cat->status : 0;
		
		if( $t->save() ) {
			return $t->id;
		} else {
			return false;
		}
	}
	
	private function getThreadForm() {
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
		
		$query = Models_Category::getSelect() . " WHERE `status` > '-1'";
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
		
		if( $id )
			$thread = Models_Thread::fetchById( $id );
		
		if( $id && ( $thread->user_id == $user->id || $user->role == Models_User::ROLE_ADMIN ) ) {
			$t = Models_Thread::fetchById( $id );
			$elements ['id']		['value'] = $t->id;
			$elements ['title']		['value'] = $t->title;
			$elements ['category']	['value'] = $t->category_id;
			$elements ['content']	['original'] = $t->content;
		}
		
		return $elements;
	}
}
