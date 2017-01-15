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
* @brief  Advanced search form from Cases
*
* @file search_adv.php
* @author Claire Figueras <dev@maarch.org>
* @author Loïc Vinet <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup indexing_searching_mlb
*/

require_once 'core/core_tables.php';
require_once 'core/class/class_request.php';
require_once 'core/class/class_security.php';
require_once 'core/class/class_manage_status.php';
require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
    . 'class_indexing_searching_app.php';
require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'class_types.php';
$core = new core_tools();
$core->test_user();
$core->load_lang();
$core->load_html();
$core->load_header('', true, false);
$core->test_service('adv_search_mlb', 'apps');
$type = new types();
$_SESSION['indexation'] = false;
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
          . 'index.php?page=search_adv&dir=indexing_searching';
$pageLabel = _SEARCH_ADV_SHORT;
$pageId = "search_adv_mlb";
$core->manage_location_bar($pagePath, $pageLabel, $pageId, $init, $level);
/***********************************************************/

$func = new functions();
$db = new Database();
$searchObj = new indexing_searching_app();
$statusObj = new manage_status();
$sec = new security();
// load saved queries for the current user in an array
$stmt = $db->query(
	"SELECT query_id, query_name FROM " . SAVED_QUERIES 
    . " WHERE user_id = ? order by query_name"
    ,array($_SESSION['user']['UserId'])
);
$queries = array();
while ($res = $stmt->fetchObject()) {
    array_push(
        $queries,
        array(
        	'ID' => $res->query_id,
        	'LABEL' => $res->query_name,
        )
    );
}

$stmt = $db->query(
	"SELECT user_id, firstname, lastname, status FROM " . USERS_TABLE
    . " WHERE enabled = ? and status <> ? order by lastname asc"
    ,array('Y','DEL')
);
$usersList = array();
while ($res = $stmt->fetchObject()) {
    array_push(
        $usersList,
        array(
        	'ID' => functions::show_string($res->user_id),
        	'NOM' => functions::show_string($res->lastname),
        	'PRENOM' => functions::show_string($res->firstname),
        	'STATUT' => $res->status,
        )
    );
}

$collId = 'letterbox_coll';
$view = $sec->retrieve_view_from_coll_id($collId);
$where = $sec->get_where_clause_from_coll_id($collId);
if (! empty($where)) {
    $where = ' where ' . $where;
}

//Check if web brower is ie_6 or not
if (preg_match("/MSIE 6.0/", $_SERVER["HTTP_USER_AGENT"])) {
    $ieBrowser = 'true';
    $formClass = 'form';
    $hr = '<tr><td colspan="2"><hr></td></tr>';
    $size = '';
} else if (preg_match('/msie/i', $_SERVER["HTTP_USER_AGENT"])
    && !preg_match('/opera/i', $HTTP_USER_AGENT)
) {
    $ieBrowser = 'true';
    $formClass = 'forms';
    $hr = '';
     $size = '';
} else {
    $ieBrowser = 'false';
    $formClass = 'forms';
    $hr = '';
    $size = '';
    // $size = 'style="width:40px;"';
}

// building of the parameters array used to pre-load the category list and the search elements
$param = array();

// Indexes specific to doctype
$indexes = $type->get_all_indexes($collId);
for ($i = 0; $i < count($indexes); $i ++) {
    $field = $indexes[$i]['column'];
    if (preg_match('/^custom_/', $field)) {
        $field = 'doc_'.$field;
    }
    if ($indexes[$i]['type'] == 'date') {
        $arrTmp2 = array(
        	'label' => $indexes[$i]['label'],
        	'type' => 'date_range',
        	'param' => array(
        		'field_label' => $indexes[$i]['label'],
        		'id1' => $field . '_from',
        		'id2' => $field . '_to',
            ),
        );
    } else if ($indexes[$i]['type'] == 'string') {
        $arrTmp2 = array(
        	'label' => $indexes[$i]['label'],
        	'type' => 'input_text',
        	'param' => array(
        		'field_label' => $indexes[$i]['label'],
        		'other' => $size,
            ),
        );
    } else { // integer or float
        $arrTmp2 = array(
        	'label' => $indexes[$i]['label'],
        	'type' => 'num_range',
        	'param' => array(
        		'field_label' => $indexes[$i]['label'],
        		'id1' => $field . '_min',
        		'id2' => $field . '_max',
            ),
        );
    }
    $param[$field] = $arrTmp2;
}

