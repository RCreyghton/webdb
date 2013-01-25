<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Handler class for the database. Contains a bunch of static helper methods.
 * Implements the Singleton pattern
 * 
 * @todo Controleren of die Singleton wel het beste is...
 */
class Helpers_Db {

	private $dbserver;
	private $dbname;
	private $dbuser;
	private $dbpass;
	
	private static $instance	= NULL;  //(Object-referene) to this object if there's a connection already
	private $mysqli;

	/**
	 * This static function is callable from everywhere?
	 * Determines whether there's already a mysqli-connection, 
	 * 
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 * @author Frank van Luijn <frank@accode.nl>
	 * @return Helpers_Db	Db-Object reference to a instatiation of this class with working mysqil conneciton
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
		$config = BASE . "../mysqlconfig.xml";
		if( ! is_file( $config ) ) {
			throw new Exception( "Could not load MySQL config file" );
		}
		
		$config = simplexml_load_file( $config );
		$this->dbserver = $config->host;
		$this->dbname = $config->db;
		$this->dbuser = $config->user;
		$this->dbpass = $config->pass;
		
		self::$instance = $this;

		if (!$this->connect()) {
			self::$instance = NULL;
			throw new Exception("Could not connect to database.");
		}
	}

	/**
	 * Connects to the DB using mysqli, using default settings
	 *
	 * Long discription to do
	 *
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function connect() {
		$this->mysqli = new mysqli(
						$this->dbserver,
						$this->dbuser,
						$this->dbpass,
						$this->dbname
		);

		if (mysqli_connect_errno()) {
			throw new Exception ("Connect error: " . mysqli_connect_error());
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

	public static function escape( $string ) {
		//make sure we've connected to the db
		return self::getInstance()->mysqli->real_escape_string( $string );
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
	 * @return mysqli_result
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public static function run($query) {
		return self::getInstance()->mysqli->query($query);
	}
	
	
	/**
	 * Returns a errormessage of the mysqli-object of the current Db Instance.
	 * 
	 * @author Frank van Luijn <frank@accode.nl>
	 * @return string|boolean	Error message: (id) error OR false
	 */
	public static function getError() {
		$no = self::getInstance()->mysqli->errno;
		$me = self::getInstance()->mysqli->error;
		return $no != 0 ? "({$no}) {$me}" : false;
	}
	
	
	/**
	 * Gets the id for a newly inserted record.
	 * 
	 * @return int	The primary index created by MySQL auto_increment, or 0.
	 */
	public static function getId() {
		return self::getInstance()->mysqli->insert_id;
	}

}