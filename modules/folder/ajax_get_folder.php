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
* File : ajax_get_project.php
*
* Script called by an ajax object to get the project id  given a market id (index_mlb.php)
*
* @package  maarch
* @version 1
* @since 10/2005
* @license GPL v3
* @author  Claire Figueras  <dev@maarch.org>
*/

$db = new Database();

$core = new core_tools();
$core->load_lang();
require_once "core/class/class_security.php";
$sec = new security();
$whereClause = $sec->get_where_clause_from_coll_id($_SESSION['collection_id_choice']);
if($_POST['FOLDER_TREE']){
	$folders = array();
	$stmt = $db->query('SELECT folders_system_id, folder_name, parent_id, folder_level FROM folders WHERE foldertype_id not in (100) AND parent_id=? AND status NOT IN (\'DEL\') order by folder_id asc', array($_POST["folders_system_id"]));
	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
	
		$stmt2 = $db->query(
				"SELECT count(*) as total FROM res_view_letterbox WHERE folders_system_id in (?) AND (".$whereClause.") AND status NOT IN ('DEL')"
				,array($row['folders_system_id']));
		$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
		$stmt3 = $db->query(
		"SELECT count(*) as total FROM folders WHERE foldertype_id not in (100) AND parent_id IN (?) AND status NOT IN ('DEL')"
		,array($row['folders_system_id']));
		$row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
		$folders[] = array(
			'parent_id' => $row['parent_id'],
			'folders_system_id' => $row['folders_system_id'],
			'nom_folder' => $row['folder_name'],
			'folder_level' => $row['folder_level'],
			'nb_doc' => $row2['total'],
			'nb_subfolder' => $row3['total']
		);
	}
	echo json_encode($folders);
	exit();
}else if($_POST['FOLDER_TREE_RESET']){
	$folders = array();
	$stmt = $db->query('SELECT folders_system_id, folder_name, parent_id, folder_level FROM folders WHERE foldertype_id not in (100) AND folders_system_id=? AND status NOT IN (\'DEL\') order by folder_id asc', array($_POST["folders_system_id"]));
	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		$stmt2 = $db->query(
				"SELECT count(*) as total FROM res_view_letterbox WHERE folders_system_id in (?) AND (".$whereClause.") AND status NOT IN ('DEL')", array($_POST['folders_system_id'])
				);
		$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
		$stmt3 = $db->query(
			"SELECT count(*) as total FROM folders WHERE foldertype_id not in (100) AND parent_id IN (".$row['folders_system_id'].")  AND status NOT IN ('DEL')"
		);
		$row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
		$folders[] = array(
			'parent_id' => $row['parent_id'],
			'folders_system_id' => $row['folders_system_id'],
			'nom_folder' => $row['folder_name'],
			'folder_level' => $row['folder_level'],
			'nb_doc' => $row2['total'],
			'nb_subfolder' => $row3['total']
		);
	}
	echo json_encode($folders);
	exit();
}else if($_POST['FOLDER_TREE_DOCS']){
	$docs = array();
	$entitiesForCurrentUser = $sec->getEntitiesForCurrentUser();
	if (empty($entitiesForCurrentUser)) {
		$stmt = $db->query('SELECT res_id, type_label, subject,doctypes_first_level_label,doctypes_second_level_label, folder_level
							FROM res_view_letterbox
							WHERE folders_system_id in (?) AND (' .$whereClause. ') AND status NOT IN (?)',
							[$_POST['folders_system_id'], 'DEL']);
	} else {
		$stmt = $db->query('SELECT res_id, type_label, subject,doctypes_first_level_label,doctypes_second_level_label, folder_level
							FROM res_view_letterbox
							WHERE folders_system_id in (?) AND (' .$whereClause. ' OR folder_destination IN (?)) AND status NOT IN (?)',
							[$_POST['folders_system_id'], $sec->getEntitiesForCurrentUser(), 'DEL']);
	}

	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		
		$docs[$row['doctypes_first_level_label']][$row['doctypes_second_level_label']][] = array(
			'res_id' => $row['res_id'],
			'type_label' => $row['type_label'],
			'subject' => $row['subject'],
			'folder_level' => $row['folder_level']
		);
	}
	echo json_encode($docs);
	exit();
}else if($_POST['AUTOFOLDERS_TREE'] && $admin->is_module_loaded('autofoldering')) {
	$config_autofoldering = "modules/autofoldering/xml/autofoldering.xml";
	$xml=simplexml_load_file($config_autofoldering);
	//print_r($xml->tree);
	$autofolders = array();

	foreach($xml->tree as $tree) {
		$autofolders[] = array(
			'id' => $tree->id,
			'desc' => $tree->desc,
			'tree_level' => 1
		);
	}
	echo json_encode($autofolders);
	exit();
}else if($_POST['AUTOFOLDER_FOLDERS'] && $admin->is_module_loaded('autofoldering')) {
	$config_autofoldering = "modules/autofoldering/xml/autofoldering.xml";
	$xml=simplexml_load_file($config_autofoldering);
	$path_folder = explode(";", $_POST['path_folder']);	
	//print_r($path_folder);
	$folders = array();
	$level = $_POST['folder_level'];
	$level=$level-1;
	$level_comp=$level-1;
	$arrayPDO = array();
	foreach($xml->tree as $tree) {
		if($tree->id == $_POST['id']){
			$view = $sec->retrieve_view_from_coll_id($tree->coll_id);
				for ($i=0; $i < $level ; $i++) { 
					if($i<>0){
						$where .= " AND (".$tree->nodes->node[$i]->target_column[0]." = ?)";
						$arrayPDO = array_merge($arrayPDO, array("path_".$i => $path_folder[$i+1]));
					}else{
						$where = "WHERE (".$tree->nodes->node[$i]->target_column[0]." = ?)";
						$arrayPDO = array_merge($arrayPDO, array("path_".$i => $path_folder[$i+1]));
					}
				}
				$select=$tree->nodes->node[$level]->target_column[0];
				if($_POST['folder_level']==$tree->nodes->node->count()){
					$check_docs=true;
				}else{
					$check_docs=false;
				}
			
			break;
		}
		
	}

	if(!empty($where)){
		$where.= ' AND ('.$whereClause.')';
	}else{
		$where = 'WHERE ('.$whereClause.')';
	}

	if($level<>0){
		$order="ORDER BY ".$select." ASC";
	}
	$stmt = $db->query('SELECT DISTINCT ('.$select.') as libelle FROM '.$view.' '.$where.' '.$order, $arrayPDO);

	while($row=$stmt->fetch(PDO::ASSOC)){
		$folders[] = array(
			'libelle' => $row['libelle'],
			'folder_level' => $level + 2,
			'check_docs' => $check_docs,
		);
	}
	echo json_encode($folders);
	exit();
}else if($_POST['AUTOFOLDER_FOLDER_DOCS'] && $admin->is_module_loaded('autofoldering')) {
	$config_autofoldering = "modules/autofoldering/xml/autofoldering.xml";
	$xml=simplexml_load_file($config_autofoldering);
	$path_folder = explode(";", $_POST['path_folder']);
	//print_r($path_folder);
	$docs = array();
	$arrayPDO = array();
	foreach($xml->tree as $tree) {
		if($tree->id == $_POST['id']){
			$view = $sec->retrieve_view_from_coll_id($tree->coll_id);
			for ($i=0; $i <= $tree->nodes->node->count()-1 ; $i++) { 
				if($i<>0){
					$where .= " AND (".$tree->nodes->node[$i]->target_column[0]." = ?)";
					$arrayPDO = array_merge($arrayPDO, array("path_".$i => $path_folder[$i+1]));
				}else{
					$where = "WHERE (".$tree->nodes->node[$i]->target_column[0]." = ?)";
					$arrayPDO = array_merge($arrayPDO, array("path_".$i => $path_folder[$i+1]));
				}
			}
			
			break;
		}
		
	}
	if(!empty($where)){
		$where.= ' AND ('.$whereClause.')';
	}else{
		$where= 'WHERE ('.$whereClause.')';
	}
	//echo "requete where: ".$where;exit();
	$stmt = $db->query('SELECT res_id, type_label, subject,doctypes_first_level_label,doctypes_second_level_label, folder_level FROM '.$view.' '.$where, $arrayPDO);

	while($row=$stmt->fetch(PDO::ASSOC)){
		$docs[] = array(
			'res_id' => $row['res_id'],
			'type_label' => $row['type_label'],
			'subject' => $row['subject'],
			'doctypes_first_level_label' => $row['doctypes_first_level_label'],
			'doctypes_second_level_label' => $row['doctypes_second_level_label'],
			'folder_level' => $row['folder_level']
		);
	}
	echo json_encode($docs);
	exit();
}else{

if(!isset($_REQUEST['id_subfolder']) || empty($_REQUEST['id_subfolder']))
{
	//$_SESSION['error'] = _SUBFOLDER.' '._IS_EMPTY;
	echo "{status : 1, error_txt : '".addslashes( _SUBFOLDER.' '._IS_EMPTY)."'}";
	exit();
}
$stmt = $db->query('SELECT parent_id FROM '.$_SESSION['tablename']['fold_folders'].' where folders_system_id = ? AND status NOT IN (\'DEL\')', array($_REQUEST['id_subfolder']));

if($stmt->rowCount() < 1)
{
	//$_SESSION['error'] = _NO_SUBFOLDER;
	echo "{status : 1, error_txt : '".addslashes(_NO_SUBFOLDER)."'}";
	exit();
}
$res = $stmt->fetchObject();
$parent_id = $res->parent_id;
$stmt = $db->query('SELECT folder_name, subject, folders_system_id FROM '.$_SESSION['tablename']['fold_folders'].' where folders_system_id = ? AND status NOT IN (\'DEL\')', array($parent_id));

if($stmt->rowCount() < 1)
{
	//$_SESSION['error'] =_NO_FOLDER;
	echo "{status : 1, error_txt : '".addslashes(_NO_FOLDER)."'}";
	exit();
}
$res = $stmt->fetchObject();
echo "{status : 0, value : '".functions::show_string($res->folder_name).', '.functions::show_string($res->subject).' ('.functions::show_string($res->folders_system_id).')'."'}";
exit();
}
?>
