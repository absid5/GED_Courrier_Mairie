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
if(!$core_tools2->test_service('my_contacts', 'apps', false)){
    if(!$core_tools2->test_service('update_contacts', 'apps', false)){
    	$core_tools2->test_service('my_contacts_menu', 'apps');
    }
}
$core_tools2->load_html();
$core_tools2->load_header('', true, false);

require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_contacts_v2.php");
require_once 'core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
    . 'class_request.php';

$request = new request();
$contact = new contacts_v2();
$db 	 = new Database();

echo '<div class="error" id="main_error">';
functions::xecho($_SESSION['error']);
echo '</div>';

echo '<div class="info" id="main_info">';
functions::xecho($_SESSION['info']);
echo '</div>';


$query = "SELECT * FROM ".$_SESSION['tablename']['contacts_v2']." WHERE contact_id = ?";

$stmt = $db->query($query, array($_SESSION['contact']['current_contact_id']));

$_SESSION['m_admin']['contact'] = array();
$line = $stmt->fetchObject();
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

$core_tools2->load_js();
if (isset($_GET['iframe']) && $_GET['iframe'] == 'fromContactIframe') {
	$contact->formaddress("add", "", false, "fromContactIframe");
} else {
	if ($_SESSION['error'] == '') {
		unset($_SESSION['m_admin']['address']);
	}
	$contact->formaddress("add", "", false, "iframe");
}

$_SESSION['error'] = '';
$_SESSION['info'] = '';

?>
	<script type="text/javascript">
		resize_frame_contact('address');
	</script>
<?php
