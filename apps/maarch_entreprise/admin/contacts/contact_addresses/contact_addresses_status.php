<?php
/*
*    Copyright 2015 Maarch
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
* @brief  Enabled/disable a contact address in the database
*
*
* @file
* @author <dev@maarch.org>
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
    $_SESSION['error'] = _SERVICE . ' ' . _UNKNOWN;
    ?>
    <script type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php';</script>
    <?php
    exit();
}

require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_contacts_v2.php");

$contact = new contacts_v2();

if ($_GET['mode'] == "ban") {
	$contact->addressEnabled($_GET['id'], "N");
} else if ($_GET['mode'] == "allow") {
	$contact->addressEnabled($_GET['id'], "Y");
}

?>

<script>
	window.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=contacts_v2_up&id=<?php functions::xecho($_SESSION['contact']['current_contact_id']);?>';
</script>

<?php

exit();