//Coming date
$arrTmp2 = array(
	'label' => _DATE_START,
	'type' => 'date_range',
	'param' => array(
		'field_label' => _DATE_START,
		'id1' => 'admission_date_from',
		'id2' =>'admission_date_to',
    ),
);
$param['admission_date'] = $arrTmp2;

//Loaded date
$arrTmp2 = array(
	'label' => _REG_DATE,
	'type' => 'date_range',
	'param' => array(
		'field_label' => _REG_DATE,
		'id1' => 'creation_date_from',
		'id2' =>'creation_date_to',
    ),
);
$param['creation_date'] = $arrTmp2;

//Closing date
$arrTmp2 = array(
	'label' => _PROCESS_DATE,
	'type' => 'date_range',
	'param' => array(
		'field_label' => _PROCESS_DATE,
		'id1' => 'closing_date_from',
		'id2' =>'closing_date_to',
    ),
);
$param['closing_date'] = $arrTmp2;

//Document date
$arrTmp2 = array(
	'label' => _DOC_DATE,
	'type' => 'date_range',
	'param' => array(
		'field_label' => _DOC_DATE,
		'id1' => 'doc_date_from',
		'id2' =>'doc_date_to',
    ),
);
$param['doc_date'] = $arrTmp2;

//Process limit date
$arrTmp2 = array(
	'label' => _LIMIT_DATE_PROCESS,
	'type' => 'date_range',
	'param' => array(
		'field_label' => _LIMIT_DATE_PROCESS,
		'id1' => 'process_limit_date_from',
		'id2' =>'process_limit_date_to',
    ),
);
$param['process_limit_date'] = $arrTmp2;

//destinataire
$arrTmp = array();
for($i=0; $i < count($usersList); $i++)
{
    array_push($arrTmp, array('VALUE' => $usersList[$i]['ID'], 'LABEL' => $usersList[$i]['NOM']." ".$usersList[$i]['PRENOM']));
}
$arrTmp2 = array('label' => _PROCESS_RECEIPT, 'type' => 'select_multiple', 'param' => array('field_label' => _PROCESS_RECEIPT, 'label_title' => _CHOOSE_RECIPIENT_SEARCH_TITLE,
'id' => 'destinataire','options' => $arrTmp));
$param['destinataire'] = $arrTmp2;

//mail_natures
$arrTmp = array();
foreach(array_keys($_SESSION['mail_natures']) as $nature)
{
    array_push($arrTmp, array('VALUE' => $nature, 'LABEL' => $_SESSION['mail_natures'][$nature]));
}
$arrTmp2 = array('label' => _MAIL_NATURE, 'type' => 'select_simple', 'param' => array('field_label' => _MAIL_NATURE,'default_label' => addslashes(_CHOOSE_MAIL_NATURE), 'options' => $arrTmp));
$param['mail_nature'] = $arrTmp2;

//priority
$arrTmp = array();
foreach (array_keys($_SESSION['mail_priorities']) as $priority)    {
    array_push(
        $arrTmp,
        array(
        	'VALUE' => $priority,
        	'LABEL' => $_SESSION['mail_priorities'][$priority]
        )
    );
}
$arrTmp2 = array(
	'label' => _PRIORITY,
	'type' => 'select_simple',
	'param' => array(
		'field_label' => _MAIL_PRIORITY,
		'default_label' => addslashes(_CHOOSE_PRIORITY),
		'options' => $arrTmp
    )
);
$param['priority'] = $arrTmp2;

if ($_SESSION['features']['search_notes'] == 'true') {
    //annotations
    $arrTmp2 = array(
    	'label' => _NOTES,
    	'type' => 'textarea',
    	'param' => array(
    		'field_label' => _NOTES,
    		'other' => $size
        )
    );
    $param['doc_notes'] = $arrTmp2;
}

