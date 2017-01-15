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
* @brief  Contains the Bakset Object (herits of the BaseObject class)
*
*
* @file
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup basket
*/

// Loads the required class
try {
	require_once("core/class/BaseObject.php");
} catch (Exception $e){
	functions::xecho($e->getMessage()).' // ';
}

/**
* @brief  Basket Object, herits of the BaseObject class
*
* @ingroup basket
*/
abstract class Basket_obj_Abstract extends BaseObject
{
	/**
	* Returns the string representing the Basket object
	*
	* @return string The basket label (basket_name and basket_id)
	*/
	function __toString()
	{
		return $this->basket_name." (".$this->basket_id.")" ;
	}
}

