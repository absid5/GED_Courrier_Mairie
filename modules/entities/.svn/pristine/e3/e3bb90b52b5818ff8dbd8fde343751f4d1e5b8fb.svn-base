<?php

require_once('core/class/class_core_tools.php');
$Core_Tools = new core_tools;
$Core_Tools->load_lang();

$status = 0;

require_once('modules/entities/class/class_manage_listdiff.php');
$diffListObj = new diffusion_list();
$difflist = $diffListObj->get_listinstance($_REQUEST['res_id'], false, $_SESSION['collection_id_choice']);

# Include display of list
$roles = $diffListObj->list_difflist_roles();

ob_start();
require_once 'modules/entities/difflist_display.php';
$return .= str_replace(array("\r", "\n", "\t"), array("", "", ""), ob_get_contents());
ob_end_clean();

echo "{status : " . $status . ", toShow : '" . addslashes($return) . "'}";
exit ();

