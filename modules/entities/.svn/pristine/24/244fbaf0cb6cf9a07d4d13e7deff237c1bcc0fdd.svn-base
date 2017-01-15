<?php
/**
* File : entity_ban.php
*
* To suspend an entity
*
* @package  Maarch Framework 3.0
* @version 1
* @since 03/2009
* @license GPL
* @author  Cédric Ndoumba  <dev@maarch.org>
*/

$core_tools = new core_tools();
$core_tools->load_lang();
$admin = new core_tools();
$admin->test_admin('manage_entities', 'entities');

$path = 'modules'.DIRECTORY_SEPARATOR.'entities'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_manage_entities.php';

require($path);

//$func = new functions();
$db = new Database();

if(isset($_GET['id']))
{
	$s_id = addslashes(functions::wash($_GET['id'], "alphanum", _ENTITY));
}
else
{
	$s_id = "";
}

$ent = new entity();
$ent->adminentity($s_id,'allow');
?>
