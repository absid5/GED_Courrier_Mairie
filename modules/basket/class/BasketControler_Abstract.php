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
* @brief  Contains the controler of the Basket Object
* (create, save, modify, etc...)
*
*
* @file
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/


// Loads the required class
try {
	require_once 'core/class/class_db.php';
	require_once 'modules/basket/class/Basket.php';
	require_once 'modules/basket/basket_tables.php';
} catch (Exception $e){
	functions::xecho($e->getMessage()).' // ';
}

/**
* @brief  Controler of the Basket Object
*
*<ul>
*  <li>Get an basket object from an id</li>
*  <li>Save in the database a basket</li>
*  <li>Manage the operation on the baskets related tables in the database
*  (_insert, select, _update, delete)</li>
*</ul>
* @ingroup core
*/
abstract class BasketControler_Abstract
{

	/**
	* Returns a Basket Object based on a basket identifier
	*
	* @param  $basketId string Basket identifier
	* @param  $canBeDisabled bool  if true gets the basket even if it is
	*  disabled in the database (false by default)
	* @return User object with properties from the database or null
	*/
	public function get($basketId, $canBeDisabled=false)
	{
		if (! isset($basketId) || empty($basketId)) {
			return null;
		}
		$db = new Database();

		$query = "select * from " . BASKET_TABLE . " where basket_id = ?";
		if (! $canBeDisabled) {
			$query .= " and enabled = 'Y'";
		}
		try {
		    $stmt = $db->query($query,array($basketId));
		} catch (Exception $e) {
			echo _NO_BASKET_WITH_ID . ' ' . functions::xssafe($basketId) . ' // ';
		}
		if ($stmt->rowCount() > 0) {
			$basket = new Basket_obj();
			$queryResult = $stmt->fetchObject();
			foreach ($queryResult as $key => $value) {
				$basket->{$key} = $value;
			}
			return $basket;
		} else {
			return null;
		}
	}

	/**
	* Saves in the database a basket object
	*
	* @param  $basket Basket object to be saved
	* @param  $mode string  Saving mode : add or up
	* @return bool true if the save is complete, false otherwise
	*/
	public function save($basket, $mode)
	{
		if (! isset($basket)) {
			return false;
		}
		if ($mode == 'up') {
			return $this->_update($basket);
		} else if ($mode == 'add') {
			return $this->_insert($basket);
		}
		return false;
	}

	/**
	* Inserts in the database (baskets table) a Basket object
	*
	* @param  $basket Basket object
	* @return bool true if the _insertion is complete, false otherwise
	*/
	protected function _insert($basket)
	{
		if (! isset($basket)) {
			return false;
		}
		$prepQuery = $this->_insertPrepare($basket);
		$db = new Database();
		$query = "insert into " . BASKET_TABLE . " (" . $prepQuery['COLUMNS']
			   . ") values(?)";
		try {
			$stmt = $db->query($query,array($prepQuery['VALUES']));
			$ok = true;
		} catch (Exception $e) {
			echo _CANNOT_INSERT_BASKET . " " . functions::xssafe($basket->toString()) . ' // ';
			$ok = false;
		}

		return $ok;
	}

	/**
	* Updates a basket in the database (baskets table) with a Basket object
	*
	* @param  $basket Basket object
	* @return bool true if the _update is complete, false otherwise
	*/
	protected function _update($basket)
	{
		if (! isset($basket)) {
			return false;
		}
		$db = new Database();
		$query = "update " . BASKET_TABLE . " set "
		       . $this->_updatePrepare($basket) . " where basket_id=?";

		try {
			$stmt = $db->query($query,array($basket->basket_id));
			$ok = true;
		} catch (Exception $e) {
			echo _CANNOT_UPDATE_BASKET . " " . functions::xssafe($basket->toString()) . ' // ';
			$ok = false;
		}
		return $ok;
	}

	/**
	* Deletes in the database (baskets related tables) a given basket
	*  (basket_id)
	*
	* @param  $basketId string  Basket identifier
	* @return bool true if the deletion is complete, false otherwise
	*/
	public function delete($basketId)
	{
		if (! isset($basketId)|| empty($basketId)) {
			return false;
		}
		if (! $this->basketExists($basketId)) {
			return false;
		}
		$db = new Database();
		$query = "delete from " . BASKET_TABLE . " where basket_id=?";
		try {
			$stmt = $db->query($query,array($basketId));
			$ok = true;
		} catch (Exception $e) {
			echo _CANNOT_DELETE_BASKET_ID . " " . functions::xssafe($basketId) . ' // ';
			$ok = false;
		}

		if ($ok) {
			$ok = $this->cleanFullGroupbasket($basketId);
		}

		return $ok;
	}

	/**
	* Cleans the groupbasket and actions_groupbasket tables in the database
	* from a given field (basket_id by default)
	*
	* @param  $id string  object identifier
	* @param  $field string  Field name (basket_id by default)
	* @return bool true if the cleaning is complete, false otherwise
	*/
	public function cleanFullGroupbasket($id , $field='basket_id')
	{
		if (! isset($id )|| empty($id) || ! isset($field) || empty($field)) {
			return false;
		}
		$ok = $this->cleanGroupbasket($id, $field);

		if ($ok) {
			$ok = $this->cleanActionsGroupbasket($id, $field);
		}
		return $ok;
	}


