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
* File : folders_list_by_name.php
*
* List of folders for autocompletion
*
* @package  Maarch Framework 3.0
* @version 3
* @since 10/2005
* @license GPL
* @author Laurent Giovannoni <dev@maarch.org>
* @author Claire Figueras <dev@maarch.org>
*/

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
$db = new Database();

$stmt = $db->query("SELECT folder_id, folder_name, folders_system_id FROM ".$_SESSION['tablename']['fold_folders']." WHERE lower(folder_name) like lower(?) order by folder_name", array($_REQUEST['folder'].'%'));

$folders = array();
while($line = $stmt->fetchobject())
{
	array_push($folders, $line->folder_name." (".$line->folder_id.")");
}
echo "<ul>\n";
$authViewList = 0;
foreach($folders as $folder)
{
	if($authViewList >= 10)
	{
		$flagAuthView = true;
	}
    if(stripos($folder, $_REQUEST['folder']) === 0)
    {
        echo "<li>".$folder."</li>\n";
		if($flagAuthView)
		{
			echo "<li>...</li>\n";
			break;
		}
		$authViewList++;
    }
}
echo "</ul>";
