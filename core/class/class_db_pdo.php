<?php
/*
 * Copyright (C) 2015 Maarch
 *
 * This file is part of Maarch.
 *
 * Maarch is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Maarch is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Maarch.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * Class for database queries
 * 
 * @package Core
 */
class Database extends functions
{
    /**
     * Prepared statements indexed by dsn and queryString
     * @var array
     */
    private static $preparedStmt = array();

    private $driver;
    private $server;
    private $port;
    private $user;
    private $password;
    private $database;
    private $dsn;
 
    private $pdo;
    
    private $error;

    private $stmt;

    /**
     * Constructor. Connects to the database if connection parameters are available in the session config 
     */
    public function __construct()
    {
        $args = func_get_args();
        if (count($args) < 1 || empty($args[0])) {
            if (isset($_SESSION['config']['databaseserver'])) {
                $this->server = $_SESSION['config']['databaseserver'];
            }
            if (isset($_SESSION['config']['databaseserverport'])) {
                $this->port = $_SESSION['config']['databaseserverport'];
            }
            if (isset($_SESSION['config']['databaseuser'])) {
                $this->user = $_SESSION['config']['databaseuser'];
            }
            if (isset($_SESSION['config']['databasepassword'])) {
                $this->password = $_SESSION['config']['databasepassword'];
            }
            if (isset($_SESSION['config']['databasename'])) {
                $this->database = $_SESSION['config']['databasename'];
            }
            if (isset($_SESSION['config']['databasetype'])) {
                switch($_SESSION['config']['databasetype']) {
                    case 'POSTGRESQL': 
                        $this->driver = 'pgsql';
                        break;
                    case 'MYSQL': 
                        $this->driver = 'mysql';
                        break;

                    case 'ORACLE':
                        $this->driver = 'oci';
                        break;

                    default:
                        print_r('DRIVER ERROR: Unknown database driver ' 
                            . $_SESSION['config']['databasetype']);
                }
            }
        } else {
            $errorArgs = true;
            if (is_array($args[0])) {
                if (!isset($args[0]['server'])) {
                    $this->server = '127.0.0.1';
                } else {
                    $this->server = $args[0]['server'];
                }
                switch($args[0]['databasetype']) {
                    case 'POSTGRESQL': 
                        $this->driver = 'pgsql';
                        break;
                    case 'MYSQL': 
                        $this->driver = 'mysql';
                        break;

                    case 'ORACLE':
                        $this->driver = 'oci';
                        break;

                    default:
                        print_r('DRIVER ERROR: Unknown database driver ' 
                            . $_SESSION['config']['databasetype']);
                }
                if (!isset($args[0]['port'])) {
                    $this->port = '5432';
                } else {
                    $this->port = $args[0]['port'];
                }
                if (!isset($args[0]['user'])) {
                    $this->user = 'postgres';
                } else {
                    $this->user = $args[0]['user'];
                }
                if (!isset($args[0]['password'])) {
                    $this->password = 'postgres';
                } else {
                    $this->password = $args[0]['pass'];
                }
                if (! isset($args[0]['base'])) {
                    $this->database = '';
                } else {
                    $this->database = $args[0]['base'];
                }
                $errorArgs = false;
            } else if (is_string($args[0]) && file_exists($args[0])) {
                $xmlconfig = simplexml_load_file($args[0]);
                $config = $xmlconfig->CONFIG_BASE;
                $this->server = (string) $config->databaseserver;
                $this->port = (string) $config->databaseserverport;
                $this->driver = (string) $config->databasetype;
                switch($this->driver) {
                    case 'POSTGRESQL': 
                        $this->driver = 'pgsql';
                        break;
                    case 'MYSQL': 
                        $this->driver = 'mysql';
                        break;

                    case 'ORACLE':
                        $this->driver = 'oci';
                        break;

                    default:
                        print_r('DRIVER ERROR: Unknown database driver ' 
                            . $_SESSION['config']['databasetype']);
                }
                $this->database = (string) $config->databasename;
                $this->user = (string) $config->databaseuser;
                $this->password = (string) $config->databasepassword;
                $errorArgs = false;
            }
        }

        // Set DSN
        $this->dsn = $this->driver 
            . ':host=' . $this->server
            . ';port=' . $this->port
            . ';dbname=' . $this->database
        ;

        if (!isset(self::$preparedStmt[$this->dsn])) {
            self::$preparedStmt[$this->dsn] = array();
        }

        // Set options
        $options = array (
            PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_CASE          => PDO::CASE_LOWER
        );
        // Create a new PDO instanace
        try {
            $this->pdo = new PDO($this->dsn, $this->user, $this->password, $options);
        } catch (PDOException $PDOException) {
            $this->error = $PDOException->getMessage();
        }
        
        if ($this->error && strstr($this->error, '08006') <> '') {
            $this->xecho('Database connection failed');
        } elseif ($this->error && $_SESSION['config']['debug'] == 'true') {
            print_r('SQL ERROR:' . $this->error);
        }
    }

    /**
     * Begin a new transaction
     * 
     * @return bool
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Retrieve last record id
     * 
     * @return PDOStatement
     */
    public function lastInsertId($sequenceName=null)
    {
        switch($_SESSION['config']['databasetype']) {
        case 'MYSQL'        : return @mysqli_insert_id($this->_sqlLink);
        case 'POSTGRESQL'   : 
            $stmt_last_insert = $this->query("SELECT last_value as lastinsertid FROM " . $sequenceName);
            $resultat_last = $stmt_last_insert->fetchObject();
            return $resultat_last->lastinsertid;
        case 'SQLSERVER'    : return '';
        case 'ORACLE'       : 
            $stmt_last_insert = $this->query("SELECT " . $sequenceName . ".currval as lastinsertid FROM dual");
            $resultat_last = $stmt_last_insert->fetchObject();
            return $resultat_last->lastinsertid;
        default             : return false;
        }   
    }



