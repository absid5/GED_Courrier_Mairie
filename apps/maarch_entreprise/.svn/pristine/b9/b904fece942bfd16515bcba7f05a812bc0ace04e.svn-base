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
* @brief Delete a structure
*
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/
$core_tools = new core_tools('admin_architecture');
$core_tools->test_admin('admin_architecture', 'apps');
$core_tools->load_lang();
$db = new Database();

if(isset($_GET['id']))
{
	$id = addslashes(functions::wash($_GET['id'], "no", _THE_STRUCTURE));
}
else
{
	$id = "";
}


$stmt = $db->query("SELECT doctypes_first_level_label FROM ".$_SESSION['tablename']['doctypes_first_level']." WHERE doctypes_first_level_id = ?", array($id));

if($stmt->rowCount() == 0)
{
	$_SESSION['error'] = _STRUCTURE.' '._UNKNOWN.".";
	header("location: ".$_SESSION['config']['businessappurl']."index.php?page=structures&order=".$_REQUEST['order']."&order_field=".$_REQUEST['order_field']."&start=".$_REQUEST['start']."&what=".$_REQUEST['what']);
	exit();
}
else
{
	$info = $stmt->fetchObject();

	// delete structure
	$db->query("UPDATE ".$_SESSION['tablename']['doctypes_first_level']." SET enabled = 'N' WHERE doctypes_first_level_id = ? ", array($id));

	//delete subfolders depending on that structure
	$db->query("UPDATE ".$_SESSION['tablename']['doctypes_second_level']." SET enabled = 'N' WHERE doctypes_first_level_id = ? ", array($id));

	if($core_tools->is_module_loaded('folder') == true)
	{
		$db->query("DELETE FROM ".$_SESSION['tablename']['fold_foldertypes_doctypes_level1']." WHERE doctypes_first_level_id = ? ", array($id));
	
		$stmt = $db->query("SELECT type_id FROM ".$_SESSION['tablename']['doctypes']." WHERE doctypes_first_level_id = ? ", array($id));

		
		while($res = $stmt->fetchObject())
		{
			//delete the doctypes from the foldertypes_doctypes table
			$db->query("DELETE FROM  ".$_SESSION['tablename']['fold_foldertypes_doctypes']."  WHERE doctype_id = ? ", array($res->type_id));
		}
	}
	// delete the doctypes
	$db->query("UPDATE ".$_SESSION['tablename']['doctypes']." SET enabled = 'N' WHERE doctypes_first_level_id = ? ", array($id));

	if($_SESSION['history']['structuredel'] == "true")
	{
		require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
		$users = new history();
		$users->add($_SESSION['tablename']['doctypes_first_level'], $id,"DEL",'structuredel',_STRUCTURE_DEL." ".strtolower(_NUM).$id."", $_SESSION['config']['databasetype']);
	}
	$_SESSION['info'] = _DELETED_STRUCTURE;
	unset($_SESSION['m_admin']);
	header("location: ".$_SESSION['config']['businessappurl']."index.php?page=structures&order=".$_REQUEST['order']."&order_field=".$_REQUEST['order_field']."&start=".$_REQUEST['start']."&what=".$_REQUEST['what']);
	exit();
}
?>
