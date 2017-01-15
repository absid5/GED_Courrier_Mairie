<?php

/*
 *    Copyright 2008,2009 Maarch
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
 * @brief  Search result list
 *
 * @file list_results_mlb.php
 * @author Claire Figueras <dev@maarch.org>
 * @author Loïc Vinet <dev@maarch.org>
 * @date $date$
 * @version $Revision$
 * @ingroup indexing_searching_mlb
 */
require_once "core" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    . "class_request.php";
require_once "core" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    . "class_security.php";
require_once "core" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    . "class_manage_status.php";
require_once
	"apps" . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    . 'class_list_show.php';
require_once "apps" . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . 'class_contacts_v2.php';
require_once "core" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    . "class_manage_status.php";
require_once "modules" . DIRECTORY_SEPARATOR . "cases" . DIRECTORY_SEPARATOR
    . "class" . DIRECTORY_SEPARATOR . 'class_modules_tools.php';
include_once "apps" . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'definition_mail_categories.php';

$status_obj = new manage_status();
$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();
$core_tools->load_html();
$core_tools->load_header('', true, false);
$sec = new security();
$status_obj = new manage_status();
$contact = new contacts_v2();
$cases = new cases();
$where = '';
$order = '';
if (isset ($_REQUEST['order']) && !empty ($_REQUEST['order'])) {
    $order = trim($_REQUEST['order']);
}
$field = '';
if (isset ($_REQUEST['order_field']) && !empty ($_REQUEST['order_field'])) {
    $field = trim($_REQUEST['order_field']);
}
$start = 0;
 if (isset ($_REQUEST['start']) && !empty ($_REQUEST['start'])) {
    $start = trim($_REQUEST['start']);
}
/****************Management of the location bar  ************/
$init = false;
if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true") {
    $init = true;
}
$level = "";
if (isset($_REQUEST['level']) && ($_REQUEST['level'] == 2
    || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4
    || $_REQUEST['level'] == 1)
) {
    $level = $_REQUEST['level'];
}
$pagePath = $_SESSION['config']['businessappurl']
    . 'index.php?page=list_results_mlb&dir=indexing_searching&order=' . $order
    . '&order_field=' . $field . '&start=' . $start;
$page_label = _RESULTS;
$page_id = "search_adv_result_mlb";
$core_tools->manage_location_bar($pagePath, $page_label, $page_id, $init, $level);
/***********************************************************/

