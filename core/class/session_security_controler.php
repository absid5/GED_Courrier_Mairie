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
* @brief  Contains the controler of the session_security object (create, save, modify, etc...)
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
	require_once("core/core_tables.php");
	require_once("core/class/session_security.php");
	require_once("core/class/ObjectControlerAbstract.php");
	require_once("core/class/ObjectControlerIF.php");
} catch (Exception $e){
	echo functions::xssafe($e->getMessage()).' // ';
}

/**
* @brief  Controler of the session_security object
*
*<ul>
*  <li>Get an session_security object for a given user_id</li>
*  <li>Save in the database a session_security</li>
*  <li>Manage the operation on the users related tables in the database (insert, select, update, delete)</li>
*</ul>
* @ingroup core
*/
class session_security_controler extends ObjectControler implements ObjectControlerIF
{
	/**
	* Returns an session_security object based on a user identifier
	*
	* @param  $user_id string  User identifier
	* @param  $comp_where string  where clause arguments (must begin with and or or)
	* @return session_security object with properties from the database or null
	*/
	public function get($user_id, $comp_where = '')
	{
		$this->set_foolish_ids(array('user_id'));
		$this->set_specific_id('user_id');
		$session_security = $this->advanced_get($user_id,SESSION_SECURITY_TABLE);

		if(isset($session_security) )
			return $session_security;
		else
			return null;
	}


	/**
	* Saves in the database a session_security object
	*
	* @param  $session_security session_security object to be saved
	* @return bool true if the save is complete, false otherwise
	*/
	public function save($session_security)
	{
		if(!isset($session_security) )
			return false;

		$this->set_foolish_ids(array('user_id'));
		$this->set_specific_id('user_id');
		if($this->sessionSecurityExists($session_security->user_id))
			return $this->update($session_security);
		else
			return $this->insert($session_security);

		return false;
	}

	/**
	* Inserts in the database (session_security table) a session_security object
	*
	* @param  $session_security session_security object
	* @return bool true if the insertion is complete, false otherwise
	*/
	private function insert($session_security)
	{
		return $this->advanced_insert($session_security);
	}

	/**
	* Updates a session_security in the database (session_security table) with a session_security object
	*
	* @param  $session_security session_security object
	* @return bool true if the update is complete, false otherwise
	*/
	private function update($session_security)
	{
		return $this->advanced_update($session_security);
	}

	/**
	* Deletes in the database a given session_security
	*
	* @param  $session_security session_security object
	* @return bool true if the deletion is complete, false otherwise
	*/
	public function delete($session_security)
	{
		$this->set_foolish_ids(array('user_id'));
		$this->set_specific_id('user_id');
		return $this->advanced_delete($session_security);
	}


	/**
	* Asserts if a session_security exists in the database for a given user (user_id)
	*
	* @param  $user_id String User identifier
	* @return bool true if the user exists, false otherwise
	*/
	public function sessionSecurityExists($user_id)
	{
		if(!isset($user_id) || empty($user_id))
			return false;

		$db = new Database();
		$query = "select user_id from ".SESSION_SECURITY_TABLE." where user_id = ?";

		try {
			$stmt = $db->query($query, array($user_id));
		} catch (Exception $e){
			echo _UNKNOWN.' '._USER." ".functions::xssafe($user_id).' // ';
		}

		if($stmt->rowCount() > 0)
		{
			return true;
		}
		return false;
	}

}
