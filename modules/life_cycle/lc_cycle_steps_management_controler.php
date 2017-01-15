<?php

/*
*   Copyright 2008-2011 Maarch
*
*   This file is part of Maarch Framework.
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
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief  Contains the life_cycle Object (herits of the BaseObject class)
* 
* 
* @file
* @author Laurent Giovannoni
* @author Sébastien Nana
* @date $date$
* @version $Revision$
* @ingroup life_cycle
*/

$sessionName = "lc_cycle_steps";
$pageName = "lc_cycle_steps_management_controler";
$tableName = "lc_cycle_steps";
$idName = "cycle_step_id";

$mode = 'add';

$core = new core_tools();
$core->load_lang();

$core->test_admin('admin_life_cycle', 'life_cycle');

if (isset($_REQUEST['mode']) && !empty($_REQUEST['mode'])) {
    $mode = $_REQUEST['mode'];
} else {
    $mode = 'list'; 
}

try {
    require_once("modules/life_cycle/class/lc_cycle_steps_controler.php");
    require_once("modules/life_cycle/class/lc_policies_controler.php");
    require_once('core/admin_tools.php');
    require_once("core/class/class_request.php");
    require_once("core/class/docserver_types_controler.php");
    if ($mode == 'list') {
        require_once("modules/life_cycle/lang/fr.php");
        require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id']
            .DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR
            ."class_list_show.php");
    }
} catch (Exception $e) {
    functions::xecho($e->getMessage());
}

$lcPoliciesControler = new lc_policies_controler();
$lcCycleStepsControler = new lc_cycle_steps_controler();
$docserverTypesControler = new docserver_types_controler();
if ($mode == "up" || $mode == "add") {
    $policiesArray = array();
    $stepsArray = array();
    $docserverTypesArray = array();
    $policiesArray = $lcPoliciesControler->getAllId();
    if (isset($_SESSION['m_admin']['lc_cycle_steps']['policy_id'])) {
        $stepsArray = $lcCycleStepsControler->getAllId(
            $_SESSION['m_admin']['lc_cycle_steps']['policy_id']
        );
    }
    $docserverTypesArray = $docserverTypesControler->getAllId();
}

if (isset($_REQUEST['submit'])) {
    // Action to do with db
    validate_cs_submit($mode);
} else {
    // Display to do
    if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
        $cycleStepId = $_REQUEST['id'];
    }
    $state = true;
    switch ($mode) {
        case "up" :
            $state = display_up($cycleStepId); 
            location_bar_management($mode);
            break;
        case "add" :
            display_add(); 
            location_bar_management($mode);
            break;
        case "del" :            
            display_del($cycleStepId); 
            break;
        case "list" :
            $lc_cycle_steps_list = display_list(); 
            location_bar_management($mode);
            break;
        case "allow" :
            display_enable($cycleStepId); 
            location_bar_management($mode);
        case "ban" :
            display_disable($cycleStepId); 
            location_bar_management($mode);
    }
    include('lc_cycle_steps_management.php');
}

/**
 * Initialize session variables
 */
function init_session()
{
    $sessionName = "lc_cycle_steps";
    $_SESSION['m_admin'][$sessionName] = array();
}

/**
 * Management of the location bar  
 */
