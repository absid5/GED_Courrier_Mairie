<?php
/**
* File : manage_listmodel.php
*
* Pop up used to create and modify diffusion lists models
*
* @package Maarch LetterBox 2.3
* @version 1.0
* @since 06/2006
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
* @author  Cyril Vazquez  <dev@maarch.org>
*/
require_once 'core/class/usergroups_controler.php';
require_once 'modules/entities/class/class_manage_listdiff.php';
require_once 'modules/entities/entities_tables.php';
require_once 'core/core_tables.php';

$core_tools = new core_tools();
$core_tools->load_lang();
$func = new functions();

$db = new Database();

$difflist = new diffusion_list();
$usergroups_controler = new usergroups_controler();

# *****************************************************************************
# Manage request paramaters
# *****************************************************************************
// Origin not used
$origin = $_REQUEST['origin'];

// Action ?
if (isset($_GET['action']))
    $action = $_GET['action'];
else  
    $action = false;

// Id ?
if(isset($_GET['id']))
    $id = $_GET['id'];
else  
    $id = false;

// Rank for remove/move ?
if(isset($_GET['rank']))
    $rank = $_GET['rank'];
else
    $rank = false;

// Mode (dest/copy or custom copy mode)
if(isset($_GET['role']) && !empty($_GET['role']))
    $role_id = $_GET['role'];
else 
    $role_id = false;
# *****************************************************************************
# Manage SESSION paramaters
# *****************************************************************************
// Object/list type
$objectType = $_SESSION['m_admin']['entity']['listmodel']['object_type'];
$objectId = $_SESSION['m_admin']['entity']['listmodel']['object_id'];
$description = $_SESSION['m_admin']['entity']['listmodel']['description'];

# Load roles
$difflistType = $difflist->get_difflist_type($objectType);
$roles = $difflist->get_difflist_type_roles($difflistType);
$available_roles = $difflist->list_difflist_roles();

if($difflistType->allow_entities == 'Y')
    $allow_entities = true;
else 
    $allow_entities = false;
   
// Dest user    
if(isset($_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]) 
    && !empty($_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]))
    $dest_is_set = true;
else
    $dest_is_set = false;

if(isset($_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]) 
    && !empty($_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]))
    $sign_is_set = true;
else
    $sign_is_set = false;
    
// 1.4 create indexed array of existing diffusion to search for users/entities easily
$user_roles = array();
$entity_roles = array();
foreach($roles as $role_id_local => $role_label) {
    for($i=0, $l=count($_SESSION['m_admin']['entity']['listmodel'][$role_id_local]['users']); 
        $i<$l; $i++
    ) {
        $user_id = $_SESSION['m_admin']['entity']['listmodel'][$role_id_local]['users'][$i]['user_id'];
        $user_roles[$user_id][] = $role_id_local;
    }
    for($i=0, $l=count($_SESSION['m_admin']['entity']['listmodel'][$role_id_local]['entities']); 
        $i<$l; 
        $i++
    ) {
        $entity_id = $_SESSION['m_admin']['entity']['listmodel'][$role_id_local]['entities'][$i]['entity_id'];
        $entity_roles[$entity_id][] = $role_id_local;
    }
}
# *****************************************************************************
# Search functions / filter users and entities avilable for list composition
# *****************************************************************************
if (isset($_POST['what_users']) && !empty($_POST['what_users'])) {
    $_GET['what_users'] = $_POST['what_users'];
}
if (isset($_POST['what_services']) && ! empty($_POST['what_services']) ) {
    $_GET['what_services'] = $_POST['what_services'];
}
if (isset($_REQUEST['no_filter'])) {
    $_GET['what_users'] = '%';
    $_GET['what_services'] = '%';
}
$users = array();
$entities = array();
$PDOarray = array();
if (isset($_GET['what_users']) 
    && ! empty($_GET['what_users']) 
) {
    $what_users = $func->wash($_GET['what_users'], 'no', '', 'no');
    $user_expr = 
        " and ( "
            . "lower(u.lastname) like lower(:whatUser) "
            . " or lower(u.firstname) like lower(:whatUser) "
            . " or lower(u.user_id) like lower(:whatUser)"
        . ")";
    $PDOarray = array_merge($PDOarray, array(":whatUser" => "%" . $what_users . "%"));
}
if (isset($_GET['what_services']) 
    && ! empty($_GET['what_services'])
) {
    $what_services = addslashes(
        $func->wash($_GET['what_services'], 'no', '', 'no')
    );
    $entity_expr = 
        " and ("
            . " lower(e.entity_label) like lower(:whatEntity) "
            . " or lower(e.entity_id) like lower(:whatEntity)"
        .")";
    $PDOarray = array_merge($PDOarray, array(":whatEntity" => "%" . $what_services . "%"));
    
}
$users_query = 
    "select u.user_id, u.firstname, u.lastname, e.entity_id, e.entity_label "
    . "FROM " . $_SESSION['tablename']['users'] . " u, " . ENT_ENTITIES . " e, "
    . ENT_USERS_ENTITIES . " ue WHERE u.status <> 'DEL' and u.enabled = 'Y' and"
    . " e.entity_id = ue.entity_id and u.user_id = ue.user_id and"
    . " e.enabled = 'Y' and ue.primary_entity='Y' " . $user_expr . $entity_expr
    . " order by u.lastname asc, u.firstname asc, u.user_id asc, e.entity_label asc";

