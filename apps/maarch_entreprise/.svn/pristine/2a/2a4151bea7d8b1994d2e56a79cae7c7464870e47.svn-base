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

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");

$req = new request();

$select = array();
$select[$_SESSION['tablename']['users']]= array('lastname', 'firstname', 'user_id');
$where = " (lower(lastname) like lower(:input) "
	."or lower(firstname) like lower(:input) "
	."or user_id like :input) and (status = 'OK' or status = 'ABS') and enabled = 'Y'";

$other = 'order by lastname, firstname';

$arrayPDO = array(":input" => $_REQUEST['Input']."%");

$res = $req->PDOselect($select, $where, $arrayPDO, $other, $_SESSION['config']['databasetype'], 11,false,"","","", false);

echo "<ul>\n";
for($i=0; $i< min(count($res), 10)  ;$i++)
{
	echo "<li>".functions::xssafe(functions::show_string($res[$i][0]['value']))
		.', ' . functions::xssafe(functions::show_string($res[$i][1]['value']))
		.' (' . functions::xssafe($res[$i][2]['value']).")</li>\n";
}
if(count($res) == 11)
{
	echo "<li>...</li>\n";
}
echo "</ul>";
