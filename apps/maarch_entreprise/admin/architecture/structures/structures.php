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
* @brief Structures list
*
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

$admin = new core_tools();
$admin->test_admin('admin_architecture', 'apps');
/****************Management of the location bar  ************/
$init = false;
if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true") {
    $init = true;
}
$level = "";
if (isset($_REQUEST['level']) && ($_REQUEST['level'] == 2
    || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4
    || $_REQUEST['level'] == 1)
) {
    $level = $_REQUEST['level'];
}
$pagePath = $_SESSION['config']['businessappurl'] . 'index.php?page=structures';
$pageLabel = _STRUCTURE_LIST;
$pageId = "structures";
$admin->manage_location_bar($pagePath, $pageLabel, $pageId, $init, $level);
/***********************************************************/
unset($_SESSION['m_admin']);
require_once "core" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    . "class_request.php";
require_once "apps" . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    . "class_list_show.php";
$func = new functions();
$db = new Database();
$select[$_SESSION['tablename']['doctypes_first_level']] = array();
array_push(
    $select[$_SESSION['tablename']['doctypes_first_level']],
    "doctypes_first_level_id", "doctypes_first_level_label"
);
$what = "";
$where = " enabled = 'Y' ";
$arrayPDO = array();
if (isset($_REQUEST['what']) && ! empty($_REQUEST['what'])) {
    $what = $_REQUEST['what'];
    $where .= " and lower(doctypes_first_level_label) like lower(?)";
    $arrayPDO = array($what. '%');
}

$list = new list_show();
$order = 'asc';
if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {
    $order = trim($_REQUEST['order']);
}
$field = 'doctypes_first_level_label';
if (isset($_REQUEST['order_field']) && ! empty($_REQUEST['order_field'])) {
    $field = trim($_REQUEST['order_field']);
}

$orderstr = $list->define_order($order, $field);

$request = new request;
$tab = $request->PDOselect(
    $select, $where, $arrayPDO, $orderstr, $_SESSION['config']['databasetype']
);
for ($i = 0; $i < count($tab); $i ++) {
    for ($j = 0; $j < count($tab[$i]); $j ++) {
        foreach (array_keys($tab[$i][$j]) as $value) {
            if ($tab[$i][$j][$value] == "doctypes_first_level_id") {
                $tab[$i][$j]["doctypes_first_level_id"] = $tab[$i][$j]['value'];
                $tab[$i][$j]["label"] = _ID;
                $tab[$i][$j]["size"] = "20";
                $tab[$i][$j]["label_align"] = "left";
                $tab[$i][$j]["align"] = "left";
                $tab[$i][$j]["valign"] = "bottom";
                $tab[$i][$j]["show"] = true;
                $tab[$i][$j]["order"] = 'doctypes_first_level_id';
            }
            if ($tab[$i][$j][$value] == "doctypes_first_level_label") {
                $tab[$i][$j]['value'] = $request->show_string(
                    $tab[$i][$j]['value']
                );
                $tab[$i][$j]["doctypes_first_level_label"] = $tab[$i][$j]['value'];
                $tab[$i][$j]["label"] = _DESC;
                $tab[$i][$j]["size"] = "70";
                $tab[$i][$j]["label_align"] = "left";
                $tab[$i][$j]["align"] = "left";
                $tab[$i][$j]["valign"] = "bottom";
                $tab[$i][$j]["show"] = true;
                $tab[$i][$j]["order"] = 'doctypes_first_level_label';
            }
        }
    }
}
$pageName = "structures";
$pageNameUp = "structure_up";
$pageNameAdd = "structure_up";
$pageNameDel = "structure_del";
$pageNameVal = "";
$tableName = $_SESSION['tablename']['doctypes_first_level'];
$pageNameBan = "";
$addLabel = _NEW_STRUCTURE_ADDED;
$_SESSION['m_admin']['init'] = true;
$_SESSION['m_admin']['sous_dossiers'] = array();

$stmt = $db->query(
	"SELECT * FROM " . $_SESSION['tablename']['doctypes_second_level']
    . " WHERE enabled = 'Y'"
);
while ($res = $stmt->fetchObject()) {
    array_push(
        $_SESSION['m_admin']['sous_dossiers'],
        array(
        	"ID" => $res->doctypes_second_level_id,
        	"LABEL" => $request->show_string($res->doctypes_second_level_label)
        )
    );
}

if ($admin->is_module_loaded('folder') == true) {
    $_SESSION['m_admin']['foldertypes'] = array();
    $stmt2 = $db->query(
    	"SELECT foldertype_id, foldertype_label FROM "
        . $_SESSION['tablename']['fold_foldertypes']
    );
    while ($res = $stmt2->fetchObject()) {
        array_push(
            $_SESSION['m_admin']['foldertypes'],
            array(
            	'id' => $res->foldertype_id,
            	'label' => $res->foldertype_label
            )
        );
    }
}
$autoCompletionArray = array();
$autoCompletionArray["list_script_url"] = $_SESSION['config']['businessappurl']
    . "index.php?display=true&page=structures_list_by_name";
$autoCompletionArray["number_to_begin"] = 1;
$list->admin_list(
    $tab, $i, _STRUCTURE_LIST . ' : ' . $i . " " . _STRUCTURES,
    'doctypes_first_level_id"', 'structures', 'structures',
    'doctypes_first_level_id', true, $pageNameUp, $pageNameVal, $pageNameBan,
    $pageNameDel, $pageNameAdd, $addLabel, FALSE, TRUE, _ALL_STRUCTURES,
    _STRUCTURE, 'folder', false, true, false, true,
    "", true, $autoCompletionArray
);
