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
* @brief  Script used by an ajax autocompleter object to get actions list
*
* @file
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

$db = new Database();
$stmt = $db->query('SELECT label_action as tag FROM '
    . $_SESSION['tablename']['actions'] . " WHERE lower(label_action) like lower(?) order by label_action",
    array('%'.$_REQUEST['what'].'%')
    );


$listArray = array();
while($line = $stmt->fetchObject()){
	array_push($listArray, $line->tag);
}
echo "<ul>\n";
$authViewList = 0;

foreach($listArray as $what){
	if($authViewList >= 10){
		$flagAuthView = true;
	}
    echo "<li>".functions::xssafe($what)."</li>\n";
	if(isset($flagAuthView) && $flagAuthView){
		echo "<li>...</li>\n";
		break;
	}
	$authViewList++;
}
echo "</ul>";
