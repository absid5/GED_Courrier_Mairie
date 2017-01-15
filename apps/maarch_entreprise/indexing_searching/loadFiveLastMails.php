<?php
/*
*
*   Copyright 2008,2015 Maarch
*
*   This file is part of Maarch Framework.
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
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief   Displays Five last documents
*
* @file
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/
require_once 'core/class/class_request.php';
require_once 'core/class/class_security.php';
require_once 'apps/' . $_SESSION['config']['app_id'] . '/class/class_contacts_v2.php';
require_once 'core/class/class_manage_status.php';
require_once 'apps/' . $_SESSION['config']['app_id'] . '/class/class_lists.php';
            
$status_obj = new manage_status();
$security   = new security();
$core_tools = new core_tools();
$request    = new request();
$contact    = new contacts_v2();
$list       = new lists();

//Include definition fields
include_once('apps/' . $_SESSION['config']['app_id'] . '/definition_mail_categories.php');

//Keep some parameters
$parameters = '';

//URL extra parameters
$urlParameters = '';

$db = new Database();

$stmt = $db->query("SELECT ir.record_id as res_id, ir.subject, ir.doc_date, ir.event_date, ir.creation_date, ir.alt_identifier 
FROM
(SELECT DISTINCT ON (h.record_id) h.record_id, h.event_date, r.subject, r.doc_date, r.creation_date, r.alt_identifier FROM history h, res_view_letterbox r
  WHERE h.user_id = ? 
  AND event_id !='linkup'
  AND (h.table_name='res_letterbox' OR h.table_name='res_view_letterbox')
  AND h.record_id <> 'none'
  AND CAST(h.record_id AS INT) = r.res_id
  AND r.status <> 'DEL'
  ORDER BY h.record_id, h.event_date desc) AS ir
ORDER BY ir.event_date desc 
LIMIT 5", array($_SESSION['user']['UserId']));
$i=0;
$j=0;
$x=0;
while($result=$stmt->fetch(PDO::FETCH_ASSOC)){
	$j=0; $x=0;
    foreach ($result as $key => $value) {
    	//
        // if($j%2){

            $tab[$i][$x]['column']=$key; 
            $tab[$i][$x]['value']=functions::xssafe($value);
            $x++;
        // }
        $j++;
    }
    $i++;
}

//var_dump($tab);

$defaultTemplate = 'welcome_five_courriers';
$selectedTemplate = $list->getTemplate();
if  (empty($selectedTemplate)) {
    if (!empty($defaultTemplate)) {
        $list->setTemplate($defaultTemplate);
        $selectedTemplate = $list->getTemplate();
    }
}
$template_list = array();
array_push($template_list, 'welcome_five_courriers');
if($core_tools->is_module_loaded('cases')) array_push($template_list, 'cases_list');

//For status icon
$extension_icon = '';
if($selectedTemplate <> 'none') $extension_icon = "_big"; 

//Result Array

for ($i=0;$i<count($tab);$i++)
{
    for ($j=0;$j<count($tab[$i]);$j++)
    {
    	//print_r(array_keys($tab[$i][$j]));
	        foreach(array_keys($tab[$i][$j]) as $value)
	        {
	            if($tab[$i][$j][$value]=="res_id")
	            {
	                $tab[$i][$j]["res_id"]=$tab[$i][$j]['value'];
	                $res_id=$tab[$i][$j]["res_id"];
	                $tab[$i][$j]["label"]=_GED_NUM;
	                $tab[$i][$j]["size"]="4";
	                $tab[$i][$j]["label_align"]="left";
	                $tab[$i][$j]["align"]="left";
	                $tab[$i][$j]["valign"]="bottom";
	                $tab[$i][$j]["show"]=true;
	                $tab[$i][$j]["order"]='res_id';
	                $_SESSION['mlb_search_current_res_id'] = $tab[$i][$j]['value'];
					//echo 'SESSION: '.$_SESSION['mlb_search_current_res_id']."\n";
					// notes
					
					
	            }
	            if($tab[$i][$j][$value]=="creation_date")
	            {
	                $tab[$i][$j]["value"]=$core_tools->format_date_db($tab[$i][$j]["value"], false, '', true);
	                $tab[$i][$j]["value"]= substr($tab[$i][$j]["value"], 0, 10);
	                $tab[$i][$j]["label"]=_CREATION_DATE;
	                $tab[$i][$j]["size"]="10";
	                $tab[$i][$j]["label_align"]="left";
	                $tab[$i][$j]["align"]="left";
	                $tab[$i][$j]["valign"]="bottom";
	                $tab[$i][$j]["show"]=true;
	                $tab[$i][$j]["order"]='creation_date';
	                //echo 'creation_date: '.$tab[$i][$j]["value"]."\n";
	            }
	            if($tab[$i][$j][$value]=="alt_identifier")
	            {
	        		
	                $tab[$i][$j]["value"]=$tab[$i][$j]['value'];
	                $tab[$i][$j]["label"]=_CHRONO_NUMBER;
	                $tab[$i][$j]["size"]="10";
	                $tab[$i][$j]["label_align"]="left";
	                $tab[$i][$j]["align"]="left";
	                $tab[$i][$j]["valign"]="bottom";
	                $tab[$i][$j]["show"]=true;
	                $tab[$i][$j]["order"]='admission_date';
	                //echo 'order: '.$tab[$i][$j]["value"]."\n";
	            }
	            
	            if($tab[$i][$j][$value]=="subject")
	            {
	                $tab[$i][$j]["value"] = $request->cut_string($request->show_string($tab[$i][$j]["value"]), 250);
	                $tab[$i][$j]["label"]=_SUBJECT;
	                $tab[$i][$j]["size"]="12";
	                $tab[$i][$j]["label_align"]="left";
	                $tab[$i][$j]["align"]="left";
	                $tab[$i][$j]["valign"]="bottom";
	                $tab[$i][$j]["show"]=true;
	                $tab[$i][$j]["order"]='subject';
	                //echo 'subject: '.$tab[$i][$j]["value"]."\n";
	            }
	                   
	        }
       	
    }
}
//Clé de la liste
$listKey = 'res_id';

//Initialiser le tableau de paramètres
$paramsTab = array();
$paramsTab['bool_showToolbar'] = false;

$paramsTab['tools'] = array();                                                      //Icones dans la barre d'outils


//Afficher la liste
$status = 0;
$content = $list->showList($tab, $paramsTab, $listKey, $_SESSION['current_basket']);
// $debug = $list->debug(false);

$content .= "<script>$$('#container')[0].setAttribute('style', 'width: auto; min-width: 1000px;');".
                    "$$('#content')[0].setAttribute('style', 'width: auto; min-width: 1000px;');".
                    "$$('#inner_content')[0].setAttribute('style', 'width: auto; min-width: 1000px;');".
                    // "$$('table#extended_list')[0].setAttribute('style', 'width: 100%; min-width: 900px; margin: 0;');".
            "</script>";

echo "{'status' : " . $status . ", 'content' : '" . addslashes($debug.$content) . "', 'error' : '" . addslashes($error) . "'}";
