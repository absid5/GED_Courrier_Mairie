<?php
/*
*   Copyright 2008-2013 Maarch
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
*/

/**
* @brief   Action : Document validation
*
* Open a modal box to displays the validation form, make the form checks
* and loads the result in database. Used by the core (manage_action.php page).
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/
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

$_SESSION['is_multi_contact'] = '';

include('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'definition_mail_categories.php');

///////////////////// Pattern to check dates
$_ENV['date_pattern'] = "/^[0-3][0-9]-[0-1][0-9]-[1-2][0-9][0-9][0-9]$/";

/**
 * Gets the path of the file to displays
 *
 * @param $res_id String Resource identifier
 * @param $coll_id String Collection identifier
 * @return String File path
 **/
function get_file_path($res_id, $coll_id)
{
    require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
    $sec = new security();
    $view = $sec->retrieve_view_from_coll_id($coll_id);
    if(empty($view))
    {
        $view = $sec->retrieve_table_from_coll($coll_id);
    }
    $db = new Database();
    $stmt = $db->query("SELECT docserver_id, path, filename FROM ".$view." WHERE res_id = ?", array($res_id));
    $res = $stmt->fetchObject();
    $path = preg_replace('/#/', DIRECTORY_SEPARATOR, $res->path);
    $docserver_id = $res->docserver_id;
    $filename = $res->filename;
    $stmt = $db->query("SELECT path_template FROM ".$_SESSION['tablename']['docservers']." WHERE docserver_id = ?", array($docserver_id));
    $res = $stmt->fetchObject();
    $docserver_path = $res->path_template;

    return $docserver_path.$path.$filename;
}

function check_category($coll_id, $res_id)
{
    require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
    $sec = new security();
    $view = $sec->retrieve_view_from_coll_id($coll_id);

    $db = new Database();
    $stmt = $db->query("SELECT category_id FROM ".$view." WHERE res_id = ?", array($res_id));
    $res = $stmt->fetchObject();

    if(!isset($res->category_id))
    {
        $ind_coll = $sec->get_ind_collection($coll_id);
        $table_ext = $_SESSION['collections'][$ind_coll]['extensions'][0];
        $db->query("INSERT INTO ".$table_ext." (res_id, category_id) VALUES (?, ?)",
            array($res_id, $_SESSION['coll_categories']['letterbox_coll']['default_category']));
    }
}

/**
 * Returns the validation form text
 *
 * @param $values Array Contains the res_id of the document to validate
 * @param $path_manage_action String Path to the PHP file called in Ajax
 * @param $id_action String Action identifier
 * @param $table String Table
 * @param $module String Origin of the action
 * @param $coll_id String Collection identifier
 * @param $mode String Action mode 'mass' or 'page'
 * @return String The form content text
 **/
