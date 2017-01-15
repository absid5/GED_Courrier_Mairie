<?php
/**
* File : users_entities_up.php
*
* Users - Entities form relate
*
* @package  Maarch Framework 3.0
* @version 1
* @since 03/2009
* @license GPL
* @author  Cédric Ndoumba  <dev@maarch.org>
* @author  Claire Figueras  <dev@maarch.org>
*/

$admin = new core_tools();
$admin->test_admin('manage_entities', 'entities');

$func = new functions();
if(isset($_GET['id']))
{
    $id = addslashes($func->wash($_GET['id'], "no", _THE_USER));
}
else
{
    $id = "";
}
 /****************Management of the location bar  ************/
$init = false;
if(isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true")
{
    $init = true;
}
$level = "";
if(isset($_REQUEST['level']) && $_REQUEST['level'] == 2 || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1)
{
    $level = $_REQUEST['level'];
}
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=users_entities_up&module=entities';
$page_label = _MODIFICATION;
$page_id = "users_entities_up";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
require_once('modules'.DIRECTORY_SEPARATOR.'entities'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_users_entities.php');
$usersent = new users_entities();

$usersent->formuserentities("up", $id);
?>
