<?php
$confirm = false;
$etapes = array('form');
$frm_width='650px';
$frm_height = 'auto';
require("modules/entities/entities_tables.php");
require_once("modules/entities/class/EntityControler.php");
require_once('modules/entities/class/class_manage_entities.php');;
require_once "modules" . DIRECTORY_SEPARATOR . "avis" . DIRECTORY_SEPARATOR
			. "class" . DIRECTORY_SEPARATOR
			. "avis_controler.php";

 function get_form_txt($values, $path_manage_action,  $id_action, $table, $module, $coll_id, $mode )
 {
    require_once('apps/' . $_SESSION['config']['app_id'] . '/class/class_chrono.php');
    $cr7 = new chrono();

    require_once 'apps/maarch_entreprise/definition_mail_categories.php';
    
 	$res_id=$values[0];

    $data = get_general_data($coll_id, $res_id, 'minimal');
    //print_r($data);
 	$avis = new avis_controler();
    $ent = new entity();
    $entity_ctrl = new EntityControler();
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
        $frm_str .='<b>'._RECOMMENDATION_LIMIT_DATE.':</b><br/>';
        $frm_str .= '<input name="recommendation_limit_date_tr" type="text" '
            . 'id="recommendation_limit_date_tr" value="" placeholder="JJ-MM-AAAA" onfocus="checkRealDateAvis();" onChange="checkRealDateAvis();"  onclick="clear_error(\'frm_error_'
            . $actionId . '\');showCalender(this);"  onblur="document.getElementById(\'recommendation_limit_date\').value=document.getElementById(\'recommendation_limit_date_tr\').value;"/>';
        $frm_str .='<br/>';
        $frm_str .='<br/><b>'._RECOMMENDATION_NOTE.':</b><br/>';
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

        $frm_str .= '<textarea style="width:98%;height:60px;resize:none;" name="notes"  id="notes" onblur="document.getElementById(\'note_content_to_users\').value=document.getElementById(\'notes\').value.replace(/[\n]/gi, \'##\' );"></textarea>';
        //var_dump($allEntitiesTree);
        $frm_str .= '<hr />';
        $frm_str .= '<div class="error" id="divError" name="divError"></div>';
                $frm_str .= '<div style="text-align:center;">';
                $frm_str .= $avis->getListPopup($res_id, $coll_id, true,'AVIS_CIRCUIT');
      
                $frm_str .='</div>';
        $frm_str .='<div id="form2" style="border:none;">';
        $frm_str .= '<form name="frm_redirect_dep" id="frm_redirect_dep" method="post" class="forms" action="#">';
        $frm_str .= '<input type="hidden" name="chosen_action" id="chosen_action" value="end_action" />';
        $frm_str .= '<input type="hidden" name="note_content_to_users" id="note_content_to_users" />';
        $frm_str .= '<input type="hidden" name="recommendation_limit_date" id="recommendation_limit_date" />';
        $frm_str .= '<input type="hidden" name="doc_date" id="doc_date" value ="'.$data['doc_date'].'"/>';
        $frm_str .= '<input type="hidden" name="process_limit_date" id="process_limit_date" value ="'.$data['process_limit_date'].'" />';
            $frm_str .='</form>';
        $frm_str .='</div>';
    $frm_str .='<hr />';

    $frm_str .='<div align="center">';
        $frm_str .=' <input type="button" name="redirect_dep" value="'._VALIDATE.'" id="redirect_dep" class="button" onclick="saveAvisWorkflowPopup(\''.$res_id.'\', \''.$coll_id.'\', \'tab_avisSetWorkflowPopup\', \'N\');valid_action_form( \'frm_redirect_dep\', \''.$path_manage_action.'\', \''. $id_action.'\', \''.$values_str.'\', \''.$table.'\', \''.$module.'\', \''.$coll_id.'\', \''.$mode.'\');" />';
        $frm_str .=' <input type="button" name="cancel" id="cancel" class="button"  value="'._CANCEL.'" onclick="pile_actions.action_pop();destroyModal(\'modal_'.$id_action.'\');"/>';
    $frm_str .='</div>';
    return addslashes($frm_str);
 }

 function check_form($form_id,$values)
 {
	 //var_dump($values);
    $recommendation_limit_date = get_value_fields($values, 'recommendation_limit_date');
    $note_content_to_users = get_value_fields($values, 'note_content_to_users');
    
    if($recommendation_limit_date == null || $recommendation_limit_date == ''){
        $_SESSION['action_error'] = _RECOMMENDATION_LIMIT_DATE. " " . _MANDATORY;
        return false;
    } else if ($note_content_to_users == null || $note_content_to_users == '') {
		$_SESSION['action_error'] = _NOTE. " " . _MANDATORY;
		return false;
	}
    

    $avis = new avis_controler();
	$curr_avis_wf = $avis->getWorkflow($_SESSION['doc_id'], $_SESSION['current_basket']['coll_id'], 'AVIS_CIRCUIT');

	if (count($curr_avis_wf['avis']) == 0){
		$_SESSION['action_error'] = _AVIS_WORKFLOW. " " . _MANDATORY;
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
    require_once('modules/avis/class/avis_controler.php');
    $note = new notes_controler();


    $db = new Database();
    
    $formValues = array();
    for($i=0; $i<count($values_form); $i++) {
        $formValue = $values_form[$i];
        $id = $formValue['ID'];
        $value = $formValue['VALUE'];
        $formValues[$id] = $value;
    }
    
        
        # save note
        if($formValues['note_content_to_users'] != ''){
            //Add notes
            $userIdTypist = $_SESSION['user']['UserId'];
            $content_note = $formValues['note_content_to_users'];
            $content_note = str_replace("##", "\n", $content_note);
            $content_note = str_replace(";", ".", $content_note);
            $content_note = str_replace("--", "-", $content_note);
            $content_note = $content_note;
            $content_note = '[' . _TO_AVIS . '] ' . $content_note;
            $note->addNote($res_id, $coll_id, $content_note);
            
        }
         
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