	/**
	* Cleans the groupbasket table in the database from a given field
	*
	* @param  $id string  object identifier
	* @param  $field string  Field name
	* @return bool true if the cleaning is complete, false otherwise
	*/
	public function cleanGroupbasket($id, $field)
	{
		if (! isset($id) || empty($id) || ! isset($field) || empty($field)) {
			return false;
		}
		$db = new Database();
		$query = "delete from " . GROUPBASKET_TABLE . " where " . $field . "=?";
		try {
			$stmt = $db->query($query,array($id));
			$ok = true;
		} catch (Exception $e) {
			echo _CANNOT_DELETE . ' ' . functions::xssafe($field) . ' ' . functions::xssafe($id) . ' // ';
			$ok = false;
		}

		return $ok;
	}

	/**
	* Cleans the actions_groupbasket table in the database from a given field
	*
	* @param  $id string  object identifier
	* @param  $field string  Field name
	* @return bool true if the cleaning is complete, false otherwise
	*/
	public function cleanActionsGroupbasket($id, $field)
	{
		if (! isset($id) || empty($id) || !isset($field) || empty($field)) {
			return false;
		}
		$db = new Database();
		$query = "delete from " . ACTIONS_GROUPBASKET_TABLE . " where " . $field
		       . "=?";
		try {
			$stmt = $db->query($query,array($basketId));
			$ok = true;
		} catch (Exception $e) {
			echo _CANNOT_DELETE . ' ' . functions::xssafe($field) . ' ' . functions::xssafe($id) . ' // ';
			$ok = false;
		}

		return $ok;
	}

	/**
	* Prepares the _update query for a given Basket object
	*
	* @param  $basket Basket object
	* @return String containing the fields and the values
	*/
	protected function _updatePrepare($basket)
	{
		$result = array();
		foreach ($basket->getArray() as $key => $value) {
			// For now all fields in the baskets table are strings
			if (! empty($value)) {
				$result[] = $key . "='" . $value . "'";
			}
		}
		// Return created string minus last ", "
		return implode(",", $result);
	}

	/**
	* Prepares the _insert query for a given Basket object
	*
	* @param  $basket Basket object
	* @return Array containing the fields and the values
	*/
	protected function _insertPrepare($basket)
	{
		$columns = array();
		$values = array();
		foreach ($basket->getArray() as $key => $value) {
			//For now all fields in the baskets table are strings or dates
			if (! empty($value)) {
				$columns[] = $key;
				$values[] = "'" . $value . "'";
			}
		}
		return array(
			'COLUMNS' => implode(",", $columns),
			'VALUES' => implode(",", $values),
		);
	}

	/**
	* Disables a given basket
	*
	* @param  $basketId String Basket identifier
	* @return bool true if the disabling is complete, false otherwise
	*/
	public function disable($basketId)
	{
		if (! isset($basketId) || empty($basketId)) {
			return false;
		}
		if (! $this->basketExists($basketId)) {
			return false;
		}
		$db = new Database();
		$query = "update " . BASKET_TABLE . " set enabled = 'N' "
		       . "where basket_id=?";

		try {
			$db->query($query,array($basketId));
			$ok = true;
		} catch (Exception $e) {
			echo _CANNOT_DISABLE_BASKET . " " . functions::xssafe($basketId) . ' // ';
			$ok = false;
		}

		return $ok;
	}

	/**
	* Enables a given basket
	*
	* @param  $basketId String Basket identifier
	* @return bool true if the enabling is complete, false otherwise
	*/
	public function enable($basketId)
	{
		if (! isset($basketId) || empty($basketId)) {
			return false;
		}
		if (! $this->basketExists($basketId)) {
			return false;
		}

		$db = new Database();
		$query = "update " . BASKET_TABLE . " set enabled = 'Y' "
		       . "where basket_id=?";

		try {
			$stmt = $db->query($query,array($basketId));
			$ok = true;
		} catch (Exception $e) {
			echo _CANNOT_ENABLE_BASKET . " " . functions::xssafe($basketId) . ' // ';
			$ok = false;
		}

		return $ok;
	}

	/**
	* Asserts if a given basket (basket_id) exists in the database
	*
	* @param  $basketId String Basket identifier
	* @return bool true if the basket exists, false otherwise
	*/
	public function basketExists($basketId)
	{
		if (! isset($basketId) || empty($basketId)) {
			return false;
		}
		$db = new Database();
		$query = "select basket from " . BASKET_TABLE . " where basket_id = ?";

		try {
			$stmt = $db->query($query,array($basketId));
		} catch (Exception $e) {
			echo _UNKNOWN . ' ' . _BASKET . " " . functions::xssafe($basketId) . ' // ';
		}

		if ($stmt->rowCount() > 0) {
			return true;
		}

		return false;
	}
}