    /**
     * Commit a transaction
     * 
     * @return bool
     */
    public function commit()
    {
        return $this->pdo->commit();
    }

    /**
     * Rollback a transaction
     * 
     * @return bool
     */
    public function rollback()
    {
        return $this->pdo->rollback();
    }

    /**
     * Check if in a transaction
     * 
     * @return bool
     */
    public function inTransaction()
    {
        return $this->pdo->inTransaction();
    }

    /**
     * Prepare a query and returns the statement.
     * Save the prepared statement for a later execution with parameters
     * @param string $queryString The SQL query string to prepare
     * 
     * @return PDOStatement
     */
    public function prepare($queryString)
    {
        if (!isset(self::$preparedStmt[$this->dsn][$queryString])) {
            self::$preparedStmt[$this->dsn][$queryString] = $this->pdo->prepare($queryString);
        }

        return self::$preparedStmt[$this->dsn][$queryString];
    }

    /**
     * Prepare and execute a query. Returns the prepared and executed statement.
     * Statement can be used to fetch resulting rows OR by a later call to a fetch method
     * @param string $queryString     The SQL query string
     * @param array  $parameters      An indexed or associative array of parameters
     * @param bool   $catchExceptions Indicates wheter the PDO exceptions must be caught 
     * @param bool   $multi Indicates wheter multi queries likes in sql file is required 
     * 
     * @return PDOStatement The prepared and executed statement
     * 
     * @throws PDOException If a PDO error occurs during preparation or execution
     */
    public function query($queryString, $parameters=null, $catchExceptions=false, $multi=false)
    {
        if ($parameters) {
            foreach ($parameters as $key => $value) {
                if (is_array($value)) {
                    //echo $key . $value. '<br />';
                    if (is_int($key)) {
                        $placeholders = implode(',', array_fill(0, count($value), '?'));
                        preg_match_all("/\?/", $queryString, $matches, PREG_OFFSET_CAPTURE);
                        $match = $matches[0][$key];
                        $queryString = substr($queryString, 0, $match[1]) 
                            . $placeholders . substr($queryString, $match[1]+1);
                        $parameters1 = array_slice($parameters, 0, $key);
                        $parameters2 = array_slice($parameters, $key+1);
                        $parameters = array_merge($parameters1, $value, $parameters2);
                    } else {
                        $placeholdersArr = array();
                        foreach ($value as $pos => $item) {
                            $pname = $key.'_'.$pos;
                            $placeholdersArr[] = $pname;
                            $parameters[$pname] = $item;
                        }
                        $placeholders = implode(',', $placeholdersArr);
                        $queryString = str_replace($key, $placeholders, $queryString);
                        unset($parameters[$key]);  
                    }
                    // var_dump($queryString);
                    // var_dump($parameters);
                } else {
                    //echo $key . $value. '<br />';
                    if (empty($parameters[$value])) {

                    }
                }
            }
        }

        if ($multi) {
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
            $this->pdo->exec($queryString);
            return true;
        } else {
            try {
                $this->stmt = $this->prepare($queryString);
                preg_match_all("/\?|\:/", $queryString, $matches, PREG_OFFSET_CAPTURE);
                if (empty($matches[0])) {
                    //echo $queryString;
                    $executed = $this->stmt->execute();
                } else {
                    $executed = $this->stmt->execute($parameters);
                }
            } catch (PDOException $PDOException) {
                if ($catchExceptions) {
                    $this->error = $PDOException->getMessage();

                    return false;
                } else {
                    if ($_SESSION['config']['debug'] == 'true') {
                        echo $queryString;
                        var_export($parameters);
                    }
                    throw $PDOException;
                }
            }
        }
        return $this->stmt;

    }

    public function limit_select($start, $count, $select_expr, $table_refs, $where_def='1=1', $other_clauses='', $select_opts='')
    {
            
        // LIMIT
        if($count || $start) 
        {
            switch($_SESSION['config']['databasetype']) {
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
                 $limit_clause = 'OFFSET ' . $start . ' LIMIT ' . $count;
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
    * Returns the word to get the current timestamp on a query
    *
    * Return
    *   (string) timestamp word
    *
    *************************************************************************/
    public function current_datetime()
    {
        switch ($this->driver) {
            case 'mysql'        : return 'CURRENT_TIMESTAMP';
            case 'pgsql'        : return 'CURRENT_TIMESTAMP';
            case 'oci'          : return 'SYSDATE';
            default             : return ' ';
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
        switch($this->driver) {
            case 'pgsql'   : 
                $stmt = $this->query(
                    "select column_name from information_schema.columns where table_name = ? and column_name = ?", 
                    array($table, $field)
                );
                $res = $stmt->rowCount();
                if ($res > 0) return true; 
                else return false;
            case 'oci'       : 
                $stmt = $this->query("SELECT * from USER_TAB_COLUMNS where TABLE_NAME = ? AND COLUMN_NAME = ?", 
                    array($table, $field)
                );
                $res = $stmt->rowCount();
                if ($res > 0) return true; 
                else return false;
            case 'mysql'        : return true; // TO DO
            default             : return false;
        }
    }
}

