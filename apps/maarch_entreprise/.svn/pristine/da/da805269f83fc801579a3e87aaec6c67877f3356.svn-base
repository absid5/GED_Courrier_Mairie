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
$admin->test_admin('admin_contacts', 'apps');
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
$pagePath = $_SESSION['config']['businessappurl'] . 'index.php?page=contact_purposes';
$pageLabel = _CONTACT_PURPOSES_LIST;
$pageId = "contact_purposes";
$admin->manage_location_bar($pagePath, $pageLabel, $pageId, $init, $level);
/***********************************************************/
unset($_SESSION['m_admin']);
require_once "core" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    . "class_request.php";
require_once "apps" . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    . "class_list_show.php";
$func = new functions();
$select[$_SESSION['tablename']['contact_purposes']] = array();
array_push(
    $select[$_SESSION['tablename']['contact_purposes']],
    "id", "label"
);
$what = "";
$where = "";
$arrayPDO = array();
if (isset($_REQUEST['what']) && ! empty($_REQUEST['what'])) {
    $what = $_REQUEST['what'];
    $where .= " lower(label) like lower(?)";
    $arrayPDO = array($what. '%');
}

$list = new list_show();
$order = 'asc';
if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {
    $order = trim($_REQUEST['order']);
}
$field = 'label';
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
            if ($tab[$i][$j][$value] == "id") {
                $tab[$i][$j]["contact_purposes_id"] = $tab[$i][$j]['value'];
                $tab[$i][$j]["label"] = _ID;
                $tab[$i][$j]["size"] = "30";
                $tab[$i][$j]["label_align"] = "left";
                $tab[$i][$j]["align"] = "left";
                $tab[$i][$j]["valign"] = "bottom";
                $tab[$i][$j]["show"] = true;
                $tab[$i][$j]["order"] = 'contact_purposes_id';
            }
            if ($tab[$i][$j][$value] == "label") {
                $tab[$i][$j]['value'] = $request->show_string(
                    $tab[$i][$j]['value']
                );
                $tab[$i][$j]["contact_purposes_label"] = $tab[$i][$j]['value'];
                $tab[$i][$j]["label"] = _DESC_CONTACT_PURPOSES;
                $tab[$i][$j]["size"] = "60";
                $tab[$i][$j]["label_align"] = "left";
                $tab[$i][$j]["align"] = "left";
                $tab[$i][$j]["valign"] = "bottom";
                $tab[$i][$j]["show"] = true;
                $tab[$i][$j]["order"] = 'contact_purposes_label';
            }
        }
    }
}
$pageName = "contact_purposes";
$pageNameUp = "contact_purposes_up";
$pageNameAdd = "contact_purposes_up";
$pageNameDel = "contact_purposes_del";
$pageNameVal = "";
$tableName = $_SESSION['tablename']['contact_purposes'];
$pageNameBan = "";
$addLabel = _NEW_CONTACT_PURPOSE_ADDED;

$autoCompletionArray = array();
$autoCompletionArray["list_script_url"] = $_SESSION['config']['businessappurl']
    . "index.php?display=true&page=contact_purposes_list_by_name";
$autoCompletionArray["number_to_begin"] = 1;
$list->admin_list(
    $tab, $i, _CONTACT_PURPOSES_LIST . ' : ' . $i . " " . _CONTACT_PURPOSES,
    'contact_purposes_id"', 'contact_purposes', 'contact_purposes',
    'contact_purposes_id', true, $pageNameUp, $pageNameVal, $pageNameBan,
    $pageNameDel, $pageNameAdd, $addLabel, FALSE, FALSE, _ALL_CONTACT_PURPOSES,
    _A_CONTACT_PURPOSE, 'tags', false, true, true, true,
    "", true, $autoCompletionArray
);
