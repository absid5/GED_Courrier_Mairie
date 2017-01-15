<?php

require_once("modules".DIRECTORY_SEPARATOR."folder".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_admin_foldertypes.php");

$core_tools = new core_tools();

$core_tools->load_lang();
$core_tools->test_admin('admin_foldertypes', 'folder');
$ft = new foldertype();

$ft->addupfoldertype("up");
?>
