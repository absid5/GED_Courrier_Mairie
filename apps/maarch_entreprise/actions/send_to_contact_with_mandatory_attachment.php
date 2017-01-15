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
$_SESSION['id_action'] = $id_action;

if($_SESSION['features']['send_to_contact_with_mandatory_attachment'] == true){
    require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_request.php');
    $db = new Database();
    $stmt = $db->query("SELECT count(res_id_master) FROM res_attachments WHERE res_id_master = ?", array($_POST['values']));
    $nbAttachments = $stmt->fetchObject(); 
    $nbAttachments = json_decode(json_encode($nbAttachments), True);

    if($nbAttachments['count'] == 0){
        $confirm = false;
        $etapes = array('no_attachment');

    }elseif($nbAttachments['count'] != 0){
        $confirm = false; 
        $etapes = array('form');
    }
}elseif($_SESSION['features']['send_to_contact_with_mandatory_attachment'] == false){
        $etapes = array('form'); 
}




$confirm = false;

$frm_width='800px';
$frm_height = 'auto';


function get_attachment_txt($values, $path_manage_action,  $id_action, $table, $module, $coll_id, $mode){
    var_dump('ok get_attachment_txt');
}

 function get_form_txt($values, $path_manage_action,  $id_action, $table, $module, $coll_id, $mode )
 {


$module='apps';
//var_dump($_REQUEST['values']);
$mode='add';






$parameters = '&coll_id='.$_REQUEST['coll_id'].'&size=full';
        $frm_str .='<iframe name="form_mail" id="form_mail" src="'. $_SESSION['config']['businessappurl'].'index.php?display=true&module=sendmail&page=mail_form_to_contact&identifier='.$_REQUEST['values'].'&origin=document&coll_id=letterbox_coll&mode='.$mode.$parameters.'" frameborder="0" width="100%" style="height:540px;padding:0px;overflow-x:hidden;overflow-y: auto;"></iframe>';

$mode='page';
 
        $frm_str .='<div id="form2" style="border:none;">';
        $frm_str .= '<form name="frm_redirect_dep" id="frm_redirect_dep" method="post" class="forms" action="#">';
        $frm_str .= '<input type="hidden" name="chosen_action" id="chosen_action" value="end_action" />';
        $frm_str .= '<input type="hidden" name="note_content_to_users" id="note_content_to_users" />';
            $frm_str .='</form>';
        $frm_str .='</div>';
        $frm_str .='<div align="center">';



        $frm_str .=' <input type="button" name="redirect_dep" value="'._VALIDATE.'" id="redirect_dep" class="button" onclick="window.frames.form_mail.document.getElementsByName(\'valid\')[0].click();" />';
        // $frm_str .=' <input type="button" name="storage" class="button" id="storage" onclick="alert(\'TEST\')";/>';
        $frm_str .=' <input style="display:none;" type="button" name="storage" class="button" id="storage" onclick="valid_action_form( \'frm_redirect_dep\', \''.$path_manage_action.'\', \''. $id_action.'\', \''.$_REQUEST['values'].'\', \''.$table.'\', \''.$module.'\', \''.$coll_id.'\', \''.$mode.'\');";/>';
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
