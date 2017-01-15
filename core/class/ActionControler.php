<?php

/*
*    Copyright 2008-2015 Maarch
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
* @brief  Contains the controler of the Action Object (create, save, modify, etc...)
*
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

// Loads the required class
try {
	require_once('core/class/class_db_pdo.php');
	require_once('core/class/Action.php');
	require_once('core/core_tables.php');
 // require_once('core/class/ObjectControlerIF.php');
    require_once('core/class/ObjectControlerAbstract.php');
    require_once('core/class/class_history.php');
} catch (Exception $e) {
	functions::xecho($e->getMessage()) . ' // ';
}

/**
* @brief  Controler of the Action Object
*
*<ul>
*  <li>Get an action object from an id</li>
*  <li>Save in the database an action</li>
*  <li>Manage the operation on the action related tables in the database (insert, select, update, delete)</li>
*</ul>
* @ingroup core
*/
class ActionControler
{
	/**
	* Database object used to connnect to the database
    */
	private static $db;

	/**
	* Actions table
    */
	public static $actions_table ;
	
	/**
	* Actions_groupbaskets_table table
    */
	public static $actions_groupbaskets_table ;

	/**
	* Opens a database connexion and values the tables variables
	*/
	public static function connect()
	{
		$db = new Database();
		
		self::$actions_table = $_SESSION['tablename']['actions'];
		self::$actions_groupbaskets_table = $_SESSION['tablename']['bask_actions_groupbaskets'];

		self::$db=$db;
	}

	/**
	* Returns an Action Object based on a action identifier
	*
	* @param  $action_id string  Action identifier
	* @return Action object with properties from the database or null
	*/
	public static function get($action_id)
	{
		
		if(empty($action_id)) {
			return null;
		}

		self::connect();
		$query = "select * from ".self::$actions_table." where id = ?";
		
		$stmt = self::$db->query($query, array($action_id));

		if($stmt->rowCount() > 0)
		{
			$action = new Action();
			$queryResult=$stmt->fetchObject();
			foreach($queryResult as $key => $value){
				$action->{$key}=$value;
			}
			return $action;
		}
		else
		{
			return null;
		}
	}


	/**
	* Returns an Action array of Object based on all action
	*
	* @return Action array of objects with properties from the database or null
	*/
	public function getAllActions()
	{
		self::connect();
		$query = "select * from ".self::$actions_table;

		$stmt = self::$db->query($query);

		if($stmt->rowCount() > 0)
		{
			$actions_list = array();
			while($queryResult=$stmt->fetchObject()){
				$action = new Action();
				foreach($queryResult as $key => $value){
					$action->{$key}=$value;
				}
				array_push($actions_list, $action);
			}
			return $actions_list;
		}
		else
		{
			return null;
		}
	}

    /**
	* Returns an Categories array of categories linked to an action
	*
	* @return categories array 
	*/
	public static function getAllCategoriesLinkedToAction($actionId)
	{
		self::connect();
		$query = "select category_id from actions_categories where action_id = ?";

		$stmt = self::$db->query($query, array($actionId));

		if ($stmt->rowCount() > 0) {
			$categories_list = array();
			while($queryResult=$stmt->fetchObject()){
				array_push($categories_list, $queryResult->category_id);
			}
			return $categories_list;
		} else {
			return null;
		}
	}
    
    
	/**
	* Saves in the database an Action object
	*
	* @param  $group Action object to be saved
	* @param  $mode string  Saving mode : add or up
	* @return bool true if the save is complete, false otherwise
	*/
	public static function save($action, $mode)
	{
		if(!isset($action)) {
			return false;
		}
		if($mode == "up") {
			return self::update($action);
            
		}
		elseif($mode =="add"){
			return self::insert($action);
        }
		return false;
	}

    /**
	* if action_page = _ raz
	*
	* 
	* @return bool true if raz ok
	*/
	public static function razActionPage()
	{
        $dbUp = new Database();
        $return = self::update($action);
        $query="update " . self::$actions_table 
        	. " set action_page = '' where action_page = '_'";
        $dbUp->query($query);
        return true;
	}

	/**
	* Inserts in the database (actions table) an Action object
	*
	* @param  $action Action object
	* @return bool true if the insertion is complete, false otherwise
	*/
	private static function insert($action)
	{
		if(!isset($action))
			return false;

		self::connect();
		$prep_query = self::insert_prepare($action);
		$query="insert into ".self::$actions_table." ("
					.$prep_query['COLUMNS']
					.") values("
					.$prep_query['VALUES']
					.")";

		$stmt = self::$db->query($query, $prep_query['ARRAY_VALUES']);
		$ok = true;

		return $ok;
	}

