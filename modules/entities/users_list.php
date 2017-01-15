<?php
/**
* File : users_list.php
*
* Users list
*
* @package  Maarch Framework 3.0
* @version 1
* @since 03/2009
* @license GPL
* @author  Cédric Ndoumba  <dev@maarch.org>
*/
$admin = new core_tools();
$admin->test_admin('manage_entities', 'entities');
 /****************Management of the location bar  ************/
$init = false;
if(isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true")
{
    $init = true;
}
$level = "";
if(isset($_REQUEST['level']) && $_REQUEST['level'] == 2 || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1)
{
    $level = $_REQUEST['level'];
}
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=users_list&module=entities';
$page_label = _ENTITIES_USERS_LIST;
$page_id = "users_list";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once('modules'.DIRECTORY_SEPARATOR.'entities'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_manage_entities.php');
require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR.'class_list_show.php');
require("modules/entities/entities_tables.php");
$func = new functions();
$ent = new entity();

$what = '';
$where = '';
$my_tab_entities_id = array();
if(isset($_REQUEST['what']) && !empty($_REQUEST['what']))
{
    $what = $func->protect_string_db($_REQUEST['what']);
    $arrayPDO = array();
    $where = " lower(lastname) like lower('".$what."%') ";
}

if($_SESSION['user']['UserId'] != 'superadmin')
{
    $my_tab_entities_id = $ent->get_all_entities_id_user($_SESSION['user']['entities']);
    if (count($my_tab_entities_id)>0)
    {
        if(isset($_REQUEST['what']) && !empty($_REQUEST['what']))
        {
            $where.= ' and ';
        }

        //we need all users who aren't releated with an entity
        // and all users who are releated with all entities that are managed by connected user
        $where.= '('.$_SESSION['tablename']['users'].'.user_id not in (select distinct user_id from '.ENT_USERS_ENTITIES.') or ?.entity_id in (?))';
        $arrayPDO = array($_SESSION['tablename']['ent_users_entities'],join(',', $my_tab_entities_id));
    }
}

$first_join_table = $_SESSION['tablename']['users'];
$second_join_table = ENT_USERS_ENTITIES;
$join_key = "user_id";
$limit = 10;

$select[$_SESSION['tablename']['users']] = array();
array_push($select[$_SESSION['tablename']['users']],"user_id","lastname","firstname","enabled","mail");
$request= new request;
$tab = $request->PDOselect($select, $where, $arrayPDO, "order by lastname asc",$_SESSION['config']['databasetype'], $limit, true, $first_join_table, $second_join_table, $join_key);
//$request->show();

for ($i=0;$i<count($tab);$i++)
{
    for ($j=0;$j<count($tab[$i]);$j++)
    {
        foreach(array_keys($tab[$i][$j]) as $value)
        {
            if($tab[$i][$j][$value]=="user_id")
            {
                $tab[$i][$j]["user_id"]=$tab[$i][$j]['value'];
                $tab[$i][$j]["label"]= _ID;
                $tab[$i][$j]["size"]="15";
                $tab[$i][$j]["label_align"]="left";
                $tab[$i][$j]["align"]="left";
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
            }
            if($tab[$i][$j][$value]=="lastname")
            {
                $tab[$i][$j]['value']= functions::show_string($tab[$i][$j]['value']);
                $tab[$i][$j]["lastname"]=$tab[$i][$j]['value'];
                $tab[$i][$j]["label"]=_LASTNAME;
                $tab[$i][$j]["size"]="15";
                $tab[$i][$j]["label_align"]="left";
                $tab[$i][$j]["align"]="left";
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
            }
            if($tab[$i][$j][$value]=="firstname")
            {
                $tab[$i][$j]['value']= functions::show_string($tab[$i][$j]['value']);
                $tab[$i][$j]["firstname"]= $tab[$i][$j]['value'];
                $tab[$i][$j]["label"]=_FIRSTNAME;
                $tab[$i][$j]["size"]="15";
                $tab[$i][$j]["label_align"]="left";
                $tab[$i][$j]["align"]="left";
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
            }
            if($tab[$i][$j][$value]=="enabled")
            {
                $tab[$i][$j]["enabled"]= $tab[$i][$j]['value'];
                $tab[$i][$j]["label"]=_STATUS;
                $tab[$i][$j]["size"]="3";
                $tab[$i][$j]["label_align"]="left";
                $tab[$i][$j]["align"]="center";
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
            }
            if($tab[$i][$j][$value]=="mail")
            {
                $tab[$i][$j]["mail"] = $tab[$i][$j]['value'];
                $tab[$i][$j]["label"]=_MAIL;
                $tab[$i][$j]["size"]="10";
                $tab[$i][$j]["label_align"]="left";
                $tab[$i][$j]["align"]="left";
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
            }
        }
    }
}
$_SESSION['m_admin']['load_entities'] = true;

$page_name = 'users_list';
$page_name_up = "users_entities_up";
$page_name_ban = "";
$page_name_del = "";
$page_name_val = "";
$page_name_add = "";
$label_add = "";
$title = _ENTITIES_USERS_LIST." : ".$i." "._USERS;
$list = new list_show();
$autoCompletionArray = array();
$list->admin_list($tab, $i, $title,'user_id','users_list', 'entities','user_id', false, $page_name_up, $page_name_val, $page_name_ban, false, $page_name_add, $label_add, false, false, _ALL_USERS, _USER, 'user', true, true, false, true, "", false, $autoCompletionArray);

?>
