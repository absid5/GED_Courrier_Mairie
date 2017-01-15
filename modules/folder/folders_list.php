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
*/

/**
* @brief   Displays the folders list in the following baskets
*
* @file
* @author Yves Christian Kpakpo <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup folder
*/

require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php";
require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_manage_status.php";
require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR
            ."class".DIRECTORY_SEPARATOR."class_lists.php";

$status_obj = new manage_status();
$core_tools = new core_tools();
$request    = new request();
$func       = new functions();
$list       = new lists();

//Basket information
if(!empty($_SESSION['current_basket']['view'])) {
	$table = $_SESSION['current_basket']['view'];
} else {
	$table = $_SESSION['current_basket']['table'];
}

//Collection
$_SESSION['collection_id_choice'] = $_SESSION['current_basket']['coll_id'];

//Table or view
    $select[$table]= array();

//Fields
    array_push($select[$table],"folders_system_id", "status", "foldertype_label", 
        "custom_t2", "folder_id", "folder_name", "creation_date", "folders_system_id as count_document");

//Where
    $where_tab = array();
    if (!empty($_SESSION['current_basket']['clause'])) $where_tab[] = stripslashes($_SESSION['current_basket']['clause']); //Basket clause
    $where = implode(' and ', $where_tab);

//Order
    $order = $order_field = '';
    $order = $list->getOrder();
    $order_field = $list->getOrderField();
    if (!empty($order_field) && !empty($order)) 
        $orderstr = "order by ".$order_field." ".$order;
    else  {
        $list->setOrder();
        $list->setOrderField('folder_name');
        $orderstr = "order by folder_name desc";
    }

//Query
	$tab = $request->PDOselect($select,$where,array(),$orderstr,$_SESSION['config']['databasetype'], '1000');
	// $request->show();

//Result Array
	for ($i=0;$i<count($tab);$i++)
	{
		for ($j=0;$j<count($tab[$i]);$j++)
		{
			foreach(array_keys($tab[$i][$j]) as $value)
			{
				if($tab[$i][$j][$value]=='folders_system_id')
				{
					$tab[$i][$j]['folders_system_id']=$tab[$i][$j]['value'];
					$tab[$i][$j]["label"]='';
					$tab[$i][$j]["size"]="4";
					$tab[$i][$j]["label_align"]="left";
					$tab[$i][$j]["align"]="center";
					$tab[$i][$j]["valign"]="bottom";
					$tab[$i][$j]["show"]=false;
					$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
					$tab[$i][$j]["order"]='folders_system_id';
				}
				if ($tab[$i][$j][$value] == "foldertype_label")
				{
					$tab[$i][$j]["label"]=_FOLDERTYPE_LABEL;
					$tab[$i][$j]["size"]="20";
					$tab[$i][$j]["label_align"]="left";
					$tab[$i][$j]["align"]="left";
					$tab[$i][$j]["valign"]="bottom";
					$tab[$i][$j]["show"]=true;
					$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
					$tab[$i][$j]["order"]="foldertype_label";
				}
                if($tab[$i][$j][$value]=="status")
                {
                    $res_status = $status_obj->get_status_data($tab[$i][$j]['value'],$extension_icon);
                    $statusCmp = $tab[$i][$j]['value'];
                    $tab[$i][$j]['value'] = "<img src = '".$res_status['IMG_SRC']."' alt = '".$res_status['LABEL']."' title = '".$res_status['LABEL']."'>";
                    $tab[$i][$j]["label"]=_STATUS;
                    $tab[$i][$j]["size"]="4";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=true;
                    $tab[$i][$j]["order"]='status';
                }
                if ($tab[$i][$j][$value] == "custom_t2")
                {
                    $tab[$i][$j]["label"]=_FOLDERTYPE;
                    $tab[$i][$j]["size"]="5";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=false;
                    $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                    $tab[$i][$j]["order"]="custom_t2";
                }
				if ($tab[$i][$j][$value] == "folder_id")
				{
					$tab[$i][$j]["label"]=_FOLDERID;
					$tab[$i][$j]["size"]="20";
					$tab[$i][$j]["label_align"]="left";
					$tab[$i][$j]["align"]="left";
					$tab[$i][$j]["valign"]="bottom";
					$tab[$i][$j]["show"]=true;
					$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
					$tab[$i][$j]["order"]="folder_id";
				}
				if ($tab[$i][$j][$value] == "folder_name")
				{
					$tab[$i][$j]["label"]=_FOLDERNAME;
					$tab[$i][$j]["size"]="20";
					$tab[$i][$j]["label_align"]="left";
					$tab[$i][$j]["align"]="left";
					$tab[$i][$j]["valign"]="bottom";
					$tab[$i][$j]["show"]=true;
					$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
					$tab[$i][$j]["order"]="folder_name";
				}
				if($tab[$i][$j][$value]=="creation_date")
				{
					$tab[$i][$j]["label"]=_FOLDERDATE;
					$tab[$i][$j]["value"] = $func->format_date($tab[$i][$j]["value"]);
					$tab[$i][$j]["size"]="15";
					$tab[$i][$j]["label_align"]="right";
					$tab[$i][$j]["align"]="right";
					$tab[$i][$j]["valign"]="bottom";
					$tab[$i][$j]["show"]=true;
					$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
					$tab[$i][$j]["order"]="creation_date";
				}
				if($tab[$i][$j][$value]=="count_document")
				{
					$tab[$i][$j]["label"]=_ARCHIVED_DOC;
					$tab[$i][$j]["size"]="15";
					$tab[$i][$j]["label_align"]="right";
					$tab[$i][$j]["align"]="right";
					$tab[$i][$j]["valign"]="bottom";
					$tab[$i][$j]["show"]=false;
					$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
					$tab[$i][$j]["order"]=false;
				}
			}
		}
	}
    
//Clé de la liste
$listKey = 'folders_system_id';

//Initialiser le tableau de paramètres
$paramsTab = array();
$paramsTab['pageTitle'] =  _RESULTS." : ".count($tab).' '._FOUND_FOLDER;        //Titre de la page
$paramsTab['bool_sortColumn'] = true;                                           //Affichage Tri
$paramsTab['bool_bigPageTitle'] = false;                                        //Affichage de l'icone de la page de details
$paramsTab['urlParameters'] = 'baskets='.$_SESSION['current_basket']['id'];     //Parametres d'url supplementaires

// $paramsTab['bool_checkBox'] = true;                                          //Affichage Case à cocher
$paramsTab['bool_radioButton'] = true;                                          //Affichage Bouttons radion

//Sublist 
$paramsTab['bool_showSublist'] = true;                                          //Affichage de sous liste
$paramsTab['sublistUrl'] = 'index.php?display=true&page='
        .'documents_list_in_folder&module=folder&coll_id='
        .$_SESSION['current_basket']['coll_id'];                                //

//Action icons array
$paramsTab['actionIcons'] = array();
$details = array(
		"script"    => "window.top.location='".$_SESSION['config']['businessappurl']."index.php?page=show_folder&module=folder&id=@@folders_system_id@@'",
        "icon"      => 'info-circle',
        "tooltip"   => _DETAILS
        );
array_push($paramsTab['actionIcons'], $details);          
 
//Afficher la liste
$status = 0;
$content = $list->showList($tab, $paramsTab, $listKey, $_SESSION['current_basket']);
// $debug = $list->debug();
echo "{status : " . $status . ", content : '" . addslashes($debug.$content) . "', error : '" . addslashes($error) . "'}";
?>
