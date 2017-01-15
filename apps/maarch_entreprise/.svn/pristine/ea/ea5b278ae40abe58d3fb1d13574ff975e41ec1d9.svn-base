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

require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_contacts_v2.php");
$core_tools = new core_tools();
$core_tools->load_lang();
$func = new functions();
$contact = new contacts_v2();

require_once "core" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "class_request.php";
require_once "apps" . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "class_list_show.php";
$func = new functions();

$return = $core_tools->test_admin('admin_contacts', 'apps', false);
if (!$return) {
    $return = $core_tools->test_admin('search_contacts', 'apps', false);
}
if (!$return) {
    $return = $core_tools->test_admin('create_contacts', 'apps');
}

 /****************Management of the location bar  ************/
$init = false;
if(isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true") {
    $init = true;
}
$level = "";
if(isset($_REQUEST['level']) && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1)) {
    $level = $_REQUEST['level'];
}
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=contact_addresses_list';
$page_label = _MANAGE_CONTACT_ADDRESSES_LIST;
$page_id = "contact_addresses_list";
$core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/

$select["view_contacts"] = array();
array_push(
    $select["view_contacts"],
    "ca_id"
    , "contact_id
    , case when view_contacts.is_corporate_person <> 'Y' then view_contacts.contact_id || ' - ' || view_contacts.contact_lastname || ' ' || view_contacts.contact_firstname|| ' - physique' else view_contacts.contact_id || ' - ' || view_contacts.society || ' - moral' end as \"society\""
    , "contact_purpose_id"
    , "departement
    , case when view_contacts.contact_lastname <> '' then view_contacts.contact_lastname else view_contacts.lastname end as \"lastname\"
    , case when view_contacts.contact_firstname <> '' then view_contacts.contact_firstname else view_contacts.firstname end as \"firstname\"
    , case when view_contacts.contact_function <> '' then view_contacts.contact_function else view_contacts.function end as \"function\""
    , "address_town", "phone", "email"
);
$what = "";
$where = "";

$arrayPDO = array();
if (isset($_REQUEST['selectedObject']) && ! empty($_REQUEST['selectedObject'])) {
    $where .= " ca_id = ? ";
    $arrayPDO = array($_REQUEST['selectedObject']);
} elseif (isset($_REQUEST['what']) && ! empty($_REQUEST['what'])) {

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
// var_dump($arrayPDO);
// var_dump($where);

$list = new list_show();
$order = 'asc';
if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {
    $order = trim($_REQUEST['order']);
}

//use to pass the next condition in order_field. Then we need to delete them.
array_push(
    $select["view_contacts"],
    "lastname", "firstname", "function"
);

$field = 'society';
if (isset($_REQUEST['order_field']) && ! empty($_REQUEST['order_field']) && in_array($_REQUEST['order_field'], $select["view_contacts"])) {
    $field = trim($_REQUEST['order_field']);
}

array_pop($select["view_contacts"]);
array_pop($select["view_contacts"]);
array_pop($select["view_contacts"]);

$orderstr = $list->define_order($order, $field);

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
                $show_string = explode(' - ', $tab[$i][$j]['value']);
                $show_string[2] = '<i style="font-size:10px;color:#16ADEB;">'.$show_string[2].'</i>';
                $show_string  = implode(' - ', $show_string);
                $tab[$i][$j]["value"] = $show_string;
                $tab[$i][$j]["society"] = $tab[$i][$j]["value"];
                $tab[$i][$j]["label"] = _LINKED_CONTACT;
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
                $tab[$i][$j]["address_town"]= $request->show_string($tab[$i][$j]['value']);
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

$pageName = "contact_addresses_list";
$pageNameUp = "contact_addresses_up&fromContactAddressesList";
$pageNameAdd = "";
$pageNameDel = "";
$pageNameVal = "";
$tableName = "view_contacts";
$pageNameBan = "";
$addLabel = "";

$autoCompletionArray = array();
$autoCompletionArray["list_script_url"] = $_SESSION['config']['businessappurl']
    . "index.php?display=true&page=contact_addresses_list_by_name";
$autoCompletionArray["number_to_begin"] = 1;
$autoCompletionArray["searchBoxAutoCompletionUpdate"] = true;

$title = _ADDRESSES_LIST." : ".$i." "._ADDRESSES;

$list->admin_list(
    $tab, $i, $title,
    'contact_id', 'contact_addresses_list', 'contact_addresses',
    'id', true, $pageNameUp, $pageNameVal, $pageNameBan,
    $pageNameDel, $pageNameAdd, $addLabel, FALSE, FALSE, _ALL_CONTACT_ADDRESSES,
    _A_CONTACT_ADDRESS, 'home', false, true, true, true,
    $what, true, $autoCompletionArray, false, true);

$_SESSION['m_admin']['address'] = array();

?>