if ($objectType == 'VISA_CIRCUIT')
$users_query = 
    "select u.user_id, u.firstname, u.lastname, e.entity_id, e.entity_label "
    . "FROM " . $_SESSION['tablename']['users'] . " u, " . ENT_ENTITIES . " e, "
    . ENT_USERS_ENTITIES . " ue, ".USERGROUP_CONTENT_TABLE." uc WHERE u.status <> 'DEL' and u.enabled = 'Y' and"
    . " e.entity_id = ue.entity_id and u.user_id = ue.user_id and"
    . " e.enabled = 'Y' and ue.primary_entity='Y' " . $user_expr . $entity_expr
    . " and u.user_id = uc.user_id AND uc.group_id IN (SELECT group_id FROM usergroups_services WHERE service_id = 'visa_documents')"
    . " order by u.lastname asc, u.firstname asc, u.user_id asc, e.entity_label asc";
	
if ($user_expr == '' && $entity_expr == '') {
    //no query
} else {
    $stmt = $db->query($users_query,$PDOarray);
    while ($line = $stmt->fetchObject()) {
        array_push(
            $users,
            array(
                'ID'     => functions::show_string($line->user_id),
                'PRENOM' => functions::show_string($line->firstname),
                'NOM'    => functions::show_string($line->lastname),
                'DEP_ID' => functions::show_string($line->entity_id),
                'DEP'    => functions::show_string($line->entity_label)
            )
        );
    }
}

$entity_query =
    "select e.entity_id,  e.entity_label FROM "
        . $_SESSION['tablename']['users'] . " u, " . ENT_ENTITIES . " e, "
        . ENT_USERS_ENTITIES . " ue WHERE u.status <> 'DEL' and u.enabled = 'Y'"
        . "and  e.entity_id = ue.entity_id and u.user_id = ue.user_id and "
        . "e.enabled = 'Y' " . $user_expr . $entity_expr
        . " group by e.entity_id, e.entity_label order by e.entity_label asc";

if ($user_expr == '' && $entity_expr == '') {
    //no query
} else {
    $stmt = $db->query($entity_query, $PDOarray);
    while ($line = $stmt->fetchObject()) {
        array_push(
            $entities,
            array(
                'ID' => functions::show_string($line->entity_id),
                'DEP' =>functions::show_string($line->entity_label)
            )
        );
    }
}
#****************************************************************************************
# RELOAD PARENT ID VALIDATION OF LIST
#****************************************************************************************     
if (isset($_POST['valid'])) {
    $_SESSION['popup_suite'] = true;
    # Reload caller with new list in session ?>
    <script type="text/javascript">
        window.parent.opener.location.reload();
        self.close();
    </script><?php
    exit;
}
#****************************************************************************************
# SWITCH ON ACTION REQUEST
#**************************************************************************************** 
switch($action) {
// ADDS
//***************************************************************************************
// Add USER AS dest/copy/custom mode
case "add_user":
    $stmt = $db->query(
        "SELECT u.firstname, u.lastname, e.entity_id, e.entity_label "
        . " FROM " . USERS_TABLE . " u "
        . " LEFT JOIN " . ENT_USERS_ENTITIES . " ue ON u.user_id = ue.user_id "
        . " LEFT JOIN " . ENT_ENTITIES . " e ON ue.entity_id = e.entity_id" 
        . " WHERE u.user_id= ? and ue.primary_entity = 'Y'",array($id)
    );
    $line = $stmt->fetchObject();
    
    $visible = 'Y';
    if(!isset($_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'])) {
        $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'] = array();  
    } else {
        if($lastUser = end($_SESSION['m_admin']['entity']['listmodel'][$role_id]['users']))
            $visible = $lastUser['visible'];
    }
    
    # If dest is set && role is dest, move current dest to copy (legacy)
    if ($role_id == 'dest' && $dest_is_set) {
        if(!isset($_SESSION['m_admin']['entity']['listmodel']['copy']['users']))
            $_SESSION['m_admin']['entity']['listmodel']['copy']['users'] = array();
        
        $old_dest = $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['user_id'];
        if(!in_array('copy', $user_roles[$old_dest])) {
            array_push(
                $_SESSION['m_admin']['entity']['listmodel']['copy']['users'],
                array(
                    'user_id' => $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['user_id'],
                    'firstname' => $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['firstname'],
                    'lastname' => $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['lastname'],
                    'entity_id' => $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['entity_id'],
                    'entity_label' => $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['entity_label'],
                    'visible' => 'Y',
                )
            );
        }
        unset($_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]);
    }
	
	# If sign is set && role is sign, move current sign to visa (legacy)
    if ($role_id == 'sign' && $sign_is_set) {
        if(!isset($_SESSION['m_admin']['entity']['listmodel']['visa']['users']))
            $_SESSION['m_admin']['entity']['listmodel']['visa']['users'] = array();
        
        $old_dest = $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['user_id'];
        if(!in_array('visa', $user_roles[$old_dest])) {
            array_push(
                $_SESSION['m_admin']['entity']['listmodel']['visa']['users'],
                array(
                    'user_id' => $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['user_id'],
                    'firstname' => $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['firstname'],
                    'lastname' => $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['lastname'],
                    'entity_id' => $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['entity_id'],
                    'entity_label' => $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['entity_label'],
                    'visible' => 'Y',
                )
            );
        }
        unset($_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]);
    }
    
    array_push(
        $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'],
        array(
            'user_id' => functions::show_string($id),
            'firstname' => functions::show_string($line->firstname),
            'lastname' => functions::show_string($line->lastname),
            'entity_id' => functions::show_string($line->entity_id),
            'entity_label' => functions::show_string($line->entity_label),
            'visible' => $visible,
        )
    ); 
    $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'] = array_values(
            $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users']
    );
    break;

