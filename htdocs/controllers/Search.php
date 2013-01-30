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
	
	public function quick() {
		$searchstring = str_replace("~", " ", $this->getString("search") );
		
		$q = Helpers_Db::escape($searchstring);
		
		$this->view = new Views_Search_Quick();
		
		$query = "SELECT threads.id, threads.title, ordering.thread_score
FROM threads
JOIN 
(
SELECT thread_id, SUM( score ) as thread_score
FROM
(
SELECT threads.id AS thread_id,
MATCH ( threads.title, threads.content ) AGAINST ( '$q' ) AS score
FROM threads
WHERE MATCH ( threads.title, threads.content ) AGAINST ( '$q' )

UNION ALL

SELECT replies.thread_id AS thread_id,
MATCH ( replies.title, replies.content ) AGAINST ( '$q' ) AS score
FROM replies
WHERE MATCH ( replies.title, replies.content ) AGAINST ( '$q' )
) AS matches

GROUP BY thread_id
) AS ordering

ON ( ordering.thread_id = threads.id )

ORDER BY ordering.thread_score DESC;";
		
		$result = Helpers_Db::run($query);
		if ( ! $result ) {
			throw new Exception( Helpers_Db::getError() );
		}
		
		$posts = array();
		
		$threadid = 0;
		while ( $row = $result->fetch_row() ) {
			$posts[] = $row;
		}
		
		$this->view->posts = $posts;
		//Niet siaplay, maar slechts render uit view zelf. Deze quick view kan het in zijn eentje af, returnt platte tekst.
		$this->view->render();	
	}
	
	public function full() {
		$searchstring = str_replace("~", " ", $this->getString("search") );
		
		$q = Helpers_Db::escape($searchstring);
		
		$this->view = new Views_Search_Full();
		
		$query = "SELECT records.*, ordering.thread_score
FROM 
(
SELECT threads.id AS thread_id, - threads.id AS post_id, threads.title, threads.content, threads.user_id, threads.ts_created,
MATCH ( threads.title, threads.content ) AGAINST ( '$q' ) AS score
FROM threads
WHERE MATCH ( threads.title, threads.content ) AGAINST ( '$q' )

UNION ALL

SELECT replies.thread_id AS thread_id, replies.id AS post_id, replies.title, replies.content, replies.user_id, replies.ts_created,
MATCH ( replies.title, replies.content ) AGAINST ( '$q' ) AS score
FROM replies
WHERE MATCH ( replies.title, replies.content ) AGAINST ( '$q' )
) AS records

JOIN

(
SELECT thread_id, SUM( score ) AS thread_score
FROM 
(

SELECT threads.id AS thread_id,
MATCH ( threads.title, threads.content ) AGAINST ( '$q' ) AS score
FROM threads
WHERE MATCH ( threads.title, threads.content ) AGAINST ( '$q' )

UNION ALL

SELECT replies.thread_id AS thread_id,
MATCH ( replies.title, replies.content ) AGAINST ( '$q' ) AS score
FROM replies
WHERE MATCH ( replies.title, replies.content ) AGAINST ( '$q' )

) AS matching

GROUP BY thread_id
) AS ordering

ON records.thread_id = ordering.thread_id

ORDER BY thread_score DESC, score DESC;";
		
		$result = Helpers_Db::run($query);
		if ( ! $result ) {
			throw new Exception( Helpers_Db::getError() );
		}
		
		$posts = array();
		
		$threadid = 0;
		while ( $row = $result->fetch_assoc() ) {
			if ( $row['thread_id'] != $threadid ) {
				$threadid = $row['thread_id'];
				$thread = Models_Thread::fetchById($threadid);
				$posts[] = $thread;
			}
			$posts[] = $row;
		}
		
		$this->view->posts = $posts;
		$this->display();
	}
}