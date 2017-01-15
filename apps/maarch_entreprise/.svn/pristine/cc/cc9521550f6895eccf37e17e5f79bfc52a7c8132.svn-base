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
$core_tools2->test_admin('my_contacts', 'apps');
$core_tools2->load_html();
$core_tools2->load_header('', true, false);

require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_contacts_v2.php");

$contact = new contacts_v2();

echo '<div class="error" id="main_error">';
functions::xecho($_SESSION['error']);
echo '</div>';

echo '<div class="info" id="main_info">';
functions::xecho($_SESSION['info']);
echo '</div>';

if(!empty($_SESSION['error'])){
	echo "<script>var main_error = document.getElementById('main_error');if (main_error != null) {main_error.style.display = 'table-cell';}</script>";
}

if(!empty($_SESSION['info'])){
	echo "<script>var main_info = document.getElementById('main_info');if (main_info != null) {main_info.style.display = 'table-cell';}</script>";
}

$_SESSION['error'] = '';
$_SESSION['info'] = '';

if ((!isset($_GET['created']) || $_GET['created'] == '') && $_SESSION['error'] <> '') {
	$_SESSION['m_admin']['contact'] = '';
}

if (isset($_GET['fromAttachmentContact']) && $_GET['fromAttachmentContact'] == "Y") {
	$_SESSION['AttachmentContact'] = "1";
}

if($_SESSION['AttachmentContact'] != "1"){
	$_SESSION['transmissionInput']= "";
}

if (isset($_GET['transmissionInput'])) {
	$_SESSION['transmissionInput'] = $_GET['transmissionInput'];
}

$core_tools2->load_js();
if (isset($_GET['created']) && $_GET['created'] <> '') {
	$contact->chooseContact(true);
} else {
	$contact->chooseContact(false);
}
?>
	<br/>
	<hr>
	<br/>
<?php
$contact->formcontact("add", "", false, true);
?>
	<script type="text/javascript">
		// launch_autocompleter_choose_contact("<?php echo $_SESSION['config']['businessappurl'] . 'index.php?display=true&page=contacts_v2_list_by_name';?>", "contact", "show_contacts", "", "contactid");
		resize_frame_contact('contact');		
	</script>
<?php

if ($_SESSION['AttachmentContact'] == "1") {
	$createContactDiv = "create_contact_div_attach";
} else {
	$createContactDiv = "create_contact_div";
}

if(isset($_GET['created']) && $_GET['created'] <> ''){
	?>
	<script type="text/javascript">
		set_new_contact_address("<?php echo $_SESSION['config']['businessappurl'] . 'index.php?display=false&dir=my_contacts&page=get_last_contact_address';?>", "<?php functions::xecho($createContactDiv);?>", "true", "<?php echo $_SESSION['transmissionInput'];?>");
		simpleAjax("<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&page=unsetAttachmentContact' ;?>");
	</script>
	<?php
}

?>
