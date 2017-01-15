<?php

/*
*    Copyright 2008-2011 Maarch
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
* @brief Contains the docserver controler page
*
*
* @file
* @author Luc KEULEYAN - BULL
* @author Laurent Giovannoni
* @date $date$
* @version $Revision$
* @ingroup admin
*/

$sessionName = "docservers";
$pageName = "docservers_management_controler";
$tableName = "docservers";
$idName = "docserver_id";

$mode = 'add';

$core = new core_tools();
$core_tools = new core_tools();
$core->load_lang();
if (isset($_REQUEST['mode']) && !empty($_REQUEST['mode'])) {
    $mode = $_REQUEST['mode'];
} else {
    $mode = 'list';
}

try {
    require_once 'core/class/class_request.php';
    require_once 'core/class/docservers_controler.php';
    require_once 'core/class/docserver_locations_controler.php';
    require_once 'core/class/docserver_types_controler.php';
    if ($mode == 'list') {
        require_once "apps" . DIRECTORY_SEPARATOR
            . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . "class"
            . DIRECTORY_SEPARATOR . "class_list_show.php";
    }
} catch (Exception $e) {
    functions::xecho($e->getMessage());
}

$core_tools->test_admin('admin_docservers', 'apps');

$docserverLocationsControler = new docserver_locations_controler();
$docserverTypesControler = new docserver_types_controler();
if ($mode == "up" || $mode == "add") {
    $docserverLocationsArray = array();
    $docserverLocationsArray = $docserverLocationsControler->getAllId();
    $docserverTypesArray = array();
    $docserverTypesArray = $docserverTypesControler->getAllId();
}

if (isset($_REQUEST['submit'])) {
    // Action to do with db
    validate_cs_submit($mode);
} else {
    // Display to do
    if (isset($_REQUEST['id']) && ! empty($_REQUEST['id'])) {
        $docserverId = $_REQUEST['id'];
    }
    $state = true;
    switch ($mode) {
        case "up" :
            $state = display_up($docserverId);
            location_bar_management($mode);
            break;
        case "add" :
            display_add();
            location_bar_management($mode);
            break;
        case "del" :
            display_del($docserverId);
            break;
        case "list" :
            $docserversList = display_list();
            location_bar_management($mode);
            break;
        case "allow" :
            display_enable($docserverId);
            location_bar_management($mode);
        case "ban" :
            display_disable($docserverId);
            location_bar_management($mode);
    }
    include('docservers_management.php');
}

/**
 * Initialize session variables
 */
function init_session()
{
    $sessionName = "docservers";
    $_SESSION['m_admin'][$sessionName] = array();
}

/**
 * Management of the location bar
 */
function location_bar_management($mode)
{
    $sessionName = "docservers";
    $pageName = "docservers_management_controler";
    $tableName = "docservers";
    $idName = "docserver_id";

    $pageLabels = array(
        'add' => _ADDITION,
        'up' => _MODIFICATION,
        'list' => _DOCSERVERS_LIST,
    );
    $pageIds = array(
        'add' => 'docserver_add',
        'up' => 'docserver_up',
        'list' => 'docservers_list',
    );

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
    $pagePath = $_SESSION['config']['businessappurl'] . 'index.php?page='
              . $pageName . '&admin=docservers&mode=' . $mode;
    $pageLabel = $pageLabels[$mode];
    $pageId = $pageIds[$mode];
    $ct = new core_tools();
    $ct->manage_location_bar($pagePath, $pageLabel, $pageId, $init, $level);
}

/**
 * Validate a submit (add or up),
 * up to saving object
 */
