<?php
/*
*    Copyright 2008 - 2011 Maarch
*
*  This file is part of Maarch Framework.
*
*   Maarch Framework is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Maarch Framework is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*    along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief   Embedded sql functions (connection, database selection, query ).
* Allow to changes the databases server
*
* @file
* @author  Claire Figueras <dev@maarch.org>
* @author  Laurent Giovannoni  <dev@maarch.org>
* @author  Loic Vinet <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

/**
* @brief   Embedded sql functions (connection, database selection, query ).
* Allow to changes the databases server
*
* <ul>
*  <li>Compatibility with the following databases : Mysql, Postgres,
*       Mssql Server, Oracle
*  <li>Connection to the Maarch database</li>
* <li>Execution of SQL queries to the Maarch database</li>
* <li>Getting results of SQL queries</li>
* <li>Managing the database errors</li>
* </ul>
* @ingroup core
*/
class dbquery extends functions
{
    /**
    * Debug mode activation.
    * Integer 1,0
         */
    private $_debug;             // debug mode

    /**
    * Debug query (debug mode). String
    */
    private $_debugQuery;       // request for the debug mode

    /**
    * SQL link identifier
    * Integer
    */
    public $_sqlLink;          // sql link identifier


    /**
    * To know where the script was stopped
    *  Integer
    */
    private $_sqlError;    // to know where the script was stopped

    /**
    * SQL query
    * String
         */
    public $query;              // query

    /**
    * Number of queries made with this identifier
         * Integer
         */
    private $_nbQuery;          // number of queries made with this identifier

    /**
    * Sent query result
         * String
         */
    private $_result;            // sent query result

    /**
    * OCI query identifier
    * @access private
    * @var integer
    */
    private $_statement  ;       // OCI query identifier

    private $_server;
    private $_port;
    private $_user;
    private $_password;
    private $_database;
    private $_databasetype;
    //private $workspace;

    public function __construct()
    {
        $args = func_get_args();
        if (count($args) < 1) {
            if (isset($_SESSION['config']['databaseserver'])) {
                $this->_server = $_SESSION['config']['databaseserver'];
            }
            if (isset($_SESSION['config']['databaseserverport'])) {
                $this->_port = $_SESSION['config']['databaseserverport'];
            }
            if (isset($_SESSION['config']['databaseuser'])) {
                $this->_user = $_SESSION['config']['databaseuser'];
            }
            if (isset($_SESSION['config']['databasepassword'])) {
                $this->_password = $_SESSION['config']['databasepassword'];
            }
            if (isset($_SESSION['config']['databasename'])) {
                $this->_database = $_SESSION['config']['databasename'];
            }
            //$this->workspace = $_SESSION['config']['databaseworkspace'];
            if (isset($_SESSION['config']['databasetype'])) {
                $this->_databasetype = $_SESSION['config']['databasetype'];
            }
        } else {
            $errorArgs = true;
            if (is_array($args[0])) {
                if (! isset($args[0]['server'])) {
                    $this->_server = '127.0.0.1';
                } else {
                    $this->_server = $args[0]['server'];
                }
                if (! isset($args[0]['databasetype'])) {
                    $this->_databasetype = 'MYSQL';
                } else {
                    $this->_databasetype = $args[0]['databasetype'];
                }
                if (! isset($args[0]['port'])) {
                    $this->_port = '3304';
                } else {
                    $this->_port = $args[0]['port'];
                }
                if (! isset($args[0]['user'])) {
                    $this->_user = 'root';
                } else {
                    $this->_user = $args[0]['user'];
                }
                //if(!isset($args[0]['workspace']))
                //{
                //  $this->workspace = 'public';
                //}
                //else
                //{
                //  $this->workspace = $args[0]['workspace'];
                //}
                if (! isset($args[0]['pass'])) {
                    $this->_password = '';
                } else {
                    $this->_password = $args[0]['pass'];
                }
                if (! isset($args[0]['base'])) {
                    $this->_database = '';
                } else {
                    $this->_database = $args[0]['base'];
                }
                $errorArgs = false;
            } else if (is_string($args[0]) && file_exists($args[0])) {
                $xmlconfig = simplexml_load_file($args[0]);
                $config = $xmlconfig->CONFIG_BASE;
                $this->_server = (string) $config->databaseserver;
                $this->_port = (string) $config->databaseserverport;
                $this->_databasetype = (string) $config->databasetype;
                $this->_database = (string) $config->databasename;
                $this->_user = (string) $config->databaseuser;
                $this->_password = (string) $config->databasepassword;
                //if (isset($config->databaseworkspace)) {
                //  $this->workspace = (string) $config->databaseworkspace;
                // }
                $errorArgs = false;
            }
            if ($errorArgs) {
                $this->_sqlError = 5; // error constructor
                $this->error();
            }
        }
    }
    /**
    * Connects to the database
    *
    */
    public function connect()
    {
        $this->_debug = 0;
        $this->_nbQuery = 0;
        
        switch($this->_databasetype) 
        {
        case 'MYSQL' : 
            $this->_sqlLink = @mysqli_connect(
                $this->_server,
                $this->_user,
                $this->_password,
                $this->_database,
                $this->_port
            );
            break;
            
        case 'POSTGRESQL' : 
            $this->_sqlLink = @pg_connect(
                'host=' . $this->_server . 
                ' user=' . $this->_user . 
                ' password=' . $this->_password . 
                ' dbname=' . $this->_database . 
                ' port=' . $this->_port
            );
            break;
            
        case 'SQLSERVER' :
            $this->_sqlLink = @mssql_connect(
                $this->_server, 
                $this->_user, 
                $this->_password
            );
            break;
            
        case 'ORACLE' : 
            if ($this->_server <> '') {
                $this->_sqlLink = oci_connect(
                    $this->_user, 
                    $this->_password, '//' . 
                    $this->_server . '/' . 
                    $this->_database, 
                    'UTF8'
                );
            } else {
                $this->_sqlLink = oci_connect(
                    $this->_user, 
                    $this->_password, 
                    $this->_database, 
                    'UTF8'
                );
            }
            $this->query("alter session set nls_date_format='dd-mm-yyyy HH24:MI:SS'");
            break;
            
        default :
            $this->_sqlLink = false;
            break;      
        }

        if (! $this->_sqlLink) {
            $this->_sqlError = 1; // error connexion
            $this->error();
        } 
        else {
            $this->select_db();
        }
    }

