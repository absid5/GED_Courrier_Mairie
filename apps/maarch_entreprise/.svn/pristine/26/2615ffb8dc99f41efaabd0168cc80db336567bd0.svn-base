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
* @brief  Contains the page controler of docserver_locations_management.php
*
* @file
* @author Luc KEULEYAN - BULL
* @author Laurent Giovannoni
* @date $date$
* @version $Revision$
* @ingroup admin
*/

$sessionName = "docserver_locations";
$pageName = "docserver_locations_management_controler";
$tableName = "docserver_locations";
$idName = "docserver_location_id";

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
	require_once 'core/class/docserver_locations_controler.php';
	require_once 'core/class/docservers_controler.php';
	if ($mode == 'list') {
		require_once "apps" . DIRECTORY_SEPARATOR
		    . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . "class"
		    . DIRECTORY_SEPARATOR . "class_list_show.php";
	}
} catch (Exception $e) {
	functions::xecho($e->getMessage());
}

$core_tools->test_admin('admin_docservers', 'apps');

if (isset($_REQUEST['submit'])) {
	// Action to do with db
	validate_cs_submit($mode);
} else {
	// Display to do
	if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
		$docserverLocationId = $_REQUEST['id'];
	}
	$state = true;
	switch ($mode) {
		case "up" :
			$res = display_up($docserverLocationId);
			$state = $res['state'];
			$docservers = $res['docservers'];
			location_bar_management($mode);
			break;
		case "add" :
			display_add();
			location_bar_management($mode);
			break;
		case "del" :
			display_del($docserverLocationId);
			break;
		case "list" :
			$docserverLocationsList = display_list();
			location_bar_management($mode);
			break;
		case "allow" :
			display_enable($docserverLocationId);
			location_bar_management($mode);
		case "ban" :
			display_disable($docserverLocationId);
			location_bar_management($mode);
	}
	include('docserver_locations_management.php');
}

// END of main block

/**
 * Initialize session variables
 */
function init_session()
{
	$sessionName = "docserver_locations";
	$_SESSION['m_admin'][$sessionName] = array();
}

/**
 * Management of the location bar
 */
function location_bar_management($mode)
{
	$sessionName = "docserver_locations";
	$pageName = "docserver_locations_management_controler";
	$tableName = "docserver_locations";
	$idName = "docserver_location_id";

	$pageLabels = array(
		'add' => _ADDITION,
		'up' => _MODIFICATION,
		'list' => _DOCSERVER_LOCATIONS_LIST,
	);
	$pageIds = array(
		'add' => 'docserver_add',
		'up' => 'docserver_up',
		'list' => 'docserver_locations_list'
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
	$sessionName = "docserver_locations";
	$pageName = "docserver_locations_management_controler";
	$tableName = "docserver_locations";
	$idName = "docserver_location_id";
	$f = new functions();
	$docserverLocationsControler = new docserver_locations_controler();
	$docserverLocations = new docserver_locations();
	$status = array();
	$status['order'] = $_REQUEST['order'];
	$status['order_field'] = $_REQUEST['order_field'];
	$status['what'] = $_REQUEST['what'];
	$status['start'] = $_REQUEST['start'];
	if (isset($_REQUEST['id'])) {
	    $docserverLocations->docserver_location_id = $_REQUEST['id'];
	}
	if (isset($_REQUEST['ipv4'])) {
	    $docserverLocations->ipv4 = $_REQUEST['ipv4'];
	}
	if (isset($_REQUEST['ipv6'])) {
	    $docserverLocations->ipv6 = $_REQUEST['ipv6'];
	}
	if (isset($_REQUEST['net_domain'])) {
	    $docserverLocations->net_domain = $_REQUEST['net_domain'];
	}
	if (isset($_REQUEST['mask'])) {
	    $docserverLocations->mask = $_REQUEST['mask'];
	}
	if (isset($_REQUEST['net_link'])) {
	    $docserverLocations->net_link = $_REQUEST['net_link'];
	}
	$control = array();
	$control = $docserverLocationsControler->save($docserverLocations, $mode);
	if (!empty($control['error']) && $control['error'] <> 1) {
		// Error management depending of mode
		$_SESSION['error'] = str_replace("#", "<br />", $control['error']);
		put_in_session("status", $status);
		put_in_session("docserver_locations", $docserverLocations->getArray());
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
			$_SESSION['info'] = _DOCSERVER_LOCATION_ADDED;
		} else {
			$_SESSION['info'] = _DOCSERVER_LOCATION_UPDATED;
		}
		unset($_SESSION['m_admin']);
		header(
			"location: " . $_SESSION['config']['businessappurl']
		    . "index.php?page=" . $pageName . "&mode=list&admin=docservers"
		    . "&order=" . $status['order'] . "&order_field="
		    . $status['order_field'] . "&start=" . $status['start']
		    . "&what=" . $status['what']
		);
	}
}

/**
 * Initialize session parameters for update display
 * @param Long $docserverLocationId
 */
function display_up($docserverLocationId)
{
	$docservers = array();
	$state = true;
	$docserverLocationsControler = new docserver_locations_controler();
	$docserversControler = new docservers_controler();
	$docserverLocations = $docserverLocationsControler->get($docserverLocationId);
	if (empty($docserverLocations)) {
		$state = false;
	} else {
		put_in_session("docserver_locations", $docserverLocations->getArray());
	}
	$docserversId = $docserverLocationsControler->getDocservers(
	    $docserverLocationId
	);
	for ($i = 0; $i < count($docserversId); $i ++) {
		$tmpUser = $docserversControler->get($docserversId[$i]);
		if (isset($tmpUser)) {
			array_push($docservers, $tmpUser);
		}
	}
	unset($tmpUser);
	$res['state'] = $state;
	$res['docservers'] = $docservers;
	return $res;
}

