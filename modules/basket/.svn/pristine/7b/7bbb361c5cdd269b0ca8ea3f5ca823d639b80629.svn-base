<?php
/*
*
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
* @brief Ajax script used in the absence management, autocompletion on the users
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup basket
*/
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");

$req = new request();

$db = new Database();

$select = array();
$select[$_SESSION['tablename']['users']]= array('lastname', 'firstname', 'user_id');

$UserInput = str_replace("&#039;", "'", $_REQUEST['UserInput']);
$BasketOwner = str_replace("&#039;", "'", $_REQUEST['baskets_owner']);

$where = " (lower(lastname) like lower(?) or lower(firstname) like lower(?) or user_id like ?)  and user_id <> ? and (status = 'OK' ) and enabled = 'Y'";
$arrayPDO = array($UserInput.'%',$UserInput.'%',$UserInput.'%',$BasketOwner);

$other = 'order by lastname';

$res = $req->PDOselect($select, $where, $arrayPDO, $other, $_SESSION['config']['databasetype'], 11,false,"","","", false);

echo "<ul>\n";
for($i=0; $i< min(count($res), 10)  ;$i++)
{
	$Name = str_replace("&#039;", "'", $res[$i][0]['value']);
	$Firstname = str_replace("&#039;", "'", $res[$i][1]['value']);
	$IdUser = str_replace("&#039;", "'", $res[$i][2]['value']);
	echo "<li>". functions::xssafe($Name).', '.functions::xssafe($Firstname).' ('.functions::xssafe($IdUser).")</li>\n";
}
if(count($res) == 11)
{
		echo "<li>...</li>\n";
}
echo "</ul>";
