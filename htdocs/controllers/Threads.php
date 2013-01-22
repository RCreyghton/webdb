<?php


class Controllers_Threads extends Controllers_Base {
	
	//TODO checken!
	public function calsPagination ( $where ) {
		$page = 1; //params $_GET['page'];
		$pagesize = 25; //$start = parent::getInt("start", 0);
		
		$countquery = Models_Threads::getSelectCount() . $where;
		$nothreads = Models_Threads::getCount( $countquery ) ;
		
		$nopages = ceil( $nothreads / $nothreads );
		
		//Als een te hoge pagina is opgevraagd, geven we de laatste pagina weer.
		if ( $page > $nopages )
			$page = $nopages;
		
		$this->setParam("page", $page);
		$this->setParam("pagesize", $pagesize);
		$this->setParam("nopages", $nopages);
		$this->setParam("nothreads", $nothreads);
		
	}
	
	
	public function setupView( $query ) {
		$threads = Models_Thread::fetchByQuery( $query );
		
		$this->view->threads = $threads;
		//onderstaande automatiseren en in base zetten?
		$this->view->page = $this->params["page"];
		$this->view->pagesize = $this->params["pagesize"];
	}
	
	
	public function unanswered() {
	    $this->view = new Views_Threads_Unanswered();
	    $where = " WHERE ((`status` > 0) AND (`answer_id` IS NULL)) ORDER BY `ts_created` ASC";
		$this->calcPagination( $where );
		$limit = params["pagesize"];
		$offset = ( params["page"] - 1 ) * $limit;
		$query = Models_Thread::getSelect() . $where . " LIMIT {$offset}, {$limit}";
		$this->setupView( $query );
	    $this->display();
	}
	
	//public function categorythreads()
	//public function userthreads()
	
	//new
	//edit
	//save
	
}
