<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Another Base class. This one contains fields and methods that all threads-listing views need, such as pagination and limited content printing.
 */
abstract class Views_Threads_Base extends Views_Base {

	public $threads;
	public $page;
	public $pagesize;
	public $nopages;
	public $nothreads;

	/**
	 * Renders the contents of this view, by parsing the $threads-array made by {@link Controllers_Threads::$task()}
	 * 
	 * @uses	Models_Base::fetchById()
	 * @uses	Models_Base::getForeignCount()
	 * @author	Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function printThreads() {
		foreach ($this->threads as $thread) {
			$user = Models_User::fetchById($thread->user_id);
			$category = Models_Category::fetchById($thread->category_id);
			$noReplies = $thread->getForeignCount("Models_Reply");
			echo "<div class=\"element\">\n<h3>{$thread->title}</h3>\n";
			echo "<p><span>Gepost op " . strftime('%A %d %B %Y, %R', $thread->ts_created) . " door {$user->firstname} {$user->lastname}</span><br />\n";
			echo "<span>in de categorie <em>{$category->name}</em>. Deze thread heeft {$noReplies} repl";
			echo ($noReplies == 1) ? "y" : "ies";
			echo ".</span></p>\n<p>";
			echo ( strlen($thread->content) > 250) ?
							substr($thread->content, 0, 250) . "..." :
							$thread->content;
			echo "</p>\n</div>\n";
		}
	}

	/**
	 * Dynamically creates a pagination and echo's it.
	 */
	public function printPagination() {
		echo "<div class=\"pagination\">";
		for ($i = 1; $i <= $this->nopages; $i++) {
			if ($i == $this->page)
				echo "<em>{$i}</em>";
			else
				echo "<a href=\"" . $this->getUrl("threads", "unanswered") . "?p={$i}&ps={$this->pagesize}\">$i</a>"; //nog hardcoded unanswered url
		}
		echo "</div>";
	}

}