//destination (department)
if ($core->is_module_loaded('entities')) {
    $collId = 'letterbox_coll';
    $where = $sec->get_where_clause_from_coll_id($collId);
    $table = $sec->retrieve_view_from_coll_id($collId);
    if (empty($table)) {
        $table = $sec->retrieve_view_from_coll_id($collId);
    }
    if (! empty($where)) {
        $where = ' and ' . $where;
    }
    $stmt = $db->query(
    	"select distinct destination, e.short_label from " . $table . " join "
        . $_SESSION['tablename']['ent_entities']
        . " e on e.entity_id = destination " . $where
        . " group by e.short_label, destination "
    );
    $arrTmp = array();
    while ($res = $stmt->fetchObject()) {
        array_push(
            $arrTmp,
            array(
            	'VALUE' => $res->destination,
            	'LABEL' => $res->short_label
            )
        );
    }

    $arrTmp2 = array(
    	'label' => _DESTINATION_SEARCH,
    	'type' => 'select_multiple',
    	'param' => array(
    		'field_label' => _DESTINATION_SEARCH,
    		'label_title' => _CHOOSE_ENTITES_SEARCH_TITLE,
			'id' => 'services',
			'options' => $arrTmp
        )
    );
    $param['destination_mu'] = $arrTmp2;
}

//process notes
$arrTmp2 = array(
	'label' => _PROCESS_NOTES,
	'type' => 'textarea',
	'param' => array(
		'field_label' => _PROCESS_NOTES,
		'other' => $size,
		'id' => 'process_notes'
    )
);
$param['process_notes'] = $arrTmp2;

// chrono
$arrTmp2 = array(
	'label' => _CHRONO_NUMBER,
	'type' => 'input_text',
	'param' => array(
		'field_label' => _CHRONO_NUMBER,
		'other' => $size
    )
);
$param['chrono'] = $arrTmp2;

//status
$status = $statusObj->get_searchable_status();
$arrTmp = array();
for ($i = 0; $i < count($status); $i ++) {
    array_push(
        $arrTmp,
        array(
        	'VALUE' => $status[$i]['ID'],
        	'LABEL' => $status[$i]['LABEL']
        )
    );
}
array_push(
    $arrTmp,
    array(
    	'VALUE'=> 'REL1',
    	'LABEL' =>_FIRST_WARNING
    )
);
array_push(
    $arrTmp,
    array(
    	'VALUE'=> 'REL2',
    	'LABEL' =>_SECOND_WARNING
    )
);
array_push(
    $arrTmp,
    array(
    	'VALUE'=> 'LATE',
    	'LABEL' =>_LATE
    )
);

// Sorts the $param['status'] array
function cmp_status($a, $b)
{
    return strcmp(strtolower($a["LABEL"]), strtolower($b["LABEL"]));
}
usort($arrTmp, "cmp_status");
$arrTmp2 = array(
	'label' => _STATUS_PLUR,
	'type' => 'select_multiple',
	'param' => array(
		'field_label' => _STATUS,
		'label_title' => _CHOOSE_STATUS_SEARCH_TITLE,
		'id' => 'status',
		'options' => $arrTmp
    )
);
$param['status'] = $arrTmp2;

//doc_type
$stmt = $db->query(
	"SELECT type_id, description FROM  " . $_SESSION['tablename']['doctypes']
    . " where enabled = ? order by description asc"
    ,array('Y')
);
$arrTmp = array();
while ($res = $stmt->fetchObject()) {
    array_push(
        $arrTmp,
        array(
        	'VALUE' => $res->type_id,
        	'LABEL' => $db->show_string($res->description)
        )
    );
}
$arrTmp2 = array(
	'label' => _DOCTYPES,
	'type' => 'select_multiple',
	'param' => array(
		'field_label' => _DOCTYPE,
		'label_title' => _CHOOSE_DOCTYPES_SEARCH_TITLE,
		'id' => 'doctypes',
		'options' => $arrTmp
    )
);
$param['doctype'] = $arrTmp2;

