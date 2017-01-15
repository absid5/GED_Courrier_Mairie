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

echo '<div class="error" id="main_error">';
functions::xecho($_SESSION['error']);
echo '</div>';

echo '<div class="info" id="main_info">';
functions::xecho($_SESSION['info']);
echo '</div>';

$_SESSION['error'] = '';
$_SESSION['info'] = '';

$core_tools2->load_js();
$func = new functions();

if(isset($_GET['id']))
{
    $id = addslashes($func->wash($_GET['id'], "alphanum", _ADDRESS));
}
else
{
    $id = "";
}

if (isset($_GET['fromContactIframe'])) {
	$iframe_txt = "fromContactIframe";
	$_SESSION['contact']['current_address_id'] = $id;
} else {
	$iframe_txt = "iframe_add_up";
}

$contact->formaddress("up", $id, false, $iframe_txt);


?>
