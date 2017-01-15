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
* @brief List of structures for autocompletion
*
*
* @file
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
$db = new Database();

$query = "SELECT id, label as tag FROM ".$_SESSION['tablename']['contact_purposes']." WHERE lower(label) like lower(?)";

$arrayPDO = array('%'.$_REQUEST['what'].'%');

if(isset($_GET['id']) &&  $_GET['id'] <> ''){
	$query .= ' and id <> ?';
	$arrayPDO = array_merge($arrayPDO, array($_GET['id']));
}

$query .= " order by label";
$stmt = $db->query($query, $arrayPDO);

$listArray = array();
while($line = $stmt->fetchObject())
{
	// array_push($listArray, $line->tag);
	$listArray[$line->id] = $line->tag;
}
echo "<ul>\n";
$authViewList = 0;

foreach($listArray as $key => $what)
{
	if($authViewList >= 10)
	{
		$flagAuthView = true;
	}
    echo "<li id=".functions::xssafe($key).">"
    	.functions::xssafe($what)."</li>\n";
	if($flagAuthView)
	{
		echo "<li id=".functions::xssafe($key).">...</li>\n";
		break;
	}
	$authViewList++;
}
echo "</ul>";