//category
$arrTmp = array();
array_push(
    $arrTmp,
    array(
    	'VALUE' => '',
    	'LABEL' => _CHOOSE_CATEGORY
    )
);
foreach (array_keys($_SESSION['coll_categories']['letterbox_coll']) as $cat_id) {
    array_push(
        $arrTmp,
        array(
        	'VALUE' => $cat_id,
        	'LABEL' => $_SESSION['coll_categories']['letterbox_coll'][$cat_id]
        )
    );
}
$arrTmp2 = array(
	'label' => _CATEGORY,
	'type' => 'select_simple',
	'param' => array(
		'field_label' => _CATEGORY,
		'default_label' => '',
		'options' => $arrTmp
    )
);
$param['category'] = $arrTmp2;

//Answers types
$arrTmp = array(
    array(
    	'ID' => 'simple_mail',
    	'VALUE'=> 'true',
    	'LABEL' =>_SIMPLE_MAIL
    ),
    array(
    	'ID' => 'AR',
    	'VALUE'=> 'true',
    	'LABEL' =>_REGISTERED_MAIL
    ),
    array(
    	'ID' => 'fax',
    	'VALUE'=> 'true',
    	'LABEL' =>_FAX
    ),
    array(
    	'ID' => 'courriel',
    	'VALUE'=> 'true',
    	'LABEL' =>_MAIL
    ),
    array(
    	'ID' => 'direct',
    	'VALUE'=> 'true',
    	'LABEL' =>_DIRECT_CONTACT
    ),
    array(
    	'ID' => 'autre',
    	'VALUE'=> 'true',
    	'LABEL' =>_OTHER
    ),
    array(
    	'ID' => 'norep',
    	'VALUE'=> 'true',
    	'LABEL' =>_NO_ANSWER
    )
);
$arrTmp2 = array(
	'label' => _ANSWER_TYPE,
	'type' => 'checkbox',
	'param' => array(
		'field_label' => _ANSWER_TYPE,
		'checkbox_data' => $arrTmp
    )
);
$param['answer_type'] = $arrTmp2;

// Sorts the param array
function cmp($a, $b)
{
    return strcmp(strtolower($a["label"]), strtolower($b["label"]));
}
uasort($param, "cmp");

$tab = $searchObj->send_criteria_data($param);

// criteria list options
$srcTab = $tab[0];

$string = '';
$core->load_js();
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
<div id="case_div" style="display:none;">
    <div id="inner_content">
        <dl id="tabricator2">
        <?php
        if($_GET['searched_item'] <> 'case')
        {
            if($core->test_service('add_cases', 'cases', false) == 1)
            {
                ?>
                <dt><?php echo _CREATE_NEW_CASE;?></dt>
                <dd>
                    <h4><p align="center"><i class="fa fa-plus fa-2x"></i> <?php echo _CREATE_NEW_CASE;?><p></h4>
                    <p class="error"><?php if (isset($_SESSION['cases_error'])){ echo $_SESSION['cases_error'];}$_SESSION['cases_error'] = "";?></p>
                    <div class="blank_space">&nbsp;</div>
                    <form name="create_case" id="create_case" action="<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=cases&page=create_case" method="post" >

                        <input type="hidden" name="display" value="true" />
                        <input type="hidden" name="module" value="cases" />
                        <input type="hidden" name="page" value="create_case" />
                        <input type="hidden" name="searched_item" value="<?php functions::xecho($_GET['searched_item']);?>" />
                        <input type="hidden" name="searched_value" value="<?php functions::xecho($_GET['searched_value']);?>" />

                        <div align="center" style="display:block;" id="div_query" class="block">
                            <table align="center" border="0" width="100%" class="<?php functions::xecho($formClass);?>">

                                <tr >
                                    <td >
                                        <table border = "0" width="100%">
                                        <tr>
                                            <td width="70%"><label for="subject" class="bold" ><?php echo _CASE_LABEL;?> :</label>
                                                <input type="text" name="case_label" id="case_label" size="40"  />
                                            </td>
                                        </tr>
                                        <tr >
                                            <td width="70%"><label for="subject" class="bold" ><?php echo _CASE_DESCRIPTION;?> :</label>
                                                <!--<textarea name="case_description" id="case_description"  rows="4" ></textarea>-->
                                                <input type="text" name="case_description" id="case_description" size="40" maxlength="255"/>
                                            </td>
                                            <td>
                                                <p align="center">
                                                <input class="button" name="imageField" type="button" value="<?php echo _VALIDATE;?>" onclick="this.form.submit();" /></p>
                                             </td>
                                        </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>
                </dd>
                <?php
            }
        }
        ?>
