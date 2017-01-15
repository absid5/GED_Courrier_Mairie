<?php

require_once "core" . DIRECTORY_SEPARATOR 
    . "class" . DIRECTORY_SEPARATOR . "class_request.php";
require_once "modules" . DIRECTORY_SEPARATOR . "folder" 
    . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR 
    . "class_admin_foldertypes.php";
require_once "core" . DIRECTORY_SEPARATOR . "class"
    . DIRECTORY_SEPARATOR . "class_manage_status.php";
require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id']
    .DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR
    ."class_lists.php";

$core       = new core_tools();
$func       = new functions();
$req        = new request();
$foldertype = new foldertype();
$status_obj = new manage_status();
$list       = new lists();

$core->load_lang();

$core->test_service('folder_search', 'folder');
/****************Management of the location bar  ************/
$init = false;
if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true") {
    $init = true;
}
$level = "";
if (isset($_REQUEST['level']) && ($_REQUEST['level'] == 2
    || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4
    || $_REQUEST['level'] == 1)
) {
    $level = $_REQUEST['level'];
}
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=search_adv_folder_result&module=folder';
$page_label = _RESULTS;
$page_id = "folder_search_adv_result";
$core->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/

$view = $_SESSION['view']['view_folders'];

$where_request = "";
$date_pattern = "/^[0-3][0-9]-[0-1][0-9]-[1-2][0-9][0-9][0-9]$/";
$arrayPDO = array();

//Foldertype
    if(isset($_REQUEST['foldertype_id']) && !empty($_REQUEST['foldertype_id']))
    {
        $_SESSION['folder_search']['foldertype_id'] = trim($_REQUEST['foldertype_id']);
        $where_request .= " ".$view.".foldertype_id = :foldertypeid and ";
        $arrayPDO = array_merge($arrayPDO, array(":foldertypeid" => $_SESSION['folder_search']['foldertype_id']));
    }
    else
    {
        if(!empty($_SESSION['folder_search']['foldertype_id'])){
            $where_request .= " ".$view.".foldertype_id = :foldertypeid and ";
            $arrayPDO = array_merge($arrayPDO, array(":foldertypeid" => $_SESSION['folder_search']['foldertype_id']));
        }else{
            $_SESSION['folder_search']['foldertype_id'] = "";
        }
    }
    
//Foldername
    if(isset($_REQUEST['folder_name']) && !empty($_REQUEST['folder_name']))
    {
        $_SESSION['folder_search']['folder_name'] = trim($_REQUEST['folder_name']);
        if($_SESSION['config']['databasetype'] == "POSTGRESQL")
        {
            $where_request .= " ".$view.".folder_name ilike :foldername and ";
            $arrayPDO = array_merge($arrayPDO, array(":foldername" => "%".$_SESSION['folder_search']['folder_name']."%"));
        }
        else
        {
            $where_request .= " ".$view.".folder_name like :foldername and ";
            $arrayPDO = array_merge($arrayPDO, array(":foldername" => "%".$_SESSION['folder_search']['folder_name']."%"));
        }
    }
    else
    {
        if(!empty($_SESSION['folder_search']['folder_name'])){
            if($_SESSION['config']['databasetype'] == "POSTGRESQL")
            {
                $where_request .= " ".$view.".folder_name ilike :foldername and ";
                $arrayPDO = array_merge($arrayPDO, array(":foldername" => "%".$_SESSION['folder_search']['folder_name']."%"));
            }
            else
            {
                $where_request .= " ".$view.".folder_name like :foldername and ";
                $arrayPDO = array_merge($arrayPDO, array(":foldername" => "%".$_SESSION['folder_search']['folder_name']."%"));
            }
        }else{
            $_SESSION['folder_search']['folder_name'] = "";
        }
    }
    
