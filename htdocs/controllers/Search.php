<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Handler of the searches, both quick and full.
 * 
 * @author Ramon Creyghton <r.creyghton@gmail.com>
 */
class Controllers_Search extends Controllers_Base {

	/**
	 * In this controller, we need the search-string to be present, and passed on the the related views for display in titles.
	 * 
	 * @param mixed[] $parts
	 */
	public function parseParts($parts) {
		parent::parseParts($parts);
		if (array_key_exists(2, $parts))
			$this->setParam("search", $parts[2]);
	}

	/**
	 * Fetches all the threads related with the posts found relevant on the search string.
	 * 
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function quick() {
		$searchstring = urldecode($this->getString("search"));

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

WHERE ( threads.status = 1 )

ORDER BY ordering.thread_score DESC
LIMIT 10;";

		$result = Helpers_Db::run($query);
		if (!$result) {
			throw new Exception(Helpers_Db::getError());
		}

		$posts = array();

		while ($row = $result->fetch_row()) {
			$posts[] = $row;
		}

		$this->view->posts = $posts;
		//Niet diaplay, maar slechts render uit view zelf. Deze quick view kan het in zijn eentje af, returnt platte tekst.
		$this->view->render();
	}

	/**
	 * Fetches the replies AND threads related with this search string.
	 * 
	 * Need to group the results by thread(_id), and order those on the sum of the full-text-search scores, makes for a complicated SQL. However, this stil executes within reasonable time.
	 * 
	 * @uses Models_Base::fetchById()
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function full() {
		$searchstring = urldecode($this->getString("search"));
		$this->view = new Views_Search_Full();
		$this->view->title = $searchstring;

		//String already escaped in getString, but here after urlDecode, we do it again, for security.
		$q = Helpers_Db::escape($searchstring);

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
		if (!$result) {
			throw new Exception(Helpers_Db::getError());
		}

		//If the current user is not an admin, we will now filter the invisible results from the resultslist.
		$user = Helpers_User::getLoggedIn();
		$inv = false;
		if ($user && $user->role == Models_User::ROLE_ADMIN)
			$inv = true;

		//The array with posts and threads-object we're going to assamble.
		$posts = array();

		$threadid = 0;
		while ($row = $result->fetch_assoc()) {
			if ($row['thread_id'] != $threadid) {
				$threadid = $row['thread_id'];
				$thread = Models_Thread::fetchById($threadid);
				//If the thread is invisible and we ought to hide it, skip saving.
				if ($inv || $thread->status == 1)
					$posts[] = $thread;
			}
			if ($inv || $thread->status == 1)
				$posts[] = $row;
		}

		$this->view->posts = $posts;
		$this->display();
	}

}
