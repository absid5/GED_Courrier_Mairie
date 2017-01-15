<?php

$confirm    = false;
$etapes     = ['form', 'empty_error'];
$frm_width  = '355px';
$frm_height = 'auto';

function get_form_txt($values, $path_manage_action, $id_action, $table, $module, $coll_id, $mode)
{
    require('modules/entities/entities_tables.php');
    require_once('modules/entities/class/EntityControler.php');
    require_once('apps/' . $_SESSION['config']['app_id'] . '/class/class_chrono.php');

    $db                 = new Database();
    $cr7                = new chrono();
    $entity_ctrl        = new EntityControler();

    $servicesCompare    = [];
    $labelAction        = '';

    if ($id_action != '') {
        $stmt = $db->query('select label_action from actions where id = ?', [$id_action]);
        $resAction = $stmt->fetchObject();
        $labelAction = functions::show_string($resAction->label_action);
    }

    $frm_str = '<div id="frm_error_'.$id_action.'" class="error"></div>';
    if ($labelAction != '') {
        $frm_str .= '<h2 class="title">' . $labelAction . ' ' . _NUM;
    } else {
        $frm_str .= '<h2 class="title">' . _REDIRECT_MAIL . ' ' ._NUM;
    }
    $values_str = '';
    if(empty($_SESSION['stockCheckbox'])) {
        for($i=0; $i < count($values); $i++)
        {
            $values_str .= $values[$i] . ', ';
        }
    } else {

        for($i=0; $i < count($_SESSION['stockCheckbox']); $i++)
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

    $EntitiesIdExclusion = [];
    $entities = $entity_ctrl->getAllEntities();
    $countEntities = count($entities);

    for ($cptAllEnt = 0;$cptAllEnt<$countEntities;$cptAllEnt++) {
        if (!is_integer(array_search($entities[$cptAllEnt]->__get('entity_id'), $servicesCompare))) {
            array_push($EntitiesIdExclusion, $entities[$cptAllEnt]->__get('entity_id'));
        }
    }

    $templates = $templatesControler->getAllTemplatesForSelect();
    $frm_str .='<br/><b>'. _NOTES .':</b><br/>';
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

    $frm_str .= '<textarea style="width:98%;height:60px;resize:none;" name="notes"  id="notes" onblur="document.getElementById(\'note_content_to_users\').value = document.getElementById(\'notes\').value;"></textarea>';
    $frm_str .= '<div id="form2" style="border:none;">';
    $frm_str .= '<form name="frm_redirect_dep" id="frm_redirect_dep" method="post" class="forms" action="#">';
    $frm_str .= '<input type="hidden" name="chosen_action" id="chosen_action" value="end_action" />';
    $frm_str .= '<input type="hidden" name="note_content_to_users" id="note_content_to_users" />';
    $frm_str .= '</form>';
    $frm_str .= '</div>';
    $frm_str .= '<hr />';

    $frm_str .= '<div align="center">';
    $frm_str .= ' <input type="button" name="redirect_dep" value="'._VALIDATE.'" id="redirect_dep" class="button" onclick="valid_action_form( \'frm_redirect_dep\', \''.$path_manage_action.'\', \''. $id_action.'\', \''.$values_str.'\', \''.$table.'\', \''.$module.'\', \''.$coll_id.'\', \''.$mode.'\');" />';
    $frm_str .= ' <input type="button" name="cancel" id="cancel" class="button"  value="'._CANCEL.'" onclick="pile_actions.action_pop();destroyModal(\'modal_'.$id_action.'\');"/>';
    $frm_str .= '</div>';

    return addslashes($frm_str);
}

function check_form($form_id, $values)
{
    return true;
}

function manage_form($arr_id, $history, $id_action, $label_action, $status, $coll_id, $table, $values_form )
{
    if(empty($values_form) || count($arr_id) < 1)
        return false;

    require_once('modules/notes/class/notes_controler.php');
    $note = new notes_controler();

    $coll_id    = $_SESSION['current_basket']['coll_id'];
    $res_id     = $arr_id[0];

    $formValues = [];
    for($i = 0; $i < count($values_form); $i++) {
        $id = $values_form[$i]['ID'];
        $formValues[$id] = $values_form[$i]['VALUE'];
    }

    # save note
    if($formValues['note_content_to_users'] != ''){
        //Add notes
        $note->addNote($res_id, $coll_id, $formValues['note_content_to_users']);
    }

    return array('result' => $res_id.'#', 'history_msg' => '');
}

function manage_empty_error($arr_id, $history, $id_action, $label_action, $status)
{
    $db = new Database();
    $_SESSION['action_error'] = '';
    $res_id = $arr_id[0];

    $db->query('UPDATE listinstance SET process_date = NULL WHERE res_id = ? AND difflist_type = ?', [$res_id, 'VISA_CIRCUIT']);

    return array('result' => $res_id.'#', 'history_msg' => $label_action);
}
