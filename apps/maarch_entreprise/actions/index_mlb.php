<?php

/*
*    Copyright 2008, 2013 Maarch
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
* @brief   Action : indexing a file
*
* Open a modal box to displays the indexing form, make the form checks and loads
*  the result in database. Used by the core (manage_action.php page).
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/

//$_SESSION['validStep'] = "ko";
include_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'definition_mail_categories.php';
require_once 'core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
        . 'class_security.php';
require_once 'core/core_tables.php';
require_once 'core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
    . 'class_request.php';
require_once 'core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
    . 'class_resource.php';
require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
    . 'class_business_app_tools.php';
require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'class_types.php';
require_once 'modules' . DIRECTORY_SEPARATOR . 'basket' . DIRECTORY_SEPARATOR
    . 'class' . DIRECTORY_SEPARATOR . 'class_modules_tools.php';
require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
    . 'class_indexing_searching_app.php';
require_once 'core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
    . 'docservers_controler.php';
require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'class_chrono.php';
require_once 'core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
    . 'class_history.php';
require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
    . 'class_contacts_v2.php';

$_SESSION['is_multi_contact'] = '';
unset($_SESSION['m_admin']['contact']);

$core = new core_tools();
$contacts_v2 = new contacts_v2();
if ($core->is_module_loaded('entities')) {
    require_once 'modules/entities/entities_tables.php';
}
if ($core->is_module_loaded('folder')) {
    require_once 'modules/folder/folder_tables.php';
}
require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'apps_tables.php';
/**
* $confirm  bool false
*/
$confirm = false;
/**
* $etapes  array Contains only one etap : form
*/
$etapes = array('form');
/**
* $frm_width  Width of the modal (empty)
*/
$frm_width='';
/**
* $frm_height  Height of the modal (empty)
*/
$frm_height = '';
/**
* $mode_form  Mode of the modal : fullscreen
*/
$mode_form = 'fullscreen';
/**
 * Returns the indexing form text
 *
 * @param $values Array Not used here
 * @param $pathManageAction String Path to the PHP file called in Ajax
 * @param $actionId String Action identifier
 * @param $table String Table
 * @param $module String Origin of the action
 * @param $collId String Collection identifier
 * @param $mode String Action mode 'mass' or 'page'
 * @return String The form content text
 **/
