<?php

/*
 * DB handler
 * Handles most basic queries.
 *
 * ASSUMES WebDB Kaper's
 * fatalerror.inc.php as DbError.php
 */

include_once 'DbError.php';

class Helpers_Db {

	private $dbserver			= 'localhost'; //(string) server name
	private $dbname				= 'webdb13AD3';   //(string) database name
	private $dbuser				= 'webdb13AD3';   //(string) database user having writing rights
	private $dbpass				= 'justafio';   //(string) database password for writing user
	private static $instance	= NULL;  //(Object-referene) to this object if there's a connection already
	private $mysqli;

	/**
	 * This static function is callable from everywhere?
	 * Determines whether there's already a mysqli-connection, 
	 * 
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 * @return Object	Db-Object reference to a instatiation of this class with working mysqil conneciton
	 * @todo	Check whether the dbh and dbh->mysqli is indeed a fully working connection
	 */
	private static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Construct a default Helpers_Db, executes default connect()
	 *
	 * Long discription
	 *
	 * @author RCreyghton
	 */
	private function __construct() {
		$this->dbh = $this;

		if (!$this->connect()) {
			$this->dbh = NULL;
			throw new Exception("Could not connect to database.");
		}
	}

	/**
	 * Connects to the DB using mysqli, using default settings
	 *
	 * Long discription to do
	 *
	 * @author RCreyghton
	 */
	public function connect() {
		$this->mysqli = new mysqli(
						$this->dbserver,
						$this->dbuser,
						$this->dbpass,
						$this->dbname
		);

		if (mysqli_connect_errno()) {
			err_die("Connect error: " . mysqli_connect_error());
		}
		return TRUE;
	}

	/**
	 * Destructor of this class. Closes mysqli.
	 *
	 * @author RCreyghton
	 */
	public function __destruct() {
		@$this->mysqli->close();
	}

	/* Riped from webdb Voorbeeldcode.
	  Return a prepared statement (safe version)
	  On error, the script dies with a standard debug-message
	 */

	public function sprepare($sql = '', $show = FALSE) {
		if ($show)
			echo "\n" . $sql . "<br />\n";
		$stmt = $this->mysqli->prepare($sql);
		if (!$stmt)
			err_die(
					'Prepare error: ' . $this->mysqli->error .
					', <br />caused by this SQL: ' . $sql
			);
		return ($stmt) ? (new mystatement($stmt, $sql, $this->mysqli)) : (FALSE);
	}

	/**
	 * This method seems rather unsafe for sql-injection!
	 * 
	 * @param String $query a valid sql-query
	 * @return mysqli-result-object
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public static function run($query) {
		return self::getInstance()->mysqli->query($query);
	}

	public static function getError() {
		$no = self::getInstance()->mysqli->errno;
		$me = self::getInstance()->mysqli->err;
		return $no != 0 ? "({$no}) {$me}" : false;
	}

}