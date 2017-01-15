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
* Module : Tags
* 
* This module is used to store ressources with any keywords
* V: 1.0
*
* @file
* @author Loic Vinet
* @date $date$
* @version $Revision$
*/


// Loads the required class
try {
	require_once("core/class/BaseObject.php");
} catch (Exception $e){
	functions::xecho($e->getMessage()).' // ';
}

/**
* @brief  Tag Object, herits of the BaseObject class 
*
* @ingroup tag
*/
abstract class TagObj_Abstract extends BaseObject
 {	
	/**
	* Returns the string representing the Tag object
	*
	* @return string The tag label (tag_label)
	*/
	function __toString(){
		return $this->tag_label ; 
	}	
}
?>