//Folder id
    if(isset($_REQUEST['folder_id']) && !empty($_REQUEST['folder_id']))
    {
        $_SESSION['folder_search']['folder_id'] = trim($_REQUEST['folder_id']);
        if($_SESSION['config']['databasetype'] == "POSTGRESQL")
        {
            $where_request .= " ".$view.".folder_id ilike :folderid and ";
            $arrayPDO = array_merge($arrayPDO, array(":folderid" => "%".$_SESSION['folder_search']['folder_id']."%"));
        }
        else
        {
            $where_request .= " ".$view.".folder_id like :folderid and ";
            $arrayPDO = array_merge($arrayPDO, array(":folderid" => "%".$_SESSION['folder_search']['folder_id']."%"));
        }
    }
    else
    {
        if(!empty($_SESSION['folder_search']['folder_id'])){
            
            if($_SESSION['config']['databasetype'] == "POSTGRESQL")
            {
                $where_request .= " ".$view.".folder_id ilike :folderid and ";
                $arrayPDO = array_merge($arrayPDO, array(":folderid" => "%".$_SESSION['folder_search']['folder_id']."%"));
            }
            else
            {
                $where_request .= " ".$view.".folder_id like :folderid and ";
                $arrayPDO = array_merge($arrayPDO, array(":folderid" => "%".$_SESSION['folder_search']['folder_id']."%"));
            }    
        }else{
            $_SESSION['folder_search']['folder_id'] = "";
        }
    }

//Creation date
    //$_SESSION['folder_search']['creation_date_start'] = '';
    if(!empty($_REQUEST['creation_date_start']) && isset($_REQUEST['creation_date_start']))
    {
        if( preg_match($date_pattern,$_REQUEST['creation_date_start'])==false )
        {
            $_SESSION['error'] = _WRONG_DATE_FORMAT;

        }
        else
        {
            $_SESSION['folder_search']['creation_date_start'] = $func->format_date_db($_REQUEST['creation_date_start']);
            $where_request .= " (".$req->extract_date($view.'.creation_date')." >= :creationdatestart) and ";
            $arrayPDO = array_merge($arrayPDO, array(":creationdatestart" => $_SESSION['folder_search']['creation_date_start']));
        }
    }else{
        if(!empty($_SESSION['folder_search']['creation_date_start'])){
            $where_request .= " (".$req->extract_date($view.'.creation_date')." >= :creationdatestart) and ";
            $arrayPDO = array_merge($arrayPDO, array(":creationdatestart" => $_SESSION['folder_search']['creation_date_start']));
        }
    }


    //$_SESSION['folder_search']['creation_date_end'] ='';
    if(!empty($_REQUEST['creation_date_end']) && isset($_REQUEST['creation_date_end']))
    {
        if( preg_match($date_pattern,$_REQUEST['creation_date_end'])==false )
        {
            $_SESSION['error'] = _WRONG_DATE_FORMAT;
        }
        else
        {
            $_SESSION['folder_search']['creation_date_end'] = $func->format_date_db($_REQUEST['creation_date_end']);
            $where_request .= " (".$req->extract_date($view.'.creation_date')." <= :creationdateend) and ";
            $arrayPDO = array_merge($arrayPDO, array(":creationdateend" => $_SESSION['folder_search']['creation_date_end']));
        }
    }else{
        if(!empty($_SESSION['folder_search']['creation_date_end'])){
            $where_request .= " (".$req->extract_date($view.'.creation_date')." <= :creationdateend) and ";
            $arrayPDO = array_merge($arrayPDO, array(":creationdateend" => $_SESSION['folder_search']['creation_date_end']));
        }
    }

    if (isset($_SESSION['user']['entities']['0'])) {
        $finalDest = [];
        foreach ($_SESSION['user']['entities'] as $tmp) {
            $finalDest[] = $tmp['ENTITY_ID'];
        }
        $where_request .= " (destination in (:finaldest) OR destination is null) and";
        $arrayPDO = array_merge($arrayPDO, [":finaldest" => $finalDest]);
    }

