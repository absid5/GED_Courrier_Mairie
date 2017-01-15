<?php
/**
* File : contacts_view.php
*
* Form to modify a contact
*
* @license GPL
* @author   <dev@maarch.org>
*/

$core_tools = new core_tools();
$core_tools->load_lang();
$core_tools->test_service('search_contacts', 'apps');

require("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_contacts_v2.php");
require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_lists.php";
require_once 'core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'class_request.php';

$func = new functions();
$list2 = new lists(); 
$db = new Database();

if(isset($_GET['id']))
{
    $id = addslashes($func->wash($_GET['id'], "alphanum", _THE_CONTACT));
    $_SESSION['contact']['current_contact_id'] = $id;
}else if ($_SESSION['contact']['current_contact_id'] <> ''){
	$id = $_SESSION['contact']['current_contact_id'];
}
else
{
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
	$page_path = $_SESSION['config']['businessappurl'].'index.php?page=contacts_view&dir=indexing_searching';
	$page_label = _VIEW_CONTACT;
	$page_id = "contacts_view";
	$core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
	/***********************************************************/
}

?><div id="divList" name="divList"><?php

$contact = new contacts_v2();
$request = new request();

$query = "SELECT * FROM ".$_SESSION['tablename']['contacts_v2']." WHERE contact_id = ?";
$stmt = $db->query($query, array($_SESSION['contact']['current_contact_id']));
$line = $stmt->fetchObject();

$_SESSION['m_admin']['contact'] = array();
$_SESSION['m_admin']['contact']['ID'] = $line->contact_id;
$_SESSION['m_admin']['contact']['TITLE'] = $request->show_string($line->title);
$_SESSION['m_admin']['contact']['LASTNAME'] = $request->show_string($line->lastname);
$_SESSION['m_admin']['contact']['FIRSTNAME'] = $request->show_string($line->firstname);
$_SESSION['m_admin']['contact']['SOCIETY'] = $request->show_string($line->society);
$_SESSION['m_admin']['contact']['SOCIETY_SHORT'] = $request->show_string($line->society_short);
$_SESSION['m_admin']['contact']['FUNCTION'] = $request->show_string($line->function);
$_SESSION['m_admin']['contact']['OTHER_DATA'] = $request->show_string($line->other_data);
$_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] = $request->show_string($line->is_corporate_person);
$_SESSION['m_admin']['contact']['CONTACT_TYPE'] = $line->contact_type;
$_SESSION['m_admin']['contact']['OWNER'] = $line->user_id;
?>
	<h1><i class="fa fa-users fa-2x"></i>&nbsp;<?php echo _VIEW_CONTACT;?></h1>
	<div id="test" class="clearfix" align="center">
<?php
		$contact->get_contact_form();
?>
		<input type="button" class="button"  name="cancel" value="<?php echo _BACK_TO_RESULTS_LIST;?>" onclick="history.go(-1);" />
		<br/><br/><br/><br/>
	</div>
<?php

$mode = 'view';
include_once 'apps/' . $_SESSION['config']['app_id'] . '/admin/contacts/contact_addresses/contact_addresses.php';
?> </div>