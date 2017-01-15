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
* @brief  Contains the docserver_types_management Object (herits of the BaseObject class)
*
*
* @file
* @author Luc KEULEYAN - BULL
* @author Laurent Giovannoni
* @date $date$
* @version $Revision$
* @ingroup admin
*/

$sessionName = "docserver_types";
$pageName = "docserver_types_management_controler";
$tableName = "docserver_types";
$idName = "docserver_type_id";

$mode = 'add';

$core = new core_tools();
$core_tools = new core_tools();
$core->load_lang();

if (isset($_REQUEST['mode']) && !empty($_REQUEST['mode'])) {
    $mode = $_REQUEST['mode'];
} else {
    $mode = 'list';
}

try{
    require_once("core/class/docserver_types_controler.php");
    require_once("core/class/class_request.php");
    require_once("core/class/docservers_controler.php");
    if ($mode == 'list') {
        require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_list_show.php");
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
    if (isset($_REQUEST['id']) && !empty($_REQUEST['id']))
        $docserver_type_id = $_REQUEST['id'];
    $state = true;
    switch ($mode) {
        case "up" :
            $res=display_up($docserver_type_id);
            $state = $res['state'];
            $docservers = $res['docservers'];
            location_bar_management($mode);
            break;
        case "add" :
            display_add();
            location_bar_management($mode);
            break;
        case "del" :
            display_del($docserver_type_id);
            break;
        case "list" :
            $docserver_types_list=display_list();
            location_bar_management($mode);
            break;
        case "allow" :
            display_enable($docserver_type_id);
            location_bar_management($mode);
        case "ban" :
            display_disable($docserver_type_id);
            location_bar_management($mode);
    }
    include('docserver_types_management.php');
}

/**
 * Initialize session variables
 */
function init_session() {
    $sessionName = "docserver_types";
    $_SESSION['m_admin'][$sessionName] = array();
}

/**
 * Management of the location bar
 */
function location_bar_management($mode) {
    $sessionName = "docserver_types";
    $pageName = "docserver_types_management_controler";
    $tableName = "docserver_types";
    $idName = "docserver_type_id";

    $page_labels = array('add' => _ADDITION, 'up' => _MODIFICATION, 'list' => _DOCSERVER_TYPES_LIST);
    $page_ids = array('add' => 'docserver_add', 'up' => 'docserver_up', 'list' => 'docserver_types_list');

    $init = false;
	if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true") 
		$init = true;

	$level = "";
	if (isset($_REQUEST['level']) && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1))
        $level = $_REQUEST['level'];

    $page_path = $_SESSION['config']['businessappurl'].'index.php?page='.$pageName.'&admin=docservers&mode='.$mode;
    $page_label = $page_labels[$mode];
    $page_id = $page_ids[$mode];
    $ct=new core_tools();
    $ct->manage_location_bar($page_path, $page_label, $page_id, $init, $level);

}

/**
 * Validate a submit (add or up),
 * up to saving object
 */
function validate_cs_submit($mode) {
    $sessionName = "docserver_types";
    $pageName = "docserver_types_management_controler";
    $tableName = "docserver_types";
    $idName = "docserver_type_id";
    $f=new functions();
    $docserverTypesControler = new docserver_types_controler();
    $docserver_types = new docserver_types();
    $status= array();
    $status['order']=$_REQUEST['order'];
    $status['order_field']=$_REQUEST['order_field'];
    $status['what']=$_REQUEST['what'];
    $status['start']=$_REQUEST['start'];
	if (isset($_REQUEST['id'])) $docserver_types->docserver_type_id = $_REQUEST['id'];
	if (isset($_REQUEST['docserver_type_label'])) $docserver_types->docserver_type_label = $_REQUEST['docserver_type_label'];
	if (isset($_REQUEST['is_container'])) $docserver_types->is_container = $_REQUEST['is_container'];
	if (isset($_REQUEST['container_max_number'])) $docserver_types->container_max_number = $_REQUEST['container_max_number'];
	if (isset($_REQUEST['is_compressed'])) $docserver_types->is_compressed = $_REQUEST['is_compressed'];
	if (isset($_REQUEST['compression_mode'])) $docserver_types->compression_mode = $_REQUEST['compression_mode'];
	if (isset($_REQUEST['is_meta'])) $docserver_types->is_meta = $_REQUEST['is_meta'];
	if (isset($_REQUEST['meta_template'])) $docserver_types->meta_template = $_REQUEST['meta_template'];
	if (isset($_REQUEST['is_logged'])) $docserver_types->is_logged = $_REQUEST['is_logged'];
	if (isset($_REQUEST['log_template'])) $docserver_types->log_template = $_REQUEST['log_template'];
	if (isset($_REQUEST['is_signed'])) $docserver_types->is_signed = $_REQUEST['is_signed'];
	if (isset($_REQUEST['fingerprint_mode'])) $docserver_types->fingerprint_mode = $_REQUEST['fingerprint_mode'];
	$control = array();
	$control = $docserverTypesControler->save($docserver_types, $mode);	
	if (!empty($control['error']) && $control['error'] <> 1) {
		// Error management depending of mode
		$_SESSION['error'] = str_replace("#", "<br />", $control['error']);
        put_in_session("status",$status);
        put_in_session("docserver_types",$docserver_types->getArray());
        switch ($mode) {
            case "up":
                if (!empty($_REQUEST['id'])) {
                    header("location: ".$_SESSION['config']['businessappurl']."index.php?page=".$pageName."&mode=up&id=".$_REQUEST['id']."&admin=docservers");
                } else {
                    header("location: ".$_SESSION['config']['businessappurl']."index.php?page=".$pageName."&mode=list&admin=docservers&order=".$status['order']."&order_field=".$status['order_field']."&start=".$status['start']."&what=".$status['what']);
                }
                exit;
            case "add":
                header("location: ".$_SESSION['config']['businessappurl']."index.php?page=".$pageName."&mode=add&admin=docservers");
                exit;
        }
    } else {
        if ($mode == "add")
            $_SESSION['info'] =  _DOCSERVER_TYPE_ADDED;
         else
            $_SESSION['info'] = _DOCSERVER_TYPE_UPDATED;
        unset($_SESSION['m_admin']);
        header("location: ".$_SESSION['config']['businessappurl']."index.php?page=".$pageName."&mode=list&admin=docservers&order=".$status['order']."&order_field=".$status['order_field']."&start=".$status['start']."&what=".$status['what']);
    }
}