    /**
    * Database selection (only for SQLSERVER)
    */
    public function select_db()
    {
        if ($this->_databasetype == 'SQLSERVER') {
            if (! @mssql_select_db($this->_database)) {
                $this->_sqlError = 2;
                $this->error();
            }
        }
    }

    /**
    * Test if the specified column exists in the database
    *
    * @param  $table : Name of searched table
    * @param  $field : Name of searched field in table
    *  ==Return : true is field is founed, false is not
    */
    public function test_column($table, $field)
    {
        switch($this->_databasetype) 
        {
        
        case 'POSTGRESQL'   : 
            $this->connect();
            $this->query("select column_name from information_schema.columns where table_name = '" . $table . "' and column_name = '" . $field . "'");
            $res = $this->nb_result();
            $this->disconnect();
            if ($res > 0) return true; 
            else return false;
            
        case 'ORACLE'       : 
            $this->connect();
            $this->query("SELECT * from USER_TAB_COLUMNS where TABLE_NAME = '" . $table . "' AND COLUMN_NAME = '" . $field . "'");
            $res = $this->nb_result();
            $this->disconnect();
            if ($res > 0) return true; 
            else return false;
        
        case 'SQLSERVER'    : return true; // TO DO
        case 'MYSQL'        : return true; // TO DO
        default             : return false;
        
        }
    }
    
