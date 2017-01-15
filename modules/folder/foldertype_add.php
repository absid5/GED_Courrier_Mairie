<?php

$admin = new core_tools();
$admin->test_admin('admin_foldertypes', 'folder');
 /****************Management of the location bar  ************/
$init = false;
if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true") {
    $init = true;
}
$level = "";
if (isset($_REQUEST['level']) && ($_REQUEST['level'] == 2 
	|| $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 
	|| $_REQUEST['level'] == 1)
) {
    $level = $_REQUEST['level'];
}
$pagePath = $_SESSION['config']['businessappurl'].'index.php?page=foldertype_add&module=folder';
$pageLabel = _ADDITION;
$pageId = "foldertype_add";
$admin->manage_location_bar($pagePath, $pageLabel, $pageId, $init, $level);
/***********************************************************/
require_once "modules/folder/class/class_admin_foldertypes.php";
$func = new functions();

$ft = new foldertype();

$ft->formfoldertype("add");

