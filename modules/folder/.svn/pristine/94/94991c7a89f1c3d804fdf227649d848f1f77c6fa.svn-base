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
* @brief   Displays document list in search mode
*
* @file
* @author Yves Christian Kpakpo <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/

require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php";
require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php";
require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR
            ."class".DIRECTORY_SEPARATOR."class_contacts_v2.php";
require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_manage_status.php";
require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR
            ."class".DIRECTORY_SEPARATOR."class_lists.php";
            
$status_obj = new manage_status();
$sec        = new security();
$core_tools = new core_tools();
$request    = new request();
$contact    = new contacts_v2();
$list       = new lists();

//Include definition fields
include_once('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'definition_mail_categories.php');

//Init
if (isset($_REQUEST['coll_id']) && !empty($_REQUEST['coll_id']))
    $_SESSION['collId'] = $_REQUEST['coll_id'];

if (isset($_REQUEST['id']) && !empty($_REQUEST['id']))
    $_SESSION['folderId'] = $_REQUEST['id'];

//Table or view
    $view = $sec->retrieve_view_from_coll_id($_SESSION['collId'] );
    $select = array();
    $select[$view]= array();

//Fields
    //Documents
    array_push($select[$view],  "res_id", "status", "subject", "category_id", "category_id as category_img", 
                                "contact_firstname", "contact_lastname", "contact_society", 
                                "user_lastname", "user_firstname", "dest_user", "type_label", 
                                "creation_date", "entity_label", "exp_user_id");
//Cases fields
    if($core_tools->is_module_loaded("cases") == true) {
        array_push($select[$view], "case_id", "case_label", "case_description");
    }
//Where clause
    $where_tab = array();
    $where_tab[] = 'folders_system_id = ? ';
    $arrayPDO = array($_SESSION['folderId']);
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
    $tab=$request->PDOselect($select,$where,$arrayPDO,$orderstr,$_SESSION['config']['databasetype'],"default", false, "", "", "", $add_security);
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
                    $tab[$i][$j]["size"]="4";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=true;
                    $tab[$i][$j]["order"]='res_id';
                    $_SESSION['mlb_search_current_res_id'] = $tab[$i][$j]['value'];
                }
                if($tab[$i][$j][$value]=="creation_date")
                {
                    $tab[$i][$j]["value"]=$core_tools->format_date_db($tab[$i][$j]["value"], false);
                    $tab[$i][$j]["label"]=_CREATION_DATE;
                    $tab[$i][$j]["size"]="10";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=false;
                    $tab[$i][$j]["order"]='creation_date';
                }
                if($tab[$i][$j][$value]=="admission_date")
                {
                    $tab[$i][$j]["value"]=$core_tools->format_date_db($tab[$i][$j]["value"], false);
                    $tab[$i][$j]["label"]=_ADMISSION_DATE;
                    $tab[$i][$j]["size"]="10";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=false;
                    $tab[$i][$j]["order"]='admission_date';
                }
                if($tab[$i][$j][$value]=="process_limit_date")
                {
                    $tab[$i][$j]["value"]=$core_tools->format_date_db($tab[$i][$j]["value"], false);
                    $compareDate = "";
                    if($tab[$i][$j]["value"] <> "" && ($statusCmp == "NEW" || $statusCmp == "COU" || $statusCmp == "VAL" || $statusCmp == "RET"))
                    {
                        $compareDate = $core_tools->compare_date($tab[$i][$j]["value"], date("d-m-Y"));
                        if($compareDate == "date2")
                        {
                            $tab[$i][$j]["value"] = "<span style='color:red;'><b>".$tab[$i][$j]["value"]."<br><small>(".$core_tools->nbDaysBetween2Dates($tab[$i][$j]["value"], date("d-m-Y"))." "._DAYS.")<small></b></span>";
                        }
                        elseif($compareDate == "date1")
                        {
                            $tab[$i][$j]["value"] = $tab[$i][$j]["value"]."<br><small>(".$core_tools->nbDaysBetween2Dates(date("d-m-Y"), $tab[$i][$j]["value"])." "._DAYS.")<small>";
                        }
                        elseif($compareDate == "equal")
                        {
                            $tab[$i][$j]["value"] = "<span style='color:blue;'><b>".$tab[$i][$j]["value"]."<br><small>("._LAST_DAY.")<small></b></span>";
                        }
                    }
                    $tab[$i][$j]["label"]=_PROCESS_LIMIT_DATE;
                    $tab[$i][$j]["size"]="10";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=true;
                    $tab[$i][$j]["order"]='process_limit_date';
                }
                if($tab[$i][$j][$value]=="category_id")
                {
                    $_SESSION['mlb_search_current_category_id'] = $tab[$i][$j]["value"];
                    // $tab[$i][$j]["value"] = $_SESSION['mail_categories'][$tab[$i][$j]["value"]];
                    $tab[$i][$j]["label"]=_CATEGORY;
                    $tab[$i][$j]["size"]="10";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=true;
                    $tab[$i][$j]["order"]='category_id';
                }
                if($tab[$i][$j][$value]=="priority")
                {
                    $tab[$i][$j]["value"] = $_SESSION['mail_priorities'][$tab[$i][$j]["value"]];
                    $tab[$i][$j]["label"]=_PRIORITY;
                    $tab[$i][$j]["size"]="10";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=false;
                    $tab[$i][$j]["order"]='priority';
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
                    $tab[$i][$j]["show"]=true;
                    $tab[$i][$j]["order"]='category_id';
                }
                if($tab[$i][$j][$value]=="contact_firstname")
                {
                    $contact_firstname = $tab[$i][$j]["value"];
                    $tab[$i][$j]["show"]=false;
                }
                if($tab[$i][$j][$value]=="contact_lastname")
                {
                    $contact_lastname = $tab[$i][$j]["value"];
                    $tab[$i][$j]["show"]=false;
                }
                if($tab[$i][$j][$value]=="contact_society")
                {
                    $contact_society = $tab[$i][$j]["value"];
                    $tab[$i][$j]["show"]=false;
                }
                if($tab[$i][$j][$value]=="user_firstname")
                {
                    $user_firstname = $tab[$i][$j]["value"];
                    $tab[$i][$j]["show"]=false;
                }
                if($tab[$i][$j][$value]=="user_lastname")
                {
                    $user_lastname = $tab[$i][$j]["value"];
                    $tab[$i][$j]["show"]=false;
                }
                if($tab[$i][$j][$value]=="exp_user_id")
                {
                    $tab[$i][$j]["label"]=_CONTACT;
                    $tab[$i][$j]["size"]="10";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=false;
                    $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                    $tab[$i][$j]["value"] = $contact->get_contact_information_from_view($_SESSION['mlb_search_current_category_id'], $contact_lastname, $contact_firstname, $contact_society, $user_lastname, $user_firstname);
                    $tab[$i][$j]["order"]=false;
                }
                if($tab[$i][$j][$value]=="type_label")
                {
                    $tab[$i][$j]["value"] = $request->show_string($tab[$i][$j]["value"]);
                    $tab[$i][$j]["label"]=_TYPE;
                    $tab[$i][$j]["size"]="12";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=true;
                    $tab[$i][$j]["order"]='type_label';
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
                if($tab[$i][$j][$value]=="category_img")
                {
                    $tab[$i][$j]["label"]=_CATEGORY;
                    $tab[$i][$j]["size"]="10";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=false;
                    $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                    $my_imgcat = get_img_cat($tab[$i][$j]['value'],$extension_icon);
                    $tab[$i][$j]['value'] = $my_imgcat;
                    $tab[$i][$j]["value"] = $tab[$i][$j]['value'];
                    $tab[$i][$j]["order"]="category_id";
                }
                if($tab[$i][$j][$value]=="count_attachment")
                {
                    $tab[$i][$j]["label"]=_ATTACHMENTS;
                    $tab[$i][$j]["size"]="12";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=false;
                    $tab[$i][$j]["order"]='count_attachment';
                }
                if($tab[$i][$j][$value]=="case_id" && $core_tools->is_module_loaded("cases") == true)
                {
                    $tab[$i][$j]["label"]=_CASE_NUM;
                    $tab[$i][$j]["size"]="10";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=false;
                    $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                    $tab[$i][$j]["value"] = "<a href='".$_SESSION['config']['businessappurl']."index.php?page=details_cases&module=cases&id=".$tab[$i][$j]['value']."'>".$tab[$i][$j]['value']."</a>";
                    $tab[$i][$j]["order"]="case_id";
                }
                if($tab[$i][$j][$value]=="case_label" && $core_tools->is_module_loaded("cases") == true)
                {
                    $tab[$i][$j]["label"]=_CASE_LABEL;
                    $tab[$i][$j]["size"]="10";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=false;
                    $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                    $tab[$i][$j]["order"]="case_label";
                }
            }
        }
    }
 //Clé de la liste