// ADD ENTITY AS copy/custom mode
case 'add_entity':
    $stmt = $db->query(
        "SELECT entity_id, entity_label FROM " . ENT_ENTITIES
        . " WHERE entity_id = ?",array($id)
    );
    $line = $stmt->fetchObject();
    $visible = 'Y';
    if(! isset($_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'])) {
        $_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'] = array();
    }  else {  
        if($lastEntity = end($_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities']))
            $visible = $lastEntity['visible'];
    }
    array_push(
        $_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'],
        array(
            'entity_id'    => $db->show_string($id),
            'entity_label' => $db->show_string($line->entity_label),
            'visible' => $visible,
        )
    );
    break;    

// REMOVE
//***************************************************************************************
// Remove USER
case 'remove_user':
    if($rank !== false && $id && $role_id
        && $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]['user_id'] == $id
    ) {
        $visible = $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]['visible'];
        unset($_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]);
        $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'] = array_values(
            $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users']
        );
        if(isset($_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]))
            $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]['visible'] = $visible;
        if($role_id == 'dest') $dest_is_set = false;
        if($role_id == 'sign') $sign_is_set = false;
    }
    break;

// Remove ENTITY
case 'remove_entity':
    if($rank !== false && $id && $role_id
        && $_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'][$rank]['entity_id'] == $id
    ) {
        $visible = $_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'][$rank]['visible'];
        unset($_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'][$rank]);
        $_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'] = array_values(
            $_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities']
        );
        if(isset($_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'][$rank]))
            $_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'][$rank]['visible'] = $visible;
    }
    break;

// MOVE
//***************************************************************************************    
case 'dest_to_copy':
    if ($dest_is_set) {
        if(! isset($_SESSION['m_admin']['entity']['listmodel']['copy']['users']))
            $_SESSION['m_admin']['entity']['listmodel']['copy']['users'] = array();
        
        $old_dest = $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['user_id'];
        if(!in_array('copy', $user_roles[$old_dest])) {
            array_push(
                $_SESSION['m_admin']['entity']['listmodel']['copy']['users'],
                array(
                    'user_id' => $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['user_id'],
                    'firstname' => $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['firstname'],
                    'lastname' => $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['lastname'],
                    'entity_id' => $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['entity_id'],
                    'entity_label' => $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['entity_label'],
                    'visible' => 'Y',
                )
            );
        }
        unset($_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]);
        $_SESSION['m_admin']['entity']['listmodel']['dest']['users'] = array_values(
            $_SESSION['m_admin']['entity']['listmodel']['dest']['users']
        );
        $dest_is_set = false;
    }
    break;

case 'copy_to_dest':
    if ($dest_is_set) {
        if(! isset($_SESSION['m_admin']['entity']['listmodel'][$role_id]['users']))
            $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'] = array();
        $old_dest = $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['user_id'];
        if(!in_array('copy', $user_roles[$old_dest])) {
            array_push(
                $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'],
                array(
                    'user_id' => $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['user_id'],
                    'firstname' => $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['firstname'],
                    'lastname' => $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['lastname'],
                    'entity_id' => $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['entity_id'],
                    'entity_label' => $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['entity_label'],
                    'visible' => 'Y',
                )
            );
        }
        unset($_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]);
        $_SESSION['m_admin']['entity']['listmodel']['dest']['users'] = array_values(
            $_SESSION['m_admin']['entity']['listmodel']['dest']['users']
        );
    }
    if (isset($_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]['user_id'])
        && !empty($_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]['user_id'])
    ) {
        $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['user_id'] = $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]['user_id'];
        $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['firstname'] = $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]['firstname'];
        $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['lastname'] = $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]['lastname'];
        $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['entity_id'] = $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]['entity_id'];
        $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['entity_label'] = $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]['entity_label'];
        $_SESSION['m_admin']['entity']['listmodel']['dest']['users'][0]['visible'] = 'Y';
        unset( $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]);
        $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'] = array_values(
            $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users']
        );
        $dest_is_set = true;
    }
    break;    
