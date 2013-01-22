<?php


class Controllers_Threads extends Controllers_Base {
	
	const DEFAULTTASK = "unanswered";
	public $start;
	public $end;
	
	//TODO checken!
	public function getLimits () {
		
		//params $_GET['start'];
		$start = parent::getInt("start", 0);
		$end = $start + 25;
		$view;
	}
	
	
	public function setupView( $query ) {
		$threads = Models_Thread::fetchByQuery( $query );
		
		$this->view->threads = $threads;
		$this->view->start = $this->$start;
		$this->view->end = $this->$end;
	}
	
	
	public function unanswered() {
	    $this->view = new Views_Threads_Unanswered();
			$this->getLimits();
			$query = Models_Thread::getSelect() . " WHERE ((`status` > 1) AND (`answer_id`  = NULL)) ORDER BY `ts_created` ASC LIMIT {$this->start}, {$this->end}";
			$this->setupView( $query );
	    parent::display($this->view);
	}
	
	//public function categorythreads()
	//public function userthreads()
	
	//new
	//edit
	//save
	
}
