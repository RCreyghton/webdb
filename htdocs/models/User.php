<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * User-class with fiels en methods to make and display Users, and get all DB-manipulations they've done on this site (i.e.: Threads, Replies, Credits...)
 * 
 * @author Shafiq Ahmadi <s.ah@live.nl>
 * @author Ramon Creyghton <r.creyghton@gmail.com>
 */
class Models_Users extends Models_Base {
		/**
		 * @var string Name of the DB-table corresponding with this class.
		 */
    const TABLENAME = "users";
		/**
		 * @var int	Unique ID of this User-object, auto_incremented in the DB.
		 */
    public $id;
		/**#$+
		 * @var string 
		 */
    public $nick;
    public $pass;
    public $emails;
    public $ts_registered;
		/**#@-*/
		/**
		 * @var int 0: anonymous; 1 user; 2 admin;
		 */
    public $role;
		/**#@+
		 * @var string
		 */
    public $firstname;
    public $lastname;
		public $ipaddress;
    /**#@-*/

		
	/**
	 * Names of the relevant fields of this object, the must correspond with the
	 * column-names of the associated table in the database.
	 * 
	 * @author Frank van Luijn <frank@accode.nl>
	 * @author Shafiq Ahmadi <s.ah@live.nl>
	 * @return string[] Array with the names of all relevant fields exept id in this object
	 */
    public function declareFields() {
        $fields = array(
            "id",
            "nick",
            "pass",
            "email",
            "ts_registered",
            "role",
            "fistname",
            "lastname",
						"ipaddress"
        );
        return $fields;
    }
   
	
	/**
	 * gets an array of Thread-objects by from this user from the DB.
	 *
	 * @return Models_Thread[] array of Thread-objects.
	 * @uses Models_Base::fetchByQuery()	
	 * @uses Models_Base::getSelect()
	 * @author Shafiq Ahmadi <s.ah@live.nl>
	 */
	public function getThreads() {
		$query = Models_Thread::getSelect() . " WHERE user_id=" . $this->id . ";";
		return Models_Thread::fetchByQuery($query);
	}
	
	
	/**
	 * gets an array of Reply-objects by this user from the DB.
	 *
	 * @return Models_Reply[] array of Reply-objects
	 * @uses Models_Base::fetchByQuery()	
   * @uses Models_Base::getSelect()
	 * @author Shafiq Ahmadi <s.ah@live.nl>
	 */
	public function getReplies() {
		$query = Models_Reply::getSelect() . " WHERE user_id=" . $this->id . ";";
		return Models_Reply::fetchByQuery($query);
	}
	
	
	/**
	 * gets an array of Credit-objects, that this user got for his Replies.
	 *
	 * @return Models_Credit[] array of Credit-objects
	 * @uses Models_Base::fetchByQuery()	
   * @uses Models_Base::getSelect()
	 * @todo SQL-query controleren!!!
	 * @todo Zoiets als getCreditCount maken, waarin we een user z'n netto waarde bepalen?
	 * @todo Dit is dubbelop met Models_Reply->calcNettCredits. Beter een static methode maken in Models_Credit en die dan her en der callen?
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function getCredits() {
		$query = Models_Credit::getSelect(). " FROM credits JOIN replies ON credit.reply_id = reply.id WHERE user_id=" . $this->id . ";";
		$creditsArray = Models_Credit::fetchByQuery($query);
		$totalcredits = 0;
		foreach ($creditsArray as $credit) {
			$totalcredits += $credit->value;
		}
		return $totalcredits;
	}
	
}	
?>
