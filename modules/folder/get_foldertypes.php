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

$core_tools = new core_tools();
$core_tools->load_lang();

$content = '';
if(!isset($_REQUEST['coll_id']) || empty($_REQUEST['coll_id']))
{
	echo _COLLECTION.' '._IS_EMPTY;
	exit();
}

$db = new Database();

$stmt = $db->query("SELECT foldertype_id,foldertype_label FROM ".$_SESSION['tablename']['fold_foldertypes'] 
	. " WHERE coll_id = ? order by foldertype_label ASC", array($_REQUEST['coll_id']));

$content = '<option value="">'._CHOOSE_FOLDERTYPE.'</option>';
while($res = $stmt->fetchObject())
{var_dump($res);
	$content .= '<option value="'.$res->foldertype_id
		.'">'.$res->foldertype_label.'</option>';
}

echo $content;

