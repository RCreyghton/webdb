<?php

/*
 * DB handler
 * Handles most basic queries.
 *
 * ASSUMES WebDB Kaper's
 * fatalerror.inc.php
 */

define('DBSERVER', 'webdb.science.uva.nl');
define('DBNAME', 'webdb13AD3');
define('DBUSER', 'webdb13AD3');
define('DBPASS', 'justafio');

class Helpers_Db {
    private $dbserver = DBSERVER;        //(string) server name
    private $dbname = DBNAME;            //(string) database name
    private $dbuser = DBUSER;    //(string) database user having writing rights
    private $dbpass = DBPASS;    //(string) database password for writing user
    private $mysqli= FALSE;              //(mysqli object) live database connection
    private $error = '';                 //(string) last errormessage caused by prepare
    
    /**
     * Construct a default Helpers_Db, executes default connect()
     *
     * Long discription
     *
     * @author RCreyghton
     */
    public function __construct() {
        $this->connect();
    }

    /**
     * Connects to the DB using mysqli, using default settings
     *
     * Long discription to do
     *
     * @author RCreyghton
     */
    public function connect () {
        $this->mysqli = new mysqli($this->dbserver, $this->dbuser, $this->dbpass, $this->dbname);
        if (mysqli_connect_errno()) {
            err_die("Connect error: ".mysqli_connect_error());
        }
        return TRUE;
    }

    /**
     * Destructor of this class. Closes mysqli.
     *
     * @author RCreyghton
     */
    public function __destruct() {
        $this->mysqli->close();
    }


    /**
     * Returns an 
}
