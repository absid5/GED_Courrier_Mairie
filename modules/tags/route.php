<?php
/*
*    Copyright 2008,2009 Maarch
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
*
*
* @file
* @author Loic Vinet <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/


//Liste des differentes routes utilisees par le module tags

// path for UNIX
if (DIRECTORY_SEPARATOR == "/") { 
	$separator = DIRECTORY_SEPARATOR;
} else { // path for WINDOWS
	$separator = DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR;
}

$route_tag_ui_script 				= '\''.$_SESSION['config']['businessappurl'] . 'index.php?display=true&module=tags&page=templates'.$separator.'tag_ui\'';
$route_tag_ui_script_without_modif	= '\''.$_SESSION['config']['businessappurl'] . 'index.php?display=true&module=tags&page=templates'.$separator.'tag_ui&opt=hide_deletebutton\'';
$route_tag_delete_tags_from_res 	= '\''.$_SESSION['config']['businessappurl'] . 'index.php?display=true&module=tags&page=aj_delete_this_tag\'';
$route_tag_add_tags_from_res		= '\''.$_SESSION['config']['businessappurl'] . 'index.php?display=true&module=tags&page=aj_add_this_tags\'';
$route_tag_fusion_tags				= '\''.$_SESSION['config']['businessappurl'] . 'index.php?display=true&module=tags&page=aj_tag_fusion_tags\'';
$route_tag_just_add_tags_from_res		= '\''.$_SESSION['config']['businessappurl'] . 'index.php?display=true&module=tags&page=aj_just_add_this_tags\'';
//-----------------------------------------------


?>