/**
 * Initialize session parameters for update display
 * @param Long $docserver_type_id
 */
function display_up($docserver_type_id) {
    $docservers = array();
    $state=true;
    $docserverTypesControler = new docserver_types_controler();
    $docserversControler = new docservers_controler();
    $docserver_types = $docserverTypesControler->get($docserver_type_id);
    if (empty($docserver_types)) {
        $state = false;
    } else {
        put_in_session("docserver_types", $docserver_types->getArray());
        if ($docserverTypesControler->docserverLinkExists($docserver_type_id)
        ) {
            $_SESSION['m_admin']['docserver_types']['link_exists'] = true;
        }
    }
    $docservers_id = $docserverTypesControler->getDocservers($docserver_type_id); //ramène le tableau des docserver_id appartenant au type
    for($i=0;$i<count($docservers_id);$i++) {
        $tmp_docserver = $docserversControler->get($docservers_id[$i]);
        if (isset($tmp_docserver)) {
            array_push($docservers, $tmp_docserver);
        }
    }
    unset($tmp_docserver);
    $res['state'] = $state;
    $res['docservers'] = $docservers;
    return $res;
}

/**
 * Initialize session parameters for add display with given docserver
 */
function display_add() {
    $sessionName = "docserver_types";
    if (!isset($_SESSION['m_admin'][$sessionName]))
        init_session();
}

/**
 * Initialize session parameters for list display
 */