case 'sign_to_visa':
    if ($sign_is_set) {
        if(! isset($_SESSION['m_admin']['entity']['listmodel']['visa']['users']))
            $_SESSION['m_admin']['entity']['listmodel']['visa']['users'] = array();
        
        $old_dest = $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['user_id'];
        if(!in_array('visa', $user_roles[$old_dest])) {
            array_push(
                $_SESSION['m_admin']['entity']['listmodel']['visa']['users'],
                array(
                    'user_id' => $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['user_id'],
                    'firstname' => $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['firstname'],
                    'lastname' => $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['lastname'],
                    'entity_id' => $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['entity_id'],
                    'entity_label' => $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['entity_label'],
                    'visible' => 'Y',
                )
            );
        }
        unset($_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]);
        $_SESSION['m_admin']['entity']['listmodel']['sign']['users'] = array_values(
            $_SESSION['m_admin']['entity']['listmodel']['sign']['users']
        );
        $sign_is_set = false;
    }
    break;
	
case 'visa_to_sign':
    if ($sign_is_set) {
        if(! isset($_SESSION['m_admin']['entity']['listmodel'][$role_id]['users']))
            $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'] = array();
        $old_sign = $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['user_id'];
        if(!in_array('visa', $user_roles[$old_sign])) {
            array_push(
                $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'],
                array(
                    'user_id' => $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['user_id'],
                    'firstname' => $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['firstname'],
                    'lastname' => $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['lastname'],
                    'entity_id' => $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['entity_id'],
                    'entity_label' => $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['entity_label'],
                    'visible' => 'Y',
                )
            );
        }
        unset($_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]);
        $_SESSION['m_admin']['entity']['listmodel']['sign']['users'] = array_values(
            $_SESSION['m_admin']['entity']['listmodel']['sign']['users']
        );
    }
    if (isset($_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]['user_id'])
        && !empty($_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]['user_id'])
    ) {
        $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['user_id'] = $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]['user_id'];
        $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['firstname'] = $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]['firstname'];
        $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['lastname'] = $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]['lastname'];
        $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['entity_id'] = $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]['entity_id'];
        $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['entity_label'] = $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]['entity_label'];
        $_SESSION['m_admin']['entity']['listmodel']['sign']['users'][0]['visible'] = 'Y';
        unset( $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]);
        $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'] = array_values(
            $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users']
        );
        $sign_is_set = true;
    }
    break; 
case 'move_user_down':
    $downUser = 
        array_splice(
            $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'], 
            $rank,
            1,
            $preserve_keys = true
        );
    $newRank = $rank+1;
    $upUser = 
        array_splice(
            $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'], 
            $newRank,
            1,
            $preserve_keys = true
        );
    if($upUser[0] && $downUser[0]) {
        # Switch visible values
        $downUserVisible = $downUser[0]['visible'];
        $upUserVisible = $upUser[0]['visible'];
        $upUser[0]['visible'] = $downUserVisible;
        $downUser[0]['visible'] = $upUserVisible;
        # Switch positions
        $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank] = $upUser[0];
        $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$newRank] = $downUser[0];
    }
    break;

case 'move_entity_down':
    $downEntity = 
        array_splice(
            $_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'], 
            $rank,
            1,
            $preserve_keys = true
        );
    $newRank = $rank+1;
    $upEntity = 
        array_splice(
            $_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'], 
            $newRank,
            1,
            $preserve_keys = true
        );
    if($upEntity[0] && $downEntity[0]) {
        # Switch visible values
        $downEntityVisible = $downEntity[0]['visible'];
        $upEntityVisible = $upEntity[0]['visible'];
        $upEntity[0]['visible'] = $downEntityVisible;
        $downEntity[0]['visible'] = $upEntityVisible;
        # Switch positions
        $_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'][$rank] = $upEntity[0];
        $_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'][$newRank] = $downEntity[0];
    }
    break;
    
case 'move_user_up':
    $upUser = 
        array_splice(
            $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'], 
            $rank,
            1,
            $preserve_keys = true
        );
    $newRank = $rank-1;
    $downUser = 
        array_splice(
            $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'], 
            $newRank,
            1,
            $preserve_keys = true
        );
    if($upUser[0] && $downUser[0]) {
        # Switch visible values
        $downUserVisible = $downUser[0]['visible'];
        $upUserVisible = $upUser[0]['visible'];
        $upUser[0]['visible'] = $downUserVisible;
        $downUser[0]['visible'] = $upUserVisible;
        # Switch positions
        $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank] = $downUser[0]; 
        $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$newRank] = $upUser[0];
    }
    break;

