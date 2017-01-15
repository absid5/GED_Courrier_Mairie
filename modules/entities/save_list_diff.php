<?php
/**
* File : save_list_diff.php
*
* Script called by an ajax object to store the list diff
*
* @package  maarch
* @version 1.5
* @since 10/2005
* @license GPL v3
* @author  Laurent Giovannoni  <dev@maarch.org>
*/

//var_dump($_REQUEST);exit;
require_once('core/class/class_core_tools.php');
require_once('modules/entities/class/class_manage_listdiff.php');
$core = new core_tools();
$core->load_lang();
$diff_list = new diffusion_list();
$list = new diffusion_list();
$params = array(
    'mode'=> $_REQUEST['mode'],
    'table' => $_REQUEST['table'],
    'coll_id' => $_REQUEST['collId'],
    'res_id' => $_REQUEST['resId'],
    'user_id' => $_REQUEST['userId'],
    'concat_list' => $_REQUEST['concatList'],
    'only_cc' => $_REQUEST['onlyCC']
);
$list->load_list_db(
    $_SESSION['details']['diff_list'],
    $params
);

//pb enchainement avec action redirect
$_SESSION['details']['diff_list']['key_value'] = md5($res_id);
$return = '<b>'. _DIFF_LIST_STORED . '</b>';

echo "{status : 0, div_content : '" . addslashes($return) 
    . "'}";
exit();