function get_form_txt($values, $pathManageAction,  $actionId, $table, $module, $collId, $mode )
{
    $_SESSION['category_id'] = '';
    if (preg_match("/MSIE 6.0/", $_SERVER["HTTP_USER_AGENT"])) {
        $ieBrowser = true;
        $displayValue = 'block';
    } else if (preg_match('/msie/i', $_SERVER["HTTP_USER_AGENT"])
        && ! preg_match('/opera/i', $_SERVER["HTTP_USER_AGENT"])
    ) {
        $ieBrowser = true;
        $displayValue = 'block';
    } else {
        $ieBrowser = false;
        $displayValue = 'table-row';
    }
    $_SESSION['req'] = "action";
    $resId = $values[0];
    $frmStr = '';

    $type = new types();
    $sec = new security();
    $b = new basket();
    $core = new core_tools();
    $business = new business_app_tools();
    if ($_SESSION['features']['show_types_tree'] == 'true') {
        $doctypes = $type->getArrayStructTypes($collId);
    } else {
        $doctypes = $type->getArrayTypes($collId);
    }
    $today = date('d-m-Y');
    $tmp = $business->get_titles();
    $titles = $tmp['titles'];
    $defaultTitle = $tmp['default_title'];
    
    if ($core->is_module_loaded('entities')) {
        $EntitiesIdExclusion = array();
        $db = new Database();

        if (count($_SESSION['user']['redirect_groupbasket'][$_SESSION['current_basket']['id']][$actionId]['entities']) > 0) {
            $stmt = $db->query(
                "SELECT entity_id FROM "
                . ENT_ENTITIES . " WHERE entity_id not in ("
                . $_SESSION['user']['redirect_groupbasket'][$_SESSION['current_basket']['id']][$actionId]['entities']
                . ") and enabled= 'Y' order by entity_id"
            );

            while ($res = $stmt->fetchObject()) {
                array_push($EntitiesIdExclusion, $res->entity_id);
            }
        }
    }
    //diffusion list in this basket ?
    if($_SESSION['current_basket']['difflist_type'] == 'entity_id'){
        $target_model = 'document.getElementById(\'destination\').options[document.getElementById(\'destination\').selectedIndex]';
    }else if($_SESSION['current_basket']['difflist_type'] == 'type_id'){
        $target_model = 'document.getElementById(\'type_id\').options[document.getElementById(\'type_id\').selectedIndex]';
        $func_load_listdiff = 'load_listmodel('.$target_model.', \'diff_list_div\', \'indexing\', $(\'category_id\').value);';
    }else{
        $target_model = 'document.getElementById(\'destination\').options[document.getElementById(\'destination\').selectedIndex]';
    }

    //var_dump($EntitiesIdExclusion);
    require_once 'modules' . DIRECTORY_SEPARATOR . 'entities'
        . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
        . 'class_manage_entities.php';
    $ent = new entity();
    $allEntitiesTree= array();
    $allEntitiesTree = $ent->getShortEntityTreeAdvanced(
        $allEntitiesTree, 'all', '', $EntitiesIdExclusion, 'all'
    );

    // Select statuses from groupbasket
    $statuses = array();
    $db = new Database();

    /* Basket of ABS users */
    if($_SESSION['current_basket']['abs_basket']=='1'){
        $query="SELECT group_id FROM usergroup_content WHERE user_id=? AND primary_group='Y'";
        $stmt = $db->query($query, array($_SESSION['current_basket']['basket_owner']));
        $grp_status=$stmt->fetchObject();
        $owner_usr_grp=$grp_status->group_id;
        $owner_basket_id=str_replace("_".$_SESSION['current_basket']['basket_owner'], "", $_SESSION['current_basket']['id']);

    }else{
        $owner_usr_grp=$_SESSION['user']['primarygroup'];
        $owner_basket_id=$_SESSION['current_basket']['id'];
    }
    $query = "SELECT status_id, label_status FROM " . GROUPBASKET_STATUS . " left join " . $_SESSION['tablename']['status']
        . " on status_id = id "
        . " WHERE basket_id= ? and (group_id = ? OR group_id in (select group_id from user_baskets_secondary where user_id = ? and basket_id = ?)) and action_id = ?";
    $stmt = $db->query($query, array($owner_basket_id, $owner_usr_grp, $_SESSION['user']['UserId'], $owner_basket_id, $actionId));

    if($stmt->rowCount() > 0) {
        while($status = $stmt->fetchObject()) {
            $statuses[] = array(
                'ID' => $status->status_id,
                'LABEL' => functions::show_string($status->label_status)
            );
        }
    }
    $frmStr .= '<h2 class="tit" id="action_title">' . _INDEXING_MLB;
    $frmStr .= '</h2>';
    $frmStr .='<i onmouseover="this.style.cursor=\'pointer\';" '.
             'onclick="javascript:window.top.location.href=\''
            . $_SESSION['config']['businessappurl'] . 'index.php\';reinit();" class="fa fa-times-circle fa-2x closeModale" title="'._CLOSE.'"/>';
    $frmStr .='</i>';
    $frmStr .= '<div id="validleft">';
    $frmStr .= '<div id="index_div" style="display:none;";>';
    
    $frmStr .= '<div id="frm_error_' . $actionId . '" class="indexing_error">'
            . '</div>';
    $frmStr .= '<form name="index_file" method="post" id="index_file" action="#"'
            . ' class="forms indexingform" style="text-align:left;">';
    $frmStr .= '<input type="hidden" name="values" id="values" value="' . $resId
            . '" />';
    $frmStr .= '<input type="hidden" name="action_id" id="action_id" value="'
            . $actionId . '" />';
    $frmStr .= '<input type="hidden" name="mode" id="mode" value="' . $mode
            . '" />';
    $frmStr .= '<input type="hidden" name="table" id="table" value="' . $table
            . '" />';
    $frmStr .= '<input type="hidden" name="coll_id" id="coll_id" value="'
            . $collId . '" />';
    $frmStr .= '<input type="hidden" name="module" id="module" value="'
            . $module . '" />';
    $frmStr .= '<input type="hidden" name="req" id="req" value="second_request"'
            . ' />';
    $frmStr .= '<input type="hidden" id="check_days_before" value="'.$_SESSION['check_days_before'].'"'
            . ' />';

    $frmStr .= '<div  style="display:block">';

    /*if (! isset($_SESSION['FILE']['extension'])
        || $_SESSION['FILE']['extension'] == ""
    ) {*/

        $frmStr .= '<div  style="display:block" id="choose_file_div">';
        $frmStr .= '<iframe src="' . $_SESSION['config']['businessappurl']
                . 'index.php?display=true&dir=indexing_searching&page='
                . 'choose_file" name="choose_file" id="choose_file" '
                . 'frameborder="0" scrolling="no" width="100%" height="30">'
                . '</iframe>';
        $frmStr .= '</div>';
    //}
    $frmStr .= '<hr />';
    
    $frmStr .= '<h4 onclick="new Effect.toggle(\'general_infos_div\', \'blind\', {delay:0.2});'
        . 'whatIsTheDivStatus(\'general_infos_div\', \'divStatus_general_infos_div\');" '
        . 'class="categorie" style="width:90%;" onmouseover="this.style.cursor=\'pointer\';">';
    $frmStr .= ' <span id="divStatus_general_infos_div" style="color:#1C99C5;"><i class="fa fa-minus-square-o"></i></span>&nbsp;' 
        ._GENERAL_INFO;
    $frmStr .= '</h4>';
    $frmStr .= '<div id="general_infos_div"  style="display:inline">';
    $frmStr .= '<div class="ref-unit">';
    
    $frmStr .= '<table width="100%" align="center" border="0" '
        . 'id="indexing_fields" style="display:block;">';

    if ($core->test_service('add_links', 'apps', false)) {
        $frmStr .= '<tr id="attachment_tr" style="display:' . $displayValue
                . ';">';
        $frmStr .= '<td>'
                . _LINK_TO_DOC . '</td>';
        $frmStr .= '<td>&nbsp;</td>';
        $frmStr .= '<td class="indexing_field"><input type="radio" '
                . 'name="attachment" id="attach" value="true" '
                . 'onclick="show_attach(\'true\');"'
                . ' /> '
                . _YES . ' <input type="radio" name="attachment" id="no_attach"'
                . ' value="false" checked="checked" '
                . 'onclick="show_attach(\'false\');"'
                . ' /> '
                . _NO . '</td>';
        $frmStr .= ' <td><span class="red_asterisk" id="attachment_mandatory" '
                . 'style="display:inline;vertical-align:middle;"><i class="fa fa-star"></i></span></td>';
        $frmStr .= '</tr>';

        $frmStr .= '<tr id="attach_show" style="display:none;">';
            $frmStr .= '<td>&nbsp;</td>';
            $frmStr .= '<td style="text-align: right;">';
                $frmStr .= '<a ';
                  $frmStr .= 'href="javascript://" ';
                  $frmStr .= 'onclick="window.open(';
                    $frmStr .= '\'' . $_SESSION['config']['businessappurl'] . 'index.php?display=true&dir=indexing_searching&page=search_adv&mode=popup&action_form=show_res_id&modulename=attachments&init_search&nodetails\', ';
                    $frmStr .= '\'search_doc_for_attachment\', ';
                    $frmStr .= '\'scrollbars=yes,menubar=no,toolbar=no,resizable=yes,status=no,width=1100,height=775\'';
                  $frmStr .= ');"';
                  $frmStr .= ' title="' . _SEARCH . '"';
                $frmStr .= '>';
                    $frmStr .= '<span style="font-weight: bold;">';
                        $frmStr .= '<i class="fa fa-link"></i>';
                    $frmStr .= '</span>';
                $frmStr .= '</a>';
            $frmStr .= '</td>';
            $frmStr .= '<td style="text-align: right;">';
                $frmStr .= '<input ';
                  $frmStr .= 'type="text" ';
                  $frmStr .= 'name="res_id" ';
                  $frmStr .= 'id="res_id" ';
                  $frmStr .= 'class="readonly" ';
                  $frmStr .= 'readonly="readonly" ';
                  $frmStr .= 'value="" ';
                $frmStr .= '/>';
            $frmStr .= '</td>';
            $frmStr .= '<td>';
                $frmStr .= '<span class="red_asterisk" id="attachment_link_mandatory" '
                . 'style="display:inline;vertical-align:middle;"><i class="fa fa-star"></i></span>';
            $frmStr .= '</td>';
        $frmStr .= '</tr>';

    }
    
    /*** Category ***/
    $frmStr .= '<tr id="category_tr" style="display:' . $displayValue . ';">';
    $frmStr .= '<td style="width:200px;"><label for="category_id" '
            . 'class="form_title" >' . _CATEGORY . '</label></td>';
    $frmStr .= '<td style="width:10px;">&nbsp;</td>';
    $frmStr .= '<td class="indexing_field"><select name="category_id" '
            . 'id="category_id" onchange="clear_error(\'frm_error_' . $actionId
            . '\');change_category(this.options[this.selectedIndex].value, \''
            . $displayValue . '\',  \'' . $_SESSION['config']['businessappurl']
            . 'index.php?display=true&dir=indexing_searching&page='
            . 'change_category\',  \'' . $_SESSION['config']['businessappurl']
            . 'index.php?display=true&page=get_content_js\');change_category_actions(\'' 
            . $_SESSION['config']['businessappurl'] 
            . 'index.php?display=true&dir=indexing_searching&page=change_category_actions'
            . '&resId=' . $resId . '&collId=' . $collId . '\');hideSelectFile();">';
    $frmStr .= '<option value="">' . _CHOOSE_CATEGORY . '</option>';
    foreach (array_keys($_SESSION['coll_categories']['letterbox_coll']) as $catId) {
        if ($catId <> 'default_category') {
            $frmStr .= '<option value="' . functions::xssafe($catId) . '"';
            if ($_SESSION['coll_categories']['letterbox_coll']['default_category'] == $catId
                || (isset($_SESSION['indexing']['category_id'])
                    && $_SESSION['indexing']['category_id'] == $catId)
            ) {
                $frmStr .= 'selected="selected"';
            }

            $frmStr .= '>' . functions::xssafe($_SESSION['coll_categories']['letterbox_coll'][$catId]) . '</option>';
        }
    }
    $frmStr .= '</select></td>';
    $frmStr .= '<td><span class="red_asterisk" id="category_id_mandatory" '
            . 'style="display:inline;vertical-align:text-top"><i class="fa fa-star"></i></span></td>';
    $frmStr .= '</tr>';
    /*** Doctype ***/
    $frmStr .= '<tr id="type_id_tr" style="display:' . $displayValue . ';">';
    $frmStr .= '<td><span class="form_title" '
            . 'id="doctype_res" style="display:none;">' . _DOCTYPE
            . '</span><span class="form_title" id="doctype_mail" '
            . 'style="display:inline;">' . _DOCTYPE_MAIL
            . '</span></td>';
    $frmStr .= '<td>&nbsp;</td>';
    $frmStr .= '<td class="indexing_field"><select name="type_id" id="type_id" '
            . 'onchange="clear_error(\'frm_error_' . $actionId . '\');'
            . 'changePriorityForSve(this.options[this.selectedIndex].value,\''
            . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
            . '&dir=indexing_searching&page=priority_for_sve\');change_doctype(this.options[this.selectedIndex].value, \''
            . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
            . '&dir=indexing_searching&page=change_doctype\', \''
            . _ERROR_DOCTYPE . '\', \'' . $actionId . '\', \''
            . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
            . '&page=get_content_js\', \'' . $displayValue . '\')'.$func_load_listdiff.'">';
    $frmStr .= '<option value="">' . _CHOOSE_TYPE . '</option>';
if ($_SESSION['features']['show_types_tree'] == 'true') {
        for ($i = 0; $i < count($doctypes); $i ++) {
            $frmStr .= '<optgroup value="" class="' //doctype_level1
                    . $doctypes[$i]['style'] . '" label="'
                    . functions::xssafe($doctypes[$i]['label']) . '" >';
            for ($j = 0; $j < count($doctypes[$i]['level2']); $j ++) {
                $frmStr .= '<optgroup value="" class="' //doctype_level2
                        . $doctypes[$i]['level2'][$j]['style'] .'" label="&nbsp;&nbsp;'
                        . functions::xssafe($doctypes[$i]['level2'][$j]['label']) . '" >';
                for ($k = 0; $k < count($doctypes[$i]['level2'][$j]['types']);
                    $k ++
                ) {
                    $frmStr .= '<option data-object_type="type_id" value="'
                            . functions::xssafe($doctypes[$i]['level2'][$j]['types'][$k]['id'])
                            . '" title="'
                            . functions::xssafe($doctypes[$i]['level2'][$j]['types'][$k]['label'])
                            . '" label="'
                            . functions::xssafe($doctypes[$i]['level2'][$j]['types'][$k]['label'])
                            . '">&nbsp;&nbsp;&nbsp;&nbsp;'
                            . functions::xssafe($doctypes[$i]['level2'][$j]['types'][$k]['label'])
                            . '</option>';
                }
                $frmStr .= '</optgroup>'; 
            }
            $frmStr .= '</optgroup>'; 
        }
    } else {
        for ($i = 0; $i < count($doctypes); $i ++) {
            $frmStr .= '<option value="' . functions::xssafe($doctypes[$i]['ID']) . '" >'
                    . functions::xssafe($doctypes[$i]['LABEL']) . '</option>';
        }
    }
    $frmStr .= '</select></td>';
    $frmStr .= '<td><span class="red_asterisk" id="type_id_mandatory" '
            . 'style="display:inline;vertical-align:text-top"><i class="fa fa-star"></i></span></td>';
    $frmStr .= '</tr>';
    /*** Priority ***/
    $frmStr .= '<tr id="priority_tr" style="display:' . $displayValue . ';">';
    $frmStr .= '<td><label for="priority" class="form_title" >' . _PRIORITY
            . '</label></td>';
    $frmStr .= '<td>&nbsp;</td>';
    $frmStr .= '<td class="indexing_field"><select name="priority" '
            . 'id="priority" onChange="updateProcessDate(\''
            . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
            . '&dir=indexing_searching&page=update_process_date\');" onFocus="updateProcessDate(\''
            . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
            . '&dir=indexing_searching&page=update_process_date\');clear_error(\'frm_error_' . $actionId
            . '\');">';
    $frmStr .= '<option value="">' . _CHOOSE_PRIORITY . '</option>';
    for ($i = 0; $i < count($_SESSION['mail_priorities']); $i ++) {
        $frmStr .= '<option value="' . functions::xssafe($i) . '" ';
        if ($_SESSION['default_mail_priority'] == $i) {
            $frmStr .= 'selected="selected"';
        }
        $frmStr .= '>' . functions::xssafe($_SESSION['mail_priorities'][$i]) . '</option>';
    }
    $frmStr .= '</select></td>';
    $frmStr .= '<td><span class="red_asterisk" id="priority_mandatory" '
            . 'style="display:inline;vertical-align:text-top"><i class="fa fa-star"></i></span></td>';
    $frmStr .= '</tr>';

    /*** Confidentiality ***/
    $frmStr .= '<tr id="confidentiality_tr" style="display:' . $displayValue
            . ';">';
    $frmStr .= '<td><label for="confidentiality" class="form_title" >'
            . _CONFIDENTIALITY . ' </label></td>';
    $frmStr .= '<td>&nbsp;</td>';
    $frmStr .= '<td class="indexing_field"><input type="radio" '
            . 'name="confidentiality" id="confidential" value="Y" />'
            . _YES . ' <input type="radio" name="confidentiality" id="no_confidential"'
            . ' value="N" checked="checked" />'
            . _NO . '</td>';
    $frmStr .= ' <td><span class="red_asterisk" id="confidentiality_mandatory" '
            . 'style="display:inline;vertical-align:text-top"><i class="fa fa-star"></i></span>&nbsp;</td>';
    $frmStr .= '</tr>';

    /*** Doc date ***/
    $frmStr .= '<tr id="doc_date_tr" style="display:' . $displayValue . ';">';
    $frmStr .= '<td><label for="doc_date" class="form_title" '
            . 'id="mail_date_label" style="display:inline;" >' . _MAIL_DATE
            . '</label><label for="doc_date" class="form_title" '
            . 'id="doc_date_label" style="display:none;" >' . _DOC_DATE
            . '</label></td>';
    $frmStr .= '<td>&nbsp;</td>';
    $frmStr .= '<td class="indexing_field"><input name="doc_date" type="text" '
            . 'id="doc_date" value="" placeholder="JJ-MM-AAAA" onfocus="checkRealDate(\'docDate\');" onChange="checkRealDate(\'docDate\');"  onclick="clear_error(\'frm_error_'
            . $actionId . '\');showCalender(this);" /></td>';
    $frmStr .= '<td><span class="red_asterisk" id="doc_date_mandatory" '
            . 'style="display:inline;vertical-align:text-top"><i class="fa fa-star"></i></span></td>';
    $frmStr .= '</tr >';
    /*** Author ***/
    $frmStr .= '<tr id="author_tr" style="display:' . $displayValue . ';">';
    $frmStr .= '<td><label for="author" class="form_title" >' . _AUTHOR
            . '</label></td>';
    $frmStr .= '<td>&nbsp;</td>';
    $frmStr .= '<td class="indexing_field"><input name="author" type="text" '
            . 'id="author" onchange="clear_error(\'frm_error_' . $actionId
            . '\');"/></td>';
    $frmStr .= '<td><span class="red_asterisk" id="author_mandatory" '
            . 'style="display:inline;"><i class="fa fa-star"></i></span>&nbsp;</td>';
    $frmStr .= '</tr>';
    /*** Admission date ***/
    $frmStr .= '<tr id="admission_date_tr" style="display:' . $displayValue
            . ';">';
    $frmStr .= '<td><label for="admission_date" class="form_title" >'
            . _RECEIVING_DATE . '</label></td>';
    $frmStr .= '<td>&nbsp;</td>';
    $frmStr .= '<td class="indexing_field"><input name="admission_date" '
            . 'type="text" id="admission_date" value="' . $today
            . '" onclick="clear_error(\'frm_error_' . $actionId . '\');'
            . 'showCalender(this);" onChange="checkRealDate(\'admissionDate\');updateProcessDate(\''
            . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
            . '&dir=indexing_searching&page=update_process_date\');" onFocus="checkRealDate(\'admissionDate\');updateProcessDate(\''
            . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
            . '&dir=indexing_searching&page=update_process_date\');"/></td>';
    $frmStr .= '<td><span class="red_asterisk" id="admission_date_mandatory" '
            . 'style="display:inline;vertical-align:text-top"><i class="fa fa-star"></i></span></td>';
    $frmStr .= '</tr>';
	
    /*** Contact ***/
    $frmStr .= '<tr id="contact_choose_tr" style="display:' . $displayValue
            . ';">';
    $frmStr .= '<td><label for="type_contact" class="form_title" >'
            . '<span id="exp_contact_choose_label">' . _SHIPPER_TYPE . '</span>'
            . '<span id="dest_contact_choose_label">' . _DEST_TYPE . '</span>'
            . '</label></td>';
    $frmStr .= '<td>&nbsp;</td>';
    $frmStr .= '<td class="indexing_field"><input type="radio" class="check" '
            . 'name="type_contact" id="type_contact_internal" value="internal" '
            . 'onclick="clear_error(\'frm_error_' . $actionId . '\');reset_check_date_exp();'
            . 'change_contact_type(\'' . $_SESSION['config']['businessappurl']
            . 'index.php?display=true&dir=indexing_searching&page='
            . 'autocomplete_contacts\', true);update_contact_type_session(\''
        .$_SESSION['config']['businessappurl']
        .'index.php?display=true&dir=indexing_searching&page=autocomplete_contacts_prepare_multi\');"  /><label for="type_contact_internal">' . _INTERNAL2 . '</label>'
			.  '</td></tr>';
    $frmStr .= '<tr id="contact_choose_2_tr" style="display:' . $displayValue
            . ';">';
    $frmStr .= '<td>&nbsp;</td>';
    $frmStr .= '<td>&nbsp;</td>';
	$frmStr .= '<td class="indexing_field"><input type="radio" name="type_contact" '
            . 'id="type_contact_external" value="external" '
            . 'onclick="clear_error(\'frm_error_' . $actionId . '\');'
            . 'change_contact_type(\'' . $_SESSION['config']['businessappurl']
            . 'index.php?display=true&dir=indexing_searching'
            . '&autocomplete_contacts\', true);update_contact_type_session(\''
        .$_SESSION['config']['businessappurl']
        .'index.php?display=true&dir=indexing_searching&page=autocomplete_contacts_prepare_multi\');"  class="check"/><label for="type_contact_external">' . _EXTERNAL	.'</label>'		
            . '</td>';
    $frmStr .= '</tr>';
    $frmStr .= '<tr id="contact_choose_3_tr" style="display:' . $displayValue
            . ';">';
    $frmStr .= '<td>&nbsp;</td>';
    $frmStr .= '<td>&nbsp;</td>';
	$frmStr .= '<td class="indexing_field"><input type="radio" name="type_contact" '
            . 'id="type_multi_contact_external" value="multi_external" '
            . 'onclick="clear_error(\'frm_error_' . $actionId . '\');'
            . 'change_contact_type(\'' . $_SESSION['config']['businessappurl']
            . 'index.php?display=true&dir=indexing_searching'
            . '&autocomplete_contacts\', true);update_contact_type_session(\''
        .$_SESSION['config']['businessappurl']
        .'index.php?display=true&dir=indexing_searching&page=autocomplete_contacts_prepare_multi\');"  class="check"/><label for="type_multi_contact_external">' . _MULTI_CONTACT	.'</label>'		
            . '</td>';
    $frmStr .= '</tr>';
    $frmStr .= '<tr id="contact_id_tr" style="display:' . $displayValue . ';">';
    $frmStr .= '<td style="vertical-align:bottom;"><label for="contact" class="form_title" >'
            . '<span id="exp_contact">' . _SHIPPER . '</span>'
            . '<span id="dest_contact">' . _DEST . '</span>'
            . '<span id="author_contact">' . _AUTHOR_DOC . '</span>';
    if ($core->test_admin('my_contacts', 'apps', false)) {
        $frmStr .= ' <a href="#" id="create_contact" title="' . _CREATE_CONTACT
                . '" onclick="new Effect.toggle(\'create_contact_div\', '
                . '\'blind\', {delay:0.2});return false;" '
                . 'style="display:inline;" ><i class="fa fa-pencil" title="' 
                . _CREATE_CONTACT . '"></i></a>';
    } else {
        $frmStr .= ' <a href="#" id="create_contact"/></a>';       
    }
    $frmStr .= '</label></td>';
    $contact_mode = "view";
    if($core->test_service('update_contacts','apps', false)) $contact_mode = 'update';
    //Path to actual script
    $path_to_script = $_SESSION['config']['businessappurl']
		."index.php?display=true&dir=indexing_searching&page=contact_check&coll_id=".$collId;
    $path_check_date_link = $_SESSION['config']['businessappurl']
        .'index.php?display=true&dir=indexing_searching&page=documents_list_mlb_search_adv&mode=popup&action_form=show_res_id&modulename=attachments&init_search&nodetails&fromContactCheck';
    $frmStr .= '<td style="vertical-align:bottom;"><a href="#" class="fa fa-book fa-2x" id="contact_card" title="' . _CONTACT_CARD
            . '" onclick="loadInfoContact();new Effect.toggle(\'info_contact_div\', '
                . '\'blind\', {delay:0.2});return false;"'
            . 'style="visibility:hidden;display:inline;" ></a>&nbsp;</td>';
    $frmStr .= '<td class="indexing_field">';

    //$frmStr .= '<i class="fa fa-user" title="'._INTERNAL2.'" style="cursor:pointer;" id="type_contact_internal_icon" onclick="$$(\'#type_contact_internal\')[0].click();$(\'type_contact_internal_icon\').setStyle({color: \'#009DC5\'});$(\'type_contact_external_icon\').setStyle({color: \'#666\'});$(\'type_multi_contact_external_icon\').setStyle({color: \'#666\'});"></i>';

    $frmStr .=' <i class="fa fa-user" title="'._SINGLE_CONTACT.'" style="cursor:pointer;color:#009DC5;" id="type_contact_external_icon" onclick="$$(\'#type_contact_external\')[0].click();$(\'type_contact_internal_icon\').setStyle({color: \'#666\'});$(\'type_contact_external_icon\').setStyle({color: \'#009DC5\'});$(\'type_multi_contact_external_icon\').setStyle({color: \'#666\'});"></i>';

    $frmStr .=' <i class="fa fa-users" title="'._MULTI_CONTACT.'" style="cursor:pointer;" id="type_multi_contact_external_icon" onclick="$$(\'#type_multi_contact_external\')[0].click();$(\'type_contact_internal_icon\').setStyle({color: \'#666\'});$(\'type_contact_external_icon\').setStyle({color: \'#666\'});$(\'type_multi_contact_external_icon\').setStyle({color: \'#009DC5\'});"></i>';
    $frmStr .=' <span style="position:relative;"><input type="text" name="contact" onkeyup="erase_contact_external_id(\'contact\', \'contactid\');erase_contact_external_id(\'contact\', \'addressid\');"'
            . 'id="contact" onblur="clear_error(\'frm_error_' . $actionId . '\');'
            . 'display_contact_card(\'visible\');if(document.getElementById(\'type_contact_external\').checked == true){check_date_exp(\''.$path_to_script.'\', \''.$path_check_date_link.'\');}" /><div id="show_contacts" '
            . 'class="autocomplete autocompleteIndex" style="width:100%;left:0px;top:17px;"></div><div class="autocomplete autocompleteIndex" id="searching_autocomplete" style="display: none;text-align:left;padding:5px;left:0px;width:100%;top:17px;"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> chargement ...</div></span></td>';
    $frmStr .= '<td><span class="red_asterisk" id="contact_mandatory" '
            . 'style="display:inline;"><i class="fa fa-star"></i></span>&nbsp;</td>';
    $frmStr .= '</tr>';
	$frmStr .= '<tr style="display:none" id="contact_check"><td></td></tr>';
    $frmStr .= '<input type="hidden" id="contactid" />';
    $frmStr .= '<input type="hidden" id="addressid" />';
    $frmStr .= '<input type="hidden" id="contactcheck" value="success"/>';
	
	/****multicontact***/
	
	//Path to actual script
	$path_to_script = $_SESSION['config']['businessappurl']
		."index.php?display=true&dir=indexing_searching&page=add_multi_contacts&coll_id=".$collId;
 	
    $_SESSION['adresses']['to'] = array();
    $_SESSION['adresses']['contactid'] = array();
	$_SESSION['adresses']['addressid'] = array();
	
    $frmStr .= '<tr id="add_multi_contact_tr" style="display:' . $displayValue . ';">';
        $frmStr .= '<td><label for="contact" class="form_title" >'
            . '<span id="dest_multi_contact">' . _DEST . '</span>';
    if ($core->test_admin('my_contacts', 'apps', false)) {
        $frmStr .= ' <a href="#" id="create_multi_contact" title="' . _CREATE_CONTACT
                . '" onclick="new Effect.toggle(\'create_contact_div\', '
                . '\'blind\', {delay:0.2});return false;" '
                . 'style="display:inline;" ><i class="fa fa-pencil" title="' 
                . _CREATE_CONTACT . '"></i></a>';
    }
    $frmStr .= '</label></td>';
    $contact_mode = "view";
    if($core->test_service('update_contacts','apps', false)) $contact_mode = 'update';
    $frmStr .= '<td><a href="#" id="multi_contact_card" class="fa fa-book fa-2x" title="' . _CONTACT_CARD
            . '" onclick="loadInfoContact();new Effect.toggle(\'info_contact_div\', '
                . '\'blind\', {delay:0.2});return false;" '
            . 'style="visibility:hidden;" ></a>&nbsp;</td>';
	$frmStr .= '<td class="indexing_field">';
    //$frmStr .= '<i class="fa fa-user" title="'._INTERNAL2.'" style="cursor:pointer;" id="type_contact_internal_icon" onclick="$$(\'#type_contact_internal\')[0].click();$(\'type_contact_internal_icon\').setStyle({color: \'#009DC5\'});$(\'type_contact_external_icon\').setStyle({color: \'#666\'});$(\'type_multi_contact_external_icon\').setStyle({color: \'#666\'});"></i>';

    $frmStr .=' <i class="fa fa-user" title="'._SINGLE_CONTACT.'" style="cursor:pointer;" id="type_contact_external_icon" onclick="$$(\'#type_contact_external\')[0].click();$(\'type_contact_internal_icon\').setStyle({color: \'#666\'});$(\'type_contact_external_icon\').setStyle({color: \'#009DC5\'});$(\'type_multi_contact_external_icon\').setStyle({color: \'#666\'});"></i>';

    $frmStr .=' <i class="fa fa-users" title="'._MULTI_CONTACT.'" style="cursor:pointer;color:#009DC5;" id="type_multi_contact_external_icon" onclick="$$(\'#type_multi_contact_external\')[0].click();$(\'type_contact_internal_icon\').setStyle({color: \'#666\'});$(\'type_contact_external_icon\').setStyle({color: \'#666\'});$(\'type_multi_contact_external_icon\').setStyle({color: \'#009DC5\'});"></i>';
 
    $frmStr .='<span style="position:relative;"><input type="text" name="email" id="email" value="" onblur="clear_error(\'frm_error_' . $actionId . '\');display_contact_card(\'visible\', \'multi_contact_card\');"/>';
    $frmStr .= '<div id="multiContactList" class="autocomplete" style="left:0px;width:100%;top:17px;"></div><div class="autocomplete autocompleteIndex" id="searching_autocomplete_multi" style="display: none;text-align:left;padding:5px;left:0px;width:100%;top:17px;"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> chargement ...</div></span>';
    $frmStr .= '<script type="text/javascript">addMultiContacts(\'email\', \'multiContactList\', \''
        .$_SESSION['config']['businessappurl']
        .'index.php?display=true&dir=indexing_searching&page=autocomplete_contacts\', \'Input\', \'2\', \'contactid\', \'addressid\');</script>';
    $frmStr .=' <input type="button" name="add" value="&nbsp;'._ADD
                    .'&nbsp;" id="valid_multi_contact" class="button" onclick="updateMultiContacts(\''.$path_to_script
                    .'&mode=adress\', \'add\', document.getElementById(\'email\').value, '
                    .'\'to\', false, document.getElementById(\'addressid\').value, document.getElementById(\'contactid\').value);display_contact_card(\'hidden\', \'multi_contact_card\');" />&nbsp;';
    $frmStr .= '</td>';
    $frmStr .= '</tr>';
    $frmStr .= '<tr id="show_multi_contact_tr">';
    $frmStr .= '<td align="right" nowrap width="10%" id="to_multi_contact"><label>'
        ._SEND_TO_SHORT.'</label></td>';
    $frmStr .= '<td>&nbsp;</td><td style="text-align:right;"><div name="to" id="to"  style="width:200px;text-align:left;" class="multicontactInput">'
        .'<div id="loading_to" style="display:none;"><i class="fa fa-spinner fa-2x" '
        .'style="vertical-align: middle;" title="loading..."></i></div></div></td>';
    $frmStr .= '<td><span class="red_asterisk" id="contact_mandatory" '
            . 'style="display:inline;"><i class="fa fa-star"></i></span>&nbsp;</td>';
    $frmStr .= '</tr>';	
	
    /*** Nature ***/
    $frmStr .= '<tr id="nature_id_tr" style="display:' . $displayValue . ';">';
    $frmStr .= '<td><label for="nature_id" class="form_title" >' . _NATURE
            . '</label></td>';
    $frmStr .= '<td>&nbsp;</td>';
    $frmStr .= '<td class="indexing_field"><select name="nature_id" '
            . 'id="nature_id" onchange="clear_error(\'frm_error_' . $actionId
            . '\');affiche_reference();">';
    $frmStr .= '<option value="">' . _CHOOSE_NATURE . '</option>';
    foreach (array_keys($_SESSION['mail_natures']) as $nature) {
        $frmStr .= '<option value="' . functions::xssafe($nature) . '" with_reference = "'.$_SESSION['mail_natures_attribute'][$nature].'"';
        if ($_SESSION['default_mail_nature'] == $nature) {
            $frmStr .= 'selected="selected"';
        }
        $frmStr .= '>' . functions::xssafe($_SESSION['mail_natures'][$nature]) . '</option>';
    }
    $frmStr .= '</select></td>';
    $frmStr .= '<td><span class="red_asterisk" id="nature_id_mandatory" '
            . 'style="display:inline;"><i class="fa fa-star"></i></span>&nbsp;</td>';
    $frmStr .= '</tr>';

    /****** RECOMMANDE ******/
    $frmStr .= '<tr id="reference_number_tr" style="display:none;">';
    $frmStr .= '<td ><label for="reference_number" class="form_title" >' ._MONITORING_NUMBER.'</label></td>';
    $frmStr .= '<td>&nbsp;</td>';
    $frmStr .= '<td><input type="text" name="reference_number" id="reference_number"/></td>';

    $frmStr .= '</tr>'; 
    /*** Subject ***/
    $frmStr .= '<tr id="subject_tr" style="display:' . $displayValue . ';">';
    $frmStr .= '<td><label for="subject" class="form_title" >' . _SUBJECT
            . '</label></td>';
    $frmStr .= '<td>&nbsp;</td>';
    $frmStr .= '<td class="indexing_field"><textarea name="subject" '
            . 'id="subject"  rows="4" onchange="clear_error(\'frm_error_'
            . $actionId . '\');" ></textarea></td>';
    $frmStr .= '<td><span class="red_asterisk" id="subject_mandatory" '
            . 'style="display:inline;"><i class="fa fa-star"></i></span>&nbsp;</td>';
    $frmStr .= '</tr>';
    /*** Entities : department + diffusion list ***/
    if ($core->is_module_loaded('entities')) {
        $frmStr .= '<tr id="department_tr" style="display:' . $displayValue
                . ';">';
        $frmStr .= '<td><label for="department" class="form_title" '
                . 'id="label_dep_dest" style="display:inline;" >'
                . _DEPARTMENT_DEST . '</label><label for="department" '
                . 'class="form_title" id="label_dep_exp" style="display:none;" >'
                . _DEPARTMENT_EXP . '</label><label for="department" '
                . 'class="form_title" id="label_dep_owner" style="display:none;" >'
                . _DEPARTMENT_OWNER . '</label></td>';
        $frmStr .= '<td>&nbsp;</td>';
        $frmStr .= '<td class="indexing_field">';
        $frmStr .= '<select name="destination" id="destination" onchange="'
                    . 'clear_error(\'frm_error_' . $actionId . '\');'
                    . 'load_listmodel('.$target_model.', \'diff_list_div\', \'indexing\', $(\'category_id\').value)'
          
                . ';" >';
        $frmStr .= '<option value="">' . _CHOOSE_DEPARTMENT . '</option>';
        $countAllEntities = count($allEntitiesTree);
        for ($cptEntities = 0;$cptEntities < $countAllEntities;$cptEntities++) {
            if (!$allEntitiesTree[$cptEntities]['KEYWORD']) {
                $frmStr .= '<option data-object_type="entity_id" value="' . $allEntitiesTree[$cptEntities]['ID'] . '"';
                if ($allEntitiesTree[$cptEntities]['DISABLED']) {
                    $frmStr .= ' disabled="disabled" class="disabled_entity"';
                } else {
                     //$frmStr .= ' style="font-weight:bold;"';
                }
                $frmStr .=  '>' 
                    .  functions::show_string($allEntitiesTree[$cptEntities]['SHORT_LABEL']) 
                    . '</option>';
            }
        }
        $frmStr .= '</select></td>';
        $frmStr .= '<td><span class="red_asterisk" id="destination_mandatory" '
                . 'style="display:inline;"><i class="fa fa-star"></i></span>&nbsp;</td>';
        $frmStr .= '</tr>';
        
        $frmStr .= '<tr id="diff_list_tr" style="display:none;">';
        $frmStr .= '<td colspan="3">';
        $frmStr .= '<div id="diff_list_div" class="scroll_div" '
                //. 'style="height:200px; width:420px; border: 1px solid;"></div>';
                . 'style="width:420px; max-width: 420px;"></div>';
        $frmStr .= '</td>';
        $frmStr .= '</tr>';
    }
    
    /*** Process limit date ***/
    $frmStr .= '<tr id="process_limit_date_use_tr" style="display:'
            . $displayValue . ';">';
    $frmStr .= '<td><label for="process_limit_date_use" class="form_title" >'
            . _PROCESS_LIMIT_DATE_USE . '</label></td>';
    $frmStr .= '<td>&nbsp;</td>';
    $frmStr .= '<td class="indexing_field"><input type="radio" '
            . 'name="process_limit_date_use" id="process_limit_date_use_yes" '
            . 'class="check" value="yes" checked="checked" '
            . 'onclick="clear_error(\'frm_error_' . $actionId . '\');'
            . 'activate_process_date(true,\'' . $displayValue . '\' );" />'
            . _YES . '<input type="radio" name="process_limit_date_use" '
            . 'id="process_limit_date_use_no" value="no" class="check" '
            . 'onclick="clear_error(\'frm_error_' . $actionId . '\');'
            . 'activate_process_date(false, \'' . $displayValue . '\');"/>'
            . _NO . '</td>';
    $frmStr .= '<td><span class="red_asterisk" '
            . 'id="process_limit_date_use_mandatory" style="display:inline;"><i class="fa fa-star"></i>'
            . '</span>&nbsp;</td>';
    $frmStr .= '</tr>';
    $frmStr .= '<tr id="process_limit_date_tr" style="display:' . $displayValue
            . ';">';
    $frmStr .= '<td><label for="process_limit_date" class="form_title" >'
            . _PROCESS_LIMIT_DATE . '</label></td>';
    $frmStr .= '<td>&nbsp;</td>';
    $frmStr .= '<td class="indexing_field"><input name="process_limit_date" '
            . 'type="text" id="process_limit_date" value="" '
            . 'onclick="showCalender(this);" '
            . 'onchange="clear_error(\'frm_error_' . $actionId . '\');checkRealLimitDate(\'process_limit_date\');" onFocus="checkRealLimitDate(\'process_limit_date\');"/></td>';
    $frmStr .= '<td><span class="red_asterisk" id="process_limit_date_mandatory"'
            . ' style="display:inline;"><i class="fa fa-star"></i></span>&nbsp;</td>';
    $frmStr .= '</tr>';
    
    /*** Status ***/
    if(count($statuses) > 0) {
        $frmStr .= '<tr id="status" style="display:' . $displayValue . ';">';
        $frmStr .= '<td><label for="status" class="form_title" >' . _STATUS
                . '</label></td>';
        $frmStr .= '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
        $frmStr .= '<td class="indexing_field"><select name="status" '
                . 'id="status" onchange="clear_error(\'frm_error_' . $actionId
                . '\');">';
        //$frmStr .= '<option value="">' . _CHOOSE_STATUS . '</option>';
        for ($i = 0; $i < count($statuses); $i ++) {
            $frmStr .= '<option value="' . functions::xssafe($statuses[$i]['ID']) . '" ';
            if ($statuses[$i]['ID'] == 'NEW') {
                $frmStr .= 'selected="selected"';
            }
            $frmStr .= '>' . functions::xssafe($statuses[$i]['LABEL']) . '</option>';
        }
        $frmStr .= '</select></td><td><span class="red_asterisk" id="market_mandatory" '
                . 'style="display:inline;"><i class="fa fa-star"></i></span>&nbsp;</td>';
        $frmStr .= '</tr>';
    }
    
    $frmStr .= '</table>';
    
    $frmStr .= '</div>';
    $frmStr .= '</div>';
    
    /*** CUSTOM INDEXES ***/
    $frmStr .= '<div id="comp_indexes" style="display:block;">';
    $frmStr .= '</div>';
    
    /*** Complementary fields ***/
    $frmStr .= '<hr />';
    
    $frmStr .= '<h4 onclick="new Effect.toggle(\'complementary_fields\', \'blind\', {delay:0.2});'
        . 'whatIsTheDivStatus(\'complementary_fields\', \'divStatus_complementary_fields\');" '
        . 'class="categorie" style="width:90%;" onmouseover="this.style.cursor=\'pointer\';">';
    $frmStr .= ' <span id="divStatus_complementary_fields" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span>&nbsp;' 
        . _OPT_INDEXES;
    $frmStr .= '</h4>';
    $frmStr .= '<div id="complementary_fields"  style="display:none">';
    $frmStr .= '<div>';
    
    $frmStr .= '<table width="100%" align="center" border="0" '
            . 'id="indexing_fields" style="display:table;">';
    
    /*** Chrono number ***/
    $frmStr .= '<tr id="chrono_number_tr" style="display:' . $displayValue . ';">';
    $frmStr .= '<td><label for="chrono_number" class="form_title" >'
            . _CHRONO_NUMBER . '</label></td>';
    $frmStr .= '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
    $frmStr .= '<td class="indexing_field"><input type="text" '
            . 'name="chrono_number" id="chrono_number" '
            . 'onchange="clear_error(\'frm_error_' . $actionId . '\');"/></td>';
    $frmStr .= '<td><span class="red_asterisk" id="chrono_number_mandatory" '
            . 'style="display:inline;"><i class="fa fa-star"></i></span>&nbsp;</td>';
    $frmStr .= '</tr>';

    /*** Folder ***/
    if ($core->is_module_loaded('folder') && ($core->test_service('associate_folder', 'folder',false) == 1)) {
        require_once 'modules' . DIRECTORY_SEPARATOR . 'folder' . DIRECTORY_SEPARATOR
            . 'class' . DIRECTORY_SEPARATOR . 'class_modules_tools.php';
        $folders = new folder();
        $folder_info = $folders->get_folders_tree('1');

        $frmStr .= '<tr id="folder_tr" style="display:' . $displayValue . ';">';
            $frmStr .= '<td><label for="folder" class="form_title" >' . _FOLDER_OR_SUBFOLDER. '</label></td>';
            $frmStr .= '<td class="indexing_field" style="text-align:right;"><select id="folder" name="folder" onchange="displayFatherFolder(\'folder\')"><option value="">Sélectionnez un dossier</option>';

            foreach ($folder_info as $key => $value) {
                 $frmStr .= '<option value="'.$value['folders_system_id'].'" parent="' . $value['parent_id'] . '">'.$value['folder_name'].'</option>';
            }
            $frmStr .= '</select></td>';
        /*$frmStr .= '<td class="indexing_field"><input type="text" '
                . 'name="folder" id="folder" onblur="clear_error(\'frm_error_'
                . $actionId . '\');return false;" />';

        if ($core->test_service('create_folder', 'folder', false) == 1) {
                $frmStr .= ' <a href="#" id="create_folder" title="' . _CREATE_FOLDER
                        . '" onclick="new Effect.toggle(\'create_folder_div\', '
                        . '\'blind\', {delay:0.2});return false;" '
                        . 'style="display:inline;" ><i class="fa fa-plus-circle" title="' 
                        . _CREATE_FOLDER . '"></i></a>';
            }
        $frmStr .= '<div id="show_folder" '
                . 'class="autocomplete"></div></td>';*/
        $frmStr .= '<td><span class="red_asterisk" id="folder_mandatory" style="display:inline;"><i class="fa fa-star"></i></span>&nbsp;</td>';
        $frmStr .= '</tr>';
        $frmStr .= '<tr id="parentFolderTr" style="display: none;text-align:center;"><td>&nbsp;</td><td colspan="2"><span id="parentFolderSpan" style="font-style: italic;font-size: 10px"></span></td></tr>';
    }
    
    /*** thesaurus ***/
    if ($core->is_module_loaded('thesaurus') && $core->test_service('thesaurus_view', 'thesaurus', false)) {
        require_once 'modules' . DIRECTORY_SEPARATOR . 'thesaurus'
                        . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
                        . 'class_modules_tools.php';
        $thesaurus = new thesaurus();


        $frmStr .= '<tr id="thesaurus_tr" style="display:' . $displayValue . ';">';
            $frmStr .= '<td colspan="3">' . _THESAURUS . '</td>';
        $frmStr .= '</tr>';

        $frmStr .= '<tr id="thesaurus_tr" style="display:' . $displayValue . ';">';
            $frmStr .= '<td colspan="2" class="indexing_field" id="thesaurus_field" style="text-align:left;"><select multiple="multiple" id="thesaurus" data-placeholder=" " onselect="alert(\'ok\')"';
        
            if (!$core->test_service('add_thesaurus_to_res', 'thesaurus', false)) {
                $frmStr .= 'disabled="disabled"';
            }

            $frmStr .= '>';

            $frmStr .= '</select>';
            $frmStr .= '<td style="width:5%;"> <i onclick="lauch_thesaurus_list(this);" class="fa fa-search" title="parcourir le thésaurus" aria-hidden="true" style="cursor:pointer;"></i></td>';
        $frmStr .= '</tr>';
        $frmStr .= '<style>#thesaurus_chosen{width:100% !important;}#thesaurus_chosen .chosen-drop{display:none;}</style>';

        /*****************/
    }
    /*** Tags ***/
    if ($core->is_module_loaded('tags') 
        && ($core->test_service('tag_view', 'tags',false) == 1)
        && ($core->test_service('add_tag_to_res', 'tags',false) == 1)
    ) {
        include_once('modules/tags/templates/index_mlb/index.php');
    }
    
    // Fin
    $frmStr .= '</table>';

    $frmStr .= '</div>';
    $frmStr .= '</div>';

    $frmStr .= '</div>';
    /*** Actions ***/
    $frmStr .= '<hr width="90%"/>';
    $frmStr .= '<p align="center">';
    
    //GET ACTION LIST BY AJAX REQUEST
    $frmStr .= '<span id="actionSpan"></span>';
    
    $frmStr .= '<input type="button" name="send" id="send" value="'
            . _VALIDATE . '" class="button" '
            . 'onclick="getIframeContent(\''
            . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
            . '&page=getIframeTemplateContent\');if(document.getElementById(\'contactcheck\').value!=\'success\'){if (confirm(\''. _CONTACT_CHECK .'\n\nContinuer ?\'))valid_action_form(\'index_file\', \''
            . $pathManageAction . '\', \'' . $actionId . '\', \'' . $resId
            . '\', \'' . $table . '\', \'' . $module . '\', \'' . $collId
            . '\', \'' . $mode . '\', true);}else{valid_action_form(\'index_file\', \''
            . $pathManageAction . '\', \'' . $actionId . '\', \'' . $resId
            . '\', \'' . $table . '\', \'' . $module . '\', \'' . $collId
            . '\', \'' . $mode . '\', true);}"/> ';
    $frmStr .= '<input name="close" id="close" type="button" value="'
            . _CANCEL . '" class="button" '
            . 'onclick="javascript:window.top.location.href=\''
            . $_SESSION['config']['businessappurl'] . 'index.php\';reinit();"/>';
    $frmStr .= '</p>';
    $frmStr .= '</form>';
    $frmStr .= '</div>';
    $frmStr .= '</div>';
    $frmStr .= '</div>';

    /*** Frame to display the doc ***/
    $frmStr .= '<div id="validright">';
    
    /**** Contact form start *******/
    if ($core->test_admin('my_contacts', 'apps', false)) {
    $frmStr .= '<div id="create_contact_div" style="display:none">';
        $frmStr .= '<iframe width="100%" height="450" src="' . $_SESSION['config']['businessappurl']
                . 'index.php?display=false&dir=my_contacts&page=create_contact_iframe" name="contact_iframe" id="contact_iframe"'
                . ' scrolling="auto" frameborder="0" style="display:block;">'
                . '</iframe>';
    $frmStr .= '</div>';
    }

    /**** Contact form end *******/

    /**** Folder form start *******/
    if ($core->test_service('create_folder', 'folder', false) == 1) {
        $frmStr .= '<div id="create_folder_div" style="display:none">';
            $frmStr .= '<iframe width="100%" height="450" src="' . $_SESSION['config']['businessappurl']
                    . 'index.php?page=create_folder_form_iframe&module=folder&display=false" name="folder_iframe" id="folder_iframe"'
                    . ' scrolling="auto" frameborder="0" style="display:block;">'
                    . '</iframe>';
        $frmStr .= '</div>';
    }
    /**** Folder form end *******/


    /**** Contact info start *******/
    $frmStr .= '<div id="info_contact_div" style="display:none">';
        $frmStr .= '<iframe width="100%" height="800" name="info_contact_iframe" id="info_contact_iframe"'
                . ' scrolling="auto" frameborder="0" style="display:block;">'
                . '</iframe>';
    $frmStr .= '</div>';
    /**** Contact info end *******/    
    $frmStr .= '<script type="text/javascript">show_admin_contacts( true);</script>';
    //$frmStr .= '<iframe src="'.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=file_iframe" name="file_iframe" id="file_iframe" scrolling="auto" frameborder="0" style="display:block;" ></iframe>';
    if ($_SESSION['origin'] == "scan") {
        $frmStr .= '<iframe src="' . $_SESSION['config']['businessappurl']
                . 'index.php?display=true&dir=indexing_searching&page='
                . 'file_iframe&#navpanes=0" name="file_iframe" id="file_iframe"'
                . ' scrolling="auto" frameborder="0" style="display:block;">'
                . '</iframe>';
    } else {
        $frmStr .= '<iframe src="' . $_SESSION['config']['businessappurl']
                . 'index.php?display=true&dir=indexing_searching&page='
                . 'file_iframe" name="file_iframe" id="file_iframe" '
                . 'scrolling="auto" frameborder="0" style="display:block;" onmouseover="this.focus()">'
                . '</iframe>';
    }
    $frmStr .= '</div>';

    /*** Extra javascript ***/
    $frmStr .= '<script type="text/javascript">resize_frame_process(\'modal_'
            . $actionId . '\', \'file_iframe\', true, true); '
            . 'window.scrollTo(0,0);';

    if (isset($_SESSION['indexing']['category_id']) && $_SESSION['indexing']['category_id'] <> "") {
        $category_default = $_SESSION['indexing']['category_id'];
        $_SESSION['indexing']['category_id'] = ""; 
    } else {
        $category_default = $_SESSION['coll_categories']['letterbox_coll']['default_category'];    
    }

    $frmStr .= 'change_category(\''
            . $category_default . '\', \'' . $displayValue
            . '\', \'' . $_SESSION['config']['businessappurl']
            . 'index.php?display=true&dir=indexing_searching&page='
            . 'change_category\',  \'' . $_SESSION['config']['businessappurl']
            . 'index.php?display=true&page=get_content_js\');change_category_actions(\'' 
            . $_SESSION['config']['businessappurl'] 
            . 'index.php?display=true&dir=indexing_searching&page=change_category_actions'
            . '&resId=' . $resId . '&collId=' . $collId . '\');'
            . 'launch_autocompleter_contacts_v2(\''
            . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
            . '&dir=indexing_searching&page=autocomplete_contacts\', \'contact\', \'show_contacts\', \'\', \'contactid\', \'addressid\');';

    if ($core->is_module_loaded('folder') && ($core->test_service('associate_folder', 'folder',false) == 1)) {
        /*$frmStr .= ' initList(\'folder\', \'show_folder\',\''
                . $_SESSION['config']['businessappurl'] . 'index.php?display='
                . 'true&module=folder&page=autocomplete_folders&mode=folder\','
                . ' \'Input\', \'2\');';*/
    }
    $frmStr .= '$$(\'select\').each(function(element) { new Chosen(element,{width: "226px", disable_search_threshold: 10,search_contains: true}); });';
    $frmStr .= '$(\'baskets\').style.visibility=\'hidden\';'
            . 'var item  = $(\'index_div\'); if(item)'
            . '{item.style.display=\'block\';}</script>';
    $frmStr .= '<style>#destination_chosen .chosen-drop{width:400px;}#folder_chosen .chosen-drop{width:400px;}</style>';

    return addslashes($frmStr);

}

