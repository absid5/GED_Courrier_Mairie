<?php
$confirm = false;
$etapes = array('form');
$frm_width='355px';
$frm_height = 'auto';
require('modules/entities/entities_tables.php');
require_once('modules/entities/class/class_manage_listdiff.php');
function get_form_txt(
    $values,
    $path_manage_action,
    $id_action,
    $table,
    $module,
    $coll_id,
    $mode
)
{
    $frm_str = '<div id="frm_error_' . $id_action . '" class="error"></div>';
    $frm_str .= '<h2 class="title">' . _ADD_COPY_FOR_DOC . ' ' . _NUM;
    $values_str = '';
    for ($i=0; $i < count($values);$i++) {
        $values_str .= $values[$i].', ';
    }
    $values_str = preg_replace('/, $/', '', $values_str);
    $frm_str .= $values_str;
    $frm_str .= '</h2><br/><br/>';

    $diff_list = new diffusion_list();
    $_SESSION['process']['diff_list'] = $diff_list->get_listinstance($values_str);
    $frm_str .= '<div id="diff_list_div_from_action">';
        $frm_str .= '<div>';
            if (count($_SESSION['process']['diff_list']['copy']['users']) == 0 && count($_SESSION['process']['diff_list']['copy']['entities']) == 0) {
                $frm_str .= _NO_COPY;
            } else {
                $frm_str .= '<table cellpadding="0" cellspacing="0" border="0" class="listing3">';
                $color = ' class="col"';
                for ($i=0;$i<count($_SESSION['process']['diff_list']['copy']['entities']);$i++) {
                    if ($color == ' class="col"') {
                        $color = '';
                    } else {
                        $color = ' class="col"';
                    }
                    $frm_str .= '<tr '.$color.' >';
                    $frm_str .= '<td><i class="fa fa-sitemap fa-2x" title="'._ENTITY.'"></i></td>';
                    $frm_str .= '<td >'.$_SESSION['process']['diff_list']['copy']['entities'][$i]['entity_id'].'</td>';
                    $frm_str .= '<td colspan="2">'.$_SESSION['process']['diff_list']['copy']['entities'][$i]['entity_label'].'</td>';
                    $frm_str .= '</tr>';
                }
                for ($i=0;$i<count($_SESSION['process']['diff_list']['copy']['users']);$i++) {
                    if ($color == ' class="col"') {
                        $color = '';
                    } else {
                        $color = ' class="col"';
                    }
                    $frm_str .= '<tr '.$color.' >';
                        $frm_str .= '<td><i class="fa fa-user fa-2x" title="'._USER.'"></i></td>';
                        $frm_str .= '<td >'.$_SESSION['process']['diff_list']['copy']['users'][$i]['firstname'].'</td>';
                        $frm_str .= '<td >'.$_SESSION['process']['diff_list']['copy']['users'][$i]['lastname'].'</td>';
                        $frm_str .= '<td>'.$_SESSION['process']['diff_list']['copy']['users'][$i]['entity_label'].'</td>';
                    $frm_str .= '</tr>';
                }
                $frm_str .= '</table>';
            }
        $frm_str .= '</div>';
    $frm_str .= '</div>';
        
    $frm_str .= '<a href="#" onclick="window.open(\''.$_SESSION['config']['businessappurl']
              . 'index.php?display=true&module=entities&page=manage_listinstance'
              . '&origin=process&only_cc&no_delete\', \'\', \'scrollbars=yes,menubar=no,'
              . 'toolbar=no,status=no,resizable=yes,width=1024,height=650,location=no\');" '
              . 'title="' . _ADD_COPIES . '"><i class="fa fa-edit fa-2x" title="'._ADD_COPIES.'"></i>'
              . _ADD_COPIES . '</a>';
    $frm_str .='<hr />';
    $frm_str .='<div align="center">';
        $frm_str .= '<form name="frm_put_in_copy" id="frm_put_in_copy" method="post" class="forms" action="#">';
            $frm_str .= '<input type="hidden" name="chosen_action" id="chosen_action" value="end_action" />';
            $frm_str .=' <input type="button" name="put_in_copy" id="put_in_copy" value="'
                     . _VALIDATE_PUT_IN_COPY . '" class="button" '
                     . 'onclick="valid_action_form( \'frm_put_in_copy\', \''
                     . $path_manage_action . '\', \''. $id_action . '\', \'' 
                     . $values_str . '\', \'' . $table . '\', \''. $module . '\', \'' 
                     . $coll_id . '\', \'' . $mode . '\');"  />&nbsp;';
            $frm_str .='<input type="button" name="cancel" id="cancel" class="button"  value="'
                      . _CANCEL . '" onclick="destroyModal(\'modal_' . $id_action . '\');"/>';
        $frm_str .='</form>';
    $frm_str .='</div>';
    return addslashes($frm_str);
}

function check_form($form_id, $values)
{
    return true;
}

function manage_form(
    $arr_id,
    $history,
    $id_action,
    $label_action,
    $status,
    $coll_id,
    $table,
    $values_form
)
{
    //var_dump($_SESSION['process']);
    $list = new diffusion_list();
    $params = array(
        'mode'=> 'listinstance', 
        'table' => $_SESSION['tablename']['ent_listinstance'], 
        'coll_id' => $coll_id, 
        'res_id' => $arr_id[0], 
        'user_id' => $_SESSION['user']['UserId'], 
        'concat_list' => true, 
        'only_cc' => true
    );
    $msg = _ADD_COPY_FOR_DOC . ' ' . $arr_id[0];
    $list->load_list_db($_SESSION['process']['diff_list'], $params);
    return array('result' => $arr_id[0], 'history_msg' => $msg);
}