    /**
    * Execution the sql query
    *
    * @param  $sqlQuery string SQL query
    * @param  $catchError bool In case of error, catch the error or not,
    *           if not catched, the error is displayed (false by default)
    * @param  $noFilter bool true if you don't want to filter on ; and --
    */
    public function query(
        $sqlQuery, 
        $catchError = false, 
        $noFilter = false,
        &$params = array()
    ) {
        if (!$this->_sqlLink) {
            $this->connect();
        }
        $canExecute = true;        
        // if filter, we looking for ; or -- in the sql query
        if (!$noFilter) {
            $func = new functions();
            $sqlQuery = $func->wash_html($sqlQuery, '');
            $ctrl1 = array();
            $ctrl1 = explode(";", $sqlQuery);
            if (count($ctrl1) > 1) {
                $canExecute = false;
                $this->_sqlError = 7;
                $this->error();
            }
            $ctrl2 = array();
            $ctrl2 = explode("--", $sqlQuery);
            if (count($ctrl2) > 1) {
                $canExecute = false;
                $this->_sqlError = 7;
                $this->error();
            }
        }

        // query
        if ($canExecute) {
            $this->_debugQuery = $sqlQuery;
            
            switch($this->_databasetype) 
            {
            case 'MYSQL' : 
                $this->query = @mysqli_query($this->_sqlLink, $sqlQuery);
                break;

            case 'POSTGRESQL' : 
                $this->query = @pg_query($this->_sqlLink, $sqlQuery);
                break;
                
            case 'SQLSERVER' : 
                $this->query = @mssql_query($sqlQuery);
                break;
                
            case 'ORACLE' : 
                $this->query = @oci_parse($this->_sqlLink, $sqlQuery);
                                
                if ($this->query == false) {
                    if ($catchError) return false;
                    $this->_sqlError = 6;
                    $this->error();
                    exit();
                } 
                else {
                    if(count($params) > 0) {
                        foreach($params as $paramname => &$paramvar) {   
                            $binded = oci_bind_by_name($this->query, $paramname, $paramvar, 100, SQLT_CHR);
                        }
                    }

                    if (! @oci_execute($this->query)) {
                        if ($catchError) return false;
                        $this->_sqlError = 3;
                        $this->error();
                    }
                    if(count($params) > 0) {
                        //
                    }
                }
                break;
                
            default : 
                $this->query = false;
            }   
            
            //$this->show();
            
            if ($this->query == false && !$catchError) {
                $this->_sqlError = 3;
                $this->error();
            }
            
            $this->_nbQuery ++;
            
            return $this->query;

        } 
        else {
            return false;
        }
    }
    
    public function start_transaction()
    {
        switch($this->_databasetype) 
        {
        case 'MYSQL'        : 
            @mysqli_query($this->_sqlLink, 'BEGIN');
            break;
        case 'SQLSERVER'    : 
            break;
        case 'POSTGRESQL'   : 
            @pg_query($this->_sqlLink, 'BEGIN');
            break;
        case 'ORACLE'       : 
            break;
        }
    }
    
    public function rollback()
    {
        switch($this->_databasetype) 
        {
        case 'MYSQL'        : 
            @mysqli_query($this->_sqlLink, 'ROLLBACK');
            break;
        case 'SQLSERVER'    : 
            break;
        case 'POSTGRESQL'   : 
            @pg_query($this->_sqlLink, 'ROLLBACK');
            break;
        case 'ORACLE'       : 
            break;
        }
    }
    
    public function commit()
    {
        switch($this->_databasetype) 
        {
        case 'MYSQL'        : 
            @mysqli_query($this->_sqlLink, 'COMMIT');
            break;
        case 'SQLSERVER'    : 
            break;
        case 'POSTGRESQL'   : 
            @pg_query($this->_sqlLink, 'COMMIT');
            break;
        case 'ORACLE'       : 
            break;
        }
    }
    
    public function getError()
    {
        switch($this->_databasetype) {
            case 'MYSQL':
                $sqlError = @mysqli_errno($this->_sqlLink);
                break;
                
            case 'SQLSERVER' : 
                $sqlError = @mssql_get_last_message();
                break;
                
            case 'POSTGRESQL':
                @pg_send_query($this->_sqlLink, $this->_debugQuery);
                $res = @pg_get_result($this->_sqlLink);
                $sqlError .= @pg_result_error($res);
                break;
                
            case 'ORACLE' :
                $res = @oci_error($this->statement);
                $sqlError = $res['message'];
                break;
                
            default :

            }
        return $sqlError;
    }
    
