<?php
/**
* File : result_folder.php
*
* Frame : show the folders corresponding to the search (folder select in indexing process)
*
* @package  Maarch PeopleBox 1.0
* @version 1.0
* @since 10/2006
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*/

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
$core_tools = new core_tools();
$core_tools->load_lang();
require_once("modules".DIRECTORY_SEPARATOR."folder".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_modules_tools.php");
require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_list_show.php");
$func = new functions();
$core_tools->test_service('folder_search', 'folder');
$core_tools->load_html();
//here we building the header
$core_tools->load_header('', true, false);
?>
<body>
<?php
//if(isset($_SESSION['folderSystemId'])and !empty($_SESSION['folderSystemId']))
if(isset($_SESSION['stringSearch'])and !empty($_SESSION['stringSearch']))
{
	$select[$_SESSION['tablename']['fold_folders']]= array();
	array_push($select[$_SESSION['tablename']['fold_folders']],"folders_system_id","folder_id","folder_name","subject","folder_level");
	$select[$_SESSION['tablename']['fold_foldertypes']]= array();
	array_push($select[$_SESSION['tablename']['fold_foldertypes']],"foldertype_label");

	$where = " ".$_SESSION['tablename']['fold_folders'].".foldertype_id = ".$_SESSION['tablename']['fold_foldertypes'].".foldertype_id ";
	$where .= " and (lower(folder_id) like lower(:stringSearch) or lower(folder_name) like lower(:stringSearch) or lower(subject) like lower(:stringSearch)) and status <> 'DEL'";
	$arrayPDO = array(":stringSearch" => '%'.$_SESSION['stringSearch'].'%');

	$request= new request;
	$tab=$request->PDOselect($select, $where, $arrayPDO, " order by folder_name ",$_SESSION['config']['databasetype']);
	//$request->show();
	$folder_tmp = new folder();
	for ($i=0;$i<count($tab);$i++)
	{
		for ($j=0;$j<count($tab[$i]);$j++)
		{
			foreach(array_keys($tab[$i][$j]) as $value)
			{
				if($tab[$i][$j][$value]=="folders_system_id")
				{
					$tab[$i][$j]["folders_system_id"]=$tab[$i][$j]['value'];
					$tab[$i][$j]["label"]=_GED_NUM;
					$tab[$i][$j]["size"]="2";
					$tab[$i][$j]["label_align"]="left";
					$tab[$i][$j]["align"]="left";
					$tab[$i][$j]["valign"]="bottom";
					$tab[$i][$j]["show"]=false;
				}
				if($tab[$i][$j][$value]=="folder_level")
				{
					$tab[$i][$j]["label"]=_PROJECT." / "._MARKET;
					if($tab[$i][$j]["value"] == 1)
					{
						$tab[$i][$j]["value"] = _PROJECT;
					}
					elseif($tab[$i][$j]["value"] == 2)
					{
						$tab[$i][$j]["value"] = _MARKET;
					}
					$tab[$i][$j]["size"]="50";
					$tab[$i][$j]["label_align"]="left";
					$tab[$i][$j]["align"]="left";
					$tab[$i][$j]["valign"]="bottom";
					$tab[$i][$j]["show"]=true;
				}
				if($tab[$i][$j][$value]=="folder_name")
				{
					$tab[$i][$j]["value"]=$request->show_string($tab[$i][$j]["value"]);
					$tab[$i][$j]["label"]=_LASTNAME;
					$tab[$i][$j]["size"]="50";
					$tab[$i][$j]["label_align"]="left";
					$tab[$i][$j]["align"]="left";
					$tab[$i][$j]["valign"]="bottom";
					$tab[$i][$j]["show"]=true;
				}
				if($tab[$i][$j][$value]=="subject")
				{
					$tab[$i][$j]["value"]=$request->show_string($tab[$i][$j]["value"]);
					$tab[$i][$j]["label"]=_LABEL;
					$tab[$i][$j]["size"]="50";
					$tab[$i][$j]["label_align"]="left";
					$tab[$i][$j]["align"]="left";
					$tab[$i][$j]["valign"]="bottom";
					$tab[$i][$j]["show"]=true;
				}
			}
		}
	}
	for ($i=0;$i<count($tab);$i++)
	{
		for ($j=0;$j<count($tab[$i]);$j++)
		{
			foreach(array_keys($tab[$i][$j]) as $value)
			{
				if($value == 'column' and $tab[$i][$j][$value]=="folders_system_id")
				{
					$tmp = array();
					$id = $tab[$i][$j]['value'];

					$folder_tmp->load_folder($id,$_SESSION['tablename']['fold_folders']);
					array_push($tab[$i], $tmp);
				}
			}
		}
	}

	//$request->show_array($tab);
	$list=new list_show();
	$ind = count($tab);
	$list->list_doc($tab,$ind,_SEARCH_RESULTS." : ".$ind." "._FOUND_FOLDERS,"folders_system_id","result_folder&module=folder","folders_system_id","folder_detail",false,true,"get",$_SESSION['config']['businessappurl']."index.php?display=true&module=folder&page=res_select_folder",_CHOOSE, false, false, true, false, false, false,  false, false, '', '', false, '', '', 'listing spec', '', false, false, array(), '<input type="hidden" name="display" value="true"/><input type="hidden" name="module" value="folder" /><input type="hidden" name="page" value="res_select_folder" />');
}
$core_tools->load_js();
?>
</body>
</html>