/**
 * Initialize session parameters for add display with given docserver
 */
function display_add()
{
	$sessionName = "docserver_locations";
	if (!isset($_SESSION['m_admin'][$sessionName])) {
		init_session();
	}
}

/**
 * Initialize session parameters for list display
 */
function display_list()
{
	$sessionName = "docserver_locations";
	$pageName = "docserver_locations_management_controler";
	$tableName = "docserver_locations";
	$idName = "docserver_location_id";
	$func = new functions();
	$listShow = new list_show();
	$_SESSION['m_admin'] = array();
	init_session();
	$select[_DOCSERVER_LOCATIONS_TABLE_NAME] = array();
	array_push(
	    $select[_DOCSERVER_LOCATIONS_TABLE_NAME], $idName, "ipv4", "ipv6",
	    "net_domain", "enabled"
	);
	$what = "";
	$where = "";
	$arrayPDO = array();
	if (isset($_REQUEST['what']) && !empty($_REQUEST['what'])) {
		$what = $_REQUEST['what'];
		$where = "lower(".$idName . ") like lower(?) ";
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
				case "ipv4":
					format_item(
					    $item, _IPV4, "15", "left", "left", "bottom", true
					);
					break;
				case "ipv6":
					format_item(
					    $item, _IPV6, "15", "left", "left", "bottom", true
					);
					break;
				case "net_domain":
					format_item(
					    $item, _NET_DOMAIN, "15", "left", "left", "bottom", true
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
	$result['label_add'] = _DOCSERVER_LOCATION_ADDITION;
	$_SESSION['m_admin']['init'] = true;
	$result['title'] = _DOCSERVER_LOCATIONS_LIST . " : " . count($tab)
	                 . " " . _DOCSERVER_LOCATIONS;
	$result['autoCompletionArray'] = array();
	$result['autoCompletionArray']["list_script_url"] = $_SESSION['config']['businessappurl']
	    . "index.php?display=true&admin=docservers"
	    . "&page=docserver_locations_list_by_id";
	$result['autoCompletionArray']["number_to_begin"] = 1;
	return $result;
}

/**
* Delete given docserver if exists and initialize session parameters
* @param unknown_type $docserverLocationId
*/
function display_del($docserverLocationId)
{
	$docserverLocationsControler = new docserver_locations_controler();
	$docserverLocations = $docserverLocationsControler->get($docserverLocationId);
	if (isset($docserverLocations)) {
		// Deletion
		$control = array();
		$control = $docserverLocationsControler->delete($docserverLocations);
		if (!empty($control['error']) && $control['error'] <> 1) {
			$_SESSION['error'] = str_replace("#", "<br />", $control['error']);
		} else {
			$_SESSION['info'] = _DOCSERVER_LOCATION_DELETED . " "
			                   . $docserverLocationId;
		}
		$pageName = "docserver_locations_management_controler";
		?><script>window.top.location='<?php
		echo $_SESSION['config']['businessappurl'] . "index.php?page="
		    . $pageName . "&mode=list&admin=docservers";
		?>';</script>
		<?php
		exit;
	} else {
		// Error management
		$_SESSION['error'] = _DOCSERVER_LOCATION . ' ' . _UNKNOWN;
	}
}

/**
 * allow given docserver if exists
 * @param unknown_type $docserverLocationId
 */
function display_enable($docserverLocationId)
{
	$docserverLocationsControler = new docserver_locations_controler();
	$docserverLocations = $docserverLocationsControler->get($docserverLocationId);
	if (isset($docserverLocations)) {
		// Enable
		$control = array();
		$control = $docserverLocationsControler->enable($docserverLocations);
		if (!empty($control['error']) && $control['error'] <> 1) {
			$_SESSION['error'] = str_replace("#", "<br />", $control['error']);
		} else {
			$_SESSION['info'] = _DOCSERVER_LOCATION_ENABLED . " "
			                   . $docserverLocationId;
		}
		$pageName = "docserver_locations_management_controler";
		?><script>window.top.location='<?php
		echo $_SESSION['config']['businessappurl'] . "index.php?page="
		    . $pageName . "&mode=list&admin=docservers";
		?>';</script>
		<?php
		exit;
	} else {
		// Error management
		$_SESSION['error'] = _DOCSERVER_LOCATION . ' ' . _UNKNOWN;
	}
}

/**
 * ban given docserver if exists
 * @param unknown_type $docserverLocationId
 */
function display_disable($docserverLocationId)
{
	$docserverLocationsControler = new docserver_locations_controler();
	$docserverLocations = $docserverLocationsControler->get($docserverLocationId);
	if (isset($docserverLocations)) {
		// Disable
		$control = array();
		$control = $docserverLocationsControler->disable($docserverLocations);
		if (!empty($control['error']) && $control['error'] <> 1) {
			$_SESSION['error'] = str_replace("#", "<br />", $control['error']);
		} else {
			$_SESSION['info'] = _DOCSERVER_LOCATION_DISABLED . " "
			                   . $docserverLocationId;
		}
		$pageName = "docserver_locations_management_controler";
		?><script>window.top.location='<?php
		echo $_SESSION['config']['businessappurl'] . "index.php?page="
		    . $pageName . "&mode=list&admin=docservers";
		?>';</script>
		<?php
		exit;
	} else {
		// Error management
		$_SESSION['error'] = _DOCSERVER_LOCATION . ' ' . _UNKNOWN;
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
function put_in_session($type, $hashable)
{
	$func = new functions();
	foreach ($hashable as $key => $value) {
		// echo "Key: $key Value: $value f:".$func->show_string($value)." // ";
		$_SESSION['m_admin'][$type][$key] = $func->show_string($value);
	}
}

