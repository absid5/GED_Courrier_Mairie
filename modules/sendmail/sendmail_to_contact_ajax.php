<?php
require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_request.php');
// if($_POST['status'] != '_NOSTATUS_'){
// $db = new Database();

// $db->query("UPDATE res_letterbox SET status = ? WHERE res_id = ?", array($_POST['status'],$_REQUEST['identifier']));

// }

$db = new Database();

$db->query("UPDATE mlb_coll_ext SET sve_start_date = CURRENT_TIMESTAMP WHERE res_id = ?", array($_REQUEST['identifier']));

// header('Location: ' . $_SESSION['config']['businessappurl']
//                     . 'index.php?page=view_baskets&module=basket'
//                 );
?>


