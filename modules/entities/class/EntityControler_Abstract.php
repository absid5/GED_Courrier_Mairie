<?php
/*
*    Copyright 2008-2016 Maarch
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
* @brief  Contains the controler of the Basket Object (create, save, modify, etc...)
*
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

// To activate de debug mode of the class
$_ENV['DEBUG'] = false;
/*
define("_CODE_SEPARATOR","/");
define("_CODE_INCREMENT",1);
*/

// Loads the required class
try {
    require_once("core/class/class_db.php");
    require_once("modules/entities/class/Entity.php");
    require_once("modules/entities/entities_tables.php");
} catch (Exception $e){
    functions::xecho($e->getMessage()).' // ';
}

/**
* @brief  Controler of the Entity Object
*
*<ul>
*  <li>Get an entity object from an id</li>
*  <li>Save in the database an entity</li>
*  <li>Manage the operation on the entities related tables in the database (insert, select, update, delete)</li>
*</ul>
* @ingroup core
*/
abstract class EntityControler_Abstract
{
    /**
    * Db query object used to connnect to the database
    */
    private static $db;

    /**
    * Entities table
    */
    public static $entities_table ;

    /**
    * Users_entities table
    */
    public static $users_entities_table ;

    /**
    * Groupbasket_redirect table
    */
    public static $groupbasket_redirect_table ;

    /**
    * Opens a database connexion and values the tables variables
    */


    /**
    * Close the database connexion
    */


    /**
    * Returns an Entity Object based on a entity identifier
    *
    * @param  $entity_id string Entity identifier
    * @param  $can_be_disabled bool  if true gets the basket even if it is disabled in the database (false by default)
    * @return User object with properties from the database or null
    */
    public function get($entity_id, $can_be_disabled = false)
    {
        if(empty($entity_id))
            return null;

        $db = new database();

        $query = "select * from ".ENT_ENTITIES." where entity_id = ?";
        if(!$can_be_disabled)
            $query .= " and enabled = 'Y'";

        try{
            $stmt = $db->query($query,array($entity_id));
        } catch (Exception $e){
            echo _NO_ENTITY_WITH_ID.' '.$entity_id.' // ';
        }
        if($stmt->rowCount() > 0)
        {
            $entity = new EntityObj();
            $queryResult=$stmt->fetchObject();
            foreach($queryResult as $key => $value){
                $entity->{$key}=$value;
            }
            
            return $entity;
        }
        else
        {
            
            return null;
        }
    }

    /**
    * Returns all entities (enabled by default) from the database in an array of Entity Objects (ordered by group_desc by default)
    *
    * @param  $order_str string  Order string passed to the query ("order by short_label asc" by default)
    * @param  $enabled_only bool  if true returns only the enabled entities, otherwise returns even the disabled (true by default)
    * @return Array of Entity objects with properties from the database
    */
    public function getAllEntities($order_str = "order by short_label asc", $enabled_only = true)
    {
        $db = new database();
        $query = "select * from ".ENT_ENTITIES." ";
        if($enabled_only)
            $query .= "where enabled = 'Y'";

        $query.= $order_str;

        try{
            $stmt = $db->query($query);
        } catch (Exception $e){}

        $entities = array();
        while($res = $stmt->fetchObject())
        {
            $ent=new EntityObj();
            foreach($res as $key => $value)
                $tmp_array[$key] = $value;

            $ent->setArray($tmp_array);
            array_push($entities, $ent);
        }
        
        return $entities;
    }

        /**
    * Returns entities of current user from the database in an array of Entity Objects (ordered by group_desc by default)
    *
    * @param  $order_str string  Order string passed to the query ("order by short_label asc" by default)
    * @param  $enabled_only bool  if true returns only the enabled entities, otherwise returns even the disabled (true by default)
    * @return Array of Entity objects with properties from the database
    */
    public function getEntitiesUser($entity_id)
    {
        $db = new database();

        $entities=self::getEntityArbo($entity_id);


        return $entities;
    }
    
