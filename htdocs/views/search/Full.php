<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Displays the full search result. In the
 * 
 * In the $posts-array, all threads are represented as a {@link Models_Thread}-object, and the actual search-matches follow as some string-arrays.
 * This parses them all and groups threads and its contents in a div. Grabs the user and category for each thread as well.
 * 
 * @uses Models_Base::fetchById()
 * @author Ramon Creyghton <r.creyghton@gmail.com>
 */
class Views_Search_Full extends Views_Base {

	public $posts;
	
	public function render() {
		$this->title = "Zoeken op: {$this->title}";
		echo "					<h2>{$this->title}</h2>\n";
		if ( empty( $this->posts ) )
			echo "						<p>Helaas, geen resultaten.</p>";
		
		$i = 0;
		while ( isset( $this->posts[$i] ) ) {
			$thread = $this->posts[$i];
			$user = Models_User::fetchById($thread->user_id);
			$category = Models_Category::fetchById($thread->category_id);
			echo "					<div class='search_listing_thread_container'>\n						<h3 class='search_listing_thread_header'><a href='./threads/single/{$thread->id}' class='headerlink'>{$thread->title}</a></h3>\n";
			echo "						<div class='search_listing_thread_details'>\n							<p>" . date("d-m-Y H:i", $thread->ts_created) . " in <em>{$category->name}</em></p>\n";
			echo "							<p>{$user->firstname} {$user->lastname}</p>\n						</div>\n";
			$i++;
			while ( isset( $this->posts[$i] ) && ! $this->posts[$i] instanceof Models_Thread ) {
				$post = $this->posts[$i];
				echo "						<div class='search_listing_content_container'>
							<h4>Relevante " . ( ($post["post_id"] == - $thread->id ) ? " inhoud" : "reactie" ) . ": {$post['title']}</h4>
							<p>{$post['content']}</p>
						</div>\n"; 
				$i++;
			}
			echo "					</div>\n";
		}
	}
	
}