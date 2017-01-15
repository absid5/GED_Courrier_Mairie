<?php
$confirm = false;
$etapes = array('form');
$frm_width='355px';
$frm_height = 'auto';
require("modules/entities/entities_tables.php");
require_once("modules/entities/class/EntityControler.php");
require_once("modules/entities/class/class_manage_entities.php");
require_once('modules/avis/class/avis_controler.php');

 function get_form_txt($values, $path_manage_action,  $id_action, $table, $module, $coll_id, $mode )
 {
    require_once('apps/' . $_SESSION['config']['app_id'] . '/class/class_chrono.php');
    $cr7 = new chrono();
    $ent = new entity();
    $entity_ctrl = new EntityControler();
    $avis = new avis_controler();

    $services = array();
    $servicesCompare = array();
    $db = new Database();
    $labelAction = '';
    if ($id_action <> '') {
        $stmt = $db->query("select label_action from actions where id = ?",array($id_action));
        $resAction = $stmt->fetchObject();
        $labelAction = functions::show_string($resAction->label_action);
    }
    

    $frm_str = '<div id="frm_error_'.$id_action.'" class="error"></div>';
    if ($labelAction <> '') {
        $frm_str .= '<h2 class="title">' . $labelAction . ' ' . _NUM;
    } else {
        $frm_str .= '<h2 class="title">'._REDIRECT_MAIL.' '._NUM;
    }
    $values_str = '';
    if(empty($_SESSION['stockCheckbox'])){
    for($i=0; $i < count($values);$i++)
        {
            $values_str .= $values[$i].', ';
        }
    }else{ 

    for($i=0; $i < count($_SESSION['stockCheckbox']);$i++)
        {
            $values_str .= $_SESSION['stockCheckbox'][$i].', ';
        }
    }
    $values_str = preg_replace('/, $/', '', $values_str);
    if(_ID_TO_DISPLAY == 'res_id'){
        $frm_str .= $values_str;
    } else if (_ID_TO_DISPLAY == 'chrono_number'){
        $chrono_number = $cr7->get_chrono_number($values_str, 'res_view_letterbox');
        $frm_str .= $chrono_number;
    }
    $frm_str .= '</h2><br/>';
    require 'modules/templates/class/templates_controler.php';
    $templatesControler = new templates_controler();
    $templates = array();

        $avisContent = $avis->getAvis($values_str);

        $EntitiesIdExclusion = array();
        $entities = $entity_ctrl->getAllEntities();
        $countEntities = count($entities);
        //var_dump($entities);
        for ($cptAllEnt = 0;$cptAllEnt<$countEntities;$cptAllEnt++) {
            if (!is_integer(array_search($entities[$cptAllEnt]->__get('entity_id'), $servicesCompare))) {
                array_push($EntitiesIdExclusion, $entities[$cptAllEnt]->__get('entity_id'));
            }
        }
        
        $allEntitiesTree= array();
        $allEntitiesTree = $ent->getShortEntityTreeAdvanced(
            $allEntitiesTree, 'all', '', $EntitiesIdExclusion, 'all'
        );
        if ($destination <> '') {
            $templates = $templatesControler->getAllTemplatesForProcess($destination);
        } else {
            $templates = $templatesControler->getAllTemplatesForSelect();
        }

        $_SESSION['redirect']['diff_list'] = array();
        $_SESSION['redirect']['diff_list']['difflist_type'] = 'entity_id';

        if (!empty($_SESSION['process']['diff_list']['avis'])) {
            $_SESSION['redirect']['diff_list']['avis'] = $_SESSION['process']['diff_list']['avis'];
        } 

        if (!empty($_SESSION['process']['diff_list']['avis_copy'])) {
            $_SESSION['redirect']['diff_list']['avis_copy'] = $_SESSION['process']['diff_list']['avis_copy'];
        }  

        if (!empty($_SESSION['process']['diff_list']['avis_info'])) {
            $_SESSION['redirect']['diff_list']['avis_info'] = $_SESSION['process']['diff_list']['avis_info'];
        }  
         
        $frm_str .='<b>'._RECOMMENDATION_LIMIT_DATE.':</b><br/>';
        $frm_str .= '<input name="recommendation_limit_date_tr" type="text" '
            . 'id="recommendation_limit_date_tr" value="'.functions::format_date_db($avisContent->recommendation_limit_date).'" placeholder="JJ-MM-AAAA" onfocus="checkRealDateAvis();" onChange="checkRealDateAvis();"  onclick="clear_error(\'frm_error_'
            . $actionId . '\');showCalender(this);"  onblur="document.getElementById(\'recommendation_limit_date\').value=document.getElementById(\'recommendation_limit_date_tr\').value;"/>';
        $frm_str .='<br/>';
        /*$frm_str .='<br/><b>'._RECOMMENDATION_NOTE.':</b><br/>';
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
        $frm_str .= '</select><br />';*/
        $avisContent->note_text=str_replace('[POUR AVIS] ', '', $avisContent->note_text);
	$avisContent->note_text=preg_replace('/[\n]/i', '##', $avisContent->note_text);
	$frm_str .='<br/><b>'._WRITTEN_BY.' : '.$avisContent->user_id.'</b><br/>';
        $frm_str .= '<textarea style="width:98%;height:60px;resize:none;" name="notes"  id="notes" onblur="document.getElementById(\'note_content_to_users\').value=document.getElementById(\'notes\').value.replace(/[\n]/gi, \'##\' );">'.$avisContent->note_text.'</textarea>';
        //var_dump($allEntitiesTree);
        $frm_str .= '<hr />';
        $frm_str .='<div id="form2" style="border:none;">';
        $frm_str .='<script>change_entity(\''.$_SESSION['destination_entity'].'\', \''.$_SESSION['config']['businessappurl'].'index.php?display=true&module=entities&page=load_listinstance&specific_role=avis'.'\', \'diff_list_div_redirect\', \'redirect\', \'\', \'false\', \'avis\');</script>';
        $frm_str .= '<form name="frm_redirect_dep" id="frm_redirect_dep" method="post" class="forms" action="#">';
        $frm_str .= '<input type="hidden" name="chosen_action" id="chosen_action" value="end_action" />';
        $frm_str .= '<input type="hidden" name="note_content_to_users" id="note_content_to_users" value="'.$avisContent->note_text.'" />';
        $frm_str .= '<input type="hidden" name="note_content_to_users_origin" id="note_content_to_users_origin" value="'.$avisContent->note_text.'" />';
        $frm_str .= '<input type="hidden" name="recommendation_limit_date" id="recommendation_limit_date" value="'.$avisContent->recommendation_limit_date.'" />';
                $frm_str .='<p>';
                    $frm_str .='<div style="clear:both;"></div>';
                $frm_str .= '<div id="diff_list_div_redirect" class="scroll_div" style="height:auto;"></div>';
                $frm_str .='</p>';
            $frm_str .='</form>';
        $frm_str .='</div>';
    $frm_str .='<hr />';

    $frm_str .='<div align="center">';
        $frm_str .=' <input type="button" name="redirect_dep" value="'._VALIDATE.'" id="redirect_dep" class="button" onclick="valid_action_form( \'frm_redirect_dep\', \''.$path_manage_action.'\', \''. $id_action.'\', \''.$values_str.'\', \''.$table.'\', \''.$module.'\', \''.$coll_id.'\', \''.$mode.'\');" />';
        $frm_str .=' <input type="button" name="cancel" id="cancel" class="button"  value="'._CANCEL.'" onclick="pile_actions.action_pop();destroyModal(\'modal_'.$id_action.'\');"/>';
    $frm_str .='</div>';
    $frm_str .= '<script>document.getElementById(\'notes\').value=document.getElementById(\'notes\').value.replace(/##/gi, \'\n\' );</script>';
    return addslashes($frm_str);
 }

 function check_form($form_id,$values)
 {
    if(empty($_SESSION['redirect']['diff_list']['avis']['users'][0])){
        $_SESSION['action_error'] = _RECOMMENDATION_USER. " " . _MANDATORY;
        return false;
    }
    $recommendation_limit_date = get_value_fields($values, 'recommendation_limit_date');
    if($recommendation_limit_date == null || $recommendation_limit_date == ''){
        $_SESSION['action_error'] = _RECOMMENDATION_LIMIT_DATE. " " . _MANDATORY;
        return false;
    }
    /*if($form_id == 'frm_redirect_dep') {
        $dep = get_value_fields($values, 'department');
        if($dep == '') {
            $_SESSION['action_error'] = _MUST_CHOOSE_DEP;
            return false;
        } else if (empty($_SESSION['redirect']['diff_list']['dest']['users'][0])
                || ! isset($_SESSION['redirect']['diff_list']['dest']['users'][0])
            ) {
                $_SESSION['action_error'] = _DEST
                    . " " . _MANDATORY;
                return false;
        } else {
            return true;
        }
    } else if($form_id == 'frm_redirect_user') {
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
    }*/
    return true;
 }

