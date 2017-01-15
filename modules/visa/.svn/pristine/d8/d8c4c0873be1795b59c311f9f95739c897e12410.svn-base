<?php


require_once('modules/visa/class/class_modules_tools.php');

$etapes = [];
$error_visa = false;
$error_visa_response_project = false;

// if (isset($_SESSION['error_visa']) && $_SESSION['error_visa'] <> '') {
//     $error_visa = true;
// }

$visa = new visa();
// $curr_visa_wf = $visa->getWorkflow($_SESSION['doc_id'], $_SESSION['current_basket']['coll_id'], 'VISA_CIRCUIT');

// if (count($curr_visa_wf['sign']) == 0){
//     $error_visa = true;
// }

$visa->checkResponseProject($_SESSION['doc_id'], $_SESSION['current_basket']['coll_id']);
if ($visa->errorMessageVisa){
    $error_visa_response_project = true;
}

if (!$error_visa && !$error_visa_response_project){
    require("modules/entities/entities_tables.php");
    require_once("modules/entities/class/EntityControler.php");
    require_once('modules/entities/class/class_manage_entities.php');
    $confirm = false;
    $etapes = array('form');
    $frm_width='355px';
    $frm_height = 'auto';
}

function get_form_txt($values, $path_manage_action,  $id_action, $table, $module, $coll_id, $mode )
{
    require_once('apps/' . $_SESSION['config']['app_id'] . '/class/class_chrono.php');

    $ent = new entity();
    $entity_ctrl = new EntityControler();
    $servicesCompare = array();
    $db = new Database();
    $cr = new chrono();

    $labelAction = '';
    if ($id_action <> '') {
        $stmt = $db->query("select label_action from actions where id = ?", array($id_action));
        $resAction = $stmt->fetchObject();
        $labelAction = $db->show_string($resAction->label_action);
    }

    $users = array();
    $stmt = $db->query("SELECT distinct uc.user_id, u.lastname, u.firstname 
                FROM usergroup_content uc 
                LEFT JOIN usergroups_services us on us.group_id = uc.group_id 
                LEFT JOIN users u on u.user_id = uc.user_id 
                WHERE us.service_id = 'sign_document'
                ORDER BY lastname asc");

    while($res = $stmt->fetchObject())
    {
        array_push($users, array( 'ID' => $res->user_id, 'NOM' => $db->show_string($res->lastname), "PRENOM" => $db->show_string($res->firstname)));
    }

    $frm_str = '<div id="frm_error_'.$id_action.'" class="error"></div>';
    if ($labelAction <> '') {
        $frm_str .= '<h2 class="title">' . $labelAction . ' ' . _NUM;
    } else {
        $frm_str .= '<h2 class="title">'._REDIRECT_MAIL.' '._NUM;
    }
    $values_str = '';
    if(empty($_SESSION['stockCheckbox'])){
        for($i=0; $i < count($values);$i++) {
            $values_str .= $values[$i].', ';
        }
    }else { 
        for($i=0; $i < count($_SESSION['stockCheckbox']);$i++) {
            $values_str .= $_SESSION['stockCheckbox'][$i].', ';
        }
    }
    $values_str = preg_replace('/, $/', '', $values_str);
	if(_ID_TO_DISPLAY == 'res_id'){
		$frm_str .= $values_str;
	} else if (_ID_TO_DISPLAY == 'chrono_number') {
    	$chrono_number = $cr->get_chrono_number($values_str, 'res_view_letterbox');
    	$frm_str .= $chrono_number;
	}
    
    $frm_str .= '</h2><br/><br/>';
    require 'modules/templates/class/templates_controler.php';
    $templatesControler = new templates_controler();

    $EntitiesIdExclusion = array();
    $entities = $entity_ctrl->getAllEntities();
    $countEntities = count($entities);

    for ($cptAllEnt = 0;$cptAllEnt<$countEntities;$cptAllEnt++) {
        if (!is_integer(array_search($entities[$cptAllEnt]->__get('entity_id'), $servicesCompare))) {
            array_push($EntitiesIdExclusion, $entities[$cptAllEnt]->__get('entity_id'));
        }
    }
    
     $allEntitiesTree = array();
     $allEntitiesTree = $ent->getShortEntityTreeAdvanced(
        $allEntitiesTree, 'all', '', $EntitiesIdExclusion, 'all'
     );

    if ($destination <> '') {
        $templates = $templatesControler->getAllTemplatesForProcess($destination);
    } else {
        $templates = $templatesControler->getAllTemplatesForSelect();
    } 
    $frm_str .='<b>'._SUBMIT_COMMENT.':</b><br/>';
    $frm_str .= '<select name="templateNotes" id="templateNotes" style="width:98%;margin-bottom: 10px;background-color: White;border: 1px solid #999;color: #666;text-align: left;" '
                . 'onchange="addTemplateToNote($(\'templateNotes\').value, \''
                . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
                . '&module=templates&page=templates_ajax_content_for_notes\');document.getElementById(\'notes\').focus();">';
    $frm_str .= '<option value="">' . _SELECT_NOTE_TEMPLATE . '</option>';
        for ($i=0;$i<count($templates);$i++) {
            if ($templates[$i]['TYPE'] == 'TXT' && ($templates[$i]['TARGET'] == 'notes' || $templates[$i]['TARGET'] == '')) {
                $frm_str .= '<option value="';
                $frm_str .= $templates[$i]['ID'];
                $frm_str .= '">';
                $frm_str .= $templates[$i]['LABEL'];
            }
            $frm_str .= '</option>';
        }
    $frm_str .= '</select><br />';

    $frm_str .= '<textarea style="width:98%;height:60px;resize:none;" name="notes"  id="notes" onblur="document.getElementById(\'note_content_to_user\').value=document.getElementById(\'notes\').value;"></textarea>';

    $frm_str .='<hr />';
        $frm_str .='<div id="form3">';
            $frm_str .= '<form name="frm_redirect_user" id="frm_redirect_user" method="post" class="forms" action="#">';
            $frm_str .= '<input type="hidden" name="chosen_action" id="chosen_action" value="end_action" />';
            $frm_str .= '<input type="hidden" name="note_content_to_user" id="note_content_to_user" value="" />';
            $frm_str .='<p>';
                $frm_str .='<label><b>'._REDIRECT_TO_USER.' :</b></label>';
                $frm_str .='<select name="user" id="user" style="float:left;">';
                    $frm_str .='<option value="">'._CHOOSE_USER2.'</option>';
                    for($i=0; $i < count($users); $i++)
                   {
                    $frm_str .='<option value="'.$users[$i]['ID'].'">'.$users[$i]['NOM'].' '.$users[$i]['PRENOM'].'</option>';
                   }
                $frm_str .='</select>';
                $frm_str .=' <input type="button" style="float:right;margin:0px;" name="redirect_user" id="redirect_user" value="'
                    ._SEND_TO_SIGNATURE
                    . '" class="button" onclick="valid_action_form( \'frm_redirect_user\', \''
                    . $path_manage_action . '\', \'' . $id_action . '\', \'' . $values_str . '\', \'' . $table . '\', \'' . $module . '\', \'' . $coll_id . '\', \'' . $mode . '\');"  />';
            $frm_str .='</p>';
            $frm_str .='<div style="clear:both;"></div>';
        $frm_str .='</form>';
    $frm_str .='</div>';
    
    $frm_str .='<hr />';

    $frm_str .='<div align="center">';
            $frm_str .='<input type="button" name="cancel" id="cancel" class="button"  value="'._CANCEL.'" onclick="pile_actions.action_pop();destroyModal(\'modal_'.$id_action.'\');"/>';
    $frm_str .='</div>';
    return addslashes($frm_str);
}

 function check_form($form_id,$values)
 {
    if($form_id == 'frm_redirect_user') {
        $user = get_value_fields($values, 'user');
        if($user == '')
        {
            $_SESSION['action_error'] = _MUST_CHOOSE_USER;
            return false;
        }
        else
        {
            return true;
        }
    } else {
        $_SESSION['action_error'] = _FORM_ERROR;
        return false;
    }
 }

function manage_form($arr_id, $history, $id_action, $label_action, $status, $coll_id, $table, $values_form )
{
    
    if(empty($values_form) || count($arr_id) < 1) 
        return false;
    
    require_once('modules/entities/class/class_manage_listdiff.php');
    $diffList = new diffusion_list();
    $visa = new visa();
    $content_note = "";
    
    $db = new Database();
    
    $formValues = array();
    for($i=0; $i<count($values_form); $i++) {
        $formValue = $values_form[$i];
        $id = $formValue['ID'];
        $value = $formValue['VALUE'];
        $formValues[$id] = $value;
    }

    if(isset($formValues['redirect_user'])) {
        $userId = $formValues['user'];
        $message = _REDIRECT_TO_USER_OK . ' ' . $userId;
    }
    
    # Change old sign to visa and set new sign
    for($i=0; $i<count($arr_id); $i++) {
        $res_id = $arr_id[$i];
        # update dest_user
        $new_dest = $userId;
        if($new_dest) {
            if($formValues['note_content_to_user'] != ''){
                //Add notes
                $userIdTypist = $_SESSION['user']['UserId'];
                $content_note = $formValues['note_content_to_user'];
                // $content_note = str_replace(";", ".", $content_note);
                // $content_note = str_replace("--", "-", $content_note);
                // $content_note = $content_note;
                
                $db->query(
                    "INSERT INTO notes (identifier, tablename, user_id, "
                            . "date_note, note_text, coll_id ) VALUES (?,?,?,CURRENT_TIMESTAMP,?,?)",
					array($res_id, $table, $userIdTypist, $content_note, $coll_id)
                );
            }
        }
        
        $circuit = $visa->getWorkflow($res_id, $coll_id, 'VISA_CIRCUIT');

        if (count($circuit['sign']) > 0) {
            // $up_request = "UPDATE listinstance SET sequence = ".count($circuit['visa']).", item_mode = 'visa', process_date = CURRENT_TIMESTAMP WHERE res_id = ".$res_id." AND item_id='".$_SESSION['user']['UserId']."' AND difflist_type = 'VISA_CIRCUIT' AND process_date ISNULL ";
            // $db->query($up_request);
            $current_timestamp = date("Y-m-d H:i:s");
            $circuit['sign']['users'][0]['process_date'] = $current_timestamp;
            if (!isset($circuit['visa']['users'])) {
                $circuit['visa']['users'] = array();
            }
            array_push($circuit['visa']['users'], $circuit['sign']['users'][0]);
        }

        // $add_request = "INSERT INTO listinstance (coll_id, res_id, sequence, item_id, item_type, item_mode, added_by_user, added_by_entity, difflist_type, process_comment) 
        //                 VALUES ('".$coll_id."', ".$res_id.", 0, '".$new_dest."', 'user_id', 'sign', '".$_SESSION['user']['UserId']."', '".$_SESSION['user']['primaryentity']['id']."', 'VISA_CIRCUIT', '".$content_note."')";
        // $db->query($add_request);   
        
        $circuit['sign']['users'] = array();
        array_push($circuit['sign']['users'],               
            array(
                'user_id' => $new_dest, 
                'process_comment' => $content_note, 
                'process_date' => "", 
                'viewed' => 0,
                'visible' => 'Y',
                'difflist_type' => 'VISA_CIRCUIT'
            )
        );
        unset($_SESSION['visa_wf']);

        if (isset($circuit['visa']['users'])) {
            $_SESSION['visa_wf']['diff_list']['visa']['users'] = $circuit['visa']['users'];
        }
        
        $_SESSION['visa_wf']['diff_list']['sign']['users'] = $circuit['sign']['users'];
        // var_dump($_SESSION['visa_wf']['diff_list']);exit;
        $visa->saveWorkflow($res_id, $coll_id, $_SESSION['visa_wf']['diff_list'], 'VISA_CIRCUIT');
        unset($_SESSION['visa_wf']);
    }
    
    $_SESSION['action_error'] = $message;
    return array('result' => implode('#', $arr_id), 'history_msg' => $message);
}

function manage_unlock($arr_id, $history, $id_action, $label_action, $status, $coll_id, $table)
{
    $db = new Database();
    for($i=0; $i<count($arr_id );$i++)
    {
        $req = $db->query("update ".$table. " set video_user = '', video_time = 0 where res_id = ?", array($arr_id[$i]));

        if(!$req)
        {
            $_SESSION['action_error'] = _SQL_ERROR;
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
    for($i=0; $i<count($values);$i++)
    {
        if($values[$i]['ID'] == $field)
        {
            return  $values[$i]['VALUE'];
        }
    }
    return false;
}

?>