$string = $_SESSION['searching']['where_request'];
$search1="'case'";
/*preg_match($search1, $string, $out);
$count = count($out[0]);
if($count == 1) {
    $searchOnCases = true;
}
else {
    $searchOnCases = false;
}*/
//temporary
$searchOnCases = true;
if(!$searchOnCases) {
    $view = $_SESSION['collections'][0]['view'];
    $select = array ();
    $select[$view] = array ();
    $where_request = $_SESSION['searching']['where_request'];
    $arrayPDO = $_SESSION['searching']['where_request_parameters'];
    array_push($select[$view], "res_id", "status", "subject", "category_id as category_img", "contact_firstname", "contact_lastname", "contact_society", "user_lastname", "user_firstname", "dest_user", "type_label", "creation_date", "destination", "category_id, exp_user_id");
    $status = $status_obj->get_not_searchable_status();
    $status_str = '';
    for ($i = 0; $i < count($status); $i++) {
        $status_str .= ":" . $status[$i]['ID'] . ",";
        $arrayPDO = array_merge($arrayPDO, array(":".$status[$i]['ID'] => $status[$i]['ID']));
    }
   
    if ($status_str <> '') {
        $status_str = preg_replace('/,$/', '', $status_str);
        $where_request.= "  status not in (".$status_str.") ";
        
    } else {
        $where_request .= " 1=1 ";
    }

    if ($core_tools->is_module_loaded("cases") == true) {
        array_push($select[$view], "case_id", "case_label", "case_description");
    }
    if ($_GET['searched_item'] == "res_id" || $_GET['searched_item'] == "res_id_in_process") {
        $case_id_in_res = $cases->get_case_id($_GET['searched_value']);
        if ($case_id_in_res <> '') {
            //$tmp1 = " and " . $_SESSION['tablename']['cases'] . ".case_id <> '" . $case_id_in_res . "' ";
        } else {
            //$tmp1 = " and " . $_SESSION['tablename']['cases'] . ".case_id <> 0 ";
        }
        $where_request .= $tmp1;
    }
    $where_clause = $sec->get_where_clause_from_coll_id($_SESSION['collection_id_choice']);
    if (!empty ($where_request)) {
        if ($_SESSION['searching']['where_clause_bis'] <> "") {
            $where_clause = "((" . $where_clause . ") or (" . $_SESSION['searching']['where_clause_bis'] . "))";
        }
        $where_request = '(' . $where_request . ') and (' . $where_clause . ')';
    } else {
        if ($_SESSION['searching']['where_clause_bis'] <> "") {
            $where_clause = "((" . $where_clause . ") or (" . $_SESSION['searching']['where_clause_bis'] . "))";
        }
        $where_request = $where_clause;
    }
    $where_request = str_replace("()", "(1=-1)", $where_request);
    $where_request = str_replace("and ()", "", $where_request);
} else {
    $where_request = $_SESSION['searching']['where_request'];
    $arrayPDO = $_SESSION['searching']['where_request_parameters'];
    $where_request = str_replace($_SESSION['collections'][0]['view'], $_SESSION['tablename']['cases'], $where_request);
    if ($_GET['searched_item'] == "res_id" || $_GET['searched_item'] == "res_id_in_process") {
        $case_id_in_res = $cases->get_case_id($_GET['searched_value']);
        if ($case_id_in_res <> '') {
            $tmp1 = "  " . $_SESSION['tablename']['cases'] . ".case_id <> :caseIdInRes ";
            $arrayPDO = array_merge($arrayPDO, array(":caseIdInRes" => $case_id_in_res));
        } else {
            $tmp1 = "  " . $_SESSION['tablename']['cases'] . ".case_id <> 0 ";
        }
        $where_request .= $tmp1;
    }
    unset ($select);
    $select = array ();
    $select[$_SESSION['tablename']['cases']] = array ();
    array_push($select[$_SESSION['tablename']['cases']], "case_id", "case_label", "case_description", "case_typist", "case_creation_date", "case_closing_date");
}

if ($_GET['searched_item'] == "case") {
    $res_id_in_case = $cases->get_res_id($_GET['searched_value']);
    $tmp1 = " and res_id not in(";
    foreach ($res_id_in_case as $rri) {
        $tmp1 .= '\'' . $rri . '\',';
    }
    $tmp1 = substr($tmp1, 0, -1);
    $tmp1 .= " )    ";
    $where_request .= $tmp1;
}

$list = new list_show();

$orderstr = $list->define_order($order, $field);

if (($_REQUEST['template'] == 'group_case') && ($core_tools->is_module_loaded('cases'))) {

    $where .= "cases.case_closing_date is null and ";

    $request = new request();
    $tab = $request->PDOselect($select, $where . $where_request, $arrayPDO, $orderstr, $_SESSION['config']['databasetype'], "default", false, "", "", "", true, false, true);

} else {
    $request = new request();
    $tab = $request->PDOselect($select, $where_request, $arrayPDO,  $orderstr, $_SESSION['config']['databasetype']);
}
//$request->show();
$_SESSION['error_page'] = '';

//defines template allowed for this list
$template_list = array ();

if ($_GET['searched_item'] == 'case')
    array_push($template_list, array (
        "name" => "attach_to_case",
        "img" => "extend_list.gif",
        "label" => _ACCESS_LIST_EXTEND
    ));

if ($_REQUEST['template'] == 'group_case')
    array_push($template_list, array (
        "name" => "group_case",
        "img" => "fa fa-briefcase fa-2x",
        "label" => _ACCESS_LIST_CASE
    ));

if (!$_REQUEST['template'])
    $template_to_use = $template_list[0]["name"];
if (isset ($_REQUEST['template']) && empty ($_REQUEST['template']))
    $template_to_use = '';
