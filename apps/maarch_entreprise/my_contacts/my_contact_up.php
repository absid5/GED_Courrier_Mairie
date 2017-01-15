<?php
/**
* File : my_contact_up.php
*
* Form to modify a contact
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

require("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_contacts_v2.php");
require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_lists.php";

$func = new functions();
$list2 = new lists(); 

if(isset($_GET['id'])) {
    $id = addslashes($func->wash($_GET['id'], "alphanum", _THE_CONTACT));
    $_SESSION['contact']['current_contact_id'] = $id;
} else if ($_SESSION['contact']['current_contact_id'] <> ''){
	$id = $_SESSION['contact']['current_contact_id'];
} else {
    $id = "";
}

if (!isset($_REQUEST['letters'])) {
     /****************Management of the location bar  ************/
    $init = false;
    if(isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true")
    {
        $init = true;
    }
    $level = "";
    if(isset($_REQUEST['level']) && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1))
    {
        $level = $_REQUEST['level'];
    }
    $page_path = $_SESSION['config']['businessappurl'].'index.php?page=my_contact_up&dir=my_contacts';
    $page_label = _MODIFICATION;
    $page_id = "my_contact_up";
    $core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
    /***********************************************************/
}

?><div id="divList" class="block" name="divList"><?php

$contact = new contacts_v2();
if ($from_iframe) {
	$contact->formcontact("up",$id, false, true);
} else {
	$contact->formcontact("up",$id, false);
}

$_SESSION['m_admin']['address'] = array();

if ($_SESSION['origin']=='contacts_list') {
    $_REQUEST['start']='';
    $_SESSION['origin']='contact_up';
}

include_once 'apps/' . $_SESSION['config']['app_id'] . '/admin/contacts/contact_addresses/contact_addresses.php';

?> </div>