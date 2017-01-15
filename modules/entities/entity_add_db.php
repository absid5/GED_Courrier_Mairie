<?php
/**
* File : entity_add_db.php
*
* Add entity in database after form
*
* @package  Maarch Framework 3.0
* @version 1
* @since 03/2009
* @license GPL
* @author  Cédric Ndoumba  <dev@maarch.org>
*/


require_once('modules'.DIRECTORY_SEPARATOR.'entities'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_manage_entities.php');

$admin = new core_tools();

$admin->load_lang();
$admin->test_admin('manage_entities', 'entities');
$ent = new entity();

$ent->addupentity("add");
?>
