<?php
/*
*    Copyright 2008 - 2011 Maarch
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
* @brief Returns in a json structure all allowed first branches of a tree for
* the current user (Ajax)
*
* @file
* @author  Claire Figueras  <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/
require_once "core/class/class_security.php";
require_once "core/class/class_core_tools.php";
require_once "apps" . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
. DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
. 'class_business_app_tools.php';
$appTools = new business_app_tools();
$core = new core_tools();
$core->load_lang();
$sec = new security();
$func = new functions();
$db = new Database();
$core->test_service('view_folder_tree', 'folder');
$whereClause = $sec->get_where_clause_from_coll_id($_SESSION['collection_id_choice']);
?>
<script type="text/javascript">
	function hideshow(which){
		if (!document.getElementById)
			return
		if (which.style.display=="block")
			which.style.display="none"
		else
			which.style.display="block"
	}
</script>
<style type="text/css">.link_open{border-left:dashed 1px #FFC200;}li{cursor: pointer;}li.folder{list-style-image: url('static.php?filename=folder.gif');list-style-position: inside;margin-top: 10px;white-space: pre;}li.folder span:hover{background-color: #BAD1E2;padding:5px;border-radius:2px;}li.folder span{padding:5px;}ul.doc a{padding:5px;}ul.doc a:hover{background-color: #BAD1E2;border-radius:2px;}</style>
<?php

$subject = $_REQUEST['project'];
$pattern = '/\([0-9]*\)/';
preg_match($pattern, substr($subject,3), $matches, PREG_OFFSET_CAPTURE);
$fold_id=str_replace("(", "", $matches[0][0]);
$fold_id=str_replace(")", "", $fold_id);
//print_r($fold_id);

$entitiesTab = $sec->getEntitiesForCurrentUser();
if($matches[0] != ''){
	$stmt = $db->query(
		"SELECT folders_system_id, folder_name, parent_id FROM folders WHERE foldertype_id not in (100) AND folders_system_id IN (?) AND status NOT IN ('DEL') order by folder_id asc ", array($fold_id)
		);

}else{
	if (!empty($entitiesTab)) {
		$stmt = $db->query(
			"SELECT folders_system_id, folder_name, parent_id FROM folders WHERE foldertype_id not in (100) AND parent_id=0 AND status NOT IN ('DEL')
				AND (destination in (?) OR destination is null) order by folder_id asc ", [$entitiesTab]
		);
	} else {
		$stmt = $db->query(
			"SELECT folders_system_id, folder_name, parent_id FROM folders WHERE foldertype_id not in (100) AND parent_id=0 AND status NOT IN ('DEL') order by folder_id asc "
		);
	}
}

$categories = array();
$html.="<ul class='folder' id='folder_tree_content'>";
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	if (empty($entitiesTab)) {
		$stmt2 = $db->query(
			"SELECT count(*) as total FROM res_view_letterbox WHERE folders_system_id in (?) AND (".$whereClause.") AND status NOT IN ('DEL')"
			, [$row['folders_system_id']]);
	} else {
		$stmt2 = $db->query(
			"SELECT count(*) as total FROM res_view_letterbox WHERE folders_system_id in (?) AND (".$whereClause."OR folder_destination IN (?)) AND status NOT IN ('DEL')"
			, [$row['folders_system_id'], $entitiesTab]);
	}
	$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
	$stmt3 = $db->query(
		"SELECT count(*) as total FROM folders WHERE foldertype_id not in (100) AND parent_id IN (?) AND status NOT IN ('DEL')"
		,array($row['folders_system_id']));
	$row3 = $stmt3->fetch(PDO::FETCH_ASSOC);

	$folders_system_id=$row['folders_system_id'];
	$html.="<li id='".$row['folders_system_id']."' class='folder'>";
	$html.="<span onclick='get_folders(".functions::xssafe($folders_system_id).")'>"
		.functions::xssafe($row['folder_name'])
		."</span><b>(<span>".functions::xssafe($row3['total'])
		." "._MARKET."</span>, <span onclick='get_folder_docs(".functions::xssafe($folders_system_id).")'>"
		.functions::xssafe($row2['total'])." document(s)</span>)</b>";
	$html.="</li>";
}
$html.="</ul>";
echo $html;
?>
