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
* @brief  Advanced search form
*
* @file search_adv.php
* @author Claire Figueras <dev@maarch.org>
* @author Loïc Vinet <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup indexing_searching_mlb
*/

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_manage_status.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."usergroups_controler.php");
require_once('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR."class_indexing_searching_app.php");
require_once('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR."class_types.php");
$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();

$_SESSION['search']['plain_text'] = "";
$_SESSION['fromContactCheck'] = "";

if (isset($_REQUEST['fromValidateMail'])) {
    $_SESSION['fromValidateMail'] = "ok";
} else {
    $_SESSION['fromValidateMail'] = "";
}

$type = new types();
$func = new functions();
$conn = new Database();

$search_obj = new indexing_searching_app();
$status_obj = new manage_status();
$sec = new security();
$_SESSION['indexation'] = false;

if (isset($_REQUEST['exclude'])){
    $_SESSION['excludeId'] = $_REQUEST['exclude'];
}

$mode = 'normal';
if(isset($_REQUEST['mode'])&& !empty($_REQUEST['mode']))
{
    $mode = $func->wash($_REQUEST['mode'], "alphanum", _MODE);
}
if($mode == 'normal')
{
    $core_tools->test_service('adv_search_mlb', 'apps');
/****************Management of the location bar  ************/
$init = false;
if(isset($_REQUEST['reinit']) && $_REQUEST['reinit']  == "true")
{
    $init = true;
    $_SESSION['current_search_query'] = "";
}
$level = "";
if(isset($_REQUEST['level']) && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1))
{
    $level = $_REQUEST['level'];
}
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=search_adv&dir=indexing_searching';
$page_label = _SEARCH_ADV_SHORT;
$page_id = "search_adv_mlb";
$core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
}
elseif($mode == 'popup' || $mode == 'frame')
{
    $core_tools->load_html();
    $core_tools->load_header('', true, false);
    $time = $core_tools->get_session_time_expire();
    $_SESSION['stockCheckbox']= '';
    ?><body>
    <div id="container" style="height:auto;">

            <div class="error" id="main_error">
                <?php functions::xecho($_SESSION['error']);?>
            </div>
            <div class="info" id="main_info">
                <?php functions::xecho($_SESSION['info']);?>
            </div><?php
}

// load saved queries for the current user in an array
$stmt = $conn->query("SELECT query_id, query_name FROM ".$_SESSION['tablename']['saved_queries']." WHERE user_id = ? order by query_name", array($_SESSION['user']['UserId']));
$queries = array();
while($res = $stmt->fetchObject())
{
    array_push($queries, array('ID'=>$res->query_id, 'LABEL' => $res->query_name));
}

$stmt = $conn->query("SELECT user_id, firstname, lastname, status FROM ".$_SESSION['tablename']['users']." WHERE enabled = 'Y' and status <> 'DEL' order by lastname asc");
$users_list = array();
while($res = $stmt->fetchObject())
{
    array_push($users_list, array('ID' => functions::show_string($res->user_id), 'NOM' => functions::show_string($res->lastname), 'PRENOM' => functions::show_string($res->firstname), 'STATUT' => $res->status));
}

$coll_id = 'letterbox_coll';
$view = $sec->retrieve_view_from_coll_id($coll_id);
$where = $sec->get_where_clause_from_coll_id($coll_id);
if(!empty($where))
{
    $where = ' where '.$where;
}

//Check if web brower is ie_6 or not
if (preg_match("/MSIE 6.0/", $_SERVER["HTTP_USER_AGENT"])) {
    $browser_ie = 'true';
    $class_for_form = 'form';
    $hr = '<tr><td colspan="2"><hr></td></tr>';
    $size = '';
} elseif(preg_match('/msie/i', $_SERVER["HTTP_USER_AGENT"]) && !preg_match('/opera/i', $HTTP_USER_AGENT) )
{
    $browser_ie = 'true';
    $class_for_form = 'forms';
    $hr = '';
     $size = '';
}
else
{
    $browser_ie = 'false';
    $class_for_form = 'forms';
    $hr = '';
     $size = '';
   // $size = 'style="width:40px;"';
}

// building of the parameters array used to pre-load the category list and the search elements
$param = array();

// Indexes specific to doctype
$indexes = $type->get_all_indexes($coll_id);

for($i=0;$i<count($indexes);$i++)
{
    $field = $indexes[$i]['column'];
    if(preg_match('/^custom_/', $field))
    {
        $field = 'doc_'.$field;
    }
    if($indexes[$i]['type_field'] == 'select')
    {
        $arr_tmp = array();
        array_push($arr_tmp, array('VALUE' => '', 'LABEL' => _CHOOSE.'...'));
        for($j=0; $j<count($indexes[$i]['values']);$j++)
        {
            array_push($arr_tmp, array('VALUE' => $indexes[$i]['values'][$j]['id'], 'LABEL' => $indexes[$i]['values'][$j]['label']));
        }
        $arr_tmp2 = array('label' => $indexes[$i]['label'], 'type' => 'select_simple', 'param' => array('field_label' => $indexes[$i]['label'],'default_label' => '', 'options' => $arr_tmp));
    }
    elseif($indexes[$i]['type'] == 'date')
    {
        $arr_tmp2 = array('label' => $indexes[$i]['label'], 'type' => 'date_range', 'param' => array('field_label' => $indexes[$i]['label'], 'id1' => $field.'_from', 'id2' =>$field.'_to'));
    }
    else if($indexes[$i]['type'] == 'string')
    {
        $arr_tmp2 = array('label' => $indexes[$i]['label'], 'type' => 'input_text', 'param' => array('field_label' => $indexes[$i]['label'], 'other' => $size));
    }
    else  // integer or float
    {
        $arr_tmp2 = array('label' => $indexes[$i]['label'], 'type' => 'num_range', 'param' => array('field_label' => $indexes[$i]['label'], 'id1' => $field.'_min', 'id2' =>$field.'_max'));
    }
    $param[$field] = $arr_tmp2;
}

//Coming date
$arr_tmp2 = array('label' => _DATE_START, 'type' => 'date_range', 'param' => array('field_label' => _DATE_START, 'id1' => 'admission_date_from', 'id2' =>'admission_date_to'));
$param['admission_date'] = $arr_tmp2;

//Loaded date
$arr_tmp2 = array('label' => _REG_DATE, 'type' => 'date_range', 'param' => array('field_label' => _REG_DATE, 'id1' => 'creation_date_from', 'id2' =>'creation_date_to'));
$param['creation_date'] = $arr_tmp2;

//Closing date
$arr_tmp2 = array('label' => _CLOSING_DATE, 'type' => 'date_range', 'param' => array('field_label' => _CLOSING_DATE, 'id1' => 'closing_date_from', 'id2' =>'closing_date_to'));
$param['closing_date'] = $arr_tmp2;

//Document date
$arr_tmp2 = array('label' => _DOC_DATE, 'type' => 'date_range', 'param' => array('field_label' => _DOC_DATE, 'id1' => 'doc_date_from', 'id2' =>'doc_date_to'));
$param['doc_date'] = $arr_tmp2;

//Process limit date
$arr_tmp2 = array('label' => _LIMIT_DATE_PROCESS, 'type' => 'date_range', 'param' => array('field_label' => _LIMIT_DATE_PROCESS, 'id1' => 'process_limit_date_from', 'id2' =>'process_limit_date_to'));
$param['process_limit_date'] = $arr_tmp2;

//Creation date pj
$arr_tmp2 = array('label' => "("._PJ.") " . _CREATION_DATE, 'type' => 'date_range', 'param' => array('field_label' => "("._PJ.") " . _CREATION_DATE, 'id1' => 'creation_date_pj_from', 'id2' =>'creation_date_pj_to'));
$param['creation_date_pj'] = $arr_tmp2;

//destinataire
$arr_tmp = array();
for($i=0; $i < count($users_list); $i++)
{
    array_push($arr_tmp, array('VALUE' => $users_list[$i]['ID'], 'LABEL' => $users_list[$i]['NOM']." ".$users_list[$i]['PRENOM']));
}
$arr_tmp2 = array('label' => _PROCESS_RECEIPT, 'type' => 'select_multiple', 'param' => array('field_label' => _PROCESS_RECEIPT, 'label_title' => _CHOOSE_RECIPIENT_SEARCH_TITLE,
'id' => 'destinataire','options' => $arr_tmp));
$param['destinataire'] = $arr_tmp2;

//mail_natures
$arr_tmp = array();
foreach(array_keys($_SESSION['mail_natures']) as $nature)
{
    array_push($arr_tmp, array('VALUE' => $nature, 'LABEL' => $_SESSION['mail_natures'][$nature]));
}
$arr_tmp2 = array('label' => _MAIL_NATURE, 'type' => 'select_simple', 'param' => array('field_label' => _MAIL_NATURE,'default_label' => addslashes(_CHOOSE_MAIL_NATURE), 'options' => $arr_tmp));
$param['mail_nature'] = $arr_tmp2;

//priority
$arr_tmp = array();
foreach(array_keys($_SESSION['mail_priorities']) as $priority)
{
    array_push($arr_tmp, array('VALUE' => $priority, 'LABEL' => $_SESSION['mail_priorities'][$priority]));
}
$arr_tmp2 = array('label' => _PRIORITY, 'type' => 'select_simple', 'param' => array('field_label' => _MAIL_PRIORITY,'default_label' => addslashes(_CHOOSE_PRIORITY), 'options' => $arr_tmp));
$param['priority'] = $arr_tmp2;

//Type de pièce jointe
$arr_tmp = array();
foreach(array_keys($_SESSION['attachment_types']) as $attachment_types)
{
    array_push($arr_tmp, array('VALUE' => $attachment_types, 'LABEL' => $_SESSION['attachment_types'][$attachment_types]));
}
$arr_tmp2 = array('label' => "("._PJ.") "._ATTACHMENT_TYPES, 'type' => 'select_simple', 'param' => array('field_label' => "("._PJ.") "._ATTACHMENT_TYPES,'default_label' => addslashes(_CHOOSE_ATTACHMENT_TYPE), 'options' => $arr_tmp));
$param['attachment_types'] = $arr_tmp2;


// dest
/*$arr_tmp2 = array('label' => _DEST, 'type' => 'input_text', 'param' => array('field_label' => _DEST, 'other' => $size));
$param['dest'] = $arr_tmp2;

//shipper
$arr_tmp2 = array('label' => _SHIPPER, 'type' => 'input_text', 'param' => array('field_label' => _SHIPPER, 'other' => $size));
$param['shipper'] = $arr_tmp2;
*/
if($_SESSION['features']['search_notes'] == 'true')
{
    //annotations
    $arr_tmp2 = array('label' => _NOTES, 'type' => 'textarea', 'param' => array('field_label' => _NOTES, 'other' => $size));
    $param['doc_notes'] = $arr_tmp2;
}

//tags 
if($core_tools->is_module_loaded('tags'))
{
    $arr_tmptag = array();
    require_once 'modules/tags/class/TagControler.php' ;
    require_once 'modules/tags/tags_tables_definition.php';
    $tag = new tag_controler;
    $tag_return_value = $tag -> get_all_tags($coll_id);
 
    if ($tag_return_value){
        foreach($tag_return_value as $tagelem)
        {
            array_push($arr_tmptag, array('VALUE' => functions::protect_string_db($tagelem['tag_label']), 'LABEL' => $tagelem['tag_label']));
        }
    }
    else
    {
        array_push($arr_tmptag, array('VALUE' => '', 'LABEL' => _TAGNONE));
    }
    $param['tag_mu'] = array('label' => _TAG_SEARCH, 'type' => 'select_multiple', 'param' => array('field_label' => _TAG_SEARCH, 'label_title' => _CHOOSE_TAG,
    'id' => 'tags','options' => $arr_tmptag));

}

//thesaurus 
if($core_tools->is_module_loaded('thesaurus'))
{
    $arr_tmpthesaurus = array();
    require_once 'modules/thesaurus/class/class_modules_tools.php' ;
    $thesaurus = new thesaurus;
    $thesaurus_list = $thesaurus->getList();
 
    if ($thesaurus_list){
        foreach($thesaurus_list as $key=>$value)
        {
            array_push($arr_tmpthesaurus, array('VALUE' => functions::protect_string_db($value->thesaurus_id), 'LABEL' => $value->thesaurus_name));
        }
    }
    else
    {
        array_push($arr_tmpthesaurus, array('VALUE' => '', 'LABEL' => _THESAURUSNONE));
    }
    $param['thesaurus_mu'] = array('label' => _THESAURUS, 'type' => 'select_multiple', 'param' => array('field_label' => _THESAURUS, 'label_title' => _CHOOSE_THESAURUS,
    'id' => 'thesaurus','options' => $arr_tmpthesaurus));

}

//destination (department)
if($core_tools->is_module_loaded('entities'))
{
    $where = $sec->get_where_clause_from_coll_id($coll_id);
    $table = $sec->retrieve_view_from_coll_id($coll_id);
    if(empty($table))
    {
        $table = $sec->retrieve_table_from_coll($coll_id);
    }
    if(!empty($where))
    {
        $where = ' where '.$where;
    }

    $stmt = $conn->query("SELECT DISTINCT ".$table.".destination, e.short_label FROM ".$table." join ".$_SESSION['tablename']['ent_entities']." e on e.entity_id = ".$table.".destination 
                            ".$where." group by e.short_label, ".$table.".destination order by e.short_label");

    $arr_tmp = array();
    while($res = $stmt->fetchObject())
    {
        array_push($arr_tmp, array('VALUE' => $res->destination, 'LABEL' => $res->short_label));
    }

    $param['destination_mu'] = array('label' => _DESTINATION_SEARCH, 'type' => 'select_multiple', 'param' => array('field_label' => _DESTINATION_SEARCH, 'label_title' => _CHOOSE_ENTITES_SEARCH_TITLE,
'id' => 'services','options' => $arr_tmp));

}

// Folder
if($core_tools->is_module_loaded('folder'))
{
    $arr_tmp2 = array('label' => _MARKET, 'type' => 'input_text', 'param' => array('field_label' => _MARKET, 'other' => $size));
    $param['market'] = $arr_tmp2;
    $arr_tmp2 = array('label' => _PROJECT, 'type' => 'input_text', 'param' => array('field_label' => _PROJECT, 'other' => $size));
    $param['project'] = $arr_tmp2;
}

//process notes
$arr_tmp2 = array('label' => _PROCESS_NOTES, 'type' => 'textarea', 'param' => array('field_label' => _PROCESS_NOTES, 'other' => $size, 'id' => 'process_notes'));
$param['process_notes'] = $arr_tmp2;

// chrono
$arr_tmp2 = array('label' => _CHRONO_NUMBER, 'type' => 'input_text', 'param' => array('field_label' => _CHRONO_NUMBER.' <span class="green_asterisk" ><i class="fa fa-star" style="vertical-align:50%"></i></span>', 'other' => $size));
$param['chrono'] = $arr_tmp2;

// identifier
$arr_tmp2 = array('label' => _REFERENCE_MAIL, 'type' => 'input_text', 'param' => array('field_label' => _REFERENCE_MAIL, 'other' => $size));
$param['identifier'] = $arr_tmp2;

// description
$arr_tmp2 = array('label' => _OTHERS_INFORMATIONS, 'type' => 'input_text', 'param' => array('field_label' => _OTHERS_INFORMATIONS, 'other' => $size));
$param['description'] = $arr_tmp2;

// Monitoring number
$arr_tmp2 = array('label' => _MONITORING_NUMBER, 'type' => 'input_text', 'param' => array('field_label' => _MONITORING_NUMBER, 'other' => $size));
$param['reference_number'] = $arr_tmp2;

//status
$status = $status_obj->get_searchable_status();
$arr_tmp = array();
for($i=0; $i < count($status); $i++)
{
    array_push($arr_tmp, array('VALUE' => $status[$i]['ID'], 'LABEL' => $status[$i]['LABEL']));
}
array_push($arr_tmp,  array('VALUE'=> 'REL1', 'LABEL' =>_FIRST_WARNING));
array_push($arr_tmp,  array('VALUE'=> 'REL2', 'LABEL' =>_SECOND_WARNING));
array_push($arr_tmp,  array('VALUE'=> 'LATE', 'LABEL' =>_LATE));

// Sorts the $param['status'] array
function cmp_status($a, $b)
{
    return strcmp(strtolower($a["LABEL"]), strtolower($b["LABEL"]));
}
usort($arr_tmp, "cmp_status");
$arr_tmp2 = array('label' => _STATUS_PLUR, 'type' => 'select_multiple', 'param' => array('field_label' => _STATUS,'label_title' => _CHOOSE_STATUS_SEARCH_TITLE,'id' => 'status',  'options' => $arr_tmp));
$param['status'] = $arr_tmp2;

//confidentifality
$arr_tmp = array();
array_push($arr_tmp,  array('VALUE'=> 'Y', 'LABEL' =>_YES));
array_push($arr_tmp,  array('VALUE'=> 'N', 'LABEL' =>_NO));
$arr_tmp2 = array('label' => _CONFIDENTIALITY, 'type' => 'select_simple', 'param' => array('field_label' => _CONFIDENTIALITY,'id' => 'confidentiality',  'options' => $arr_tmp));
$param['confidentiality'] = $arr_tmp2;

//doc_type
$stmt = $conn->query("SELECT type_id, description  FROM  " 
    . $_SESSION['tablename']['doctypes'] . " WHERE enabled = 'Y' and coll_id = ? order by description asc", array($coll_id)
);
$arr_tmp = array();
while ($res=$stmt->fetchObject())
{
    array_push($arr_tmp, array('VALUE' => $res->type_id, 'LABEL' => functions::show_string($res->description)));
}
$arr_tmp2 = array('label' => _DOCTYPES_MAIL, 'type' => 'select_multiple', 'param' => array('field_label' => _DOCTYPES_MAIL,'label_title' => _CHOOSE_DOCTYPES_MAIL_SEARCH_TITLE, 'id' => 'doctypes', 'options' => $arr_tmp));
$param['doctype'] = $arr_tmp2;

//category
$arr_tmp = array();
array_push($arr_tmp, array('VALUE' => '', 'LABEL' => _CHOOSE_CATEGORY));
foreach (array_keys($_SESSION['coll_categories']['letterbox_coll']) as $cat_id) {
    if ($cat_id <> 'default_category') {
        array_push(
            $arr_tmp, 
            array(
                'VALUE' => $cat_id, 
                'LABEL' => $_SESSION['coll_categories']['letterbox_coll'][$cat_id]
            )
        );
    }
}
$arr_tmp2 = array('label' => _CATEGORY, 'type' => 'select_simple', 'param' => array('field_label' => _CATEGORY,'default_label' => '', 'options' => $arr_tmp));
$param['category'] = $arr_tmp2;

$usergroups_controler = new usergroups_controler();
$array_groups = $usergroups_controler->getAllUsergroups("", false);

//signatory group
$arr_tmp = array();
for($iGroups=0; $iGroups< count($array_groups);$iGroups++) {
    array_push($arr_tmp, array('VALUE' => $array_groups[$iGroups]->group_id, 'LABEL' => $array_groups[$iGroups]->group_desc));
}
$arr_tmp2 = array('label' => _SIGNATORY_GROUP, 'type' => 'select_simple', 'param' => array('field_label' => _SIGNATORY_GROUP,'default_label' => addslashes(_CHOOSE_GROUP), 'options' => $arr_tmp));
$param['signatory_group'] = $arr_tmp2;

// signatory name
$arr_tmp2 = array('label' => _SIGNATORY_NAME, 'type' => 'input_text', 'param' => array('field_label' => _SIGNATORY_NAME, 'other' => $size));
$param['signatory_name'] = $arr_tmp2;


 //Addresses contact externe
    $arr_tmp2 = array('label' => _ADDRESSES_MAJ, 'type' => 'input_text', 'param' => array('field_label' => _ADDRESSES_MAJ));
    $param['addresses_id'] = $arr_tmp2;

//contact_type
$stmt = $conn->query("SELECT id, label  FROM  contact_types order by label asc", array());
$arr_tmp = array();
while ($res=$stmt->fetchObject())
{
    array_push($arr_tmp, array('VALUE' => $res->id, 'LABEL' => functions::show_string($res->label)));
}
$arr_tmp2 = array('label' => _CONTACT_TYPE, 'type' => 'select_simple', 'param' => array('field_label' => _CONTACT_TYPE,'label_title' => _CONTACT_TYPE, 'id' => 'contact_type', 'options' => $arr_tmp));
$param['contact_type'] = $arr_tmp2;

// Sorts the param array
function cmp($a, $b)
{
    return strcmp(strtolower($a["label"]), strtolower($b["label"]));
}
uasort($param, "cmp");

$tab = $search_obj->send_criteria_data($param);

// criteria list options
$src_tab = $tab[0];

//$core_tools->show_array($param);

$core_tools->load_js();
?>
<script type="text/javascript" src="<?php echo $_SESSION['config']['businessappurl'];?>static.php?filename=search_adv.js" ></script>
<script type="text/javascript">
<!--
var valeurs = { <?php echo $tab[1];?>};
var loaded_query = <?php if(isset($_SESSION['current_search_query']) && !empty($_SESSION['current_search_query']))
{ echo $_SESSION['current_search_query'];}else{ echo '{}';}?>;

function del_query_confirm()
{
    if(confirm('<?php echo _REALLY_DELETE.' '._THIS_SEARCH.'?';?>'))
    {
        del_query_db($('query').options[$('query').selectedIndex], 'select_criteria', 'frmsearch2', '<?php echo _SQL_ERROR;?>', '<?php echo _SERVER_ERROR;?>', '<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=manage_query';?>');
        return false;
    }
}
-->
</script>
<?php if($_GET['mode']!='popup'){ ?>
<h1>
    <i class="fa fa-search fa-2x"></i> <?php echo _ADV_SEARCH_MLB;?>
</h1>
<?php } ?>
<div id="inner_content">

<?php if (count($queries) > 0)
{?>
<form name="choose_query" id="choose_query" action="#" method="post" >
<div align="center" style="display:block;" id="div_query">

<label for="query"><?php echo _MY_SEARCHES;?> : </label>
<select name="query" id="query" onchange="load_query_db(this.options[this.selectedIndex].value, 'select_criteria', 'parameters_tab', '<?php echo _SQL_ERROR;?>', '<?php echo _SERVER_ERROR;?>', '<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=manage_query';?>');return false;" >
    <option id="default_query" value=""><?php echo _CHOOSE_SEARCH;?></option>
    <?php for($i=0; $i< count($queries);$i++)
    {
    ?><option value="<?php functions::xecho($queries[$i]['ID']);?>" id="query_<?php functions::xecho($queries[$i]['ID']);?>"><?php functions::xecho($queries[$i]['LABEL']);?></option><?php }?>
</select>

<input name="del_query" id="del_query" value="<?php echo _DELETE_QUERY;?>" type="button"  onclick="del_query_confirm();" class="button" style="display:none" />
</div>
</form>
<?php } ?>
<form name="frmsearch2" method="post" action="<?php 
    if($mode == 'normal') {
        //echo $_SESSION['config']['businessappurl'] . 'index.php';
        echo $_SESSION['config']['businessappurl'] 
            . 'index.php?display=true&dir=indexing_searching&page=search_adv_result'; 
    } elseif ($mode == 'frame' || $mode == 'popup'){ 
        echo $_SESSION['config']['businessappurl'] 
            . 'index.php?display=true&dir=indexing_searching&page=search_adv_result';
    }?>"  id="frmsearch2" class="<?php functions::xecho($class_for_form);?>">
<input type="hidden" name="dir" value="indexing_searching" />
    <input type="hidden" name="page" value="search_adv_result" />
<input type="hidden" name="mode" value="<?php functions::xecho($mode);?>" />
<?php if($mode == 'frame' || $mode == 'popup'){?>
    <input type="hidden" name="display" value="true" />
    <input type="hidden" name="action_form" value="<?php functions::xecho($_REQUEST['action_form']);?>" />
    <input type="hidden" name="modulename" value="<?php functions::xecho($_REQUEST['modulename']);?>" />
<?php
}
if(isset($_REQUEST['nodetails']))
{?>
<input type="hidden" name="nodetails" value="true" />
<?php
}?>
<table align="center" border="0" width="100%">
    <tr>
        <td>
            <a href="#" onclick="clear_search_form('frmsearch2','select_criteria');clear_q_list();erase_contact_external_id('contactid', 'contactid_external');erase_contact_external_id('contactid_internal', 'contact_internal_id');">
                <i class="fa fa-refresh fa-4x" title="<?php echo _CLEAR_SEARCH;?>"></i>
            </a>
        </td>
        <td align="right">
            <span style="display:none;">
                <input name="imageField" type="submit" value="" onclick="valid_search_form('frmsearch2');this.form.submit();" />
            </span>
            <a href="#" onclick="valid_search_form('frmsearch2');$('frmsearch2').submit();">
                <i class="fa fa-search fa-4x" title="<?php echo _SEARCH;?>"></i>
            </a>
        </td>
    </tr>
</table>
<table align="center" border="0" width="100%">
<?php
            if($core_tools->is_module_loaded("basket") == true) { ?>
             <tr>
                <td colspan="2" ></td>
            </tr>
            <tr>
                <td>
                    <div class="block">
                    <h2><?php echo _SEARCH_SCOPE;?></h2>
                    <table border="0" width="100%" class="content">
                        <tr>
                            <td width="70%">
                                <label for="baskets" class="bold" ><?php echo _SPREAD_SEARCH_TO_BASKETS;?>:</label>
                                <input type="hidden" name="meta[]" value="baskets_clause#baskets_clause#select_simple" />
                                <select name="baskets_clause" id="baskets_clause">
                                    <option id="true" value="true"><?php echo _ALL_BASKETS;?></option>
                                    <option id="false" value="false"><?php echo _NO;?></option>
                                    <?php 
                                    if($_REQUEST['mode'] != 'popup') {
                                        for($i=0; $i< count($_SESSION['user']['baskets']);$i++) {
                                            if (
                                                $_SESSION['user']['baskets'][$i]['coll_id'] == $coll_id 
                                                && $_SESSION['user']['baskets'][$i]['is_folder_basket'] == 'N'
                                                && $_SESSION['user']['baskets'][$i]['id'] <> 'IndexingBasket'
                                                && $_SESSION['user']['baskets'][$i]['id'] <> 'EmailsToQualify'
                                                && $_SESSION['user']['baskets'][$i]['id'] <> 'InitBasket'
                                                && $_SESSION['user']['baskets'][$i]['id'] <> 'RetourCourrier'
                                                && $_SESSION['user']['baskets'][$i]['id'] <> 'QualificationBasket'
                                            ) {
                                                ?><option id="<?php 
                                                    functions::xecho($_SESSION['user']['baskets'][$i]['id']);
                                                    ?>" value="<?php 
                                                    functions::xecho($_SESSION['user']['baskets'][$i]['id']);
                                                    ?>" ><?php 
                                                    functions::xecho($_SESSION['user']['baskets'][$i]['desc']);
                                                ?></option>
                                                <?php
                                            }
                                        }
                                    } ?>
                                </select>
                            </td>
                            <td><em><?php echo _SEARCH_SCOPE_HELP;?></em></td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    </div>
                </td>
                <td>
                    <p align="center">
                    </p>
                </td>
            </tr>
            <tr><td colspan="2"><hr/></td></tr>
            <?php
            }
            if($core_tools->is_module_loaded("cases") == true)
            { ?>
             <tr>
                <td colspan="2" ></td>
            </tr>
            <tr>
                <td>
                    <div class="block">
                    <h2><?php echo _CASE_INFO;?></h2>
                    <table border="0" width="100%" class="content">

                        <tr>
                            <td width="70%"><label for="numcase" class="bold" ><?php echo _CASE_NUMBER;?>:</label>
                                <input type="text" name="numcase" id="numcase" <?php functions::xecho($size);?>  />
                                <input type="hidden" name="meta[]" value="numcase#numcase#input_text" />
                            </td>
                            <td><em><?php echo _CASE_NUMBER_HELP;?></em></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%"><label for="labelcase" class="bold" ><?php echo _CASE_LABEL;?>:</label>
                                <input type="text" name="labelcase" id="labelcase" <?php functions::xecho($size);?>  />
                                <input type="hidden" name="meta[]" value="labelcase#labelcase#input_text" />
                            </td>
                            <td><em><?php echo _CASE_LABEL_HELP;?></em></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%"><label for="descriptioncase" class="bold" ><?php echo _CASE_DESCRIPTION;?>:</label>
                                <input type="text" name="descriptioncase" id="descriptioncase" <?php functions::xecho($size);?>  />
                                <input type="hidden" name="meta[]" value="descriptioncase#descriptioncase#input_text" />
                            </td>
                            <td><em><?php echo _CASE_DESCRIPTION_HELP;?></em></td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    </div>
                </td>
                <td>
                    <p align="center">
                    </p>
                </td>
            </tr>
        <?php
    }    ?>




    <tr>
        <td colspan="2" ></td>
    </tr>
    <tr >
        <td >
        <div class="block">
        <h2><?php echo _LETTER_INFO;?></h2>
            <table border = "0" width="100%" class="content" style="position:relative;">
                <tr>
                    <td width="70%"><label for="subject" class="bold" ><?php echo _MAIL_OBJECT;?>:</label>
                        <input type="text" name="subject" id="subject" <?php functions::xecho($size);?>  />
                        <input type="hidden" name="meta[]" value="subject#subject#input_text" /><span class="green_asterisk"><i class="fa fa-star"></i></span>
                    </td>
                    <td><em><?php echo _MAIL_OBJECT_HELP;?></em></td>
                </tr>
                <tr>
                    <td width="70%"><label for="fulltext" class="bold" ><?php echo _FULLTEXT;?>:</label>
                        <input type="text" name="fulltext" id="fulltext" <?php functions::xecho($size);?>  />
                        <input type="hidden" name="meta[]" value="fulltext#fulltext#input_text" />
                        <a href="javascript::" onclick="new Effect.toggle('iframe_fulltext_help', 'blind', {delay:0.2})"><i class="fa fa-search" title="<?php echo _HELP_FULLTEXT_SEARCH;?>"></i></a>
                    </td>
                    <td><em><?php echo _FULLTEXT_HELP;?></em></td>
                </tr>
                <tr id="iframe_fulltext_help" name="iframe_fulltext_help" style="display:none;">
                    <td width="70%" >
                        <iframe src="<?php echo $_SESSION['config']['businessappurl'] 
                            . 'index.php?display=true&page=fulltext_search_help';?>" frameborder="0" width="100%" height="227px">
                        </iframe>
                    </td>
                </tr>
                <tr>
                    <td width="70%"><label for="numged" class="bold"><?php echo _N_GED;?>:</label>
                        <input type="text" name="numged" id="numged" <?php functions::xecho($size);?>  />
                        <input type="hidden" name="meta[]" value="numged#numged#input_text" />
                    </td>
                    <td><em><?php echo _N_GED_HELP;?></em></td>
                </tr>
                <tr>
                    <td width="70%"><label for="multifield" class="bold" ><?php echo _MULTI_FIELD;?>:</label>
                        <input type="text" name="multifield" id="multifield" <?php functions::xecho($size);?>  />
                        <input type="hidden" name="meta[]" value="multifield#multifield#input_text" />
                    </td>
                    <td><em><?php echo _MULTI_FIELD_HELP;?></em></td>
                </tr>
                <tr>
                    <td width="70%"><label for="contactid" class="bold"><?php echo _CONTACT_EXTERNAL;?>:</label>
                        <input type="text" name="contactid" id="contactid" onkeyup="erase_contact_external_id('contactid', 'contactid_external');"/>
                        <input type="hidden" name="meta[]" value="contactid#contactid#input_text" /><span class="green_asterisk"><i class="fa fa-star"></i></span>
                        <div id="contactListByName" class="autocomplete"></div>
                        <script type="text/javascript">
                            initList_hidden_input('contactid', 'contactListByName', '<?php 
                                echo $_SESSION['config']['businessappurl'];?>index.php?display=true&page=contacts_v2_list_by_name', 'what', '2', 'contactid_external');
                        </script>
                        <input id="contactid_external" name="contactid_external" type="hidden" />
                    </td>
                    <td><em><?php echo "";?></em></td>
                </tr>
                <tr>
                    <td width="70%"><label for="contactid_internal" class="bold"><?php echo _CONTACT_INTERNAL;?>:</label>
                        <input type="text" name="contactid_internal" id="contactid_internal" onkeyup="erase_contact_external_id('contactid_internal', 'contact_internal_id');"/>
                        <input type="hidden" name="meta[]" value="contactid_internal#contactid_internal#input_text" />
                        <div id="contactInternalListByName" class="autocomplete"></div>
                        <script type="text/javascript">
                            initList_hidden_input('contactid_internal', 'contactInternalListByName', '<?php 
                                echo $_SESSION['config']['businessappurl'];?>index.php?display=true&dir=indexing_searching&page=users_list_by_name_search', 'what', '2', 'contact_internal_id');
                        </script>
                        <input id="contact_internal_id" name="contact_internal_id" type="hidden" />
                    </td>
                    <td><em><?php echo "";?></em></td>
                </tr>
            </table>
            </div>
        </td>
    </tr>
    <tr>
        <td><span class="green_asterisk"><i class="fa fa-star" style="vertical-align:50%"></i></span><?php echo _SEARCH_INDICATION;?></td>
    </tr>
    <tr><td colspan="2"><hr/></td></tr>
<tr>
<td >
<div class="block">
<h2><?php echo _ADD_PARAMETERS;?>&nbsp;:&nbsp;<select name="select_criteria" id="select_criteria" style="display:inline;" onchange="add_criteria(this.options[this.selectedIndex].id, 'parameters_tab', <?php 
        echo $browser_ie;?>, '<?php echo _ERROR_IE_SEARCH;?>');window.location.href = '#bottom';">
            <?php echo $src_tab;?>
        </select></h2>
<table border = "0" width="100%" class="content" id="parameters_tab">
       <tr>
        <td width="100%" colspan="3" style="text-align:center;"><em><?php echo _ADD_PARAMETERS_HELP;?></em></td>
        </tr>
 </table>
 </div>
</td></tr>
</table>

<table align="center" border="0" width="100%">
    <tr>
        <td>
            <a href="#" onclick="clear_search_form('frmsearch2','select_criteria');clear_q_list();erase_contact_external_id('contactid', 'contactid_external');erase_contact_external_id('contactid_internal', 'contact_internal_id');">
             <i class="fa fa-refresh fa-4x" title="<?php echo _CLEAR_FORM;?>"></i>
            </a>
        </td>
        <td align="right">
            <a href="#" onclick="valid_search_form('frmsearch2');$('frmsearch2').submit();">
                <i class="fa fa-search fa-4x" title="<?php echo _SEARCH;?>"></i>
            </a>
        </td>
    </tr>
</table>

</form>
<br/>
</div>

<script type="text/javascript">
load_query(valeurs, loaded_query, 'parameters_tab', '<?php echo $browser_ie;?>', '<?php echo _ERROR_IE_SEARCH;?>');
<?php if(isset($_REQUEST['init_search']))
{
    ?>clear_search_form('frmsearch2','select_criteria');clear_q_list();erase_contact_external_id('contactid', 'contactid_external');erase_contact_external_id('contactid_internal', 'contact_internal_id'); <?php
}?>
</script>

<?php if($mode == 'popup' || $mode == 'frame')
{
    echo '</div>';
    if($mode == 'popup')
    {
    ?><br/><div align="center"><input type="button" name="close" class="button" value="<?php echo _CLOSE_WINDOW;?>" onclick="self.close();" /></div> <?php
    }
    echo '</body></html>';
}
