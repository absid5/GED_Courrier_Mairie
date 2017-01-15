<?php
	/*require_once('core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR 
        . 'class_request.php');*/
	require_once('core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR 
        . 'class_db_pdo.php');
		
	require_once 'modules/attachments/attachments_tables.php';
	
	$db = new Database();
	$stmt = $db->query("SELECT attachment_type from ".RES_ATTACHMENTS_TABLE." where res_id = ? and status <> 'DEL'", array($_REQUEST['res_id']));
	$infos = $stmt->fetchObject();
	$type = $infos->attachment_type;
	if ($type == 'signed_response'){
		echo "{status:1}";		
	}
	else echo "{status:0, type:'".$type."'}";		
?>