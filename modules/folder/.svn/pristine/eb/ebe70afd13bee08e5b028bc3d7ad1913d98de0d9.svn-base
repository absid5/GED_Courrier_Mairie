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
* File : create_folder_get_folder_index.php
*
* Ajax script used to get folder index during folder creation
*
* @package  Folder
* @version 1.0
* @since 06/2007
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*/

require_once 'modules/folder/class/class_admin_foldertypes.php';

$core = new core_tools();
//here we loading the lang vars
$core->load_lang();
$foldertype = new foldertype();
$content = '';

if (! isset($_REQUEST['foldertype_id']) || empty($_REQUEST['foldertype_id'])) {
	echo _FOLDERTYPE.' '._IS_EMPTY;
	exit();
}

$indexes = $foldertype->get_indexes($_REQUEST['foldertype_id']);
$mandatory = $foldertype->get_mandatory_indexes($_REQUEST['foldertype_id']);

if (count($indexes) > 0) {
	foreach (array_keys($indexes) as $key) {
		$content .= '<p>';
		$content .= '<label for="' . $key . '">	' . $indexes[$key]['label']
		         . ' :</label>';
		if ($indexes[$key]['type_field'] == 'input') {
			if ($indexes[$key]['type'] == 'date') {
				$content .= '<input name="' . $key . '" type="text" id="' . $key
				         . '" value="';
				if (! empty($_SESSION['m_admin']['folder']['indexes'][$key])) {
					$content .= $_SESSION['m_admin']['folder']['indexes'][$key];
				} else if ($indexes[$key]['default_value'] <> false) {
					$content .= $foldertype->format_date_db(
					    $indexes[$key]['default_value'], true
					);
				}
				$content .= '" onclick="showCalender(this);"/>';
			} else {
				$content .= '<input name="' . $key . '" type="text" id="' . $key
				         . '" value="';
				if (! empty($_SESSION['m_admin']['folder']['indexes'][$key])) {
					$content .= $_SESSION['m_admin']['folder']['indexes'][$key];
				} else if ($indexes[$key]['default_value'] <> false) {
					$content .= $indexes[$key]['default_value'];
				}
				$content .= '"  />';
			}
		} else {
			$content .= '<select name="' . $key . '" id="' . $key . '" >';
			$content .= '<option value="">' . _CHOOSE . '...</option>';
			for ($i = 0; $i < count($indexes[$key]['values']); $i ++) {
			    $content .= '<option value="'
			             . $indexes[$key]['values'][$i]['id'] . '"';
				if (isset($_SESSION['m_admin']['folder']['indexes'][$key])
				    && $indexes[$key]['values'][$i]['id'] == $_SESSION['m_admin']['folder']['indexes'][$key]
				) {
					$content .= ' selected="selected"';
				} else if ($indexes[$key]['default_value'] <> false
				    && $indexes[$key]['values'][$i]['id'] == $indexes[$key]['default_value']
				    && empty($_SESSION['m_admin']['folder']['indexes'][$key])
				) {
					$content .= ' selected="selected"';
				}
				$content .= ' >' . $indexes[$key]['values'][$i]['label']
				         . '</option>';
			}
			$content .= '</select>';
		}

		if (in_array($key, $mandatory)) {
			$content .= ' <span class="red_asterisk"><i class="fa fa-star"></i></span>';
		}
		$content .= '</p>';
	}
}

$db = new Database();

if (isset($_SESSION['user']['entities'][0])) {
	$finalDest = [];
	foreach ($_SESSION['user']['entities'] as $tmp) {
		$finalDest[] = $tmp['ENTITY_ID'];
	}
	$stmt = $db->query(
		"SELECT folders_system_id, folder_id, folder_name FROM "
		. $_SESSION['tablename']['fold_folders']
		. " WHERE folder_level = 1 and status <> 'DEL' and (destination in (?) OR destination is null)", [$finalDest]
	);
} else {
	$stmt = $db->query(
		"SELECT folders_system_id, folder_id, folder_name FROM "
		. $_SESSION['tablename']['fold_folders']
		. " WHERE folder_level = 1 and status <> 'DEL' "
	);
}

$folders = array();
while ($res = $stmt->fetchObject()) {
	array_push(
	    $folders,
	    [
	    	'SYS_ID' => $res->folders_system_id,
	    	'ID' => $res->folder_id,
	    	'NAME' => $res->folder_name
	    ]
	);
}
include_once 'modules/folder/create_folder_get_folder_index_comp.php';

echo $content;