function get_form_txt($values, $path_manage_action,  $id_action, $table, $module, $coll_id, $mode )
{
    if (preg_match("/MSIE 6.0/", $_SERVER["HTTP_USER_AGENT"]))
    {
        $browser_ie = true;
        $display_value = 'block';
    }
    elseif(preg_match('/msie/i', $_SERVER["HTTP_USER_AGENT"]) && !preg_match('/opera/i', $_SERVER["HTTP_USER_AGENT"]) )
    {
        $browser_ie = true;
        $display_value = 'block';
    }
    else
    {
        $browser_ie = false;
        $display_value = 'table-row';
    }
    unset($_SESSION['m_admin']['contact']);
    $_SESSION['req'] = "action";
    $res_id = $values[0];
    $_SESSION['doc_id'] = $res_id;

		// Ouverture de la modal

	$docLockerCustomPath = 'apps/maarch_entreprise/actions/docLocker.php';
    $docLockerPath = $_SESSION['config']['businessappurl'] . '/actions/docLocker.php';
    if (is_file($docLockerCustomPath))
        require_once $docLockerCustomPath;
    else if (is_file($docLockerPath))
        require_once $docLockerPath;
    else
        exit("can't find docLocker.php");

    $docLocker = new docLocker($res_id);
    if (!$docLocker->canOpen()) {
        $docLockerscriptError = '<script>';
            $docLockerscriptError .= 'destroyModal("modal_' . $id_action . '");';
            $docLockerscriptError .= 'alert("'._DOC_LOCKER_RES_ID.''.$res_id.''._DOC_LOCKER_USER.' ' . $_SESSION['userLock'] . '");';
        $docLockerscriptError .= '</script>';
        return $docLockerscriptError;
    }

    $frm_str = '';
    require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
    require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_business_app_tools.php");
    require_once("modules".DIRECTORY_SEPARATOR."basket".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_modules_tools.php");
    require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_types.php");
    require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");

    $sec =new security();
    $core_tools =new core_tools();
    $b = new basket();
    $type = new types();
    $business = new business_app_tools();

    $_SESSION['save_list']['fromValidateMail'] = "true";
    $_SESSION['count_view_baskets']=0;

    if($_SESSION['features']['show_types_tree'] == 'true')
    {
        $doctypes = $type-> getArrayStructTypes($coll_id);
    }
    else
    {
        $doctypes = $type->getArrayTypes($coll_id);
    }
    $db = new Database();
    $hidden_doctypes = array();
    $tmp = $business->get_titles();
    $titles = $tmp['titles'];
    $default_title = $tmp['default_title'];
    if($core_tools->is_module_loaded('templates'))
    {
        $stmt = $db->query("SELECT type_id FROM ".$_SESSION['tablename']['temp_templates_doctype_ext']." WHERE is_generated = 'NULL!!!'");
        while($res = $stmt->fetchobject())
        {
            array_push($hidden_doctypes, $res->type_id);
        }
    }
    $today = date('d-m-Y');

    if ($core_tools->is_module_loaded('entities')) {
        $EntitiesIdExclusion = array();
        $db = new Database();
        if (count($_SESSION['user']['redirect_groupbasket'][$_SESSION['current_basket']['id']][$id_action]['entities']) > 0) {
            $stmt = $db->query(
                "SELECT entity_id FROM "
                . ENT_ENTITIES . " WHERE entity_id not in ("
                . $_SESSION['user']['redirect_groupbasket'][$_SESSION['current_basket']['id']][$id_action]['entities']
                . ") and enabled= 'Y' order by entity_id"
            );
            while ($res = $stmt->fetchObject()) {
                array_push($EntitiesIdExclusion, $res->entity_id);
            }
        }
        require_once 'modules/entities/class/class_manage_entities.php';
        $ent = new entity();
        $allEntitiesTree= array();
        $allEntitiesTree = $ent->getShortEntityTreeAdvanced(
            $allEntitiesTree, 'all', '', $EntitiesIdExclusion, 'all'
        );

        //diffusion list in this basket ?
        if($_SESSION['current_basket']['difflist_type'] == 'entity_id'){
            $target_model = 'document.getElementById(\'destination\').options[document.getElementById(\'destination\').selectedIndex]';
            $func_load_listdiff_by_entity = 'change_entity(this.options[this.selectedIndex].value, \''.$_SESSION['config']['businessappurl'].'index.php?display=true&module=entities&page=load_listinstance'.'\',\'diff_list_div\', \'indexing\', \''.$display_value.'\', \'\', $(\'category_id\').value);';
        }else if($_SESSION['current_basket']['difflist_type'] == 'type_id'){
            $target_model = 'document.getElementById(\'type_id\').options[document.getElementById(\'type_id\').selectedIndex]';
            $func_load_listdiff_by_type = 'load_listmodel('.$target_model.', \'diff_list_div\', \'indexing\', $(\'category_id\').value);';
        }else{
            $target_model = 'document.getElementById(\'destination\').options[document.getElementById(\'destination\').selectedIndex]';
            $func_load_listdiff_by_entity = 'change_entity(this.options[this.selectedIndex].value, \''.$_SESSION['config']['businessappurl'].'index.php?display=true&module=entities&page=load_listinstance'.'\',\'diff_list_div\', \'indexing\', \''.$display_value.'\', \'\', $(\'category_id\').value);';
        }

        //LOADING LISTMODEL
        require_once('modules/entities/class/class_manage_listdiff.php');
        $diff_list = new diffusion_list();
        $load_listmodel = true;
        $stmt = $db->query("SELECT res_id FROM " . $_SESSION['tablename']['ent_listinstance']." WHERE res_id = ?", array($res_id));
        if ($stmt->rowCount() > 0) {
            $load_listmodel = false;
            $_SESSION['indexing']['diff_list'] = $diff_list->get_listinstance($res_id);
        }
    }
	
	//Load Multicontacts
	//CONTACTS
    $query = "SELECT c.is_corporate_person, c.is_private, c.contact_lastname, c.contact_firstname, c.society, c.society_short, c.contact_purpose_id, c.address_num, c.address_street, c.address_postal_code, c.address_town, c.lastname, c.firstname, c.contact_id, c.ca_id ";
		$query .= "FROM view_contacts c, contacts_res cres ";
		$query .= "WHERE cres.coll_id = 'letterbox_coll' AND cres.res_id = ? AND cast (c.contact_id as varchar) = cres.contact_id AND c.ca_id = cres.address_id";			
	$stmt = $db->query($query, array($res_id));
	
    $_SESSION['adresses']['to'] = array();
    $_SESSION['adresses']['addressid'] = array();
	$_SESSION['adresses']['contactid'] = array();
	
	while($res = $stmt->fetchObject()){

        if ($res->is_corporate_person == 'Y') {
            $addContact = $res->society . ' ' ;
            if (!empty ($res->society_short)) {
                $addContact .= '('.$res->society_short.') ';
            }
        } else {
            $addContact = $res->contact_lastname . ' ' . $res->contact_firstname . ' ';
            if (!empty ($res->society)) {
                $addContact .= '(' .$res->society . ') ';
            }                        
        }
        if ($res->is_private == 'Y') {
            $addContact .= '('._CONFIDENTIAL_ADDRESS.')';
        } else {
            require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_contacts_v2.php");
            $contact = new contacts_v2();
            $addContact .= '- ' . $contact->get_label_contact($res->contact_purpose_id, $_SESSION['tablename']['contact_purposes']).' : ';
            if (!empty($res->lastname) || !empty($res->firstname)) {
                $addContact .= $res->lastname . ' ' . $res->firstname;
            }
            if (!empty($res->address_num) || !empty($res->address_street) || !empty($res->address_town) || !empty($res->address_postal_code)) {
                $addContact .= ', '.$res->address_num .' ' . $res->address_street .' ' . $res->address_postal_code .' ' . strtoupper($res->address_town);
            }         
        }

         array_push($_SESSION['adresses']['to'], $addContact);
         array_push($_SESSION['adresses']['addressid'], $res->ca_id);
		 array_push($_SESSION['adresses']['contactid'], $res->contact_id);
	}
	
    //USERS
	$query = "SELECT u.firstname, u.lastname, u.user_id ";
		$query .= "FROM users u, contacts_res cres ";
		$query .= "WHERE cres.coll_id = 'letterbox_coll' AND cres.res_id = ? AND cast (u.user_id as varchar) = cres.contact_id";			
	$stmt = $db->query($query, array($res_id));
	
	while($res = $stmt->fetchObject()){
		$addContact = $res->firstname . $res->lastname;
         array_push($_SESSION['adresses']['to'], $addContact);
         array_push($_SESSION['adresses']['addressid'], 0);
		 array_push($_SESSION['adresses']['contactid'], $res->user_id);
	}
    
    check_category($coll_id, $res_id);
    $data = get_general_data($coll_id, $res_id, 'minimal');

    $frm_str .= '<h2 class="tit" id="action_title">'._VALIDATE_MAIL.' '._NUM.functions::xssafe($res_id);
    $frm_str .= '</h2>';
    $frm_str .='<i onmouseover="this.style.cursor=\'pointer\';" '
             .'onclick="new Ajax.Request(\'' . $_SESSION['config']['businessappurl'] 
                . 'index.php?display=true&dir=actions&page=docLocker\',{ method:\'post\', parameters: {\'AJAX_CALL\': true, \'unlock\': true, \'res_id\': ' 
                . functions::xssafe($res_id) . '}, onSuccess: function(answer){window.location.href=\'' 
                . $_SESSION['config']['businessappurl']. 'index.php?page=view_baskets&module=basket&baskets=' 
                . $_SESSION['current_basket']['id'] . '\';} });$(\'baskets\').style.visibility=\'visible\';destroyModal(\'modal_'.$id_action.'\');reinit();"'
             .' class="fa fa-times-circle fa-2x closeModale" title="'._CLOSE.'"/>';
    $frm_str .='</i>';
    $frm_str .= '<div id="validleft">';
    $frm_str .= '<div id="valid_div" style="display:none;";>';
                    $frm_str .= '<div id="frm_error_'.$id_action.'" class="indexing_error"></div>';
                    $frm_str .= '<form name="index_file" method="post" id="index_file" action="#" class="forms indexingform" style="text-align:left;">';

                    $frm_str .= '<input type="hidden" name="values" id="values" value="'.$res_id.'" />';
                    $frm_str .= '<input type="hidden" name="action_id" id="action_id" value="'.$id_action.'" />';
                    $frm_str .= '<input type="hidden" name="mode" id="mode" value="'.$mode.'" />';
                    $frm_str .= '<input type="hidden" name="table" id="table" value="'.$table.'" />';
                    $frm_str .= '<input type="hidden" name="coll_id" id="coll_id" value="'.$coll_id.'" />';
                    $frm_str .= '<input type="hidden" name="module" id="module" value="'.$module.'" />';
                    $frm_str .= '<input type="hidden" name="req" id="req" value="second_request" />';
                    $frm_str .= '<input type="hidden" id="check_days_before" value="'.$_SESSION['check_days_before'].'" />';

            $frm_str .= '<div  style="display:block">';

    
    $frm_str .= '<hr width="90%" align="center"/>';
    
    $frm_str .= '<h4 onclick="new Effect.toggle(\'general_infos_div\', \'blind\', {delay:0.2});'
        . 'whatIsTheDivStatus(\'general_infos_div\', \'divStatus_general_infos_div\');" '
        . 'class="categorie" style="width:90%;" onmouseover="this.style.cursor=\'pointer\';">';
    $frm_str .= ' <span id="divStatus_general_infos_div" style="color:#1C99C5;"><i class="fa fa-minus-square-o"></i></span>&nbsp;' 
        ._GENERAL_INFO;
    $frm_str .= '</h4>';
    $frm_str .= '<div id="general_infos_div"  style="display:inline">';
    $frm_str .= '<div class="ref-unit">';
    $_SESSION['category_id'] = $data['category_id']['value'];
                  $frm_str .= '<table width="100%" align="center" border="0"  id="indexing_fields" style="display:block;">';
                  /*** Category ***/
                  $frm_str .= '<tr id="category_tr" style="display:'.$display_value.';">';
                    $frm_str .='<td class="indexing_label"><label for="category_id" class="form_title" >'._CATEGORY.'</label></td>';
                    $frm_str .='<td>&nbsp;</td>';
                    $frm_str .='<td class="indexing_field"><select name="category_id" id="category_id" onchange="clear_error(\'frm_error_' 
                             . $id_action . '\');change_category(this.options[this.selectedIndex].value, \''.$display_value.'\',  \''
                             . $_SESSION['config']['businessappurl'] . 'index.php?display=true&dir=indexing_searching&page=change_category\',  \'' 
                             . $_SESSION['config']['businessappurl'].'index.php?display=true&page=get_content_js\');change_category_actions(\'' 
                             . $_SESSION['config']['businessappurl'] 
                             . 'index.php?display=true&dir=indexing_searching&page=change_category_actions'
                             . '&resId=' . $res_id . '&collId=' . $coll_id . '\');">';
                                $frm_str .='<option value="">'._CHOOSE_CATEGORY.'</option>';
                            foreach (array_keys($_SESSION['coll_categories']['letterbox_coll']) as $cat_id) {
                                if ($cat_id <> 'default_category') {
                                    $frm_str .='<option value="'.functions::xssafe($cat_id).'"';
                                    if (
                                        (isset($data['category_id']['value']) && $data['category_id']['value'] == $cat_id)
                                        || $_SESSION['coll_categories']['letterbox_coll']['default_category'] == $cat_id
                                        || $_SESSION['indexing']['category_id'] == $cat_id
                                    ) {
                                        $frm_str .='selected="selected"';
                                    }
                                    $frm_str .='>'.functions::xssafe($_SESSION['coll_categories']['letterbox_coll'][$cat_id]).'</option>';
                                }
                            }
                        $frm_str.='</select></td>';
                        $frm_str .= '<td><span class="red_asterisk" id="category_id_mandatory" style="display:inline;vertical-align:text-top"><i class="fa fa-star"></i></span></td>';
                  $frm_str .= '</tr>';
                   /*** Doctype ***/
                  $frm_str .= '<tr id="type_id_tr" style="display:'.$display_value.';">';
                    $frm_str .='<td class="indexing_label"><span class="form_title" id="doctype_res" style="display:none;">'._DOCTYPE.'</span><span class="form_title" id="doctype_mail" style="display:inline;" >'._DOCTYPE_MAIL.'</span></td>';
                    $frm_str .='<td>&nbsp;</td>';
                    $frm_str .='<td class="indexing_field"><select name="type_id" id="type_id" onchange="clear_error(\'frm_error_'.$id_action.'\');changePriorityForSve(this.options[this.selectedIndex].value,\''
            . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
            . '&dir=indexing_searching&page=priority_for_sve\');change_doctype(this.options[this.selectedIndex].value, \''.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=change_doctype\', \''._ERROR_DOCTYPE.'\', \''.$id_action.'\', \''.$_SESSION['config']['businessappurl'].'index.php?display=true&page=get_content_js\' , \''.$display_value.'\','.$res_id.', \''.$coll_id.'\')'.$func_load_listdiff_by_type.'">';
                            $frm_str .='<option value="">'._CHOOSE_TYPE.'</option>';
                            if ($_SESSION['features']['show_types_tree'] == 'true') {
                                for ($i = 0; $i < count($doctypes); $i ++) {
                                    $frm_str .= '<optgroup value="" class="' //doctype_level1
                                            . $doctypes[$i]['style'] . '" label="'
                                            . functions::xssafe($doctypes[$i]['label']) . '" >';
                                    for ($j = 0; $j < count($doctypes[$i]['level2']); $j ++) {
                                        $frm_str .= '<optgroup value="" class="' //doctype_level2
                                                . $doctypes[$i]['level2'][$j]['style'] .'" label="&nbsp;&nbsp;'
                                                . functions::xssafe($doctypes[$i]['level2'][$j]['label']) . '" >';
                                        for ($k = 0; $k < count($doctypes[$i]['level2'][$j]['types']);
                                            $k ++
                                        ) {
                                            if (!in_array($doctypes[$i]['level2'][$j]['types'][$k]['id'],$hidden_doctypes)) {
                                                $frm_str .='<option data-object_type="type_id" value="'.functions::xssafe($doctypes[$i]['level2'][$j]['types'][$k]['id']).'" ';
                                                if (isset($data['type_id']) && !empty($data['type_id']) && $data['type_id'] == $doctypes[$i]['level2'][$j]['types'][$k]['id']) {
                                                    $frm_str .= ' selected="selected" ';
                                                }
                                                $frm_str .=' title="'.functions::xssafe($doctypes[$i]['level2'][$j]['types'][$k]['label'])
                                                . '" label="'.functions::xssafe($doctypes[$i]['level2'][$j]['types'][$k]['label'])
                                                . '">&nbsp;&nbsp;&nbsp;&nbsp;'.functions::xssafe($doctypes[$i]['level2'][$j]['types'][$k]['label']).'</option>';
                                            }
                                        }
                                        $frmStr .= '</optgroup>'; 
                                    }
                                    $frmStr .= '</optgroup>'; 
                                }
                            }
                            else
                            {
                                for($i=0; $i<count($doctypes);$i++)
                                {
                                    $frm_str .='<option value="'.functions::xssafe($doctypes[$i]['ID']).'" ';
                                    if(isset($data['type_id']) && !empty($data['type_id']) && $data['type_id'] == $doctypes[$i]['ID'])
                                    {
                                        $frm_str .= ' selected="selected" ';
                                    }
                                    $frm_str .=' >'.functions::xssafe($doctypes[$i]['LABEL']).'</option>';
                                }
                            }
                            $frm_str .='</select>';
                            $frm_str .= '<td><span class="red_asterisk" id="type_id_mandatory" style="display:inline;vertical-align:text-top"><i class="fa fa-star"></i></span></td>';
                  $frm_str .= '</tr>';
                /*** Priority ***/
                  $frm_str .= '<tr id="priority_tr" style="display:'.$display_value.';">';
                        $frm_str .='<td class="indexing_label"><label for="priority" class="form_title" >'._PRIORITY.'</label></td>';
                        $frm_str .='<td>&nbsp;</td>';
                        $frm_str .='<td class="indexing_field"><select name="priority" id="priority" onChange="updateProcessDate(\''
                                    . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
                                    . '&dir=indexing_searching&page=update_process_date\');" onFocus="updateProcessDate(\''
                                    . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
                                    . '&dir=indexing_searching&page=update_process_date\');clear_error(\'frm_error_' . $id_action
                                    . '\');">';
                            $frm_str .='<option value="">'._CHOOSE_PRIORITY.'</option>';
                                for($i=0; $i<count($_SESSION['mail_priorities']);$i++)
                                {
                                    $frm_str .='<option value="'.functions::xssafe($i).'" ';
                                    if(isset($data['type_id'])&& $data['priority'] == $i)
                                    {
                                        $frm_str .='selected="selected"';
                                    }else if($data['priority']=='' && $_SESSION['default_mail_priority']==$i){
					$frm_str .='selected="selected"';
				}
                                    $frm_str .='>'.functions::xssafe($_SESSION['mail_priorities'][$i]).'</option>';
                                }
                            $frm_str .='</select></td>';
                            $frm_str .= '<td><span class="red_asterisk" id="priority_mandatory" style="display:inline;"><i class="fa fa-star"></i></span>&nbsp;</td>';
                  $frm_str .= '</tr>';

                  /*** Confidentiality ***/
                    $frm_str .= '<tr id="confidentiality_tr" style="display:' . $display_value
                            . ';">';
                    $frm_str .= '<td><label for="confidentiality" class="form_title" >'
                            . _CONFIDENTIALITY . ' </label></td>';
                    $frm_str .= '<td>&nbsp;</td>';
                    $frm_str .= '<td class="indexing_field"><input type="radio" '
                            . 'name="confidentiality" id="confidential" value="Y" />'
                            . _YES . ' <input type="radio" name="confidentiality" id="no_confidential"'
                            . ' value="N" checked="checked" />'
                            . _NO . '</td>';
                    $frm_str .= ' <td><span class="red_asterisk" id="confidentiality_mandatory" '
                            . 'style="display:inline;vertical-align:text-top"><i class="fa fa-star"></i></span>&nbsp;</td>';
                    $frm_str .= '</tr>';

                  /*** Doc date ***/
                   $frm_str .= '<tr id="doc_date_tr" style="display:'.$display_value.';">';
                        $frm_str .='<td class="indexing_label"><label for="doc_date" class="form_title" id="mail_date_label" style="display:inline;" >'._MAIL_DATE.'</label><label for="doc_date" class="form_title" id="doc_date_label" style="display:none;" >'._DOC_DATE.'</label></td>';
                        $frm_str .='<td>&nbsp;</td>';
                        $frm_str .='<td class="indexing_field"><input name="doc_date" type="text" id="doc_date" value="';
                        if(isset($data['doc_date'])&& !empty($data['doc_date']))
                        {
                            $frm_str .= $data['doc_date'];
                        }
                        else
                        {
                            $frm_str .= $today;
                        }
                        $frm_str .= '" onfocus="checkRealDate(\'docDate\');" onChange="checkRealDate(\'docDate\');" onclick="clear_error(\'frm_error_'.$id_action.'\');showCalender(this);"/></td>';
                        $frm_str .= '<td><span class="red_asterisk" id="doc_date_mandatory" style="display:inline;"><i class="fa fa-star"></i></span>&nbsp;</td>';
                  $frm_str .= '</tr >';
                  /*** Author ***/
                   $frm_str .= '<tr id="author_tr" style="display:'.$display_value.';">';
                        $frm_str .='<td class="indexing_label"><label for="author" class="form_title" >'._AUTHOR.'</label></td>';
                        $frm_str .='<td>&nbsp;</td>';
                        $frm_str .='<td class="indexing_field"><input name="author" type="text" id="author" onchange="clear_error(\'frm_error_'.$id_action.'\');"';
                        if(isset($data['author'])&& !empty($data['author']))
                        {
                            $frm_str .= ' value="'.$data['author'].'" ';
                        }
                        else
                        {
                            $frm_str .= ' value="" ';
                        }
                        $frm_str .= '/></td>';
                        $frm_str .= '<td><span class="red_asterisk" id="author_mandatory" style="display:inline;"><i class="fa fa-star"></i></span>&nbsp;</td>';
                  $frm_str .= '</tr>';
                  /*** Admission date ***/
                  $frm_str .= '<tr id="admission_date_tr" style="display:'.$display_value.';">';
                        $frm_str .='<td class="indexing_label"><label for="admission_date" class="form_title" >'._RECEIVING_DATE.'</label></td>';
                        $frm_str .='<td>&nbsp;</td>';
                        $frm_str .='<td class="indexing_field"><input name="admission_date" type="text" id="admission_date" value="';
                        if(isset($data['admission_date'])&& !empty($data['admission_date']))
                        {
                            $frm_str .= $data['admission_date'];
                        }
                        else
                        {
                            $frm_str .= $today;
                        }
                        $frm_str .= '" onclick="clear_error(\'frm_error_' . $actionId . '\');'
            . 'showCalender(this);" onChange="checkRealDate(\'admissionDate\');updateProcessDate(\''
            . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
            . '&dir=indexing_searching&page=update_process_date\');" onFocus="checkRealDate(\'admissionDate\');updateProcessDate(\''
            . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
            . '&dir=indexing_searching&page=update_process_date\');"/></td>';
                        $frm_str .= '<td><span class="red_asterisk" id="admission_date_mandatory" style="display:inline;"><i class="fa fa-star"></i></span>&nbsp;</td>';
                  $frm_str .= '</tr>';
				  
                /*** Contact ***/
                  $frm_str .= '<tr id="contact_choose_tr" style="display:'.$display_value.';">';
					   $frm_str .='<td class="indexing_label"><label for="type_contact" class="form_title" ><span id="exp_contact_choose_label">'._SHIPPER_TYPE.'</span><span id="dest_contact_choose_label">'._DEST_TYPE.'</span></label></td>';
					   $frm_str .='<td>&nbsp;</td>';
					   $frm_str .='<td class="indexing_field"><input type="radio" name="type_contact" id="type_contact_internal" value="internal"  class="check" onclick="clear_error(\'frm_error_'.$id_action.'\');change_contact_type(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=autocomplete_contacts\');update_contact_type_session(\''
        .$_SESSION['config']['businessappurl']
        .'index.php?display=true&dir=indexing_searching&page=autocomplete_contacts_prepare_multi\');reset_check_date_exp();"';

						if ($data['type_contact'] == 'internal') {
							$frm_str .= ' checked="checked" '; 
						}
                    $frm_str .= ' /><label for="type_contact_internal">'._INTERNAL2.'</label></td></tr>';
					
					$frm_str .= '<tr id="contact_choose_2_tr" style="display:'.$display_value.';">';
					   $frm_str .='<td>&nbsp;</td>';
					   $frm_str .='<td>&nbsp;</td>';
					   $frm_str .='<td class="indexing_field">';					
						$frm_str .= '<input type="radio" name="type_contact" class="check" id="type_contact_external" value="external" onclick="clear_error(\'frm_error_'.$id_action.'\');change_contact_type(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=autocomplete_contacts\');update_contact_type_session(\''
        .$_SESSION['config']['businessappurl']
        .'index.php?display=true&dir=indexing_searching&page=autocomplete_contacts_prepare_multi\');"';
						if ($data['type_contact'] == 'external') {
							$frm_str .= ' checked="checked" ';
						}
                    $frm_str .= '/><label for="type_contact_external">'._EXTERNAL.'</label></td></tr>';
					
					$frm_str .= '<tr id="contact_choose_3_tr" style="display:' . $displayValue
								. ';">';
						$frm_str .= '<td>&nbsp;</td>';
						$frm_str .= '<td>&nbsp;</td>';
						$frm_str .= '<td class="indexing_field"><input type="radio" name="type_contact" '
								. 'id="type_multi_contact_external" value="multi_external" '
								. 'onclick="clear_error(\'frm_error_' . $id_action . '\');'
								. 'change_contact_type(\'' . $_SESSION['config']['businessappurl']
								. 'index.php?display=true&dir=indexing_searching'
								. '&autocomplete_contacts\', true);update_contact_type_session(\''
        .$_SESSION['config']['businessappurl']
        .'index.php?display=true&dir=indexing_searching&page=autocomplete_contacts_prepare_multi\');"  class="check" ';
						if ($data['type_contact'] == 'multi_external') {
							$frm_str .= ' checked="checked" ';
						}
						$frm_str .= '/><label for="type_multi_contact_external">' . _MULTI_CONTACT	.'</label>'		
						. '</td>';
                    $frm_str .= '</tr>';
					 
					$frm_str .= '<tr id="contact_id_tr" style="display:'.$display_value.';">';
                   $frm_str .='<td class="indexing_label" style="vertical-align:bottom;"><label for="contact" class="form_title" ><span id="exp_contact">'._SHIPPER.'</span><span id="dest_contact">'._DEST.'</span>'
                            . '<span id="author_contact">' . _AUTHOR_DOC . '</span>';
                    if ($core->test_admin('my_contacts', 'apps', false)) {
                        $frm_str .=' <a href="#" id="create_contact" title="'._CREATE_CONTACT.'" onclick="new Effect.toggle(\'create_contact_div\', \'blind\', {delay:0.2});return false;" style="display:inline;" >'
                            .'<i class="fa fa-pencil" title="' . _CREATE_CONTACT . '"></i></a>';
                    } else {
                        $frm_str .= ' <a href="#" id="create_contact"/></a>';       
                    }
                     $frm_str .= '</label></td>';
                    $contact_mode = "view";
                    if($core_tools->test_service('update_contacts','apps', false)) $contact_mode = 'up';
                   $frm_str .='<td style="vertical-align:bottom;"><a href="#" id="contact_card" class="fa fa-book fa-2x" title="'._CONTACT_CARD.'" onclick="loadInfoContact();new Effect.toggle(\'info_contact_div\', '
                        . '\'blind\', {delay:0.2});return false;"'
                        . ' style="visibility:hidden;display:inline;" ></a>&nbsp;</td>';
		//Path to actual script
    $path_to_script = $_SESSION['config']['businessappurl']
		."index.php?display=true&dir=indexing_searching&page=contact_check&coll_id=".$collId;
    $path_check_date_link = $_SESSION['config']['businessappurl']
        .'index.php?display=true&dir=indexing_searching&page=documents_list_mlb_search_adv&mode=popup&action_form=show_res_id&modulename=attachments&init_search&nodetails&fromContactCheck&fromValidateMail';
    //check functions on load page
/*        if (condition) {
            $frm_str.="<script>check_date_exp('".$path_to_script."');</script>";
        }*/

                   $frm_str .='<td class="indexing_field">';
                    if($data['type_contact'] == 'internal'){
                        //$frm_str .= '<i class="fa fa-user" title="'._INTERNAL2.'" style="cursor:pointer;color:#009DC5;" id="type_contact_internal_icon" onclick="$$(\'#type_contact_internal\')[0].click();$(\'type_contact_internal_icon\').setStyle({color: \'#009DC5\'});$(\'type_contact_external_icon\').setStyle({color: \'#666\'});$(\'type_multi_contact_external_icon\').setStyle({color: \'#666\'});"></i>';

                        $frm_str .=' <i class="fa fa-user" title="'._SINGLE_CONTACT.'" style="cursor:pointer;color:#009DC5;" id="type_contact_external_icon" onclick="$$(\'#type_contact_external\')[0].click();$(\'type_contact_internal_icon\').setStyle({color: \'#666\'});$(\'type_contact_external_icon\').setStyle({color: \'#009DC5\'});$(\'type_multi_contact_external_icon\').setStyle({color: \'#666\'});"></i>';

                    }elseif ($data['type_contact'] == 'external') {
                        //$frm_str .= '<i class="fa fa-user" title="'._INTERNAL2.'" style="cursor:pointer;" id="type_contact_internal_icon" onclick="$$(\'#type_contact_internal\')[0].click();$(\'type_contact_internal_icon\').setStyle({color: \'#009DC5\'});$(\'type_contact_external_icon\').setStyle({color: \'#666\'});$(\'type_multi_contact_external_icon\').setStyle({color: \'#666\'});"></i>';

                        $frm_str .=' <i class="fa fa-user" title="'._SINGLE_CONTACT.'" style="cursor:pointer;color:#009DC5;" id="type_contact_external_icon" onclick="$$(\'#type_contact_external\')[0].click();$(\'type_contact_internal_icon\').setStyle({color: \'#666\'});$(\'type_contact_external_icon\').setStyle({color: \'#009DC5\'});$(\'type_multi_contact_external_icon\').setStyle({color: \'#666\'});"></i>';

                    }
                   
                    $frm_str .=' <i class="fa fa-users" title="'._MULTI_CONTACT.'" style="cursor:pointer;" id="type_multi_contact_external_icon" onclick="$$(\'#type_multi_contact_external\')[0].click();$(\'type_contact_internal_icon\').setStyle({color: \'#666\'});$(\'type_contact_external_icon\').setStyle({color: \'#666\'});$(\'type_multi_contact_external_icon\').setStyle({color: \'#009DC5\'});"></i>';
    
                   $frm_str .='<span style="position:relative;"><input type="text" onkeyup="erase_contact_external_id(\'contact\', \'contactid\');erase_contact_external_id(\'contact\', \'addressid\');" name="contact" id="contact" onchange="clear_error(\'frm_error_'.$id_action.'\');display_contact_card(\'visible\');" onblur="display_contact_card(\'visible\');if(document.getElementById(\'type_contact_external\').checked == true){check_date_exp(\''.$path_to_script.'\',\''.$path_check_date_link.'\');}"';
                    if(isset($data['contact']) && !empty($data['contact']))
                   {
                      $frm_str .= ' value="'.$data['contact'].'" ';
                    }
                    $frm_str .=  ' /><div id="show_contacts" class="autocomplete autocompleteIndex" style="width:100%;left:0px;top:17px;"></div><div class="autocomplete autocompleteIndex" id="searching_autocomplete" style="display: none;text-align:left;padding:5px;left:0px;width:100%;top:17px;"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> chargement ...</div></span></td>';
                   $frm_str .= '<td><span class="red_asterisk" id="contact_mandatory" style="display:inline;vertical-align:text-top"><i class="fa fa-star"></i></span></td>';
                     $frm_str .= '</tr>';
		   $frm_str .= '<tr style="display:none" id="contact_check"><td></td></tr>';
                    $frm_str .= '<input type="hidden" id="contactid" ';
                        if(isset($data['contactId']) && !empty($data['contactId'])){
                            $frm_str .= ' value="'.$data['contactId'].'" ';
                        }
                    $frm_str .= '/>';
                    $frm_str .= '<input type="hidden" id="addressid" ';
                        if(isset($data['addressId']) && !empty($data['addressId'])){
                            $frm_str .= ' value="'.$data['addressId'].'" ';
                        }
                    $frm_str .= '/>';
		    $frm_str .= '<input type="hidden" id="contactcheck" value="success"/>';
					 
					/****multicontact***/
					
					//Path to actual script
					$path_to_script = $_SESSION['config']['businessappurl']
						."index.php?display=true&dir=indexing_searching&page=add_multi_contacts&coll_id=".$collId;
					
					//$_SESSION['adresses'] = '';
					
					$frm_str .= '<tr id="add_multi_contact_tr" style="display:' . $displayValue . ';">';
						$frm_str .= '<td><label for="contact" class="form_title" >'
							. '<span id="dest_multi_contact">' . _DEST . '</span>';
                    if ($core->test_admin('my_contacts', 'apps', false)) {
						$frm_str .= ' <a href="#" id="create_multi_contact" title="' . _CREATE_CONTACT
								. '" onclick="new Effect.toggle(\'create_contact_div\', '
								. '\'blind\', {delay:0.2});return false;" '
								. 'style="display:inline;" ><i class="fa fa-pencil" title="' . _CREATE_CONTACT . '"></i></a>';
					}
					$frm_str .= '</label></td>';
					$contact_mode = "view";
					if($core->test_service('update_contacts','apps', false)) $contact_mode = 'update';
					$frm_str .= '<td><a href="#" id="multi_contact_card" class="fa fa-book fa-2x" title="' . _CONTACT_CARD
							. '" onclick="loadInfoContact();new Effect.toggle(\'info_contact_div\', '
                . '\'blind\', {delay:0.2});return false;" '
							. 'style="visibility:hidden;display:inline;text-align:right;" ></a>&nbsp;</td>';
					$frm_str .= '<td class="indexing_field">';

                    //$frm_str .= '<i class="fa fa-user" title="'._INTERNAL2.'" style="cursor:pointer;" id="type_contact_internal_icon" onclick="$$(\'#type_contact_internal\')[0].click();$(\'type_contact_internal_icon\').setStyle({color: \'#009DC5\'});$(\'type_contact_external_icon\').setStyle({color: \'#666\'});$(\'type_multi_contact_external_icon\').setStyle({color: \'#666\'});"></i>';

                    $frm_str .=' <i class="fa fa-user" title="'._SINGLE_CONTACT.'" style="cursor:pointer;" id="type_contact_external_icon" onclick="$$(\'#type_contact_external\')[0].click();$(\'type_contact_internal_icon\').setStyle({color: \'#666\'});$(\'type_contact_external_icon\').setStyle({color: \'#009DC5\'});$(\'type_multi_contact_external_icon\').setStyle({color: \'#666\'});"></i>';

                    $frm_str .=' <i class="fa fa-users" title="'._MULTI_CONTACT.'" style="cursor:pointer;color:#009DC5;" id="type_multi_contact_external_icon" onclick="$$(\'#type_multi_contact_external\')[0].click();$(\'type_contact_internal_icon\').setStyle({color: \'#666\'});$(\'type_contact_external_icon\').setStyle({color: \'#666\'});$(\'type_multi_contact_external_icon\').setStyle({color: \'#009DC5\'});"></i>';
                     
                    $frm_str .='<span style="position:relative;"><input type="text" name="email" id="email" onblur="clear_error(\'frm_error_' . $id_action . '\');display_contact_card(\'visible\', \'multi_contact_card\');"/>';
                    $frm_str .= '<div id="multiContactList" class="autocomplete" style="left:0px;width:100%;top:17px;"></div><div class="autocomplete autocompleteIndex" id="searching_autocomplete_multi" style="display: none;text-align:left;padding:5px;left:0px;width:100%;top:17px;"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> chargement ...</div></span>';
					$frm_str .= '<script type="text/javascript">addMultiContacts(\'email\', \'multiContactList\', \''
						.$_SESSION['config']['businessappurl']
						.'index.php?display=true&dir=indexing_searching&page=autocomplete_contacts\', \'Input\', \'2\', \'contactid\', \'addressid\');</script>';
					$frm_str .='<input type="button" name="add" value="&nbsp;'._ADD
									.'&nbsp;" id="valid_multi_contact" class="button" onclick="updateMultiContacts(\''.$path_to_script
									.'&mode=adress\', \'add\', document.getElementById(\'email\').value, '
									.'\'to\', false, document.getElementById(\'addressid\').value, document.getElementById(\'contactid\').value);display_contact_card(\'hidden\', \'multi_contact_card\');" />';
					$frm_str .= '</td>';
					$frm_str .= '</tr>';
					$frm_str .= '<tr id="show_multi_contact_tr">';
					$frm_str .= '<td align="right" nowrap width="10%" id="to_multi_contact"><label>'
						._SEND_TO_SHORT.'</label></td>';
					$frm_str .= '<td>&nbsp;</td><td style="width:200px"><div name="to" id="to" class="multicontactInput">';
					
					$nbContacts = count($_SESSION['adresses']['to']);

					if($nbContacts > 0){
						for($icontacts = 0; $icontacts < $nbContacts; $icontacts++){
							$frm_str .= '<div class="multicontact_element" id="'.$icontacts.'_'.$_SESSION['adresses']['to'][$icontacts].'">'.$_SESSION['adresses']['to'][$icontacts];
							//if ($readOnly === false) {
								$frm_str .= '&nbsp;<div class="email_delete_button" id="'.$icontacts.'"'
									. 'onclick="updateMultiContacts(\''.$path_to_script
									.'&mode=adress\', \'del\', \''.$_SESSION['adresses']['to'][$icontacts].'\', \'to\', this.id, \''.$_SESSION['adresses']['addressid'][$icontacts].'\', \''.$_SESSION['adresses']['contactid'][$icontacts].'\');" alt="'._DELETE.'" title="'
									._DELETE.'">x</div>';
							//}
							$frm_str .= '</div>';
						}
					}
					
					$frm_str .= '</div></td>';
					$frm_str .= '<td><span class="red_asterisk" id="contact_mandatory" '
							. 'style="display:inline;vertical-align:text-top"><i class="fa fa-star"></i></span></td>';
					$frm_str .= '</tr>';	
					
                /*** Nature ***/
                 $frm_str .= '<tr id="nature_id_tr" style="display:'.$display_value.';">';
                        $frm_str .='<td class="indexing_label"><label for="nature_id" class="form_title" >'._NATURE.'</label></td>';
                        $frm_str .='<td>&nbsp;</td>';
                        $frm_str .='<td class="indexing_field"><select name="nature_id" id="nature_id" onchange="clear_error(\'frm_error_'.$id_action.'\');affiche_reference();">';
                            $frm_str .='<option value="">'. _CHOOSE_NATURE.'</option>';
                            foreach(array_keys($_SESSION['mail_natures']) as $nature)
                            {
                                $frm_str .='<option value="'.functions::xssafe($nature).'"  with_reference = "'.$_SESSION['mail_natures_attribute'][$nature].'"';
                                if(isset($data['nature_id']) && $data['nature_id'] == $nature) {
                                    $frm_str .='selected="selected"';
                                } else if ($data['nature_id'] == "" && $_SESSION['default_mail_nature'] == $nature) {
                                    $frm_str .='selected="selected"';
                                }
                                $frm_str .='>'.functions::xssafe($_SESSION['mail_natures'][$nature]).'</option>';
                            }
                        $frm_str .= '</select></td>';
                        $frm_str .= '<td><span class="red_asterisk" id="nature_mandatory" style="display:inline;vertical-align:text-top"><i class="fa fa-star"></i></span></td>';
                  $frm_str .= '</tr>';

                /*** Recommande ***/
                $frm_str .= '<tr id="reference_number_tr" style="display:none;">';
                    $frm_str .= '<td ><label for="reference_number" class="form_title" >' ._MONITORING_NUMBER.'</label></td>';
                    $frm_str .= '<td>&nbsp;</td>';
                    $frm_str .= '<td><input type="text" name="reference_number" id="reference_number"';
                        if (isset($data['reference_number']) && $data['reference_number'] <> '') {
                            $frm_str .= 'value = "'.$data['reference_number'].'"';
                        }
                    $frm_str .= '/></td>';
                $frm_str .= '</tr>'; 

                /*** Subject ***/
                  $frm_str .= '<tr id="subject_tr" style="display:'.$display_value.';">';
                        $frm_str .='<td class="indexing_label"><label for="subject" class="form_title" >'._SUBJECT.'</label></td>';
                        $frm_str .='<td>&nbsp;</td>';
                        $frm_str .='<td class="indexing_field"><textarea name="subject" id="subject" rows="4" onchange="clear_error(\'frm_error_'.$id_action.'\');" >';
                          if(isset($data['subject']) && !empty($data['subject']))
                           {
                              $frm_str .= $data['subject'];
                            }
                         $frm_str .= '</textarea></td>';
                         $frm_str .= '<td><span class="red_asterisk" id="subject_mandatory" style="display:inline;vertical-align:text-top"><i class="fa fa-star"></i></span></td>';
                  $frm_str .= '</tr>';
                
                /*** Entities : department + diffusion list ***/
                if ($core_tools->is_module_loaded('entities')) {
                    $_SESSION['validStep'] = "ok";
                    $frm_str .= '<tr id="department_tr" style="display:'.$display_value.';">';
                    $frm_str .='<td class="indexing_label"><label for="department" class="form_title" id="label_dep_dest" style="display:inline;" >'._DEPARTMENT_DEST.'</label><label for="department" class="form_title" id="label_dep_exp" style="display:none;" >'._DEPARTMENT_EXP.'</label><label for="department" '
                        . 'class="form_title" id="label_dep_owner" style="display:none;" >'
                        . _DEPARTMENT_OWNER . '</label></td>';
                    $frm_str .='<td>&nbsp;</td>';
                    $frm_str .='<td class="indexing_field"><select name="destination" id="destination" onchange="clear_error(\'frm_error_'.$id_action.'\');'.$func_load_listdiff_by_entity.'">';
                    $frm_str .='<option value="">'._CHOOSE_DEPARTMENT.'</option>';
                    
                    $countAllEntities = count($allEntitiesTree);
                    for ($cptEntities = 0;$cptEntities < $countAllEntities;$cptEntities++) {
                        if (!$allEntitiesTree[$cptEntities]['KEYWORD']) {
                            $frm_str .= '<option data-object_type="entity_id" value="' . $allEntitiesTree[$cptEntities]['ID'] . '"';
                             if (isset($data['destination'])&& $data['destination'] == $allEntitiesTree[$cptEntities]['ID']) {
                                $frm_str .=' selected="selected"';
                            }
                            if ($allEntitiesTree[$cptEntities]['DISABLED']) {
                                $frm_str .= ' disabled="disabled" class="disabled_entity"';
                            } else {
                                 //$frm_str .= ' style="font-weight:bold;"';
                            }
                            $frm_str .=  '>' 
                                .  functions::show_string($allEntitiesTree[$cptEntities]['SHORT_LABEL']) 
                                . '</option>';
                        }
                    }
                    $frm_str .='</select></td>';
                    $frm_str .= '<td><span class="red_asterisk" id="destination_mandatory" style="display:inline;vertical-align:text-top"><i class="fa fa-star"></i></span></td>';
                    $frm_str .= '</tr>';
                    $frm_str .= '<tr id="diff_list_tr" style="display:none;">';
                    $frm_str .= '<td colspan="3">';
                    $frm_str .= '<div id="diff_list_div" class="scroll_div" style="width:420px; max-width: 420px;"></div>';
                    $frm_str .= '</td>';
                    $frm_str .= '</tr>';
                }
                
                /*** Process limit date ***/
        $frm_str .= '<tr id="process_limit_date_use_tr" style="display:'.$display_value.';">';
            $frm_str .='<td class="indexing_label"><label for="process_limit_date_use" class="form_title" >'._PROCESS_LIMIT_DATE_USE.'</label></td>';
            $frm_str .='<td>&nbsp;</td>';
            $frm_str .='<td class="indexing_field"><input type="radio"  class="check" name="process_limit_date_use" id="process_limit_date_use_yes" value="yes" ';
            if($data['process_limit_date_use'] == true)
            {
                $frm_str .=' checked="checked"';
            }
            $frm_str .=' onclick="clear_error(\'frm_error_'.$id_action.'\');activate_process_date(true, \''.$display_value.'\');" />'._YES.'<input type="radio" name="process_limit_date_use"  class="check"  id="process_limit_date_use_no" value="no" onclick="clear_error(\'frm_error_'.$id_action.'\');activate_process_date(false, \''.$display_value.'\');" ';
            if(!isset($data['process_limit_date_use']))
            {
                $frm_str .=' checked="checked"';
            }
            $frm_str .='/>'._NO.'</td>';
            $frm_str .= '<td><span class="red_asterisk" id="process_limit_date_use_mandatory" style="display:inline;vertical-align:text-top"><i class="fa fa-star"></i></span></td>';
        $frm_str .= '</tr>';
        $frm_str .= '<tr id="process_limit_date_tr" style="display:'.$display_value.';">';
            $frm_str .='<td class="indexing_label"><label for="process_limit_date" class="form_title" >'._PROCESS_LIMIT_DATE.'</label></td>';
            $frm_str .='<td>&nbsp;</td>';
            $frm_str .='<td class="indexing_field"><input name="process_limit_date" type="text" id="process_limit_date"  onclick="clear_error(\'frm_error_'.$id_action.'\');showCalender(this);" value="';
            if(isset($data['process_limit_date'])&& !empty($data['process_limit_date']))
            {
                $frm_str .= $data['process_limit_date'];
            }
            $frm_str .='"/></td>';
            $frm_str .= '<td><span class="red_asterisk" id="process_limit_date_mandatory" style="display:inline;vertical-align:text-top"><i class="fa fa-star"></i></span></td>';
        $frm_str .= '</tr>';
        
        /*** Status ***/
        // Select statuses from groupbasket
        $statuses = array();
        $db = new Database();

        /* Basket of ABS users */
        if($_SESSION['current_basket']['abs_basket']=='1'){
            $query="SELECT group_id FROM usergroup_content WHERE user_id= ? AND primary_group='Y'";
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
            . " where basket_id= ? and group_id = ? and action_id = ?";
        $stmt = $db->query($query, array($owner_basket_id, $owner_usr_grp, $id_action));

        if($stmt->rowCount() > 0) {
            while($status = $stmt->fetchObject()) {
                $statuses[] = array(
                    'ID' => $status->status_id,
                    'LABEL' => functions::show_string($status->label_status)
                );
            }
        }
        $view = $sec->retrieve_view_from_coll_id($coll_id);
        if(count($statuses) > 0) {
            //load current status
            $stmt = $db->query("SELECT status FROM " 
                . $view 
                . " WHERE res_id = ?", array($res_id));
            $statusObj = $stmt->fetchObject();
            $current_status = $statusObj->status;
            if ($current_status <> '') {
                $stmt = $db->query("SELECT label_status FROM " . STATUS_TABLE 
                    . " WHERE id = ?", array($current_status));
                $statusObjLabel = $stmt->fetchObject();
                $current_status_label = $statusObjLabel->label_status;
            }
            $frm_str .= '<tr id="status" style="display:' . $display_value . ';">';
            $frm_str .= '<td><label for="status" class="form_title" >' . _STATUS
                    . '</label></td>';
            $frm_str .= '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
            $frm_str .= '<td class="indexing_field"><select name="status" '
                    . 'id="status" onchange="clear_error(\'frm_error_' . $id_action
                    . '\');">';
            if ($current_status <> '') {
                $frm_str .= '<option value="' . $current_status . '">' . _CHOOSE_CURRENT_STATUS 
                    . ' : ' . $current_status_label . '(' . $current_status . ')</option>';
            } else {
                $frm_str .= '<option value="">' . _CHOOSE_CURRENT_STATUS . ')</option>';
            }
            for ($i = 0; $i < count($statuses); $i ++) {
                $frm_str .= '<option value="' . functions::xssafe($statuses[$i]['ID']) . '" ';
                if ($statuses[$i]['ID'] == 'NEW') {
                    $frm_str .= 'selected="selected"';
                }
                $frm_str .= '>' . functions::xssafe($statuses[$i]['LABEL']) . '</option>';
            }
            $frm_str .= '</select></td><td><span class="red_asterisk" id="market_mandatory" '
                . 'style="display:inline;"><i class="fa fa-star"></i></span>&nbsp;</td>';
            $frm_str .= '</tr>';
        }
        
        $frm_str .= '</table>';
        
        $frm_str .= '</div>';
        $frm_str .= '</div>';
        
        /*** CUSTOM INDEXES ***/
        $frm_str .= '<div id="comp_indexes" style="display:block;">';
        $frm_str .= '</div>';
        
        /*** Complementary fields ***/
        $frm_str .= '<hr />';
        
        $frm_str .= '<h4 onclick="new Effect.toggle(\'complementary_fields\', \'blind\', {delay:0.2});'
            . 'whatIsTheDivStatus(\'complementary_fields\', \'divStatus_complementary_fields\');" '
            . 'class="categorie" style="width:90%;" onmouseover="this.style.cursor=\'pointer\';">';
        $frm_str .= ' <span id="divStatus_complementary_fields" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span>&nbsp;' 
            . _OPT_INDEXES;
        $frm_str .= '</h4>';
        $frm_str .= '<div id="complementary_fields"  style="display:none">';
        $frm_str .= '<div>';
        
        $frm_str .= '<table width="100%" align="center" border="0" '
            . 'id="indexing_fields" style="display:table;">';

        /*** Chrono number ***/
        $stmt = $db->query("SELECT alt_identifier FROM " 
            . $view 
            . " WHERE res_id = ?", array($res_id));
        $resChrono = $stmt->fetchObject();
        $chrono_number = explode('/', $resChrono->alt_identifier);
        $chrono_number = $chrono_number[1];
        $frm_str .= '<tr id="chrono_number_tr" style="display:'.$display_value.';">';
            $frm_str .='<td><label for="chrono_number" class="form_title" >'._CHRONO_NUMBER.'</label></td>';
            $frm_str .='<td>&nbsp;</td>';
            $frm_str .='<td class="indexing_field"><input type="text" name="chrono_number" value="' 
                . functions::xssafe($chrono_number) . '" id="chrono_number" onchange="clear_error(\'frm_error_'.$id_action.'\');"/></td>';
            $frm_str .='<td><span class="red_asterisk" id="chrono_number_mandatory" style="display:inline;"><i class="fa fa-star"></i></span>&nbsp;</td>';
        $frm_str .= '</tr>';
        
        /*** Folder  ***/
        if ($core_tools->is_module_loaded('folder') && ($core->test_service('associate_folder', 'folder',false) == 1)) {
            require_once 'modules' . DIRECTORY_SEPARATOR . 'folder' . DIRECTORY_SEPARATOR
            . 'class' . DIRECTORY_SEPARATOR . 'class_modules_tools.php';
            $folders = new folder();
            $folder_info = $folders->get_folders_tree('1');
            $folder = '';
            $folder_id = '';
            if(isset($data['folder'])&& !empty($data['folder']))
            {
                $folder = $data['folder'];
                $folder_id = str_replace(')', '', substr($folder, strrpos($folder,'(')+1));
            }
            $frm_str .= '<tr id="folder_tr" style="display:'.$display_value.';">';
                $frm_str .= '<td><label for="folder" class="form_title" >' . _FOLDER_OR_SUBFOLDER . '</label></td>';
                $frm_str .= '<td class="indexing_field" style="text-align:right;"><select id="folder" name="folder" onchange="displayFatherFolder(\'folder\')"><option value="">Sélectionnez un dossier</option>';

                foreach ($folder_info as $key => $value) {
                    if($value['folders_system_id'] == $folder_id){
                        $frm_str .= '<option selected="selected" value="'.$value['folders_system_id'].'" parent="' . $value['parent_id'] . '">'.$value['folder_name'].'</option>';
                    }else{
                        $frm_str .= '<option value="'.$value['folders_system_id'].'" parent="' . $value['parent_id'] . '">'.$value['folder_name'].'</option>';
                    }
                    
                }
                $frm_str .= '</select>';
                $frm_str .= '</td>';
                $frm_str .= '<td>';
                if ($core->test_service('create_folder', 'folder', false) == 1) {
                    $frm_str .=' <a href="#" id="create_folder" title="' . _CREATE_FOLDER
                            . '" onclick="new Effect.toggle(\'create_folder_div\', '
                            . '\'blind\', {delay:0.2});return false;" '
                            . 'style="display:inline;" ><i class="fa fa-plus-circle" title="' 
                            . _CREATE_FOLDER . '"></i></a>';
                }
                $frm_str .= '</td>';
            $frm_str .= '</tr>';
            $frm_str .= '<tr id="parentFolderTr" style="display: none"><td>&nbsp;</td><td colspan="2"><span id="parentFolderSpan" style="font-style: italic;font-size: 10px"></span></td></tr>';
        }

        /*** thesaurus ***/
        if ($core->is_module_loaded('thesaurus') && $core->test_service('thesaurus_view', 'thesaurus', false)) {
            require_once 'modules' . DIRECTORY_SEPARATOR . 'thesaurus'
                            . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
                            . 'class_modules_tools.php';
            $thesaurus = new thesaurus();

            $thesaurusListRes = array();

            $thesaurusListRes = $thesaurus->getThesaursusListRes($res_id);


            $frm_str .= '<tr id="thesaurus_tr" style="display:' . $display_value . ';">';
                $frm_str .= '<td colspan="3" style="width:100%;">' . _THESAURUS . '</td>';
            $frm_str .= '</tr>';

            $frm_str .= '<tr id="thesaurus_tr" style="display:' . $display_value . ';">';
                $frm_str .= '<td colspan="2" class="indexing_field" id="thesaurus_field" style="text-align:left;"><select multiple="multiple" style="width:100%;" id="thesaurus" data-placeholder=" "';

                if (!$core->test_service('add_thesaurus_to_res', 'thesaurus', false)) {
                    $frm_str .= 'disabled="disabled"';
                }
                $frm_str .= '>';
                if(!empty($thesaurusListRes)){
                    foreach ($thesaurusListRes as $key => $value) {

                        $frm_str .= '<option title="'.functions::show_string($value['LABEL']).'" data-object_type="thesaurus_id" id="thesaurus_'.$value['ID'].'"  value="' . $value['ID'] . '"';
                            $frm_str .= ' selected="selected"'; 
                        $frm_str .= '>' 
                            .  functions::show_string($value['LABEL']) 
                            . '</option>';

                    }
                }
                $frm_str .= '</optgroup>';
                $frm_str .= '</select></td><td style="width:5%;"><i onclick="lauch_thesaurus_list(this);" class="fa fa-search" title="parcourir le thésaurus" aria-hidden="true" style="cursor:pointer;"></i></td>';
            $frm_str .= '</tr>';
            $frm_str .= '<style>#thesaurus_chosen{width:100% !important;}#thesaurus_chosen .chosen-drop{display:none;}</style>';

            /*****************/
        }

        if ($core_tools->is_module_loaded('tags') &&
                    ($core_tools->test_service('tag_view', 'tags',false) == 1)) {
            $tags = get_value_fields($formValues, 'tag_userform');
            $tags_list = explode('__', $tags);
            include_once("modules".DIRECTORY_SEPARATOR."tags".DIRECTORY_SEPARATOR
            ."templates/validate_mail/index.php");
        }

        $frm_str .= '</table>';
        
        $frm_str .= '</div>';
        $frm_str .= '</div>';


        /*** Actions ***/
        $frm_str .= '<hr width="90%" align="center"/>';
        $frm_str .= '<p align="center">';
        
        //GET ACTION LIST BY AJAX REQUEST
        $frm_str .= '<span id="actionSpan"></span>';

        $frm_str .= '<input type="button" name="send" id="send" value="'._VALIDATE.'" class="button" onclick="if(document.getElementById(\'contactcheck\').value!=\'success\'){if (confirm(\''. _CONTACT_CHECK .'\n\nContinuer ?\')){new Ajax.Request(\'' . $_SESSION['config']['businessappurl'] . 'index.php?display=true&dir=actions&page=docLocker\',{ method:\'post\', parameters: {\'AJAX_CALL\': true, \'unlock\': true, \'res_id\': ' . $res_id . '} });valid_action_form( \'index_file\', \''.$path_manage_action.'\', \''. $id_action.'\', \''.$res_id.'\', \''.$table.'\', \''.$module.'\', \''.$coll_id.'\', \''.$mode.'\');}}else{new Ajax.Request(\'' . $_SESSION['config']['businessappurl'] . 'index.php?display=true&dir=actions&page=docLocker\',{ method:\'post\', parameters: {\'AJAX_CALL\': true, \'unlock\': true, \'res_id\': ' . $res_id . '} });valid_action_form( \'index_file\', \''.$path_manage_action.'\', \''. $id_action.'\', \''.$res_id.'\', \''.$table.'\', \''.$module.'\', \''.$coll_id.'\', \''.$mode.'\');}"/> ';
        $frm_str .= '<input name="close" id="close" type="button" value="'._CANCEL.'" class="button" onclick="new Ajax.Request(\'' . $_SESSION['config']['businessappurl'] . 'index.php?display=true&dir=actions&page=docLocker\',{ method:\'post\', parameters: {\'AJAX_CALL\': true, \'unlock\': true, \'res_id\': ' . $res_id . '}, onSuccess: function(answer){window.location.href=\'' . $_SESSION['config']['businessappurl']. 'index.php?page=view_baskets&module=basket&baskets=' . $_SESSION['current_basket']['id'] . '\';} });$(\'baskets\').style.visibility=\'visible\';destroyModal(\'modal_'.$id_action.'\');reinit();"/>';
        $frm_str .= '</p>';
    $frm_str .= '</form>';
    $frm_str .= '</div>';
    $frm_str .= '</div>';
        $frm_str .= '</div>';

    $frm_str .= '<div id="validright">';
        
        /*** TOOLBAR ***/
        $frm_str .= '<div class="block" align="center" style="height:20px;width=95%;">';
        
        $frm_str .= '<table width="95%" cellpadding="0" cellspacing="0">';
        $frm_str .= '<tr align="center">';
        
        // HISTORY
        if ($core_tools->test_service('view_doc_history', 'apps', false)) {
            $frm_str .= '<td>';
            $frm_str .= '<span onclick="hideOtherDiv(\'history_div\');new Effect.toggle(\'history_div\', \'blind\', {delay:0.2});'
                . 'whatIsTheDivStatus(\'history_div\', \'divStatus_history_div\');return false;" '
                . 'onmouseover="this.style.cursor=\'pointer\';" class="categorie" style="width:90%;">';
            $frm_str .= '<span id="divStatus_history_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span>';
           $frm_str .= '<b>&nbsp;<i class="fa fa-line-chart fa-2x" title="'. _DOC_HISTORY.'"></i><sup><span style="display:none;"></span></sup></b>';
            $frm_str .= '</b></span>';
            $frm_str .= '</td>';
        }

        //NOTE
        if ($core_tools->is_module_loaded('notes')) {
            $frm_str .= '<td>';
            require_once 'modules/notes/class/class_modules_tools.php';
            $notes_tools    = new notes();
            //Count notes
            $nbr_notes = $notes_tools->countUserNotes($res_id, $coll_id);
            if ($nbr_notes == 0){
                $class = 'nbResZero';
                $style2 = 'display:none';
                $style = 'opacity:0.5;';
            }
            else{
                $class = 'nbRes';
                $style = '';
                $style2 = '';
            }
            $frm_str .= '<span onclick="hideOtherDiv(\'notes_div\');new Effect.toggle(\'notes_div\', \'blind\', {delay:0.2});'
                . 'whatIsTheDivStatus(\'notes_div\', \'divStatus_notes_div\');return false;" '
                . 'onmouseover="this.style.cursor=\'pointer\';" class="categorie" style="width:90%;">';
            $frm_str .= '<span id="divStatus_notes_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span><b>'
                . '&nbsp;<i class="fa fa-pencil fa-2x" style="'.$style.'" title="'._NOTES.'"></i> <sup><span id="nb_note" style="'.$style2.'" class="'.$class.'">'.$nbr_notes.'</span></sup>';
            $frm_str .= '</b></span>';
            $frm_str .= '</td>';
        }
        
        //ATTACHMENTS
        if ($core_tools->is_module_loaded('attachments')) {
            $frm_str .= '<td>';
            $db = new Database;
            $stmt = $db->query("SELECT res_id FROM "
                . $_SESSION['tablename']['attach_res_attachments']
                . " WHERE status <> 'DEL'  and attachment_type <> 'converted_pdf' and attachment_type <> 'print_folder' and res_id_master = ? and coll_id = ? and (status <> 'TMP' or (typist = ? and status = 'TMP'))", array($res_id, $coll_id, $_SESSION['user']['UserId']));
            if ($stmt->rowCount() > 0) {
                $nb_attach = $stmt->rowCount();
                $style = '';
                $style2 = '';
            }else{
                $style = 'opacity:0.5;';
                $style2 = 'display:none;';
            }
            if ($answer <> '') {
                $answer .= ': ';
            }
            $frm_str .= '<span onclick="hideOtherDiv(\'list_answers_div\');new Effect.toggle(\'list_answers_div\', \'blind\', {delay:0.2});'
                . 'whatIsTheDivStatus(\'list_answers_div\', \'divStatus_done_answers_div\');return false;" '
                . 'onmouseover="this.style.cursor=\'pointer\';" class="categorie" style="width:90%;">';
            $frm_str .= '<span id="divStatus_done_answers_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span><b>'
                . '&nbsp;<i class="fa fa-paperclip fa-2x" style="'.$style.'" title="'._PJ.'"></i> <sup><span class="nbRes" style="'.$style2.'" id="nb_attach">'. $nb_attach . '</span></sup>';
            $frm_str .= '</b></span>';
            $frm_str .= '</td>';
        }
              
        if ($core_tools->is_module_loaded('entities')) {
            $frm_str .= '<td>';
            $frm_str .= '<span class="diff_list_history" style="width: 90%; cursor: pointer;" onmouseover="this.style.cursor=\'pointer\';" onclick="hideOtherDiv(\'diff_list_history_div\');new Effect.toggle(\'diff_list_history_div\', \'appear\', {delay:0.2});whatIsTheDivStatus(\'diff_list_history_div\', \'divStatus_diff_list_history_div\');return false;">';
                $frm_str .= '<span id="divStatus_diff_list_history_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span>';
                $frm_str .= '<b>&nbsp;<i class="fa fa-history fa-2x" title="'. _DIFF_LIST_HISTORY.'"></i><sup><span style="display:none;"></span></sup></b>';
            $frm_str .= '</span>';
            $frm_str .= '</td>';
        }
        
        //LINKS
        $frm_str .= '<td>';
        require_once('core/class/LinkController.php');
        $Class_LinkController = new LinkController();
        $nbLink = $Class_LinkController->nbDirectLink(
            $res_id,
            $coll_id,
            'all'
        );
        if ($nbLink == 0) {
            $class = 'nbResZero';
            $style2 = 'display:none';
            $style = 'opacity:0.5;';
        }else{
            $class = 'nbRes';
            $style = '';
            $style2 = '';
        }
        $frm_str .= '<span id="to_link" onclick="hideOtherDiv(\'links_div\');new Effect.toggle(\'links_div\', \'blind\', {delay:0.2});'
            . 'whatIsTheDivStatus(\'links_div\', \'divStatus_links_div\');return false;" '
            . 'onmouseover="this.style.cursor=\'pointer\';" class="categorie" style="width:90%;">';
        $frm_str .= '<span id="divStatus_links_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span><b>'
              . '&nbsp;<i class="fa fa-link fa-2x" style="'.$style.'" title="'._LINK_TAB.'"></i> <sup><span id="nbLinks" style="'.$style2.'" class="'.$class.'">' 
            . $nbLink . '</span></sup>';
        $frm_str .= '</b></span>';
        $frm_str .= '</td>';


        //CASES
        if ($core_tools->is_module_loaded('cases')) {
            require_once('modules/cases/class/class_modules_tools.php');
            $cases = new cases();
            $case_id = $cases->get_case_id($res_id);
            if ($case_id <> false) {
                $case_properties = $cases->get_case_info($case_id);
                $style = '';
            } else {
                $case_properties = array();
                $style = 'opacity:0.5;';
            }

            $frm_str .= '<td>';
            $frm_str .= '<span onclick="new Effect.toggle(\'cases_div\', \'blind\', {delay:0.2});'
                . 'whatIsTheDivStatus(\'cases_div\', \'divStatus_cases_div\');hideOtherDiv(\'cases_div\');return false;" '
                . 'onmouseover="this.style.cursor=\'pointer\';" class="categorie" style="width:90%;">';
            $frm_str .= ' <span id="divStatus_cases_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span>&nbsp;<b><i class="fa fa-suitcase fa-2x" style="'.$style.'" title="'. _CASE.'"></i><sup><span style="display:none;"></span></sup></b>';
            $frm_str .= '<span class="lb1-details">&nbsp;</span>';
            $frm_str .= '</span>';
            $frm_str .= '<br>';
            $frm_str .= '</td>';
        }
        
        //END TOOLBAR
        $frm_str .= '</table>';
        $frm_str .= '</div>';
        
        //FRAME FOR TOOLS
        
    /**** Contact form start *******/
    if ($core->test_admin('my_contacts', 'apps', false)) {
        $frm_str .= '<div id="create_contact_div" style="display:none">';
            $frm_str .= '<iframe width="100%" height="450" src="' . $_SESSION['config']['businessappurl']
                    . 'index.php?display=false&dir=my_contacts&page=create_contact_iframe" name="contact_iframe" id="contact_iframe"'
                    . ' scrolling="auto" frameborder="0" style="display:block;">'
                    . '</iframe>';
        $frm_str .= '</div>';
    }
    /**** Contact form end *******/

        /**** Folder form start *******/
    if ($core->test_service('create_folder', 'folder', false) == 1) {
        $frm_str .= '<div id="create_folder_div" style="display:none">';
            $frm_str .= '<iframe width="100%" height="450" src="' . $_SESSION['config']['businessappurl']
                    . 'index.php?page=create_folder_form_iframe&module=folder&display=false" name="folder_iframe" id="folder_iframe"'
                    . ' scrolling="auto" frameborder="0" style="display:block;">'
                    . '</iframe>';
        $frm_str .= '</div>';
    }
    /**** Folder form end *******/

    /**** Contact info start *******/
    $frm_str .= '<div id="info_contact_div" style="display:none">';
        $frm_str .= '<iframe width="100%" height="800" name="info_contact_iframe" id="info_contact_iframe"'
                . ' scrolling="auto" frameborder="0" style="display:block;">'
                . '</iframe>';
    $frm_str .= '</div>';
    /**** Contact info end *******/ 
    
        $frm_str .= '<script type="text/javascript">show_admin_contacts(true);</script>';
        
        //HISTORY FRAME
        $frm_str .= '<div class="block" id="history_div" style="display:none">';
        $frm_str .= '<div class="ref-unit">';
        $frm_str .= '<center><h2 onclick="new Effect.toggle(\'history_div\', \'blind\', {delay:0.2});';
        $frm_str .= 'whatIsTheDivStatus(\'history_div\', \'divStatus_history_div\');';
        $frm_str .= 'return false;" onmouseover="this.style.cursor=\'pointer\';">' . _WF. '</h2></center>';
        $frm_str .= '<iframe src="'
            . $_SESSION['config']['businessappurl']
            . 'index.php?display=true&dir=indexing_searching&page=document_workflow_history&id='
            . $res_id . '&coll_id=' . $coll_id . '&load&size=medium" '
            . 'name="hist_wf_doc_process" id="hist_wf_doc_process" width="100%" height="500px" align="center" '
            . 'scrolling="auto" frameborder="0" style="height: 500px; max-height: 500px; overflow: scroll;"></iframe>';

            $frm_str .= '<br/>';

            $frm_str .= '<span style="cursor: pointer;" onmouseover="this.style.cursor=\'pointer\';" onclick="new Effect.toggle(\'hist_doc_process\', \'blind\', {delay:0.2});whatIsTheDivStatus(\'hist_doc_process\', \'divStatus_all_history_div\');return false;">'
            . '<span id="divStatus_all_history_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span>'
            . '<b>&nbsp;'. _ALL_HISTORY.'</b>'
        . '</span>';
        $frm_str .= '<iframe src="'
            . $_SESSION['config']['businessappurl']
            . 'index.php?display=true&dir=indexing_searching&page=document_history&id='
            . $res_id . '&coll_id=' . $coll_id . '&load&size=medium" '
            . 'name="hist_doc_process" id="hist_doc_process" width="100%" height="690px" align="center" '
            . 'scrolling="auto" frameborder="0" style="height: 690px; max-height: 690px; overflow: scroll;display:none;" ></iframe>';
        $frm_str .= '</div>';
        $frm_str .= '<hr />';
        $frm_str .= '</div>';

        //CASES FRAME
        if ($core_tools->is_module_loaded('cases')) {
            if (!isset($case_properties['case_id'])) {
                $case_properties = array();
                $case_properties['case_id'] = '';
                $case_properties['case_label'] = '';
                $case_properties['case_description'] = '';
            }
            $frm_str .= '<div id="cases_div" style="display:none" onmouseover="this.style.cursor=\'pointer\';">';
            $frm_str .= '<div class="block" style="margin-top:-2px;">';
            $frm_str .= '<center><h2 onclick="new Effect.toggle(\'cases_div\', \'blind\', {delay:0.2});';
            $frm_str .= 'whatIsTheDivStatus(\'cases_div\', \'divStatus_cases_div\');';
            $frm_str .= 'return false;">' . _CASE . '</h2></center>';
            $frm_str .= '<form name="cases" method="post" id="cases" action="#" class="forms addforms2" style="text-align:center;">';
            $frm_str .= '<table width="98%" align="center" border="0">';
            $frm_str .= '<tr>';
            $frm_str .= '<td><label for="case_id" class="case_label" >' . _CASE . '</label></td>';
            $frm_str .= '<td>&nbsp;</td>';
            $frm_str .= '<td><input type="text" readonly="readonly" class="readonly" name="case_id" id="case_id" value="'
                . $case_properties['case_id'] . '"  onblur=""/>';
            $frm_str .= '</td>';
            $frm_str .= '</tr>';
            $frm_str .= '<tr>';
            $frm_str .= '<td><label for="case_label" class="case_label" >' . _CASE_LABEL . '</label></td>';
            $frm_str .= '<td>&nbsp;</td>';
            $frm_str .= '<td><input type="text" readonly="readonly" class="readonly" name="case_label" '
                . 'id="case_label" onblur="" value="'.$case_properties['case_label'].'" />';
            $frm_str .= '</td>';
            $frm_str .= '</tr>';
            $frm_str .= '<tr>';
            $frm_str .= '<td><label for="case_description" class="case_description" >'
                . _CASE_DESCRIPTION . '</label></td>';
            $frm_str .= '<td>&nbsp;</td>';
            $frm_str .= '<td><input type="text" readonly="readonly" class="readonly" '
                . 'name="case_description" id="case_description" onblur="" value="'
                . $case_properties['case_description'].'" />';
            $frm_str .= '</td>';
            $frm_str .= '</tr>';
            $frm_str .= '<tr>';
            if ($core_tools->test_service('join_res_case_in_process', 'cases',false) == 1) {
                $frm_str .= '<td colspan="3"> <input type="button" class="button" name="search_case" '
                    . 'id="search_case" value="';
                if ($case_properties['case_id']<>'') {
                    $frm_str .= _MODIFY_CASE;
                } else {
                    $frm_str .= _JOIN_CASE;
                }
                $frm_str .= '" onclick="window.open(\'' . $_SESSION['config']['businessappurl']
                    . 'index.php?display=true&module=cases&page=search_adv_for_cases'
                    . '&searched_item=res_id_in_process&searched_value=' . $_SESSION['doc_id']
                    . '\',\'\', \'scrollbars=yes,menubar=no,toolbar=no,resizable=yes,'
                    . 'status=no,width=1020,height=710\');"/></td>';
            }
            $frm_str .= '</tr>';
            $frm_str .= '</table>';
            $frm_str .= '</form>';
            $frm_str .= '<br>';
            $frm_str .= '</div>';
            $frm_str .= '<hr />';
            $frm_str .= '</div>';
        }
        //NOTES
        if ($core_tools->is_module_loaded('notes')) {
            //Iframe notes
            $frm_str .= '<div id="notes_div" style="display:none;">';
            $frm_str .= '<div class="ref-unit block" style="margin-top:-2px;">';
            $frm_str .= '<center><h2 onclick="new Effect.toggle(\'notes_div\', \'blind\', {delay:0.2});';
            $frm_str .= 'whatIsTheDivStatus(\'notes_div\', \'divStatus_notes_div\');';
            $frm_str .= 'return false;" onmouseover="this.style.cursor=\'pointer\';">' . _NOTES. '</h2></center>';
            $frm_str .= '<iframe name="list_notes_doc" id="list_notes_doc" src="'
                . $_SESSION['config']['businessappurl']
                . 'index.php?display=true&module=notes&page=notes&identifier='
                . $res_id . '&origin=document&coll_id=' . $coll_id . '&load&size=medium"'
                . ' frameborder="0" width="100%" height="650px"></iframe>';
            $frm_str .= '</div>';
            $frm_str .= '<hr />';
            $frm_str .= '</div>';
        }
        
        //ATTACHMENTS
        if ($core_tools->is_module_loaded('attachments')) {
            require 'modules/templates/class/templates_controler.php';
            $templatesControler = new templates_controler();
            $templates = array();
            $templates = $templatesControler->getAllTemplatesForProcess($data['destination']);
            
			$_SESSION['destination_entity'] = $data['destination'];
            $frm_str .= '<div id="list_answers_div" class="block" style="display:none;margin-top:-2px;">';

            $frm_str .= '<div>';
                $frm_str .= '<div id="processframe" name="processframe">';
                    $frm_str .= '<center><h2 onclick="new Effect.toggle(\'list_answers_div\', \'blind\', {delay:0.2});';
                    $frm_str .= 'new Effect.toggle(\'done_answers_div\', \'blind\', {delay:0.2});';
                    $frm_str .= 'whatIsTheDivStatus(\'done_answers_div\', \'divStatus_done_answers_div\');';
                    $frm_str .= 'return false;">' . _PJ . ', ' . _ATTACHEMENTS . '</h2></center>';
                    $req = new Database;
                    $stmt =$req->query("SELECT res_id FROM ".$_SESSION['tablename']['attach_res_attachments']
                        . " WHERE (status = 'A_TRA' or status = 'TRA') and res_id_master = ? and coll_id = ?",
                        array($res_id, $coll_id));
                    //$req->show();
                    $nb_attach = 0;
                    if ($stmt->rowCount() > 0) {
                        $nb_attach = $stmt->rowCount();
                    }
                    $frm_str .= '<div class="ref-unit">';
                    $frm_str .= '<center>';
                    if ($core_tools->is_module_loaded('templates')) {

                        $frm_str .= '<input type="button" name="attach" id="attach" class="button" value="'

                            . _CREATE_PJ

                            .'" onclick="showAttachmentsForm(\'' . $_SESSION['config']['businessappurl']
                            . 'index.php?display=true&module=attachments&page=attachments_content\',\'98%\',\'auto\')" />';
                    }
                    $frm_str .= '</center><iframe name="list_attach" id="list_attach" src="'
                    . $_SESSION['config']['businessappurl']
                    . 'index.php?display=true&module=attachments&page=frame_list_attachments&load&attach_type_exclude=converted_pdf,print_folder" '
                    . 'frameborder="0" width="100%" height="600px"></iframe>';
                    $frm_str .= '</div>';
                $frm_str .= '</div>';
                //$frm_str .= '<hr class="hr_process"/>';
            $frm_str .= '</div>';
            $frm_str .= '<hr />';
        $frm_str .= '</div>';
    }

        if ($core_tools->is_module_loaded('entities')) {
            $frm_str .= '<div id="diff_list_history_div" class="block" style="display:none;margin-top:-2px;">';
                $frm_str .= '<center><h2>';
                $frm_str .= _DIFF_LIST_HISTORY;
                $frm_str .= '</h2></center>';
                $s_id = $res_id;
                $return_mode = true;
                require_once('modules/entities/difflist_history_display.php');

            $frm_str .= '</div>';            
        }
        
        //LINKS
        $frm_str .= '<div id="links_div" style="display:none" onmouseover="this.style.cursor=\'pointer\';">';
        $frm_str .= '<div class="block" style="text-align: left;margin-top:-2px;">';
        $frm_str .= '<h2>';
        $frm_str .= '<center>' . _LINK_TAB . '</center>';
        $frm_str .= '</h2>';
        $frm_str .= '<div id="loadLinks">';
        $nbLinkDesc = $Class_LinkController->nbDirectLink(
            $res_id,
            $coll_id,
            'desc'
        );
        if ($nbLinkDesc > 0) {
            $frm_str .= '<i class="fa fa-long-arrow-right fa-2x"></i>';
            $frm_str .= $Class_LinkController->formatMap(
                $Class_LinkController->getMap(
                    $res_id,
                    $coll_id,
                    'desc'
                ),
                'desc'
            );
            $frm_str .= '<br />';
        }
        $nbLinkAsc = $Class_LinkController->nbDirectLink(
            $res_id,
            $coll_id,
            'asc'
        );
        if ($nbLinkAsc > 0) {
            $frm_str .= '<i class="fa fa-long-arrow-left fa-2x"></i>';
            $frm_str .= $Class_LinkController->formatMap(
                $Class_LinkController->getMap(
                    $res_id,
                    $coll_id,
                    'asc'
                ),
                'asc'
            );
            $frm_str .= '<br />';
        }
        $frm_str .= '</div>';
        if ($core_tools->test_service('add_links', 'apps', false)) {
            include 'apps/'.$_SESSION['config']['app_id'].'/add_links.php';
            $frm_str .= $Links;
        }
        $frm_str .= '</div>';
        $frm_str .= '<hr />';
        $frm_str .= '</div>';
        
        //DOCUMENT VIEWER
        $path_file = get_file_path($res_id, $coll_id);
		
        $frm_str .= '<iframe src="'.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=view_resource_controler&id='
            . $res_id.'&coll_id='.$coll_id.'" name="viewframevalid" id="viewframevalid"  scrolling="auto" frameborder="0" style="width:100% !important;" onmouseover="this.focus()"></iframe>';
            
        //END RIGHT DIV
        $frm_str .= '</div>';

        /*** Extra javascript ***/
        $frm_str .= '<script type="text/javascript">displayFatherFolder(\'folder\');resize_frame_process("modal_'.$id_action.'", "viewframevalid", true, true);resize_frame_process("modal_'.$id_action.'", "hist_doc", true, false);window.scrollTo(0,0);';

    	// DocLocker constantly	
    	$frm_str .= 'setInterval("new Ajax.Request(\'' . $_SESSION['config']['businessappurl'] . 'index.php?display=true&dir=actions&page=docLocker\',{ method:\'post\', parameters: {\'AJAX_CALL\': true, \'lock\': true, \'res_id\': ' . $res_id . '} });", 50000);';
            
        $frm_str .='init_validation(\''.$_SESSION['config']['businessappurl'] 
            . 'index.php?display=true&dir=indexing_searching&page=autocomplete_contacts\', \''
            . $display_value.'\', \'' 
            . $_SESSION['config']['businessappurl'] 
            . 'index.php?display=true&dir=indexing_searching&page=change_category\',  \''
            . $_SESSION['config']['businessappurl']
            . 'index.php?display=true&page=get_content_js\');$(\'baskets\').style.visibility=\'hidden\';var item = $(\'valid_div\'); if(item){item.style.display=\'block\';}';
        $frm_str .='var type_id = $(\'type_id\');change_category_actions(\'' 
            . $_SESSION['config']['businessappurl'] 
            . 'index.php?display=true&dir=indexing_searching&page=change_category_actions'
            . '&resId=' . $res_id . '&collId=' . $coll_id . '\');';
        $frm_str .='if(type_id){change_doctype(type_id.options[type_id.selectedIndex].value, \''.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=change_doctype\', \''._ERROR_DOCTYPE.'\', \''.$id_action.'\', \''.$_SESSION['config']['businessappurl'].'index.php?display=true&page=get_content_js\' , \''.$display_value.'\', '.$res_id.', \''. $coll_id.'\', true);}';
        if($data['process_limit_date'] == null){
        
            $frm_str .="activate_process_date(false, '".$display_value."');";
        }
        if($core_tools->is_module_loaded('entities') )
        {
            if($_SESSION['current_basket']['difflist_type'] == 'entity_id'){
                $frm_str .='change_entity(\''.$data['destination'].'\', \''.$_SESSION['config']['businessappurl'].'index.php?display=true&module=entities&page=load_listinstance'.'\',\'diff_list_div\', \'indexing\', \''.$display_value.'\'';
                if(!$load_listmodel)
                {
                    $frm_str .= ',\'false\',$(\'category_id\').value);';
                }else{
                    $frm_str .= ',\'true\',$(\'category_id\').value);';
                }
                
            }else if($_SESSION['current_basket']['difflist_type'] == 'type_id'){
                if(!$load_listmodel){
                    $frm_str .='change_entity(\''.$data['destination'].'\', \''.$_SESSION['config']['businessappurl'].'index.php?display=true&module=entities&page=load_listinstance'.'\',\'diff_list_div\', \'indexing\', \''.$display_value.'\'';
                    $frm_str .= ',\'false\',$(\'category_id\').value);';     
                }else{
                    $frm_str .= 'load_listmodel('.$target_model.', \'diff_list_div\', \'indexing\', $(\'category_id\').value);';
                    $frm_str .= '$(\'diff_list_tr\').style.display=\''.$display_value.'\';';
                }  
            }else{
                $frm_str .='change_entity(\''.$data['destination'].'\', \''.$_SESSION['config']['businessappurl'].'index.php?display=true&module=entities&page=load_listinstance'.'\',\'diff_list_div\', \'indexing\', \''.$display_value.'\'';
                if(!$load_listmodel)
                {
                    $frm_str .= ',\'false\',$(\'category_id\').value);';
                }else{
                    $frm_str .= ',\'true\',$(\'category_id\').value);';
                }
            }
        }
        if ($data['confidentiality'] == 'Y') {
            $frm_str .='$(\'confidential\').checked=true;';
        } else if ($data['confidentiality'] == 'N') {           
            $frm_str .='$(\'no_confidential\').checked=true;';
        }

        if ($data['type_contact'] == 'internal') {
            $frm_str .='$(\'type_contact_internal\').checked=true;';
        } else if ($data['type_contact'] == 'external') {           
            $frm_str .='$(\'type_contact_external\').checked=true;';
        }
        //Path to actual script
        $path_to_script = $_SESSION['config']['businessappurl']
        ."index.php?display=true&dir=indexing_searching&page=contact_check&coll_id=".$collId;
        //check functions on load page
        if ($data['type_contact'] != 'internal') {
            $frm_str.="check_date_exp('".$path_to_script."','".$path_check_date_link."');";
        }
        $frm_str .='launch_autocompleter_contacts_v2(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=autocomplete_contacts\', \'\', \'\', \'\', \'contactid\', \'addressid\');update_contact_type_session(\''
            .$_SESSION['config']['businessappurl']
            .'index.php?display=true&dir=indexing_searching&page=autocomplete_contacts_prepare_multi\');';
        $frm_str .= 'affiche_reference();';

        $frm_str .= '$$(\'select\').each(function(element) { new Chosen(element,{width: "226px", disable_search_threshold: 10,search_contains: true}); });';
        $frm_str .='</script>';
        $frm_str .= '<style>#destination_chosen .chosen-drop{width:400px;}#folder_chosen .chosen-drop{width:400px;}</style>';


	// À la fin de la methode d’ouverture de la modale
	$docLocker->lock();
	
    return addslashes($frm_str);
}

/**
 * Checks the action form
 *
 * @param $form_id String Identifier of the form to check
 * @param $values Array Values of the form
 * @return Bool true if no error, false otherwise
 **/
function check_form($form_id,$values)
{
    $_SESSION['action_error'] = '';
    if(count($values) < 1 || empty($form_id))
    {
        $_SESSION['action_error'] =  _FORM_ERROR;
        return false;
    }
    else
    {
        $attach = get_value_fields($values, 'attach');
        $coll_id = get_value_fields($values, 'coll_id');
        
        if ($attach) {
            $idDoc = get_value_fields($values, 'res_id');
            if (! $idDoc || empty($idDoc)) {
                $_SESSION['action_error'] .= _LINK_REFERENCE . '<br/>';
            }
            if (! empty($_SESSION['action_error'])) {
                return false;
            }
        }
        
        $cat_id = get_value_fields($values, 'category_id');

        if($cat_id == false)
        {
            $_SESSION['action_error'] = _CATEGORY.' '._IS_EMPTY;
            return false;
        }
        $no_error = process_category_check($cat_id, $values);
        return $no_error;
    }
}


/**
 * Checks the values of the action form for a given category
 *
 * @param $cat_id String Category identifier
 * @param $values Array Values of the form to check
 * @return Bool true if no error, false otherwise
 **/
function process_category_check($cat_id, $values)
{
    $core = new core_tools();
    // If No category : Error
    if(!isset($_ENV['categories'][$cat_id]))
    {
        $_SESSION['action_error'] = _CATEGORY.' '._UNKNOWN.': '.$cat_id;
        return false;
    }

    // Simple cases
    for($i=0; $i<count($values); $i++)
    {
        if($_ENV['categories'][$cat_id][$values[$i]['ID']]['mandatory'] == true  && (empty($values[$i]['VALUE']) )) //&& ($values[$i]['VALUE'] == 0 && $_ENV['categories'][$cat_id][$values[$i]['ID']]['type_form'] <> 'integer')
        {
            $_SESSION['action_error'] = $_ENV['categories'][$cat_id][$values[$i]['ID']]['label'].' '._IS_EMPTY;
            return false;
        }
        if($_ENV['categories'][$cat_id][$values[$i]['ID']]['type_form'] == 'date' && !empty($values[$i]['VALUE']) && preg_match($_ENV['date_pattern'],$values[$i]['VALUE'])== 0)
        {
            $_SESSION['action_error'] = $_ENV['categories'][$cat_id][$values[$i]['ID']]['label']." "._WRONG_FORMAT."";
            return false;
        }
        if($_ENV['categories'][$cat_id][$values[$i]['ID']]['type_form'] == 'integer' && (!empty($values[$i]['VALUE']) || $values[$i]['VALUE'] == 0) && preg_match("/^[0-9]*$/",$values[$i]['VALUE'])== 0)
        {
            $_SESSION['action_error'] = $_ENV['categories'][$cat_id][$values[$i]['ID']]['label']." "._WRONG_FORMAT."";
            return false;
        }
        if($_ENV['categories'][$cat_id][$values[$i]['ID']]['type_form'] == 'radio' && !empty($values[$i]['VALUE']) && !in_array($values[$i]['VALUE'], $_ENV['categories'][$cat_id][$values[$i]['ID']]['values']))
        {
            $_SESSION['action_error'] = $_ENV['categories'][$cat_id][$values[$i]['ID']]['label']." "._WRONG_FORMAT."";
            return false;
        }
    }

    ///// Checks the complementary indexes depending on the doctype
    require_once('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_types.php');
    $type = new types();
    $type_id =  get_value_fields($values, 'type_id');
    /*if($type_id == 1)
    {
        $_SESSION['action_error'] = _TYPE." "._WRONG_FORMAT."";
        return false;
    }*/
    $coll_id =  get_value_fields($values, 'coll_id');
    $indexes = $type->get_indexes( $type_id,$coll_id, 'minimal');
    $val_indexes = array();
    for($i=0; $i<count($indexes);$i++)
    {
        $val_indexes[$indexes[$i]] =  get_value_fields($values, $indexes[$i]);
    }
    $test_type = $type->check_indexes($type_id, $coll_id,$val_indexes );
    if(!$test_type)
    {
        $_SESSION['action_error'] .= $_SESSION['error'];
        $_SESSION['error'] = '';
        return false;
    }

    ///////////////////////// Other cases
    //doc date
    /*$doc_date = get_value_fields($values, 'doc_date');
    $admission_date = get_value_fields($values, 'admission_date');
    if ($admission_date < $doc_date)
    {
		$_SESSION['action_error'] = "La date du courrier doit être antérieure à la date d'arrivée du courrier ";
		return false;
	}*/
	
    // Process limit Date
    $_SESSION['store_process_limit_date'] = "";
    if(isset($_ENV['categories'][$cat_id]['other_cases']['process_limit_date']))
    {
        $process_limit_date_use_yes = get_value_fields($values, 'process_limit_date_use_yes');
        $process_limit_date_use_no = get_value_fields($values, 'process_limit_date_use_no');
        if($process_limit_date_use_yes == 'yes')
        {
            $_SESSION['store_process_limit_date'] = "ok";
            $process_limit_date = get_value_fields($values, 'process_limit_date');
            if(trim($process_limit_date) == "" || preg_match($_ENV['date_pattern'], $process_limit_date)== 0)
            {
                $_SESSION['action_error'] = $_ENV['categories'][$cat_id]['other_cases']['process_limit_date']['label']." "._WRONG_FORMAT."";
                return false;
            }
        }
        elseif($process_limit_date_use_no == 'no')
        {
            $_SESSION['store_process_limit_date'] = "ko";
        }

		$process_limit_date = new datetime($process_limit_date);
       $process_limit_date = date_add($process_limit_date,date_interval_create_from_date_string('23 hours + 59 minutes + 59 seconds'));


    }

    if (isset($_ENV['categories'][$cat_id]['priority'])) {
        $priority = get_value_fields(
            $values, 'priority'
        );

        if ($priority === '') {
            $_SESSION['action_error'] = $_ENV['categories'][$cat_id]['priority']['label']
                . " " . _MANDATORY;
            return false;
        }
    }

    // Contact
    if(isset($_ENV['categories'][$cat_id]['other_cases']['contact'])){
        $contact_type = get_value_fields($values, 'type_contact_external');
        if(!$contact_type) {
            $contact_type = get_value_fields($values, 'type_contact_internal');
        }
		if (!$contact_type) {
            $contact_type = get_value_fields($values, 'type_multi_contact_external');
        }
        if(!$contact_type){
            $_SESSION['action_error'] = $_ENV['categories'][$cat_id]['other_cases']['type_contact']['label']." "._MANDATORY."";
            return false;
        }
        $contact = get_value_fields($values, 'contactid');
		$nb_multi_contact = count($_SESSION['adresses']['to']);

        $contact_field = get_value_fields($values, 'contact');

        if ($contact_field <> "" && empty($contact)) {
            $_SESSION['action_error'] = $_ENV['categories'][$cat_id]['other_cases']['contact']['label']
                . ' ' . _WRONG_FORMAT . ". " . _USE_AUTOCOMPLETION;
            return false;
        }
        
        if($_ENV['categories'][$cat_id]['other_cases']['contact']['mandatory'] == true)
        {
            if((empty($contact) && $contact_type != 'multi_external') || ($nb_multi_contact == 0 && $contact_type == 'multi_external'))
            {
                $_SESSION['action_error'] = $_ENV['categories'][$cat_id]['other_cases']['contact']['label'].' '._IS_EMPTY;
                return false;
            }
        }
        // if(!empty($contact) )
        // {
        //     if($contact_type == 'external' && !preg_match('/\(\d+\)$/', trim($contact)))
        //     {
        //         $_SESSION['action_error'] = $_ENV['categories'][$cat_id]['other_cases']['contact']['label']." "._WRONG_FORMAT.".<br/>".' '._USE_AUTOCOMPLETION;
        //         return false;
        //     }
        //     elseif($contact_type == 'internal' && preg_match('/\([A-Za-Z0-9-_ ]+\)$/', $contact) == 0)
        //     elseif($contact_type == 'internal' && preg_match('/\((.|\s|\d|\h|\w)+\)$/i', $contact) == 0)
        //     if($contact_type == 'internal' && preg_match('/\((.|\s|\d|\h|\w)+\)$/i', $contact) == 0)
        //     {
        //         $_SESSION['action_error'] = $_ENV['categories'][$cat_id]['other_cases']['contact']['label']." "._WRONG_FORMAT.".<br/>"._USE_AUTOCOMPLETION;
        //         return false;
        //     }
        // }
    }

    if($core->is_module_loaded('entities'))
    {
        // Diffusion list
        if(isset($_ENV['categories'][$cat_id]['other_cases']['diff_list']) && $_ENV['categories'][$cat_id]['other_cases']['diff_list']['mandatory'] == true)
        {
            if(empty($_SESSION['indexing']['diff_list']['dest']['users'][0]['user_id']) || !isset($_SESSION['indexing']['diff_list']['dest']['users'][0]['user_id']))
            {
                $_SESSION['action_error'] = $_ENV['categories'][$cat_id]['other_cases']['diff_list']['label']." "._MANDATORY."";
                return false;
            }
        }
    }
    if($core->is_module_loaded('folder'))
    {
        $db = new Database();
        $folder_id = '';

        $folder_id = get_value_fields($values, 'folder');
        if(isset($_ENV['categories'][$cat_id]['other_cases']['folder']) && $_ENV['categories'][$cat_id]['other_cases']['folder']['mandatory'] == true)
        {
            if(empty($folder))
            {
                $_SESSION['action_error'] = $_ENV['categories'][$cat_id]['other_cases']['folder']['label'].' '._IS_EMPTY;
                return false;
            }
        }
        /*if(!empty($folder) )
        {
            if(!preg_match('/\([0-9]+\)$/', $folder))
            {
                $_SESSION['action_error'] = $_ENV['categories'][$cat_id]['other_cases']['folder']['label']." "._WRONG_FORMAT."";
                return false;
            }
            $folder_id = str_replace(')', '', substr($folder, strrpos($folder,'(')+1));
            $stmt = $db->query("SELECT folders_system_id FROM ".$_SESSION['tablename']['fold_folders']." WHERE folders_system_id = ?", array($folder_id));
            if($stmt->rowCount() == 0)
            {
                $_SESSION['action_error'] = _FOLDER.' '.$folder_id.' '._UNKNOWN;
                return false;
            }
        }*/
        if(!empty($type_id ) &&  !empty($folder_id))
        {
            $foldertype_id = '';

            $stmt = $db->query("SELECT foldertype_id FROM ".$_SESSION['tablename']['fold_folders']." WHERE folders_system_id = ?", array($folder_id));
            $res = $stmt->fetchObject();
            $foldertype_id = $res->foldertype_id;
            $stmt = $db->query("SELECT fdl.foldertype_id FROM "
                .$_SESSION['tablename']['fold_foldertypes_doctypes_level1']." fdl, "
                .$_SESSION['tablename']['doctypes']." d WHERE d.doctypes_first_level_id = fdl.doctypes_first_level_id and fdl.foldertype_id = ? and d.type_id = ".$type_id
                , array($foldertype_id));
            if($stmt->rowCount() == 0)
            {
                $_SESSION['action_error'] .= _ERROR_COMPATIBILITY_FOLDER;
                return false;
            }
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
    for($i=0; $i<count($values);$i++)
    {
        if($values[$i]['ID'] == $field)
        {
            return  $values[$i]['VALUE'];
        }
    }
    return false;
}

/**
 * Action of the form : update the database
 *
 * @param $arr_id Array Contains the res_id of the document to validate
 * @param $history String Log the action in history table or not
 * @param $id_action String Action identifier
 * @param $label_action String Action label
 * @param $status String  Not used here
 * @param $coll_id String Collection identifier
 * @param $table String Table
 * @param $values_form String Values of the form to load
 **/
function manage_form($arr_id, $history, $id_action, $label_action, $status,  $coll_id, $table, $values_form )
{
//var_dump("manage_form");
    if(empty($values_form) || count($arr_id) < 1 || empty($coll_id))
    {
        $_SESSION['action_error'] = _ERROR_MANAGE_FORM_ARGS;
        return false;
    }

    require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
    require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
    require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_resource.php");
    $db = new Database();
    $sec = new security();
    $core = new core_tools();
    $resource = new resource();
    $table = $sec->retrieve_table_from_coll($coll_id);
    $ind_coll = $sec->get_ind_collection($coll_id);
    $table_ext = $_SESSION['collections'][$ind_coll]['extensions'][0];
    $res_id = $arr_id[0];

    $attach = get_value_fields($values_form, 'attach');

    if ($core->is_module_loaded('tags')) {
        $tags_list = get_value_fields($values_form, 'tag_userform');
        $tags_list = explode('__', $tags_list);

        include_once("modules".DIRECTORY_SEPARATOR."tags"
        .DIRECTORY_SEPARATOR."tags_update.php");
    }

    //thesaurus
    if ($core->is_module_loaded('thesaurus')) {
        require_once 'modules' . DIRECTORY_SEPARATOR . 'thesaurus'
                    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
                    . 'class_modules_tools.php';
        $thesaurus = new thesaurus();
        $thesaurusList = get_value_fields($values_form, 'thesaurus');
	   $thesaurus->updateResThesaurusList($thesaurusList,$res_id);
    }

    $query_ext = "update ".$table_ext." set ";
    $query_res = "update ".$table." set ";
    $arrayPDOres = array();
    $arrayPDOext = array();

    $cat_id = get_value_fields($values_form, 'category_id');

    $query_ext .= " category_id = ? " ;
    $arrayPDOext = array_merge($arrayPDOext, array($cat_id));
    //$query_res .= " status = 'NEW' " ;

    // Specific indexes : values from the form
    // Simple cases
    for($i=0; $i<count($values_form); $i++)
    {
        if($_ENV['categories'][$cat_id][$values_form[$i]['ID']]['type_field'] == 'integer' && $_ENV['categories'][$cat_id][$values_form[$i]['ID']]['table'] <> 'none')
        {
            if($_ENV['categories'][$cat_id][$values_form[$i]['ID']]['table'] == 'res')
            {
                $query_res .= ", ".$values_form[$i]['ID']." = ? ";
                $arrayPDOres = array_merge($arrayPDOres, array($values_form[$i]['VALUE']));
            }
            else if($_ENV['categories'][$cat_id][$values_form[$i]['ID']]['table'] == 'coll_ext')
            {
                $query_ext .= ", ".$values_form[$i]['ID']." = ? ";
                $arrayPDOext = array_merge($arrayPDOext, array($values_form[$i]['VALUE']));
            }
        }
        else if($_ENV['categories'][$cat_id][$values_form[$i]['ID']]['type_field'] == 'string' && $_ENV['categories'][$cat_id][$values_form[$i]['ID']]['table'] <> 'none')
        {
            if($_ENV['categories'][$cat_id][$values_form[$i]['ID']]['table'] == 'res')
            {
                $query_res .= ", ".$values_form[$i]['ID']." = ?";
                $arrayPDOres = array_merge($arrayPDOres, array($values_form[$i]['VALUE']));
            }
            else if($_ENV['categories'][$cat_id][$values_form[$i]['ID']]['table'] == 'coll_ext')
            {
                $query_ext .= ", ".$values_form[$i]['ID']." = ?";
                $arrayPDOext = array_merge($arrayPDOext, array($values_form[$i]['VALUE']));
            }
        }
        else if($_ENV['categories'][$cat_id][$values_form[$i]['ID']]['type_field'] == 'date' && $_ENV['categories'][$cat_id][$values_form[$i]['ID']]['table'] <> 'none')
        {
            if($_ENV['categories'][$cat_id][$values_form[$i]['ID']]['table'] == 'res')
            {
                $query_res .= ", ".$values_form[$i]['ID']." = ?";
                $arrayPDOres = array_merge($arrayPDOres, array($values_form[$i]['VALUE']));
            }
            else if($_ENV['categories'][$cat_id][$values_form[$i]['ID']]['table'] == 'coll_ext')
            {
                $query_ext .= ", ".$values_form[$i]['ID']." = ?";
                $arrayPDOext = array_merge($arrayPDOext, array($values_form[$i]['VALUE']));
            }
        }
    }
    $status_id = get_value_fields($values_form, 'status');
    if (empty($status_id) || $status_id === "") {
        $status_id = 'BAD';
    } else {
        $query_res .= ", status = ?";
        $arrayPDOres = array_merge($arrayPDOres, array($status_id));
    }

    ///////////////////////// Other cases
    require_once('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_types.php');
    $type = new types();
    $type->inits_opt_indexes($coll_id, $res_id);
    $type_id =  get_value_fields($values_form, 'type_id');
    $indexes = $type->get_indexes( $type_id,$coll_id, 'minimal');
    $val_indexes = array();
    for($i=0; $i<count($indexes);$i++)
    {
        $val_indexes[$indexes[$i]] =  get_value_fields($values_form, $indexes[$i]);
    }
    $query_res .=  $type->get_sql_update($type_id, $coll_id, $val_indexes);

    // Confidentiality
    $confidentiality_yes = get_value_fields($values_form, 'confidential');

    if (!empty($confidentiality_yes)) {
        $query_res .= ", confidentiality = ?";
        $arrayPDOres = array_merge($arrayPDOres, array($confidentiality_yes));
    } else {
        $confidentiality_no = get_value_fields($values_form, 'no_confidential');
        $query_res .= ", confidentiality = ?";
        $arrayPDOres = array_merge($arrayPDOres, array($confidentiality_no));
    }

    // Process limit Date
    if(isset($_ENV['categories'][$cat_id]['other_cases']['process_limit_date']))
    {
        $process_limit_date = get_value_fields($values_form, 'process_limit_date');

        $process_limit_date = new datetime($process_limit_date);
        $process_limit_date = date_add($process_limit_date,date_interval_create_from_date_string('23 hours + 59 minutes + 59 seconds'));
        $process_limit_date = (array) $process_limit_date; 

        if($_ENV['categories'][$cat_id]['other_cases']['process_limit_date']['table'] == 'res')
        {
            $query_res .= ", process_limit_date = '".$db->format_date_db($process_limit_date['date'],'true','','true')."'";
        }
        else if($_ENV['categories'][$cat_id]['other_cases']['process_limit_date']['table'] == 'coll_ext')
        {
            if($_SESSION['store_process_limit_date'] == "ok")
            {
                $query_ext .= ", process_limit_date = '".$db->format_date_db($process_limit_date['date'],'true','','true')."'";
            } else {
				$query_ext .= ", process_limit_date = null";
			}
            $_SESSION['store_process_limit_date'] = "";
        }
    }

    // Contact
    if(isset($_ENV['categories'][$cat_id]['other_cases']['contact']))
    {
        $contact = get_value_fields($values_form, 'contact');
        $contact_type = get_value_fields(
			$values_form, 'type_contact_external');
			
        if(!$contact_type){
            $contact_type = get_value_fields(
				$values_form, 'type_contact_internal');
        }
		
		if (!$contact_type) {
            $contact_type = get_value_fields(
                $values_form, 'type_multi_contact_external'
            );
        }
		
		
		$nb_multi_contact = count($_SESSION['adresses']['to']);

		$db->query("DELETE FROM contacts_res where res_id = ?", array($res_id));
		
		$db->query("UPDATE ". $table_ext 
			. " SET exp_user_id = NULL, dest_user_id = NULL, exp_contact_id = NULL, dest_contact_id = NULL where res_id = ?",  
			 array($res_id));
		if($nb_multi_contact > 0 && $contact_type == 'multi_external'){
		
			for($icontact = 0; $icontact<$nb_multi_contact; $icontact++){
			
				$db->query("INSERT INTO contacts_res (coll_id, res_id, contact_id, address_id) VALUES (?, ?, ?, ?)",
                    array($coll_id, $res_id, $_SESSION['adresses']['contactid'][$icontact], $_SESSION['adresses']['addressid'][$icontact]));

			}
			
			$query_ext .= ", is_multicontacts = 'Y'";
		
		}
		else{
            $contact_id = get_value_fields(
                $values_form, 'contactid'
            );	
            if(!ctype_digit($contactId)){
                $contactType = 'internal';
            }else{
                $contactType = 'external';
            }	
			// $contact_id = str_replace(')', '', substr($contact, strrpos($contact,'(')+1));
			if($contact_type == 'internal')
			{
				if($cat_id == 'incoming' || $cat_id == 'internal'  || $cat_id == 'ged_doc')
				{
					$query_ext .= ", exp_user_id = ?";
                    $arrayPDOext = array_merge($arrayPDOext, array($contact_id));
				}
				else if($cat_id == 'outgoing')
				{
					$query_ext .= ", dest_user_id = ?";
                    $arrayPDOext = array_merge($arrayPDOext, array($contact_id));
				}
				$db->query("DELETE FROM contacts_res where res_id = ?", array($res_id));
				$query_ext .= ", is_multicontacts = ''";
			}
			elseif($contact_type == 'external')
			{
				if($cat_id == 'incoming' || $cat_id == 'ged_doc')
				{
					$query_ext .= ", exp_contact_id = ?";
                    $arrayPDOext = array_merge($arrayPDOext, array($contact_id));
				}
				else if($cat_id == 'outgoing' || $cat_id == 'internal')
				{
					$query_ext .= ", dest_contact_id = ?";
                    $arrayPDOext = array_merge($arrayPDOext, array($contact_id));
				}
                $addressId = get_value_fields(
                    $values_form, 'addressid'
                );
                $query_ext .= ", address_id = ?";
                $arrayPDOext = array_merge($arrayPDOext, array($addressId));

				$db->query("DELETE FROM contacts_res where res_id = ?", array($res_id));
				$query_ext .= ", is_multicontacts = ''";
			}
		}
    }
    
    if($core->is_module_loaded('folder') && ($core->test_service('associate_folder', 'folder',false) == 1))
    {
        $folder_id = '';
        $stmt = $db->query("SELECT folders_system_id FROM ".$table ." WHERE res_id = ?", array($res_id));
        $res = $stmt->fetchObject();
        $old_folder_id = $res->folders_system_id;

        $folder_id = get_value_fields($values_form, 'folder');

        if(!empty($folder_id)) {
            $query_res .= ", folders_system_id = ?";
            $arrayPDOres = array_merge($arrayPDOres, array($folder_id));
        } else if(empty($folder_id) && !empty($old_folder_id)) {
            $query_res .= ", folders_system_id = NULL";
        }

        if($folder_id <> $old_folder_id && $_SESSION['history']['folderup'])
        {
            require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
            $hist = new history();
            $hist->add($_SESSION['tablename']['fold_folders'], $folder_id, "UP", 'folderup', _DOC_NUM.$res_id._ADDED_TO_FOLDER, $_SESSION['config']['databasetype'],'apps');
            if(isset($old_folder_id) && !empty($old_folder_id))
            {
                $hist->add($_SESSION['tablename']['fold_folders'], $old_folder_id, "UP", 'folderup', _DOC_NUM.$res_id._DELETED_FROM_FOLDER, $_SESSION['config']['databasetype'],'apps');
            }
        }
    }

    if($core->is_module_loaded('entities'))
    {
        // Diffusion list
        $load_list_diff = false;
        if(isset($_ENV['categories'][$cat_id]['other_cases']['diff_list']) )
        {
            if(!empty($_SESSION['indexing']['diff_list']['dest']['users'][0]['user_id']) && isset($_SESSION['indexing']['diff_list']['dest']['users'][0]['user_id']))
            {
                $query_res .= ", dest_user = ?";
                $arrayPDOres = array_merge($arrayPDOres, array($_SESSION['indexing']['diff_list']['dest']['users'][0]['user_id']));
            }
            $load_list_diff = true;
        }
    }    
    
    $query_res = preg_replace('/set ,/', 'set ', $query_res);
    //$query_res = substr($query_res, strpos($query_string, ','));

    $arrayPDOres = array_merge($arrayPDOres, array($res_id));
    $db->query($query_res." where res_id = ? ", $arrayPDOres);

    $arrayPDOext = array_merge($arrayPDOext, array($res_id));
    $db->query($query_ext." where res_id = ?", $arrayPDOext);

    if($core->is_module_loaded('entities'))
    {
        if($load_list_diff)
        {
            require_once('modules'.DIRECTORY_SEPARATOR.'entities'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_manage_listdiff.php');
            $diff_list = new diffusion_list();


            $params = array(
                'mode'=> 'listinstance', 
                'table' => $_SESSION['tablename']['ent_listinstance'], 
                'coll_id' => $coll_id, 
                'res_id' => $res_id, 
                'user_id' => $_SESSION['user']['UserId'],
                'fromQualif' => true
            );
            $diff_list->load_list_db($_SESSION['indexing']['diff_list'], $params);
        }
    }
    
    
    //Create chrono number
    //######
    if ($cat_id == 'outgoing') {
        $queryChrono = "SELECT alt_identifier FROM " . $table_ext 
            . " WHERE res_id = ?";
        $stmt = $db->query($queryChrono, array($res_id));
        $resultChrono = $stmt->fetchObject();
        if ($resultChrono->alt_identifier == '' OR $resultChrono->alt_identifier == NULL) {
        require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'class_chrono.php';
        $cTypeId = get_value_fields($values_form, 'type_id');
        $cEntity = get_value_fields($values_form, 'destination');
        $cChronoOut = get_value_fields($values_form, 'chrono_number');
        $chronoX = new chrono();
        $myVars = array(
            'entity_id' => $cEntity,
            'type_id' => $cTypeId,
            'category_id' => $cat_id,
        );
        $myForm = array(
            'chrono_out' => $cChronoOut,
        );
        $myChrono = $chronoX->generate_chrono($cat_id, $myVars, $myForm);
        if ($myChrono <> '' && $cChronoOut == '') {
            $db->query("UPDATE " . $table_ext ." SET alt_identifier = ? WHERE res_id = ? ",
                array($myChrono, $res_id));
        }
    }
    } elseif ($cat_id == 'incoming' || $cat_id == 'internal' ) {
        $queryChrono = "SELECT alt_identifier FROM " . $table_ext 
            . " WHERE res_id = ?";
        $stmt = $db->query($queryChrono, array($res_id));
        $resultChrono = $stmt->fetchObject();
        if ($resultChrono->alt_identifier == '' OR $resultChrono->alt_identifier == NULL) {
            require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'class_chrono.php';
            $cTypeId = get_value_fields($values_form, 'type_id');
            $cEntity = get_value_fields($values_form, 'destination');
            $cChronoOut = get_value_fields($values_form, 'chrono_number');
            $chronoX = new chrono();
            $myVars = array(
                'entity_id' => $cEntity,
                'type_id' => $cTypeId,
                'category_id' => $cat_id,                
                'res_id' => $res_id
            );
            //print_r($myVars);
            $myForm = array(
                'chrono_out' => $cChronoOut,
            );
            $myChrono = $chronoX->generate_chrono($cat_id, $myVars, $myForm);
            if ($myChrono <> '') {
                $db->query("UPDATE " . $table_ext ." SET alt_identifier = ? where res_id = ?", array($myChrono, $res_id));
            }
        }
    }

    //$_SESSION['indexing'] = array();
    unset($_SESSION['upfile']);

    //$_SESSION['indexation'] = true;
    return array('result' => $res_id.'#', 'history_msg' => '');
}
