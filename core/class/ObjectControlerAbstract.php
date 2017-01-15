<?php

define ("_DEBUG", false);
define ("_ADVANCED_DEBUG",false);

/**
 * Implementing few generic features for controlers of type
 * "all-the-properties-of-the-object-are-the-columns-of-the-
 * database-table", i.e. BaseObject-kind.
 *
 * @author boulio
 *
 */
abstract class ObjectControler
{
    static protected $db;
    static protected $computed_properties = array();
    static protected $foolish_ids = array();
    static protected $specific_id ;

    protected function set_foolish_ids($array)
    {
        if (isset($array) && is_array($array)){
            self::$foolish_ids = $array;
        }
    }

    protected function set_specific_id($id)
    {
        self::$specific_id = $id;
    }

    /**
     * Insert given object in given table.
     * Return inserted object if succeeded.
     * @param unknown_type $object
     * @return unknown_type
     */
    protected function advanced_insert($object)
    {
        $tableName = get_class($object);
        if (!isset($object) ) {
            return false;
        }

        // Inserting object
        $preparation = self::insert_prepare(
            $object, self::$computed_properties
        );
        
        $query = "insert into $tableName (" . $preparation['properties']
               . ") values(" . $preparation['values'] . ")";
        self::$db = new Database();
        
        $stmt = self::$db->query($query, $preparation['arrayValues']);
        
        if ($stmt) {
            $result = true;
        } else {
            $result = false;
        }    
        
        return $result;
    }

    /**
     * Prepare two strings for insert query :
     * - 'properties' for properties field of insert query,
     * - 'values' for values field of insert query.
     * Needs list of values to _exclude_ of insert query (i.e.
     * usually values computed in the get() function of controler).
     * Result in an array.
     * @param Any $object
     * @param string[] $computed_properties
     * @return string[]
     */
    protected function insert_prepare($object, $computed_properties)
    {
        $result = array();
        $properties = array();
        $values = array();
        $arrayValues = array();
        foreach ($object->getArray() as $key => $value) {
            if(!in_array($key,$computed_properties)) {
                // Adding property
                $properties[] = $key;
                // Adding property value
                if (substr_compare($key, '_id', -3) == 0
                    || substr_compare($key, '_number', -7) == 0) {
                    if (in_array($key, self::$foolish_ids)) {
                        //$values[] = "'" . $value . "'";
                    } else {
                        // Number
                        if (empty($value)) {
                            // Default value
                            $value = 0;
                        }
                    }
                    $arrayValues[] = $value;
                    $values[] = '?';
                } elseif(substr_compare($key, "is_", 0, 3) == 0
                    || substr_compare($key, "can_", 0, 4) == 0) {
                    // Boolean
                    if ($value === true) {
                        $boolValue = "Y";
                    } elseif ($value === false) {
                        $boolValue = "N";
                    } else {
                        $boolValue = $value;
                    }
                    $values[] = '?';
                    $arrayValues[] = $boolValue;
                } else {
                    if (
                        $value == 'CURRENT_TIMESTAMP'
                        || $value == 'SYSDATE'
                    ) {
                        $values[] = $value;
                    } else {
                        $values[] = '?';
                        $arrayValues[] = $value;
                    }
                }
            }
        }
        $result['properties'] = implode(",", $properties);
        $result['values'] = implode(",", $values);
        $result['arrayValues'] = $arrayValues;
        return $result;
    }

    /**
     * Update given object in given table, according
     * with given table id name.
     * Return updated object if succeeded.
     * @param unknown_type $object
     * @return unknown_type
     */
    protected function advanced_update($object)
    {
        if (!isset($object)){
            return false;
        }
        $tableName = get_class($object);
        $table_id = $tableName .'_id';

        if (isset(self::$specific_id) && !empty(self::$specific_id)){
            $table_id = self::$specific_id;
        }

        $prep_query = self::update_prepare($object, self::$computed_properties);

        $prep_query['arrayValues'][] = $object->{$table_id};

        $query = "update $tableName set "
               . $prep_query['query']
               . " where $table_id=?";

        self::$db = new Database();        
        $stmt = self::$db->query($query, $prep_query['arrayValues']);
        
        if ($stmt) {
            $result = true;
        } else {
            $result = false;
        }  
        
        return $result;
    }

    /**
     * Prepare string for update query
     * @param Any $object
     * @param string[] $computed_properties
     * @return String
     */
    private function update_prepare($object, $computed_properties)
    {
        $result = array();
        $arrayValues=array();
        foreach ($object->getArray() as $key => $value) {
            if (!in_array($key,$computed_properties)) {
                if($key == self::$specific_id) {
                    // do not update key
                } elseif (substr_compare($key, '_id', -3) == 0
                    || substr_compare($key, '_number', -7) == 0) {
                    if (in_array($key, self::$foolish_ids)) {
                        //$result[] = $key . "='" . $value . "'";
                    } else {
                        // Number
                        if (empty($value)) {
                            // Default value
                            $value = 0;
                        }
                    }
                    $result[] = $key . "=?";
                    $arrayValues[]=$value;
                } elseif (substr_compare($key, 'is_', 0, 3) == 0
                    || substr_compare($key, 'can_', 0, 4) == 0) {
                    // Boolean
                    if ($value === true) {
                        $boolValue = "Y";
                    } elseif ($value === false) {
                        $boolValue = "N";
                    } else {
                        $boolValue = $value;
                    }
                    $result[] = $key . "=?";
                    $arrayValues[] = $boolValue;
                } else {
                    // Character or date
                    if (
                        $value == 'CURRENT_TIMESTAMP'
                        || $value == 'SYSDATE'
                    ) {
                    $result[] = $key . "=" . $value;
                    } else {
                        $result[] = $key . "=?";
                        $arrayValues[] = $value;
                    }
                }
            }
        }
        $theResult['query'] = implode(",", $result);
        $theResult['arrayValues'] = $arrayValues;
        return $theResult;
    }

