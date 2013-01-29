<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Controllers_Search extends Controllers_Base {

	public function parseParts( $parts ) {
		parent::parseParts( $parts );
		if ( array_key_exists(2, $parts ))
			$this->setParam ("search", $parts[2]);
	}
	
	public function search() {
		$searchstring = $this->getString("search");
		$q = Helpers_Db::escape($searchstring);
		
		$this->view = new Views_Search_Result();
		
		$query = "SELECT * FROM 
(
SELECT threads.id AS thread_id, threads.category_id, threads.title, threads.content, threads.user_id, threads.ts_created, MATCH(threads.title, threads.content) AGAINST("test") AS score 
FROM threads 
WHERE MATCH(threads.title, threads.content) AGAINST("test") 
UNION ALL 
SELECT replies.thread_id AS thread_id, threads.title, threads.content, threads.user_id, threads.ts_created,, MATCH(replies.title, replies.content) AGAINST("test") AS score 
FROM replies 
WHERE MATCH(replies.title, replies.content) AGAINST("test")
) AS sub_query
GROUP BY thread_id
ORDER BY score DESC;";
		
		
		
		
		$posts = Models_Category::fetchByQuery( $query );
		$this->view->posts = $posts;
		$this->display();
	}
}