function location_bar_management($mode)
{
    //$sessionName = "lc_cycle_steps";
    $pageName = "lc_cycle_steps_management_controler";
    //$tableName = "lc_cycle_steps";
    //$idName = "cycle_step_id";
    
    $pageLabels = array(
        'add' => _ADDITION, 
        'up' => _MODIFICATION, 
        'list' => _LC_CYCLE_STEPS_LIST,
    );
    $pageIds = array(
        'add' => 'docserver_add', 
        'up' => 'docserver_up', 
        'list' => 'lc_cycle_steps_list',
    );

    $init = false;
    if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true") {
        $init = true;
    }
    $level = "";
    if (isset($_REQUEST['level']) 
        && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3 
            || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1
        )
    ) {
        $level = $_REQUEST['level'];
    }
    $pagePath = $_SESSION['config']['businessappurl'].'index.php?page='
        .$pageName.'&module=life_cycle&mode='.$mode;
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
    //$sessionName = "lc_cycle_steps";
    $pageName = "lc_cycle_steps_management_controler";
    //$tableName = "lc_cycle_steps";
    //$idName = "cycle_step_id";
    //$f=new functions();
    $lcCycleStepsControler = new lc_cycle_steps_controler();
    $status = array();
    $status['order'] = $_REQUEST['order'];
    $status['order_field'] = $_REQUEST['order_field'];
    $status['what'] = $_REQUEST['what'];
    $status['start'] = $_REQUEST['start'];
    $lc_cycle_steps = new lc_cycle_steps();
    if (isset($_REQUEST['id'])) {
        $lc_cycle_steps->cycle_step_id = $_REQUEST['id'];
    }
    if (isset($_REQUEST['policy_id'])) {
        $lc_cycle_steps->policy_id = $_REQUEST['policy_id'];
    }
    if (isset($_REQUEST['cycle_id'])) {
        $lc_cycle_steps->cycle_id = $_REQUEST['cycle_id'];
    }
    if (isset($_REQUEST['docserver_type_id'])) {
        $lc_cycle_steps->docserver_type_id = $_REQUEST['docserver_type_id'];
    }
    if (isset($_REQUEST['cycle_step_desc'])) {
        $lc_cycle_steps->cycle_step_desc = $_REQUEST['cycle_step_desc'];
    }
    if (isset($_REQUEST['sequence_number'])) {
        $lc_cycle_steps->sequence_number = $_REQUEST['sequence_number'];
    }
    //if (isset($_REQUEST['is_allow_failure'])) {
        //$lc_cycle_steps->is_allow_failure = $_REQUEST['is_allow_failure'];
    //}
    //if (isset($_REQUEST['is_must_complete'])) {
        //$lc_cycle_steps->is_must_complete = $_REQUEST['is_must_complete'];
    //}
    if (isset($_REQUEST['step_operation'])) {
        $lc_cycle_steps->step_operation = $_REQUEST['step_operation'];    
    }
    $control = array();
    $control = $lcCycleStepsControler->save($lc_cycle_steps, $mode);
    if (!empty($control['error']) && $control['error'] <> 1) {
        // Error management depending of mode
        $_SESSION['error'] = str_replace("#", "<br />", $control['error']);
        At_putInSession("status", $status);
        At_putInSession("lc_cycle_steps", $lc_cycle_steps->getArray());
        switch ($mode) {
            case "up":
                if (!empty($_REQUEST['id'])) {
                    header("location: ".$_SESSION['config']['businessappurl']
                           ."index.php?page=".$pageName."&mode=up&id="
                           .$_REQUEST['id']
                           ."&module=life_cycle");
                } else {
                    header("location: ".$_SESSION['config']['businessappurl']
                           ."index.php?page=".$pageName
                           ."&mode=list&module=life_cycle&order="
                           .$status['order']."&order_field="
                           .$status['order_field']."&start=".$status['start']
                           ."&what=".$status['what']);
                }
                exit;
            case "add":
                header("location: ".$_SESSION['config']['businessappurl']
                       ."index.php?page=".$pageName
                       ."&mode=add&module=life_cycle");
                exit;
        }
    } else {
        if ($mode == "add") {
            $_SESSION['info'] = _LC_CYCLE_STEP_ADDED;
        } else {
            $_SESSION['info'] = _LC_CYCLE_STEP_UPDATED;
        }
        unset($_SESSION['m_admin']);
        header("location: ".$_SESSION['config']['businessappurl']
               ."index.php?page=".$pageName
               ."&mode=list&module=life_cycle&order="
               .$status['order']."&order_field=".$status['order_field']
               ."&start=".$status['start']."&what=".$status['what']);
    }
}

/**
 * Initialize session parameters for update display
 * @param Long $cycleStepId
 */
function display_up($cycleStepId)
{
    $state = true;
    $lcCycleStepsControler = new lc_cycle_steps_controler();
    $lc_cycle_steps = $lcCycleStepsControler->get($cycleStepId);
    if (empty($lc_cycle_steps)) {
        $state = false;
    } else {
        At_putInSession("lc_cycle_steps", $lc_cycle_steps->getArray()); 
    }
    return $state;
}

/**
 * Initialize session parameters for add display with given docserver
 */
function display_add()
{
    $sessionName = "lc_cycle_steps";
    if (!isset($_SESSION['m_admin'][$sessionName])) {
        init_session();
    }
}

/**
 * Initialize session parameters for list display
 */