    /**
     * Get object of given class with given id from
     * good table and according with given class name.
     * Can return null if no corresponding object.
     * @param long $id Id of object to get
     * @param string $class_name
     * @return unknown_type
     */
    protected function advanced_get($id, $table_name)
    {
        if (strlen($id) == 0) {
            return null;
        }
        $object_name = $table_name;
        $table_id = $table_name . '_id';

        if(isset(self::$specific_id) && !empty(self::$specific_id)) {
            $table_id = self::$specific_id;
        }

        self::$db = new Database();
        
        $select = "select * from $table_name where $table_id=?";

        $stmt = self::$db->query($select, array($id));
        if ($stmt->rowCount() == 0) {
            return null;
        } else {
            // Constructing result
            $object = new $object_name();
            $queryResult = $stmt->fetchObject();
            foreach ((array)$queryResult as $key => $value) {
                if ($value == 't') {          /* BUG FROM PGSQL DRIVER! */
                    $value = true;            /*                        */
                } elseif ($value == 'f') {    /*                        */
                    $value = false;           /*                        */
                }                            /**************************/
                $object->{$key} = $value;
            }
        }

        return $object;
    }

    /**
     * Get object of given class with given id from
     * good table and according with given class name.
     * Can return null if no corresponding object.
     * @param long $id Id of object to get
     * @param string $class_name
     * @return unknown_type
     */
    protected function advanced_getWithComp($id, $table_name, $whereComp='', $params=array())
    {
        if (strlen($id) == 0) {
            return null;
        }

        $object_name = $table_name;
        $table_id = $table_name . '_id';

        if(isset(self::$specific_id) && !empty(self::$specific_id)) {
            $table_id = self::$specific_id;
        }

        $database = new Database();
        if($table_name == 'users'){ 
            $theQuery = "SELECT * FROM $table_name WHERE upper($table_id) = upper(:id) " . $whereComp;
        }else{
            $theQuery = "SELECT * FROM $table_name WHERE $table_id = :id " . $whereComp;
        }
        //$theQuery = "SELECT * FROM $table_name WHERE $table_id = :id " . $whereComp;
        $queryParams = array(':id' => $id);

        if (count($params > 0)) {
            foreach ($params as $keyParam => $keyValue) {
                $queryParams[":" . $keyParam] = $keyValue;
            }
        }

        $stmt = $database->query($theQuery, $queryParams);
        
        if ($stmt->rowCount() == 0) {
            return null;
        } else {
            // Constructing result
            $object = new $object_name();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);    
            
            for ($cpt=0;$cpt<count($rows);$cpt++) {
                foreach ($rows[$cpt] as $key => $value) {
                    if ($value == 't') {          /* BUG FROM PGSQL DRIVER! */
                        $value = true;            /*                        */
                    } elseif ($value == 'f') {    /*                        */
                        $value = false;           /*                        */
                    }                            /**************************/
                    $object->{$key} = $value;
                }
            }
        }

        return $object;
    }

     /**
     * Delete given object from given table, according with
     * given table id name.
     * Return true if succeeded.
     * @param Any $object
     * @return boolean
     */
    protected function advanced_delete($object)
    {
        if (!isset($object)){
            return false;
        }
        $table_name = get_class($object);
        $table_id = $table_name . '_id';

        if (isset(self::$specific_id) && !empty(self::$specific_id)) {
            $table_id = self::$specific_id;
        }
        self::$db = new Database();
    
        $query = "delete from $table_name where $table_id=?";
    
        $stmt = self::$db->query($query, array($object->{$table_id}));
        
        if ($stmt) {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Enable given object from given table, according with
     * given table id name.
     * Return true if succeeded.
     * @param Any $object
     * @return boolean
     */
    protected function advanced_enable($object)
    {
        if (!isset($object)) {
            return false;
        }
        $table_name = get_class($object);
        $table_id = $table_name . '_id';

        if (isset(self::$specific_id) && !empty(self::$specific_id)) {
            $table_id = self::$specific_id;
        }
        self::$db = new Database();
        
        $query="update $table_name set enabled = 'Y' where $table_id=?";
        
        $stmt = self::$db->query($query, array($object->{$table_id}));
        
        if ($stmt) {
            $result = true;
        } else {
            $result = false;
        }
        
        return $result;
    }

    /**
     * Reactivate given object from given table, according with
     * given table id name.
     * Return true if succeeded.
     * @param Any $object
     * @return boolean
     */
    protected function advanced_reactivate($object)
    {
        if (!isset($object)) {
            return false;
        }
        $table_name = get_class($object);

        if (isset(self::$specific_id) && !empty(self::$specific_id)) {
            $table_id = self::$specific_id;
        }
        self::$db = new Database();
        
        $query="update $table_name set status = 'OK' where user_id = lower(?)";
                
        $stmt = self::$db->query($query, array($object->{$table_id}));
        
        if ($stmt) {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Disable given object from given table, according with
     * given table id name.
     * Return true if succeeded.
     * @param Any $object
     * @return boolean
     */
    protected function advanced_disable($object)
    {
        if (!isset($object)) {
            return false;
        }
        $table_name = get_class($object);
        $table_id=$table_name."_id";

        if (isset(self::$specific_id) && !empty(self::$specific_id)) {
            $table_id = self::$specific_id;
        }
        self::$db = new Database();
        
        $query = "update $table_name set enabled = 'N' where $table_id=?";
        
        $stmt = self::$db->query($query, array($object->{$table_id}));
        
        if ($stmt) {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }
}
