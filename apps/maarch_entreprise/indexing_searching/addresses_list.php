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
* @brief    Contacts list of the current user
*
* @file     my_contacts.php
* @author   Yves Christian Kpakpo <dev@maarch.org>
* @date     $date$
* @version  $Revision$
* @ingroup  apps
*/

require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php";
require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR
            ."class".DIRECTORY_SEPARATOR."class_lists.php";
require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR
            ."class".DIRECTORY_SEPARATOR."class_list_show.php";
require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR
            ."class".DIRECTORY_SEPARATOR."class_contacts_v2.php";

$core_tools = new core_tools();
$request    = new request();
$list       = new lists();   
$contact    = new contacts_v2();
$db         = new Database();

 $parameters = '';
 if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) $parameters .= '&order='.$_REQUEST['order'];
 if (isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field'])) $parameters .= '&order_field='.$_REQUEST['order_field'];
 if (isset($_REQUEST['what']) && !empty($_REQUEST['what'])) $parameters .= '&what='.$_REQUEST['what'];
 if (isset($_REQUEST['selectedObject']) && !empty($_REQUEST['selectedObject'])) $parameters .= '&selectedObject='.$_REQUEST['selectedObject'];
 if (isset($_REQUEST['start']) && !empty($_REQUEST['start'])) $parameters .= '&start='.$_REQUEST['start'];
 if (isset($_REQUEST['mode']) && !empty($_REQUEST['mode'])) $parameters .= '&mode='.$_REQUEST['mode'];

 $_SESSION['origin']="contacts_list";

$return = $core_tools->test_admin('admin_contacts', 'apps', false);
if (!$return) {
    $return = $core_tools->test_admin('search_contacts', 'apps');
}

 if (isset($_REQUEST['load'])) {
    $_SESSION['m_admin'] = array();

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
    $page_path = $_SESSION['config']['businessappurl'].'index.php?page=addresses_list&dir=indexing_searching&load';
    $page_label = _ADDRESSES_LIST;
    $page_id = "addresses_list";
    $core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
    /***********************************************************/
    ?>
    <div id="inner_content">
    <?php    
    //Load list
    $target = $_SESSION['config']['businessappurl'].'index.php?page=addresses_list&dir=indexing_searching'.$parameters;
    $listContent = $list->loadList($target, true, 'divList', 'false');
    echo $listContent;
    ?>
    </div>
    <?php
} else {
    //Table
    $select["view_contacts"] = array();
    array_push(
        $select["view_contacts"],
        "ca_id", "contact_id", "society", "contact_purpose_id", "is_private", "departement
    , case when view_contacts.contact_lastname <> '' then view_contacts.contact_lastname else view_contacts.lastname end as \"lastname\"
    , case when view_contacts.contact_firstname <> '' then view_contacts.contact_firstname else view_contacts.firstname end as \"firstname\"
    , case when view_contacts.contact_function <> '' then view_contacts.contact_function else view_contacts.function end as \"function\""
    , "address_town", "phone", "email", "contact_purpose_label"
    );
    $what = "";
    $where = "";

    $arrayPDO = array();
    if (isset($_REQUEST['selectedObject']) && ! empty($_REQUEST['selectedObject'])) {
        $where .= " ca_id = ? ";
        $arrayPDO = array_merge($arrayPDO, array($_REQUEST['selectedObject']));
    } elseif (isset($_REQUEST['what']) && ! empty($_REQUEST['what'])) {
        $what = $_REQUEST['what'];

        $what = str_replace("  ", "", $_REQUEST['what']);
        $what_table = explode(" ", $what);

        foreach($what_table as $key => $what_a){
            $sql_lastname[] = " lower(lastname) LIKE lower(:what_".$key.")";
            $sql_firstname[] = " lower(firstname) LIKE lower(:what_".$key.")";
            $sql_society[] = " lower(departement) LIKE lower(:what_".$key.")";
            $sql_contact_firstname[] = " lower(contact_firstname) LIKE lower(:what_".$key.")";
            $sql_contact_lastname[] = " lower(contact_lastname) LIKE lower(:what_".$key.")";
            $arrayPDO = array_merge($arrayPDO, array(":what_".$key => $what_a."%"));
        }

        $where .= " (" . implode(' OR ', $sql_lastname) . " ";
        $where .= " or " . implode(' OR ', $sql_firstname) . " ";
        $where .= " or " . implode(' OR ', $sql_society) . " ";
        $where .= " or " . implode(' OR ', $sql_contact_firstname) . " ";
        $where .= " or " . implode(' OR ', $sql_contact_lastname) . ") ";
    }

    $list_show = new list_show();

    $order = 'asc';
    if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {
        $order = trim($_REQUEST['order']);
    }

    //use to pass the next condition in order_field. Then we need to delete them.
    array_push(
        $select["view_contacts"],
        "lastname", "firstname", "function"
    );

    $field = 'contact_purpose_id';
    if (isset($_REQUEST['order_field']) && ! empty($_REQUEST['order_field']) && in_array($_REQUEST['order_field'], $select["view_contacts"])) {
        $field = trim($_REQUEST['order_field']);
    }

    array_pop($select["view_contacts"]);
    array_pop($select["view_contacts"]);
    array_pop($select["view_contacts"]);

    $orderstr = $list_show->define_order($order, $field);

    $request = new request;
    $tab = $request->PDOselect(
        $select, $where, $arrayPDO, $orderstr, $_SESSION['config']['databasetype']
    );
    // $request->show();

    for ($i = 0; $i < count($tab); $i ++) {
        for ($j = 0; $j < count($tab[$i]); $j ++) {
            foreach (array_keys($tab[$i][$j]) as $value) {
                if ($tab[$i][$j][$value] == "ca_id") {
                    $tab[$i][$j]["id"] = $tab[$i][$j]['value'];
                    $tab[$i][$j]["label"] = _ID;
                    $tab[$i][$j]["size"] = "30";
                    $tab[$i][$j]["label_align"] = "left";
                    $tab[$i][$j]["align"] = "left";
                    $tab[$i][$j]["valign"] = "bottom";
                    $tab[$i][$j]["show"] = false;
                    $tab[$i][$j]["order"] = 'id';
                }
                if ($tab[$i][$j][$value] == "contact_id") {
                    $tab[$i][$j]["contact_id"] = $tab[$i][$j]['value'];
                    $tab[$i][$j]["label"] = _CONTACT_ID;
                    $tab[$i][$j]["size"] = "30";
                    $tab[$i][$j]["label_align"] = "left";
                    $tab[$i][$j]["align"] = "left";
                    $tab[$i][$j]["valign"] = "bottom";
                    $tab[$i][$j]["show"] = false;
                    $tab[$i][$j]["order"] = 'contact_id';
                }
                if ($tab[$i][$j][$value] == "society") {
                    $tab[$i][$j]["society"] = $tab[$i][$j]['value'];
                    $tab[$i][$j]["label"] = _STRUCTURE_ORGANISM;
                    $tab[$i][$j]["size"] = "30";
                    $tab[$i][$j]["label_align"] = "left";
                    $tab[$i][$j]["align"] = "left";
                    $tab[$i][$j]["valign"] = "bottom";
                    $tab[$i][$j]["show"] = true;
                    $tab[$i][$j]["order"] = 'society';
                }
                if ($tab[$i][$j][$value] == "contact_purpose_id") {
                    $tab[$i][$j]["value"]= $contact->get_label_contact($tab[$i][$j]['value'], $_SESSION['tablename']['contact_purposes']);
                    $tab[$i][$j]["contact_purpose_id"] = $tab[$i][$j]['value'];
                    $tab[$i][$j]["label"] = _CONTACT_PURPOSE;
                    $tab[$i][$j]["size"] = "20";
                    $tab[$i][$j]["label_align"] = "left";
                    $tab[$i][$j]["align"] = "left";
                    $tab[$i][$j]["valign"] = "bottom";
                    $tab[$i][$j]["show"] = true;
                    $tab[$i][$j]["order"] = 'contact_purpose_label';
                }
	            if($tab[$i][$j][$value]=="is_private")
	            {
	                $is_private = $tab[$i][$j]['value'];
	                $tab[$i][$j]["show"]=false;
	            }
                if ($tab[$i][$j][$value] == "departement") {
	                if ($is_private == "Y") {
	                    $tab[$i][$j]['value'] = "Confidentiel";
	                } else {
	                    $tab[$i][$j]['value'] = $request->show_string($tab[$i][$j]['value']);                   
	                }

                    $tab[$i][$j]["departement"] = $tab[$i][$j]['value'];
                    $tab[$i][$j]["label"] = _SERVICE;
                    $tab[$i][$j]["size"] = "20";
                    $tab[$i][$j]["label_align"] = "left";
                    $tab[$i][$j]["align"] = "left";
                    $tab[$i][$j]["valign"] = "bottom";
                    $tab[$i][$j]["show"] = true;
                    $tab[$i][$j]["order"] = 'departement';
                }
                if($tab[$i][$j][$value]=="lastname")
                {
                    $tab[$i][$j]['value']=$request->show_string($tab[$i][$j]['value']);
                    $tab[$i][$j]["lastname"]=$tab[$i][$j]['value'];
                    $tab[$i][$j]["label"]=_LASTNAME;
                    $tab[$i][$j]["size"]="15";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=true;
                    $tab[$i][$j]["order"]= "lastname";
                }
                if($tab[$i][$j][$value]=="firstname")
                {
                    $tab[$i][$j]["firstname"]= $request->show_string($tab[$i][$j]['value']);
                    $tab[$i][$j]["label"]=_FIRSTNAME;
                    $tab[$i][$j]["size"]="15";
                    $tab[$i][$j]["label_align"]="center";
                    $tab[$i][$j]["align"]="center";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=true;
                    $tab[$i][$j]["order"]= "firstname";
                }
                if($tab[$i][$j][$value]=="function")
                {
                    $tab[$i][$j]['value']=$request->show_string($tab[$i][$j]['value']);
                    $tab[$i][$j]["function"]=$tab[$i][$j]['value'];
                    $tab[$i][$j]["label"]=_FUNCTION;
                    $tab[$i][$j]["size"]="15";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=true;
                    $tab[$i][$j]["order"]= "function";
                }
                if($tab[$i][$j][$value]=="address_town")
                {
	                if ($is_private == "Y") {
	                    $tab[$i][$j]['value'] = "Confidentiel";
	                } else {
	                    $tab[$i][$j]['value'] = $request->show_string($tab[$i][$j]['value']);                   
	                }
                    $tab[$i][$j]["address_town"]= $tab[$i][$j]['value'];
                    $tab[$i][$j]["label"]=_TOWN;
                    $tab[$i][$j]["size"]="10";
                    $tab[$i][$j]["label_align"]="center";
                    $tab[$i][$j]["align"]="center";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=true;
                    $tab[$i][$j]["order"]= "address_town";
                }
                if($tab[$i][$j][$value]=="phone")
                {
                    $tab[$i][$j]['value']=$request->show_string($tab[$i][$j]['value']);
                    $tab[$i][$j]["phone"]=$tab[$i][$j]['value'];
                    $tab[$i][$j]["label"]=_PHONE;
                    $tab[$i][$j]["size"]="15";
                    $tab[$i][$j]["label_align"]="left";
                    $tab[$i][$j]["align"]="left";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=false;
                    $tab[$i][$j]["order"]= "phone";
                }
                if($tab[$i][$j][$value]=="email")
                {
                    $tab[$i][$j]["email"]= $request->show_string($tab[$i][$j]['value']);
                    $tab[$i][$j]["label"]=_MAIL;
                    $tab[$i][$j]["size"]="15";
                    $tab[$i][$j]["label_align"]="center";
                    $tab[$i][$j]["align"]="center";
                    $tab[$i][$j]["valign"]="bottom";
                    $tab[$i][$j]["show"]=false;
                    $tab[$i][$j]["order"]= "email";
                }
            }
        }
    }

    //List parameters
    $paramsTab = array();
    $paramsTab['bool_modeReturn'] = false;                                              //Desactivation du mode return (vs echo)
    $paramsTab['pageTitle'] =  _ADDRESSES_LIST." : ".count($tab)." "._ADDRESSES;           //Titre de la page
    $paramsTab['urlParameters'] = '&dir=indexing_searching';                                   //parametre d'url supplementaire
    if ($_REQUEST['mode'] == 'search') {
        $paramsTab['urlParameters'] .= "&mode=search";
    } 
    $paramsTab['pagePicto'] = 'street-view';                                //Image (pictogramme) de la page
    $paramsTab['bool_sortColumn'] = true;                                               //Affichage Tri
    $paramsTab['bool_showSearchTools'] = true;                                          //Afficle le filtre alphabetique et le champ de recherche
    $paramsTab['searchBoxAutoCompletionUrl'] = $_SESSION['config']['businessappurl']
                    ."index.php?display=true&page=contact_addresses_list_by_name";            //Script pour l'autocompletion
          
    $paramsTab['searchBoxAutoCompletionMinChars'] = 2;                                  //Nombre minimum de caractere pour activer l'autocompletion (1 par defaut)
    $paramsTab['searchBoxAutoCompletionUpdate'] = true;
    //Action icons array
    $paramsTab['actionIcons'] = array();
    //get start
    $start = $list->getStart();

    $view = array(
            "script"        => "window.top.location='".$_SESSION['config']['businessappurl']
                                    ."index.php?page=contact_addresses_up&fromSearchContacts&id=@@ca_id@@&what=".$what."&start=".$start."'",
            "class"         =>  "view",
            "label"         =>  _VIEW,
            "tooltip"       =>  _VIEW
            );
    array_push($paramsTab['actionIcons'], $view);
    
    //Afficher la liste
    echo '<br/>';
    $list->showList($tab, $paramsTab, 'contact_id');
}