<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Controllers_Threads extends Controllers_Base {

	/**
	 * Calculatas all parameters needed to make a correct Pagination in a Threads-View.
	 * 
	 * @uses Controllers_Base::getInt()
	 * @uses Controllers_Base::setParam()
	 * @uses Models_Thread::getSelectCount()
	 * @uses Models_Thread::getCount()
	 * @param string $where The same WHERE-clause of the SQL query is responsible for fetching the acutals threads, is needed here to calculate the number of threads we are to distribute over this pagination.
	 */
	public function calcPagination($where) {
		$page = $this->getInt("p", 1);
		$pagesize = $this->getInt("ps", 25);

		$countquery = Models_Thread::getSelectCount() . $where;
		$nothreads = Models_Thread::getCount($countquery);

		$nopages = ceil($nothreads / $pagesize);

		//Als een te hoge pagina is opgevraagd, geven we de laatste pagina weer.
		if ($page > $nopages)
			$page = $nopages;

		$this->setParam("page", $page);
		$this->setParam("pagesize", $pagesize);
		$this->setParam("nopages", $nopages);
		$this->setParam("nothreads", $nothreads);
	}

	/**
	 * 
	 * @todo Al die parameters wat meer automatisch overzetten middels loopje.
	 * @uses Models_Thread::fetchByQuery()
	 * @param string $query The query wich will fetch our threads.
	 */
	public function setupView($query) {
		$threads = Models_Thread::fetchByQuery($query);

		$this->view->threads = $threads;
		//onderstaande automatiseren en in base zetten?

		$this->view->page = $this->params["page"];
		$this->view->pagesize = $this->params["pagesize"];
		$this->view->nopages = $this->params["nopages"];
		$this->view->nothreads = $this->params["nothreads"];
	}

	/**
	 * 
	 * @uses Models_Thread::getSelect()
	 * @uses calcPagination()
	 * @uses setupView()
	 */
	public function unanswered() {
		$this->view = new Views_Threads_Unanswered();
		$where = " WHERE ((`status` > 0) AND (`answer_id` IS NULL)) ORDER BY `ts_created` ASC";
		$this->calcPagination($where);
		$limit = $this->params["pagesize"];
		$offset = ( $this->params["page"] - 1 ) * $limit;
		$query = Models_Thread::getSelect() . $where . " LIMIT {$offset}, {$limit}";
		$this->setupView($query);
		$this->display();
	}

	//public function categorythreads()
	//public function userthreads()
	//new
	//edit
	//save
}
