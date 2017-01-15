<?php
/*
*    Copyright 2014 Maarch
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
*
*
* @file
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

// GESTION DES ADDRESSES
echo '<h2><i class="fa fa-home fa-2x"></i> &nbsp;'; 
    if ($mode <> 'view') { 
        echo _MANAGE_CONTACT_ADDRESSES_IMG;
    } else {
        echo _CONTACT_ADDRESSES_ASSOCIATED;
    } 
echo '</h2>';

require_once "core" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    . "class_request.php";
require_once "apps" . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    . "class_list_show.php";
$func = new functions();

if (isset($_REQUEST['fromMyContactsList']) || isset($_REQUEST['fromSearchContactsList'])) {
    $_REQUEST['what'] = "";
    $_REQUEST['order'] = "";
    $_REQUEST['order_field'] = "";
}

$select[$_SESSION['tablename']['contact_addresses']] = array();
array_push(
    $select[$_SESSION['tablename']['contact_addresses']],
    "id", "contact_id", "contact_purpose_id", "departement", "lastname", "firstname", "function", "is_private","address_num", "address_street", "address_postal_code", "address_town", "phone", "email"
);
$what = "";
$where = "contact_id = ? ";
$arrayPDO = array($_SESSION['contact']['current_contact_id']);
if (isset($_REQUEST['what']) && ! empty($_REQUEST['what'])) {
    $what = $_REQUEST['what'];
    $where .= " and lower(lastname) like lower(?)";
    $arrayPDO = array_merge($arrayPDO, array($what. '%'));
}

$list = new list_show();
$order = 'asc';
if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {
    $order = trim($_REQUEST['order']);
}
$field = 'lastname';
if (isset($_REQUEST['order_field']) && ! empty($_REQUEST['order_field'])) {
    $field = trim($_REQUEST['order_field']);
}

$orderstr = $list->define_order($order, $field);

$request = new request;
$tab = $request->PDOselect(
    $select, $where, $arrayPDO, $orderstr, $_SESSION['config']['databasetype']
);
for ($i = 0; $i < count($tab); $i ++) {
    for ($j = 0; $j < count($tab[$i]); $j ++) {
        foreach (array_keys($tab[$i][$j]) as $value) {
            if ($tab[$i][$j][$value] == "id") {
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
            if ($tab[$i][$j][$value] == "contact_purpose_id") {
                $tab[$i][$j]["value"]= $contact->get_label_contact($tab[$i][$j]['value'], $_SESSION['tablename']['contact_purposes']);
                $tab[$i][$j]["contact_purpose_id"] = $tab[$i][$j]['value'];
                $tab[$i][$j]["label"] = _CONTACT_PURPOSE;
                $tab[$i][$j]["size"] = "20";
                $tab[$i][$j]["label_align"] = "left";
                $tab[$i][$j]["align"] = "left";
                $tab[$i][$j]["valign"] = "bottom";
                $tab[$i][$j]["show"] = true;
                $tab[$i][$j]["order"] = 'contact_purpose_id';
            }
            if ($tab[$i][$j][$value] == "departement") {
                $tab[$i][$j]['value'] = $request->show_string(
                    $tab[$i][$j]['value']
                );
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
                if ($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == "Y") {
                    $tab[$i][$j]["show"]=true;
                } else {
                    $tab[$i][$j]["show"]=false;
                }
                $tab[$i][$j]["order"]= "lastname";
            }
            if($tab[$i][$j][$value]=="firstname")
            {
                $tab[$i][$j]["firstname"]= $request->show_string($tab[$i][$j]['value']);
                $tab[$i][$j]["label"]=_FIRSTNAME;
                $tab[$i][$j]["size"]="15";
                $tab[$i][$j]["label_align"]="left";
                $tab[$i][$j]["align"]="left";
                $tab[$i][$j]["valign"]="bottom";
                if ($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == "Y") {
                    $tab[$i][$j]["show"]=true;
                } else {
                    $tab[$i][$j]["show"]=false;
                }
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
                if ($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == "Y") {
                    $tab[$i][$j]["show"]=true;
                } else {
                    $tab[$i][$j]["show"]=false;
                }
                $tab[$i][$j]["order"]= "function";
            }
            if($tab[$i][$j][$value]=="is_private")
            {
                $is_private = $tab[$i][$j]['value'];
                $tab[$i][$j]["show"]=false;
            }
            if($tab[$i][$j][$value]=="address_num")
            {
                $address_num = $tab[$i][$j]['value'];
                $tab[$i][$j]["show"]=false;
            }
            if($tab[$i][$j][$value]=="address_street")
            {
                if ($is_private == "Y") {
                    $tab[$i][$j]['value'] = "Confidentielle";
                } else {
                    $tab[$i][$j]['value'] = $address_num . " " . $request->show_string($tab[$i][$j]['value']);                    
                }

                $tab[$i][$j]["address_street"]= $tab[$i][$j]['value'];
                $tab[$i][$j]["label"]= _ADDRESS;
                $tab[$i][$j]["size"]="15";
                $tab[$i][$j]["label_align"]="left";
                $tab[$i][$j]["align"]="left";
                $tab[$i][$j]["valign"]="bottom";
                if ($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == "Y") {
                    $tab[$i][$j]["show"]=false;
                } else {
                    $tab[$i][$j]["show"]=true;
                }
                $tab[$i][$j]["order"]= "address_street";
            }
            if($tab[$i][$j][$value]=="address_postal_code")
            {
                if ($is_private == "Y") {
                    $tab[$i][$j]['value'] = "Confidentiel";
                } else {
                    $tab[$i][$j]['value']=$request->show_string($tab[$i][$j]['value']);
                }
                $tab[$i][$j]["address_postal_code"]=$tab[$i][$j]['value'];
                $tab[$i][$j]["label"]=_POSTAL_CODE;
                $tab[$i][$j]["size"]="15";
                $tab[$i][$j]["label_align"]="left";
                $tab[$i][$j]["align"]="left";
                $tab[$i][$j]["valign"]="bottom";
               if ($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == "Y") {
                    $tab[$i][$j]["show"]=false;
                } else {
                    $tab[$i][$j]["show"]=true;
                }
                $tab[$i][$j]["order"]= "address_postal_code";
            }
            if($tab[$i][$j][$value]=="address_town")
            {
                if ($is_private == "Y") {
                    $tab[$i][$j]['value'] = "Confidentielle";
                }
                $tab[$i][$j]["address_town"]= $request->show_string($tab[$i][$j]['value']);
                $tab[$i][$j]["label"]=_TOWN;
                $tab[$i][$j]["size"]="15";
                $tab[$i][$j]["label_align"]="left";
                $tab[$i][$j]["align"]="left";
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
                $tab[$i][$j]["order"]= "address_town";
            }
            if($tab[$i][$j][$value]=="phone")
            {
                if ($is_private == "Y") {
                    $tab[$i][$j]['value'] = "Confidentiel";
                } else {
                    $tab[$i][$j]['value']=$request->show_string($tab[$i][$j]['value']);
                }
                $tab[$i][$j]["phone"]=$tab[$i][$j]['value'];
                $tab[$i][$j]["label"]=_PHONE;
                $tab[$i][$j]["size"]="15";
                $tab[$i][$j]["label_align"]="left";
                $tab[$i][$j]["align"]="left";
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
                $tab[$i][$j]["order"]= "phone";
            }
            if($tab[$i][$j][$value]=="email")
            {
                if ($is_private == "Y") {
                    $tab[$i][$j]['value'] = "Confidentiel";
                }
                $tab[$i][$j]["email"]= $request->show_string($tab[$i][$j]['value']);
                $tab[$i][$j]["label"]=_MAIL;
                $tab[$i][$j]["size"]="15";
                $tab[$i][$j]["label_align"]="center";
                $tab[$i][$j]["align"]="center";
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
                $tab[$i][$j]["order"]= "email";
            }
        }
    }
}

//List parameters
    $paramsTab = array();
    $paramsTab['bool_modeReturn'] = false;                                              //Desactivation du mode return (vs echo)
    $paramsTab['pageTitle'] =  '';           											//Titre de la page
    $paramsTab['listCss'] =  'listing largerList spec';
    if ($mode == "view") {
        $paramsTab['urlParameters'] = '&dir=indexing_searching&letters&display=true'; 
    } else {
        $paramsTab['urlParameters'] = '&dir=my_contacts&letters&display=true';                                   //parametre d'url supplementaire        
    }

    $paramsTab['bool_sortColumn'] = true;                                               //Affichage Tri
    $paramsTab['bool_showSearchTools'] = true;                                          //Afficle le filtre alphabetique et le champ de recherche
    $paramsTab['bool_showSearchBox'] = false;

    $paramsTab['searchBoxAutoCompletionUrl'] = $_SESSION['config']['businessappurl']
        ."index.php?display=true&page=contact_addresses_list_by_name&idContact=".$_SESSION['contact']['current_contact_id'];   //Script pour l'autocompletion
    $paramsTab['searchBoxAutoCompletionMinChars'] = 2;                                  //Nombre minimum de caractere pour activer l'autocompletion (1 par defaut)
    if($mode <> 'view'){
        $paramsTab['bool_showAddButton'] = true;                                            //Affichage du bouton Nouveau
    }
    $paramsTab['addButtonLabel'] = _NEW_CONTACT_ADDRESS;                                //Libellé du bouton Nouveau
    if ($from_iframe) {
	    $paramsTab['addButtonScript'] = "window.location='".$_SESSION['config']['businessappurl']
	        ."index.php?display=false&dir=my_contacts&page=create_address_iframe&iframe=fromContactIframe'";
    } else {
        if($mode <> 'view'){
        $paramsTab['addButtonScript'] = "window.top.location='".$_SESSION['config']['businessappurl']
            ."index.php?page=contact_addresses_add&mycontact=Y'";                       //Action sur le bouton nouveau (2)            
        }
    }

    //Action icons array
    $paramsTab['actionIcons'] = array();
        //get start
        $start = $list2->getStart();
    if ($mode <> 'view') {   
       if ($from_iframe) {
	        $update = array(
	                "script"        => "window.location='".$_SESSION['config']['businessappurl']
	                                        ."index.php?display=false&dir=my_contacts&page=update_address_iframe&id=@@id@@&fromContactIframe'",
	                "class"         =>  "change",
	                "label"         =>  _MODIFY,
	                "tooltip"       =>  _MODIFY
	                );
        } else {
	        $update = array(
	                "script"        => "window.top.location='".$_SESSION['config']['businessappurl']
	                                        ."index.php?page=contact_addresses_up&mycontact=Y&id=@@id@@&what=".$what."&start=".$start."'",
	                "class"         =>  "change",
	                "label"         =>  _MODIFY,
	                "tooltip"       =>  _MODIFY
	                );
        }

        array_push($paramsTab['actionIcons'], $update); 

		if ($from_iframe) {
            if ($_SESSION['AttachmentContact'] == "1") {
                $infoContactDiv = "info_contact_div_attach";
            } else {
                $infoContactDiv = "info_contact_div";
            }
	        $use = array(
	                "script"        => "set_new_contact_address('".$_SESSION['config']['businessappurl'] . "index.php?display=false&dir=my_contacts&page=get_last_contact_address&contactid=".$_SESSION['contact']['current_contact_id']."&addressid=@@id@@', '".$infoContactDiv."', 'true');simpleAjax('".$_SESSION['config']['businessappurl']."index.php?display=true&page=unsetAttachmentContact');",
	                "class"         =>  "use",
	                "label"         =>  _USE,
                    "tooltip"       =>  _USE
	                );
	        array_push($paramsTab['actionIcons'], $use);
		} else {
	        $delete = array(
	                "href"          => $_SESSION['config']['businessappurl']
	                                    ."index.php?page=contact_addresses_del&mycontact=Y&what=".$what."&start=".$start,
	                "class"         =>  "delete",
	                "label"         =>  _DELETE,
	                "tooltip"       =>  _DELETE,
	                "alertText"     =>  _REALLY_DELETE.": @@lastname@@ @@firstname@@ ?"
	                );
	        array_push($paramsTab['actionIcons'], $delete);
		}
    } else {
        $view = array(
                "script"        => "window.top.location='".$_SESSION['config']['businessappurl']
                                        ."index.php?dir=indexing_searching&page=contact_address_view&addressid=@@id@@&what=".$what."&start=".$start."'",
                "class"         =>  "view",
                "label"         =>  _VIEW,
                "tooltip"       =>  _VIEW
                );
        array_push($paramsTab['actionIcons'], $view);
    }
//Afficher la liste
    echo '<br/>';
    $list2->showList($tab, $paramsTab, 'id');