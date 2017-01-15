<?php
/*
*    Copyright 2014 Maarch
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
*
*
* @file
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

$core_tools2 = new core_tools();
$core_tools2->load_lang();
$core_tools2->test_admin('search_contacts', 'apps');
$core_tools2->load_html();
$core_tools2->load_header('', true, false);
$core_tools2->load_js();

require_once 'core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'class_request.php';
require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_contacts_v2.php");
require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_business_app_tools.php");

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
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=contact_address_view&dir=indexing_searching';
$page_label = _VIEW_ADDRESS;
$page_id = "contact_address_view";
$core_tools2->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/

$request = new request();
$db = new Database();

echo '<div class="error" id="main_error">';
functions::xecho($_SESSION['error']);
echo '</div>';

echo '<div class="info" id="main_info">';
functions::xecho($_SESSION['info']);
echo '</div>';

$_SESSION['error'] = '';
$_SESSION['info'] = '';

if (!isset($_GET['addressid']) || $_GET['addressid'] == '') {
	echo '<div class="error" id="main_error">';
	echo _YOU_MUST_SELECT_ADDRESS;
	echo '</div>';
	exit;
}

$contact = new contacts_v2();

$query = "SELECT * FROM ".$_SESSION['tablename']['contact_addresses']." WHERE id = ?";
$stmt = $db->query($query, array($_GET['addressid']));
$line = $stmt->fetchObject();

$_SESSION['m_admin']['address'] = array();
$_SESSION['m_admin']['address']['ID'] = $line->id;
$_SESSION['m_admin']['address']['CONTACT_ID'] = $line->contact_id;
$_SESSION['m_admin']['address']['TITLE'] = $request->show_string($line->title);
$_SESSION['m_admin']['address']['LASTNAME'] = $request->show_string($line->lastname);
$_SESSION['m_admin']['address']['FIRSTNAME'] = $request->show_string($line->firstname);
$_SESSION['m_admin']['address']['FUNCTION'] = $request->show_string($line->function);
$_SESSION['m_admin']['address']['OTHER_DATA'] = $request->show_string($line->other_data);
$_SESSION['m_admin']['address']['OWNER'] = $line->user_id;
$_SESSION['m_admin']['address']['DEPARTEMENT'] = $request->show_string($line->departement);
$_SESSION['m_admin']['address']['CONTACT_PURPOSE_ID'] = $line->contact_purpose_id;
$_SESSION['m_admin']['address']['OCCUPANCY'] = $request->show_string($line->occupancy);
$_SESSION['m_admin']['address']['ADD_NUM'] = $request->show_string($line->address_num);
$_SESSION['m_admin']['address']['ADD_STREET'] = $request->show_string($line->address_street);
$_SESSION['m_admin']['address']['ADD_COMP'] = $request->show_string($line->address_complement);
$_SESSION['m_admin']['address']['ADD_TOWN'] = $request->show_string($line->address_town);
$_SESSION['m_admin']['address']['ADD_CP'] = $request->show_string($line->address_postal_code);
$_SESSION['m_admin']['address']['ADD_COUNTRY'] = $request->show_string($line->address_country);
$_SESSION['m_admin']['address']['PHONE'] = $request->show_string($line->phone);
$_SESSION['m_admin']['address']['MAIL'] = $request->show_string($line->email);
$_SESSION['m_admin']['address']['WEBSITE'] = $request->show_string($line->website);
$_SESSION['m_admin']['address']['IS_PRIVATE'] = $request->show_string($line->is_private);
$_SESSION['m_admin']['address']['SALUTATION_HEADER'] = $request->show_string($line->salutation_header);
$_SESSION['m_admin']['address']['SALUTATION_FOOTER'] = $request->show_string($line->salutation_footer);

$core_tools2->load_js();
?>
	<h1><i class="fa fa-home fa-2x"></i>&nbsp;<?php echo _VIEW_ADDRESS;?></h1>
    <div id="inner_content" class="clearfix" align="center">
<?php
	$contact->get_contact_form();
	$contact->get_address_form();
?>
	<input type="button" class="button"  name="cancel" value="<?php echo _BACK;?>" onclick="history.go(-1);" />
	</div>