/**
 * Checks the action form
 *
 * @param $formId String Identifier of the form to check
 * @param $values Array Values of the form
 * @return Bool true if no error, false otherwise
 **/
function check_form($formId, $values)
{
    
    if ($_SESSION['upfile']['format']=='maarch'){
        $_SESSION['upfile']='';
        $_SESSION['upfile']['error']='0';
        $_SESSION['upfile']['format']='maarch';
    } elseif (empty($_SESSION['upfile']['format'])) {
        $_SESSION['action_error'] = _FILE . ' ' . _MANDATORY;
        return false;
    }

    //print_r($values);
    $_SESSION['action_error'] = '';
    if (count($values) < 1 || empty($formId)) {
        $_SESSION['action_error'] = _FORM_ERROR;
        return false;
    } else {

        //print_r($values);
        $attach = get_value_fields($values, 'attach');
        $collId = get_value_fields($values, 'coll_id');
        if ($attach) {
            $idDoc = get_value_fields($values, 'res_id');
            if (! $idDoc || empty($idDoc)) {
                $_SESSION['action_error'] .= _LINK_REFERENCE . '<br/>';
            }
            if (! empty($_SESSION['action_error'])) {
                return false;
            }
        }
        $catId = get_value_fields($values, 'category_id');
        if (! $catId) {
            $_SESSION['action_error'] = _CATEGORY . ' ' . _IS_EMPTY;
            return false;
        }
        $noError = process_category_check($catId, $values);
        
        
        if ($noError == false) {
            //$_SESSION['action_error'] .= _ERROR_CATEGORY;
            return false;
        }

        if (isset($_SESSION['upfile']['format'])
            && $_SESSION['upfile']['format'] <> 'maarch'
        ) {
            $is = new indexing_searching_app();
            $state = $is->is_filetype_allowed(
                $_SESSION['upfile']['format']
            );
            if (! $state) {
                $_SESSION['action_error'] .= '<br/>'
                    . $_SESSION['upfile']['format'] . _FILETYPE . ' '
                    . _NOT_ALLOWED;
                return false;
            }
        }
        return check_docserver($collId);
    }
}

