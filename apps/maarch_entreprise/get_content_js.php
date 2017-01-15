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
* @brief  Script called by an ajax object to return the content of a javascript file
*
* Script called by an ajax object to return the content of a javascript file
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/

header('content-type: text/javascript');

if (empty($_REQUEST['scripts'])) {
	echo '';
	exit();
}

$authorizedPaths = ['change_doctype.js'];

$arr_scripts = explode('$$', $_REQUEST['scripts']);
for ($i=0; $i<count($arr_scripts ); $i++) {
	if ($arr_scripts[$i] <> '') {
		$arr_scripts[$i] = str_replace("\\", "", $arr_scripts[$i]);
		$arr_scripts[$i] = str_replace("/", "", $arr_scripts[$i]);
		$arr_scripts[$i] = str_replace("..", "", $arr_scripts[$i]);
		if (in_array($arr_scripts[$i], $authorizedPaths)) {
			$arr_scripts[$i] = 'modules/templates/js/' . $arr_scripts[$i];
		}
		echo file_get_contents($arr_scripts[$i]);
	}
}
exit();

