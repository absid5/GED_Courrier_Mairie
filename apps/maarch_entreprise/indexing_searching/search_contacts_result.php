<?php

/*
*   Copyright 2014 Maarch
*
*   This file is part of Maarch Framework.
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
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*
* @brief  Advanced search form management
*
* @file search_contacts_result.php
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
*/

require_once('core/class/class_request.php');
require_once('core/class/class_security.php');
require_once('apps/' . $_SESSION['config']['app_id'] 
    . '/class/class_indexing_searching_app.php'
);
require_once('apps/' . $_SESSION['config']['app_id'] . '/class/class_types.php');
$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();
$is = new indexing_searching_app();
$func = new functions();
$req = new request();
$type = new types();
$fields = "";
$orderby = "";

$coll_id = 'letterbox_coll';
$indexes = $type->get_all_indexes($coll_id);
//$func->show_array($indexes);
$_SESSION['error_search'] = '';
$_SESSION['searching']['comp_query'] = '';
// define the row of the start
if (isset($_REQUEST['start'])) {
    $start = $_REQUEST['start'];
} else {
    $start = 0;
}

$mode = 'normal';
$core_tools->test_service('search_contacts', 'apps');

$where_request = "";
$arrayPDO = array();
$case_view = false;
 $_ENV['date_pattern'] = "/^[0-3][0-9]-[0-1][0-9]-[1-2][0-9][0-9][0-9]$/";
$json_txt = '{';

