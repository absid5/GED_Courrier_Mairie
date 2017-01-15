<?php
/**
* File : sous_dossiers.php
*
* Subfolders list
*
* @package  Maarch PeopleBox 1.0
* @version 2.1
* @since 06/2006x
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*/

$admin = new core_tools();
$admin->test_admin('admin_architecture', 'apps');
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
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=subfolders';
$page_label = _SUBFOLDER_LIST;
$page_id = "subfolders";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_list_show.php");
$func = new functions();
$db = new Database();
$select[$_SESSION['tablename']['doctypes_second_level']] = array();
array_push($select[$_SESSION['tablename']['doctypes_second_level']],"doctypes_second_level_id","doctypes_first_level_id","doctypes_second_level_label");
$what = "";
$where= " enabled = 'Y' ";
$arrayPDO = array();
if(isset($_REQUEST['what']) && !empty($_REQUEST['what']))
{
    $what = $_REQUEST['what'];
	$where .= " and (lower(doctypes_second_level_label) like lower(?)) ";
    $arrayPDO = array($what.'%');
}
$list = new list_show();
$order = 'asc';
if(isset($_REQUEST['order']) && !empty($_REQUEST['order']))
{
    $order = trim($_REQUEST['order']);
}
$field = 'doctypes_second_level_label';
if(isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field']))
{
    $field = trim($_REQUEST['order_field']);
}

$orderstr = $list->define_order($order, $field);

$request= new request;
$tab=$request->PDOselect($select,$where,$arrayPDO,$orderstr ,$_SESSION['config']['databasetype']);
for ($i=0;$i<count($tab);$i++)
{
    for ($j=0;$j<count($tab[$i]);$j++)
    {
        foreach(array_keys($tab[$i][$j]) as $value)
        {

            if($tab[$i][$j][$value]=="doctypes_second_level_id")
            {
                $tab[$i][$j]["doctypes_second_level_id"]=$tab[$i][$j]['value'];
                $tab[$i][$j]["label"]=_ID;
                $tab[$i][$j]["size"]="10";
                $tab[$i][$j]["label_align"]="left";
                $tab[$i][$j]["align"]="left";
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
                $tab[$i][$j]["order"]='doctypes_second_level_id';
            }
            if($tab[$i][$j][$value]=="doctypes_first_level_id")
            {
                $tab[$i][$j]["doctypes_first_level_id"]=$tab[$i][$j]['value'];
                $tab[$i][$j]["label"]=_STRUCTURE;
                $tab[$i][$j]["size"]="10";
                $tab[$i][$j]["label_align"]="left";
                $tab[$i][$j]["align"]="left";
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
                $tab[$i][$j]["order"]='doctypes_first_level_id';
            }
            if($tab[$i][$j][$value]=="doctypes_second_level_label")
            {
                $tab[$i][$j]['value']=$request->show_string($tab[$i][$j]['value']);
                $tab[$i][$j]["doctypes_second_level_label"]=$tab[$i][$j]['value'];
                $tab[$i][$j]["label"]=_DESC;
                $tab[$i][$j]["size"]="60";
                $tab[$i][$j]["label_align"]="left";
                $tab[$i][$j]["align"]="left";
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
                $tab[$i][$j]["order"]='doctypes_second_level_label';
            }
        }
    }
}
$page_name = "subfolders";
$page_name_up = "subfolder_up";
$page_name_add = "subfolder_up";
$page_name_del = "subfolder_del";
$page_name_val = "";
$table_name = $_SESSION['tablename']['doctypes_second_level'];
$page_name_ban = "";
$label_add = _ADD_SUBFOLDER;
$_SESSION['m_admin']['structures'] = array();

$stmt= $db->query("SELECT * FROM ".$_SESSION['tablename']['doctypes_first_level']." WHERE enabled = 'Y'");
while($res = $stmt->fetchObject())
{
    array_push($_SESSION['m_admin']['structures'], array('ID' => $res->doctypes_first_level_id, 'LABEL'=> $res->doctypes_first_level_label));
}
$autoCompletionArray = array();
$autoCompletionArray["list_script_url"] = $_SESSION['config']['businessappurl']."index.php?display=true&page=subfolders_list_by_name";
$autoCompletionArray["number_to_begin"] = 1;

$list->admin_list($tab, $i, _SUBFOLDER_LIST.' : '.$i." ".strtolower(_SUBFOLDERS), 'doctypes_second_level_id','subfolders','subfolders','doctypes_second_level_id', true, $page_name_up, $page_name_val, $page_name_ban, $page_name_del, $page_name_add, $label_add, FALSE, TRUE, _ALL_SUBFOLDERS, _SUBFOLDER, 'folder-open', false, true, false, true, "", true, $autoCompletionArray);
?>
