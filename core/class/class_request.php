<?php
/*
*    Copyright 2008,2009 Maarch
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
* @brief   Contains all the function to build a SQL query
*
* @file
* @author  Loïc Vinet  <dev@maarch.org>
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

require_once 'core/class/class_db_pdo.php';

/**
* @brief   Contains all the function to build a SQL query (select, insert and update)
*
* @ingroup core
*/
class request extends dbquery
{

    /**
    * Constructs the select query and returns the results in an array
    *
    * @param  $select array Query fields
    * @param  $where  string Where clause of the query
    * @param  $parameters  array An indexed or associative array of parameters
    * @param  $other  string Query complement (order by, ...)
    * @param  $database_type string Type of the database
    * @param  $limit string Maximum numbers of results (500 by default)
    * @param  $left_join boolean Is the request is a left join ? (false by default)
    * @param  $first_join_table string Name of the first join table (empty by default)
    * @param  $second_join_table string Name of the second join table (empty by default)
    * @param  $join_key string  Key of the join (empty by default)
    * @param  $add_security string  Add the user security where clause or not (true by default)
    * @param  $distinct_argument  Add the distinct parameters in the sql query (false by default)
    * @return array Results of the built query
    */
    public function PDOselect($select, $where, $parameters = null, $other, $database_type, $limit="default", $left_join=false, $first_join_table="", $second_join_table="", $join_key="", $add_security = true, $catch_error = false, $distinct_argument = false)
    {
        $db = new Database();
        if($limit == 0 || $limit == "default")
        {
            $limit = $_SESSION['config']['databasesearchlimit'];
        }
      
        //Extracts data in the first argument : $select.
        $tab_field = array();
        $table = '';
        $table_string = '';
        $field_string = '';
        foreach (array_keys($select) as $value)
        {
            $table = $value;
            $table_string .= $table.",";
            foreach ($select[$value] as $subvalue)
            {
                $field = $subvalue;
                $field_string .= $table.".".$field.",";
            }
            //Query fields and table names have been wrote in 2 strings
        }
        //Strings need to be cleaned
        $table_string = substr($table_string, 0, -1);
        $field_string = substr($field_string, 0, -1);

        //Extracts data from the second argument : the where clause
        if (trim($where) <> "")
        {
            $where_string = $where;
            //$where_string = " where ".$where;
        }
        else
        {
            $where_string = "";
        }
         $join = '';
        if($left_join)
        {
            //Reste table string
            $table_string = "";

            //Add more table in join syntax
            foreach (array_keys($select) as $value)
            {
                if ($value <> $first_join_table && $value <> $second_join_table)
                {
                    $table_string = $value.",";
                }
            }

            $join = " left join ";
            $table_string .= $first_join_table;
            $join .= $second_join_table." on ".$second_join_table.".".$join_key." = ".$first_join_table.".".$join_key;
        }

        if($add_security)
        {
            foreach(array_keys($_SESSION['user']['security']) as $coll)
            {
                if(isset($_SESSION['user']['security'][$coll]['DOC']['table']))
                {
                    if(preg_match('/'.$_SESSION['user']['security'][$coll]['DOC']['table'].'/',$table_string) || preg_match('/'.$_SESSION['user']['security'][$coll]['DOC']['view'].'/',$table_string) )
                    {
                        if(empty($where_string))
                        {
                            $where_string = "( ".$_SESSION['user']['security'][$coll]['DOC']['where']." ) ";
                            //$where_string = " where ( ".$_SESSION['user']['security'][$coll]['DOC']['where']." ) ";
                        }
                        else
                        {
                            $where_string = ''.$where_string." and ( ".$_SESSION['user']['security'][$coll]['DOC']['where']." ) ";
                        }
                        break;
                    }
                }
            }
        }
        //Time to create the SQL Query
        $query = "";
        $dist = '';
        if($distinct_argument == true)
        {
            $dist = " distinct ";
        }
        
        $query = $db->limit_select(0, $limit, $field_string, $table_string." ".$join, $where_string, $other, $dist);

        if (preg_match('/_view/i', $query)) {
            $_SESSION['last_select_query'] = $query;
            $_SESSION['last_select_query_parameters'] = $parameters;
        }

        $res_query = $db->query($query, $parameters, $catch_error);

        if($catch_error && !$res_query)
        {
            return false;
        }
        $result=array();
        while($line = $res_query->fetch(PDO::FETCH_ASSOC))
        {
            $temp= array();
            foreach (array_keys($line) as $resval)
            {
                if (!is_int($resval))
                {
                    array_push(
                        $temp,
                        array(
                            'column'=>$resval,
                            'value'=>functions::xssafe($line[$resval]),
                        )
                    );
                }
            }
            array_push($result,$temp);
        }
        if(count($result) == 0 && $catch_error)
        {
            return true;
        }
        return $result;
    }

