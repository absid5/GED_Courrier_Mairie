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
* @brief  Modify the contact in the database after the form
*
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

$core_tools = new core_tools();
$core_tools->load_lang();
$return = $core_tools->test_admin('admin_contacts', 'apps', false);
if (!$return) {
    $return = $core_tools->test_admin('create_contacts', 'apps', false);
}

if (!$return) {
    $return = $core_tools->test_admin('my_contacts', 'apps', false);
}

if (!$return) {
    $return = $core_tools->test_admin('update_contacts', 'apps', false);
}

if (!$return) {
    $return = $core_tools->test_admin('my_contacts_menu', 'apps', false);
}

if (!$return) {
    $_SESSION['error'] = _SERVICE . ' ' . _UNKNOWN;
    ?>
    <script type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php';</script>
    <?php
    exit();
}

require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_contacts_v2.php");

$contact = new contacts_v2();
$iframe = false;

if(isset($_GET['mycontact']) && $_GET['mycontact'] <> ''){
	$admin = false;
	if($_GET['mycontact'] == 'iframe'){
		$iframe = 1;
	} else if ($_GET['mycontact'] == 'iframe_add_up') {
		$iframe = 2;
	} else if ($_GET['mycontact'] == 'fromContactIframe') {
        $iframe = 3;
    }

} else {
	$admin = true;
}

if (isset($_REQUEST['fromContactAddressesList'])) {
    $_SESSION['contact_address']['fromContactAddressesList'] = "yes";
}

$contact->addupaddress($_POST['mode'], $admin, $iframe);
?>
