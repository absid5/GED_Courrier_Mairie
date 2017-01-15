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
$core_tools->test_admin('admin_contacts', 'apps');
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
$pagePath = $_SESSION['config']['businessappurl'] . 'index.php?page=contact_purposes_del';
$pageLabel = _CONTACT_PURPOSE_DEL;
$pageId = "contact_purposes_del";
$core_tools->manage_location_bar($pagePath, $pageLabel, $pageId, $init, $level);
/***********************************************************/

if(isset($_GET['id']))
{
	$id = addslashes(functions::wash($_GET['id'], "no", _THE_CONTACT_PURPOSE));
}
else
{
	$id = "";
}

if ($_REQUEST['valid']) {
	$id = $_POST['id'];
	if ($_POST['new']){
		$newid = $_POST['new'];

		// delete contact types
		$db->query("DELETE FROM ".$_SESSION['tablename']['contact_purposes']." WHERE id = ?", array($id));
		$db->query("UPDATE ".$_SESSION['tablename']['contact_addresses']." SET contact_purpose_id = ? WHERE contact_purpose_id = ?", array($newid, $id));

		if($_SESSION['history']['contact_purposes_del'] == "true")
		{
			require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
			$users = new history();
			$users->add($_SESSION['tablename']['contact_purposes'], $id,"DEL",'contact_purposes_del',_CONTACT_TYPE_DEL." ".strtolower(_NUM).$id."", $_SESSION['config']['databasetype']);
		}
		$_SESSION['info'] = _DELETED_CONTACT_PURPOSE.".";
		unset($_SESSION['m_admin']);
		?>
	        <script type="text/javascript">
	            window.location.href="<?php echo $_SESSION['config']['businessappurl'].'index.php?page=contact_purposes&order='.functions::xssafe($_REQUEST['order']).'&order_field='.functions::xssafe($_REQUEST['order_field']).'&start='.functions::xssafe($_REQUEST['start']).'&what='.functions::xssafe($_REQUEST['what']);?>";
	        </script>	
	    <?php
	} else {
		$_SESSION['error'] = _NEW_CONTACT_PURPOSE.' '._IS_EMPTY.". ". _USE_AUTOCOMPLETION;
		$contact->type_purpose_address_del($id, true, $_SESSION['tablename']['contact_purposes'], 'contact_purpose', _DELETED_CONTACT_PURPOSE, _WARNING_MESSAGE_DEL_CONTACT_PURPOSE, _CONTACT_PURPOSE_DEL, _CONTACT_PURPOSE_REAFFECT, _NEW_CONTACT_PURPOSE, _CHOOSE_CONTACT_PURPOSES, 'contact_purposes', 'contact_purposes_del', _CONTACT_PURPOSE);
	}
} else {
	$contact->type_purpose_address_del($id, true, $_SESSION['tablename']['contact_purposes'], 'contact_purpose', _DELETED_CONTACT_PURPOSE, _WARNING_MESSAGE_DEL_CONTACT_PURPOSE, _CONTACT_PURPOSE_DEL, _CONTACT_PURPOSE_REAFFECT, _NEW_CONTACT_PURPOSE, _CHOOSE_CONTACT_PURPOSES, 'contact_purposes', 'contact_purposes_del', _CONTACT_PURPOSE);
}