    /**
    * Returns the query results in an object
    *
    * @return Object
    */
    public function fetch_object()
    {
        switch($this->_databasetype) 
        {
        case 'MYSQL'        : return @mysqli_fetch_object($this->query);
        case 'SQLSERVER'    : return @mssql_fetch_object($this->query);
        case 'POSTGRESQL'   : return @pg_fetch_object($this->query);
        case 'ORACLE'       : 
            $myObject = @oci_fetch_object($this->query);
            //$myLowerObject = false;
            $myLowerObject = new stdClass();
            if (isset($myObject) && ! empty($myObject)) {
                foreach ($myObject as $key => $value) {
                    $myKey = strtolower($key);
                    if (oci_field_type($this->query, $key) == 'CLOB') {
                        $myBlob = $myObject->{$key};
                        if (isset($myBlob)) {
                            $myLowerObject->{$myKey} = $myBlob->read(
                                $myBlob->size()
                            );
                        }
                    } else {
                        $myLowerObject->{$myKey} = $myObject->{$key};
                    }
                }
                return $myLowerObject;
            } 
            else {
                return false;
            }
        }
    }

    /**
    * Returns the query results in an array
    *
    * @return array
    */
    public function fetch_array()
    {
        switch($this->_databasetype) 
        {
        case 'MYSQL'        : return @mysqli_fetch_array($this->query);
        case 'SQLSERVER'    : return @mssql_fetch_array($this->query);
        case 'POSTGRESQL'   : return @pg_fetch_array($this->query);
        case 'ORACLE'       : 
            $tmpStatement = array();
            $tmpStatement = @oci_fetch_array($this->query);

            if (is_array($tmpStatement)) {
                //$this->show_array($tmp_statement);
                foreach (array_keys($tmpStatement) as $key) {
                    if (! is_numeric($key)
                        && oci_field_type($this->query, $key) == 'CLOB'
                    ) {
                        if (isset($tmpStatement[$key])) {
                            $tmp = $tmpStatement[$key];
                            $tmpStatement[$key] = $tmp->read($tmp->size());
                        }
                    }
                }
                return array_change_key_case($tmpStatement, CASE_LOWER);
            }
        default         : return false;
        }
    }
    
        /**
    * Returns the query results in an array
    *
    * @return array
    */
    public function fetch_assoc()
    {
        switch($this->_databasetype) 
        {
        case 'MYSQL'        : return @mysqli_fetch_assoc($this->query);
        case 'SQLSERVER'    : return @mssql_fetch_assoc($this->query);
        case 'POSTGRESQL'   : return @pg_fetch_assoc($this->query);
        case 'ORACLE'       : 
            $tmpStatement = array();
            $tmpStatement = @oci_fetch_assoc($this->query);

            if (is_array($tmpStatement)) {
                //$this->show_array($tmp_statement);
                foreach (array_keys($tmpStatement) as $key) {
                    if (! is_numeric($key)
                        && oci_field_type($this->query, $key) == 'CLOB'
                    ) {
                        if (isset($tmpStatement[$key])) {
                            $tmp = $tmpStatement[$key];
                            $tmpStatement[$key] = $tmp->read($tmp->size());
                        }
                    }
                }
                return array_change_key_case($tmpStatement, CASE_LOWER);
            }
        default         : return false;
        }
    }
    
    /**
    * Returns the query results in a row
    *
    * @return array
    */
    public function fetch_row()
    {
        switch($this->_databasetype) 
        {
        case 'MYSQL'        : return @mysqli_fetch_row($this->query);
        case 'POSTGRESQL'   : return @pg_fetch_row($this->query);
        case 'SQLSERVER'    : return @mssql_fetch_row($this->query);
        case 'ORACLE'       : return @oci_fetch_row($this->statement);
        default             : return false;
        }     
    }

    /**
    * Returns the number of results for the current query
    *
    * @return integer Results number
    */
    public function nb_result()
    {
        switch($this->_databasetype) 
        {
        case 'MYSQL'        : return @mysqli_num_rows($this->query);
        case 'POSTGRESQL'   : return @pg_num_rows($this->query);
        case 'SQLSERVER'    : return @mssql_num_rows($this->query);
        case 'ORACLE'       : 
        if (file_exists($GLOBALS['configFile'])) {
                    $dbNbResult = new dbquery($GLOBALS['configFile']);
        } else {
                $dbNbResult = new dbquery();
        }
            $dbNbResult->connect();
            $dbNbResult->query("SELECT COUNT(*) FROM  (" . $this->_debugQuery . ")", true);
            $row = $dbNbResult->fetch_array();
            return $row[0]; 
        default             : return false;
        }
    }

