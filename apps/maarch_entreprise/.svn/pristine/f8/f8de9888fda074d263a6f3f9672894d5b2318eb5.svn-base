<?php
/*
*
*    Copyright 2008,2015 Maarch
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
$admin->test_admin('admin_parameters', 'apps');
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
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=admin_parameters&admin=parameters';
$page_label = _PARAMETERS;
$page_id = "admin_parameters";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/

?>
<table width="100%">
    <tr>
        <td align="left">
            <input class="button" type="button" value="<?php echo _CONTROL_PARAM_TECHNIC;
                ?>" onclick="window.location.href = '<?php echo $_SESSION['config']['businessappurl'] 
                    . 'index.php?admin=parameters&page=control_param_technic';?>';"/>      
        </td>
   </tr>
</table>
<?php

require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_list_show.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("core".DIRECTORY_SEPARATOR."core_tables.php");

$func = new functions();
$request = new request;

$what = '';
$where = '';
$arrayPDO = array();

$list = new list_show();

if(isset($_REQUEST['what']) && !empty($_REQUEST['what']))
{
    $what = $_REQUEST['what'];
	$where = " lower(id) like lower(?) or lower(description) like lower(?) ";
    $arrayPDO = array($what.'%', '%'.$what.'%');
}

$order = 'asc';
if(isset($_REQUEST['order']) && !empty($_REQUEST['order']))
{
    $order = trim($_REQUEST['order']);
}
$field = 'id';
if(isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field']))
{
    $field = trim($_REQUEST['order_field']);
}

$orderstr = $list->define_order($order, $field);

$select[PARAM_TABLE] = array();
array_push($select[PARAM_TABLE], "id", "description", "param_value_string", "param_value_int", "param_value_date");

$tab = $request->PDOselect($select, $where, $arrayPDO, $orderstr, $_SESSION['config']['databasetype']);

# loop on lines
for ($i=0;$i<count($tab);$i++)
{
    $value_shown = false;
    # Loop on cols
    for ($j=0;$j<count($tab[$i]);$j++)
    {
        if($tab[$i][$j]['column']=="id")
        {
            $tab[$i][$j]["id"]=$tab[$i][$j]['value'];
            $tab[$i][$j]["label"]= _ID;
            $tab[$i][$j]["size"]="20";
            $tab[$i][$j]["label_align"]="left";
            $tab[$i][$j]["align"]="left";
            $tab[$i][$j]["order"]=$tab[$i][$j][$col];
            $tab[$i][$j]["valign"]="bottom";
            $tab[$i][$j]["show"]=true;
        }

        if($tab[$i][$j]['column']=="description")
        {
            $tab[$i][$j]['value']=$request->show_string($tab[$i][$j]['value']);
            $tab[$i][$j]["description"]=$tab[$i][$j]['value'];
            $tab[$i][$j]["label"]=_DESC;
            $tab[$i][$j]["size"]="30";
            $tab[$i][$j]["label_align"]="left";
            $tab[$i][$j]["align"]="left";
            $tab[$i][$j]["order"]=$tab[$i][$j][$col];
            $tab[$i][$j]["valign"]="bottom";
            $tab[$i][$j]["show"]=true;
        }
        
        if($tab[$i][$j]['column']=="param_value_string" && (string)$tab[$i][$j]['value'] <> "")
        {
            $tab[$i][$j]['value']=$request->show_string($tab[$i][$j]['value']);
            // $tab[$i][$j]["label"]=_VALUE;
            $tab[$i][$j]["size"]="30";
            $tab[$i][$j]["label_align"]="left";
            $tab[$i][$j]["align"]="left";
            $tab[$i][$j]["order"]=$tab[$i][$j][$col];
            $tab[$i][$j]["valign"]="bottom";
            $tab[$i][$j]["show"]=true;
            $value_shown = true;
        }
        if($tab[$i][$j]['column']=="param_value_int" && (string)$tab[$i][$j]['value'] <> "")
        {
            $tab[$i][$j]['value']=$request->show_string($tab[$i][$j]['value']);
            // $tab[$i][$j]["label"]=_VALUE;
            $tab[$i][$j]["size"]="50";
            $tab[$i][$j]["label_align"]="left";
            $tab[$i][$j]["align"]="left";
            $tab[$i][$j]["order"]=$tab[$i][$j][$col];
            $tab[$i][$j]["valign"]="bottom";
            $tab[$i][$j]["show"]=true;
            $value_shown = true;
        }
        if($tab[$i][$j]['column']=="param_value_date" && (string)$tab[$i][$j]['value'] <> "")
        {
            $tab[$i][$j]['value']=$request->show_string($tab[$i][$j]['value']);
            // $tab[$i][$j]["label"]=_VALUE;
            $tab[$i][$j]["size"]="50";
            $tab[$i][$j]["label_align"]="left";
            $tab[$i][$j]["align"]="left";
            $tab[$i][$j]["order"]=$tab[$i][$j][$col];
            $tab[$i][$j]["valign"]="bottom";
            $tab[$i][$j]["show"]=true;
            $value_shown = true;
        }
    }

    if(!$value_shown) {
        $tab[$i][2]['column'] = 'param_value_string';
        $tab[$i][2]['value']='';
        $tab[$i][2]["label"]=_VALUE;
        $tab[$i][2]["size"]="50";
        $tab[$i][2]["label_align"]="left";
        $tab[$i][2]["align"]="left";
        $tab[$i][2]["order"]='';
        $tab[$i][2]["valign"]="bottom";
        $tab[$i][2]["show"]=true;
    }

}

$tab[0][2]["label"]=_VALUE;

$page_name = "admin_parameters";
$page_name_up = "admin_parameter&mode=up";
$page_name_add = "admin_parameter&mode=add";
$page_name_del = "admin_parameter&mode=del";
$label_add = _ADD_PARAMETER;
$_SESSION['m_admin']['init'] = true;

$title = _PARAMETERS." : ".$i." "._PARAMETER_S;
$autoCompletionArray = false;//array();

$list->admin_list($tab, $i, $title, 'id','admin_parameters','parameters', 'id', true, $page_name_up, $page_name_val, $page_name_ban, $page_name_del, $page_name_add, $label_add, false, false, _ALL_PARAMETERS, _PARAMETER, 'wrench', false, true, false, true, "", true, $autoCompletionArray, false, true);
?>