case 'move_entity_up':
    $upEntity = 
    array_splice(
        $_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'], 
        $rank,
        1,
        $preserve_keys = true
    );
    $newRank = $rank-1;
    $downEntity = 
        array_splice(
            $_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'], 
            $newRank,
            1,
            $preserve_keys = true
        );
    
    
    if($upEntity[0] && $downEntity[0]) {
        # Switch visible values
        $downEntityVisible = $downEntity[0]['visible'];
        $upEntityVisible = $upEntity[0]['visible'];
        $upEntity[0]['visible'] = $downEntityVisible;
        $downEntity[0]['visible'] = $upEntityVisible;
        # Switch positions
        $_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'][$rank] = $downEntity[0];
        $_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'][$newRank] = $upEntity[0];
    }
    break;   
    
// VISIBLE
//*************************************************************************************** 
case 'make_user_visible':
    $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]['visible'] = 'Y'; 
    break;
    
case 'make_user_unvisible':
    $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$rank]['visible'] = 'N'; 
    break;    
    
case 'make_entity_visible':
    $_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'][$rank]['visible'] = 'Y'; 
    break;
    
case 'make_entity_unvisible':
    $_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'][$rank]['visible'] = 'N'; 
    break;  
# END SWITCH ACTION
}

// 1.4 create indexed array of existing diffusion to search for users/entities easily
$user_roles = array();
$entity_roles = array();
foreach($roles as $role_id => $role_label) {
    for($i=0, $l=count($_SESSION['m_admin']['entity']['listmodel'][$role_id]['users']); 
        $i<$l; $i++
    ) {
        $user_id = $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$i]['user_id'];
        $user_roles[$user_id][] = $role_id;
    }
    for($i=0, $l=count($_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities']); 
        $i<$l; 
        $i++
    ) {
        $entity_id = $_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'][$i]['entity_id'];
        $entity_roles[$entity_id][] = $role_id;
    }
}

$core_tools->load_html();
$core_tools->load_header(_LISTMODEL);
$time = $core_tools->get_session_time_expire();
$link = $_SESSION['config']['businessappurl']."index.php?display=true&module=entities&page=manage_listmodel";
if ($onlyCc) $link .= '&only_cc';
if ($noDelete) $link .= '&no_delete';

$linkwithwhat =  
    $link 
    . '&what_users=' . $whatUsers 
    . '&what_services=' . $whatServices;