    /**
    * Closes database connexion
    *
    */
    public function disconnect()
    {
        switch($this->_databasetype)
        {
        case 'MYSQL':
            if (! mysqli_close($this->_sqlLink)) {
                $this->_sqlError = 4;
                $this->error();
            }
            break;
            
        case 'SQLSERVER' : 
            if (! mssql_close($this->_sqlLink)) {
                $this->_sqlError = 4;
                $this->error();
            }
            break;
            
        case 'POSTGRESQL':
            if (! pg_close($this->_sqlLink)) {
                $this->_sqlError = 4;
                $this->error();
            }
            break;
            
        case 'ORACLE' :
            if (! oci_close($this->_sqlLink)) {
                $this->_sqlError = 4;
                $this->error();
            }
            break;
            
        default :

        }
    }

    /**
    * SQL Error management
    *
    */
    private function error() 
    {
        
        require_once('core' . DIRECTORY_SEPARATOR . 'class' 
            . DIRECTORY_SEPARATOR . 'class_history.php');
        $trace = new history();
        
        // Connexion error
        if ($this->_sqlError == 1) {
            echo '- <b>' . _DB_CONNEXION_ERROR . '</b>';
            if ($_SESSION['config']['debug'] == 'true') {
                echo ' -<br /><br />' . _DATABASE_SERVER . ' : '
                    . $this->_server . '<br/>' . _DB_PORT . ' : ' . $this->_port
                    . '<br/>' . _DB_TYPE . ' : ' . $this->_databasetype
                    . '<br/>' . _DB_NAME . ' : ' . $this->_database . '<br/>'
                    . _DB_USER . ' : ' . $this->_user . '<br/>' . _PASSWORD
                    . ' : ' . $this->_password;
            }
            header('HTTP/1.1 500 Internal server error');
            exit();
        }

        // Selection error
        if ($this->_sqlError == 2) {
            echo '- <b>' . _SELECTION_BASE_ERROR . '</b>';
            if ($_SESSION['config']['debug'] == 'true') {
                echo ' -<br /><br />' . _DATABASE . ' : ' . $this->_database;
            }
            $trace->add("", 0, "SELECTDB", "DBERROR", _SELECT_DB_FAILED." : ".$this->_database, $_SESSION['config']['databasetype'], "database", true, _KO, _LEVEL_FATAL);
            exit();
        }

        // Query error
        if ($this->_sqlError == 3) {
            
            $sqlError = $this->getError();
            
            $trace->add(
                "", 
                0, 
                "QUERY", 
                "DBERROR", 
                _QUERY_DB_FAILED . ": '" . $sqlError . "' " 
                . _QUERY . ": [" . $this->protect_string_db($this->_debugQuery)."]",
                $_SESSION['config']['databasetype'], 
                "database", 
                true, 
                _KO, 
                _LEVEL_ERROR
            );
            
            throw new Exception (_QUERY_DB_FAILED.": '".$sqlError."' "._QUERY.": [".$this->protect_string_db($this->_debugQuery)."]");
            
        }

        // Closing connexion error
        if ($this->_sqlError == 4) {
            echo '- <b>' . _CLOSE_CONNEXION_ERROR . '</b> -<br /><br />';
            $trace->add("", 0, "CLOSE", "DBERROR", _CLOSE_DB_FAILED, $_SESSION['config']['databasetype'], "database", true, _KO, _LEVEL_ERROR);
            exit();
        }

        // Constructor error
        if ($this->_sqlError == 5) {
            echo '- <b>' . _DB_INIT_ERROR . '</b> <br />';
            $trace->add("", 0, "INIT", "DBERROR", _INIT_DB_FAILED, $_SESSION['config']['databasetype'], "database", true, _KO, _LEVEL_ERROR);
            exit();
        }
        // Query Preparation error (ORACLE & DB2)
        if ($this->_sqlError == 6) {
            echo '- <b>' . _QUERY_PREP_ERROR . '</b> <br />';
            $trace->add("", 0, "QUERY", "DBERROR", _PREPARE_QUERY_DB_FAILED, $_SESSION['config']['databasetype'], "database", true, _KO, _LEVEL_ERROR);
            exit();
        }
        // Query Preparation error (ORACLE & DB2)
        if ($this->_sqlError == 7) {
            $_SESSION['error'] .= _SQL_QUERY_NOT_SECURE;
            $trace->add("", 0, "QUERY", "DBERROR", _SQL_QUERY_NOT_SECURE, $_SESSION['config']['databasetype'], "database", true, _KO, _LEVEL_ERROR);
            //exit();
        }
    }

