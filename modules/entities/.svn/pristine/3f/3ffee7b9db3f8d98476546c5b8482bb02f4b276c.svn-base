<?php

// AJAX 
// Loads a list of listmodels onto a html structure
// >>> difflist_type : type of list (listmodels.object_type)
// >>> return type : type of list to return [select | ul]
require_once 'modules/entities/class/class_manage_listdiff.php';
$difflist = new diffusion_list();

$objectType = $_REQUEST['objectType'];
$returnElementType = $_REQUEST['returnElementType'];

$listmodels = $difflist->select_listmodels($objectType);
$l = count($listmodels);

$return = "";

switch($returnElementType) {
case 'select':
    for($i=0; $i<$l; $i++) {
        $listmodel = $listmodels[$i];
        $return .= "<option value='".functions::xecho($listmodel['object_id'])."' >"
            . functions::xecho($listmodel['description']) ."</option>";
    }
    break;
    
case 'list':
    for($i=0; $i<$l; $i++) {
        $listmodel = $listmodels[$i];
        $return .= "<li id='" . functions::xecho($listmodel['object_id']) 
            ."'>" . functions::xecho($listmodel['description']) . "</li>";
    }
    break;
}

echo $return;
