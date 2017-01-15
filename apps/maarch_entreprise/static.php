<?php
include_once('../../core/init.php');

if(isset($_GET['filename']) && !empty($_GET['filename']))
{
    $_GET['filename'] = str_replace("\\", "", $_GET['filename']);
    $_GET['filename'] = str_replace("/", "", $_GET['filename']);
    $_GET['filename'] = str_replace("..", "", $_GET['filename']);
    $filename = trim($_GET['filename']);
    $items = explode('.', $filename);
    $ext = array_pop($items);
    $dir = '';
    $module = '';
    $path = '';
    if(isset($_GET['module']) && !empty($_GET['module']))
    {
        $module = trim($_GET['module']);
    }
    switch(strtoupper($ext))
    {
        case 'CSS' :
            $mime_type = 'text/css';
            $dir = 'css'.DIRECTORY_SEPARATOR;
            break;
        case 'JS' :
            $mime_type = 'text/javascript';
            $dir = 'js'.DIRECTORY_SEPARATOR;
            break;
        case 'HTML' :
            $mime_type = 'text/html';
            break;
/*
        case 'XML' :
            $mime_type = 'text/xml';
            break;
*/
        case 'PNG' :
            $mime_type = 'image/png';
            $dir = 'img'.DIRECTORY_SEPARATOR;
            break;
        case 'JPEG' :
            $mime_type = 'image/jpeg ';
            $dir = 'img'.DIRECTORY_SEPARATOR;
            break;
        case 'JPG' :
            $mime_type = 'image/jpeg ';
            $dir = 'img'.DIRECTORY_SEPARATOR;
            break;
        case 'GIF' :
            $mime_type = 'image/gif ';
            $dir = 'img'.DIRECTORY_SEPARATOR;
            break;
        default :
            $mime_type = '';
    }
    if(isset($_GET['dir']) && !empty($_GET['dir']))
    {
        $dir = trim($_GET['dir']).DIRECTORY_SEPARATOR;
    }
    if(!empty($module) && $module <> 'core')
    {

        if(file_exists($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$dir.$filename))
        {
            $path = $_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$dir.$filename;
        }
        elseif(file_exists($_SESSION['config']['corepath'].'modules'.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$dir.$filename))
        {
            $path = $_SESSION['config']['corepath'].'modules'.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$dir.$filename;
        }
    }
    else if($module == 'core')
    {
        if(file_exists($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.$dir.$filename))
        {
            $path = $_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.$dir.$filename;
        }
        elseif(file_exists($_SESSION['config']['corepath'].'core'.DIRECTORY_SEPARATOR.$dir.$filename))
        {
            $path = $_SESSION['config']['corepath'].'core'.DIRECTORY_SEPARATOR.$dir.$filename;
        }
    }
    else
    {

        if(file_exists($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.$dir.$filename))
        {
            $path = $_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.$dir.$filename;
        }
        elseif(file_exists($_SESSION['config']['corepath'].'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.$dir.$filename))
        {
            $path = $_SESSION['config']['corepath'].'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.$dir.$filename;
        }
    }

    if(!empty($mime_type) && !empty($path))
    {

        $date = mktime(0,0,0,date("m" ) + 2  ,date("d" ) ,date("Y" )  );
        $date = date("D, d M Y H:i:s", $date);
        $time = 30*12*60*60;
        header("Pragma: public");
        header("Expires: ".$date." GMT");
        header("Cache-Control: max-age=".$time.", must-revalidate");
        //header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Type: ".$mime_type);
        header("Content-Disposition: inline; filename=".$filename.";");
        header("Content-Transfer-Encoding: binary");
        readfile($path);
    }
}

exit();
