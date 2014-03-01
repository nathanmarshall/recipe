<?php
/**
* @package SPLIB
* @version $Id: MySQL.php,v 1.6 2003/09/23 19:39:11 harry Exp $
*/
/**
* MySQL Database Connection Class
* @access public
* @package SPLIB
*/

class MySQL {
    /**
    * MySQL server data source
    * @access private
    * @var string
    */
    var $dsn;
	
    /**
    * MySQL data source options
    * @access private
    * @var array
    */
    var $opt;

    /**
    * MySQL username
    * @access private
    * @var string
    */
    var $user;

    /**
    * MySQL user's password
    * @access private
    * @var string
    */
    var $pass;

    /**
    * MySQL Resource link identifier stored here
    * @access private
    * @var string
    */
    var $dbConn;

    /**
    * MySQL constructor
    * @param string host (MySQL server hostname)
    * @param string dbUser (MySQL User Name)
    * @param string dbPass (MySQL User Password)
    * @param string dbName (Database to select)
    * @access public
    */
    function MySQL ($host,$user,$pass,$dbName) {
        $this->dsn="mysql:host=$host;dbname=$dbName;charset=utf8";
		$this->opt = array(
			PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC
		);
        $this->user=$user;
        $this->pass=$pass;
        $this->connectToDb();
    }

    /**
    * Establishes connection to MySQL and selects a database
    * @return void
    * @access private
    */
    function connectToDb () {
        // Make connection to MySQL server

		$this->dbConn = new PDO($this->dsn,
                                      $this->user,
                                      $this->pass,
									  $this->opt);
    }
}
?>