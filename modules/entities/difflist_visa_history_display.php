<?php
/*
*
*    Copyright 2015 Maarch
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
* @brief   Displays diffusion list history 
*
* @file
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/

require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php";
require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php";
require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_manage_status.php";
require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR
            ."class".DIRECTORY_SEPARATOR."class_lists.php";
 
$sec        = new security();
$core_tools = new core_tools();
$request    = new request();
$list       = new lists();

//Include definition fields
// include_once('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'definition_mail_categories.php');

//Parameters
$urlParameters = '';

//Start
    
    //Templates
    $defaultTemplate = 'history_list_diff';
    $selectedTemplate = $list->getTemplate();
    if  (empty($selectedTemplate)) {
        if (!empty($defaultTemplate)) {
            $list->setTemplate($defaultTemplate);
            $selectedTemplate = $list->getTemplate();
        }
    }
    $template_list = array();
    array_push($template_list, 'history_list_diff');
    
    //For status icon
    $extension_icon = '';
    if($selectedTemplate <> 'none') $extension_icon = "_big";

/************Construction de la requete*******************/
//Table or view
    $view = "listinstance_history";
    $select = array();
    $select[$view]= array();

//Fields
    //Documents
    array_push($select[$view],  "listinstance_history_id", "res_id", "updated_by_user", "updated_date");

//Where clause
    $where_tab = array(); 
    $where_tab[] = "res_id = ?";
    $where_tab[] = "coll_id = ?";
	$arrayPDO = array();
	
	if (isset($diffListType)) {
			$where_tab[] = "listinstance_history_id IN (SELECT DISTINCT listinstance_history_id from listinstance_history_details where difflist_type = '$diffListType')"; 
    }

//From searching comp query
    $where_request = implode(' and ', $where_tab);
    $arrayPDO = array($s_id,$coll_id);
//Order
    $order = $order_field = '';
    $order = $list->getOrder();
    $order_field = $list->getOrderField();
    // $_SESSION['save_list']['order'] = $order;
    // $_SESSION['save_list']['order_field'] = $order_field;

    if (!empty($order_field) && !empty($order)) 
        $orderstr = "order by ".$order_field." ".$order;
    else  {
        $list->setOrder();
        $list->setOrderField('updated_date');
        $orderstr = "order by updated_date desc";
    }
    
//URL extra Parameters  
    $parameters = '';
    $start = $list->getStart();
    if (!empty($selectedTemplate)) $parameters .= '&template='.$selectedTemplate;
    if (!empty($start)) $parameters .= '&start='.$start;
    // $_SESSION['save_list']['start'] = $start;

//Query
    $tab=$request->PDOselect($select,$where_request,$arrayPDO,$orderstr,$_SESSION['config']['databasetype'],"default", false, "", "", "", $add_security);
    // $request->show();
    
//Result array
    for ($i=0;$i<count($tab);$i++)
    {
        for ($j=0;$j<count($tab[$i]);$j++)
        {
            foreach(array_keys($tab[$i][$j]) as $value)
            { 
                if($tab[$i][$j][$value]=="listinstance_history_id")
                {
                    $tab[$i][$j]["label"]=_TYPE_ID_HISTORY;
                    $tab[$i][$j]['value'] = functions::show_string($tab[$i][$j]['value']);
                    $tab[$i][$j]["size"]="15";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=true;
                    $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                    $tab[$i][$j]["order"]="listinstance_history_id";
                }

                if($tab[$i][$j][$value]=="res_id")
                {
                    $tab[$i][$j]["label"]=_RES_ID;
                    $tab[$i][$j]['value'] = functions::show_string($tab[$i][$j]['value']);
                    $tab[$i][$j]["size"]="15";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=true;
                    $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                    $tab[$i][$j]["order"]="res_id";
                }

                if($tab[$i][$j][$value]=="updated_by_user")
                {
                    $tab[$i][$j]["label"]=_UPDATED_BY_USER;
                    $db = new Database();
                    $stmt = $db->query("SELECT firstname, lastname FROM users WHERE user_id = ?",array($tab[$i][$j]['value']));
                    $user = $stmt->fetchObject();
                    $tab[$i][$j]['value'] =  ucwords($user->lastname) . " " . functions::show_string(ucfirst($user->firstname));
                    $tab[$i][$j]["size"]="15";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=true;
                    $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                    $tab[$i][$j]["order"]="updated_by_user";
                }

                if($tab[$i][$j][$value]=="updated_date")
                {
                    $tab[$i][$j]["label"]=__UPDATED_DATE;
                    $tab[$i][$j]['value'] = $core_tools->format_date_db($tab[$i][$j]['value']);
                    $tab[$i][$j]["size"]="15";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=true;
                    $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                    $tab[$i][$j]["order"]="updated_date";
                }
                
            }
        }
    }

if (count($tab) > 0) {
/************Construction de la liste*******************/
    //Clé de la liste
    $listKey = 'listinstance_history_id';

    //Initialiser le tableau de paramètres
    $paramsTab = array();
    if (isset($return_mode) && $return_mode) {
        $paramsTab['bool_modeReturn'] = true;                                   //Desactivation du mode return (vs echo)
    } else {
        $paramsTab['bool_modeReturn'] = false;                                   //Desactivation du mode return (vs echo)
    }

    $paramsTab['listCss'] = 'listing largerList spec';                       //css
    $paramsTab['bool_showTemplateDefaultList'] = false;                      //Default list (no template)

    //Form attributs
    //Standalone form
    $paramsTab['bool_standaloneForm'] = true;   

    //Afficher la liste dans process
    if (isset($return_mode) && $return_mode) {
        $frm_str .= $list->showList($tab, $paramsTab, $listKey);
    } else {
        $list->showList($tab, $paramsTab, $listKey);
    }
} else {
    if (isset($return_mode) && $return_mode) {
        $frm_str .= '<br/>';
        $frm_str .= _DIFFLIST_NEVER_MODIFIED;
    } else {
        echo '<br/>' . _DIFFLIST_NEVER_MODIFIED;
    }    
}
    
    // $list->debug();