#******************************************************************************
# DISPLAY EXISTING LIST
#******************************************************************************
?>
<body onload="setTimeout(window.close, <?php echo $time;?>*60*1000);">
    <script type="text/javascript">
        function add_user(id) {
            var user_id = $('user_id_' + id).value;
            var role_select = $('user_role_' + id);
			var role = role_select.options[role_select.selectedIndex].value;
            goTo('<?php echo($linkwithwhat);?>&action=add_user&id='+user_id+'&role='+role);
        }
        function add_entity(id) {
            var entity_id = $('entity_id_' + id).value;
            var role_select = $('entity_role_' + id);
			var role = role_select.options[role_select.selectedIndex].value;
            goTo('<?php echo($linkwithwhat);?>&action=add_entity&id='+entity_id+'&role='+role);
        }
    </script>
    <div id="diff_list" class="block" align="center">
        <h2><?php 
            echo _DIFFUSION_LIST . ' - ';
            functions::xecho($description);
        ?></h2><?php 
        #**************************************************************************
        # DEST USER
        #**************************************************************************
        if(1==2 && isset($_SESSION['m_admin']['entity']['listmodel']['dest']['user_id']) 
            && !empty($_SESSION['m_admin']['entity']['listmodel']['dest']['user_id'])
        ) { ?>
        <h3 class="sstit"><?php echo _PRINCIPAL_RECIPIENT;?></h3>
        <table cellpadding="0" cellspacing="0" border="0" class="listing spec">
            <tr>
                <td>
                    <i class="fa fa-user fa-2x" title="<?php echo _USER;?>"></i>
                </td>
                <td><?php
                if($_SESSION['m_admin']['entity']['listmodel']['dest']['visible'] == 'Y') { ?>
                    <i class="fa fa-check fa-2x" title="<?php echo _VISIBLE;?>"></i> <?php
                } ?>
                </td>
                <td><?php functions::xecho($_SESSION['m_admin']['entity']['listmodel']['dest']['lastname'] ). " " . $_SESSION['m_admin']['entity']['listmodel']['dest']['firstname'];?></td>
                <td><?php functions::xecho($_SESSION['m_admin']['entity']['listmodel']['dest']['entity_label']);?></td>
                <td class="action_entities" style="width:5%;"><!-- Remove dest -->
                    <a href="<?php echo($linkwithwhat);?>&action=remove_dest"><i class="fa fa-remove fa-lg"></i></a>
                </td>
                <td class="action_entities" style="width:15%;"><!-- Move dest to copy -->
                    <a href="<?php echo($linkwithwhat);?>&action=dest_to_copy&role=copy" ><i class="fa fa-arrow-down fa-2x"></i><?php echo _TO_CC;?></a>
                </td>
            </tr>
        </table><?php 
        } ?>
        <br/> <?php 
        #**************************************************************************
        # OTHER ROLES
        #**************************************************************************
        foreach($roles as $role_id => $role_label) {
            if (count($_SESSION['m_admin']['entity']['listmodel'][$role_id]['users']) > 0
             || count($_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities']) > 0
            ) { ?>
                <h3 class="sstit"  style="font-size:1.5em; text-align:left; margin-left:100px; margin-bottom: -10px"><?php functions::xecho($role_label);?></h3>
                <table cellpadding="0" cellspacing="0" border="0" class="listing liste_diff spec"><?php
                #**************************************************************************
                # OTHER ROLE USERS
                #**************************************************************************
                $color = ' class="col"';
                for($i=0, $l=count($_SESSION['m_admin']['entity']['listmodel'][$role_id]['users']); 
                    $i<$l; 
                    $i++
                ) {
                    $user = $_SESSION['m_admin']['entity']['listmodel'][$role_id]['users'][$i];
                    
                    if ($color == ' class="col"') $color = '';
                    else $color = ' class="col"';?>
                    <tr <?php echo $color;?> >
                        <td style="width:5%;">
                            <i class="fa fa-user fa-2x" title="<?php echo _USER.' '.$role_label;?>"></i>
                        </td>
                        <td style="width:5%;"><?php
                        if($user['visible'] == 'Y') { ?>
                            <a href="<?php echo($linkwithwhat);?>&action=make_user_unvisible&role=<?php functions::xecho($role_id); ?>&rank=<?php functions::xecho($i);?>">
                                <i class="fa fa-check fa-2x" title="<?php echo _VISIBLE;?>"></i>
                            </a><?php
                        } else {?>
                            <a href="<?php echo($linkwithwhat);?>&action=make_user_visible&role=<?php functions::xecho($role_id); ?>&rank=<?php functions::xecho($i);?>">
                                <i class="fa fa-times fa-2x" title="<?php echo _NOT_VISIBLE;?>"></i>
                            </a><?php
                        } ?>
                        </td>
                        <td ><?php functions::xecho($user['lastname'] . " " . $user['firstname']);?></td>
                        <td><?php functions::xecho($user['entity_label']);?></td>
                        <td class="action_entities" style="width:5%;"><!-- Remove user -->
                            <a href="<?php functions::xecho($linkwithwhat);?>&action=remove_user&role=<?php functions::xecho($role_id); ?>&rank=<?php functions::xecho($i);?>&id=<?php functions::xecho($user['user_id']);?>"><i class="fa fa-remove fa-lg" title="<?php echo _DEL_USER_LISTDIFF ;?>"></i></a>
                        </td>
                        <td class="action_entities" style="width:15%;"><!-- Switch copy to dest --><?php
                        if($role_id == 'dest'  && isset($roles['copy']) ) { ?>
                            <a href="<?php echo($linkwithwhat);?>&action=dest_to_copy&role=copy" ><i class="fa fa-arrow-down"></i><?php echo _TO_CC;?></a><?php
                        } elseif($role_id == 'sign'  && isset($roles['visa']) ) { ?>
                            <a href="<?php echo($linkwithwhat);?>&action=sign_to_visa&role=visa" ><i class="fa fa-arrow-up"></i><?php echo _VISA_USER;?></a><?php
                        } elseif($role_id == 'copy' && isset($roles['dest'])) { ?>
                            <a href="<?php echo($linkwithwhat);?>&action=copy_to_dest&role=copy&rank=<?php functions::xecho($i);?>" ><i class="fa fa-arrow-up"></i><?php echo _TO_DEST;?></a><?php
                        } elseif($role_id == 'visa' && isset($roles['sign'])) { ?>
                            <a href="<?php echo($linkwithwhat);?>&action=visa_to_sign&role=visa&rank=<?php functions::xecho($i);?>" ><i class="fa fa-arrow-down"></i><?php echo _TO_SIGN;?></a><?php
                        } else echo '&nbsp;'?>
                        </td>
                        <td class="action_entities" style="width:5%;"><!-- Move up in list --><?php 
                        if($i > 0) { ?>
                            <a href="<?php echo($linkwithwhat);?>&action=move_user_up&role=<?php functions::xecho($role_id); ?>&rank=<?php functions::xecho($i);?>" ><i class="fa fa-arrow-up"></i></a><?php
                        } ?>
                        </td>
                        <td class="action_entities" style="width:5%;"><!-- Move down in list --><?php 
                        if($i < $l-1) { ?>
                            <a href="<?php echo($linkwithwhat);?>&action=move_user_down&role=<?php functions::xecho($role_id); ?>&rank=<?php functions::xecho($i);?>" ><i class="fa fa-arrow-down"></i></a><?php
                        } ?>
                        </td>
                    </tr> <?php
                }
                #**************************************************************************
                # OTHER ROLE ENTITIES
                #**************************************************************************
                for($i=0, $l=count($_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities']); 
                    $i<$l;
                    $i++
                ) {
                    $entity = $_SESSION['m_admin']['entity']['listmodel'][$role_id]['entities'][$i];
                    if ($color == ' class="col"') $color = '';
                    else $color = ' class="col"';?>
                    <tr <?php echo $color;?> >
                        <td style="width:5%;">
                            <i class="fa fa-sitemap fa-2x" title="<?php echo _ENTITY.' '.$role_label;?>"></i> 
                        </td>
                        <td style="width:5%;"><?php
                        if($entity['visible'] == 'Y') { ?>
                            <a href="<?php echo($linkwithwhat);?>&action=make_entity_unvisible&role=<?php functions::xecho($role_id); ?>&rank=<?php functions::xecho($i);?>">
                                <i class="fa fa-check fa-2x" title="<?php echo _VISIBLE;?>"></i>
                            </a><?php
                        } else {?>
                            <a href="<?php echo($linkwithwhat);?>&action=make_entity_visible&role=<?php functions::xecho($role_id); ?>&rank=<?php functions::xecho($i);?>">
                                <i class="fa fa-times fa-2x" title="<?php echo _NOT_VISIBLE;?>"></i>
                            </a><?php
                        } ?>
                        </td>
                        <td ><?php functions::xecho($entity['entity_id']);?></td>
                        <td ><?php functions::xecho($entity['entity_label']);?></td>
                        <td class="action_entities">
                            <a href="<?php echo($linkwithwhat);?>&action=remove_entity&role=<?php functions::xecho($role_id); ?>&rank=<?php functions::xecho($i);?>&id=<?php functions::xecho($entity['entity_id']);?>" >
                                <i class="fa fa-remove fa-lg" title="<?php echo _DEL_ENTITY_LISTDIFF;?>"></i>
                            </a>
                        </td>
                        <td class="action_entities">&nbsp;</td>
                        <td class="action_entities"><!-- Move up in list --><?php 
                        if($i > 0) { ?>
                            <a href="<?php echo($linkwithwhat);?>&action=move_entity_up&role=<?php functions::xecho($role_id); ?>&rank=<?php functions::xecho($i);?>" ><i class="fa fa-arrow-up"></i></a><?php
                        } ?>
                        </td>
                        <td class="action_entities"><!-- Move down in list --><?php 
                        if($i < $l-1) { ?>
                            <a href="<?php echo($linkwithwhat);?>&action=move_entity_down&role=<?php functions::xecho($role_id); ?>&rank=<?php functions::xecho($i);?>" ><i class="fa fa-arrow-down"></i></a><?php
                        } ?>
                        </td>
                    </tr><?php
                } ?>
                </table>
                <br/><?php
            }
        }
        #******************************************************************************
        # ACTIONS BUTTONS
        #******************************************************************************?>      
        <form name="pop_diff" method="post" >
            <div align="center">
                <input align="middle" type="submit" value="<?php echo _VALIDATE;?>" class="button" name="valid"  />
                <input align="middle" type="button" value="<?php echo _CANCEL;?>"  onclick="self.close();" class="button"/>
            </div>
        </form>
        <br/>
        <br/><?php
		
        #******************************************************************************
        # LIST OF AVAILABLE ENTITIES / USERS
        #******************************************************************************?>
        <hr align="center" color="#6633CC" size="5" width="60%">
        <div align="center">
            <form action="#" name="search_diff_list" >
                <input type="hidden" name="display" value="true" />
                <input type="hidden" name="module" value="entities" />
                <input type="hidden" name="page" value="manage_listmodel" />
                <input type="hidden" name="origin" id="origin" value="<?php functions::xecho($origin);?>" />
                <table cellpadding="2" cellspacing="2" border="0">
                    <tr>
                        <th>
                            <label for="what_users" class="bold"><?php echo _USER;?></label>
                        </th>
                        <th>
                            <input name="what_users" id="what_users" type="text" <?php if(isset($_GET["what_users"])) echo "value ='".functions::xssafe($_GET["what_users"])."'";?> />
                        </th>
                    </tr>
                    <tr>
                        <th>
                            <label for="what_services" class="bold"><?php echo _DEPARTMENT;?></label>
                        </th>
                        <th>
                            <input name="what_services" id="what_services" type="text" <?php if(isset($_GET["what_services"])) echo "value ='".functions::xssafe($_GET["what_services"])."'";?>/>
                        </th>
                    </tr>
                    <tr>
						<th>
							<label for="no_filter" class="bold">&nbsp;</label>
						</th>
						<th>
							<input class="button" name="no_filter" id="no_filter" type="button" onclick="$('what_services').value='';$('what_users').value='';" value="<?php echo _NO_FILTER;?>"/>
						</th>
					</tr>
                </table>
            </form>     
        </div> 
        <script type="text/javascript">
            repost('<?php echo($link);?>',new Array('diff_list_items'),new Array('what_users','what_services'),'keyup',250);
            repost('<?php echo($link);?>',new Array('diff_list_items'),new Array('no_filter'), 'click',250);
        </script>
        <br/>
        <div id="diff_list_items"> <?php
        #******************************************************************************
        # LIST OF AVAILABLE USERS
        #******************************************************************************
        if (count($users) > 0) {
        ?> 
            <div align="center">
                <h2 class="tit"><?php echo _USERS_LIST;?></h2>
                <table cellpadding="0" cellspacing="0" border="0" class="listing spec">
                    <thead>
                        <tr>
                            <th ><?php echo _LASTNAME . " " . _FIRSTNAME;?></th>
                            <th><?php echo _DEPARTMENT;?></th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead><?php 
                    $color = ' class="col"';
                                            //print_r($roles);
                    foreach ($available_roles as $id => $label) {
                        $available_roles_ids[]=$id;
                    }
                    $usersListDiff = array();
                    foreach ($user_roles as $key => $value) {
                        $usersListDiff[]=$key;
                    }

                    for($j=0, $m=count($users);
                        $j<$m; 
                        $j++
                    ) {
                        $user_id = $users[$j]['ID'];
                        $possible_roles = array();

                        if(!in_array($user_id, $usersListDiff)){
                            foreach($roles as $role_id => $role_label) {
                                if(in_array($role_id, $available_roles_ids) || $usergroups_controler->inGroup($users[$j]['ID'], $role_id))
                                    $possible_roles[$role_id] = $role_label;
                          }
                        

                        }
                        
                        if ($color == ' class="col"') $color = '';
                        else $color = ' class="col"';?>
                        <tr <?php echo $color ;?> id="user_<?php functions::xecho($j);?>">
                        <td style="width:30%;"><?php functions::xecho($users[$j]['NOM'] . " ". $users[$j]['PRENOM']);?></td>
                        <td style="width:50%;"><?php functions::xecho($users[$j]['DEP']);?></td>
                        <td class="action_entities" style="width:20%;text-align:center;"><?php
                        if(count($possible_roles) > 0) { ?>
                            <input type="hidden" id="user_id_<?php functions::xecho($j);?>" value="<?php functions::xecho($users[$j]['ID']);?>" />
                            <select name="role" id="user_role_<?php functions::xecho($j);?>" style="width:60%;"><?php
                            foreach($possible_roles as $role_id => $role_label) { ?>
                                <option value="<?php functions::xecho($role_id);?>"><?php functions::xecho($role_label);?></option><?php 
                            } ?>
                            </select>&nbsp;
                            <span onclick="add_user(<?php functions::xecho($j);?>);" style="cursor: pointer"/> 
                                <i class="fa fa-user-plus fa-lg" title="<?php echo _ADD_USER_LISTDIFF ;?>"></i>
                            </span><?php
                        } else echo _NO_AVAILABLE_ROLE;?>
                        </td>
                    </tr><?php 
                    } ?>
                </table>
                <br/>
            </div> <?php
            }
            #******************************************************************************
            # LIST OF AVAILABLE ENTITIES
            #****************************************************************************** 
            if (count($entities) > 0) {
                if($allow_entities) { ?>
                <div align="center"> 
                    <h2 class="tit"><?php echo _ENTITIES_LIST;?></h2>
                    <table cellpadding="0" cellspacing="0" border="0" class="listing spec">
                        <thead>
                            <tr>
                                <th><?php echo _ID;?></th>
                                <th><?php echo _DEPARTMENT;?></th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead><?php 
                    $color = ' class="col"';

                    $entityListDiff = array();
                    foreach ($entity_roles as $key => $value) {
                        $entityListDiff[]=$key;
                    }
                    for ($j=0, $m=count($entities); $j<$m ; $j++) {
                        $entity_id = $entities[$j]['ID'];
                        
                        # Check if at least one role can be added
                        $possible_roles = array();
                        if(!in_array($entity_id, $entityListDiff)){
                            foreach($roles as $role_id => $role_label) {
                                if($role_id == 'copy')
                                    $possible_roles[$role_id] = $role_label;
                            } 
                        }
                        
                        if($color == ' class="col"') $color = '';
                        else $color = ' class="col"';?>
                        <tr <?php echo $color;?>>
                            <td style="width:30%;"><?php functions::xecho($entities[$j]['ID']);?></td>
                            <td style="width:50%;"><?php functions::xecho($entities[$j]['DEP']);?></td>
                            <td class="action_entities" style="width:20%;text-align:center;"><?php
                            if(count($possible_roles) > 0) { ?>
                                <input type="hidden" id="entity_id_<?php functions::xecho($j);?>" value="<?php functions::xecho($entities[$j]['ID']);?>" />
                                <select name="role" id="entity_role_<?php functions::xecho($j);?>" style="width:60%;"><?php 
                                foreach($possible_roles as $role_id => $role_label) { ?>
                                    <option value="<?php functions::xecho($role_id);?>"><?php functions::xecho($role_label);?></option><?php 
                                } ?>
                                </select>&nbsp;
                                <span onclick="add_entity(<?php functions::xecho($j);?>);" style="cursor: pointer"/> 
                                    <i class="fa fa-plus fa-lg" title="<?php echo _ADD_ENTITY_LISTDIFF;?>"></i>
                                </span><?php
                            } else echo _NO_AVAILABLE_ROLE;?>               
                            </td>
                        </tr> <?php
                    } ?>
                    </table>
                </div><?php
                }
            }
            ?>
        </div>  
    </div>
</body>
</html>