function manage_form($arr_id, $history, $id_action, $label_action, $status, $coll_id, $table, $values_form )
{
    /*
        Redirect to dep:
        $values_form = array (size=3)
          0 => 
            array (size=2)
              'ID' => string 'chosen_action' (length=13)
              'VALUE' => string 'end_action' (length=10)
          1 => 
            array (size=2)
              'ID' => string 'department' (length=10)
              'VALUE' => string 'DGA' (length=3)
          2 => 
            array (size=2)
              'ID' => string 'redirect_dep' (length=12)
              'VALUE' => string 'Rediriger' (length=9)
        
        Redirect to user:
        $values_form = array (size=3)
          0 => 
            array (size=2)
              'ID' => string 'chosen_action' (length=13)
              'VALUE' => string 'end_action' (length=10)
          1 => 
            array (size=2)
              'ID' => string 'user' (length=4)
              'VALUE' => string 'aackermann' (length=10)
          2 => 
            array (size=2)
              'ID' => string 'redirect_user' (length=13)
              'VALUE' => string 'Rediriger' (length=9)
    
    */
    
    if(empty($values_form) || count($arr_id) < 1) 
        return false;
    
    $res_id = $arr_id[0];
    require_once('modules/entities/class/class_manage_listdiff.php');
    require_once('modules/notes/class/notes_controler.php');
    $diffList = new diffusion_list();
    $note = new notes_controler();
    $avis = new avis_controler();

    $new_difflist = $diffList->get_listinstance($res_id);

    $db = new Database();
    
    $formValues = array();
    for($i=0; $i<count($values_form); $i++) {
        $formValue = $values_form[$i];
        $id = $formValue['ID'];
        $value = $formValue['VALUE'];
        $formValues[$id] = $value;
    }
    
        # Reset users in specific role
        $new_difflist['avis'] = array();
        $new_difflist['avis_copy'] = array();
        $new_difflist['avis_info'] = array();

        $new_difflist['avis']['users'] = array();
        $new_difflist['avis_copy']['users'] = array();
        $new_difflist['avis_info']['users'] = array();

        $new_difflist['difflist_type'] = 'entity_id';
        
        foreach ($_SESSION['redirect']['diff_list']['avis']['users'] as $key => $value) {
            //print_r($value);
            array_push(
                $new_difflist['avis']['users'], 
                array(
                    'user_id' => $value['user_id'], 
                    'firstname' => $value['firstname'],
                    'entity_id' => $value['entity_id'],
                    'entity_label' => $value['entity_label'],
                    'visible' => $value['visible']
                )
            );
        }

        if(!empty($_SESSION['redirect']['diff_list']['avis_copy']['users'])){
            foreach ($_SESSION['redirect']['diff_list']['avis_copy']['users'] as $key => $value) {
                //print_r($value);
                array_push(
                    $new_difflist['avis_copy']['users'], 
                    array(
                        'user_id' => $value['user_id'], 
                        'firstname' => $value['firstname'],
                        'entity_id' => $value['entity_id'],
                        'entity_label' => $value['entity_label'],
                        'visible' => $value['visible']
                    )
                );
            }
        }

        if(!empty($_SESSION['redirect']['diff_list']['avis_info']['users'])){
            foreach ($_SESSION['redirect']['diff_list']['avis_info']['users'] as $key => $value) {
                //print_r($value);
                array_push(
                    $new_difflist['avis_info']['users'], 
                    array(
                        'user_id' => $value['user_id'], 
                        'firstname' => $value['firstname'],
                        'entity_id' => $value['entity_id'],
                        'entity_label' => $value['entity_label'],
                        'visible' => $value['visible']
                    )
                );
            }
        }
        

        # save note
        //if(($formValues['note_content_to_users'] != '') && ($formValues['note_content_to_users'] != $formValues['note_content_to_users_origin'])){
            //Add notes
            $content_note = $formValues['note_content_to_users'];
            $content_note = str_replace("##", "\n", $content_note);
            $content_note = str_replace(";", ".", $content_note);
            $content_note = str_replace("--", "-", $content_note);
            $content_note = $content_note;
            $content_note = '[' . _TO_AVIS . '] ' . $content_note.' ← '._VALIDATE_BY.' '.$_SESSION['user']['UserId'];
            $avis->UpdateNoteAvis($res_id, $coll_id, $content_note);
            
        //}
        $avis->processAvis($res_id,$formValues['recommendation_limit_date']);
        # Save listinstance
        $diffList->save_listinstance(
            $new_difflist, 
            $new_difflist['difflist_type'],
            $coll_id, 
            $res_id, 
            $_SESSION['user']['UserId']
        );           
    
    # Pb with action chain : main action page is saved after this. 
    #   if process, $_SESSION['process']['diff_list'] will override this one
    
    $_SESSION['process']['diff_list'] = $new_difflist;
    $_SESSION['indexing']['diff_list'] = $new_difflist;
    $_SESSION['action_error'] = $message;
    return array('result' => implode('#', $arr_id), 'history_msg' => $message);
}

function manage_unlock($arr_id, $history, $id_action, $label_action, $status, $coll_id, $table)
{
    $db = new Database();
    for($i=0; $i<count($arr_id );$i++)
    {
        $req = $db->query("update ".$table. " set video_user = '', video_time = 0 where res_id = ?",array($arr_id[$i]));

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
