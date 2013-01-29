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
	
	public $id;
	public $order;
	
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
		if ( empty($this->threads) ) {
			echo "\n					<h3>Er zijn geen threads om weer te geven</h3>\n";
		} else {
			foreach ($this->threads as $thread) {
				$user = Models_User::fetchById($thread->user_id);
				$category = Models_Category::fetchById($thread->category_id);
				$noReplies = $thread->getForeignCount("Models_Reply");
				echo "\n					<div class='element'>\n						<h3>{$thread->title}</h3>\n";
				echo "<p><span>Gepost op " . date("d-m-Y H:i", $thread->ts_created) . " door ";
				echo "{$user->firstname} {$user->lastname}</span><br />\n";
				echo "<span>in de categorie <em>{$category->name}</em>. Deze thread heeft {$noReplies} repl";
				echo ($noReplies == 1) ? "y" : "ies";
				echo ".</span></p>\n<p>";
				echo ( strlen($thread->content) > 250) ?
								substr($thread->content, 0, 250) . "..." :
								$thread->content;
				echo "</p>\n";
				echo "						<a href='./threads/single/{$thread->id}'>Bekijk deze vraag</a>\n";
				echo "					</div>\n";
			}
		}
	}

	/**
	 * Dynamically creates a pagination and echo's it.
	 * Calles printSorting, since every paginated view needs sorting as well.
	 */
	public function printPagination() {
		echo "\n					<div class=\"pagination\">\n						";
		for ($i = 1; $i <= $this->nopages; $i++) {
			if ($i == $this->page)
				echo "<em>{$i}</em>";
			else
				echo "<a href=\"" . $this->getUrl("order") . "/" . $i . "/" . $this->pagesize . "\">$i</a>";
		}
		echo "\n					</div>\n";
		$this->printSorting();
	}

	/**
	 * Dymaically creates the Sorting-box for this page and echo's it.
	 */
	public function printSorting() {
		echo "\n					<div class=\"sorting\"><table border=\"0\">
						<tr><td>Sorteren op</td></tr>
						<tr class=\"" . (($this->order == "views_a") ? "active" : "") . "\"><td><a href=\"{$this->getUrl("id")}/views_a/1/{$this->pagesize}\">Veelbekeken</a></td></tr>
						<tr class=\"" . (($this->order == "views_d") ? "active" : "") . "\"><td><a href=\"{$this->getUrl("id")}/views_d/1/{$this->pagesize}\">Weinigbekeken</a></td></tr>
						<tr class=\"" . (($this->order == "date_a") ? "active" : "") . "\"><td><a href=\"{$this->getUrl("id")}/date_a/1/{$this->pagesize}\">Oudste</a></td></tr>
						<tr class=\"" . (($this->order == "date_d") ? "active" : "") . "\"><td><a href=\"{$this->getUrl("id")}/date_d/1/{$this->pagesize}\">Nieuwste</a></td></tr>
					</table></div>\n\n";
	}

	/**
	 * Returns a string like "pagina 1 van 3" or null if just 1 page.
	 * @return string|null	
	 */
	public function printHead() {
		echo "					<h2>" . $this->title . ( ($this->nopages > 1) ? " - pagina {$this->page} van {$this->nopages}" : "" ) . "</h2>\n";
	}
	
	/**
	 * Returns the URL for this thread-view with current order and id. Pagination left out (or: to be filled in).
	 * 
	 * @return type
	 */
	public function getURL($detail = "order") {
		$url = parent::getURL() . "/" . $this->id ;
		if ($detail == "id")
			return $url;
		$url .= "/" . $this->order;
		if ($detail == "order")
			return $url;
		$url .= "/" . $this->page . "/" . $this->pagesize ;
		if ($detail == "page")
			return $url;
		return parent::getURL();
	}

}