    public function getEntityArbo($parent)
    {
       $db = new Database();
       $entities=array();

       $stmt = $db->query("SELECT * from entities WHERE parent_entity_id = ? and enabled = 'Y'",array($parent));
       while($res = $stmt->fetchObject())
       {   
           $ent=new EntityObj();
           foreach($res as $key => $value)
            $tmp_array[$key] = $value;

            $ent->setArray($tmp_array);
            array_push($entities, $ent);
            $entities=array_merge(self::getEntityArbo($res->entity_id),$entities);
        }
        return $entities;
    }
    
    public function getEntityParentTreeOf($entityId)
    {
        $entities = array();
        if ($entityId <> '') {
            $db = new Database();
           

            $stmt = $db->query("SELECT parent_entity_id from entities WHERE entity_id = ? and enabled = 'Y'",array($entityId));
            while ($res = $stmt->fetchObject()) {
                $ent=new EntityObj();
                foreach($res as $key => $value)
                    $tmp_array[$key] = $value;

                $ent->setArray($tmp_array);
                array_push($entities, $ent);
                $entities=array_merge(self::getEntityParentTreeOf($res->parent_entity_id),$entities);
            }
        } else {
        
        }
        return $entities;
    }

    /**
    * Returns entities of current user from the database in an array of Entity Objects (ordered by group_desc by default)
    *
    * @param  $entities Array of Entities Objects  entities  will be sort
    * @return Array of Entity objects sorted
    */
    public function sortEntities($entities)
    {
        $arr=array();
        foreach($entities as $key => $value){
            array_push($arr, "'".$value->entity_id."'");
        }
        $tmp_entities=join(',',$arr);   

        $db = new Database();
        $arr=array();
        $query="Select * from ".ENT_ENTITIES." WHERE entity_id IN (?) order by short_label asc";
        $stmt = $db->query($query,array($tmp_entities));
        while($res = $stmt->fetchObject())
        {
            $ent=new EntityObj();
            foreach($res as $key => $value)
                $tmp_array[$key] = $value;

            $ent->setArray($tmp_array);
            array_push($arr, $ent);
        }
        return $arr;
    }

    /**
    * Returns in an array all the members of an entity (user_id only)
    *
    * @param  $user_id string  User identifier
    * @return Array (user_id, user_role, primary_entity) or null
    */
    public function getUsersEntities($user_id)
    {
        if(empty($user_id))
            return null;

        $db = new Database();
        $func = new functions();
        $query = "SELECT ue.entity_id, ue.user_role, ue.primary_entity from ". ENT_USERS_ENTITIES." ue, ".ENT_ENTITIES." u where ue.user_id = ? and ue.entity_id = u.entity_id and u.enabled = 'Y'";

        try{
            $stmt = $db->query($query,array($user_id));
        } catch (Exception $e){
            echo _NO_USER_WITH_ID.' '.$user_id.' // ';
        }
        $entities = array();
        while($res= $stmt->fetchObject())
        {
            array_push($entities, array('USER_ID' => $user_id, 'ENTITY_ID' => $res->entity_id, 'PRIMARY' => $res->primary_entity, 'ROLE' => $res->user_role));
        }

        return $entities;
    }

    /**
    * Saves in the database an entity object
    *
    * @param  $entity Entity object to be saved
    * @param  $mode string  Saving mode : add or up
    * @return bool true if the save is complete, false otherwise
    */
    public function save($entity, $mode)
    {
        if(!isset($entity) )
            return false;

        if($mode == "up")
            return self::update($entity);
        elseif($mode =="add")
            return self::insert($entity);

        return false;
    }

    /**
    * Inserts in the database (entities table) an Entity object
    *
    * @param  $entity Entity object
    * @return bool true if the insertion is complete, false otherwise
    */
    protected function insert($entity)
    {
        if(!isset($entity) )
            return false;

        $db = new Database();
        $prep_query = self::insert_prepare($entity);

        $query="insert into ".ENT_ENTITIES." (?) values(?)";
        try{
            $stmt = $db->query($query,array($prep_query['COLUMNS'],$prep_query['VALUES']));
            $ok = true;
        } catch (Exception $e){
            echo _CANNOT_INSERT_ENTITY." ".$entity->toString().' // ';
            $ok = false;
        }
        
        return $ok;
    }