/**
 * Makes all the checks on the docserver and store the file
 *
 * @param $catId String Collection identifier
 * @return Bool true if no error, false otherwise
 **/
function check_docserver($collId) {

    if (isset($_SESSION['indexing']['path_template'])
        && ! empty($_SESSION['indexing']['path_template'])
        && isset($_SESSION['indexing']['destination_dir'])
        && ! empty($_SESSION['indexing']['destination_dir'])
        && isset($_SESSION['indexing']['docserver_id'])
        && ! empty($_SESSION['indexing']['docserver_id'])
        && isset($_SESSION['indexing']['file_destination_name'])
        && ! empty($_SESSION['indexing']['file_destination_name'])
    ) {
        $_SESSION['action_error'] = _CHECK_FORM_OK;
        return true;
    }
    $core = new core_tools();
    if ($core->is_module_loaded('templates')
        && $_SESSION['upfile']['format'] == "maarch"
    ) {
        if (!isset($_SESSION['template_content'])
            || empty($_SESSION['template_content'])
        ) {
            $_SESSION['action_error'] = _TEMPLATE . ' ' . _IS_EMPTY;
            return false;
        }
        if (
            !isset($_SESSION['upfile']['name'])
            && $_SESSION['upfile']['name'] == ''
        ) {
            $_SESSION['upfile']['name'] = 'tmp_file_'
                . $_SESSION['user']['UserId'] . '_' . rand() . '.maarch';
            $tmpPath = $_SESSION['config']['tmppath'] . DIRECTORY_SEPARATOR
                . $_SESSION['upfile']['name'];
            $myfile = fopen($tmpPath, "w");
            if (!$myfile) {
                $_SESSION['action_error'] .= _FILE_OPEN_ERROR . '.<br/>';
                return false;
            }
            fwrite($myfile, $_SESSION['template_content']);
            fclose($myfile);
            $_SESSION['upfile']['size'] = filesize($tmpPath);
        }
    }
    if ($_SESSION['origin'] == "scan") {
        $newFileName = "tmp_file_" . $_SESSION['upfile']['md5'] . '.'
                     . strtolower($_SESSION['upfile']['format']);
    } else {
        $newFileName = $_SESSION['upfile']['name'];
    }

    $docserverControler = new docservers_controler();
    $fileInfos = array(
        "tmpDir"      => $_SESSION['config']['tmppath'],
        "size"        => $_SESSION['upfile']['size'],
        "format"      => $_SESSION['upfile']['format'],
        "tmpFileName" => $newFileName,
    );
    //print_r($fileInfos);
    $storeResult = array();
    $storeResult = $docserverControler->storeResourceOnDocserver(
        $collId, $fileInfos
    );
    //print_r($storeResult);
    if (isset($storeResult['error']) && $storeResult['error'] <> "") {
        $_SESSION['action_error'] = $storeResult['error'];
        return false;
    } else {
        $_SESSION['indexing']['path_template'] = $storeResult['path_template'];
        $_SESSION['indexing']['destination_dir'] = $storeResult['destination_dir'];
        $_SESSION['indexing']['docserver_id'] = $storeResult['docserver_id'];
        $_SESSION['indexing']['file_destination_name'] = $storeResult['file_destination_name'];
        $_SESSION['action_error'] = _CHECK_FORM_OK;
        return true;
    }
}

