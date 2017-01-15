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
* @brief  Contains the session_security Object (herits of the BaseObject class)
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
	require_once("core/class/BaseObject.php");
} catch (Exception $e){
	echo functions::xssafe($e->getMessage()).' // ';
}

/**
* @brief  session_security Object, herits of the BaseObject class 
*
* @ingroup core
*/
class session_security extends BaseObject
 {	
	
	/**
	* Returns the string representing the session_security object
	*
	* @return string The session_security label 
	*/
	function __toString(){
		return "Session security for (".$this->user_id.")" ; 
	}	
}
?>
