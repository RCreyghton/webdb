<?php


class Controllers_Threads extends Controllers_Base {
	
	const DEFAULTTASK = "unanswered";
	
	//TODO checken!
	public function listing () {
		
		//params $_GET['start'];
		$start = parent::getInt("start", 0);
		$end = $start + 25;
		
		$query = Models_Thread::getSelect() . " WHERE `status` > 0 LIMIT {$start}, {$end}";
		
		$threads = Models_Thread::fetchByQuery( $query );
		
		$view = new Views_Threads_Listing();
		$view->threads = $threads;
		$view->start = $start;
		$view->end = $end;
		$view->render();
	}
	
	//public function categorythreads()
	//public function userthreads()
	
	public function unanswered() {
	    $view = new Views_Threads_Unanswered();
	    parent::display($view);
	}
	
	//new
	//edit
	//save
	
}
