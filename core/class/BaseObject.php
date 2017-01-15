<?php
/*
*    Copyright 2008,2009,2010 Maarch
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
 * This object aims at giving a 
 * standard structure for objects
 * that come directly from the 
 * table of a database.
 * 
 * @author Claire Figueras <dev@maarch.org>
 * @author Boulio Nicolas
 *
 */
require_once("core/class/class_functions.php");

class BaseObject {
	protected $data = array(); 

	/**
	 * Initializes an object
	 */
	public function __construct(){
	}

	/**
	 * Sets value of a property of current object
	 * 
	 * @param string $name Name of property to set
	 * @param object $value Value of property $name
	 * @return boolean True if the set is ok, false otherwise
	 */
	public function __set($name, $value)
	{
		if(isset($name))
		{
			$this->data[$name] = $value;
			return true;
		}
		return false;
		
	}

	/**
	 * Gets value of a property of current object
	 * 
	 * @param string $name Name of property to get
	 * @return string Value of $name  or null
	 * @exception $e Exception Sent if $name does not exist
	 */
	public function __get($name) {
		try {
			if (isset($this->data[$name])) return $this->data[$name];
		} catch (Exception $e) {
			echo 'Exception catched: '.functions::xssafe($e->getMessage()).', null returned<br/>';
			return null;
		}
	}

	/**
	 * Checks if a given property is set in the current object
	 * 
	 * @param string $name Name of property to check
	 * @return Bool
	 */
	public function __isset($name)
	{
		if (isset($this->data[$name])) 
			return (false === empty($this->data[$name]));
		 else 
			return false;
	        
	}
	
	/**
	 * Gets values of all properties of the current object in an array
	 * 
	 * @return Array properties ( key=>value)
	 */
	public function getArray() 
	{
		if(is_null($this->data))
			return null;
		else
			return $this->data;
	}
	
    /**
    * Sets an array in the current object
    */
	public function setArray($array) 
	{
		$this->data = $array;
	}
    
	/**
    * Get label of a given property 
    *
    * @return String label
    */
	public function getLabel($name){
		if(in_array($name, array_keys($data))){
			return eval("_".strtoupper($name));
		} else {
			return "";
		}
	}
    
    /**
	* Delete a given property in the current object
	* 
	* @param string $name Name of property to delete
	*/
    public function __unset($name)
	{
        unset($this->data[$name]);
    }
}
?>
