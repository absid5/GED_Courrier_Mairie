<?php
/*
*
*    Copyright 2008,2012 Maarch
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
*
*   @author  Cyril Vazquez <dev@maarch.org>
*/

$admin = new core_tools();
$admin->test_admin('admin_difflist_types', 'entities');
$_SESSION['m_admin']= array();
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
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=admin_difflist_types&module=entities';
$page_label = _DIFFLIST_TYPES;
$page_id = "admin_difflist_types";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/

require_once("apps/".$_SESSION['config']['app_id']."/class/class_list_show.php");
require_once("core/class/class_request.php");
require_once("modules/entities/entities_tables.php");

$func = new functions();
$request = new request;

$what = '';
$where = '';

$list = new list_show();
$arrayPDO = array();

if(isset($_REQUEST['what']) && !empty($_REQUEST['what']))
{
    $what = $func->protect_string_db($_REQUEST['what']);
	$where = " lower(difflist_type_label) like lower(?)";
    $arrayPDO = array_merge($arrayPDO, array($what."%"));
}

$order = 'asc';
if(isset($_REQUEST['order']) && !empty($_REQUEST['order']))
{
    $order = trim($_REQUEST['order']);
}
$field = 'difflist_type_id';
if(isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field']))
{
    $field = trim($_REQUEST['order_field']);
}

$orderstr = $list->define_order($order, $field);

$select[ENT_DIFFLIST_TYPES] = array();
array_push($select[ENT_DIFFLIST_TYPES], "difflist_type_id", "difflist_type_label", 'is_system');

$tab = $request->PDOselect($select, $where, $arrayPDO, $orderstr, $_SESSION['config']['databasetype']);

for ($i=0;$i<count($tab);$i++)
{
    for ($j=0;$j<count($tab[$i]);$j++)
    {
        foreach(array_keys($tab[$i][$j]) as $value)
        {
            if($tab[$i][$j][$value]=="difflist_type_id")
            {
                $tab[$i][$j]["difflist_type_id"]=$tab[$i][$j]['value'];
                $tab[$i][$j]["label"]= _ID;
                $tab[$i][$j]["size"]="18";
                $tab[$i][$j]["label_align"]="left";
                $tab[$i][$j]["align"]="left";
                $tab[$i][$j]["order"]=$tab[$i][$j][$value];
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
            }

            if($tab[$i][$j][$value]=="difflist_type_label")
            {
                $tab[$i][$j]['value']=$request->show_string($tab[$i][$j]['value']);
                $tab[$i][$j]["difflist_type_label"]=$tab[$i][$j]['value'];
                $tab[$i][$j]["label"]=_DIFFLIST_TYPE_LABEL;
                $tab[$i][$j]["size"]="25";
                $tab[$i][$j]["label_align"]="left";
                $tab[$i][$j]["align"]="left";
                $tab[$i][$j]["order"]=$tab[$i][$j][$value];
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
            }
            
            if($tab[$i][$j][$value]=="is_system")
            {
                $tab[$i][$j]['value']=$request->show_string($tab[$i][$j]['value']);
                if($tab[$i][$j]['value'] == 'Y')
                    $tab[$i][$j]["can_delete"] = 'false';
                $tab[$i][$j]["label"]= _IS_SYSTEM;
                $tab[$i][$j]["size"]="10";
                $tab[$i][$j]["label_align"]="left";
                $tab[$i][$j]["align"]="left";
                $tab[$i][$j]["order"]=$tab[$i][$j][$value];
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=false;
            }
        }
    }
}

$page_name = "admin_difflist_types";
$page_name_up = "admin_difflist_type&mode=up";
$page_name_add = "admin_difflist_type&mode=add";
$page_name_del = "admin_difflist_type&mode=del";
$label_add = _ADD_DIFFLIST_TYPE;
$_SESSION['m_admin']['init'] = true;

$title = _DIFFLIST_TYPES." : ".$i." "._DIFFLIST_TYPE;
$autoCompletionArray = false;//array();

$list->admin_list($tab, $i, $title, 'difflist_type_id','admin_difflist_types','entities', 'difflist_type_id', true, $page_name_up, $page_name_val, $page_name_ban, $page_name_del, $page_name_add, $label_add, false, false, _ALL_DIFFLIST_TYPES, _DIFFLIST_TYPE, "share-alt", true, true, false, true, "", true, $autoCompletionArray, false, true);
?>