/**
 * Array $_REQUEST['meta'] exemple
(
    [0] => copies#copies_false,copies_true#radio
    [1] => objet#objet#input_text
    [2] => numged#numged#input_text
    [3] => multifield#multifield#input_text
    [4] => category#category#select_simple
    [5] => doc_date#doc_date_from,doc_date_to#date_range
)
**/
//$func->show_array($_REQUEST['meta']);exit;
if (count($_REQUEST['meta']) > 0) {
    //Verif for parms sended by url
    if ($_GET['meta']) {
        for ($m=0; $m<count($_REQUEST['meta']);$m++) {
            if (strstr($_REQUEST['meta'][$m], '||') == true) {
                $_REQUEST['meta'][$m] = str_replace('||', '#', $_REQUEST['meta'][$m]);
            }
        }
    }
    $opt_indexes = array();
    $_SESSION['meta_search'] = $_REQUEST['meta'];
    for ($i=0;$i<count($_REQUEST['meta']);$i++) {
        $tab = explode('#', $_REQUEST['meta'][$i]);

        $id_val = $tab[0];
        $json_txt .= "'".$tab[0]."' : { 'type' : '".$tab[2]."', 'fields' : {";
        $tab_id_fields = explode(',', $tab[1]);
        //$func->show_array($tab_id_fields);
        for ($j=0; $j<count($tab_id_fields);$j++) {

            // CONTACT TYPE
            if ($tab_id_fields[$j] == 'contact_type' && !empty($_REQUEST['contact_type']))
            {
                $json_txt .= " 'contact_type' : ['".addslashes(trim($_REQUEST['contact_type']))."'],";
                $where_request .= " contact_type = :contactType and ";
                $arrayPDO = array_merge($arrayPDO, array(":contactType" => $_REQUEST['contact_type']));
            }
            // SOCIETY
            elseif ($tab_id_fields[$j] == 'society' && (!empty($_REQUEST['society']) || $_REQUEST['society'] <> '') )
            {
                $json_txt .= " 'society' : ['".addslashes(trim($_REQUEST['society']))."'],";
                $where_request .= " lower(society) like lower(:society) and ";
                $arrayPDO = array_merge($arrayPDO, array(":society" => '%'.$_REQUEST['society'].'%'));
            }
            // SOCIETY SHORT
            elseif ($tab_id_fields[$j] == 'society_short' && (!empty($_REQUEST['society_short']) || $_REQUEST['society_short'] <> '') )
            {
                $json_txt .= " 'society_short' : ['".addslashes(trim($_REQUEST['society_short']))."'],";
                $where_request .= " lower(society_short) like lower(:societyShort) and ";
                $arrayPDO = array_merge($arrayPDO, array(":societyShort" => '%'.$_REQUEST['society_short'].'%'));
            }
            // LASTNAME
            elseif ($tab_id_fields[$j] == 'lastname' && (!empty($_REQUEST['lastname']) ||$_REQUEST['lastname'] <> '') )
            {
                $json_txt .= " 'lastname' : ['".addslashes(trim($_REQUEST['lastname']))."'],";
                $where_request .= " lower(contact_lastname) like lower(:contactLastname) and ";
                $arrayPDO = array_merge($arrayPDO, array(":contactLastname" => '%'.$_REQUEST['lastname'].'%'));
            }
            // FIRSTNAME
            elseif ($tab_id_fields[$j] == 'firstname' && (!empty($_REQUEST['firstname']) ||$_REQUEST['firstname'] <> '') )
            {
                $json_txt .= " 'firstname' : ['".addslashes(trim($_REQUEST['firstname']))."'],";
                $where_request .= " lower(contact_firstname) like lower(:contactFirstname) and ";
                $arrayPDO = array_merge($arrayPDO, array(":contactFirstname" => '%'.$_REQUEST['firstname'].'%'));
            }
            // CREATED BY
            elseif ($tab_id_fields[$j] == 'created_by' && !empty($_REQUEST['created_by_id']))
            {
                $json_txt .= " 'created_by' : ['".addslashes(trim($_REQUEST['created_by']))."'], 'created_by_id' : ['".addslashes(trim($_REQUEST['created_by_id']))."'],";
                $where_request .= " contact_user_id = :createdById and ";
                $arrayPDO = array_merge($arrayPDO, array(":createdById" => $_REQUEST['created_by_id']));
            }
            // CONTACTS PURPOSE
            elseif ($tab_id_fields[$j] == 'contact_purpose' && !empty($_REQUEST['contact_purposes_id']))
            {
                $json_txt .= " 'contact_purpose' : ['".addslashes(trim($_REQUEST['contact_purpose']))."'], 'contact_purposes_id' : ['".addslashes(trim($_REQUEST['contact_purposes_id']))."'],";
                $where_request .= " contact_purposes_id = :contactPurposeId and ";
                $arrayPDO = array_merge($arrayPDO, array(":contactPurposeId" => $_REQUEST['contact_purposes_id']));
            }
            else  // opt indexes check
            {
                $tmp = $type->search_checks($indexes, $tab_id_fields[$j], $_REQUEST[$tab_id_fields[$j]] );
                //$func->show_array($tmp);
                $json_txt .= $tmp['json_txt'];
                $where_request .= $tmp['where'];
            }
        }
        $json_txt = preg_replace('/,$/', '', $json_txt);
        $json_txt .= "}},";
    }
    $json_txt = preg_replace('/,$/', '', $json_txt);
}
$json_txt = preg_replace("/,$/", "", $json_txt);
$json_txt .= '}';

$_SESSION['current_search_query'] = $json_txt;
if (!empty($_SESSION['error'])) {
    $_SESSION['error_search'] = '<br /><div class="error">'._MUST_CORRECT_ERRORS.' : <br /><br /><strong>'.$_SESSION['error_search'].'<br /><a href="'.$_SESSION['config']['businessappurl'].'index.php?page=search_adv&dir=indexing_searching">'._CLICK_HERE_TO_CORRECT.'</a></strong></div>';
    ?>
    <script  type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl'].'index.php?page=search_adv_error&dir=indexing_searching';?>';</script>
    <?php
    exit();
} else {
    $where_request = trim($where_request);
    $_SESSION['searching']['where_request'] = $where_request;
    $_SESSION['searching']['where_request_parameters'] = $arrayPDO;
}

if (empty($_SESSION['error_search'])) {

    ?>
    <script type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl'].'index.php?page=list_results_contacts&dir=indexing_searching';?>';</script>
    <?php
    exit();
}