	/**
	* Updates a action in the database (action table) with a Action object
	*
	* @param  $action Action object
	* @return bool true if the update is complete, false otherwise
	*/
	private static function update($action)
	{
		if(!isset($action) )
			return false;

		self::connect();
		$prep_query = self::update_prepare($action);
		$query="update ".self::$actions_table." set "
					. $prep_query['QUERY']
					. " where id=?";

		$prep_query['VALUES'][] = $action->id;

		$stmt = self::$db->query($query, $prep_query['VALUES']);
		$ok = true;
		
		return $ok;
	}

	/**
	* Deletes in the database (actions table) a given action (action_id)
	*
	* @param  $action_id string  Action identifier
	* @return bool true if the deletion is complete, false otherwise
	*/
	public static function delete($action_id)
	{
		if(!isset($action_id)|| empty($action_id) )
			return false;
		if(!self::actionExists($action_id))
			return false;

		self::connect();
		$query="delete from ".self::$actions_table." where id=?";

		self::$db->query($query, array($action_id));
		$ok = true;

		if($ok)
			self::cleanActionsGroupbasket($action_id);


		return $ok;
	}

	/**
	* Cleans the actions_groupbasket table in the database from a given action (action_id)
	*
	* @param  $action_id string  Action identifier
	* @return bool true if the cleaning is complete, false otherwise
	*/
	public static function cleanActionsGroupbasket($action_id)
	{
		if(!isset($action_id)|| empty($action_id) )
			return false;

		self::connect();
		$query="delete from ".self::$actions_groupbaskets_table."  where id_action=?";

		$stmt = self::$db->query($query, array($action_id));
		$ok = true;

		return $ok;
	}

	/**
	* Asserts if a given action (action_id) exists in the database
	*
	* @param  $action_id String Action identifier
	* @return bool true if the action exists, false otherwise
	*/
	public static function actionExists($action_id)
	{
		if(!isset($action_id) || empty($action_id))
			return false;

		self::connect();
		$query = "select id from ".self::$actions_table." where id = ?";

		$stmt = self::$db->query($query, array($action_id));		

		if($stmt->rowCount() > 0)
		{
			return true;
		}
		return false;
	}

	/**
	* Prepares the update query for a given Action object
	*
	* @param  $action Action object
	* @return String containing the fields and the values
	*/
	private static function update_prepare($action)
	{
		$result=array();
		$arrayValues=array();
		foreach($action->getArray() as $key => $value)
		{
			if(!empty($value))
			{
				$result[]=$key."=?";
				$arrayValues[]=$value;
			}
		}

		return array(
			'QUERY' => implode(",",$result), 
			'VALUES' => $arrayValues,
		);
	}

	/**
	* Prepares the insert query for a given Action object
	*
	* @param  $action Action object
	* @return Array containing the fields and the values
	*/
	private function insert_prepare($action)
	{
		$columns=array();
		$values=array();
		$arrayValues=array();
		foreach($action->getArray() as $key => $value)
		{
			//For now all fields in the actions table are strings or dates
			if(!empty($value))
			{
				$columns[]=$key;
				$values[]="?";
				$arrayValues[]=$value;
			}
		}
		return array(
			'COLUMNS' => implode(",",$columns), 
			'VALUES' => implode(",",$values),
			'ARRAY_VALUES' => $arrayValues
		);
	}
    
    /**
    * Return the last actionId
    * 
    * @return bigint actionId
    */
    public function getLastActionId($actionLabel)
    {
        $query = "select id from " . ACTIONS_TABLE
            . " where label_action = ?"
            . " order by id desc";
        $stmt = self::$db->query($query, array($actionLabel));
        $queryResult = $stmt->fetchObject();
        return $queryResult->id;
    }
    
    public function saveCategoriesAssociation($actionId)
    {
        self::$db->query("delete from " . ACTIONS_CATEGORIES_TABLE_NAME 
            . " where action_id = ?", array($actionId)
        );
        for ($i=0;$i<count($_SESSION['m_admin']['action']['categoriesSelected']);$i++) {
            self::$db->query("insert into " . ACTIONS_CATEGORIES_TABLE_NAME 
                . " (action_id, category_id) VALUES (?, ?)"
            	, array($actionId, $_SESSION['m_admin']['action']['categoriesSelected'][$i])
            );
        }
    }
}