if ($_REQUEST['template'])
    $template_to_use = $_REQUEST['template'];

//for status icon
$extension_icon = '';
if ($template_to_use <> '')
    $extension_icon = "_big";

//build the tab with right format for list_doc function
if (count($tab) > 0) {
    //Specific View for group_case_template, we don' need to load the standard list_result_mlb
    if (($_REQUEST['template'] == 'group_case') && ($core_tools->is_module_loaded('cases'))) {
        include ("modules" . DIRECTORY_SEPARATOR . "cases" . DIRECTORY_SEPARATOR . 'mlb_list_group_case_addon.php');
    } else {
        for ($i = 0; $i < count($tab); $i++) {
            for ($j = 0; $j < count($tab[$i]); $j++) {
                foreach (array_keys($tab[$i][$j]) as $value) {
                    if ($tab[$i][$j][$value] == 'res_id') {
                        $tab[$i][$j]['res_id'] = $tab[$i][$j]['value'];
                        $tab[$i][$j]["label"] = _GED_NUM;
                        $tab[$i][$j]["size"] = "4";
                        $tab[$i][$j]["label_align"] = "left";
                        $tab[$i][$j]["align"] = "center";
                        $tab[$i][$j]["valign"] = "bottom";
                        $tab[$i][$j]["show"] = true;
                        $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                        $tab[$i][$j]["order"] = 'res_id';
                        $_SESSION['mlb_search_current_res_id'] = $tab[$i][$j]['value'];
                    }
                    if ($tab[$i][$j][$value] == "type_label") {
                        $tab[$i][$j]["label"] = _TYPE;
                        $tab[$i][$j]['value'] = $request->show_string($tab[$i][$j]['value']);
                        $tab[$i][$j]["size"] = "15";
                        $tab[$i][$j]["label_align"] = "left";
                        $tab[$i][$j]["align"] = "left";
                        $tab[$i][$j]["valign"] = "bottom";
                        $tab[$i][$j]["show"] = true;
                        $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                        $tab[$i][$j]["order"] = "type_label";
                    }
                    if ($tab[$i][$j][$value] == "status") {
                        $tab[$i][$j]["label"] = _STATUS;
                        $res_status = $status_obj->get_status_data($tab[$i][$j]['value'], $extension_icon);
                        $tab[$i][$j]['value'] = "<img src = '" . $res_status['IMG_SRC'] . "' alt = '" . $res_status['LABEL'] . "' title = '" . $res_status['LABEL'] . "'>";
                        $tab[$i][$j]["size"] = "5";
                        $tab[$i][$j]["label_align"] = "left";
                        $tab[$i][$j]["align"] = "left";
                        $tab[$i][$j]["valign"] = "bottom";
                        $tab[$i][$j]["show"] = true;
                        $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                        $tab[$i][$j]["order"] = "status";
                    }
                    if ($tab[$i][$j][$value] == "subject") {
                        $tab[$i][$j]["label"] = _SUBJECT;
                        $tab[$i][$j]['value'] = $request->show_string($tab[$i][$j]['value']);
                        $tab[$i][$j]["size"] = "25";
                        $tab[$i][$j]["label_align"] = "left";
                        $tab[$i][$j]["align"] = "left";
                        $tab[$i][$j]["valign"] = "bottom";
                        $tab[$i][$j]["show"] = true;
                        $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                        $tab[$i][$j]["order"] = "subject";
                    }
                    if ($tab[$i][$j][$value] == "dest_user") {
                        $tab[$i][$j]["label"] = _DEST_USER;
                        $tab[$i][$j]["size"] = "10";
                        $tab[$i][$j]["label_align"] = "left";
                        $tab[$i][$j]["align"] = "left";
                        $tab[$i][$j]["valign"] = "bottom";
                        $tab[$i][$j]["show"] = true;
                        $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                        $tab[$i][$j]["order"] = "dest_user";
                    }
                    if ($tab[$i][$j][$value] == "creation_date") {
                        $tab[$i][$j]["label"] = _REG_DATE;
                        $tab[$i][$j]["size"] = "10";
                        $tab[$i][$j]["label_align"] = "left";
                        $tab[$i][$j]["align"] = "left";
                        $tab[$i][$j]["valign"] = "bottom";
                        $tab[$i][$j]["show"] = true;
                        $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                        $tab[$i][$j]["value"] = $request->format_date_db($tab[$i][$j]['value'], false);
                        $tab[$i][$j]["order"] = "creation_date";
                    }
                    if ($tab[$i][$j][$value] == "destination") {
                        $tab[$i][$j]["label"] = _ENTITY;
                        $tab[$i][$j]['value'] = $request->show_string($tab[$i][$j]['value']);
                        $tab[$i][$j]["size"] = "10";
                        $tab[$i][$j]["label_align"] = "left";
                        $tab[$i][$j]["align"] = "left";
                        $tab[$i][$j]["valign"] = "bottom";
                        $tab[$i][$j]["show"] = false;
                        $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                        $tab[$i][$j]["order"] = "destination";
                    }
                    if ($tab[$i][$j][$value] == "category_id") {
                        $_SESSION['mlb_search_current_category_id'] = $tab[$i][$j]['value'];
                        $tab[$i][$j]["label"] = _CATEGORY;
                        $tab[$i][$j]["size"] = "10";
                        $tab[$i][$j]["label_align"] = "left";
                        $tab[$i][$j]["align"] = "left";
                        $tab[$i][$j]["valign"] = "bottom";
                        $tab[$i][$j]["show"] = true;
                        $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                        $tab[$i][$j]["value"] = $_SESSION['coll_categories']['letterbox_coll'][$tab[$i][$j]['value']];
                        $tab[$i][$j]["order"] = "category_id";
                    }
                    if ($tab[$i][$j][$value] == "category_img") {
                        $tab[$i][$j]["label"] = _CATEGORY;
                        $tab[$i][$j]["size"] = "10";
                        $tab[$i][$j]["label_align"] = "left";
                        $tab[$i][$j]["align"] = "left";
                        $tab[$i][$j]["valign"] = "bottom";
                        $tab[$i][$j]["show"] = false;
                        $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                        $my_imgcat = get_img_cat($tab[$i][$j]['value'], $extension_icon);
                        $tab[$i][$j]['value'] = $my_imgcat;
                        $tab[$i][$j]["value"] = $tab[$i][$j]['value'];
                        $tab[$i][$j]["order"] = "category_id";
                    }
                    if ($tab[$i][$j][$value] == "contact_firstname") {
                        $contact_firstname = $tab[$i][$j]["value"];
                        $tab[$i][$j]["show"] = false;
                    }
                    if ($tab[$i][$j][$value] == "contact_lastname") {
                        $contact_lastname = $tab[$i][$j]["value"];
                        $tab[$i][$j]["show"] = false;
                    }
                    if ($tab[$i][$j][$value] == "contact_society") {
                        $contact_society = $tab[$i][$j]["value"];
                        $tab[$i][$j]["show"] = false;
                    }
                    if ($tab[$i][$j][$value] == "user_firstname") {
                        $user_firstname = $tab[$i][$j]["value"];
                        $tab[$i][$j]["show"] = false;
                    }
                    if ($tab[$i][$j][$value] == "user_lastname") {
                        $user_lastname = $tab[$i][$j]["value"];
                        $tab[$i][$j]["show"] = false;
                    }
                    if ($tab[$i][$j][$value] == "exp_user_id") {
                        $tab[$i][$j]["label"] = _CONTACT;
                        $tab[$i][$j]["size"] = "10";
                        $tab[$i][$j]["label_align"] = "left";
                        $tab[$i][$j]["align"] = "left";
                        $tab[$i][$j]["valign"] = "bottom";
                        $tab[$i][$j]["show"] = false;
                        $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                        $tab[$i][$j]["value"] = $contact->get_contact_information_from_view($_SESSION['mlb_search_current_category_id'], $contact_lastname, $contact_firstname, $contact_society, $user_lastname, $user_firstname);
                        $tab[$i][$j]["order"] = false;
                    }
                    if ($tab[$i][$j][$value] == "case_id" && $core_tools->is_module_loaded("cases") == true) {
                        $tab[$i][$j]["label"] = _CASE_NUM;
                        $tab[$i][$j]["size"] = "10";
                        $tab[$i][$j]["label_align"] = "left";
                        $tab[$i][$j]["align"] = "left";
                        $tab[$i][$j]["valign"] = "bottom";
                        $tab[$i][$j]["show"] = true;
                        $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                        $tab[$i][$j]["value"] = "<a href='" . $_SESSION['config']['businessappurl'] . "index.php?page=details_cases&module=cases&id=" . $tab[$i][$j]['value'] . "'>" . $tab[$i][$j]['value'] . "</a>";
                        $tab[$i][$j]["order"] = "case_id";
                    }
                    if ($tab[$i][$j][$value] == "case_label" && $core_tools->is_module_loaded("cases") == true) {
                        $tab[$i][$j]["label"] = _CASE_LABEL;
                        $tab[$i][$j]["size"] = "10";
                        $tab[$i][$j]["label_align"] = "left";
                        $tab[$i][$j]["align"] = "left";
                        $tab[$i][$j]["valign"] = "bottom";
                        $tab[$i][$j]["show"] = true;
                        $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                        $tab[$i][$j]["order"] = "case_id";
                    }
                    if ($tab[$i][$j][$value] == "case_closing_date" && $core_tools->is_module_loaded("cases") == true) {
                        $tab[$i][$j]["label"] = _CASE_CLOSING_DATE;
                        $tab[$i][$j]["size"] = "10";
                        $tab[$i][$j]["label_align"] = "left";
                        $tab[$i][$j]["align"] = "left";
                        $tab[$i][$j]["valign"] = "bottom";
                        $tab[$i][$j]["show"] = false;
                        if ($tab[$i][$j]['value'] <> '')
                            $tab[$i][$j]['value'] = "(" . _CASE_CLOSED . ")";
                        $tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
                        $tab[$i][$j]["order"] = "case_id";
                    }
                }
            }
        }
    } 
    ?>

    <h4><p align="center"><i class="fa fa-search fa-2x"></i> <?php echo _SEARCH_RESULTS." - ".count($tab)." "._FOUND_DOC;?></h4></p>
        <div id="inner_content">
            <?php
            $details = 'details';
            $list->list_doc($tab, $i, '', 'res_id', 'list_results_mlb_frame&module=cases&searched_item=' . $_GET['searched_item'] . '&searched_value=' . $_GET['searched_value'], 'res_id', $details . '&dir=indexing_searching', true, true, 'post', $_SESSION['config']['businessappurl'] . "index.php?display=true&module=cases&page=execute_attachement&searched_item=" . $_GET['searched_item'] . "&searched_value=" . $_GET['searched_value'], _LINK_TO_CASE, false, true, true, false, false, false, true, true, '', '', false, '', '', 'listing spec', '', false, false, null, '<input type="hidden" name="display" value="true"/><input type="hidden" name="module" value="cases" /><input type="hidden" name="page" value="execute_attachement" />', '{}', true, '', true, array (), true, $template_list, $template_to_use, false, true);
            echo "<p align='center'><a href=\"" . $_SESSION['config']['businessappurl'] . 'index.php?display=true&module=cases&page=search_adv_for_cases&searched_item=' . functions::xssafe($_GET['searched_item']) . '&searched_value=' . functions::xssafe($_GET['searched_value']) . '">' . _MAKE_NEW_SEARCH . '</a></strong></div></p>';
            ?>
        </div>
        <?php
} else {
    echo "<br/><br/><br/><p class=\"error\"><i class='fa fa-close fa-2x'></i><br />" . _NO_RESULTS . "</p><br/><p align='center'><a href=\"" . $_SESSION['config']['businessappurl'] . 'index.php?display=true&module=cases&page=search_adv_for_cases&searched_item=' . functions::xssafe($_GET['searched_item']) . '&searched_value=' . functions::xssafe($_GET['searched_value']) . '">' . _MAKE_NEW_SEARCH . '</a></strong></div></p>';
}
$core_tools->load_js();
?>