//Optional indexes
    if(isset($_SESSION['folder_search']['foldertype_id']) && !empty($_SESSION['folder_search']['foldertype_id']))
    {
        $indexes = $foldertype->get_indexes($_SESSION['folder_search']['foldertype_id']) ;
        foreach(array_keys($indexes) as $key)
        {
            if(isset($_REQUEST[$key]) && !empty($_REQUEST[$key]))
            {
                $_SESSION['folder_search'][$key] = $_REQUEST[$key];
                $where_request .= $foldertype->search_checks($indexes, $key, $_REQUEST[$key], 'view_folders');
            }
            elseif(isset($_REQUEST[$key.'_from']) && !empty($_REQUEST[$key.'_from']))
            {
                $_SESSION['folder_search'][$key.'_from'] = $_REQUEST[$key.'_from'];
                $where_request .= $foldertype->search_checks($indexes, $key.'_from', $_REQUEST[$key.'_from'], 'view_folders');
            }
            elseif( isset($_REQUEST[$key.'_to']) && !empty($_REQUEST[$key.'_to']))
            {
                $_SESSION['folder_search'][$key.'_to'] = $_REQUEST[$key.'_to'];
                $where_request .= $foldertype->search_checks($indexes, $key.'_to', $_REQUEST[$key.'_to'], 'view_folders');
            }
            elseif( isset($_REQUEST[$key.'_max'])  && !empty($_REQUEST[$key.'_max']))
            {
                $_SESSION['folder_search'][$key.'_max'] = $_REQUEST[$key.'_max'];
                $where_request .= $foldertype->search_checks($indexes, $key.'_max', $_REQUEST[$key.'_max'], 'view_folders');
            }
            elseif(isset($_REQUEST[$key.'_min']) && !empty($_REQUEST[$key.'_min']))
            {
                $_SESSION['folder_search'][$key.'_min'] = $_REQUEST[$key.'_min'];
                $where_request .= $foldertype->search_checks($indexes, $key.'_min', $_REQUEST[$key.'_min'], 'view_folders');
            }
        }
    }
    
if($_SESSION['save_list']['fromDetail'] == "true") {
        $urlParameters .= '&start='.$_SESSION['save_list']['start'];
        $urlParameters .= '&lines='.$_SESSION['save_list']['lines'];
        $urlParameters .= '&order='.$_SESSION['save_list']['order'];
        $urlParameters .= '&order_field='.$_SESSION['save_list']['order_field'];
        // if ($_SESSION['save_list']['template'] <> "") {
        //     $urlParameters .= '&template='.$_SESSION['save_list']['template'];
        // }
        $_SESSION['save_list']['fromDetail'] = "false";
        $_SESSION['save_list']['url'] = $urlParameters;
    }
    $_SESSION['save_list']['start'] = "";
    $_SESSION['save_list']['lines'] = "";
    $_SESSION['save_list']['order'] = "";
    $_SESSION['save_list']['order_field'] = "";
    //$_SESSION['save_list']['template'] = "";    

if(!empty($_SESSION['error']))
{
    $func->echo_error(_ADV_SEARCH_FOLDER_TITLE, "<br /><div class=\"error\">"._MUST_CORRECT_ERRORS." : <br /><br /><strong>"
        .$_SESSION['error']."<br /><a href=\"".$_SESSION['config']['businessappurl']
        ."index.php?page=search_adv_folder&module=folder\">"._CLICK_HERE_TO_CORRECT
        ."</a></strong></div>", 'title', $_SESSION['config']['businessappurl']
        ."static.php?module=folder&filename=picto_search_b.gif");
}
else
{   
    $_SESSION['searching']['where_request'] = $where_request;
    $_SESSION['searching']['where_request_parameters'] = $arrayPDO;

    //List
    $target = $_SESSION['config']['businessappurl'].'index.php?module=folder&page=folders_list_search_adv'.$urlParameters;
    $listContent = $list->loadList($target, true, 'divList', 'false');
    echo $listContent;
}
?>