    /**
    * Builds the insert query and sends it to the database
    *
    * @param string $table table to insert
    * @param array $data data to insert
    * @param array $database_type type of the database
    * @return bool True if the query was sent ok and processed by the database without error, False otherwise
    */
    public function insert($table, $data, $database_type)
    {
        $db = new Database();
        $field_string = "( ";
        $value_string = "( ";
        $parameters = array();
        for ($i=0;$i<count($data);$i++) {
            if(
                trim(strtoupper($data[$i]['value'])) == "SYSDATE"
                || trim(strtoupper($data[$i]['value'])) == "CURRENT_TIMESTAMP"
            ) {
                $value_string .= $data[$i]['value'] . ',';
            } else {
                $value_string .= "?,";
                $parameters[] = $data[$i]['value'];
            }
            $field_string .= $data[$i]['column'].",";
        }
        $value_string = substr($value_string, 0, -1);
        $field_string = substr($field_string, 0, -1);

        $value_string .= ")";
        $field_string .= ")";

        //Time to create the SQL Query
        $query = "INSERT INTO " . $table . " " . $field_string . " VALUES " . $value_string;
        /*echo $query . PHP_EOL;
        var_dump($parameters);exit;*/
        $stmt = $db->query($query, $parameters);

        return true;
    }

    /**
    * Constructs the update query and sends it to the database with PDO
    *
    * @param  $table string Table to update
    * @param  $data array Data to update
    * @param  $where string Where clause of the query
    * @param  $parameters array An indexed or associative array of parameters
    * @param  $databasetype array Type of the database
    */

    public function PDOupdate($table, $data, $where, $parametersInit = null, $databasetype)
    {
        $db = new Database();
        $update_string = "";
        $parameters = array();
        for ($i=0; $i < count($data);$i++) {
            if ($data[$i]['type'] == "string" || $data[$i]['type'] == "date") {
                if ($databasetype == "POSTGRESQL" && $data[$i]['type'] == "date" 
                    && ($data[$i]['value'] == '' || $data[$i]['value'] == ' ')) {
                    $update_string .= $data[$i]['column']."=NULL,";
                } else {
                    if (trim(strtoupper($data[$i]['value'])) == "SYSDATE") {
                        $update_string .= $data[$i]['column']."=sysdate,";
                    } elseif(trim(strtoupper($data[$i]['value'])) == "CURRENT_TIMESTAMP") {
                        $update_string .= $data[$i]['column']."=CURRENT_TIMESTAMP,";
                    } else {
                        $update_string .= $data[$i]['column']."=?,";
                        $parameters[] = $data[$i]['value'];
                    }
                }
            } else {
                if ($data[$i]['value'] == 'NULL') {
                    $update_string .= $data[$i]['column']."=NULL,";   
                } else {
                    $update_string .= $data[$i]['column']."=?,";
                    $parameters[] = $data[$i]['value']; 
                }
            }
        }
        $update_string = substr($update_string, 0, -1);
        if ($where <> "") {
            $where_string = " WHERE " . $where;
        } else {
            $where_string = "";
        }
        if (is_array($parametersInit)) {
            for ($cpt=0;$cpt<count($parametersInit);$cpt++) {
                $parameters[] = $parametersInit[$cpt];
            }
        }
        //Time to create the SQL Query
        $query = "";
        $query = "UPDATE " . $table . " SET " . $update_string . $where_string;
        /*echo $query . '<br/>';
        echo '<pre>';
        var_dump($parameters);
        echo '</pre>';*/
        $stmt = $db->query($query, $parameters);
        return $stmt;
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
        switch ($_SESSION['config']['databasetype'])
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
}
