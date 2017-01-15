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
$sec        = new security();
$core_tools = new core_tools();
$request    = new request();
$list       = new lists();

//Templates
    $defaultTemplate = 'cases_list_search_adv';
    $selectedTemplate = $list->getTemplate();
    if  (empty($selectedTemplate)) {
        if (!empty($defaultTemplate)) {
            $list->setTemplate($defaultTemplate);
            $selectedTemplate = $list->getTemplate();
        }
    }
    $template_list = array();
    array_push($template_list, 'documents_list_search_adv');
    if($core_tools->is_module_loaded('cases')) array_push($template_list, 'cases_list_search_adv');

//Create sql request
 $_SESSION['collection_id_choice'] = 'letterbox_coll';
 $view = $sec->retrieve_view_from_coll_id($_SESSION['collection_id_choice'] );

$arrayPDO = array();

//Table or view
    $select = array();
    $select[$_SESSION['tablename']['cases']]= array();
    $select[$view]= array();
//Fields    
    array_push($select[$_SESSION['tablename']['cases']], "case_id", "case_label", "case_description", "case_typist", "case_creation_date");

//Where
    $where_tab = array();
   
    //From case join
    $where_tab[] = $_SESSION['tablename']['cases'] . ".case_id = " . $view . ".case_id";

    //From search
    if (!empty($_SESSION['searching']['where_request'])) {
        $where_tab[] = $_SESSION['searching']['where_request']. '(1=1)';
        $arrayPDO = array_merge($arrayPDO, $_SESSION['searching']['where_request_parameters']);
    }
    
    //From searching comp query
    if(isset($_SESSION['searching']['comp_query']) && trim($_SESSION['searching']['comp_query']) <> '') {

        $where_security = $sec->get_where_clause_from_coll_id($_SESSION['collection_id_choice']);

        if(count($where_tab) <> 0) {
            $where = implode(' and ', $where_tab);
            $where_request = '('.$where.') and (('.$where_security.') or ('.$_SESSION['searching']['comp_query'].'))';

        } else {
            $where_request = '('.$where_security.' or '.$_SESSION['searching']['comp_query'].')';
        }
        $add_security = false;
        
    } else {
        $status = $status_obj->get_not_searchable_status();   
        if(count($status) > 0) {    
            $status_tab = array();
            $status_str = '';
            for($i=0; $i<count($status);$i++){
                array_push($status_tab, ":".$status[$i]['ID']);
                $arrayPDO = array_merge($arrayPDO, array(":".$status[$i]['ID'] => $status[$i]['ID']));
            }
            $status_str = implode(' ,', $status_tab);
            $where_tab[] = "status not in (".$status_str.")";
        }

        $where_request = implode(' and ', $where_tab);
        $add_security = true;
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
        $orderstr = "order by ". $_SESSION['tablename']['cases'].".case_label asc";
    }
    
//Query       
    $tab=$request->PDOselect($select,$where_request, $arrayPDO, $orderstr,$_SESSION['config']['databasetype'], "default", false, "", "", "", $add_security, false, true);
    //$request->show();
//Result
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

if (count($tab) > 0) {
    
    //Clé de la liste
    $listKey = 'case_id';

    //Initialiser le tableau de paramètres
    $paramsTab = array();
    $paramsTab['bool_modeReturn'] = false;                                                  //Desactivation du mode return (vs echo)
    $paramsTab['pageTitle'] =  _RESULTS." : ".count($tab).' '._FOUND_CASE;                  //Titre de la page
    $paramsTab['pagePicto'] = 'search'; 
    $paramsTab['bool_sortColumn'] = true;                                                   //Affichage Tri
    $paramsTab['defaultTemplate'] = 'cases_list_search_adv';  
    if (count($template_list) >0 ) {                                    //Templates
        $paramsTab['templates'] = array();
        $paramsTab['templates'] = $template_list;
    }                              //Default template
    $paramsTab['bool_showTemplateDefaultList'] = true;                                      //Default list (no template)
    $paramsTab['tools'] = array();                                                          //Icones dans la barre d'outils
    $export = array(
            "script"        =>  "window.open('".$_SESSION['config']['businessappurl']."index.php?display=true&page=export', '_blank');",
            "icon"          =>  'cloud-download',
            "tooltip"       =>  _EXPORT_LIST,
            "disabledRules" =>  count($tab)." == 0"
            );
    array_push($paramsTab['tools'],$export);   

    // Gerer manuellement les actions dans la sous liste
    $paramsTab['defaultAction'] = $_SESSION['current_basket']['default_action'];
    $paramsTab['collId'] =  $_SESSION['current_basket']['coll_id'];
    $paramsTab['tableName'] = $_SESSION['current_basket']['table'];
    $paramsTab['currentPageUrl'] = $_SESSION['current_basket']['page_no_frame']."&display=true";

    // Process instructions
    $paramsTab['processInstructions'] = _CLICK_ICON_TO_TOGGLE.'  <i class="fa fa-arrow-down fa-2x"></i>';
    
    //Afficher la liste
    $list->showList($tab, $paramsTab, $listKey);
    // $list->debug();
} else {

    //error and search url
    $url_error = $_SESSION['config']['businessappurl']
        .'index.php?dir=indexing_searching'
        .'&page=search_adv_error';
    
    $url_search = $_SESSION['config']['businessappurl']
        .'index.php?dir=indexing_searching'
        .'&page=search_adv'.$urlParameters;
    
    //error
    $_SESSION['error_search'] = '<p class="error"><i class="fa fa-remove fa-2x"></i><br />'
        ._NO_RESULTS.'</p><br/><br/><div align="center"><strong><a href="javascript://" '
        .' onclick = "window.top.location.href=\''.$url_search.'\'">'._MAKE_NEW_SEARCH.'</a></strong></div>'; 
    
    echo '<script type="text/javascript">window.top.location.href=\''.$url_error.'\';</script>';
}
?>
