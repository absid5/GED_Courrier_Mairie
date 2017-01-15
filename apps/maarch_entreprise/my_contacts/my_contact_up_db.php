<?php
/**
* File : my_contact_up_db.php
*
* Modify the contact in the database after the form
*
* @package Maarch LetterBox 2.3
* @version 2.0
* @since 10/2005
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*/

$core_tools = new core_tools();
$core_tools->load_lang();
if(!$core_tools->test_service('my_contacts', 'apps', false)){
    if(!$core_tools->test_service('update_contacts', 'apps', false)){
    	$core_tools->test_service('my_contacts_menu', 'apps');
    }
}

require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_contacts_v2.php");

$contact = new contacts_v2();

if(isset($_GET['confirm']) &&  $_GET['confirm'] <> ''){
	$confirm = 'Y';
	$_POST['mode'] = $_GET['mode'];
} else {
	$confirm = 'N';
}

if(isset($_GET['mycontact']) &&  $_GET['mycontact'] <> ''){
	$mycontact = $_GET['mycontact'];
} else {
	$mycontact = 'Y';
}

$contact->addupcontact($_POST['mode'], false, $confirm, $mycontact);
?>