    /**
    * Shows the query for debug
    *
    */
    public function show()
    {
        echo _LAST_QUERY . ' : <textarea cols="70" rows="10">'
            . $this->_debugQuery . '</textarea>';
    }

    /**
    * Returns the last insert id for the current query in case  of
    *   autoincrement id
    *
    * @return integer  last increment id
    */
    public function last_insert_id($sequenceName = '')
    {
        switch($this->_databasetype) {
        case 'MYSQL'        : return @mysqli_insert_id($this->_sqlLink);
        case 'POSTGRESQL'   : 
            $this->query = @pg_query("select currval('" . $sequenceName . "') as lastinsertid");
            $line = @pg_fetch_object($this->query);
            return $line->lastinsertid;
        case 'SQLSERVER'    : return '';
        case 'ORACLE'       : 
            $this->query("select " . $sequenceName . ".currval as lastinsertid from dual");
            $line = $this->fetch_object($this->query);
            return $line->lastinsertid;
        default             : return false;
        }       
    }
	
	/**
    * Returns the next free id of a sequence
	*
	* @param string $seqName name of the sequence
	*
	* @return integer next id in the given sequence
    */
    public function next_id($sequenceName = '')
    {
		switch($this->_databasetype) {
			case 'MYSQL'        : return '';
			case 'POSTGRESQL'   : 
				$this->query = @pg_query("select nextval('" . $sequenceName . "') as nextid");
				$line = @pg_fetch_object($this->query);
				return $line->nextid;
			case 'SQLSERVER'   	: return '';
			case 'ORACLE'       : 
				$this->query("select " . $sequenceName . ".nextval  as nextid from dual");
				$line = $this->fetch_object($this->query);
				return $line->nextid;
			default             : return false;
		}
	}
	
    
    /*************************************************************************
    * Returns instruction to get date or part of the date
    *
    * Parameters
    *   (string) date string
    *   (string) date part name {year | month | day | hour | minute | second}
    *
    * Return
    *   (string) date instruction
    *
    *************************************************************************/
    public function extract_date($date_field, $arg = '')
    {
        switch ($this->_databasetype)
        {
        case "SQLSERVER":
            return '';
        
        case "MYSQL":
            switch($arg) 
            {
            case 'year'     : return ' date_format('.$date_field.', %Y)';
            case 'month'    : return ' date_format('.$date_field.', %m)';
            case 'day'      : return ' date_format('.$date_field.', %d)';
            case 'hour'     : return ' date_format('.$date_field.', %k)';
            case 'minute'   : return ' date_format('.$date_field.', %i)';
            case 'second'   : return ' date_format('.$date_field.', %s)';
            default         : return ' date('.$date_field.')';
            }
        
        case "POSTGRESQL":
            switch($arg) 
            {
            case 'year'     : return " date_part( 'year', ".$date_field.")";
            case 'month'    : return " date_part( 'month', ".$date_field.")";
            case 'day'      : return " date_part( 'day', ".$date_field.")";
            case 'hour'     : return " date_part( 'hour', ".$date_field.")";
            case 'minute'   : return " date_part( 'minute', ".$date_field.")";
            case 'second'   : return " date_part( 'second', ".$date_field.")";
            default         : return ' date('.$date_field.')';
            }
        
        case "ORACLE":
            switch($arg) 
            {
            case 'year'     : return " to_char(".$date_field.", 'YYYY')";
            case 'month'    : return " to_char(".$date_field.", 'MM')";
            case 'day'      : return " to_char(".$date_field.", 'DD')";
            case 'hour'     : return " to_char(".$date_field.", 'HH24')";
            case 'minute'   : return " to_char(".$date_field.", 'MI')";
            case 'second'   : return " to_char(".$date_field.", 'SS')";
            //default         : return " to_char(".$date_field.", 'DD/MM/YYYY')";
            default         : return $date_field;
            }
    
        }
    }
    