$listKey = 'res_id';

//Initialiser le tableau de paramètres
$paramsTab = array();
// $paramsTab['bool_sortColumn'] = true;                                               //Affichage Tri
$paramsTab['bool_bigPageTitle'] = false;                                            //Affichage du titre en grand
// $paramsTab['bool_showIconDocument'] = true;                                         //Affichage de l'icone du document
// $paramsTab['bool_showIconDetails'] = true;                                          //Affichage de l'icone de la page de details
$paramsTab['urlParameters'] = 'coll_id='.$_SESSION['collId'].'&display=true';      //Parametres d'url supplementaires
$paramsTab['defaultTemplate'] = 'folder_documents_list';                            //Default template
$paramsTab['divListId'] = 'div_sublist_'.$_SESSION['folderId'];                     //Id du Div de retour ajax
$paramsTab['listHeight'] = '100%';                                                 //Hauteur de la liste
$paramsTab['bool_showSmallToolbar'] = true;                                         //
$paramsTab['linesToShow'] = 10;                                                     //
// $paramsTab['bool_checkBox'] = true;                                                 //Affichage Case à cocher
// $paramsTab['collId'] = $_SESSION['collId'];                                         //ID de la collection
// $paramsTab['tableName'] = $sec->retrieve_view_from_coll_id($_SESSION['collId']);    //Nom de la table
// $paramsTab['formId']= 'formSubList';                                                //

//output
$status = 0;
$listContent = $list->showList($tab, $paramsTab, $listKey);
$content = '<div id ="div_sublist_'.$_SESSION['folderId'].'">'.$listContent.'</div>';

// $debug = $list->debug();
echo "{status : " . $status . ", content : '" . addslashes($debug.$content) . "', error : '" . addslashes($error) . "'}";
exit ();
?>
