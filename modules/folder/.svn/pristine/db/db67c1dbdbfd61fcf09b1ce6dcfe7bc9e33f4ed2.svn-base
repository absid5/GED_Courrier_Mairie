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
* @brief    Displays documents list in details folder tree
*
* @file     list_doc.php
* @author   Yves Christian Kpakpo <dev@maarch.org>
* @date     $date$
* @version  $Revision$
* @ingroup  folder
*/

require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php";
require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php";
require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_manage_status.php";
require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR
            ."class".DIRECTORY_SEPARATOR."class_lists.php";
            
$security   = new security();
$core_tools = new core_tools();
$request    = new request();
$status_obj = new manage_status();
$list       = new lists();

if(isset($_REQUEST['listid']) && $_REQUEST['listid'] <> "") {

    //Table or view
        $view = $security->retrieve_view_from_coll_id($_SESSION['current_foldertype_coll_id'] );
        $select = array();
        $select[$view]= array();

    //Fields
        array_push($select[$view],"res_id", "status", "type_label", "category_id", "subject", "creation_date");

    //Where clause
        $where_tab = array();
        //From tree
        $where_tab[] = 'res_id in (?)';
        $arrayPDO = array($_REQUEST['listid']);
        //From security
        foreach (array_keys($_SESSION['user']['security']) as $collId) {
            if($collId == $_SESSION['current_foldertype_coll_id']) {
                $where_tab[] = '(' . $_SESSION['user']['security'][$collId]['DOC']['where'] . ')';
                break;
            }
        }
        //Build where
        $where = implode(' and ', $where_tab);

        //Order
        $order = $order_field = '';
        $order = $list->getOrder();
        $order_field = $list->getOrderField();
        if (!empty($order_field) && !empty($order)) 
            $orderstr = "order by ".$order_field." ".$order;
        else  {
            $list->setOrder();
            $list->setOrderField('creation_date');
            $orderstr = "order by creation_date desc";
        }
        
    //Query    
        $tab=$request->PDOselect($select,$where, $arrayPDO, $orderstr,$_SESSION['config']['databasetype'],"default", false, "", "", "", true);
        // $request->show();
        
    //Result Array
        for ($i=0;$i<count($tab);$i++)
        {
            for ($j=0;$j<count($tab[$i]);$j++)
            {
                foreach(array_keys($tab[$i][$j]) as $value)
                {
                    if($tab[$i][$j][$value]=="res_id")
                    {
                        $tab[$i][$j]["res_id"]=$tab[$i][$j]['value'];
                        $tab[$i][$j]["label"]=_GED_NUM;
                        $tab[$i][$j]["size"]="1";
                        $tab[$i][$j]["label_align"]="left";
                        $tab[$i][$j]["align"]="left";
                        $tab[$i][$j]["valign"]="bottom";
                        $tab[$i][$j]["show"]=true;
                        $tab[$i][$j]["order"]='res_id';
                        $_SESSION['mlb_search_current_res_id'] = $tab[$i][$j]['value'];
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
                    }
                    if($tab[$i][$j][$value]=="category_id")
                    {
                        $_SESSION['mlb_search_current_category_id'] = $tab[$i][$j]["value"];
                        $tab[$i][$j]["value"] = $_SESSION['mail_categories'][$tab[$i][$j]["value"]];
                        $tab[$i][$j]["label"]=_CATEGORY;
                        $tab[$i][$j]["size"]="10";
                        $tab[$i][$j]["label_align"]="left";
                        $tab[$i][$j]["align"]="left";
                        $tab[$i][$j]["valign"]="bottom";
                        $tab[$i][$j]["show"]=false;
                        $tab[$i][$j]["order"]='category_id';
                    }
                    if($tab[$i][$j][$value]=="type_label")
                    {
                        $tab[$i][$j]["value"] = $request->show_string($tab[$i][$j]["value"]);
                        $tab[$i][$j]["label"]=_TYPE;
                        $tab[$i][$j]["size"]="10";
                        $tab[$i][$j]["label_align"]="left";
                        $tab[$i][$j]["align"]="left";
                        $tab[$i][$j]["valign"]="bottom";
                        $tab[$i][$j]["show"]=false;
                        $tab[$i][$j]["order"]='type_label';
                    }
                    if($tab[$i][$j][$value]=="creation_date")
                    {
                        $tab[$i][$j]["value"]=$core_tools->format_date_db($tab[$i][$j]["value"], false);
                        $tab[$i][$j]["label"]=_CREATION_DATE;
                        $tab[$i][$j]["size"]="10";
                        $tab[$i][$j]["label_align"]="left";
                        $tab[$i][$j]["align"]="left";
                        $tab[$i][$j]["valign"]="bottom";
                        $tab[$i][$j]["show"]=true;
                        $tab[$i][$j]["order"]='creation_date';
                    }
                }
            }
        }
        
    //List
        $listKey = 'res_id';                                                                //Clé de la liste
        $paramsTab = array();                                                               //Initialiser le tableau de paramètres
        $paramsTab['bool_sortColumn'] = true;                                               //Affichage Tri
        $paramsTab['pageTitle'] = count($tab).' '._FOUND_DOC;                               //Titre de la page
        $paramsTab['bool_bigPageTitle'] = false;                                            //Affichage du titre en grand
        $paramsTab['bool_showIconDocument'] = true;                                         //Affichage de l'icone du document
        $paramsTab['bool_showIconDetails'] = true;                                          //Affichage de l'icone de la page de details
        $details_page = $security->get_script_from_coll($_SESSION['current_foldertype_coll_id'], 'script_details');
        $paramsTab['viewDetailsLink'] = 'index.php?page=' . str_replace(".php", '', $details_page) 
            . '&dir=indexing_searching';       //Link to the details page
        $paramsTab['urlParameters'] = 'listid='.$_REQUEST['listid'].'&display=true';        //Parametres d'url supplementaires
        // $paramsTab['listHeight'] = '200px';                                                 //Hauteur de la liste
        $paramsTab['bool_showSmallToolbar'] = true;                                         //Mini barre d'outils
        $paramsTab['linesToShow'] = 15;                                                     //Nombre de ligne a afficher
        $paramsTab['listCss'] = 'listingsmall';
        
        //Output
        $status = 0;
        $content = $list->showList($tab, $paramsTab, $listKey);
        // $debug = $list->debug();
       
 }
 
 echo "{status : " . $status . ", content : '" . addslashes($debug.$content) . "', error : '" . addslashes($error) . "'}";
?>