    /**
    * Updates an entity in the database (entities table) with an Entity object
    *
    * @param  $entity Entity object
    * @return bool true if the update is complete, false otherwise
    */
    protected function update($entity)
    {
        if(!isset($entity) )
            return false;

        $query="update ".ENT_ENTITIES." set "
                    .self::update_prepare($entity)
                    ." where entity_id= ?";

        try{
            $stmt = $db->query(array($entity->entity_id));
            $ok = true;
        } catch (Exception $e){
            echo _CANNOT_UPDATE_ENTITY." ".$entity->toString().' // ';
            $ok = false;
        }
        return $ok;
    }

    /**
    * Deletes in the database (entities related tables) a given entity (entity_id)
    *
    * @param  $entity_id string  Entity identifier
    * @return bool true if the deletion is complete, false otherwise
    */
    public function delete($entity_id)
    {
        if(!isset($entity_id)|| empty($entity_id) )
            return false;
        if(! self::userExists($entity_id))
            return false;

        self::connect();
        $query="delete from ".ENT_ENTITIES."  where entity_id= ?";

        try{
            $stmt = $db->query($query,array($entity_id));
            $ok = true;
        } catch (Exception $e){
            echo _CANNOT_DELETE_ENTITY_ID." ".$entity_id.' // ';
            $ok = false;
        }

        if($ok)
            $ok = cleanGroupbasketRedirect($entity_id);

        if($ok)
            $ok = cleanUsersentities($entity_id);

        return $ok;
    }

    /**
    * Cleans the users_entities table in the database from a given field (entity_id by default)
    *
    * @param  $id string  object identifier
    * @param  $field string  Field name (entity_id by default)
    * @return bool true if the cleaning is complete, false otherwise
    */
    public function cleanUsersentities($id, $field = 'entity_id')
    {
        if(!isset($id)|| empty($id) )
            return false;

        $db = new Database();
        $query="delete from ".ENT_USERS_ENTITIES." where ".$field."= ?";

        try{
            $stmt = $db->query($query,array($id));
            $ok = true;
        } catch (Exception $e){
            echo _CANNOT_DELETE.' '.$field." ".$id.' // ';
            $ok = false;
        }

        return $ok;
    }

    /**
    * Cleans the groupbasket_redirect table in the database from a given field (entity_id by default)
    *
    * @param  $id string  object identifier
    * @param  $field string  Field name (entity_id by default)
    * @return bool true if the cleaning is complete, false otherwise
    */
    public function cleanGroupbasketRedirect($id, $field = 'entity_id')
    {
        if(!isset($id)|| empty($id) )
            return false;

        $db = new Database();
        $query="delete from ".$_SESSION['tablename']['ent_groupbasket_redirect']." where ".$field."= ?";

        try{
            $stmt = $db->query($query,array($id));
            $ok = true;
        } catch (Exception $e){
            echo _CANNOT_DELETE.' '.$field." ".$id.' // ';
            $ok = false;
        }

        return $ok;
    }

    /**
    * Asserts if a given entity (entity_id) exists in the database
    *
    * @param  $entity_id String Entity identifier
    * @return bool true if the basket exists, false otherwise
    */
    public function entityExists($entity_id)
    {
        if(!isset($entity_id) || empty($entity_id))
            return false;

        $query = "select entity_id from ".ENT_ENTITIES." where entity_id = ?";

        try{
            $stmt = $db->query($query,array($entity_id));
        } catch (Exception $e){
            echo _UNKNOWN.' '._ENTITY." ".$entity_id.' // ';
        }

        if($stmt->rowCount() > 0)
        {
            
            return true;
        }
        
        return false;
    }