/**
 * Checks the values of the action form for a given category
 *
 * @param $catId String Category identifier
 * @param $values Array Values of the form to check
 * @return Bool true if no error, false otherwise
 **/
function process_category_check($catId, $values)
{
    //print_r($values);
    $core = new core_tools();
    // If No category : Error
    if (! isset($_ENV['categories'][$catId])) {
        $_SESSION['action_error'] = _CATEGORY . ' ' . _UNKNOWN . ': ' . $catId;
        return false;
    }

    // Simple cases
    for ($i = 0; $i < count($values); $i ++) {

        if (! isset($values[$i]['ID'])) {
            $tmpId = 'none';
        } else {
            $tmpId = $values[$i]['ID'];
        }

        if (isset($_ENV['categories'][$catId][$tmpId]['mandatory'])
           && $_ENV['categories'][$catId][$tmpId]['mandatory'] == true
           && empty($values[$i]['VALUE'])
        ) {
            $_SESSION['action_error'] = $_ENV['categories'][$catId][$tmpId]['label']
                                      . ' ' . _IS_EMPTY;
            return false;
        }
        if (isset($_ENV['categories'][$catId][$tmpId]['type_form'])
            && $_ENV['categories'][$catId][$tmpId]['type_form'] == 'date'
            && ! empty($values[$i]['VALUE'])
            && preg_match($_ENV['date_pattern'], $values[$i]['VALUE']) == 0
        ) {
            $_SESSION['action_error'] = $_ENV['categories'][$catId][$tmpId]['label']
                                      . ' ' . _WRONG_FORMAT;
            return false;
        }
        if (isset($_ENV['categories'][$catId][$tmpId]['type_form'])
            && $_ENV['categories'][$catId][$tmpId]['type_form'] == 'integer'
            && ! empty($values[$i]['VALUE'])
            && preg_match("/^[0-9]*$/", $values[$i]['VALUE']) == 0
        ) {
            $_SESSION['action_error'] = $_ENV['categories'][$catId][$tmpId]['label']
                                      . ' ' . _WRONG_FORMAT;
            return false;
        }
        if (isset($_ENV['categories'][$catId][$tmpId]['type_form'])
            && $_ENV['categories'][$catId][$tmpId]['type_form'] == 'radio'
            && ! empty($values[$i]['VALUE'])
            && ! in_array(
                $values[$i]['VALUE'], $_ENV['categories'][$catId][$tmpId]['values']
            )
        ) {
            $_SESSION['action_error'] = $_ENV['categories'][$catId][$tmpId]['label']
                                      . ' ' . _WRONG_FORMAT;
            return false;
        }
    }
    ///// Checks the complementary indexes depending on the doctype
    require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
        . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
        . 'class_types.php';
    $type = new types();
    $typeId = get_value_fields($values, 'type_id');
    $collId = get_value_fields($values, 'coll_id');
    $indexes = $type->get_indexes($typeId, $collId, 'minimal');
    $valIndexes = array();
    for ($i = 0; $i < count($indexes); $i ++) {
        $valIndexes[$indexes[$i]] = get_value_fields($values, $indexes[$i]);
    }
    $testType = $type->check_indexes($typeId, $collId, $valIndexes );
    if (! $testType) {
        $_SESSION['action_error'] .= $_SESSION['error'];
        $_SESSION['error'] = '';
        return false;
    }

    ///////////////////////// Other cases
    // Process limit Date
    $_SESSION['store_process_limit_date'] = "";
    if (isset($_ENV['categories'][$catId]['other_cases']['process_limit_date'])) {
        $processLimitDateUseYes = get_value_fields(
            $values, 'process_limit_date_use_yes'
        );
        $processLimitDateUseNo = get_value_fields(
            $values, 'process_limit_date_use_no'
        );
        if ($processLimitDateUseYes == 'yes') {
            $_SESSION['store_process_limit_date'] = "ok";
            $processLimitDate = get_value_fields($values, 'process_limit_date');
            if(trim($processLimitDate) == ""){
                $_SESSION['action_error'] = $_ENV['categories'][$catId]['other_cases']['process_limit_date']['label']
                    . " " . _IS_EMPTY;
                return false;
            }else if (preg_match($_ENV['date_pattern'], $processLimitDate) == 0
            ) {
                $_SESSION['action_error'] = $_ENV['categories'][$catId]['other_cases']['process_limit_date']['label']
                    . " " . _WRONG_FORMAT;
                return false;
            }
        } else if ($processLimitDateUseNo == 'no') {
            $_SESSION['store_process_limit_date'] = "ko";
        }
       $processLimitDate = new datetime($processLimitDate);
       $processLimitDate = date_add($processLimitDate,date_interval_create_from_date_string('23 hours + 59 minutes + 59 seconds'));
    }

    if (isset($_ENV['categories'][$catId]['priority'])) {
        $priority = get_value_fields(
            $values, 'priority'
        );

        if ($priority === '') {
            $_SESSION['action_error'] = $_ENV['categories'][$catId]['priority']['label']
                . " " . _MANDATORY;
            return false;
        }
    }

    // Contact
    if (isset($_ENV['categories'][$catId]['other_cases']['contact'])) {
        $contactType = get_value_fields($values, 'type_contact_external');
        if (! $contactType) {
            $contactType = get_value_fields($values, 'type_contact_internal');
        }        
		if (! $contactType) {
            $contactType = get_value_fields($values, 'type_multi_contact_external');
        }
        if (! $contactType) {
            $_SESSION['action_error'] = $_ENV['categories'][$catId]['other_cases']['type_contact']['label']
                . " " . _MANDATORY;
            return false;
        }
        $contact = get_value_fields($values, 'contactid');
		$nb_multi_contact = count($_SESSION['adresses']['to']);

        $contact_field = get_value_fields($values, 'contact');

        if ($contact_field <> "" && empty($contact)) {
            $_SESSION['action_error'] = $_ENV['categories'][$catId]['other_cases']['contact']['label']
                . ' ' . _WRONG_FORMAT . ". " . _USE_AUTOCOMPLETION;
            return false;
        }

        // if (! empty($contact)) {
        //     if ($contactType == 'external'
        //         && preg_match('/\([0-9]+\)$/', $contact) == 0
        //     ) {
        //         $_SESSION['action_error'] = $_ENV['categories'][$catId]['other_cases']['contact']['label']
        //             . " " . _WRONG_FORMAT . ".<br/>" . _USE_AUTOCOMPLETION;
        //         return false;
        //     } else if ($contactType == 'internal'
        //         && preg_match('/\((.|\s|\d|\h|\w)+\)$/i', $contact) == 0
        //     ) {
        //         $_SESSION['action_error'] = $_ENV['categories'][$catId]['other_cases']['contact']['label']
        //             . " " . _WRONG_FORMAT . ".<br/>" . _USE_AUTOCOMPLETION;
        //         return false;
        //     }
        // }

        if ($_ENV['categories'][$catId]['other_cases']['contact']['mandatory'] == true) {
            if ((empty($contact) && $contactType != 'multi_external') || ($nb_multi_contact == 0 && $contactType == 'multi_external')) {
                $_SESSION['action_error'] = $_ENV['categories'][$catId]['other_cases']['contact']['label']
                    . ' ' . _IS_EMPTY;
                return false;
            }
        }

    }

    if ($core->is_module_loaded('entities')) {
        // Diffusion list
        if (isset($_ENV['categories'][$catId]['other_cases']['diff_list'])
            && $_ENV['categories'][$catId]['other_cases']['diff_list']['mandatory'] == true
        ) {
            if (empty($_SESSION['indexing']['diff_list']['dest']['users'][0])
                || ! isset($_SESSION['indexing']['diff_list']['dest']['users'][0])
            ) {
                $_SESSION['action_error'] = $_ENV['categories'][$catId]['other_cases']['diff_list']['label']
                    . " " . _MANDATORY;
                return false;
            }
        }
    }
    if ($core->is_module_loaded('folder')) {
        $db = new Database();
        $folderId = '';
        
        $folderId = get_value_fields($values, 'folder');
        if (isset($_ENV['categories'][$catId]['other_cases']['folder'])
            && $_ENV['categories'][$catId]['other_cases']['folder']['mandatory'] == true
        ) {
            if (empty($folderId)) {
                $_SESSION['action_error'] = $_ENV['categories'][$catId]['other_cases']['folder']['label']
                    . ' ' . _IS_EMPTY;
                return false;
            }
        }
        /*if (! empty($folder)) {
            if (! preg_match('/\([0-9]+\)$/', $folder)) {
                $_SESSION['action_error'] = $_ENV['categories'][$catId]['other_cases']['folder']['label']
                    . " " . _WRONG_FORMAT;
                return false;
            }
            $folderId = str_replace(
                ')', '', substr($folder, strrpos($folder, '(') + 1)
            );
            $stmt = $db->query(
                "SELECT folders_system_id FROM " . FOLD_FOLDERS_TABLE
                . " WHERE folders_system_id = ?", array($folderId)
            );
            if ($stmt->rowCount() == 0) {
                $_SESSION['action_error'] = _FOLDER . ' ' . $folderId . ' '
                                          . _UNKNOWN;
                return false;
            }
        }*/

        if (! empty($typeId ) && ! empty($folderId)) {
            $foldertypeId = '';
            
            $stmt = $db->query(
                "SELECT foldertype_id FROM " . FOLD_FOLDERS_TABLE
                ." WHERE folders_system_id = ?", array($folderId)
            );
            
            $res = $stmt->fetchObject();
            $foldertypeId = $res->foldertype_id;
            $stmt = $db->query(
                "SELECT fdl.foldertype_id FROM "
                . FOLD_FOLDERTYPES_DOCTYPES_LEVEL1_TABLE . " fdl, "
                . DOCTYPES_TABLE . " d WHERE d.doctypes_first_level_id = "
                . "fdl.doctypes_first_level_id and fdl.foldertype_id = ? and d.type_id = ?",
                array($foldertypeId, $typeId)
            );
            if ($stmt->rowCount() == 0) {
                $_SESSION['action_error'] .= _ERROR_COMPATIBILITY_FOLDER;
                return false;
            }
        }
    }

    //For specific case => chrono number
    $chronoOut = get_value_fields($values, 'chrono_number');
    if (isset($_ENV['categories'][$catId]['other_cases']['chrono_number'])
        && $_ENV['categories'][$catId]['other_cases']['chrono_number']['mandatory'] == true
    ) {
        if ($chronoOut == false) {
            $_SESSION['action_error'] = _NO_CHRONO_NUMBER_DEFINED . ' ';
            return false;
        }
    }
    return true;
}