<!-- ##########################-->

        <?php
        if($_GET['searched_item']=="res_id" || $_GET['searched_item']=="res_id_in_process")
            $title_search = _SEARCH_A_CASE;

        elseif($_GET['searched_item']=="case")
            $title_search = _SEARCH_A_RES;

        else
            $title_search = _ERROR
        ?>

            <dt><?php functions::xecho($title_search );?></dt>
            <dd>
                <h4><p align="center"><i class="fa fa-search fa-2x"></i> <?php functions::xecho($title_search );?></h4></p>
                <!-- <hr/> -->
                <br/>
        <?php if (count($queries) > 0)
        {?>
        <!--
        <form name="choose_query" id="choose_query" action="#" method="post" >
        <div align="center" style="display:block;" id="div_query">

        <label for="query"><?php echo _MY_SEARCHES;?> : </label>
        <select name="query" id="query" onchange="load_query_db(this.options[this.selectedIndex].value, 'select_criteria', 'frmsearch2', '<?php echo _SQL_ERROR;?>', '<?php echo _SERVER_ERROR;?>', '<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=manage_query';?>');return false;" >
            <option id="default_query" value=""><?php echo _CHOOSE_SEARCH;?></option>
            <?php for($i=0; $i< count($queries);$i++)
            {
            ?><option value="<?php functions::xecho($queries[$i]['ID']);?>" id="query_<?php functions::xecho($queries[$i]['ID']);?>"><?php functions::xecho($queries[$i]['LABEL']);?></option><?php }?>
        </select>

        <input name="del_query" id="del_query" value="<?php echo _DELETE_QUERY;?>" type="button"  onclick="del_query_confirm();" class="button" style="display:none" />
        </div>
        </form>
        -->
        <?php } ?>
        <!--<form name="frmsearch2" method="get" action="<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=search_adv_result&dir=indexing_searching"  id="frmsearch2" class="<?php functions::xecho($formClass);?>">-->
        <form name="frmsearch2" method="get" action="<?php echo $_SESSION['config']['businessappurl'];?>index.php"  id="frmsearch2" class="<?php functions::xecho($formClass);?>">
        <input type="hidden" name="display" value="true" />
        <input type="hidden" name="dir" value="indexing_searching" />
        <input type="hidden" name="page" value="search_adv_result" />

        <?php
        if (isset($_GET['schema']) && $_GET['schema'] <> '') {
        ?>
            <input type="hidden" name="schema" value="<?php functions::xecho($_GET['schema']);?>" />
        <?php
        }
        ?>
        <input type="hidden" name="specific_case" value="attach_to_case" />
        <!-- #########################To search a ressource for this res############################-->
        <input type="hidden" name="searched_item" value="<?php functions::xecho($_GET['searched_item']);?>" />
        <input type="hidden" name="searched_value" value="<?php functions::xecho($_GET['searched_value']);?>" />


        <?php

        if ($_GET['searched_item'] == "res_id" || $_GET['searched_item'] == "res_id_in_process")
        {

            echo '<input type="hidden" name="template" value="group_case" />';
        }
        ?>

        <!-- #############################################################################-->

        <table align="center" border="0" width="100%">

            <?php
            if($core->is_module_loaded("cases") == true)
            { ?>
             <tr>
                <td colspan="2" ><h2><?php echo _CASE_INFO;?></h2></td>
            </tr>
            <tr>
                <td>
                    <div class="block">
                    <table border="0" width="100%">

                        <tr>
                            <td width="70%"><label for="numcase" class="bold" ><?php echo _CASE_NUMBER;?> :</label>
                                <input type="text" name="numcase" id="numcase" <?php functions::xecho($size);?>  />
                                <input type="hidden" name="meta[]" value="numcase#numcase#input_text" />
                            </td>
                            <td><em><?php echo _CASE_NUMBER_HELP;?></em></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%"><label for="labelcase" class="bold" ><?php echo _CASE_LABEL;?> :</label>
                                <input type="text" name="labelcase" id="labelcase" <?php functions::xecho($size);?>  />
                                <input type="hidden" name="meta[]" value="labelcase#labelcase#input_text" />
                            </td>
                            <td><em><?php echo _CASE_LABEL_HELP;?></em></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="70%"><label for="descriptioncase" class="bold" ><?php echo _CASE_DESCRIPTION;?> :</label>
                                <input type="text" name="descriptioncase" id="descriptioncase" <?php functions::xecho($size);?>  />
                                <input type="hidden" name="meta[]" value="descriptioncase#descriptioncase#input_text" />
                            </td>
                            <td><em><?php echo _CASE_DESCRIPTION_HELP;?></em></td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    </div>
                    <div class ="block_end">&nbsp;</div>
                </td>
                <td>
                    <p align="center">
                    </p>
                </td>
            </tr>
        <?php
    }    ?>