function display_list()
{
    //$sessionName = "lc_cycle_steps";
    $pageName = "lc_cycle_steps_management_controler";
    //$tableName = "lc_cycle_steps";
    $idName = "cycle_step_id";
    $func = new functions();
    $_SESSION['m_admin'] = array();
    init_session();
    $select[_LC_CYCLE_STEPS_TABLE_NAME] = array();
    array_push(
        $select[_LC_CYCLE_STEPS_TABLE_NAME], 
        $idName, 
        "cycle_id", 
        "cycle_step_desc", 
        "policy_id" , 
        "sequence_number"
    );
    $what = "";
    $where = "";
    $arrayPDO = array();
    if (isset($_REQUEST['what']) && !empty($_REQUEST['what'])) {
        $what = $_REQUEST['what'];
        $where = "lower(".$idName.") like lower(?) ";
        $arrayPDO = array_merge($arrayPDO, array($what.'%'));
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
    $listShow = new list_show();
    $orderstr = $listShow->define_order($order, $field);
    $request = new request();
    $tab = $request->PDOselect(
        $select, $where, $arrayPDO, $orderstr, $_SESSION['config']['databasetype']
    );
    //$request->show();
    for ($i = 0;$i < count($tab);$i++) {
        foreach ($tab[$i] as &$item) {
            switch ($item['column']) {
                case $idName:
                    At_formatItem(
                        $item, _ID, "15", "left", "left", "bottom", true
                    );
                    break;
                case "cycle_step_desc":
                    At_formatItem(
                        $item, _CYCLE_STEP_DESC, "40", "left", "left", 
                        "bottom", true
                    );
                    break;
                case "cycle_id":
                    At_formatItem(
                        $item, _CYCLE_ID, "15", "left", "left", "bottom", true
                    );
                    break;
                case "policy_id":
                    At_formatItem(
                        $item, _POLICY_ID, "15", "left", "left", "bottom", true
                    );
                    break;
                case "sequence_number":
                    At_formatItem(
                        $item, _SEQUENCE_NUMBER, "15", "left", "left", 
                        "bottom", true
                    );
                    break;
            }
        }
    }
    $result = array();
    $result['tab'] = $tab;
    $result['what'] = $what;
    $result['page_name'] = $pageName."&mode=list";
    $result['page_name_up'] = $pageName."&mode=up";
    $result['page_name_del'] = $pageName."&mode=del";
    //$result['page_name_val']= $pageName."&mode=allow";
    //$result['page_name_ban'] = $pageName."&mode=ban";
    $result['page_name_add'] = $pageName."&mode=add";
    $result['label_add'] = _LC_CYCLE_STEP_ADDITION;
    $_SESSION['m_admin']['init'] = true;
    $result['title'] = _LC_CYCLE_STEPS_LIST." : ".count($tab)." "
        ._LC_CYCLE_STEPS;
    $result['autoCompletionArray'] = array();
    $result['autoCompletionArray']["list_script_url"] =
        $_SESSION['config']['businessappurl']
            ."index.php?display=false&module=life_cycle&page=lc_cycle_steps_list_by_id";
    $result['autoCompletionArray']["number_to_begin"] = 1;
    return $result;
}

/**
 * Delete given docserver if exists and initialize session parameters
 * @param unknown_type $cycleStepId
 */
function display_del($cycleStepId)
{
    $lcCycleStepsControler = new lc_cycle_steps_controler();
    $lc_cycle_steps = $lcCycleStepsControler->get($cycleStepId);
    if (isset($lc_cycle_steps)) {
        // Deletion
        $control = array();
        $control = $lcCycleStepsControler->delete($lc_cycle_steps);
        if (!empty($control['error']) && $control['error'] <> 1) {
            $_SESSION['error'] = str_replace("#", "<br />", $control['error']);
        } else {
            $_SESSION['info'] = _LC_CYCLE_STEP_DELETED." ".$cycleStepId;
        }
        $pageName = "lc_cycle_steps_management_controler";
        ?><script>window.top.location='<?php echo $_SESSION['config']['businessappurl']
            ."index.php?page=".$pageName."&mode=list&module=life_cycle";?>';</script>
        <?php
        exit;
    } else {
        // Error management
        $_SESSION['error'] = _LC_CYCLE_STEP.' '._UNKNOWN;
    }
}