function validate_cs_submit($mode)
{
    $sessionName = "docservers";
    $pageName = "docservers_management_controler";
    $tableName = "docservers";
    $idName = "docserver_id";
    $f = new functions();
    $docserversControler = new docservers_controler();
    $docservers = new docservers();
    $status = array();
    $status['order'] = $_REQUEST['order'];
    $status['order_field'] = $_REQUEST['order_field'];
    $status['what'] = $_REQUEST['what'];
    $status['start'] = $_REQUEST['start'];
    if (isset($_REQUEST['id'])) {
        $docservers->docserver_id = $_REQUEST['id'];
    }
    if (isset($_REQUEST['docserver_type_id'])) {
        $docservers->docserver_type_id = $_REQUEST['docserver_type_id'];
    }
    if (isset($_REQUEST['device_label'])) {
        $docservers->device_label = $_REQUEST['device_label'];
    }
    if (isset($_REQUEST['is_readonly'])) {
        $docservers->is_readonly = $_REQUEST['is_readonly'];
    }
    if (isset($_REQUEST['size_limit_number'])) {
        $docservers->size_limit_number = $_REQUEST['size_limit_hidden'];
    }
    if (isset($_REQUEST['path_template'])) {
        $docservers->path_template = $_REQUEST['path_template'];
    }
    if (isset($_REQUEST['coll_id'])) {
        $docservers->coll_id = $_REQUEST['coll_id'];
    }
    if (isset($_REQUEST['priority_number'])) {
        $docservers->priority_number = $_REQUEST['priority_number'];
    }
    if (isset($_REQUEST['docserver_location_id'])) {
        $docservers->docserver_location_id = $_REQUEST['docserver_location_id'];
    }
    if (isset($_REQUEST['adr_priority_number'])) {
        $docservers->adr_priority_number = $_REQUEST['adr_priority_number'];
    }
    $control = array();
    $control = $docserversControler->save($docservers, $mode);
    if (!empty($control['error']) && $control['error'] <> 1) {
        // Error management depending of mode
        $_SESSION['error'] = str_replace("#", "<br />", $control['error']);
        put_in_session("status", $status);
        put_in_session("docservers", $docservers->getArray());
        //var_dump($_SESSION['m_admin']['docservers']);
        switch ($mode) {
            case "up":
                if (!empty($_REQUEST['id'])) {
                    header(
                        "location: " . $_SESSION['config']['businessappurl']
                        . "index.php?page=" . $pageName . "&mode=up&id="
                        . $_REQUEST['id'] . "&admin=docservers"
                    );
                } else {
                    header(
                        "location: " . $_SESSION['config']['businessappurl']
                        . "index.php?page=" . $pageName
                        . "&mode=list&admin=docservers&order="
                        . $status['order'] . "&order_field="
                        . $status['order_field'] . "&start=" . $status['start']
                        . "&what=" . $status['what']
                    );
                }
                exit;
            case "add":
                header(
                    "location: " . $_SESSION['config']['businessappurl']
                    . "index.php?page=" . $pageName
                    . "&mode=add&admin=docservers"
                );
                exit;
        }
    } else {
        if ($mode == "add") {
            $_SESSION['info'] = _DOCSERVER_ADDED;
        } else {
            $_SESSION['info'] = _DOCSERVER_UPDATED;
        }
        unset($_SESSION['m_admin']);
        header(
            "location: " . $_SESSION['config']['businessappurl']
            . "index.php?page=" . $pageName . "&mode=list&admin=docservers"
            . "&order=" . $status['order'] . "&order_field="
            . $status['order_field'] . "&start=" . $status['start'] . "&what="
            . $status['what']
        );
    }
}

/**
 * Initialize session parameters for update display
 * @param Long $docserverId
 */
function display_up($docserverId)
{
    $docserversControler = new docservers_controler();
    $state = true;
    $docservers = $docserversControler->get($docserverId);
    if (empty($docservers)) {
        $state = false;
    } else {
        put_in_session("docservers", $docservers->getArray());
        if ($docserversControler->resxLinkExists(
            $docservers->docserver_id,
            $docservers->coll_id
        )
        ) {
            $_SESSION['m_admin']['docservers']['link_exists'] = true;
        }
        if ($docserversControler->adrxLinkExists(
            $docservers->docserver_id,
            $docservers->coll_id
        )
        ) {
            $_SESSION['m_admin']['docservers']['link_exists'] = true;
        }
    }
    return $state;
}

/**
 * Initialize session parameters for add display with given docserver
 */
function display_add()
{
    $sessionName = "docservers";
    if (!isset($_SESSION['m_admin'][$sessionName])) {
        init_session();
    }
}

