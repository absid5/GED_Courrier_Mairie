<?php

/*
*   Copyright 2008-2015 Maarch
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
* @brief Action : Process a document
*
* Open a modal box to displays the process form, make the form checks and loads the result in database.
* Used by the core (manage_action.php page).
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/

/**
* $confirm  bool false
*/
$confirm = false;
/**
* $etapes  array Contains 2 etaps : form and status (order matters)
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

require_once "modules" . DIRECTORY_SEPARATOR . "visa" . DIRECTORY_SEPARATOR
			. "class" . DIRECTORY_SEPARATOR
			. "class_modules_tools.php";
			
			
include('apps/' . $_SESSION['config']['app_id']. '/definition_mail_categories.php');


/**
 * Gets the folder data for a given document
 *
 * @param $coll_id string Collection identifier
 * @param $res_id string Resource identifier
 * @return Array Folder data (folder + subfolder)
 **/
function get_folder_data($coll_id, $res_id)
{
    require_once('core/class/class_security.php');
    $sec = new security();
    $view = $sec->retrieve_view_from_coll_id($coll_id);
    if (empty($view)) {
        $view = $sec->retrieve_table_from_coll($coll_id);
    }
    $db = new Database();
    $folder = '';
    $stmt = $db->query("SELECT folders_system_id, folder_id, folder_name, fold_parent_id, fold_subject, folder_level FROM "
        . $view . " WHERE res_id = ?", array($res_id));

    if ($stmt->rowCount() == 1) {
        $res = $stmt->fetchObject();
        if (!empty($res->folders_system_id)) {
            $folder = functions::xssafe($res->folder_id).', '.functions::xssafe($res->folder_name)
                .' ('.functions::xssafe($res->folders_system_id).')';
        }
    }

    return $folder;
}


/**
 * Returns the indexing form text
 *
 * @param $values Array Contains the res_id of the document to process
 * @param $path_manage_action String Path to the PHP file called in Ajax
 * @param $id_action String Action identifier
 * @param $table String Table
 * @param $module String Origin of the action
 * @param $coll_id String Collection identifier
 * @param $mode String Action mode 'mass' or 'page'
 * @return String The form content text
 **/
function get_form_txt($values, $path_manage_action,  $id_action, $table, $module, $coll_id, $mode)
{
    //print_r($_SESSION['current_basket']);
    if (preg_match("/MSIE 6.0/", $_SERVER["HTTP_USER_AGENT"])) {
        $browser_ie = true;
        $display_value = 'block';
    } elseif (
        preg_match('/msie/i', $_SERVER["HTTP_USER_AGENT"])
        && !preg_match('/opera/i', $_SERVER["HTTP_USER_AGENT"])
    ) {
        $browser_ie = true;
        $display_value = 'block';
    } else {
        $browser_ie = false;
        $display_value = 'table-row';
    }
    $_SESSION['req'] = "action";
    $res_id = $values[0];
	
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
            $docLockerscriptError .= 'alert("'._DOC_LOCKER_RES_ID.''
                .$res_id.''._DOC_LOCKER_USER.' ' . functions::xssafe($_SESSION['userLock']) . '");';
        $docLockerscriptError .= '</script>';
        return $docLockerscriptError;
    }
	
    $frm_str = '';
    require_once('core/class/class_security.php');
    require_once('modules/basket/class/class_modules_tools.php');
    require_once('core/class/class_request.php');
    require_once('apps/' . $_SESSION['config']['app_id'] . '/class/class_types.php');
    require_once('apps/' . $_SESSION['config']['app_id'] . '/class/class_indexing_searching_app.php');
    require_once('apps/' . $_SESSION['config']['app_id'] . '/class/class_chrono.php');
    $type = new types();
    $sec =new security();
    $core_tools =new core_tools();
    $doctypes = $type->getArrayTypes($coll_id);
    $b = new basket();
    $is = new indexing_searching_app();
    $cr = new chrono();
    $db = new Database();
    $_SESSION['save_list']['fromProcess'] = "true";
    $_SESSION['count_view_baskets']=0;
    $data = array();
    $params_data = array('show_folder' => true);
    $data = get_general_data($coll_id, $res_id, 'full', $params_data);
    $process_data = $is->get_process_data($coll_id, $res_id);
    $chrono_number = $cr->get_chrono_number($res_id, $sec->retrieve_view_from_table($table));
    $_SESSION['doc_id'] = $res_id;
    $indexes = array();
    if (isset($data['type_id'])) {
        $indexes = $type->get_indexes($data['type_id']['value'], $coll_id);
        $fields = 'res_id';
        foreach (array_keys($indexes) as $key) {
            $fields .= ','.$key;
        }
        $stmt = $db->query("SELECT ".$fields." FROM ".$table." WHERE res_id = ?", array($res_id));
        $values_fields = $stmt->fetchObject();
        //print_r($indexes);
    }
    if ($core_tools->is_module_loaded('entities')) {
        require_once('modules/entities/class/class_manage_listdiff.php');
        $listdiff = new diffusion_list();
        $roles = $listdiff->list_difflist_roles();
        $_SESSION['process']['diff_list'] = $listdiff->get_listinstance($res_id, false, $coll_id);
        $_SESSION['process']['difflist_type'] = $listdiff->get_difflist_type($_SESSION['process']['diff_list']['object_type']);
    }
    //  to activate locking decomment these lines
    /*if ($b->reserve_doc($_SESSION['user']['UserId'], $res_id, $coll_id) == false) {
        $frm_str = '<div>';
        $frm_str .= '<h1 class="tit" id="action_title">'._DOC_NUM." ".$res_id ;
                    $frm_str .= '</h1>';
            $frm_str .= '<div>'._DOC_ALREADY_RSV.'</div>';
            $frm_str .= '<div><input type="button" name="close" id="close" value="'
            * . _CLOSE_WINDOW.'" class="button" onclick="javascript:destroyModal(\'modal_'.$id_action.'\');reinit();"/></div>';
            $frm_str .= '</div>';

    } else {
    */

