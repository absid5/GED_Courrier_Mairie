<?php
/*
*    Copyright 2008,2009 Maarch
*
*  This file is part of Maarch Framework.
*
*   Maarch Framework is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Maarch Framework is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*    along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief  Add contact form
*
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

$core_tools2 = new core_tools();
$core_tools2->load_lang();
$return = $core_tools2->test_admin('admin_contacts', 'apps', false);
if (!$return) {
    $return = $core_tools2->test_admin('create_contacts', 'apps', false);
}

if (!$return) {
    $return = $core_tools2->test_admin('my_contacts', 'apps', false);
}
if (!$return) {
    $return = $core_tools2->test_admin('my_contacts_menu', 'apps', false);
}

if (!$return) {
    $_SESSION['error'] = _SERVICE . ' ' . _UNKNOWN;
    ?>
    <script type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php';</script>
    <?php
    exit();
}

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
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=contact_addresses_add';
$page_label = _ADDITION_ADDRESS;
$page_id = "contact_addresses_add";
$core_tools2->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/

require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_contacts_v2.php");

$contact = new contacts_v2();

if(isset($_GET['mycontact']) && $_GET['mycontact'] <> ''){
	$admin = false;
} else {
	$admin = true;
}

$contact->formaddress("add", "", $admin);
?>
