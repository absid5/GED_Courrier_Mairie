<?php
# AJAX Script to validate listmodel header values
# and load into session if needed

require_once 'core/class/class_core_tools.php';
$core = new core_tools();
$core->load_lang();

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("modules/entities/entities_tables.php");

$db = new Database();

$mode = $_REQUEST['mode'];
$objectType = $_REQUEST['objectType'];
$objectId = $_REQUEST['objectId'];
$description = $_REQUEST['description'];
$title = $_REQUEST['title'];

//print_r($description); exit;

$return = "";

if($objectId == '' || $objectType == '')
    $return .= _SELECT_OBJECT_TYPE_AND_ID . "<br/>";
    
if($title == '')
    $return .= _ENTER_TITLE . "<br/>";
    
if($mode == 'add' && $objectId && $objectType && $collId) {
    $stmt = $db->query(
        "select count(1) as nb from " . ENT_LISTMODELS
        . " where object_type = ?"
        . " and object_id = ?",array($objectType,$objectId)
    );
    $res = $stmt->fetchObject();
    if($res->nb > 0)
        $return .= _LISTMODEL_ID_ALREADY_USED . "<br/>";
}

# Load header into session
$_SESSION['m_admin']['entity']['listmodel']['object_type'] = $objectType;
$_SESSION['m_admin']['entity']['listmodel']['object_id'] = $objectId;
$_SESSION['m_admin']['entity']['listmodel']['description'] = $description;
$_SESSION['m_admin']['entity']['listmodel']['title'] = $title;
# Return messages
echo $return;