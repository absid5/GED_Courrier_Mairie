<?php
/**
* File : reopen.php
*
* Identification with cookie
*
* @package  Maarch PeopleBox 1.0
* @version 2.1
* @since 10/2005
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*/
include_once('../../core/class/class_functions.php');
include('../../core/init.php');

//$_SESSION['slash_env'] = DIRECTORY_SEPARATOR;

$path_tmp = explode('/',$_SERVER['SCRIPT_FILENAME']);
$path_server = implode('/',array_slice($path_tmp,0,array_search('apps',$path_tmp))).'/';
if (isset($_SESSION['config']['coreurl'])) {
    $_SESSION['urltomodules'] = $_SESSION['config']['coreurl']."/modules/";
}
$_SESSION['config']['corepath'] = $path_server;
chdir($_SESSION['config']['corepath']);
if(!isset($_SESSION['config']['app_id']) || empty($_SESSION['config']['app_id']))
{
    $_SESSION['config']['app_id'] = $path_tmp[count($path_tmp) -2];
}

$func = new functions();
$cookie = explode("&", $_COOKIE['maarch']);
$user = explode("=",$cookie[0]);
$thekey = explode("=",$cookie[1]);
$s_UserId = strtolower($func->wash($user[1],"no","","yes"));
$s_key =strtolower($func->wash($thekey[1],"no","","yes"));
$_SESSION['arg_page'] = '';

if(!empty($_SESSION['error']) || ($s_UserId == "1" && $s_key == ""))
{
    header("location: ".$_SESSION['config']['businessappurl']."index.php?display=true&page=login");
    exit();
}
else
{

    if(trim($_SERVER['argv'][0]) <> "")
    {
        $_SESSION['requestUri'] = $_SERVER['argv'][0];
        header("location: ".$_SESSION['config']['businessappurl']."index.php?display=true&page=login");
    }
    else
    {
        header("location: ".$_SESSION['config']['businessappurl']."index.php?display=true&page=login");
    }
    exit();
}