/**
 * Get the value of a given field in the values returned by the form
 *
 * @param $values Array Values of the form to check
 * @param $field String the field
 * @return String the value, false if the field is not found
 **/
function get_value_fields($values, $field)
{
    for ($i = 0; $i < count($values); $i ++) {
        if ($values[$i]['ID'] == $field) {
            return  $values[$i]['VALUE'];
        }
    }
    return false;
}

/**
 * Action of the form : loads the index in the db
 *
 * @param $arrId Array Not used here
 * @param $history String Log the action in history table or not
 * @param $actionId String Action identifier
 * @param $label_action String Action label
 * @param $status String  Not used here
 * @param $collId String Collection identifier
 * @param $table String Table
 * @param $formValues String Values of the form to load
 * @return false or an array
 *          $data['result'] : res_id of the new file followed by #
 *          $data['history_msg'] : Log complement (empty by default)
 *          $data['page_result'] : Page to load when action is done and modal closed
 **/
function manage_form($arrId, $history, $actionId, $label_action, $status, $collId, $table, $formValues )
{
    if (empty($formValues) || count($arrId) < 1 || empty($collId)) {
        $_SESSION['action_error'] = _ERROR_MANAGE_FORM_ARGS;
        return false;
    }
    $resId = '';
    $db = new Database();
    $sec = new security();
    $req = new request();
    $core = new core_tools();
    $table = $sec->retrieve_table_from_coll($collId);
    $indColl = $sec->get_ind_collection($collId);
    $tableExt = $_SESSION['collections'][$indColl]['extensions'][0];
    $queryExtFields = '(';
    $queryExtValues = '(';
    $arrayPDO = array();
    $resource = new resource();
    $_SESSION['data'] = array();

    // Load in the $_SESSION['data'] minimal indexes
    array_push(
        $_SESSION['data'],
        array(
            'column' => 'typist',
            'value' => $_SESSION['user']['UserId'],
            'type' => 'string',
        )
    );
    array_push(
        $_SESSION['data'],
        array(
            'column' => 'docserver_id',
            'value' => $_SESSION['indexing']['docserver_id'],
            'type' => 'string',
        )
    );

    if (isset($_SESSION['upfile']['format'])) {
        array_push(
            $_SESSION['data'],
            array(
                'column' => 'format',
                'value' => $_SESSION['upfile']['format'],
                'type' => 'string',
            )
        );
    }

    $confidentiality_yes = get_value_fields($formValues, 'confidential');

    if (!empty($confidentiality_yes)) {
        array_push(
            $_SESSION['data'],
            array(
                'column' => 'confidentiality',
                'value' => $confidentiality_yes,
                'type' => 'string',
            )
        );
    } else {
        $confidentiality_no = get_value_fields($formValues, 'no_confidential');
        array_push(
            $_SESSION['data'],
            array(
                'column' => 'confidentiality',
                'value' => $confidentiality_no,
                'type' => 'string',
            )
        );
    }

    //store the initiator entity
    if (isset($_SESSION['user']['primaryentity']['id'])) {
        array_push(
            $_SESSION['data'],
            array(
                'column' => 'initiator',
                'value' => $_SESSION['user']['primaryentity']['id'],
                'type' => 'string',
            )
        );
    }
    $status_id = get_value_fields($formValues, 'status');
    if(empty($status_id) || $status_id === "") $status_id = 'BAD';
    array_push(
        $_SESSION['data'],
        array(
            'column' => 'status',
            'value' => $status_id,
            'type' => 'string',
        )
    );
    array_push(
        $_SESSION['data'],
        array(
            'column' => 'offset_doc',
            'value' => '',
            'type' => 'string',
        )
    );
    array_push(
        $_SESSION['data'],
        array(
            'column' => 'logical_adr',
            'value' => '',
            'type' => 'string',
        )
    );

    if (isset($_SESSION['origin']) && $_SESSION['origin'] == 'scan') {
        array_push(
            $_SESSION['data'],
            array(
                'column' => 'scan_user',
                'value' => $_SESSION['user']['UserId'],
                'type' => 'string',
            )
        );
        array_push(
            $_SESSION['data'],
            array(
                'column' => 'scan_date',
                'value' => $req->current_datetime(),
                'type' => 'function',
            )
        );
    }
    //without file case
    if (
        isset($_SESSION['with_file']) 
        && $_SESSION['with_file']
    ) {
        array_push(
            $_SESSION['data'],
            array(
                'column' => 'source',
                'value' => 'with_empty_file',
                'type' => 'string',
            )
        );
    }

    $attach = get_value_fields($formValues, 'attach');

    $catId = get_value_fields($formValues, 'category_id');

    $queryExtFields .= 'category_id,' ;
    $queryExtValues .= "?," ;
    $arrayPDO = array_merge($arrayPDO, array($catId));

    $_SESSION['origin'] = "";
    // Specific indexes : values from the form
    // Simple cases
    for ($i = 0; $i < count($formValues); $i ++) {
        $tmpId = $formValues[$i]['ID'];
        if (isset($_ENV['categories'][$catId][$tmpId]['type_field'])
            && $_ENV['categories'][$catId][$tmpId]['type_field'] == 'integer'
            && $_ENV['categories'][$catId][$tmpId]['table'] <> 'none'
        ) {
            if (isset($_ENV['categories'][$catId][$tmpId]['table'])
                && $_ENV['categories'][$catId][$tmpId]['table'] == 'res'
            ) {
                array_push(
                    $_SESSION['data'],
                    array(
                        'column' => $tmpId,
                        'value'  => $formValues[$i]['VALUE'],
                        'type'   => 'integer',
                    )
                );
            } else if (isset($_ENV['categories'][$catId][$tmpId]['table'])
                && $_ENV['categories'][$catId][$tmpId]['table'] == 'coll_ext'
            ) {
                $queryExtFields .= $tmpId . ',';
                $queryExtValues .= ' ? ,';
                $arrayPDO = array_merge($arrayPDO, array($formValues[$i]['VALUE']));
            }
        } else if (isset($_ENV['categories'][$catId][$tmpId]['type_field'])
            && isset($_ENV['categories'][$catId][$tmpId]['table'])
            && $_ENV['categories'][$catId][$tmpId]['type_field'] == 'string'
            && $_ENV['categories'][$catId][$tmpId]['table'] <> 'none'
        ) {
            
            if ($_ENV['categories'][$catId][$tmpId]['table'] == 'res') {
               
               array_push(
                    $_SESSION['data'],
                    array(
                        'column' => $tmpId,
                        'value' => $formValues[$i]['VALUE'],
                        'type' => 'string'
                    )
                );
            } else if ($_ENV['categories'][$catId][$tmpId]['table'] == 'coll_ext') {
                $queryExtFields .= $formValues[$i]['ID'] . ',';
                $queryExtValues .= " ? ,";
                $arrayPDO = array_merge($arrayPDO, array($formValues[$i]['VALUE']));
            }
        } else if (isset($_ENV['categories'][$catId][$tmpId]['type_field'])
            && isset($_ENV['categories'][$catId][$tmpId]['table'])
            && $_ENV['categories'][$catId][$tmpId]['type_field'] == 'date'
            && $_ENV['categories'][$catId][$tmpId]['table'] <> 'none'
        ) {
            if ($_ENV['categories'][$catId][$tmpId]['table'] == 'res') {
                array_push(
                    $_SESSION['data'],
                    array(
                        'column' => $tmpId,
                        'value' => functions::format_date_db($formValues[$i]['VALUE']),
                        'type' => 'date',
                    )
                );
            } else if ($_ENV['categories'][$catId][$tmpId]['table'] == 'coll_ext') {
                $queryExtFields .= $formValues[$i]['ID'] . ',';
                $queryExtValues .= " ? ,";
                $arrayPDO = array_merge($arrayPDO, array(functions::format_date_db(
                    $formValues[$i]['VALUE']
                )));
            }
        }
    }
    ///// Manages the complementary indexes depending on the doctype
    $type = new types();
    $typeId = get_value_fields($formValues, 'type_id');
    $indexes = $type->get_indexes($typeId, $collId, 'minimal');
    $valIndexes = array();
    for ($i = 0; $i < count($indexes); $i ++) {
        $valIndexes[$indexes[$i]] = get_value_fields(
            $formValues, $indexes[$i]
        );
    }
    $_SESSION['data'] = $type->fill_data_array(
        $typeId, $collId, $valIndexes, $_SESSION['data']
    );

    ///////////////////////// Other cases
    // Process limit Date
    if (isset($_ENV['categories'][$catId]['other_cases']['process_limit_date'])) {
        $processLimitDate = get_value_fields(
            $formValues, 'process_limit_date'
        );
        $processLimitDate = new datetime($processLimitDate);
        $processLimitDate = date_add($processLimitDate,date_interval_create_from_date_string('23 hours + 59 minutes + 59 seconds'));
        
        $processLimitDate = (array) $processLimitDate;

        if ($_ENV['categories'][$catId]['other_cases']['process_limit_date']['table'] == 'res') {
            array_push(
                $_SESSION['data'],
                array(
                    'column' => 'process_limit_date',
                    'value' => functions::format_date_db($processLimitDate['date'],'true','','true'),
                    'type' => 'date',
                )
            );
        } else if ($_ENV['categories'][$catId]['other_cases']['process_limit_date']['table'] == 'coll_ext') {
            if ($_SESSION['store_process_limit_date'] == "ok") {
                $queryExtFields .= 'process_limit_date,';
                $queryExtValues .= " ? ,";
                $arrayPDO = array_merge($arrayPDO, array(functions::format_date_db(
                    $processLimitDate['date'],'true','','true'
                )));
            }
            $_SESSION['store_process_limit_date'] = "";
        }
    }


    if ($core->is_module_loaded('folder')) {
        $folderId = '';
        $folderId = get_value_fields($formValues, 'folder');

        
        if (! empty($folderId)) {
            array_push(
                $_SESSION['data'],
                array(
                    'column' => 'folders_system_id',
                    'value' => $folderId,
                    'type' => 'integer',
                )
            );
        }
    }

    if ($core->is_module_loaded('entities')) {
        // Diffusion list
        $loadListDiff = false;
        if (isset($_ENV['categories'][$catId]['other_cases']['diff_list'])) {
            if (! empty($_SESSION['indexing']['diff_list']['dest']['users'][0])
                && isset($_SESSION['indexing']['diff_list']['dest']['users'][0])
            ) {
                array_push(
                    $_SESSION['data'],
                    array(
                        'column' => 'dest_user',
                        'value' => $_SESSION['indexing']['diff_list']['dest']['users'][0]['user_id'],
                        'type' => 'string'
                    )
                );
            }
            $loadListDiff = true;
        }
    }

    //print_r($_SESSION['data']);
    $resId = $resource->load_into_db(
        $table, $_SESSION['indexing']['destination_dir'],
        $_SESSION['indexing']['file_destination_name'],
        $_SESSION['indexing']['path_template'],
        $_SESSION['indexing']['docserver_id'], $_SESSION['data'],
        $_SESSION['config']['databasetype']
    );
	
    // Contact
    if (isset($_ENV['categories'][$catId]['other_cases']['contact'])) {
        $contact = get_value_fields($formValues, 'contact');
		
        $contactType = get_value_fields(
            $formValues, 'type_contact_external'
        );
		
        if (! $contactType) {
            $contactType = get_value_fields(
                $formValues, 'type_contact_internal'
            );
        }        
		
		if (! $contactType) {
            $contactType = get_value_fields(
                $formValues, 'type_multi_contact_external'
            );
        }

		$nb_multi_contact = count($_SESSION['adresses']['to']);
		
		if($nb_multi_contact > 0 && $contactType == 'multi_external'){
		
			for($icontact = 0; $icontact<$nb_multi_contact; $icontact++){
			
				$db->query("INSERT INTO contacts_res (coll_id, res_id, contact_id, address_id) VALUES (?, ?, ?, ?)",
                    array($collId, $resId, $_SESSION['adresses']['contactid'][$icontact], $_SESSION['adresses']['addressid'][$icontact]));
			
			}
			
			$queryExtFields .= 'is_multicontacts,';
			$queryExtValues .= "'Y',";
		
		} else {
		
            $contactId = get_value_fields(
                $formValues, 'contactid'
            );
            if(!ctype_digit($contactId)){
                $contactType = 'internal';
            }else{
                $contactType = 'external';
            }
            
			if ($contactType == 'internal') {
				if ($catId == 'incoming' || $catId == 'internal' || $catId == 'ged_doc') {
					$queryExtFields .= 'exp_user_id,';
					$queryExtValues .= " ?,";
                    $arrayPDO = array_merge($arrayPDO, array($contactId));
				} else if ($catId == 'outgoing') {
					$queryExtFields .= 'dest_user_id,';
					$queryExtValues .= " ?,";
                    $arrayPDO = array_merge($arrayPDO, array($contactId));
				}
			} else if ($contactType == 'external') {
				if ($catId == 'incoming' || $catId == 'ged_doc') {
					$queryExtFields .= 'exp_contact_id,';
					$queryExtValues .= " ?,";
                    $arrayPDO = array_merge($arrayPDO, array($contactId));
				} else if ($catId == 'outgoing' || $catId == 'internal') {
					$queryExtFields .= 'dest_contact_id,';
					$queryExtValues .= " ?,";
                    $arrayPDO = array_merge($arrayPDO, array($contactId));
				}
                $addressId = get_value_fields(
                    $formValues, 'addressid'
                );
                $queryExtFields .= 'address_id,';
                $queryExtValues .= " ?,"; 
                $arrayPDO = array_merge($arrayPDO, array($addressId));
			}
		}
    }
	
    if ($resId <> false) {
        //Create chrono number
        //######
        $cTypeId = get_value_fields($formValues, 'type_id');
        $cEntity = get_value_fields($formValues, 'destination');

        $cChronoOut = get_value_fields($formValues, 'chrono_number');
        $chronoX = new chrono();
        $myVars = array(
            'entity_id' => $cEntity,
            'type_id' => $cTypeId,
            'category_id' => $catId,
            'folder_id' => $folderId,
        );
        $_SESSION['folderId'] = $folderId;
        $myForm = array(
            'chrono_out' => $cChronoOut,
        );
        $myChrono = $chronoX->generate_chrono($catId, $myVars, $myForm);

        $queryExtFields .= 'alt_identifier,';
        $queryExtValues .= " ?,";
        $arrayPDO = array_merge($arrayPDO, array($myChrono));
        //######
        $queryExtFields = preg_replace('/,$/', ',res_id)', $queryExtFields);
        $queryExtValues = preg_replace(
            '/,$/', ', ?)', $queryExtValues
        );
        $arrayPDO = array_merge($arrayPDO, array($resId));
        $queryExt = " INSERT INTO " . $tableExt . " " . $queryExtFields
                   . ' values ' . $queryExtValues ;

        $db->query($queryExt, $arrayPDO);
        if ($core->is_module_loaded('folder') && ! empty($folderId)
            && $_SESSION['history']['folderup']
        ) {
            $hist = new history();
            $hist->add(
                $_SESSION['tablename']['fold_folders'], $folderId, "UP", 'folderup',
                _DOC_NUM . $resId . _ADDED_TO_FOLDER,
                $_SESSION['config']['databasetype'], 'apps'
            );
        }

        if ($core->is_module_loaded('entities')) {
            if ($loadListDiff) {
                require_once 'modules' . DIRECTORY_SEPARATOR . 'entities'
                    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
                    . 'class_manage_listdiff.php';
                $diffList = new diffusion_list();
                $params = array(
                    'mode' => 'listinstance',
                    'table' => $_SESSION['tablename']['ent_listinstance'],
                    'coll_id' => $collId,
                    'res_id' => $resId,
                    'user_id' => $_SESSION['user']['UserId'],
                );
                $diffList->load_list_db(
                    $_SESSION['indexing']['diff_list'], $params
                );
            }
        }
        if ($core->is_module_loaded('tags')) {
            $tags = get_value_fields($formValues, 'tag_userform');
            $tags_list = explode('__', $tags);
                include_once("modules".DIRECTORY_SEPARATOR."tags"
                .DIRECTORY_SEPARATOR."tags_update.php");
        }

        //thesaurus
        if ($core->is_module_loaded('thesaurus')) {
            require_once 'modules' . DIRECTORY_SEPARATOR . 'thesaurus'
                        . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
                        . 'class_modules_tools.php';
            $thesaurus = new thesaurus();
            $thesaurusList = '';
            $thesaurusList = get_value_fields($formValues, 'thesaurus');
            
            if (! empty($thesaurusList)) {
                $thesaurus->saveResThesaurusList($thesaurusList,$resId);
            }
        }
        
    } else {
        $_SESSION['action_error'] = _ERROR_RES_ID;
        return false;
    }

    if ($attach) {
        $idDoc = get_value_fields($formValues, 'res_id');
        for($i=0;$i<count($_SESSION['stockCheckbox']);$i++){
        $queryLink = "INSERT INTO res_linked (res_parent, res_child, coll_id) VALUES(?, ?, ?)";
        $arrayPDO = array($_SESSION['stockCheckbox'][$i], $resId, $_SESSION['collection_id_choice']);
        $db->query($queryLink, $arrayPDO);
        }
        $hist2 = new history();
        $hist2->add($table,
           $resId,
           "ADD",
           'linkadd',
           _LINKED_TO . $idDoc,
           $_SESSION['config']['databasetype'],
           'apps'
        );

        $hist3 = new history();
        $hist3->add($table,
            $idDoc,
           "UP",
           'linkup',
           '(doc. ' . $resId . ')' . _NOW_LINK_WITH_THIS_ONE,
           $_SESSION['config']['databasetype'],
           'apps'
        );

    }
    if ($core->is_module_loaded('tags')) {
        include_once("modules".DIRECTORY_SEPARATOR."tags"
        .DIRECTORY_SEPARATOR."tags_update.php");
    }

    // $_SESSION['indexing'] = array();
    unset($_SESSION['upfile']);
    unset($_SESSION['data']);
    $_SESSION['action_error'] = _NEW_DOC_ADDED;
    $_SESSION['indexation'] = true;
    return array(
        'result' => $resId . '#',
        'history_msg' => '',
        'page_result' => $_SESSION['config']['businessappurl']
                         . 'index.php?page=details&dir=indexing_searching'
                         . '&coll_id=' . $collId . '&id=' . $resId
    );

}
