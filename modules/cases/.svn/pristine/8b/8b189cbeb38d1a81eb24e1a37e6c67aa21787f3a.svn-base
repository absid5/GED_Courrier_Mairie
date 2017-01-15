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
* @brief   Displays document list in baskets
*
* @file
* @author Yves Christian Kpakpo <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup basket
*/
require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php";
require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php";
require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_manage_status.php";
require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR
            ."class".DIRECTORY_SEPARATOR."class_lists.php";

$status_obj = new manage_status();
$security   = new security();
$core_tools = new core_tools();
$request    = new request();
$list       = new lists();

//Include definition fields
include_once('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'definition_mail_categories.php');

//Create sql request
if(!empty($_SESSION['current_basket']['view'])) {
	$table = $_SESSION['current_basket']['view'];
} else {
	$table = $_SESSION['current_basket']['table'];
}

$_SESSION['collection_id_choice'] = $_SESSION['current_basket']['coll_id'];//Collection

//Table
    $select = array();
    $select[$_SESSION['tablename']['cases']]= array();
    $select[$table]= array();
//Fields
    array_push($select[$_SESSION['tablename']['cases']], "case_id", "case_label", "case_description", "case_typist", "case_creation_date");

//Where
    $where = " " . $_SESSION['tablename']['cases'] . ".case_id = " . $table . ".case_id";
    if(isset($_REQUEST['origin']) && $_REQUEST['origin'] == 'searching') {
        $where = $_SESSION['searching']['where_request'] . ' '. $where;
    }
   
    if(!empty($_SESSION['current_basket'])){
           $where .= " and (".$_SESSION['current_basket']['clause'].")";
    }
    
    
//Order
    $order = $order_field = '';
    $order = $list->getOrder();
    $order_field = $list->getOrderField();
    if (!empty($order_field) && !empty($order)) 
        $orderstr = "order by ".$order_field." ".$order;
    else  {
        $list->setOrder('asc');
        $list->setOrderField('case_label');
        $orderstr = "order by case_label asc";
    }

//Templates
$defaultTemplate = 'cases_list';
$selectedTemplate = $list->getTemplate();
if  (empty($selectedTemplate)) {
    if (!empty($defaultTemplate)) {
        $list->setTemplate($defaultTemplate);
        $selectedTemplate = $list->getTemplate();
    }
}
$template_list = array();
array_push($template_list, 'documents_list_with_attachments');
if($core_tools->is_module_loaded('cases')) array_push($template_list, 'cases_list');      
$arrayPDO = array();
//Request  
    if(!empty($_SESSION['current_basket'])){
        $tab = $request->PDOselect($select, $where . $where_concat, $arrayPDO, 'order by '. $_SESSION['tablename']['cases'] .'.case_id desc', $_SESSION['config']['databasetype'], "default", false, "", "", "", false, false, true);
    }else{
        $tab = $request->PDOselect($select, $where . $where_concat, $arrayPDO, 'order by '. $_SESSION['tablename']['cases'] .'.case_id desc', $_SESSION['config']['databasetype'], "default", false, "", "", "", true, false, true);

    }
    //$request->show();

    //Result array
for ($i=0;$i<count($tab);$i++)
{
	for ($j=0;$j<count($tab[$i]);$j++)
	{
		foreach(array_keys($tab[$i][$j]) as $value)
		{
			if($tab[$i][$j][$value]=='case_id')
			{
				$tab[$i][$j]['case_id']=$tab[$i][$j]['value'];
				$tab[$i][$j]["label"]=_NUM_CASE;
				$tab[$i][$j]["size"]="4";
				$tab[$i][$j]["label_align"]="left";
				$tab[$i][$j]["align"]="center";
				$tab[$i][$j]["valign"]="bottom";
				$tab[$i][$j]["show"]=true;
				$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
				$tab[$i][$j]["order"]='case_id'; 
			}
			if($tab[$i][$j][$value]=="case_label")
			{
				$tab[$i][$j]["label"]=_CASE_LABEL;
				$tab[$i][$j]['value'] = $request->show_string($tab[$i][$j]['value']);
				$tab[$i][$j]["size"]="15";
				$tab[$i][$j]["label_align"]="left";
				$tab[$i][$j]["align"]="left";
				$tab[$i][$j]["valign"]="bottom";
				$tab[$i][$j]["show"]=true;
				$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
				$tab[$i][$j]["order"]="case_label";
			}
			if($tab[$i][$j][$value]=="case_creation_date")
			{
				$tab[$i][$j]["label"]=_CASE_CREATION_DATE;
				$tab[$i][$j]['value'] = $request->format_date_db($tab[$i][$j]['value'], false);
				$tab[$i][$j]["size"]="5";
				$tab[$i][$j]["label_align"]="left";
				$tab[$i][$j]["align"]="left";
				$tab[$i][$j]["valign"]="bottom";
				$tab[$i][$j]["show"]=true;
				$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
				$tab[$i][$j]["order"]="case_creation_date";
			}
			if($tab[$i][$j][$value]=="case_closing_date")
			{
				$tab[$i][$j]["label"]=_CASE_CLOSING_DATE;
				
				if($tab[$i][$j]['value'] <> '')
					$tab[$i][$j]['value'] = "<b>("._CASE_CLOSED.")</b><br/>";
				else
					$tab[$i][$j]['value'] = '';
					
				$tab[$i][$j]["size"]="5";
				$tab[$i][$j]["label_align"]="left";
				$tab[$i][$j]["align"]="left";
				$tab[$i][$j]["valign"]="bottom";
				$tab[$i][$j]["show"]=false;
				$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
				$tab[$i][$j]["order"]="case_closing_date";
			}
			if($tab[$i][$j][$value]=="case_typist")
			{
				$tab[$i][$j]["label"]=_CASE_TYPIST;
				$tab[$i][$j]['value'] = $request->show_string($tab[$i][$j]['value']);
				$tab[$i][$j]["size"]="25";
				$tab[$i][$j]["label_align"]="left";
				$tab[$i][$j]["align"]="left";
				$tab[$i][$j]["valign"]="bottom";
				$tab[$i][$j]["show"]=true;
				$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
				$tab[$i][$j]["order"]="case_typist";
			}
			if($tab[$i][$j][$value]=="case_description")
			{
				$tab[$i][$j]["label"]=_CASE_DESCRIPTION;
				$tab[$i][$j]['value'] = $request->show_string($tab[$i][$j]['value']);
				$tab[$i][$j]["size"]="25";
				$tab[$i][$j]["label_align"]="left";
				$tab[$i][$j]["align"]="left";
				$tab[$i][$j]["valign"]="bottom";
				$tab[$i][$j]["show"]=false;
				$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
				$tab[$i][$j]["order"]="case_description";
			}
		}
	}
}

//Clé de la liste
$listKey = 'case_id';

//Initialiser le tableau de paramètres
$paramsTab = array();
$paramsTab['pageTitle'] =  _RESULTS." : ".count($tab).' '._FOUND_CASE;              //Titre de la page
$paramsTab['bool_sortColumn'] = true;                                               //Affichage Tri
$paramsTab['bool_bigPageTitle'] = false;                                            //Affichage du titre en grand
$paramsTab['urlParameters'] = 'baskets='.$_SESSION['current_basket']['id'];         //Parametres d'url supplementaires
if (count($template_list) >0 ) {                                                    //Templates
    $paramsTab['templates'] = array();
    $paramsTab['templates'] = $template_list;
}
$paramsTab['bool_showTemplateDefaultList'] = true;                                  //Default list (no template)
$paramsTab['defaultTemplate'] = $defaultTemplate;                                   //Default template
$paramsTab['tools'] = array();                                                      //Icones dans la barre d'outils
$export = array(
        "script"        =>  "window.open('".$_SESSION['config']['businessappurl']."index.php?display=true&page=export', '_blank');",
        "icon"          =>  'cloud-download',
        "tooltip"       =>  _EXPORT_LIST,
        "disabledRules" =>  count($tab)." == 0"
        );
array_push($paramsTab['tools'],$export);   

//Gerer manuellement les actions dans la sous liste
$paramsTab['defaultAction'] = $_SESSION['current_basket']['default_action'];
$paramsTab['collId'] =  $_SESSION['current_basket']['coll_id'];
$paramsTab['tableName'] = $_SESSION['current_basket']['table'];
$paramsTab['currentPageUrl'] = $_SESSION['current_basket']['page_no_frame']."&display=true";

//Afficher la liste
$status = 0;
$content = $list->showList($tab, $paramsTab, $listKey);
//$debug = $list->debug();
echo "{status : " . $status . ", content : '" . addslashes($debug.$content) . "', error : '" . addslashes($error) . "'}";
?>
