<?php
/*
difflist_type_id : $('difflist_type_id').value,
difflist_type_label : $('difflist_type_label').value,
*/

require_once("modules/entities/class/class_manage_listdiff.php");
$difflist = new diffusion_list();


require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("modules/entities/entities_tables.php");

$db = new Database();

$difflist_type_id    = functions::protect_string_db($_REQUEST['difflist_type_id']); 
$difflist_type_label = functions::protect_string_db($_REQUEST['difflist_type_label']);
$difflist_type_roles = functions::protect_string_db($_REQUEST['difflist_type_roles']);
$allow_entities      = functions::protect_string_db($_REQUEST['allow_entities']);

# Controls
$errors = false;
if(!empty($difflist_type_id)) {
    $stmt = $db->query(
        "select count(1) as nb from " . ENT_DIFFLIST_TYPES
            . " where difflist_type_id = ?",array($difflist_type_id));
    $res = $stmt->fetchObject();
    $nb = $res->nb;
    if($_REQUEST['mode'] == 'add' && $nb == 1)
        $errors .= "<br/>" . _DIFFLIST_TYPE_ID_ALREADY_USED;
    if($_REQUEST['mode'] == 'up' && $nb == 0)
        $errors .= "<br/>" . _DIFFLIST_TYPE_ID_IS_UNKNOWN;
} else {
        $errors .= "<br/>" . _DIFFLIST_TYPE_ID_IS_MANDATORY;
}


if(empty($difflist_type_label))
    $errors .= "<br/>" . _DIFFLIST_TYPE_LABEL_IS_MANDATORY;


# If errors detected, return html
if($errors) {
    echo $errors;
    return;
}

# If no error, proceed
switch($_REQUEST['mode']) {
case 'add':
    $difflist->insert_difflist_type(
        $difflist_type_id,
        $difflist_type_label,
        $difflist_type_roles,
        $allow_entities     
    );
    
    break;
case 'up':
    $difflist->update_difflist_type(
        $difflist_type_id,
        $difflist_type_label,
        $difflist_type_roles,
        $allow_entities
    );
    break;

}