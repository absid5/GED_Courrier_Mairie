<?php
# AJAX save listmodel from admin_listmodel

require_once("modules/entities/class/class_manage_listdiff.php");
$difflist = new diffusion_list();

switch($_REQUEST['mode']) {
case 'up':
case 'add':
    $difflist->save_listmodel(
        $_SESSION['m_admin']['entity']['listmodel'], 
        $objectType = $_REQUEST['objectType'],
        $objectId = $_REQUEST['objectId'],
        $title = $_REQUEST['title'],
        $description = $_REQUEST['description']
        
    );
    break;
    
case 'del':
    $difflist->delete_listmodel(
        $objectType = $_REQUEST['objectType'],
        $objectId = $_REQUEST['objectId']
    );
    break;
}