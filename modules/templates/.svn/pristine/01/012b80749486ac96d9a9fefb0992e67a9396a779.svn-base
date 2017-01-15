<?php
$db = new Database();
$core = new core_tools();
$core->load_lang();

if(empty($_SESSION['indexing_type_id']) || !isset($_SESSION['indexing_type_id']))
{
	// ERREUR
	exit();
}
 
$stmt = $db->query("select is_generated, template_id from ".$_SESSION['tablename']['temp_templates_doctype_ext']." where type_id = ? ", 
					array($_SESSION['indexing_type_id'])
		);
$is_generated = 'N';
$template = '';
if($stmt->rowCount() > 0)
{
	$res = $stmt->fetchObject();
	$is_generated = $res->is_generated;
	$template = $res->template_id;
}
if($_SESSION['origin'] == "scan")
{
	$otherArg = "&#navpanes=0";
}
else
{
	$otherArg = "";
}

array_push($_SESSION['indexing_services'], array('script' => 'modules/templates/js/change_doctype.js', 'function_to_execute' => 'doctype_template', 'arguments' => array(
array('id' => 'is_generated' , 'value' =>$is_generated),
array('id'=> 'template_id', 'value' => $template),
array('id'=> 'doc_frame', 'value' => $_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=file_iframe'.$otherArg),
array('id'=> 'model_frame', 'value' =>$_SESSION['config']['businessappurl'].'index.php?display=true&module=templates&page=file_iframe&model_id='.$template))));
?>
