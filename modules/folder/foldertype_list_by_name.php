<?php 

/*
*
*    Copyright 2008,2015 Maarch
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
* File : foldertype_list_by_name.php
*
* List of foldertype for autocompletion
*
* @package  Maarch Framework 3.0
* @version 3
* @since 10/2005
* @license GPL
* @author Laurent Giovannoni <dev@maarch.org>
*/

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
$db = new Database();

$stmt = $db->query("SELECT foldertype_label as tag FROM ".$_SESSION['tablename']['fold_foldertypes']." WHERE lower(foldertype_label) like lower(?) order by foldertype_label", array($_REQUEST['what'].'%'));

$listArray = array();
while($line = $stmt->fetchObject())
{
	array_push($listArray, $line->tag);
}
echo "<ul>\n";
$authViewList = 0;

foreach($listArray as $what)
{
	if($authViewList >= 10)
	{
		$flagAuthView = true;
	}
    if(stripos($what, $_REQUEST['what']) === 0)
    {
        echo "<li>".$what."</li>\n";
		if($flagAuthView)
		{
			echo "<li>...</li>\n";
			break;
		}
		$authViewList++;
    }
}
echo "</ul>";
