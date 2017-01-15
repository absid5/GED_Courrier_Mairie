<?php
/*
*    Copyright 2008-2014 Maarch
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
* @brief  contacts list
*
*
* @file
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

$admin = new core_tools();
$return = $admin->test_admin('admin_contacts', 'apps', false);
if (!$return) {
    $return = $admin->test_admin('create_contacts', 'apps', false);
}

if (!$return) {
    $_SESSION['error'] = _SERVICE . ' ' . _UNKNOWN;
    ?>
    <script type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php';</script>
    <?php
    exit();
}

$func = new functions();
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
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=contacts_v2';
$page_label = _CONTACTS_LIST;
$page_id = "contacts_v2";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
unset($_SESSION['fromContactTree']);
?>
<table>
</table>
<table width="100%">
    <tr>
        <?php if ($admin->test_admin('admin_contacts', 'apps', false)) { ?>
        <td width="33%">

                <a href="<?php 
                    echo $_SESSION['config']['businessappurl']
                    ;?>index.php?admin=contacts&page=manage_duplicates">
                    <h2>
                        <i class="fa fa-magic fa-2x"></i>&nbsp;<?php 
                        echo _MANAGE_DUPLICATES;?>
                    </h2>
                </a>

        </td>
        <?php } ?>
        <td align="left">
            <input class="button" type="button" value="<?php echo _EXPORT_CONTACT;?>" onclick="window.open('<?php echo $_SESSION['config']['businessappurl'] . 'index.php?display=true&dir=admin&page=export_admin_list'?>');window.location.reload();"/>      
        </td>
        <td align="right">
            <input class="button" type="button" value="<?php echo _MANAGE_CONTACT_ADDRESSES_LIST;?>" onclick="window.location.href='<?php echo $_SESSION['config']['businessappurl'] . 'index.php?page=contact_addresses_list'?>'"/>      
        </td>
   </tr>
</table>
<?php
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_list_show.php");
require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_contacts_v2.php");
$contact = new contacts_v2();
$select[$_SESSION['tablename']['contacts_v2']] = array();
array_push($select[$_SESSION['tablename']['contacts_v2']],"contact_id", "is_corporate_person", "contact_type", "society","lastname","firstname", "user_id", "enabled");
$what = "";

$where =" ";
$arrayPDO = array();
if (isset($_REQUEST['selectedObject']) && ! empty($_REQUEST['selectedObject'])) {
    $where .= " contact_id = ? ";
    $arrayPDO = array($_REQUEST['selectedObject']);
} elseif(isset($_REQUEST['what']) && !empty($_REQUEST['what'])) {
    $what = $func->wash($_REQUEST['what'], "alphanum", "", "no");

    $what = str_replace("  ", "", $_REQUEST['what']);
    $what_table = explode(" ", $what);

    foreach($what_table as $key => $what_a){
        $sql_lastname[] = " lower(lastname) LIKE lower(:what_".$key.")";
        $sql_society[] = " lower(society) LIKE lower(:what_".$key.")";
        $arrayPDO = array_merge($arrayPDO, array(":what_".$key => $what_a."%"));
    }

    $where .= " (" . implode(' OR ', $sql_lastname) . " ";
    $where .= " or " . implode(' OR ', $sql_society) . ") ";

}
$list = new list_show();
$order = 'asc';
if(isset($_REQUEST['order']) && !empty($_REQUEST['order']))
{
    $order = trim($_REQUEST['order']);
}
$field = 'lastname, society';
if(isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field']))
{
    $field = trim($_REQUEST['order_field']);
}

$orderstr = $list->define_order($order, $field);

//EXPORT DE LA LISTE
$select2[$_SESSION['tablename']['contacts_v2']] = array();
array_push($select2[$_SESSION['tablename']['contacts_v2']], 'contact_id as "'._ID.'"','is_corporate_person as "'._IS_CORPORATE_PERSON.'"', 'contact_type as "'._CONTACT_TYPE.'"','lastname as "'._LASTNAME . '"', 'firstname as "'._FIRSTNAME . '"', 'society as "'._STRUCTURE_ORGANISM . '"');

$request= new request;

// $request->show();

$_SESSION['export_admin_list'] = array();
$_SESSION['export_admin_list']['select'] = $select2;
$_SESSION['export_admin_list']['where'] = $where;
$_SESSION['export_admin_list']['aPDO'] = $arrayPDO;
$_SESSION['export_admin_list']['order'] = $orderstr;

$tab=$request->PDOselect($select,$where,$arrayPDO, $orderstr,$_SESSION['config']['databasetype']);

for ($i=0;$i<count($tab);$i++)
{
    for ($j=0;$j<count($tab[$i]);$j++)
    {
        foreach(array_keys($tab[$i][$j]) as $value)
        {
            if($tab[$i][$j][$value]=="contact_id")
            {
                $tab[$i][$j]["contact_id"]=$tab[$i][$j]['value'];
                $tab[$i][$j]["label"]= _ID;
                $tab[$i][$j]["size"]="5";
                $tab[$i][$j]["label_align"]="left";
                $tab[$i][$j]["align"]="left";
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
                $tab[$i][$j]["order"]= "contact_id";
            }
            if($tab[$i][$j][$value]=="contact_type")
            {
                $tab[$i][$j]["contact_type"]= $tab[$i][$j]['value'];
                $tab[$i][$j]["value"]= $contact->get_label_contact($tab[$i][$j]['value'], $_SESSION['tablename']['contact_types']);
                $tab[$i][$j]["label"]= _CONTACT_TYPE;
                $tab[$i][$j]["size"]="10";
                $tab[$i][$j]["label_align"]="left";
                $tab[$i][$j]["align"]="left";
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
                $tab[$i][$j]["order"]= "contact_type";
            }
            if($tab[$i][$j][$value]=="is_corporate_person")
            {
                $tab[$i][$j]['value']= ($tab[$i][$j]['value'] == 'Y')? _YES : _NO;
                $tab[$i][$j]["label"]=_IS_CORPORATE_PERSON;
                $tab[$i][$j]["size"]="5";
                $tab[$i][$j]["label_align"]="center";
                $tab[$i][$j]["align"]="center";
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
                $tab[$i][$j]["order"]= "is_corporate_person";
            }
            if($tab[$i][$j][$value]=="society")
            {
                $tab[$i][$j]['value']=$request->show_string($tab[$i][$j]['value']);
                $tab[$i][$j]["society"]=$tab[$i][$j]['value'];
                $tab[$i][$j]["label"]=_STRUCTURE_ORGANISM;
                $tab[$i][$j]["size"]="15";
                $tab[$i][$j]["label_align"]="left";
                $tab[$i][$j]["align"]="left";
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
                $tab[$i][$j]["order"]= "society";
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
                $tab[$i][$j]["firstname"]= $tab[$i][$j]['value'];
                $tab[$i][$j]["label"]=_FIRSTNAME;
                $tab[$i][$j]["size"]="15";
                $tab[$i][$j]["label_align"]="center";
                $tab[$i][$j]["align"]="center";
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
                $tab[$i][$j]["order"]= "firstname";
            }
            if($tab[$i][$j][$value]=="user_id")
            {
                $tab[$i][$j]["user_id"]= $tab[$i][$j]['value'];
                $tab[$i][$j]["label"]=_CREATE_BY;
                $tab[$i][$j]["size"]="15";
                $tab[$i][$j]["label_align"]="center";
                $tab[$i][$j]["align"]="center";
                $tab[$i][$j]["valign"]="bottom";
                $tab[$i][$j]["show"]=true;
                $tab[$i][$j]["order"]= "user_id";
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
$page_name = "contacts_v2";
$page_name_up = "contacts_v2_up";
$page_name_ban = "";
$page_name_val= "";

if ($admin->test_admin('admin_contacts', 'apps', false)) {
    $page_name_del = "contacts_v2_del";
    $page_name_ban = "contacts_v2_status&mode=ban";
    $page_name_val = "contacts_v2_status&mode=allow";
}

$page_name_add = "contacts_v2_add";
$label_add = _CONTACT_ADDITION;
$_SESSION['m_admin']['init'] = true;
$title = _CONTACTS_LIST." : ".$i." "._CONTACTS;
$autoCompletionArray = array();
$autoCompletionArray["list_script_url"] = $_SESSION['config']['businessappurl']."index.php?display=true&page=contacts_v2_list_by_name";
$autoCompletionArray["number_to_begin"] = 1;
$autoCompletionArray["searchBoxAutoCompletionUpdate"] = true;

$list->admin_list($tab, $i, $title, 'contact_id','contacts_v2','contacts_v2','contact_id', true, $page_name_up, $page_name_val, $page_name_ban, $page_name_del, $page_name_add, $label_add, FALSE, FALSE, _ALL_CONTACTS, _CONTACT, 'users', false, true, true, true, $what, true, $autoCompletionArray, false, true);
$_SESSION['m_admin']['contacts'] = array();
$_SESSION['m_admin']['contacts']['id'] = "";
$_SESSION['m_admin']['contacts']['title'] = "";
$_SESSION['m_admin']['contacts']['lastname'] = "";
$_SESSION['m_admin']['contacts']['firtsname'] = "";
$_SESSION['m_admin']['contacts']['society'] = "";
$_SESSION['m_admin']['contacts']['function'] = "";
$_SESSION['m_admin']['contacts']['address_num'] = "";
$_SESSION['m_admin']['contacts']['address_street'] = "";
$_SESSION['m_admin']['contacts']['address_complement'] = "";
$_SESSION['m_admin']['contacts']['address_town'] = "";
$_SESSION['m_admin']['contacts']['address_postal_code'] = "";
$_SESSION['m_admin']['contacts']['address_country'] = "";
$_SESSION['m_admin']['contacts']['email'] = "";
$_SESSION['m_admin']['contacts']['phone'] = "";
$_SESSION['m_admin']['contacts']['other_data'] = "";
$_SESSION['m_admin']['contacts']['is_corporate_person'] = "";
$_SESSION['m_admin']['contacts']['is_private'] = "";

$_SESSION['origin']="contacts_list";
