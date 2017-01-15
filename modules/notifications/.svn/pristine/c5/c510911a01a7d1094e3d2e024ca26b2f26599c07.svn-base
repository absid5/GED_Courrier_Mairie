<?php

/*
*   Copyright 2008-2016 Maarch
*
*   This file is part of Maarch Framework.
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
*   along with Maarch Framework. If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief Contains the events Object
* (herits of the BaseObject class)
*
* @file
* @author Cyril Vazquez - Maarch
* @date $date$
* @version $Revision$
* @ingroup core
*/


// Loads the required class
try {
    require_once("core/class/BaseObject.php");
    require_once("core/class/ObjectControlerAbstract.php");
} catch (Exception $e){
    functions::xecho($e->getMessage()).' // ';
}

/**
 * Class for controling docservers objects from database
 */
abstract class events_Abstract extends ObjectControler
{
	/**
	 *Print a viewable string to render the object.
	 * @return string Rendering of the object
	 */

	public function __toString()
	{
		return $this->event_sid;
	}
}

