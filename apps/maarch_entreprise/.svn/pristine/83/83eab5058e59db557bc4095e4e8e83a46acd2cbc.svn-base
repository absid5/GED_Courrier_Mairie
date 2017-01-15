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
$pagePath = $_SESSION['config']['businessappurl'] . 'index.php?page=contact_types_del';
$pageLabel = _CONTACT_TYPE_DEL;
$pageId = "contact_types_del";
$core_tools->manage_location_bar($pagePath, $pageLabel, $pageId, $init, $level);
/***********************************************************/

if(isset($_GET['id']))
{
	$id = addslashes(functions::wash($_GET['id'], "no", _THE_CONTACT_TYPE));
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
		$db->query("DELETE FROM ".$_SESSION['tablename']['contact_types']." WHERE id = ?", array($id));
		$db->query("UPDATE ".$_SESSION['tablename']['contacts_v2']." SET contact_type = ? WHERE contact_type = ?", array($newid, $id));

		if($_SESSION['history']['contact_types_del'] == "true")
		{
			require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
			$users = new history();
			$users->add($_SESSION['tablename']['contact_types'], $id,"DEL",'contact_types_del',_CONTACT_TYPE_DEL." ".strtolower(_NUM).$id."", $_SESSION['config']['databasetype']);
		}
		$_SESSION['info'] = _DELETED_CONTACT_TYPE.".";
		unset($_SESSION['m_admin']);
		?>
	        <script type="text/javascript">
	            window.location.href="<?php echo $_SESSION['config']['businessappurl'].'index.php?page=contact_types&order='.functions::xssafe($_REQUEST['order']).'&order_field='.functions::xssafe($_REQUEST['order_field']).'&start='.functions::xssafe($_REQUEST['start']).'&what='.functions::xssafe($_REQUEST['what']);?>";
	        </script>	
	    <?php
	} else {
		$_SESSION['error'] = _NEW_CONTACT_TYPE.' '._IS_EMPTY.".";
		$contact->type_purpose_address_del($id, true, $_SESSION['tablename']['contact_types'], 'contact_type', _DELETED_CONTACT_TYPE, _WARNING_MESSAGE_DEL_CONTACT_TYPE, _CONTACT_TYPE_DEL, _CONTACT_TYPE_REAFFECT, _NEW_CONTACT_TYPE, _CHOOSE_CONTACT_TYPES, 'contact_types', 'contact_types_del', _CONTACT_TYPE);
	}
} else {
	$contact->type_purpose_address_del($id, true, $_SESSION['tablename']['contact_types'], 'contact_type', _DELETED_CONTACT_TYPE, _WARNING_MESSAGE_DEL_CONTACT_TYPE, _CONTACT_TYPE_DEL, _CONTACT_TYPE_REAFFECT, _NEW_CONTACT_TYPE, _CHOOSE_CONTACT_TYPES, 'contact_types', 'contact_types_del', _CONTACT_TYPE);
}