<!--
            <tr>
                <td colspan="2" ><h2><?php echo _LETTER_INFO;?></h2></td>
            </tr>
            <tr >
                <td >
                <div class="block">
                    <table border = "0" width="100%">
                    <tr>
                        <td width="70%"><label for="subject" class="bold" ><?php echo _MAIL_OBJECT;?>:</label>
                            <input type="text" name="subject" id="subject" <?php functions::xecho($size);?>  />
                            <input type="hidden" name="meta[]" value="subject#subject#input_text" />
                        </td>
                        <td><em><?php echo _MAIL_OBJECT_HELP;?></em></td>
                    </tr>
                    <tr>
                        <td width="70%"><label for="fulltext" class="bold" ><?php echo _FULLTEXT;?>:</label>
                            <input type="text" name="fulltext" id="fulltext" <?php functions::xecho($size);?>  />
                            <input type="hidden" name="meta[]" value="fulltext#fulltext#input_text" />
                        </td>
                        <td><em><?php echo _FULLTEXT_HELP;?></em></td>
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

                    </table>
                    </div>
                    <div class="block_end">&nbsp;</div>
                </td>

            </tr>
            <tr><td colspan="2"><hr/></td></tr>
        <tr>
        <td  >
        <div class="block">
         <table border = "0" width="100%">
               <tr>
             <td width="70%">
                <label class="bold"><?php echo _ADD_PARAMETERS;?>:</label>
                <select name="select_criteria" id="select_criteria" style="display:inline;" onchange="add_criteria(this.options[this.selectedIndex].id, 'frmsearch2', <?php functions::xecho($ieBrowser);?>, '<?php echo _ERROR_IE_SEARCH;?>');">
                    <?php functions::xecho($srcTab);?>
                </select>
             </td>

                <td width="30%"><em><?php echo _ADD_PARAMETERS_HELP;?></em></td>
                </tr>
         </table>
         </div>
         <div class="block_end">&nbsp;</div>
        </td></tr> -->
        </table>
            <p align="right">
<!--                         <input class="button_search_adv" name="imageField" type="button" value="" onclick="valid_search_form('frmsearch2');this.form.submit();" />
                <br/>
                <input class="button_search_adv_text" name="imageField" type="button" value="<?php echo _SEARCH;?>" onclick="valid_search_form('frmsearch2');this.form.submit();" /> -->
                <a onclick="valid_search_form('frmsearch2');$('frmsearch2').submit();" href="#">
                    <i class="fa fa-search fa-5x" title="<?php echo _SEARCH;?>"></i>
                </a>
            </p>
        </form>


        <br/>
        <div align="right">
        </div>

        <script type="text/javascript">
        load_query(valeurs, loaded_query, 'frmsearch2', '<?php echo $ieBrowser;?>', '<?php echo _ERROR_IE_SEARCH;?>');
        </script>

    </dd>
</dl>
</div>
<!-- <div align="center"><input type="button" class="button" name="close" id="close" value="<?php echo _CLOSE_WINDOW;?>" onclick="self.close();" /></div> -->
</div>

<script type="text/javascript">
 var item  = $('case_div');
  var tabricator1 = new Tabricator('tabricator2', 'DT');
  if(item)
    {
     item.style.display='block';
    }
</script>
