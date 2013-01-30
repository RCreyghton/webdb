<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Views_Search_Full extends Views_Base {

	public $posts;
	
	public function render() {
		$this->title = "Zoeken op " . $this->title;
		$i = 0;
		while ( isset( $this->posts[$i] ) ) {
			$thread = $this->posts[$i];
			echo "					<div class='element'>\n						<h3>{$thread->title}</h3>\n";
			$i++;
			while ( isset( $this->posts[$i] ) && ! $this->posts[$i] instanceof Models_Thread ) {
				$post = $this->posts[$i];
				echo "						<div class='subcontent'>Relevante " . ( ($post["post_id"] == - $thread->id ) ? " inhoud" : "reactie" ) . ":
							<h4>{$post['title']}</h4>
							<p>{$post['content']}</p>
						</div>\n"; 
				$i++;
			}
			echo "					</div>\n";
		}
	}
	
}