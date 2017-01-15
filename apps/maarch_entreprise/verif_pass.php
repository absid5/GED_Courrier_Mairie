<?php
/**
* File : verif_pass.php
*
* Treat the user modification (new password)
*
* @package  Maarch PeopleBox 1.0
* @version 2.0
* @since 06/2006
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*/

require_once('core' . DIRECTORY_SEPARATOR . 'class'
	. DIRECTORY_SEPARATOR . 'class_security.php');

$core_tools = new core_tools();
$core_tools->load_lang();
$func = new functions();

$_SESSION['error'] ="";
$_SESSION['user']['pass'] =  $func->wash($_REQUEST['pass1'], "no", _THE_PSW);

$pass2 = $func->wash($_REQUEST['pass2'], "no", _THE_PSW_VALIDATION);

if($_SESSION['user']['pass'] <> $pass2)
{
	$_SESSION['error'] = _WRONG_SECOND_PSW;
}
else
{
	$sec = new security();
	$_SESSION['user']['pass'] = $sec->getPasswordHash($pass2);
}

$_SESSION['user']['FirstName'] = $func->wash($_REQUEST['FirstName'], "no", _THE_LASTNAME);
$_SESSION['user']['LastName'] = $func->wash($_REQUEST['LastName'], "no", _THE_FIRSTNAME);

if(isset($_REQUEST['Department']) && !empty($_REQUEST['Department']))
{
	$_SESSION['user']['department']  = $func->wash($_REQUEST['Department'], "no", _THE_DEPARTMENT);
}

if(isset($_REQUEST['Phone']) && !empty($_REQUEST['Phone']))
{
	$_SESSION['user']['Phone']  = $_REQUEST['Phone'];
}
$_SESSION['user']['Mail']  = '';
$tmp=$func->wash($_REQUEST['Mail'], "mail", _MAIL);
if($tmp <> false)
{
	$_SESSION['user']['Mail'] = $tmp;
}
if(!empty($_SESSION['error']))
{
	header("location: ".$_SESSION['config']['businessappurl']."index.php?display=true&page=change_pass");
	exit();
}
else
{
	$db = new Database();

	$tmp_fn = $_SESSION['user']['FirstName'];
	$tmp_ln = $_SESSION['user']['LastName'];
	$tmp_dep = $_SESSION['user']['department'];

	$db->query("UPDATE ".$_SESSION['tablename']['users']." SET password = ? ,firstname = ?, lastname = ?, phone = ?, mail = ? , department = ? , change_password = 'N' where user_id = ?",
		array($_SESSION['user']['pass'], $tmp_fn, $tmp_ln, $_SESSION['user']['Phone'], $_SESSION['user']['Mail'], $tmp_dep, $_SESSION['user']['UserId']));

	header("location: ".$_SESSION['config']['businessappurl']."index.php");
	exit();

}
