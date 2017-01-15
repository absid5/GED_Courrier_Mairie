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
* @brief Delete a structure
*
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/
require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_contacts_v2.php");
$contact = new contacts_v2();
$core_tools = new core_tools('');
$return = $core_tools->test_admin('admin_contacts', 'apps', false);
if (!$return) {
    $return = $core_tools->test_admin('create_contacts', 'apps', false);
}

if (!$return) {
    $return = $core_tools->test_admin('my_contacts', 'apps', false);
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
$core_tools->load_lang();
$db = new Database();

/****************Management of the location bar  ************/
$init = false;
if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true") {
    $init = true;
}
$level = "";
if (isset($_REQUEST['level']) && ($_REQUEST['level'] == 2
    || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4
    || $_REQUEST['level'] == 1)
) {
    $level = $_REQUEST['level'];
}
$pagePath = $_SESSION['config']['businessappurl'] . 'index.php?page=contact_addresses_del';
$pageLabel = _ADDRESS_DEL;
$pageId = "contact_addresses_del";
$core_tools->manage_location_bar($pagePath, $pageLabel, $pageId, $init, $level);
/***********************************************************/

if(isset($_GET['id'])){
	$id = addslashes(functions::wash($_GET['id'], "no", _THE_CONTACT_ADDRESS));
} else{
	$id = "";
}

if(isset($_GET['mycontact']) && $_GET['mycontact'] <> ''){
	$admin = false;
} else {
	$admin = true;
}