//Load multicontacts
	$query = "SELECT c.firstname, c.lastname, c.society, c.contact_id, c.ca_id  ";
			$query .= "FROM view_contacts c, contacts_res cres  ";
			$query .= "WHERE cres.coll_id = 'letterbox_coll' AND cres.res_id = ? AND cast (c.contact_id as varchar) = cres.contact_id AND c.ca_id = cres.address_id ";
			$query .= "GROUP BY c.firstname, c.lastname, c.society, c.contact_id, c.ca_id";
			
	$stmt = $db->query($query, array($res_id));
	$nbContacts = 0;
	$frameContacts = "";
	$frameContacts = "{";
	while($res = $stmt->fetchObject()){
		$nbContacts = $nbContacts + 1;
		$firstname = str_replace("'","\'", $res->firstname);
		$firstname = str_replace('"'," ", $firstname);
		$lastname = str_replace("'","\'", $res->lastname);
		$lastname = str_replace('"'," ", $lastname);
		$society = str_replace("'","\'", $res->society);
		$society = str_replace('"'," ", $society);
		$frameContacts .= "'contact ".$nbContacts."' : '" 
            . functions::xssafe($firstname) . " " . functions::xssafe($lastname) 
            . " " . functions::xssafe($society) . " (contact)', ";
	}
    $query = "select u.firstname, u.lastname, u.user_id ";
			$query .= "from users u, contacts_res cres  ";
			$query .= "where cres.coll_id = 'letterbox_coll' AND cres.res_id = ? AND cast (u.user_id as varchar) = cres.contact_id ";
			$query .= "GROUP BY u.firstname, u.lastname, u.user_id";
			
	$stmt = $db->query($query, array($res_id));
	
	while($res = $stmt->fetchObject()){
		$nbContacts = $nbContacts + 1;
		$firstname = str_replace("'","\'", $res->firstname);
		$firstname = str_replace('"'," ", $firstname);
		$lastname = str_replace("'","\'", $res->lastname);
		$lastname = str_replace('"'," ", $lastname);
		$frameContacts .= "'contact ".$nbContacts."' : '" 
            . functions::xssafe($firstname) . " " . functions::xssafe($lastname) . " (utilisateur)', ";
	}
	$frameContacts = substr($frameContacts, 0, -2);
	$frameContacts .= "}";
	
    $frm_str = '<h2 id="action_title">'
            . _PROCESS . _LETTER_NUM . ' ' . $res_id;
                $frm_str .= '</h2>';
    $frm_str .='<i onmouseover="this.style.cursor=\'pointer\';" '
             .'onclick="new Ajax.Request(\'' . $_SESSION['config']['businessappurl'] 
                . 'index.php?display=true&dir=actions&page=docLocker\',{ method:\'post\', parameters: {\'AJAX_CALL\': true, \'unlock\': true, \'res_id\': ' 
                . $res_id . '}, onSuccess: function(answer){window.location.href=window.location.href;} });var tmp_bask=$(\'baskets\');';
        $frm_str .= 'if (tmp_bask){tmp_bask.style.visibility=\'visible\';}var tmp_ent =$(\'entity\');';
        $frm_str .= 'if (tmp_ent){tmp_ent.style.visibility=\'visible\';} var tmp_cat =$(\'category\');';
        $frm_str .= 'if (tmp_cat){tmp_cat.style.visibility=\'visible\';}destroyModal(\'modal_'
            . $id_action . '\');reinit();"'
             .' class="fa fa-times-circle fa-2x closeModale" title="'._CLOSE.'"/>';
    $frm_str .='</i>';
    /********************************* LEFT PART **************************************/
    $frm_str .= '<div id="validleftprocess">';
                $frm_str .= '<div id="frm_error_' . $id_action . '" class="error"></div>';
                $frm_str .= '<form name="process" method="post" id="process" action="#" '
                          . 'class="formsProcess addformsProcess" style="text-align:left;">';
                $frm_str .= '<input type="hidden" name="values" id="values" value="' . $res_id . '" />';
                $frm_str .= '<input type="hidden" name="action_id" id="action_id" value="' . $id_action . '" />';
                $frm_str .= '<input type="hidden" name="mode" id="mode" value="' . $mode . '" />';
                $frm_str .= '<input type="hidden" name="table" id="table" value="' . $table . '" />';
                $frm_str .= '<input type="hidden" name="coll_id" id="coll_id" value="' . $coll_id . '" />';
                $frm_str .= '<input type="hidden" name="module" id="module" value="' . $module . '" />';
                $frm_str .= '<input type="hidden" name="req" id="req" value="second_request" />';
                
                $frm_str .= '<h3 onclick="new Effect.toggle(\'general_datas_div\', \'blind\', {delay:0.2});'
                    . 'whatIsTheDivStatus(\'general_datas_div\', \'divStatus_general_datas_div\');return false;" '
                    . 'onmouseover="this.style.cursor=\'pointer\';" class="categorie" style="width:90%;">';
                $frm_str .= ' <span id="divStatus_general_datas_div" style="color:#1C99C5;"><i class="fa fa-minus-square-o"></i></span>&nbsp;<b>'
                        . _GENERAL_INFO . '</b>';
            $frm_str .= '<span class="lb1-details">&nbsp;</span>';
        $frm_str .= '</h3>';

        //GENERAL DATAS
        $frm_str .= '<div id="general_datas_div" style="display:block">';
            $frm_str .= '<div>';
              $frm_str .= '<table width="95%" align="left" border="0">';
              // Displays the document indexes
            foreach (array_keys($data) as $key) {
				if($key != 'is_multicontacts' && $key != 'folder' ||($key == 'is_multicontacts' && $data[$key]['show_value'] == 'Y')){
					$frm_str .= '<tr>';
						$frm_str .= '<td width="50%" align="left"><span class="form_title_process">'
							. $data[$key]['label'] . ' :</span>';
                        if (isset($data[$key]['addon'])) {
                            $frm_str .= ' '.$data[$key]['addon'];
                        }
						$frm_str .= '<td>';
						if ($data[$key]['display'] == 'textinput') {
							$frm_str .= '<input type="text" name="' . $key . '" id="' . $key
								. '" value="' . $data[$key]['show_value']
								. '" readonly="readonly" class="readonly" style="border:none;" />';
						} elseif ($data[$key]['display'] == 'textarea') {
                            if($key == 'is_multicontacts'){
                                $frm_str .= '<input type="hidden" name="' . $key . '" id="' . $key
                                    . '" value="' . $data[$key]['show_value']
                                    . '" readonly="readonly" class="readonly" style="border:none;" />'; 

                                $frm_str .= '<div onClick="$(\'return_previsualise\').style.display=\'none\';" id="return_previsualise" style="cursor: pointer; display: none; border-radius: 10px; box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.4); padding: 10px; width: auto; height: auto; position: absolute; top: 0; left: 0; z-index: 999; background-color: rgba(255, 255, 255, 0.9); border: 3px solid #459ed1;">';
                                    $frm_str .= '<input type="hidden" id="identifierDetailFrame" value="" />';
                                $frm_str .= '</div>';
                                
                                $frm_str .= '<input type="text" value="'.$nbContacts . ' ' ._CONTACTS .'" readonly="readonly" class="readonly" size="40" style="cursor: pointer; border:none;" title="'._SHOW_MULTI_CONTACT.'" alt="'.SHOW_MULTI_CONTACT.'"'; 
                                            $frm_str .= 'onClick = "previsualiseAdminRead(event, '.$frameContacts.');"';
                                $frm_str .= '/>';                                  
                                    
                            } else {
    							$frm_str .= '<textarea name="'.$key.'" id="'.$key.'" rows="3" readonly="readonly" class="readonly" '
    										.'title="'.$data[$key]['show_value'].'" style="width: 150px; max-width: 150px; border: none; color: #666666;">'
    											.$data[$key]['show_value']
    										.'</textarea>';
                            }
						}  else if ($data[$key]['field_type'] == 'radio') {
                            for($k=0; $k<count($data[$key]['radio']);$k++) {
                                $frm_str .= '<input name ="'. $key .'" ';
                                 if ($data[$key]['value'] == $data[$key]['radio'][$k]['ID']){ 
                                    $frm_str .= 'checked';
                                }
                                $frm_str .= ' type="radio" id="'. $key .'_' . $data[$key]['radio'][$k]['ID'].'" value="'. $data[$key]['radio'][$k]['ID'].'" disabled >'. $data[$key]['radio'][$k]['LABEL'];
                            }
                        }
						
						if($key == 'type_id'){
							$_SESSION['category_id_session'] = $data[$key]['value'];
						}
						
						$frm_str .= '</td>';
					$frm_str .= '</tr>';
				}
            }
            if ($chrono_number <> '') {
                $frm_str .= '<tr>';
                $frm_str .= '<td width="50%" align="left"><span class="form_title_process">'
                    . _CHRONO_NUMBER . ' :</span></td>';
                $frm_str .= '<td>';
                $frm_str .= '<input type="text" name="alt_identifier" id="alt_identifier" value="'
                    . functions::xssafe($chrono_number) 
                    . '" readonly="readonly" class="readonly" style="border:none;" />';
                $frm_str .= '</td>';
                $frm_str .= '</tr>';
            }
            if (count($indexes) > 0) {
                foreach (array_keys($indexes) as $key) {
                    $frm_str .= '<tr>';
                        $frm_str .= '<td width="50%" align="left"><span class="form_title_process" >'
                                  . $indexes[$key]['label'] . ' :</span></td>';
                        $frm_str .= '<td>';
                        $frm_str .= '<input type="text" name="' . $key . '" id="'
                                  . $key . '" readonly="readonly" class="readonly" style="border:none;" ';
                        if ($indexes[$key]['type_field'] == 'input') {
                            $frm_str .= ' value="'.$values_fields->{$key}.'" ';
                        } else {
                            $val = '';
                            for ($i=0; count($indexes[$key]['values']); $i++) {
                                if ($values_fields->{$key} == $indexes[$key]['values'][$i]['id']) {
                                    $val =     $indexes[$key]['values'][$i]['label'];
                                    break;
                                }
                            }
                            $frm_str .= ' value="'.$val.'" ';
                        }
                        $frm_str .= ' />';
                        $frm_str .= '</td >';
                    $frm_str .= '</tr>';
                }
            }
            //extension
            $db = new Database();
            $stmt = $db->query("SELECT format FROM ".$table." WHERE res_id = ?", array($res_id));
            $formatLine = $stmt->fetchObject();
            $frm_str .= '<tr>';
            $frm_str .= '<td width="50%" align="left"><span class="form_title_process">' . _FORMAT . ' :</span></td>';
            $frm_str .= '<td>';
            $frm_str .= '<input type="text" name="alt_identifier" id="alt_identifier" value="'
                . functions::xssafe($formatLine->format) . '" readonly="readonly" class="readonly" style="border:none;" />';
            $frm_str .= '</td >';
            $frm_str .= '</tr>';
        $frm_str .= '</table>';
        $frm_str .= '</div>';
    $frm_str .= '</div><br/>';

    //RESPONSE FORM
    /*$nb_attach = 0;

    $stmt = $db->query("SELECT answer_type_bitmask FROM "
        .$_SESSION['collections'][0]['extensions'][0]." WHERE res_id = ?", array($res_id));
    $res = $stmt->fetchObject();
    $bitmask = $res->answer_type_bitmask;
    switch ($bitmask) {
        case "000000":
            $answer = '';
            break;
        case "000001":
            $answer = _SIMPLE_MAIL;
            break;
        case "000010":
            $answer = _REGISTERED_MAIL;
            break;
        case "000100":
            $answer = _DIRECT_CONTACT;
            break;
        case "001000":
            $answer = _EMAIL;
            break;
        case "010000":
            $answer = _FAX;
            break;
        case "100000":
            $answer = _ANSWER;
            break;
        default:
            $answer = _ANSWER;
    }

    if ($core_tools->is_module_loaded('attachments')) {
        $stmt = $db->query("SELECT res_id FROM "
            . $_SESSION['tablename']['attach_res_attachments']
            . " WHERE status <> 'DEL' and attachment_type <> 'converted_pdf' and attachment_type <> 'print_folder' and res_id_master = ? and coll_id = ? and (status <> 'TMP' or (typist = ? and status = 'TMP'))", array($res_id, $coll_id, $_SESSION['user']['UserId']));
        if ($stmt->rowCount() > 0) {
            $nb_attach = $stmt->rowCount();
            $style = '';
            $style2 = '';
        }else{
            $style = 'opacity:0.5;';
            $style2 = 'display:none;';
        }
    }

    if ($answer <> '') {
        $answer .= ': ';
    }

    $frm_str .= '<div class="desc" id="done_answers_div" style="display:none;width:90%;">';
        $frm_str .= '<div class="ref-unit" style="width:95%;">';
            $frm_str .= '<table width="95%">';
                $frm_str .= '<tr>';
                    $frm_str .= '<td>';
                    $frm_str .= '<input type="checkbox"  class="check" name="direct_contact" id="direct_contact" value="true"';
                    if ($process_data['direct_contact']) {
                        $frm_str .= 'checked="checked"';
                    }
                    $frm_str .= ' onclick="unmark_empty_process(\'no_answer\');" />'._DIRECT_CONTACT.'<br/>';
                    $frm_str .= '<input type="checkbox"  class="check" name="fax" id="fax" value="true"';
                    if ($process_data['fax']) {
                        $frm_str .= 'checked="checked"';
                    }
                    $frm_str .=' onclick="unmark_empty_process(\'no_answer\');" />'._FAX.'<br/>';
                    $frm_str .= '<input type="checkbox"  class="check" name="email" id="email"  value="true"';
                    if ($process_data['email']) {
                        $frm_str .= 'checked="checked"';
                    }
                    $frm_str .='onclick="unmark_empty_process(\'no_answer\');" />'._EMAIL.'<br/>';
                    $frm_str .= '<input type="checkbox"  class="check" name="simple_mail" id="simple_mail"  value="true" ';
                    if ($process_data['simple_mail']) {
                        $frm_str .= 'checked="checked"';
                    }
                    $frm_str .= 'onclick="unmark_empty_process(\'no_answer\');" />'._SIMPLE_MAIL.'<br/>';
                    $frm_str .= '<input type="checkbox"  class="check" name="registered_mail" id="registered_mail" value="true" ';
                    if ($process_data['registered_mail']) {
                        $frm_str .= 'checked="checked"';
                    }
                    $frm_str .='onclick="unmark_empty_process(\'no_answer\');" />'._REGISTERED_MAIL.'<br/>';
                    $frm_str .= '<input type="checkbox"  class="check" name="no_answer" id="no_answer" value="true"';
                    if ($process_data['no_answer']) {
                        $frm_str .= 'checked="checked"';
                    }
                    $frm_str .='/>'._NO_ANSWER.'<br />';
                    $frm_str .= '<input type="checkbox"  class="check" name="other" id="other" value="true"';
                    if ($process_data['other']) {
                        $frm_str .= 'checked="checked"';
                    }
                    $frm_str .='onclick="unmark_empty_process(\'no_answer\');" />'
                        . _OTHER . ' : <input type="text" name="other_answer" id="other_answer" value="';
                    if (!empty($process_data['other_answer_desc'])) {
                        $frm_str .= $process_data['other_answer_desc'];
                    } else {
                        $frm_str .='['._DEFINE.']';
                    }
                    $frm_str .='"';
                    if (empty($process_data['other_answer_desc']))
                    {
                        $frm_str .= ' onfocus="if (this.value==\'['._DEFINE.']\'){this.value=\'\';}" ';
                    }
                    $frm_str .=' /><br/>';
                    $frm_str .= '</td>';
                    $frm_str .= '<td>&nbsp;</td>';

                    $frm_str .= '</tr>';
                    $frm_str .= '<tr>';
                    /*$frm_str .= '<td><label for="process_notes">'
                        . _PROCESS_NOTES
                        . ' : </label><br/><textarea name="process_notes" id="process_notes" style="display:block;" rows="8" cols="30">'
                        . $process_data['process_notes'].'</textarea></td>';
                $frm_str .= '</tr>';
            $frm_str .= '</table>';
        $frm_str .= '</div>';
    $frm_str .= '</div>';
    $frm_str .= '<br>';*/

    $frm_str .= '<h3 onclick="new Effect.toggle(\'complementary_fields\', \'blind\', {delay:0.2});'
            . 'whatIsTheDivStatus(\'complementary_fields\', \'divStatus_complementary_fields\');" '
            . 'class="categorie" style="width:90%;" onmouseover="this.style.cursor=\'pointer\';">';
        $frm_str .= ' <span id="divStatus_complementary_fields" style="color:#1C99C5;"><i class="fa fa-minus-square-o"></i></span>&nbsp;' 
            . _OPT_INDEXES;
        $frm_str .= '</h3>';
        $frm_str .= '<div id="complementary_fields"  style="display:block">';
        $frm_str .= '<div>';
    //FOLDERS
    if ($core_tools->is_module_loaded('folder')  && ($core_tools->test_service('associate_folder', 'folder',false) == 1)) {
        require_once 'modules' . DIRECTORY_SEPARATOR . 'folder' . DIRECTORY_SEPARATOR
            . 'class' . DIRECTORY_SEPARATOR . 'class_modules_tools.php';
        $folders = new folder();
        $folder_info = $folders->get_folders_tree('1');
        $folder = '';
        $folder_id = '';

        // Displays the folder data
        $folder = '';

        if(isset($data['folder'])&& !empty($data['folder']))
        {
            $folder = $data['folder']['show_value'];
            $folder_id = str_replace(')', '', substr($folder, strrpos($folder,'(')+1));
        }

                $frm_str .= '<table width="98%" align="center" border="0" id="folder_div">';
                    $frm_str .= '<tr>';
                    $frm_str .= '<td>'. _FOLDER . '</td>';
                    $frm_str .= '</tr>';

                        $frm_str .= '<tr id="folder_tr" style="display:'.$display_value.';">';
                    $frm_str .= '<td class="indexing_field"><select id="folder" name="folder" onchange="displayFatherFolder(\'folder\')" style="width:95%;"><option value="">Sélectionnez un dossier</option>';
                     foreach ($folder_info as $key => $value) {
                if($value['folders_system_id'] == $folder_id){
                    $frm_str .= '<option selected="selected" value="'.$value['folders_system_id'].'" parent="' . $value['parent_id'] . '">'.$value['folder_name'].'</option>';
                }else{
                    $frm_str .= '<option value="'.$value['folders_system_id'].'" parent="' . $value['parent_id'] . '">'.$value['folder_name'].'</option>';
                }
                
            }

            $frm_str .= '</select></td>';
                $frm_str .= '</tr>';
        $frm_str .= '<tr id="parentFolderTr" style="display: none"><td><span id="parentFolderSpan" style="font-style: italic;font-size: 10px"></span></td></tr>';
        $frm_str .= '</table>';
        $frm_str .='<input type="hidden" name="res_id_to_process" id="res_id_to_process"  value="' . $res_id . '" />';
    }
        /*** thesaurus ***/
        if ($core->is_module_loaded('thesaurus') && $core->test_service('thesaurus_view', 'thesaurus', false)) {
            require_once 'modules' . DIRECTORY_SEPARATOR . 'thesaurus'
                            . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
                            . 'class_modules_tools.php';
            $thesaurus = new thesaurus();

            $thesaurusListRes = array(); 

            $thesaurusListRes = $thesaurus->getThesaursusListRes($res_id);

            $frm_str .= '<table width="98%" align="center" border="0" id="thesaurus_div">';
            $frm_str .= '<tr id="thesaurus_tr" style="display:' . $display_value. ';">';
                $frm_str .= '<td>'. _THESAURUS . '</td>';
                $frm_str .= '<td>&nbsp;</td>';
            $frm_str .= '</tr>';

            $frm_str .= '<tr id="thesaurus_tr" style="display:' . $displayValue . ';">';
            $frm_str .= '<td colspan="3" class="indexing_field" id="thesaurus_field"><select multiple="multiple" id="thesaurus" data-placeholder=" "';

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
            $frm_str .= '</select></td>';
            $frm_str .= ' <td><i onclick="lauch_thesaurus_list(this);" class="fa fa-search" title="parcourir le thésaurus" aria-hidden="true" style="cursor:pointer;"></i></td>';
            $frm_str .= '</tr>';
            $frm_str .= '</table>';
            $frm_str .= '<style>#thesaurus_chosen{width:100% !important;}#thesaurus_chosen .chosen-drop{display:none;}</style>';
            /*****************/
        }
    //TAGS
    if (
        $core_tools->is_module_loaded('tags') && (
        $core_tools->test_service('tag_view', 'tags',false) == 1)
        )
    {
        include_once("modules".DIRECTORY_SEPARATOR."tags".
        DIRECTORY_SEPARATOR."templates/process/index.php");
    }
    $frm_str .= '</div>';
    $frm_str .= '</div>';

	
    //ACTIONS
    $frm_str .= '<hr class="hr_process"/>';
    $frm_str .= '<p align="center" style="width:90%;">';
        $frm_str .= '<b>'._ACTIONS.' : </b>';
        $actions  = $b->get_actions_from_current_basket($res_id, $coll_id, 'PAGE_USE');
        if (count($actions) > 0) {
            $frm_str .='<select name="chosen_action" id="chosen_action">';
                $frm_str .='<option value="">'._CHOOSE_ACTION.'</option>';
                for ($ind_act = 0;$ind_act<count($actions);$ind_act++) {
                    $frm_str .='<option value="'.functions::xssafe($actions[$ind_act]['VALUE']).'"';
                    if ($ind_act==0) {
                        $frm_str .= 'selected="selected"';
                    }
                    $frm_str .= '>'.functions::xssafe($actions[$ind_act]['LABEL']).'</option>';
                }
            $frm_str .='</select><br>';
            $frm_str .= '<input type="button" name="send" id="send" value="'
                . _VALIDATE
                . '" class="button" onclick="new Ajax.Request(\'' 
                . $_SESSION['config']['businessappurl'] . 'index.php?display=true&dir=actions&page=docLocker\',{ method:\'post\', parameters: {\'AJAX_CALL\': true, \'unlock\': true, \'res_id\': ' . $res_id . '} });valid_action_form(\'process\', \''
                . $path_manage_action . '\', \'' . $id_action.'\', \''
                . $res_id . '\', \'' . $table . '\', \'' . $module . '\', \''
                . $coll_id . '\', \'' . $mode . '\');"/> ';
        }
        $frm_str .= '<input name="close" id="close" type="button" value="'
            . _CANCEL . '" class="button" onclick="new Ajax.Request(\'' 
            . $_SESSION['config']['businessappurl'] 
            . 'index.php?display=true&dir=actions&page=docLocker\',{ method:\'post\', parameters: {\'AJAX_CALL\': true, \'unlock\': true, \'res_id\': ' . $res_id . '}, onSuccess: function(answer){var cur_url=window.location.href; if (cur_url.indexOf(\'&directLinkToAction\') != -1) cur_url=cur_url.replace(\'&directLinkToAction\',\'\');window.location.href=cur_url;} });var tmp_bask=$(\'baskets\');';
        $frm_str .= 'if (tmp_bask){tmp_bask.style.visibility=\'visible\';}var tmp_ent =$(\'entity\');';
        $frm_str .= 'if (tmp_ent){tmp_ent.style.visibility=\'visible\';} var tmp_cat =$(\'category\');';
        $frm_str .= 'if (tmp_cat){tmp_cat.style.visibility=\'visible\';}destroyModal(\'modal_'
            . $id_action . '\');reinit();"/>';
    $frm_str .= '</p>';
    $frm_str .= '</form>';
    $frm_str .= '</div>';
    $frm_str .= '</div>';

    // ****************************** RIGHT PART *******************************************/

    $frm_str .= '<div id="validright">';
    
    /*** TOOLBAR ***/
    $frm_str .= '<div class="block" align="center" style="height:20px;width=100%;">';
    
    $frm_str .= '<table width="95%" cellpadding="0" cellspacing="0">';
    $frm_str .= '<tr align="center">';
    
    //HISTORY
    if ($core_tools->test_service('view_doc_history', 'apps', false)) {
        $frm_str .= '<td>';
        $frm_str .= '<span onclick="new Effect.toggle(\'history_div\', \'blind\', {delay:0.2});'
            . 'whatIsTheDivStatus(\'history_div\', \'divStatus_history_div\');hideOtherDiv(\'history_div\');return false;" '
            . 'onmouseover="this.style.cursor=\'pointer\';" class="categorie" style="width:90%;">';
        $frm_str .= '<span id="divStatus_history_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span><b>'
            . '&nbsp;<i class="fa fa-line-chart fa-2x" title="'._DOC_HISTORY.'"></i> <sup><span style="display:none;"></span></sup>';
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
        $frm_str .= '<span onclick="new Effect.toggle(\'notes_div\', \'blind\', {delay:0.2});'
            . 'whatIsTheDivStatus(\'notes_div\', \'divStatus_notes_div\');hideOtherDiv(\'notes_div\');return false;" '
            . 'onmouseover="this.style.cursor=\'pointer\';" class="categorie" style="width:90%;">';
        $frm_str .= '<span id="divStatus_notes_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span><b>'
            . '&nbsp;<i class="fa fa-pencil fa-2x" style="'.$style.'" title="'._NOTES.'"></i> <sup><span id="nb_note" style="'.$style2.'" class="'.$class.'">'.$nbr_notes.'</span></sup>';
        $frm_str .= '</b></span>';
        $frm_str .= '</td>';
    }
    
    //SENDMAILS                
    if ($core_tools->is_module_loaded('sendmail') === true 
        && $core_tools->test_service('sendmail', 'sendmail', false) === true
    ) {
        require_once "modules" . DIRECTORY_SEPARATOR . "sendmail" . DIRECTORY_SEPARATOR
            . "class" . DIRECTORY_SEPARATOR
            . "class_modules_tools.php";
        $sendmail_tools    = new sendmail();
        //Count mails
        $nbr_emails = 0;
        $nbr_emails = $sendmail_tools->countUserEmails($res_id, $coll_id);
        if($nbr_emails == 0){
            $class = 'nbResZero';
            $style2 = 'display:none';
            $style = 'opacity:0.5;';
        }else{
            $class = 'nbRes';
            $style = '';
            $style2 = '';
        }
        $frm_str .= '<td>';
        $frm_str .= '<span onclick="new Effect.toggle(\'emails_div\', \'blind\', {delay:0.2});'
            . 'whatIsTheDivStatus(\'emails_div\', \'divStatus_emails_div\');hideOtherDiv(\'emails_div\');return false;" '
            . 'onmouseover="this.style.cursor=\'pointer\';" class="categorie" style="width:90%;">';
        $frm_str .= ' <span id="divStatus_emails_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span><b>&nbsp;'
            . '<i class="fa fa-envelope fa-2x" style="'.$style.'" title="'._SENDED_EMAILS.'"></i> <sup><span id="nb_emails" style="'.$style2.'" class="'.$class.'">'.$nbr_emails.'</span></sup>';
        $frm_str .= '</b></span>&nbsp;';
        $frm_str .= '</td>';
    }
    
    //DIFFUSION LIST
    if ($core_tools->is_module_loaded('entities')) {        
        $frm_str .= '<td>';
        $frm_str .= '<span onclick="new Effect.toggle(\'diff_list_div\', \'blind\', {delay:0.2});'
            . 'whatIsTheDivStatus(\'diff_list_div\', \'divStatus_diff_list_div\');hideOtherDiv(\'diff_list_div\');return false;" '
            . 'onmouseover="this.style.cursor=\'pointer\';" class="categorie" style="width:90%;">';
        $frm_str .= '<span id="divStatus_diff_list_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span><b>'
            . '&nbsp;<i class="fa fa-gear fa-2x" title="'._DIFF_LIST_COPY.'"></i> <sup><span style="display:none;"></span></sup>';
        $frm_str .= '</b></span>';
        $frm_str .= '</td>';
    }
    
     //test service add new version
    $viewVersions = false;
    if ($core->test_service('add_new_version', 'apps', false)) {
        $viewVersions = true;
    }
    //VERSIONS
    if ($core->is_module_loaded('content_management') && $viewVersions) {
        $versionTable = $sec->retrieve_version_table_from_coll_id(
            $coll_id
        );
        $selectVersions = "SELECT res_id FROM "
            . $versionTable . " WHERE res_id_master = ? and status <> 'DEL' order by res_id desc";
        $dbVersions = new Database();
        $stmt = $dbVersions->query($selectVersions, array($res_id));
        $nb_versions_for_title = $stmt->rowCount();
        $lineLastVersion = $stmt->fetchObject();
        $lastVersion = $lineLastVersion->res_id;
        if ($lastVersion <> '') {
            $objectId = $lastVersion;
            $objectTable = $versionTable;
        } else {
            $objectTable = $sec->retrieve_table_from_coll(
                $coll_id
            );
            $objectId = $res_id;
            $_SESSION['cm']['objectId4List'] = $res_id;
        }
        if ($nb_versions_for_title == 0) {
            $extend_title_for_versions = '0';
            $class = 'nbResZero';
            $style2 = 'display:none';
            $style = 'opacity:0.5;';
        } else {
            $extend_title_for_versions = $nb_versions_for_title;
            $class = 'nbRes';
            $style = '';
            $style2 = '';
        }
        $_SESSION['cm']['resMaster'] = '';
        $frm_str .= '<td>';
        $frm_str .= '<span onclick="new Effect.toggle(\'versions_div\', \'blind\', {delay:0.2});'
            . 'whatIsTheDivStatus(\'versions_div\', \'divStatus_versions_div\');hideOtherDiv(\'versions_div\');return false;" '
            . 'onmouseover="this.style.cursor=\'pointer\';" class="categorie" style="width:90%;">';
        $frm_str .= '<span id="divStatus_versions_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span><b>'
            . '&nbsp;<i class="fa fa-code-fork fa-2x" style="'.$style.'" title="'._VERSIONS.'"></i> <sup><span id="nbVersions" style="'.$style2.'" class="'.$class.'">' 
            . $extend_title_for_versions . '</span></sup>';
        $frm_str .= '</b></span>';
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
    $frm_str .= '<span onclick="new Effect.toggle(\'links_div\', \'blind\', {delay:0.2});'
        . 'whatIsTheDivStatus(\'links_div\', \'divStatus_links_div\');hideOtherDiv(\'links_div\');return false;" '
        . 'onmouseover="this.style.cursor=\'pointer\';" class="categorie" style="width:90%;">';
    $frm_str .= '<span id="divStatus_links_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span><b>'
         . '&nbsp;<i class="fa fa-link fa-2x" style="'.$style.'" title="'._LINK_TAB.'"></i> <sup><span id="nbLinks" style="'.$style2.'" class="'.$class.'">' 
            . $nbLink . '</span></sup>';
    $frm_str .= '</b></span>';
    $frm_str .= '</td>';
	
	//VISA CIRCUIT
	if ($core->test_service('config_visa_workflow', 'visa', false)){
        $visa = new visa();
        if($visa->nbVisa($res_id,$coll_id) == 0){
            $style = 'opacity:0.5;';
        }else{
            $style = '';
        }
    $frm_str .= '<td>';
    
    $frm_str .= '<span onclick="new Effect.toggle(\'visa_div\', \'blind\', {delay:0.2});'
        . 'whatIsTheDivStatus(\'visa_div\', \'divStatus_visa_div\');hideOtherDiv(\'visa_div\');return false;" '
        . 'onmouseover="this.style.cursor=\'pointer\';" class="categorie" style="width:90%;">';
    $frm_str .= '<span id="divStatus_visa_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span><b>'
         . '&nbsp;<i class="fa fa-certificate fa-2x" style="'.$style.'" title="'._VISA_WORKFLOW.'"></i><sup><span style="display:none;"></span></sup>';
    $frm_str .= '</b></span>';
    $frm_str .= '</td>';
	}
	//AVIS CIRCUIT
    if ($core_tools->is_module_loaded('avis')) { 
        require_once('modules'.DIRECTORY_SEPARATOR.'avis'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'avis_controler.php');
        $avis = new avis_controler();
        if($avis->nbAvis($res_id,$coll_id) == 0){
            $style = ' opacity:0.5;';
        }else{
            $style = '';
        }
    	if ($core->test_service('config_avis_workflow', 'avis', false)){
            
            $frm_str .= '<td>';
            
            $frm_str .= '<span onclick="new Effect.toggle(\'avis_div\', \'blind\', {delay:0.2});'
                . 'whatIsTheDivStatus(\'avis_div\', \'divStatus_avis_div\');hideOtherDiv(\'avis_div\');return false;" '
                . 'onmouseover="this.style.cursor=\'pointer\';" class="categorie" style="width:90%;">';
            $frm_str .= '<span id="divStatus_avis_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span><b>'
                 . '&nbsp;<i class="fa fa-check-square fa-2x" style="'.$style.'" title="'._AVIS_WORKFLOW.'"></i><sup><span style="display:none;"></span></sup>';
            $frm_str .= '</b></span>';
            $frm_str .= '</td>';
    	}
    }

    $frm_str .= '<td>';
    $frm_str .= '<span onclick="new Effect.toggle(\'list_answers_div\', \'blind\', {delay:0.2});'
              . 'whatIsTheDivStatus(\'list_answers_div\', \'divStatus_done_answers_div\');return false;" '
              . 'onmouseover="this.style.cursor=\'pointer\';" style="width:90%;">';

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
    
    $frm_str .= '<span id="divStatus_done_answers_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span><b>'
                . '&nbsp;<i class="fa fa-paperclip fa-2x" style="'.$style.'" title="'._PJ.'"></i> <sup><span class="nbRes" style="'.$style2.'" id="nb_attach">'. $nb_attach . '</span></sup>';
    $frm_str .= '<span class="lb1-details">&nbsp;</span>';
    $frm_str .= '</span>';
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
        $frm_str .= '<td>';
        $frm_str .= '<span onclick="new Effect.toggle(\'folder_div\', \'blind\', {delay:0.2});'
            . 'whatIsTheDivStatus(\'folder_div\', \'divStatus_folder_div\');return false;" '
            . 'onmouseover="this.style.cursor=\'pointer\';" class="categorie" style="width:90%;">';
        $frm_str .= ' <span id="divStatus_folder_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span>&nbsp;<b><i class="fa fa-folder-o fa-2x" style="'.$style.'" title="'. _FOLDER_ATTACH.'"></i><sup><span style="display:none;"></span></sup></b>';
            $frm_str .= '<span class="lb1-details">&nbsp;</span>';
        $frm_str .= '</span>';
        $frm_str .= '<td>';

    //PRINT FOLDER
   if ($core_tools->test_service('print_folder_doc', 'visa', false)){
        $frm_str .= '<span onclick="new Effect.toggle(\'print_fold_div\', \'blind\', {delay:0.2});'
            . 'whatIsTheDivStatus(\'print_fold_div\', \'divStatus_print_fold_div\');hideOtherDiv(\'print_fold_div\');return false;" '
            . 'onmouseover="this.style.cursor=\'pointer\';" class="categorie" style="width:90%;">';
        $frm_str .= ' <span id="divStatus_print_fold_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span>&nbsp;<b><i class="fa fa-print fa-2x" title="'. _PRINTFOLDER .'"></i><sup><span style="display:none;"></span></sup></b>';
        $frm_str .= '<span class="lb1-details">&nbsp;</span>';
        $frm_str .= '</span>';
        $frm_str .= '<br>';
    }

    //END TOOLBAR
    $frm_str .= '</table>';
    $frm_str .= '</div>';
    
    //ATTACHMENTS FRAME
    if ($core_tools->is_module_loaded('attachments')) {
        require 'modules/templates/class/templates_controler.php';
        $templatesControler = new templates_controler();
        $templates = array();
        $templates = $templatesControler->getAllTemplatesForProcess($data['destination']['value']);
        $_SESSION['destination_entity'] = $data['destination']['value'];
        //var_dump($templates);
        $frm_str .= '<div id="list_answers_div" style="display:none" onmouseover="this.style.cursor=\'pointer\';">';
            $frm_str .= '<div class="block" style="margin-top:-2px;">';
                $frm_str .= '<div id="processframe" name="processframe">';
                    $frm_str .= '<center><h2 onclick="new Effect.toggle(\'list_answers_div\', \'blind\', {delay:0.2});';
                    $frm_str .= 'whatIsTheDivStatus(\'list_answers_div\', \'divStatus_done_answers_div\');';
                    $frm_str .= 'return false;">' . _PJ . ', ' . _ATTACHEMENTS . '</h2></center>';
                    $db = new Database;
                    $stmt = $db->query("SELECT res_id FROM ".$_SESSION['tablename']['attach_res_attachments']
                        . " WHERE (status = 'A_TRA' or status = 'TRA') and attachment_type <> 'converted_pdf' and attachment_type <> 'print_folder' and res_id_master = ? and coll_id = ?", array($res_id, $coll_id));
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
                            . 'index.php?display=true&module=attachments&page=attachments_content\')" />';
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

    //DIFFUSION FRAME
    if ($core_tools->is_module_loaded('entities')) {
        $category = $data['category_id']['value'];
        if($core->test_service('add_copy_in_indexing_validation', 'entities', false)){
            $onlyCC = '&only_cc';
        }

        $frm_str .= '<div id="diff_list_div" style="display:none;" onmouseover="this.style.cursor=\'pointer\';">';
            $frm_str .= '<div class="block" style="margin-top:-2px;">';
                $frm_str .= '<center><h2 onclick="new Effect.toggle(\'diff_list_div\', \'blind\', {delay:0.2});';
                $frm_str .= 'whatIsTheDivStatus(\'diff_list_div\', \'divStatus_diff_list_div\');';
                $frm_str .= 'return false;">' . _DIFF_LIST_COPY . '</h2></center>';
                if ($core_tools->test_service('add_copy_in_process', 'entities', false)) {
                    $frm_str .= '<div style="text-align:center;"><input type="button" class="button" title="'._UPDATE_LIST_DIFF.'" value="'._UPDATE_LIST_DIFF.'" onclick="window.open(\''
                        . $_SESSION['config']['businessappurl']
                        . 'index.php?display=true&module=entities&cat='.$category.'&page=manage_listinstance'
                        . '&origin=process'.$onlyCC.'\', \'\', \'scrollbars=yes,menubar=no,'
                        . 'toolbar=no,status=no,resizable=yes,width=1024,height=650,location=no\');" /></div>';    
                }
                # Get content from buffer of difflist_display 
                $difflist = $_SESSION['process']['diff_list'];
                
                ob_start();
                require_once 'modules/entities/difflist_display.php';
                $frm_str .= str_replace(array("\r", "\n", "\t"), array("", "", ""), ob_get_contents());
                ob_end_clean();

                //$frm_str .= '<hr class="hr_process"/>';
                $frm_str .= '<br/>';                
                $frm_str .= '<br/>';                
                $frm_str .= '<span class="diff_list_history" style="width: 90%; cursor: pointer;" onmouseover="this.style.cursor=\'pointer\';" onclick="new Effect.toggle(\'diff_list_history_div\', \'blind\', {delay:0.2});whatIsTheDivStatus(\'diff_list_history_div\', \'divStatus_diff_list_history_div\');return false;">';
                    $frm_str .= '<span id="divStatus_diff_list_history_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span>';
                    $frm_str .= '<b>&nbsp;<small>'._DIFF_LIST_HISTORY.'</small></b>';
                $frm_str .= '</span>';
                $frm_str .= '<div id="diff_list_history_div" style="display:none">';
            $s_id = $res_id;
            $return_mode = true;
            $diffListType = 'entity_id';
            require_once('modules/entities/difflist_history_display.php');
            $frm_str .= '</div>';
                $frm_str .= '</div>';
                $frm_str .= '<br/>';
                

            $frm_str .= '<hr />';

        $frm_str .= '</div>';
    }

    //HISTORY FRAME
    $frm_str .= '<div class="desc" id="history_div" style="display:none">';
    $frm_str .= '<div class="ref-unit block" style="margin-top:-2px;">';
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
        . 'name="hist_doc_process" id="hist_doc_process" width="100%" height="650px" align="center" '
        . 'scrolling="auto" frameborder="0" style="height: 650px; max-height: 650px; overflow: scroll;display:none;" ></iframe>';


    //$frm_str .= '<hr class="hr_process"/>';
    $frm_str .= '</div>';
    $frm_str .= '<hr />';
    $frm_str .= '</div>';

    //NOTES FRAME
    if ($core_tools->is_module_loaded('notes')) {
        $frm_str .= '<div class="desc" id="notes_div" style="display:none;">';
        $frm_str .= '<div class="ref-unit block" style="margin-top:-2px;">';
        $frm_str .= '<center><h2 onclick="new Effect.toggle(\'notes_div\', \'blind\', {delay:0.2});';
        $frm_str .= 'whatIsTheDivStatus(\'notes_div\', \'divStatus_notes_div\');';
        $frm_str .= 'return false;" onmouseover="this.style.cursor=\'pointer\';">' . _NOTES. '</h2></center>';
        $frm_str .= '<iframe src="'
            . $_SESSION['config']['businessappurl']
            . 'index.php?display=true&module=notes&page=notes&identifier='
            . $res_id . '&origin=document&coll_id=' . $coll_id . '&load&size=medium" '
            . 'name="list_notes_doc" id="list_notes_doc" width="100%" height="650px" align="center" '
            . 'scrolling="auto" frameborder="0" ></iframe>';
        //$frm_str .= '<hr class="hr_process"/>';
        $frm_str .= '</div>';
        $frm_str .= '<hr />';
        $frm_str .= '</div>';
    }
    
    //SENDMAIL FRAME
    if ($core_tools->test_service('sendmail', 'sendmail', false) === true) {
        $frm_str .= '<div class="desc" id="emails_div" style="display:none;">';
        $frm_str .= '<div class="ref-unit block" style="margin-top:-2px;">';
        $frm_str .= '<center><h2 onclick="new Effect.toggle(\'emails_div\', \'blind\', {delay:0.2});';
        $frm_str .= 'whatIsTheDivStatus(\'emails_div\', \'divStatus_emails_div\');';
        $frm_str .= 'return false;" onmouseover="this.style.cursor=\'pointer\';">' . _SENDED_EMAILS. '</h2></center>';
        $frm_str .= '<iframe src="'
            . $_SESSION['config']['businessappurl']
            . 'index.php?display=true&module=sendmail&page=sendmail&identifier='
            . $res_id . '&origin=document&coll_id=' . $coll_id . '&load&size=medium" '
            . 'name="sendmail_iframe" id="sendmail_iframe" width="100%" height="650px" align="center" '
            . 'scrolling="auto" frameborder="0" ></iframe>';
        $frm_str .= '</div>';
        $frm_str .= '</div>';
    }

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
                //$frm_str .= '<hr class="hr_process"/>';
            $frm_str .= '</div>';
            $frm_str .= '<hr />';
        $frm_str .= '</div>';
    }
	
	//PRINT FOLDER FRAME
    if ($core_tools->test_service('print_folder_doc', 'visa', false)){
        $frm_str .= '<div id="print_fold_div" style="display:none" onmouseover="this.style.cursor=\'pointer\';">';
            $frm_str .= '<div class="block" style="margin-top:-2px;">';
                $frm_str .= '<center><h2 onclick="new Effect.toggle(\'print_fold_div\', \'blind\', {delay:0.2});';
                $frm_str .= 'whatIsTheDivStatus(\'print_fold_div\', \'divStatus_print_fold_div\');';
                $frm_str .= 'return false;">Dossier d\'impression</h2></center>';
               
                $frm_str .= '<br>';
				$print_folder = new visa();
				$frm_str .= $print_folder->showPrintFolder($coll_id, $table, $_SESSION['doc_id']);
            $frm_str .= '</div>';
        $frm_str .= '</div>';
    }

    //LINKS FRAME
    $frm_str .= '<div id="links_div" style="display:none" onmouseover="this.style.cursor=\'pointer\';">';
        $frm_str .= '<div class="block" style="text-align: left;margin-top:-2px;">';
            $frm_str .= '<h2 onclick="new Effect.toggle(\'links_div\', \'blind\', {delay:0.2});';
            $frm_str .= 'whatIsTheDivStatus(\'links_div\', \'divStatus_links_div\');';
                $frm_str .= 'return false;">';
                $frm_str .= '<center>' . _LINK_TAB . '</center>';
            $frm_str .= '</h2>';
            $frm_str .= '<div id="loadLinks">';
                $nbLinkDesc = $Class_LinkController->nbDirectLink(
                    $_SESSION['doc_id'],
                    $_SESSION['collection_id_choice'],
                    'desc'
                );
                if ($nbLinkDesc > 0) {
                    $frm_str .= '<i class="fa fa-long-arrow-right fa-2x"></i>';
                    $frm_str .= $Class_LinkController->formatMap(
                        $Class_LinkController->getMap(
                            $_SESSION['doc_id'],
                            $_SESSION['collection_id_choice'],
                            'desc'
                        ),
                        'desc'
                    );
                    $frm_str .= '<br />';
                }
                $nbLinkAsc = $Class_LinkController->nbDirectLink(
                    $_SESSION['doc_id'],
                    $_SESSION['collection_id_choice'],
                    'asc'
                );
                if ($nbLinkAsc > 0) {
                    $frm_str .= '<i class="fa fa-long-arrow-left fa-2x"></i>';
                    $frm_str .= $Class_LinkController->formatMap(
                        $Class_LinkController->getMap(
                            $_SESSION['doc_id'],
                            $_SESSION['collection_id_choice'],
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

    //VERSIONS FRAME
    //test service add new version
    $addNewVersion = false;
    if ($core->test_service('add_new_version', 'apps', false)) {
        $addNewVersion = true;
    }
    $frm_str .= '<div id="versions_div" style="display:none" onmouseover="this.style.cursor=\'pointer\';">';
        $frm_str .= '<div class="block" style="margin-top:-2px;">';
                //$frm_str .= '<center><h2>' . _VERSIONS . '</h2></center>';
                $frm_str .= '<h2 onclick="new Effect.toggle(\'versions_div\', \'blind\', {delay:0.2});';
                $frm_str .= 'whatIsTheDivStatus(\'versions_div\', \'divStatus_versions_div\');';
                    $frm_str .= 'return false;">';
                    $frm_str .= '<center>' . _VERSIONS . '</center>';
                $frm_str .= '</h2>';
                $frm_str .= '<div class="error" id="divError" name="divError"></div>';
                $frm_str .= '<div style="text-align:center;">';
                    $frm_str .= '<a href="';
                        $frm_str .=  $_SESSION['config']['businessappurl'];
                        $frm_str .= 'index.php?display=true&dir=indexing_searching&page=view_resource_controler&original&id=';
                        $frm_str .= $res_id;
                        $frm_str .= '" target="_blank">';
                        $frm_str .= '<i class="fa fa-download fa-2x" title="' . _VIEW_ORIGINAL . '"></i>&nbsp;';
                        $frm_str .= _VIEW_ORIGINAL . ' | ';
                    $frm_str .= '</a>';
                    if ($addNewVersion) {
                        $_SESSION['cm']['objectTable'] = $objectTable;
                        $frm_str .= '<div id="createVersion" style="display: inline;"></div>';
                    }
                    $frm_str .= '<div id="loadVersions"></div>';
                    $frm_str .= '<script language="javascript">';
                        $frm_str .= 'showDiv("loadVersions", "nbVersions", "createVersion", "';
                            $frm_str .= $_SESSION['config']['businessappurl'];
                            $frm_str .= 'index.php?display=false&module=content_management&page=list_versions")';
                    $frm_str .= '</script>';
                $frm_str .= '</div><br>';
        $frm_str .= '</div>';
        $frm_str .= '<hr />';
    $frm_str .= '</div>';

	
	//VISA CIRCUIT FRAME
    $modifVisaWorkflow = false;
    if ($core->test_service('config_visa_workflow', 'visa', false)) {
        $modifVisaWorkflow = true;
    }
    $frm_str .= '<div id="visa_div" style="display:none" onmouseover="this.style.cursor=\'pointer\';">';
        $frm_str .= '<div class="block" style="margin-top:-2px;">';
                $frm_str .= '<h2 onclick="new Effect.toggle(\'visa_div\', \'blind\', {delay:0.2});';
                $frm_str .= 'whatIsTheDivStatus(\'visa_div\', \'divStatus_visa_div\');';
                    $frm_str .= 'return false;">';
                    $frm_str .= '<center>' . _VISA_WORKFLOW . '</center>';
                $frm_str .= '</h2>';
                $frm_str .= '<div class="error" id="divError" name="divError"></div>';
                $frm_str .= '<div style="text-align:center;">';
                $visa = new visa();
				$frm_str .= $visa->getList($res_id, $coll_id, $modifVisaWorkflow, 'VISA_CIRCUIT');
                
                $frm_str .= '</div><br>';
				/* Historique diffusion visa */
				$frm_str .= '<br/>'; 
                    $frm_str .= '<br/>';                
                    $frm_str .= '<span class="diff_list_visa_history" style="width: 90%; cursor: pointer;" onmouseover="this.style.cursor=\'pointer\';" onclick="new Effect.toggle(\'diff_list_visa_history_div\', \'blind\', {delay:0.2});whatIsTheDivStatus(\'diff_list_visa_history_div\', \'divStatus_diff_list_visa_history_div\');return false;">';
                        $frm_str .= '<span id="divStatus_diff_list_visa_history_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span>';
                        $frm_str .= '<b>&nbsp;<small>'._DIFF_LIST_VISA_HISTORY.'</small></b>';
                    $frm_str .= '</span>';

                    $frm_str .= '<div id="diff_list_visa_history_div" style="display:none">';

                        $s_id = $res_id;
                        $return_mode = true;
						$diffListType = 'VISA_CIRCUIT';
                        require_once('modules/entities/difflist_visa_history_display.php');
						

                    $frm_str .= '</div>';
					
				/****************************/
        $frm_str .= '</div>';
        $frm_str .= '<hr />';
    $frm_str .= '</div>';
	
    if ($core_tools->is_module_loaded('avis')) { 
    	//AVIS CIRCUIT FRAME
        $modifVisaWorkflow = false;
        if ($core->test_service('config_avis_workflow', 'avis', false)) {
            $modifAvisWorkflow = true;
        }
        $frm_str .= '<div id="avis_div" style="display:none" onmouseover="this.style.cursor=\'pointer\';">';
            $frm_str .= '<div class="block" style="margin-top:-2px;">';
                    $frm_str .= '<h2 onclick="new Effect.toggle(\'avis_div\', \'blind\', {delay:0.2});';
                    $frm_str .= 'whatIsTheDivStatus(\'avis_div\', \'divStatus_avis_div\');';
                        $frm_str .= 'return false;">';
                        $frm_str .= '<center>' . _AVIS_WORKFLOW . '</center>';
                    $frm_str .= '</h2>';
                    /*require_once('modules/avis/difflist_avis_workflow_display.php');*/
                    $frm_str .= '<div class="error" id="divError" name="divError"></div>';
                    $frm_str .= '<div style="text-align:center;">';
                        $avis = new avis_controler();
    					$frm_str .= $avis->getList($res_id, $coll_id, $modifAvisWorkflow,'AVIS_CIRCUIT');
    					
                    $frm_str .= '</div><br>';
            $frm_str .= '</div>';
            $frm_str .= '<hr />';
        $frm_str .= '</div>';
    }
    //RESOURCE FRAME
    $frm_str .= '<iframe src="' . $_SESSION['config']['businessappurl']
        . 'index.php?display=true&dir=indexing_searching&page=view_resource_controler&id='
        . $res_id . '" name="viewframe" id="viewframe" scrolling="auto" frameborder="0" width="100%" style="width:100% !important;"></iframe>';

    $frm_str .= '</div>';

    //SCRIPT
    $frm_str .= '<script type="text/javascript">displayFatherFolder(\'folder\');resize_frame_process("modal_'
        . $id_action . '", "viewframe", true, true);window.scrollTo(0,0);';
	$curr_visa_wf = $visa->getWorkflow($res_id, $coll_id, 'VISA_CIRCUIT');
	if (count($curr_visa_wf['visa']) == 0 && count($curr_visa_wf['sign']) == 0){
		$frm_str .= 'load_listmodel_visa(\''.$data['destination']['value'].'\',\'VISA_CIRCUIT\',\'tab_visaSetWorkflow\', true);';
	}
    if ($core->is_module_loaded('folder') && ($core->test_service('associate_folder', 'folder',false) == 1)){
        $frm_str .= 'new Chosen($(\'folder\'),{width: "95%", disable_search_threshold: 10, search_contains: true});';
    }
	if ($core->is_module_loaded('thesaurus') && $core->test_service('thesaurus_view', 'thesaurus', false)) {
        $frm_str .= 'new Chosen($(\'thesaurus\'),{width: "95%", disable_search_threshold: 10});';
    }

	if ($core->is_module_loaded('tags')){
        $frm_str .= 'new Chosen($(\'tag_userform\'),{width: "95%", disable_search_threshold: 10, search_contains: true});';
    }
	// DocLocker constantly	
	$frm_str .= 'setInterval("new Ajax.Request(\'' . $_SESSION['config']['businessappurl'] . 'index.php?display=true&dir=actions&page=docLocker\',{ method:\'post\', parameters: {\'AJAX_CALL\': true, \'lock\': true, \'res_id\': ' . $res_id . '} });", 50000);';
        $frm_str .= '$(\'entity\').style.visibility=\'hidden\';';
        $frm_str .= '$(\'category\').style.visibility=\'hidden\';';
        $frm_str .= '$(\'baskets\').style.visibility=\'hidden\';';
    $frm_str .= '</script>';

    //}

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
    $check = true;
    //$other_checked = false;
    $other_txt = '';
    $folder = '';
    $core = new core_tools();
    //print_r($values);
    /*for ($i=0; $i<count($values); $i++) {
        if ($values[$i]['ID'] == "direct_contact" && $values[$i]['VALUE'] == "true") {
            $check = true;
        }
        if ($values[$i]['ID'] == "fax" && $values[$i]['VALUE'] == "true") {
            $check = true;
        }
        if ($values[$i]['ID'] == "email" && $values[$i]['VALUE'] == "true") {
            $check = true;
        }
        if ($values[$i]['ID'] == "simple_mail" && $values[$i]['VALUE'] == "true") {
            $check = true;
        }
        if ($values[$i]['ID'] == "registered_mail" && $values[$i]['VALUE'] == "true") {
            $check = true;
        }
        if ($values[$i]['ID'] == "no_answer" && $values[$i]['VALUE'] == "true") {
            $check = true;
        }
        if ($values[$i]['ID'] == "other" && $values[$i]['VALUE'] == "true")
        {
            $check = true;
            $other_checked = true;
        }
        if ($values[$i]['ID'] == "other_answer"  && trim($values[$i]['VALUE']) <> html_entity_decode('['._DEFINE.']', ENT_NOQUOTES, 'UTF-8')) {
            $other_txt = $values[$i]['VALUE'];
        }
        if ($values[$i]['ID'] == "folder") {
            $folder = $values[$i]['VALUE'];
        }
        if ($values[$i]['ID'] == "coll_id") {
            $coll_id = $values[$i]['VALUE'];
        }
        if ($values[$i]['ID'] == "res_id_to_process") {
            $res_id = $values[$i]['VALUE'];
        }
    }*/
    if ($core->is_module_loaded('folder')) {
        $db = new Database();
        $folder_id = '';

        if (!empty($folder)) {

            $folder_id = $folder;
            $stmt = $db->query("SELECT folders_system_id FROM ".$_SESSION['tablename']['fold_folders']." WHERE folders_system_id = ?", array($folder_id));
            if ($stmt->rowCount() == 0) {
                $_SESSION['action_error'] = _FOLDER.' '.$folder_id.' '._UNKNOWN;
                return false;
            }
        }

        if (!empty($res_id) && !empty($coll_id) && !empty($folder_id)) {
            require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_security.php');
            $sec = new security();
            $table = $sec->retrieve_table_from_coll($coll_id);
            if (empty($table)) {
                $_SESSION['action_error'] .= _COLLECTION.' '._UNKNOWN;
                return false;
            }
            $stmt = $db->query("SELECT type_id FROM ".$table." WHERE res_id = ?", array($res_id));
            $res = $stmt->fetchObject();
            $type_id = $res->type_id;
            $foldertype_id = '';
            $stmt = $db->query("SELECT foldertype_id FROM ".$_SESSION['tablename']['fold_folders']." WHERE folders_system_id = ?", array($folder_id));
            $res = $stmt->fetchObject();
            $foldertype_id = $res->foldertype_id;
            $stmt = $db->query("SELECT fdl.foldertype_id FROM ".$_SESSION['tablename']['fold_foldertypes_doctypes_level1']
                ." fdl, ".$_SESSION['tablename']['doctypes']
                ." d WHERE d.doctypes_first_level_id = fdl.doctypes_first_level_id and fdl.foldertype_id = ? and d.type_id = ?", array($foldertype_id, $type_id));
            if ($stmt->rowCount() == 0) {
                $_SESSION['action_error'] .= _ERROR_COMPATIBILITY_FOLDER;
                return false;
            }
        }
    }
    /*if ($other_checked && $other_txt == '') {
        $_SESSION['action_error'] = _MUST_DEFINE_ANSWER_TYPE;
        return false;
    }
    if ($check == false) {
        $_SESSION['action_error'] = _MUST_CHECK_ONE_BOX;
    }*/
    return $check;
}

/**
 * Action of the form : loads the index in the db
 *
 * @param $arr_id Array Not used here
 * @param $history String Log the action in history table or not
 * @param $id_action String Action identifier
 * @param $label_action String Action label
 * @param $status String  Not used here
 * @param $coll_id String Collection identifier
 * @param $table String Table
 * @param $values_form String Values of the form to load
 * @return false or an array
 *             $data['result'] : res_id of the new file followed by #
 *             $data['history_msg'] : Log complement (empty by default)
 **/
function manage_form($arr_id, $history, $id_action, $label_action, $status,  $coll_id, $table, $values_form)
{
    if (empty($values_form) || count($arr_id) < 1 || empty($coll_id)) {
        return false;
    }

    require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
    $sec =new security();
    $db = new Database();
    $core = new core_tools();
    $res_table = $sec->retrieve_table_from_coll($coll_id);
    $ind = $sec->get_ind_collection($coll_id);
    $table = $_SESSION['collections'][$ind]['extensions'][0];
    $simple_mail = '0';
    $AR_mail = '0';
    $contact = '0';
    $email = '0';
    $fax = '0';
    $other = '0';
    $no_answer = '0';
    $other_txt = '';
    $process_notes = '';
    $folder = '';
    $thesaurusList = '';

    for ($j=0; $j<count($values_form); $j++) {
        if ($values_form[$j]['ID'] == "simple_mail" && $values_form[$j]['VALUE'] == "true") {
            $simple_mail = '1';
        }
        if ($values_form[$j]['ID'] == "registered_mail" && $values_form[$j]['VALUE'] == "true") {
            $AR_mail = '1';
        }
        if ($values_form[$j]['ID'] == "direct_contact" && $values_form[$j]['VALUE'] == "true") {
            $contact = '1';
        }
        if ($values_form[$j]['ID'] == "email" && $values_form[$j]['VALUE'] == "true") {
            $email = '1';
        }
        if ($values_form[$j]['ID'] == "fax" && $values_form[$j]['VALUE'] == "true") {
            $fax = '1';
        }
        if ($values_form[$j]['ID'] == "other" && $values_form[$j]['VALUE'] == "true") {
            $other = '1';
        }
        if ($values_form[$j]['ID'] == "no_answer" && $values_form[$j]['VALUE'] == "true") {
            $no_answer = '1';
        }
        if ($values_form[$j]['ID'] == "other_answer" && !empty($values_form[$j]['ID']) && trim($values_form[$j]['ID']) <> html_entity_decode('['._DEFINE.']', ENT_NOQUOTES, 'UTF-8')) {
            $other_txt = $values_form[$j]['VALUE'];
        }
        if ($values_form[$j]['ID'] == "process_notes") {
            $process_notes = $values_form[$j]['VALUE'];
        }
        if ($values_form[$j]['ID'] == "folder") {
            $folder = $values_form[$j]['VALUE'];
        }
        if ($values_form[$j]['ID'] == "tag_userform") {
            $tags = $values_form[$j]['VALUE'];
        }
        if ($values_form[$j]['ID'] == "thesaurus") {
            $thesaurusList = $values_form[$j]['VALUE'];
        }
    }
    if ($no_answer == '1') {
        $bitmask = '000000';
    } else {
        $bitmask = $other.$fax.$email.$contact.$AR_mail.$simple_mail;
    }

    if ($core->is_module_loaded('tags')) {
        $tags_list = explode('__', $tags);
        include_once("modules".DIRECTORY_SEPARATOR."tags".
                    DIRECTORY_SEPARATOR."tags_update.php");
    }

    //thesaurus
    if ($core->is_module_loaded('thesaurus')) {
        require_once 'modules' . DIRECTORY_SEPARATOR . 'thesaurus'
                    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
                    . 'class_modules_tools.php';
        $thesaurus = new thesaurus();
        $thesaurus->updateResThesaurusList($thesaurusList,$arr_id[0]);
    }

    if ($core->is_module_loaded('folder') && ($core->test_service('associate_folder', 'folder',false) == 1)) {
        $folder_id = '';
        $old_folder_id = '';
        //get old folder ID
        $stmt = $db->query("SELECT folders_system_id FROM ".$res_table." WHERE res_id = ?", array($arr_id[0]));
        $res = $stmt->fetchObject();
        $old_folder_id = $res->folders_system_id;
            
        if (!empty($folder)) {    
            $folder_id = $folder;

            if ($folder_id <> $old_folder_id && $_SESSION['history']['folderup']) {
                require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
                $hist = new history();
                $hist->add($_SESSION['tablename']['fold_folders'], $folder_id, "UP", 'folderup', _DOC_NUM.$arr_id[0]._ADDED_TO_FOLDER, $_SESSION['config']['databasetype'],'apps');
                if (isset($old_folder_id) && !empty($old_folder_id)) {
                    $hist->add($_SESSION['tablename']['fold_folders'], $old_folder_id, "UP", 'folderup', _DOC_NUM.$arr_id[0]._DELETED_FROM_FOLDER, $_SESSION['config']['databasetype'],'apps');
                }
            }

            $db->query("UPDATE ".$res_table." SET folders_system_id = ? WHERE res_id = ? ", array($folder_id, $arr_id[0]));
        } else if(empty($folder) && !empty($old_folder_id)) { //Delete folder reference in res_X
            $db->query("UPDATE ".$res_table." SET folders_system_id = NULL WHERE res_id = ?", array($arr_id[0]));
        }
    }
    if ($core->is_module_loaded('entities') && count($_SESSION['redirect']['diff_list']) == 0) {
        require_once('modules/entities/class/class_manage_listdiff.php');
        $list = new diffusion_list();
        $params = array('mode'=> 'listinstance', 'table' => $_SESSION['tablename']['ent_listinstance'], 'coll_id' => $coll_id, 'res_id' => $arr_id[0], 'user_id' => $_SESSION['user']['UserId'], 'concat_list' => true, 'only_cc' => true);
        $list->load_list_db($_SESSION['process']['diff_list'], $params); //pb enchainement avec action redirect
    }
    //$_SESSION['process']['diff_list'] = array();
    $_SESSION['redirect']['diff_list'] = array();
    unset($_SESSION['redirection']);
    unset($_SESSION['redirect']);
    $db->query("UPDATE ".$table." SET answer_type_bitmask = ?, process_notes = ?, other_answer_desc = ? WHERE res_id= ?",
    array($bitmask, $process_notes, $other_txt, $arr_id[0]));
    return array('result' => $arr_id[0].'#', 'history_msg' => '');
}

function manage_unlock($arr_id, $history, $id_action, $label_action, $status, $coll_id, $table)
{
    $db = new Database();
    $result = '';
    for ($i=0; $i<count($arr_id);$i++) {
        $result .= $arr_id[$i].'#';
        $db->query("UPDATE ".$table. " SET video_user = '', video_time = 0 WHERE res_id = ?", array($arr_id[$i]));
    }
    return array('result' => $result, 'history_msg' => '');
}