function display_list() {
    $sessionName = "docserver_types";
    $pageName = "docserver_types_management_controler";
    $tableName = "docserver_types";
    $idName = "docserver_type_id";
	$func = new functions();
	$listShow = new list_show();
    $_SESSION['m_admin'] = array();
    init_session();
    $select[_DOCSERVER_TYPES_TABLE_NAME] = array();
    array_push($select[_DOCSERVER_TYPES_TABLE_NAME], $idName, "docserver_type_label", "is_container", "is_compressed", "enabled");
    $what = "";
    $where ="";
    $arrayPDO = array();
    if (isset($_REQUEST['what']) && !empty($_REQUEST['what'])) {
        $what = $_REQUEST['what'];
        $where = "lower(".$idName." )like lower(?) ";
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
    $tab=$request->PDOselect($select,$where,$arrayPDO,$orderstr,$_SESSION['config']['databasetype']);
    for ($i=0;$i<count($tab);$i++) {
        foreach($tab[$i] as &$item) {
            switch ($item['column']) {
                case $idName:
                    format_item($item,_ID,"18","left","left","bottom",true); break;
                case "docserver_type_label":
                    format_item($item,_DOCSERVER_TYPE_LABEL,"15","left","left","bottom",true); break;
                case "is_container":
                    if ($item['value'] == "Y") {
                        $item['value'] = "<i class='fa fa-check fa-2x' title='"._CONTAINER."'></i>";
                    } elseif ($item['value'] == "N") {
                        $item['value'] = "<i class='fa fa-remove fa-2x' title='"._NOT_CONTAINER."'></i>";
                    }
                    format_item($item,_IS_CONTAINER,"5","left","left","bottom",true); break;
                case "is_compressed":
                    if ($item['value'] == "Y") {
                        $item['value'] = "<i class='fa fa-check fa-2x' title='"._COMPRESSED."'></i>";
                    } elseif ($item['value'] == "N") {
                        $item['value'] = "<i class='fa fa-remove fa-2x' title='"._NOT_COMPRESSED."'></i>";
                    }
                    format_item($item,_IS_COMPRESSED,"5","left","left","bottom",true); break;
                case "enabled":
                    format_item($item,_ENABLED,"5","left","left","bottom",true); break;
            }
        }
    }
    $result = array();
    $result['tab']=$tab;
    $result['what']=$what;
    $result['page_name'] = $pageName."&mode=list";
    $result['page_name_up'] = $pageName."&mode=up";
    $result['page_name_del'] = $pageName."&mode=del";
    $result['page_name_val']= $pageName."&mode=allow";
    $result['page_name_ban'] = $pageName."&mode=ban";
    $result['page_name_add'] = $pageName."&mode=add";
    $result['label_add'] = _DOCSERVER_TYPE_ADDITION;
    $_SESSION['m_admin']['init'] = true;
    $result['title'] = _DOCSERVER_TYPES_LIST." : ".count($tab)." "._DOCSERVER_TYPES;
    $result['autoCompletionArray'] = array();
    $result['autoCompletionArray']["list_script_url"] = $_SESSION['config']['businessappurl']."index.php?display=true&admin=docservers&page=docserver_types_list_by_id";
    $result['autoCompletionArray']["number_to_begin"] = 1;
    return $result;
}

/**
 * Delete given docserver if exists and initialize session parameters
 * @param unknown_type $docserver_type_id
 */
function display_del($docserver_type_id) {
	$docserverTypesControler = new docserver_types_controler();
	$docserver_types = $docserverTypesControler->get($docserver_type_id);
	if (isset($docserver_types)) {
		// Deletion
		$control = array();
		$control = $docserverTypesControler->delete($docserver_types);
		if (!empty($control['error']) && $control['error'] <> 1) {
			$_SESSION['error'] = str_replace("#", "<br />", $control['error']);
		} else {
			$_SESSION['info'] = _DOCSERVER_TYPE_DELETED." ".$docserver_type_id;
		}
		$pageName = "docserver_types_management_controler";
		?><script>window.top.location='<?php echo $_SESSION['config']['businessappurl']."index.php?page=".$pageName."&mode=list&admin=docservers";?>';</script>
		<?php
		exit;
	} else {
		// Error management
		$_SESSION['error'] = _DOCSERVER_TYPE.' '._UNKNOWN;
	}
}

/**
 * allow given docserver if exists
 * @param unknown_type $docserver_type_id
 */
function display_enable($docserver_type_id) {
	$docserverTypesControler = new docserver_types_controler();
	$docserver_types = $docserverTypesControler->get($docserver_type_id);
	if (isset($docserver_types)) {
		// Enable
		$control = array();
		$control = $docserverTypesControler->enable($docserver_types);
		if (!empty($control['error']) && $control['error'] <> 1) {
			$_SESSION['error'] = str_replace("#", "<br />", $control['error']);
		} else {
			$_SESSION['info'] = _DOCSERVER_TYPE_ENABLED." ".$docserver_type_id;
		}
		$pageName = "docserver_types_management_controler";
		?><script>window.top.location='<?php echo $_SESSION['config']['businessappurl']."index.php?page=".$pageName."&mode=list&admin=docservers";?>';</script>
		<?php
		exit;
	} else {
		// Error management
		$_SESSION['error'] = _DOCSERVER_TYPE.' '._UNKNOWN;
	}
}

/**
 * ban given docserver if exists
 * @param unknown_type $docserver_type_id
 */
function display_disable($docserver_type_id) {
	$docserverTypesControler = new docserver_types_controler();
	$docserver_types = $docserverTypesControler->get($docserver_type_id);
	if (isset($docserver_types)) {
		// Disable
		$control = array();
		$control = $docserverTypesControler->disable($docserver_types);
		if (!empty($control['error']) && $control['error'] <> 1) {
			$_SESSION['error'] = str_replace("#", "<br />", $control['error']);
		} else {
			$_SESSION['info'] = _DOCSERVER_TYPE_DISABLED." ".$docserver_type_id;
		}
		$pageName = "docserver_types_management_controler";
		?><script>window.top.location='<?php echo $_SESSION['config']['businessappurl']."index.php?page=".$pageName."&mode=list&admin=docservers";?>';</script>
		<?php
		exit;
	} else {
		// Error management
		$_SESSION['error'] = _DOCSERVER_TYPE.' '._UNKNOWN;
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
 * @param $label_align
 * @param $align
 * @param $valign
 * @param $show
 */
function format_item(&$item,$label,$size,$label_align,$align,$valign,$show) {
	$func = new functions();
    $item['value']=$func->show_string($item['value']);
    $item[$item['column']]=$item['value'];
    $item["label"]=$label;
    $item["size"]=$size;
    $item["label_align"]=$label_align;
    $item["align"]=$align;
    $item["valign"]=$valign;
    $item["show"]=$show;
    $item["order"]=$item['column'];
}

/**
 * Put given object in session, according with given type
 * NOTE: given object needs to be at least hashable
 * @param string $type
 * @param hashable $hashable
 */
function put_in_session($type,$hashable) {
    $func = new functions();
    foreach($hashable as $key=>$value) {
        // echo "Key: $key Value: $value f:".$func->show_string($value)." // ";
        $_SESSION['m_admin'][$type][$key]=$func->show_string($value);
    }
}

?>
