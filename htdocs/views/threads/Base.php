<?php

abstract class Views_Threads_Base extends Views_Base {
	
	public $threads;
	public $page;
	public $pagesize;
	public $nopages;
	public $nothreads;
	
	
	/**
	 * Renders the contents of this view, by parsing the $threads-array made by Controllers_Threads->$task()
	 * 
	 * @uses	Models_Base::fetchById()
	 * @uses	Models_Base->getForeignCount()
	 * @author	Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function printThreads() {
		foreach ($this->threads as $thread) {
			$user = Models_User::fetchById( $thread->user_id );
			$category = Models_Category::fetchById( $thread->category_id );
			$noReplies = $thread->getForeignCount( "Models_Reply" );
			echo "<div class=\"element\">\n<h3>{$thread->title}</h3>\n";
			echo "<p><span>Gepost op " . strftime('%A %d %B %Y, %R', $thread->ts_created) . " door {$user->firstname} {$user->lastname}</span><br />\n";
			echo "in de categorie <em>{$category->name}</em>. Deze thread heeft {$noReplies} replie";
			echo ($noReplies == 1) ? "" : "s" ;
			echo ".</span></p>\n<p>";
			echo ( strlen($thread->content) > 50) ?
				substr($thread->content, 0, 50) . "..." :
				$thread->content;
			echo "</p>\n</div>\n";
		}
	}
	
	public function printPagination() {
		echo "<div class=\"pagination\">";
		for ($i = 1; $i <= $this->nopages; $i++) {
			if ($i == $this->page)
				echo "<em>{$i}</em>";
			else
				echo "<a href=\"" . getUrl("threads","unanswered") . "?p={$i}&ps={$this->pagesize}\">$i</a>"; //nog hardcoded unanswered url
		}
		echo "</div>";
	}
	
}
