<?php


require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_request.php');


$db = new Database();

$stmt = $db->query("SELECT process_mode from mlb_doctype_ext WHERE type_id = ?", array($_POST['type_id']));
$res = $stmt->fetchObject();
$sve_type = $res->process_mode;
$_SESSION['process_mode'] = $sve_type;

foreach ($_SESSION['process_mode_priority'] as $key => $value){
    if($sve_type == $key){
		echo "{status : 0, value : ".$value."}";
		exit;
	}elseif($sve_type == $key){
		echo "{status : 0, value : ".$value."}";
		exit;
	}elseif($sve_type == $key){
		echo "{status : 1, value : ".$value."}";
		exit;
	}
}



?>