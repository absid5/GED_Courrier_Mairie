<?php
/**
* File : users_entities_up_db.php
*
* Modify the users_entities in the database after the form
*
* @package  Maarch Framework 3.0
* @version 1
* @since 03/2009
* @license GPL
* @author  Cédric Ndoumba  <dev@maarch.org>
*/

$core_tools = new core_tools();
$core_tools->test_admin('manage_entities', 'entities');
$core_tools->load_lang();
require_once('modules'.DIRECTORY_SEPARATOR.'entities'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_users_entities.php');

$usersEnt = new users_entities();

$usersEnt->addupusersentities("up");
?>
