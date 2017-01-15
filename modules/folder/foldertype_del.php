<?php

require_once("modules".DIRECTORY_SEPARATOR."folder".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_admin_foldertypes.php");

$func = new functions();
$core_tools = new core_tools();

$core_tools->load_lang();
$core_tools->test_admin('admin_foldertypes', 'folder');
if(isset($_GET['id']))
{
	$s_id = addslashes($func->wash($_GET['id'], "alphanum", _THE_ID));
}
else
{
	$s_id = "";
}

$ft = new foldertype();
$ft->adminfoldertype($s_id,"del");
?>
