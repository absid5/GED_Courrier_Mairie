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
* File : folders_list_by_id.php
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

require_once "core/class/class_request.php";
$db = new Database();

//requete permettant de rechercher sur les dossiers qui ne sont pas en status del
$stmt = $db->query(
	"SELECT folder_id FROM " . $_SESSION['tablename']['fold_folders']
    . " WHERE status != 'DEL' and lower(folder_id) like lower(?) order by folder_id",
	array($_REQUEST['Input'] . '%')
);

$folders = array();
while ($line = $stmt->fetchObject()) {
	array_push($folders, $line->folder_id);
}

echo "<ul>";
$authViewList = 0;
foreach ($folders as $folder) {
	if ($authViewList >= 10) {
		$flagAuthView = true;
	}
    if (stripos($folder, $_REQUEST['Input']) === 0) {
        echo "<li>" . $folder . "</li>";
		if (isset($flagAuthView) && $flagAuthView) {
			echo "<li>...</li>";
			break;
		}
		$authViewList ++;
    }
}
echo "</ul>";