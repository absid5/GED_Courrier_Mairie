<?php
/**
* File : group_up_db.php
*
*  Modify the group in the database after the form
*
* @package  Maarch PeopleBox 1.0
* @version 2.1
* @since 06/2006
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*/

$core_tools = new core_tools();
//here we loading the lang vars
$core_tools->load_lang();
$core_tools->test_admin('admin_reports', 'reports');

require_once("modules".DIRECTORY_SEPARATOR."reports".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_admin_reports.php");

$admin_reports = new admin_reports();

$admin_reports->load_reports_db($_REQUEST['reports'],$_SESSION['m_admin']['reports']['GroupId']);
?>
