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
		$this->calcPagination($where);
		
		$orderarray = explode("_", $this->getString( "order", $defaultorder) );
		$orderparsed = $this->parseOrder( $orderarray[0] );
		//De gebruiker kan een niet valide order-string hebben meegegeven. In dat geval moeten we alnog de default gebruiken.
		if ( $orderparsed == NULL ) {
			$orderarray = explode("_", $defaultorder );
			$orderparsed = $this->parseOrder( $orderarray[0] );
		}
		$order = "`" . $orderparsed . "`";
		if ( count($orderarray) > 1 && $orderarray[1] == "d" )
			$order .= " DESC";
		
		$limit = $this->params["pagesize"];
		$offset = ( $this->params["page"] - 1 ) * $limit;
		$query = Models_Thread::getSelect() . $where . " ORDER BY " . $order . " LIMIT {$offset}, {$limit}";
		$threads = Models_Thread::fetchByQuery($query);
		$this->view->threads = $threads;

		/* Onderstaande functionaliteit in methode in view geplaatst...
		foreach ($this->params as $key => $value) {
			if ( array_key_exists( $key, get_object_vars( $this->view )) ) {
				$this->view->$key = $value;
			}
		}
		 * Het inladen gebeurt nu met loadParams, dat wordt aangeroepen in display();
		 */
		
		//now we can safely execute the view's specific render function, calling it via $this->display()
		$this->display();
	}

	/**
	 * 
	 * @uses setupView()
	 */
	public function unanswered() {
		$this->view = new Views_Threads_Unanswered();
		$where = " WHERE ((`status` > 0) AND (`answer_id` IS NULL))";
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
			if( $thread->user_id == $user->id || $user->role == Models_User::ROLE_ADMIN ) {
				$thread->answer_id = $accept;
				$thread->save();
			}
		}
		
		if( $deaccept ) {
			$user = Helpers_User::getLoggedIn();
			if( $thread->user_id == $user->id || $user->role == Models_User::ROLE_ADMIN ) {
				$thread->answer_id = NULL;
				$thread->save();
			}
		}
		
		//load the view
		$this->view = new Views_Threads_Single();
		$this->view->thread = $thread;
		$this->view->replies = $thread->getForeignModels( 'Models_Reply' );
		
		$this->display();
	}
}