/**
 * Initialize session parameters for list display
 */
function display_list()
{
    $sessionName = "docservers";
    $pageName = "docservers_management_controler";
    $tableName = "docservers";
    $idName = "docserver_id";
    $func = new functions();
    $listShow = new list_show();
    $_SESSION['m_admin'] = array();
    init_session();
    $select[_DOCSERVERS_TABLE_NAME] = array();
    array_push(
        $select[_DOCSERVERS_TABLE_NAME], $idName, "device_label",
        "docserver_type_id", "size_limit_number", "actual_size_number",
        "coll_id", "enabled"
    );
    $what = "";
    $where = "";
    $arrayPDO = array();
    if (isset($_REQUEST['what']) && !empty($_REQUEST['what'])) {
        $what = $_REQUEST['what'];
        $where = "lower(".$idName.") like lower(?) ";
        $arrayPDO = array($what.'%');
    }
    // Checking order and order_field values
    $order = 'asc';
    if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {
        $order = trim($_REQUEST['order']);
    }
    $field = $idName;
    if (isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field'])) {
        $field = trim($_REQUEST['order_field']);
    }
    $orderstr = $listShow->define_order($order, $field);
    $request = new request();
    $tab = $request->PDOselect(
        $select, $where, $arrayPDO, $orderstr, $_SESSION['config']['databasetype']
    );
    for ($i = 0; $i < count($tab); $i ++) {
        foreach ($tab[$i] as &$item) {
            switch ($item['column']) {
                case $idName:
                    format_item(
                        $item, _ID, "18", "left", "left", "bottom", true
                    );
                    break;
                case "device_label":
                    format_item(
                        $item, _DEVICE_LABEL, "15", "left", "left", "bottom",
                        true
                    );
                    break;
                case "docserver_type_id":
                    format_item(
                        $item, _DOCSERVER_TYPE, "15", "left", "left", "bottom",
                        true
                    );
                    break;
                case "coll_id":
                    format_item(
                        $item, _COLL_ID, "15", "left", "left", "bottom", true
                    );
                    break;
                case "size_limit_number":
                    $sizeLimit = $item['value'];
                    format_item(
                        $item, _SIZE_LIMIT_NUMBER, "5", "left", "left",
                        "bottom", false
                    );
                    break;
                case "actual_size_number":
                    if (isset($sizeLimit) && $sizeLimit <> 0) {
                        $item['value'] = number_format(
                            ($item['value'] * 100) / $sizeLimit, 0
                        );
                    } else {
                        $item['value'] = 0;
                    }
                    $item['value'] .= "%";
                    format_item(
                        $item, _PERCENTAGE_FULL, "5", "left", "left", "bottom",
                        true
                    );
                    break;
                case "enabled":
                    format_item(
                        $item, _ENABLED, "5", "left", "left", "bottom", true
                    );
                    break;
            }
        }
    }
    $result = array();
    $result['tab'] = $tab;
    $result['what'] = $what;
    $result['page_name'] = $pageName . "&mode=list";
    $result['page_name_up'] = $pageName . "&mode=up";
    $result['page_name_del'] = $pageName . "&mode=del";
    $result['page_name_val'] = $pageName . "&mode=allow";
    $result['page_name_ban'] = $pageName . "&mode=ban";
    $result['page_name_add'] = $pageName . "&mode=add";
    $result['label_add'] = _DOCSERVER_ADDITION;
    $_SESSION['m_admin']['init'] = true;
    $result['title'] = _DOCSERVERS_LIST . " : " . count($tab) . " "
                     . _DOCSERVERS;
    $result['autoCompletionArray'] = array();
    $result['autoCompletionArray']["list_script_url"] = $_SESSION['config']['businessappurl']
        . "index.php?display=true&admin=docservers&page=docservers_list_by_id";
    $result['autoCompletionArray']["number_to_begin"] = 1;
    return $result;
}

/**
 * Delete given docserver if exists and initialize session parameters
 * @param unknown_type $docserverId
 */
