<?php
/*
 * Copyright (C) 2008-2015 Maarch
 *
 * This file is part of Maarch.
 *
 * Maarch is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Maarch is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Maarch.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
* File : autocomplete_folders.php
*
* Autocompletion list on folder or subfolder
*
* @package  maarch
* @version 1
* @since 10/2005
* @license GPL v3
* @author  Claire Figueras  <dev@maarch.org>
*/
require('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_request.php');
$req = new request();
$db = new Database();

//Build query
$select = array();
    //Table
    $table = $_SESSION['tablename']['fold_folders'];
    //Fields
    $select[$table]= array( 'folder_id', 'folder_name',  'folders_system_id');
    //Where
    $where = '';

	$category_id = $_SESSION['category_id_session'];
	
	if($category_id != null and $category_id != ''){
	
		$stmt = $db->query("SELECT doctypes_first_level_id FROM doctypes WHERE type_id = ?", array($category_id));

		$res = $stmt->fetchObject();
		
		$stmt = $db->query("SELECT foldertype_id FROM foldertypes_doctypes_level1 Where doctypes_first_level_id = ?", array($res->doctypes_first_level_id));
		$res = $stmt->fetchObject();

		if (isset($_SESSION['user']['entities']['0'])) {
			$finalDest = '(';
			foreach ($_SESSION['user']['entities'] as $tmp) {
				$finalDest .= ($finalDest[strlen($finalDest) - 1] == '(' ? '\'' . $tmp['ENTITY_ID'] . '\'' : ', \'' . $tmp['ENTITY_ID'] . '\'');
			}
			$finalDest .= ')';
			$where .= " (foldertype_id = :folderTypeId) and (lower(folder_name) like lower(:Input) or lower(folder_id) like lower(:Input) ) and (status NOT IN('DEL','FOLDDEL')) and (destination in " . $finalDest . " OR destination is null)";
		} else {
			$where .= " (foldertype_id = :folderTypeId) and (lower(folder_name) like lower(:Input) or lower(folder_id) like lower(:Input) ) and (status NOT IN('DEL','FOLDDEL'))";
		}
		$arrayPDO = array(":folderTypeId" => $res->foldertype_id, ":Input" => "%" .$_REQUEST['Input']."%");
		//Order
		$order = 'order by folders_system_id, folder_name';

	}else{
	
		$stmt = $db->query("SELECT doctypes_first_level_id FROM doctypes");
		$doctypes_1 = '';
		$arrayPDO = array();
		while($res = $stmt->fetchObject()){
			$doctypes_1 .= "?,";
			$arrayPDO = array_merge($arrayPDO, array($res->doctypes_first_level_id));
		}
		$doctypes_1 .= 0 ;
		$stmt = $db->query("SELECT foldertype_id FROM foldertypes_doctypes_level1 Where doctypes_first_level_id in ( ".$doctypes_1.")", $arrayPDO);
		$wh = '';
		$arrayPDO = array();
		while($res = $stmt->fetchObject()){
			$wh .= "?,";
			$arrayPDO = array_merge($arrayPDO, array($res->foldertype_id));
		}
		$wh .= 0 ;
		if (isset($_SESSION['user']['entities']['0'])) {
			$finalDest = '(';
			foreach ($_SESSION['user']['entities'] as $tmp) {
				$finalDest .= ($finalDest[strlen($finalDest) - 1] == '(' ? '\'' . $tmp['ENTITY_ID'] . '\'' : ', \'' . $tmp['ENTITY_ID'] . '\'');
			}
			$finalDest .= ')';
			$where .= " (foldertype_id in (".$wh.")) and (lower(folder_name) like lower(?) or lower(folder_id) like lower(?)) and (status NOT IN('DEL','FOLDDEL')) and (destination in " . $finalDest . " OR destination is null)";
		} else {
			$where .= " (foldertype_id in (".$wh.")) and (lower(folder_name) like lower(?) or lower(folder_id) like lower(?)) and (status NOT IN('DEL','FOLDDEL'))";
		}
		$arrayPDO = array_merge($arrayPDO, array("%" .$_REQUEST['Input']."%", "%" .$_REQUEST['Input']."%"));
		//Order
		$order = 'order by folders_system_id, folder_name';
	}
	
//Query
$res = $req->PDOselect($select, $where, $arrayPDO, $order, $_SESSION['config']['databasetype'], 11,false,"","","", false);

//Autocompletion output
echo "<ul>\n";
for($i=0; $i< min(count($res), 10)  ;$i++) {
	echo "<li>".$req->show_string($res[$i][0]['value']).', '.$req->show_string($res[$i][1]['value']).' ('.$res[$i][2]['value'].")</li>\n";
}

//Show only ten item
if(count($res) == 11) {
		echo "<li>...</li>\n";
}
echo "</ul>";
