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
* @brief  Form to modify a contact
*
*
* @file
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

$core_tools = new core_tools();
$core_tools->load_lang();
$return = $core_tools->test_admin('admin_contacts', 'apps', false);
if (!$return) {
    $return = $core_tools->test_admin('create_contacts', 'apps', false);
}

if (!$return) {
    $_SESSION['error'] = _SERVICE . ' ' . _UNKNOWN;
    ?>
    <script type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php';</script>
    <?php
    exit();
}

require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_contacts_v2.php");

$func = new functions();

if(isset($_GET['id']))
{
    $id = addslashes($func->wash($_GET['id'], "alphanum", _CONTACT));
    $_SESSION['contact']['current_contact_id'] = $id;
}else if ($_SESSION['contact']['current_contact_id'] <> ''){
	$id = $_SESSION['contact']['current_contact_id'];
}
else
{
    $id = "";
}
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
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=contacts_v2_up';
$page_label = _MODIFICATION;
$page_id = "contacts_v2_up";
$core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
if (isset($_REQUEST['fromContactTree'])) {
    $_SESSION['fromContactTree'] = 'yes';
}
$contact = new contacts_v2();
$contact->formcontact("up",$id);

// GESTION DES ADDRESSES
echo '<div class="block">';
echo '<h2><i class="fa fa-home fa-2x"></i> &nbsp;' . _MANAGE_CONTACT_ADDRESSES_IMG . '</h2>';
require_once "core" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    . "class_request.php";
require_once "apps" . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    . "class_list_show.php";
$func = new functions();

$select['view_contacts'] = array();
array_push(
    $select['view_contacts'],
    "ca_id as id", "contact_id", "contact_purpose_id", "departement", "lastname", "firstname", "function", "address_num", "address_street", "address_postal_code", "address_town", "phone", "email", "enabled", "contact_purpose_label"
);
$what = "";
$where = "contact_id = :contactid ";
$arrayPDO = array(":contactid" => $id);
if (isset($_REQUEST['selectedObject']) && ! empty($_REQUEST['selectedObject'])) {
    $where .= " and ca_id = :selectedObject ";
    $arrayPDO = array_merge($arrayPDO, array(":selectedObject" => $_REQUEST['selectedObject']));
} elseif (isset($_REQUEST['what2']) && ! empty($_REQUEST['what2'])) {

    $what = str_replace("  ", "", $_REQUEST['what2']);
    $what_table = explode(" ", $what);

    foreach($what_table as $key => $what_a){
        if (strlen($what_a) > 2) {
            $sql_lastname[] = " lower(lastname) LIKE lower(:what_".$key.")";
            $sql_firstname[] = " lower(firstname) LIKE lower(:what_".$key.")";
            $sql_society[] = " lower(departement) LIKE lower(:what_".$key.")";
            $sql_purpose[] = " lower(contact_purpose_label) LIKE lower(:what_".$key.")";
            $arrayPDO = array_merge($arrayPDO, array(":what_".$key => $what_a."%"));
        }
    }
    if ($sql_lastname <> "") {
        $where .= " and (" . implode(' OR ', $sql_lastname) . " ";
        $where .= " or " . implode(' OR ', $sql_firstname) . " ";
        $where .= " or " . implode(' OR ', $sql_purpose) . " ";
        $where .= " or " . implode(' OR ', $sql_society) . ") ";
    }

}

// var_dump($arrayPDO);
// var_dump($where);

$list = new list_show();
$order = 'asc';
if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {
    $order = trim($_REQUEST['order']);
}
$field = 'lastname';
if (isset($_REQUEST['order_field']) && ! empty($_REQUEST['order_field']) && in_array($_REQUEST['order_field'], $select['view_contacts'])) {
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
                $tab[$i][$j]["label_align"]="center";
                $tab[$i][$j]["align"]="center";
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
            if($tab[$i][$j][$value]=="address_num")
            {
                $address_num = $tab[$i][$j]['value'];
                $tab[$i][$j]["show"]=false;
            }
            if($tab[$i][$j][$value]=="address_street")
            {
                $tab[$i][$j]['value'] = $address_num . " " . $request->show_string($tab[$i][$j]['value']);
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
                $tab[$i][$j]['value']=$request->show_string($tab[$i][$j]['value']);
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
                $tab[$i][$j]["address_town"]= $request->show_string($tab[$i][$j]['value']);
                $tab[$i][$j]["label"]=_TOWN;
                $tab[$i][$j]["size"]="15";
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
                $tab[$i][$j]["show"]=true;
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
                $tab[$i][$j]["show"]=true;
                $tab[$i][$j]["order"]= "email";
            }
            if($tab[$i][$j][$value]=="enabled")
            {
                $tab[$i][$j]["enabled"]= $tab[$i][$j]['value'];
                $tab[$i][$j]["label"]=_STATUS;
                $tab[$i][$j]["size"]="5";
                $tab[$i][$j]["label_align"]="center";
                $tab[$i][$j]["align"]="center";
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
                $tab[$i][$j]["order"]= "enabled";
            }
        }
    }
}
$pageName = "contact_addresses";
$pageNameUp = "contact_addresses_up";
$pageNameAdd = "contact_addresses_add";
$pageNameVal = "";
$pageNameBan = "";

if ($core_tools->test_admin('admin_contacts', 'apps', false)) {
    $pageNameDel = "contact_addresses_del";
    $pageNameBan = "contact_addresses_status&mode=ban";
    $pageNameVal = "contact_addresses_status&mode=allow";
}

$tableName = $_SESSION['tablename']['contact_addresses'];
$addLabel = _NEW_CONTACT_ADDRESS;
$autoCompletionArray = array();
$autoCompletionArray["list_script_url"] = $_SESSION['config']['businessappurl']
    . "index.php?display=true&page=contact_addresses_list_by_name&idContact=".$id;
$autoCompletionArray["number_to_begin"] = 1;
$autoCompletionArray["searchBoxAutoCompletionUpdate"] = true;

if ($_SESSION['origin']=='contacts_list') {
    $_REQUEST['start']='';
    $_SESSION['origin']='contact_up';
}

$list->admin_list(
    $tab, $i, '',
    'contact_id"', 'contacts_v2_up', 'contacts_v2',
    'id', true, $pageNameUp, $pageNameVal, $pageNameBan,
    $pageNameDel, $pageNameAdd, $addLabel, FALSE, FALSE, _ALL_CONTACT_ADDRESSES,
    _A_CONTACT_ADDRESS, '', false, true, true, true,
    $what, true, $autoCompletionArray, false, false, 'what2', 'whatListInput2'
);

$_SESSION['m_admin']['address'] = array();
echo '</div>';
?>