function display_del($docserverId)
{
    $docserversControler = new docservers_controler();
    $docservers = $docserversControler->get($docserverId);
    if (isset($docservers)) {
        // Deletion
        $control = array();
        $control = $docserversControler->delete($docservers);
        if (!empty($control['error']) && $control['error'] <> 1) {
            $_SESSION['error'] = str_replace("#", "<br />", $control['error']);
        } else {
            $_SESSION['info'] = _DOCSERVER_DELETED . " " . $docserverId;
        }
        $pageName = "docservers_management_controler";
        ?><script>window.top.location='<?php
        echo $_SESSION['config']['businessappurl'] . "index.php?page="
            . $pageName . "&mode=list&admin=docservers";
        ?>';</script>
        <?php
        exit;
    } else {
        // Error management
        $_SESSION['error'] = _DOCSERVER . ' ' . _UNKNOWN;
    }
}

/**
 * allow given docserver if exists
 * @param unknown_type $docserverId
 */
function display_enable($docserverId)
{
    $docserversControler = new docservers_controler();
    $docservers = $docserversControler->get($docserverId);
    if (isset($docservers)) {
        // Enable
        $control = array();
        $control = $docserversControler->enable($docservers);
        if (!empty($control['error']) && $control['error'] <> 1) {
            $_SESSION['error'] = str_replace("#", "<br />", $control['error']);
        } else {
            $_SESSION['info'] = _DOCSERVER_ENABLED." ".$docserverId;
        }
        $pageName = "docservers_management_controler";
        ?><script>window.top.location='<?php
        echo $_SESSION['config']['businessappurl'] . "index.php?page="
            . $pageName . "&mode=list&admin=docservers";
        ?>';</script>
        <?php
        exit;
    } else {
        // Error management
        $_SESSION['error'] = _DOCSERVER . ' ' . _UNKNOWN;
    }
}

/**
 * ban given docserver if exists
 * @param unknown_type $docserverId
 */
function display_disable($docserverId)
{
    $docserversControler = new docservers_controler();
    $docservers = $docserversControler->get($docserverId);
    if (isset($docservers)) {
        // Disable
        $control = array();
        $control = $docserversControler->disable($docservers);
        if (!empty($control['error']) && $control['error'] <> 1) {
            $_SESSION['error'] = str_replace("#", "<br />", $control['error']);
        } else {
            $_SESSION['info'] = _DOCSERVER_DISABLED." ".$docserverId;
        }
        $pageName = "docservers_management_controler";
        ?><script>window.top.location='<?php
        echo $_SESSION['config']['businessappurl'] . "index.php?page="
            . $pageName . "&mode=list&admin=docservers";
        ?>';</script>
        <?php
        exit;
    } else {
        // Error management
        $_SESSION['error'] = _DOCSERVER . ' ' . _UNKNOWN;
    }
}

/**
 * Format given item with given values, according with HTML formating.
 * NOTE: given item needs to be an array with at least 2 keys:
 * 'column' and 'value'.
 * NOTE: given item is modified consequently.
 * @param $item
 * @param $label
 * @param $size
 * @param $labelAlign
 * @param $align
 * @param $valign
 * @param $show
 */
function format_item(&$item, $label, $size, $labelAlign, $align, $valign, $show)
{
    $func = new functions();
    $item['value'] = $func->show_string($item['value']);
    $item[$item['column']] = $item['value'];
    $item["label"] = $label;
    $item["size"] = $size;
    $item["label_align"] = $labelAlign;
    $item["align"] = $align;
    $item["valign"] = $valign;
    $item["show"] = $show;
    $item["order"] = $item['column'];
}

/**
 * Put given object in session, according with given type
 * NOTE: given object needs to be at least hashable
 * @param string $type
 * @param hashable $hashable
 */
function put_in_session($type,$hashable)
{
    $func = new functions();
    foreach ($hashable as $key => $value) {
        // echo "Key: $key Value: $value f:".$func->show_string($value)." // ";
        if ($key == 'path_template') {
            $_SESSION['m_admin'][$type][$key] = $value;
        } else {
            $_SESSION['m_admin'][$type][$key] = $func->show_string($value);
        }
    }
}