    public function escape_string($string)
    {
        switch ($this->_databasetype)
        {
        case "SQLSERVER" : 
            $string = str_replace("'", "''", $string);
            $string = str_replace("\\", "\\\\", $string);
            break;
        case "ORACLE" :
            $string = str_replace("'", "''", $string);
            $string = str_replace("\\", "\\\\", $string);
            break;
        case "MYSQL": 
            $string = mysql_escape_string($string);
            break;
        case "POSTGRESQL":
            $string = pg_escape_string($string);
        }
        return $string;
        
    }
    
    /*************************************************************************
    * Returns the difference between 2 dates in days
    *
    * Parameters
    *   (string) end date
    *   (string) start date
    *
    * Return
    *   (integer) number of days
    *
    *************************************************************************/
    public function get_date_diff($date1, $date2)
    {
        switch($this->_databasetype) 
        {
        case 'MYSQL'        : return 'datediff('.$date1.', '.$date2.')';
        case 'POSTGRESQL'   : return $this->extract_date($date1).' - '.$this->extract_date($date2);
        case 'SQLSERVER'    : return '';
        case 'ORACLE'       : 
            if ($date1 <> 'SYSDATE') {
                $date1 = "to_date(" . $date1 . ", 'DD/MM/YYYY')";
            }
            elseif ($date2 <> 'SYSDATE') {
                $date2 = "to_date(" . $date2 . ", 'DD/MM/YYYY')";
            }
            return $date1 . " - " . $date2;
        default             : return false;
        }       
    }
    
    /*************************************************************************
    * Returns the word to get the current timestamp on a query
    *
    * Return
    *   (string) timestamp word
    *
    *************************************************************************/
    public function current_datetime()
    {
        switch($this->_databasetype) 
        {
        case 'MYSQL'        : return 'CURRENT_TIMESTAMP';
        case 'POSTGRESQL'   : return 'CURRENT_TIMESTAMP';
        case 'SQLSERVER'    : return 'CURRENT_TIMESTAMP';
        case 'ORACLE'       : return 'SYSDATE';
        default             : return ' ';
        }
    }
    
    /*************************************************************************
    * Returns a select query with limit clause
    *
    * Parameters
    *   (integer) start : Offset of first result requested (default 0)
    *   (integer) count : Number of result requested (default 0)
    *   (string) select expression : Selected columns (comma separated)
    *   (string) table references : One or more tables (can be prepared by function make_table_ref) 
    *   (string) where def
    *   (string) other_clauses : group_by, order_by, having...
    *   (string) select options : distinct
    *
    * Return
    *   (string) query string
    *
    * Evolutions
    *   Offset with MSSQL
    *************************************************************************/
    public function limit_select($start, $count, $select_expr, $table_refs, $where_def='1=1', $other_clauses='', $select_opts='')
    {
            
        // LIMIT
        if($count || $start) 
        {
            switch($this->_databasetype) {
            case 'MYSQL' : 
                $limit_clause = 'LIMIT ' . $start . ',' . $count;
                break;
                
            case 'POSTGRESQL' : 
                $limit_clause = 'OFFSET ' . $start . ' LIMIT ' . $count;
                break;
                
            case 'SQLSERVER' : 
                $select_opts .= ' TOP ' . $count;
                break;
                
            case 'ORACLE' : 
                if($where_def) $where_def .= ' AND ';
                $where_def .= ' ROWNUM <= ' . $count;
                break;
                
            default : 
                break;
            }
        }
        
        if(empty($where_def)) $where_def = '1=1';
        
        // CONSTRUCT QUERY
        $query = 'SELECT' . 
            ' ' . $select_opts . 
            ' ' . $select_expr . 
            ' FROM ' . $table_refs .
            ' WHERE ' . $where_def .
            ' ' . $other_clauses .
            ' ' . $limit_clause;
        
        return $query;
        
    }
    
    /*************************************************************************
    * Returns an empty list for SELECT X WHERE Y IN (------)
    *
    * Return
    *   (string) Empty list
    *
    *************************************************************************/
    public function empty_list()
    {
        switch($this->_databasetype) 
        {
        case 'MYSQL'        : return "''";
        case 'POSTGRESQL'   : return "''";
        case 'SQLSERVER'    : return "''''";
        case 'ORACLE'       : return "''''";
        default             : return "''";
        }
    }
    
}