    /**
    * Prepares the update query for a given Entity object
    *
    * @param  $entity Entity object
    * @return String containing the fields and the values
    */
    protected function update_prepare($entity)
    {
        $result=array();
        foreach($entity->getArray() as $key => $value)
        {
            // For now all fields in the users table are strings or dates
            if(!empty($value))
            {
                $result[]=$key."='".$value."'";
            }
        }
        // Return created string minus last ", "
        return implode(",",$result);
    }

    /**
    * Prepares the insert query for a given Entity object
    *
    * @param  $entity Entity object
    * @return Array containing the fields and the values
    */
    protected function insert_prepare($entity)
    {
        $columns=array();
        $values=array();
        foreach($entity->getArray() as $key => $value)
        {
            //For now all fields in the users table are strings or dates
            if(!empty($value))
            {
                $columns[]=$key;
                $values[]="'".$value."'";
            }
        }
        return array('COLUMNS' => implode(",",$columns), 'VALUES' => implode(",",$values));
    }

    /**
    * Disables a given entity
    *
    * @param  $entity_id String Entity identifier
    * @return bool true if the disabling is complete, false otherwise
    */
    public function disable($entity_id)
    {
        if(!isset($entity_id)|| empty($entity_id) )
            return false;
        if(! self::entityExists($entity_id))
            return false;

        $db = new Database();
        $query="update ".ENT_ENTITIES." set enabled = 'N' where entity_id= ?";

        try{
            $stmt = $db->query($query,$entity_id);
            $ok = true;
        } catch (Exception $e){
            echo _CANNOT_DISABLE_ENTITY." ".$entity_id.' // ';
            $ok = false;
        }
        return $ok;
    }

    /**
    * Enables a given entity
    *
    * @param  $entity_id String Entity identifier
    * @return bool true if the enabling is complete, false otherwise
    */
    public function enable($entity_id)
    {
        if(!isset($entity_id)|| empty($entity_id) )
            return false;
        if(! self::entityExists($entity_id))
            return false;

        self::connect();
        $query="update ".ENT_ENTITIES." set enabled = 'Y' where entity_id='".$entity_id."'";

        try{
            $stmt = $db->query($query,array($entity_id));
            $ok = true;
        } catch (Exception $e){
            echo _CANNOT_ENABLE_ENTITY." ".$entity_id.' // ';
            $ok = false;
        }
        return $ok;
    }

    /**
    * Returns the number of entities of the entities table (only the enabled by default)
    *
    * @param  $enabled_only Bool if true counts only the enabled ones, otherwise counts all entities even the disabled ones (true by default)
    * @return Integer the number of entities in the entities table
    */
    public function getEntitiesCount($enabled_only = true)
    {
        $nb = 0;
        $db = new Database();

        $query = "select entity_id  from ".ENT_ENTITIES." " ;
        if($enabled_only)
            $query .= "where enabled ='Y'";

        try{
            $stmt = $db->query($query);
        } catch (Exception $e){}

        $nb = $stmt->rowCount();
        return $nb;
    }

    /**
    * Loads into the users_entities table the given data for a given user
    *
    * @param  $user_id String User identifier
    * @param  $array Array
    * @return bool true if the loadng is complete, false otherwise
    */
    public function loadDbUsersentities($user_id, $array)
    {
        if(!isset($user_id)|| empty($user_id) )
            return false;
        if(!isset($array) || count($array) == 0)
            return false;

        $ok = true;
        $func = new functions();
        for($i=0; $i < count($array ); $i++)
        {
            if($ok)
            {
                $query = "INSERT INTO ".ENT_USERS_ENTITIES." (user_id, entity_id, primary_entity, user_role) VALUES (?, ?, ?, ?)";
                try{
                    $db = new database();
                    $stmt = $db->query($query,array($user_id,$array[$i]['ENTITY_ID'],$array[$i]['PRIMARY'],$array[$i]['ROLE']));
                    $ok = true;
                } catch (Exception $e){
                    $ok = false;
                }
            }
            else
                break;
        }
        return $ok;
    }
}
?>
