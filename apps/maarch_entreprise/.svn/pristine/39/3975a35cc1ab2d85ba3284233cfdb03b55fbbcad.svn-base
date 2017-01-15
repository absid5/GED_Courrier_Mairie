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
* @brief Modify a structure
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
    $return = $core_tools->test_admin('search_contacts', 'apps', false);
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
require_once "core" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "class_request.php";

$func = new functions();

if(isset($_GET['id']))
{
    $id = addslashes($func->wash($_GET['id'], "alphanum", _CONTACT));
}
else
{
    $id = "";
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
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=contact_addresses_up';
$page_label = _MODIFICATION;
$page_id = "contact_addresses_up";
$core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/

if(isset($_GET['mycontact']) && $_GET['mycontact'] <> ''){
	$admin = false;
} else {
	$admin = true;
}

$contact = new contacts_v2();
$request = new request;
$db = new Database();

if (isset($_REQUEST['fromContactAddressesList']) || isset($_REQUEST['fromSearchContacts'])) {
    $query = "SELECT contact_id FROM contact_addresses WHERE id = ?";
    $stmt = $db->query($query, array($id));
    $result = $stmt->fetchObject();
    $stmt = $db->query("SELECT * FROM contacts_v2 WHERE contact_id = ?", array($result->contact_id));

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
    if($admin && !empty($_SESSION['m_admin']['contact']['OWNER']))
    {
        $stmt = $db->query("SELECT lastname, firstname FROM ".$_SESSION['tablename']['users']." WHERE user_id = ?", array($_SESSION['m_admin']['contact']['OWNER']));
        $res = $stmt->fetchObject();
        $_SESSION['m_admin']['contact']['OWNER'] = $res->lastname.', '.$res->firstname.' ('.$_SESSION['m_admin']['contact']['OWNER'].')';
    }
    $_SESSION['contact_address']['fromContactAddressesList'] = "yes";
    
}

if (isset($_REQUEST['fromSearchContacts'])) {
    ?>
    <script>
        window.top.location='<?php echo $_SESSION['config']['businessappurl'] . "index.php?dir=indexing_searching&page=contact_address_view&addressid=".$id;?>';
    </script>
    <?php
    exit;
} else {
    echo '<div class="block" style="margin-top:15px;">';
    functions::xssafe($contact->formaddress("up",$id, $admin));
    echo '</div>';
}
