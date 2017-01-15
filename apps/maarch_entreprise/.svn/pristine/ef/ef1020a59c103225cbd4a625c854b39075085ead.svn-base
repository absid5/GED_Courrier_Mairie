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
* @brief   Action : simple confirm
*
* Open a modal box to confirm a status modification. Used by the core (manage_action.php page).
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
$etapes = array('form');
$frm_width='285px';
$frm_height = 'auto';

 function get_form_txt($values, $path_manage_action,  $id_action, $table, $module, $coll_id, $mode )
 {

    $res_id=$values[0];
    $db = new Database();
    $labelAction = '';
    if ($id_action <> '') {
        $stmt = $db->query("select label_action from actions where id = ?",array($id_action));
        $resAction = $stmt->fetchObject();
        $labelAction = functions::show_string($resAction->label_action);
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
  
    require 'modules/templates/class/templates_controler.php';
    $templatesControler = new templates_controler();
    $templates = array();

        if ($destination <> '') {
            $templates = $templatesControler->getAllTemplatesForProcess($destination);
        } else {
            $templates = $templatesControler->getAllTemplatesForSelect();
        }
        $frm_str .='<center style="font-size:15px;">'._ACTION_CONFIRM.'<br/><br/><b>'.$labelAction.' ?</b></center><br/>';
        $frm_str .='<b>'._PROCESS_NOTES.':</b><br/>';
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

        $frm_str .= '<textarea placeholder="motif de l\'action (optionnel) ..." style="width:98%;height:60px;resize:none;" name="notes"  id="notes" onblur="document.getElementById(\'note_content_to_users\').value=document.getElementById(\'notes\').value;"></textarea>';
        $frm_str .='<div id="form2" style="border:none;">';
        $frm_str .= '<form name="frm_redirect_dep" id="frm_redirect_dep" method="post" class="forms" action="#">';
        $frm_str .= '<input type="hidden" name="chosen_action" id="chosen_action" value="end_action" />';
        $frm_str .= '<input type="hidden" name="note_content_to_users" id="note_content_to_users" />';
            $frm_str .='</form>';
        $frm_str .='</div>';

    $frm_str .='<div align="center">';
        $frm_str .=' <input type="button" name="redirect_dep" value="'._VALIDATE.'" id="redirect_dep" class="button" onclick="valid_action_form( \'frm_redirect_dep\', \''.$path_manage_action.'\', \''. $id_action.'\', \''.$values_str.'\', \''.$table.'\', \''.$module.'\', \''.$coll_id.'\', \''.$mode.'\');" />';
        $frm_str .=' <input type="button" name="cancel" id="cancel" class="button"  value="'._CANCEL.'" onclick="pile_actions.action_pop();destroyModal(\'modal_'.$id_action.'\');"/>';
    $frm_str .='</div>';
    return addslashes($frm_str);
 }

 function check_form($form_id,$values)
 {
    return true;
 }

function manage_form($arr_id, $history, $id_action, $label_action, $status, $coll_id, $table, $values_form )
{
    
    if(empty($values_form) || count($arr_id) < 1) 
        return false;

    require_once('modules/notes/class/notes_controler.php');
    $note = new notes_controler();


    $db = new Database();
    
    $formValues = array();
    for($i=0; $i<count($values_form); $i++) {
        $formValue = $values_form[$i];
        $id = $formValue['ID'];
        $value = $formValue['VALUE'];
        $formValues[$id] = $value;
    }
    
    $db = new Database();
    $_SESSION['action_error'] = '';
    $result = '';
    $coll_id = $_SESSION['current_basket']['coll_id'];
    $res_id = $arr_id[0];
    require_once("core/class/class_security.php");
    $sec = new security();
    $table = $sec->retrieve_table_from_coll($coll_id);

    # save note
        if($formValues['note_content_to_users'] != ''){
            //Add notes
            $nb_avis = $sequence +1;
            $userIdTypist = $_SESSION['user']['UserId'];
            $content_note = $formValues['note_content_to_users'];
            $content_note = str_replace(";", ".", $content_note);
            $content_note = str_replace("--", "-", $content_note);
            $content_note = $content_note;
            $note->addNote($res_id, $coll_id, $content_note);
            
        }
    return array('result' => $res_id.'#', 'history_msg' => '');
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
