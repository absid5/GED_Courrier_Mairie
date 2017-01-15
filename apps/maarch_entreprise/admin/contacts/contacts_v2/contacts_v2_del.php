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
* @brief  Delete contact
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
$core_tools->test_admin('admin_contacts', 'apps');
require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_contacts_v2.php");

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

$page_path = $_SESSION['config']['businessappurl'].'index.php?page=contacts_v2_del';
$page_label = _DELETION;
$page_id = "contacts_v2_del";
$core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
$func = new functions();
$db = new Database();
$contact = new contacts_v2();

if(isset($_GET['id']))
{
    $s_id = addslashes($func->wash($_GET['id'], "alphanum", _CONTACT));
}
else
{
    $s_id = "";
}

if(isset($_REQUEST['valid']))
{
        
    if(!empty($_REQUEST['contact']) && !empty($_REQUEST['address']))
    {
        $new_contact = $_REQUEST['contact'];
        $new_address = $_REQUEST['address'];

        $i=0;
        $db->query("UPDATE ".$_SESSION['collections'][$i]['extensions'][$i] 
            . " SET exp_contact_id = ?, address_id = ? WHERE exp_contact_id = ?", array($new_contact, $new_address, $s_id));
        $db->query("UPDATE ".$_SESSION['collections'][$i]['extensions'][$i] 
            . " SET dest_contact_id = ?, address_id = ? WHERE dest_contact_id = ?", array($new_contact, $new_address, $s_id));
        $db->query("UPDATE contacts_res SET contact_id = ?, address_id = ? WHERE contact_id = ?", array($new_contact, $new_address, $s_id));
        $db->query("DELETE FROM " . $_SESSION['tablename']['contacts_v2']
            . " WHERE contact_id = ?", array($s_id));
        $db->query("DELETE FROM " . $_SESSION['tablename']['contact_addresses']
            . " WHERE contact_id = ?", array($s_id));
        if($_SESSION['history']['contactdel'])
        {
            require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_history.php');
            $hist = new history();
            $hist->add($_SESSION['tablename']['contacts_v2'], $s_id,"DEL","contactdel",_CONTACT_DELETED.' : '.$s_id, $_SESSION['config']['databasetype']);
        }
        ?>
        <script type="text/javascript">
            window.location.href="<?php echo $_SESSION['config']['businessappurl'].'index.php?page=contacts_v2&order='.functions::xssafe($_REQUEST['order'])
                ."&order_field=".functions::xssafe($_REQUEST['order_field'])."&start=".functions::xssafe($_REQUEST['start'])
                ."&what=".functions::xssafe($_REQUEST['what']);?>";
        </script>
        <?php

    } elseif(empty($_REQUEST['contact'])) {
        $_SESSION['error'] = _NEW_CONTACT.' '._IS_EMPTY.". ". _USE_AUTOCOMPLETION;
        $contact->delcontact($s_id);
    } elseif(empty($_REQUEST['address'])) {
        $_SESSION['error'] = _NEW_ADDRESS.' '._IS_EMPTY.". ". _USE_AUTOCOMPLETION;
        $contact->delcontact($s_id);
    }
} else {
    $contact->delcontact($s_id);
}