if ($_REQUEST['valid']) {
	$id = $_POST['id'];

	if ($_POST['new'] && $_POST['new_contact_id']){
		$newid = $_POST['new'];
		$new_contact_id = $_POST['new_contact_id'];

		// delete contact types
		$db->query("DELETE FROM ".$_SESSION['tablename']['contact_addresses']." WHERE id = ?", array($id));

		$stmt = $db->query("SELECT res_id, exp_contact_id, dest_contact_id FROM mlb_coll_ext WHERE address_id = ?", array($id));

		while($res = $stmt->fetchObject()){
			if ($res->exp_contact_id <> "") {
				$db->query("UPDATE mlb_coll_ext SET exp_contact_id = ? WHERE res_id = ?", array($new_contact_id, $res->res_id));
			} else {
				$db->query("UPDATE mlb_coll_ext SET dest_contact_id = ? WHERE res_id = ?", array($new_contact_id, $res->res_id));
			}
		}

		$db->query("UPDATE mlb_coll_ext SET address_id = ? WHERE address_id = ?", array($newid, $id));
		$db->query("UPDATE contacts_res SET contact_id = ?, address_id = ? WHERE address_id = ?", array($new_contact_id, $newid, $id));

		if($_SESSION['history']['contact_addresses_del'] == "true")
		{
			require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
			$users = new history();
			$users->add($_SESSION['tablename']['contact_addresses'], $id,"DEL",'contact_addresses_del', _ADDRESS_DEL." ".strtolower(_NUM).$id."", $_SESSION['config']['databasetype']);
		}
		$_SESSION['info'] = _DELETED_ADDRESS;
		unset($_SESSION['m_admin']);
		?>
	        <script type="text/javascript">
	            window.location.href="<?php echo $_SESSION['config']['businessappurl'].'index.php?page=contacts_v2_up&order='.$_REQUEST['order'].'&order_field='.$_REQUEST['order_field'].'&start='.$_REQUEST['start'].'&what='.$_REQUEST['what'];?>";
	        </script>	
	    <?php
	} else if (!$_POST['new_contact_id']) {
		$_SESSION['error'] = _NEW_CONTACT.' '._IS_EMPTY.". ". _USE_AUTOCOMPLETION;
		$contact->type_purpose_address_del($id, $admin, $_SESSION['tablename']['contact_addresses'], 'contact_address', _DELETED_ADDRESS, _WARNING_MESSAGE_DEL_CONTACT_ADDRESS, _ADDRESS_DEL, _CONTACT_ADDRESS_REAFFECT, _NEW_ADDRESS, _CHOOSE_CONTACT_ADDRESS, 'contacts_v2_up', 'contact_addresses_del', _CONTACT_ADDRESS);
	} else if (!$_POST['new']) {
		$_SESSION['error'] = _NEW_ADDRESS.' '._IS_EMPTY.". ". _USE_AUTOCOMPLETION;
		$contact->type_purpose_address_del($id, $admin, $_SESSION['tablename']['contact_addresses'], 'contact_address', _DELETED_ADDRESS, _WARNING_MESSAGE_DEL_CONTACT_ADDRESS, _ADDRESS_DEL, _CONTACT_ADDRESS_REAFFECT, _NEW_ADDRESS, _CHOOSE_CONTACT_ADDRESS, 'contacts_v2_up', 'contact_addresses_del', _CONTACT_ADDRESS);
	}
} else if($_REQUEST['move']) {

	$id = $_POST['id'];
	
	if ($_POST['new_contact_id_reaffect']) {
		$db->query("UPDATE contact_addresses set contact_id = ? WHERE id = ?", array($_POST['new_contact_id_reaffect'], $id));
		$db->query("UPDATE contacts_res set contact_id = ? WHERE address_id = ?", array($_POST['new_contact_id_reaffect'], $id));

		$stmt = $db->query("SELECT res_id, exp_contact_id, dest_contact_id FROM mlb_coll_ext WHERE address_id = ?", array($id));

		while($res = $stmt->fetchObject()){
			if ($res->exp_contact_id <> "") {
				$db->query("UPDATE mlb_coll_ext SET exp_contact_id = ? WHERE res_id = ?", array($_POST['new_contact_id_reaffect'], $res->res_id));
			} else {
				$db->query("UPDATE mlb_coll_ext SET dest_contact_id = ? WHERE res_id = ?", array($_POST['new_contact_id_reaffect'], $res->res_id));
			}
		}

		if($_SESSION['history']['contact_addresses_del'] == "true")
		{
			require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
			$users = new history();
			$users->add($_SESSION['tablename']['contact_addresses'], $id,"DEL",'contact_addresses_del', _ADDRESS_MOVED." ".strtolower(_NUM).$id."", $_SESSION['config']['databasetype']);
		}
		$_SESSION['info'] = _ADDRESS_MOVED.".";
		unset($_SESSION['m_admin']);
		?>
	        <script type="text/javascript">
	            window.location.href="<?php echo $_SESSION['config']['businessappurl'].'index.php?page=contacts_v2_up&order='.$_REQUEST['order'].'&order_field='.$_REQUEST['order_field'].'&start='.$_REQUEST['start'].'&what='.$_REQUEST['what'];?>";
	        </script>	
	    <?php

	} else {
		$_SESSION['error'] = _NEW_CONTACT.' '._IS_EMPTY.". ". _USE_AUTOCOMPLETION;
		$contact->type_purpose_address_del($id, $admin, $_SESSION['tablename']['contact_addresses'], 'contact_address', _DELETED_ADDRESS, _WARNING_MESSAGE_DEL_CONTACT_ADDRESS, _ADDRESS_DEL, _CONTACT_ADDRESS_REAFFECT, _NEW_ADDRESS, _CHOOSE_CONTACT_ADDRESS, 'contacts_v2_up', 'contact_addresses_del', _CONTACT_ADDRESS);		
	}


} else if($_REQUEST['delete']) {

	$id = $_POST['id'];
	$db->query("DELETE FROM contact_addresses WHERE id = ?", array($id));

	if($_SESSION['history']['contact_addresses_del'] == "true")
	{
		require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
		$users = new history();
		$users->add($_SESSION['tablename']['contact_addresses'], $id,"DEL",'contact_addresses_del', _ADDRESS_DEL." ".strtolower(_NUM).$id."", $_SESSION['config']['databasetype']);
	}
	$_SESSION['info'] = _DELETED_ADDRESS;
	unset($_SESSION['m_admin']);
	?>
        <script type="text/javascript">
            window.location.href="<?php echo $_SESSION['config']['businessappurl'].'index.php?page=contacts_v2_up&order='.$_REQUEST['order'].'&order_field='.$_REQUEST['order_field'].'&start='.$_REQUEST['start'].'&what='.$_REQUEST['what'];?>";
        </script>	
    <?php

} else {
	$contact->type_purpose_address_del($id, $admin, $_SESSION['tablename']['contact_addresses'], 'contact_address', _DELETED_ADDRESS, _WARNING_MESSAGE_DEL_CONTACT_ADDRESS, _ADDRESS_DEL, _CONTACT_ADDRESS_REAFFECT, _NEW_ADDRESS, _CHOOSE_CONTACT_ADDRESS, 'contacts_v2_up', 'contact_addresses_del', _CONTACT_ADDRESS);
}
?>
