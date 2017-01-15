<?php
/*
*    Copyright 2008-2015 Maarch
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
* @brief   Manage core actions
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

$db = new Database();
$core = new core_tools();
$core->load_lang();
$res_action = array();

/*
* Puts the values that are in a string into an array.
* $$ field separator, # field_name / value separator
*
* @param $val string Values to split
* @return array Values in array
*/
function get_values_in_array($val)
{
    $tab = explode('$$',$val);
    $values = array();
    for($i=0; $i<count($tab);$i++)
    {
        $tmp = explode('#', $tab[$i]);

        $val_tmp=array();
        for($idiese=1;$idiese<count($tmp);$idiese++){
                $val_tmp[]=$tmp[$idiese];
            }
            $valeurDiese = implode("#",$val_tmp);
        if(isset($tmp[1]))
        {
            array_push($values, array('ID' => $tmp[0], 'VALUE' => $valeurDiese));
        }
    }
    return $values;
}

// Form validation
if($_POST['req'] == 'valid_form' && !empty($_POST['action_id']) && isset($_POST['action_id']) && !empty($_POST['form_to_check'])&& isset($_POST['form_to_check']))
{

    $id_action = $_POST['action_id'];
    // Gets the action informations from the database
    $stmt = $db->query("select * from ".$_SESSION['tablename']['actions']." where id = ?", array($id_action));

    if($stmt->rowCount() < 1)
    {
        $_SESSION['action_error'] = _ACTION_NOT_IN_DB;
        echo "{status : 5, error_txt : '".addslashes(functions::xssafe($_SESSION['action_error']))."'}";
        exit();
    }

    $res = $stmt->fetchObject();
    $label_action = $res->label_action;
    $status = $res->id_status;
    $action_page = $res->action_page;
    $bool_history = $res->history;
    $create_id = $res->create_id;

        //No script defined for this action
    if($action_page == '')
    {
        $_SESSION['action_error'] = _ACTION_NOT_IN_DB;
        echo "{status : 5, error_txt : '".addslashes(functions::xssafe($_SESSION['action_error']))."'}";
        exit();
    }
    $custom_path = '';
    $path_action_page = $core->get_path_action_page($action_page);

    if(isset($_SESSION['custom_override_id']) && !empty($_SESSION['custom_override_id']))
    {
        $custom_path = 'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.$path_action_page;
    }
    if($custom_path <> '' && file_exists($_SESSION['config']['corepath'].$custom_path))
    {
        include($custom_path);
    }
    else
    {
        if(file_exists($path_action_page))
        {
            include($path_action_page);
        }
        else
        {
            // Invalid path to script
            $_SESSION['action_error'] = $label_action.' '._ACTION_PAGE_MISSING;
            echo "{status : 8, error_txt: '".addslashes(functions::xssafe($_SESSION['action_error']))."'}";
            exit();
        }
    }


    $frm_error = check_form(trim($_POST['form_to_check']),get_values_in_array($_POST['form_values']));
    if($frm_error == false)
    {
        echo "{status : 1, error_txt : '".addslashes(functions::xssafe($_SESSION['action_error']))."'}";
        exit();
    }
    else
    {
        if($create_id == 'N')
        {
            echo "{status : 0, error_txt : '".addslashes(functions::xssafe($_SESSION['action_error']))."', page_result : '', manage_form_now : false}";
        }
        else
        {
            echo "{status : 0, error_txt : '".addslashes(functions::xssafe($_SESSION['action_error']))."', page_result : '', manage_form_now : true}";
        }
        exit();
    }
}
elseif(trim($_POST['req']) == 'change_status' && !empty($_POST['values']) && !empty($_POST['new_status']) && !empty($_POST['table']))
{
    $stmt = $db->query("select id from status where id = ?", array($_POST['new_status']));
    $lineStatus = $stmt->fetchObject();
    if ($lineStatus->id <> '') {
        $arr_id = explode(',', $_POST['values']);
        $result = '';
        for ($i=0; $i<count($arr_id );$i++) {
            $arr_id[$i] = str_replace('#', '', $arr_id[$i]);
            $result .= $arr_id[$i].'#';
            if (trim($_POST['new_status']) <> '') {
                if ($_POST['table'] == 'folders') {
                    $query_str = "update " . $_POST['table'] 
                        .  " set status = ? where folders_system_id = ?";
                } else {
                    $query_str = "update " . $_POST['table'] 
                        . " set status = ? where res_id = ?";
                }
                $stmt = $db->query($query_str, array($_POST['new_status'], $arr_id[$i]));
                if (!$stmt) {
                    $_SESSION['action_error'] = _SQL_ERROR.' : '.$query_str;
                    echo "{status : 1, error_txt : '".addslashes(_ERROR_WITH_STATUS." ".functions::xssafe($query_str))."'}";
                    exit();
                }
            }
        }
        echo "{status : 0, error_txt : '".addslashes(_STATUS_UPDATED.' : '.functions::xssafe($_POST['new_status']))."'}";
        exit();
    } else {
        echo "{status : 0, error_txt : '".addslashes(_STATUS_NOT_EXISTS.' : '.functions::xssafe($_POST['new_status']))."'}";
        exit();
    }
}
// Post variables error
else if(empty($_POST['values']) || !isset($_POST['action_id']) || empty($_POST['action_id']) ||
($_POST['mode'] <> 'mass' && $_POST['mode'] <> 'page')  || empty($_POST['table'])
|| empty($_POST['coll_id']) || empty($_POST['module']) || ($_POST['req'] <> 'first_request' && $_POST['req'] <> 'second_request' && $_POST['req'] <> 'change_status'))
{
    $tmp = 'values : '.$_POST['values'].', action_id : '.$_POST['action_id'].', mode : '. $_POST['mode'].', table : '.$_POST['table'].', coll_id : '.$_POST['coll_id'].', module : '.$_POST['module'].', req : '.$_POST['req'];
    $_SESSION['action_error'] = $tmp._AJAX_PARAM_ERROR;
    echo "{status : 1, error_txt : '".functions::xssafe($id_action).addslashes(functions::xssafe($_SESSION['action_error']))."'}";
    exit();
} else {
    // Puts the res_id into an array
    $arr_id = explode(',', $_POST['values']);
    $id_action = $_POST['action_id'];
    // Gets the action informations from the database
    $stmt = $db->query("select * from ".$_SESSION['tablename']['actions']." where id = ?", array($id_action));
    if ($stmt->rowCount() < 1) {
        $_SESSION['action_error'] = _ACTION_NOT_IN_DB;
        echo "{status : 5, error_txt : '".addslashes(functions::xssafe($_SESSION['action_error']))."'}";
        exit();
    }

    $res = $stmt->fetchObject();
    $label_action = $res->label_action;
    $status = $res->id_status;
    $action_page = $res->action_page;
    $bool_history = $res->history;

    //No script defined for this action
    if($action_page == '')
    {
        //If second request : Error
        if($_POST['req'] == 'second_request')
        {
            $_SESSION['action_error'] = _ACTION_NOT_IN_DB;
            echo "{status : 5, error_txt : '".addslashes(functions::xssafe($_SESSION['action_error']))."'}";
            exit();
        }

        //If no status defined in the action file , error
        if($status == '' || $status == 'NONE')
        {
            $_SESSION['action_error'] = $label_action.' : '._ERROR_PARAM_ACTION;
            echo "{status : 6, error_txt : '".functions::xssafe(addslashes($_SESSION['action_error']))."'}";
            exit();
        }
        $stmt = $db->query("select id from status where id = ?", array($status));
        $lineStatus = $stmt->fetchObject();
        if ($lineStatus->id <> '') {
            // Update the status
            $result = '';
            for ($i=0;$i<count($arr_id );$i++) {
                $arr_id[$i] = str_replace('#', '', $arr_id[$i]);
                $result .= $arr_id[$i].'#';
                if (trim($status) <> '') {
                    if ($_POST['table'] == 'folders') {
                        $query_str = "update " . $_POST['table'] 
                            .  " set status = ? where folders_system_id = ?";
                    } else {
                        $query_str = "update " . $_POST['table'] 
                            .  " set status = ? where res_id = ?";
                    }
                    $stmt = $db->query($query_str, array($status, $arr_id[$i]));
                    if (!$stmt) {
                        $_SESSION['action_error'] = _SQL_ERROR . ' : ' . $query_str;
                        echo "{status : 7, error_txt : '" . addslashes(functions::xssafe($label_action) 
                            . ' : ' . functions::xssafe($_SESSION['action_error'])) . "'}";
                        exit();
                    }
                }
            }
        }
        $res_action = array('result' => $result, 'history_msg' => '');
        $_SESSION['action_error'] = _ACTION_DONE.' : '.$label_action;
        echo "{status : 0, error_txt : '".addslashes($_SESSION['action_error']).", status : "
            .functions::xssafe($status).", ".functions::xssafe($_POST['values'])."', page_result : ''}";
    }
    // There is a script for the action
    else
    {
        $custom_path = '';
        $path_action_page = $core->get_path_action_page($action_page);

        if(isset($_SESSION['custom_override_id']) && !empty($_SESSION['custom_override_id']))
        {
            $custom_path = 'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.$path_action_page;
        }
        if($custom_path <> '' && file_exists($_SESSION['config']['corepath'].$custom_path))
        {
            include($custom_path);
        }
        else
        {
            if(file_exists($path_action_page))
            {
                include($path_action_page);
            }
            else
            {
                // Invalid path to script
                $_SESSION['action_error'] = $label_action.' '._ACTION_PAGE_MISSING;
                echo "{status : 8, error_txt: '".addslashes(functions::xssafe($_SESSION['action_error']))."'}";
                exit();
            }
        }

        if($_POST['req'] == 'first_request' && in_array('form', $etapes))
        {
            $frm_test = get_form_txt($arr_id, $_SESSION['config']['businessappurl'].'index.php?display=true&page=manage_action&module=core', $id_action, $_POST['table'],$_POST['module'], $_POST['coll_id'],  $_POST['mode'] );
            echo "{status : 3, form_content : '".$frm_test."', height : '".$frm_height."', width : '".$frm_width."', 'mode_frm' : '".$mode_form."', 'action_status' : '".functions::xssafe($status)."'}";
            exit();
        }
        elseif( $_POST['req'] == 'first_request' && in_array('no_attachment',$etapes))
        {
            echo "{status : 3, form_content : '<div class=\"h2_title\">" . addslashes(_ADD_ATTACHMENT_TO_SEND_TO_CONTACT) .
                "</div><p class=\"buttons\"><input type=\"button\" class=\"button\" value=\""._CANCEL."\" onclick=\"destroyModal(\'modal_" .$id_action . "\')\" id=\"submit\" name=\"submit\"></p>', height : '250px', width : '300px', 'mode_frm' : '', validate : 'OK', 'action_status' : '".functions::xssafe($status)."'}";
            exit();
        }
		elseif( $_POST['req'] == 'first_request' && $error_visa == true)
        {
            echo "{status : 4, error : '".addslashes(_NO_VISA)."', validate : 'OK', 'action_status' : '".functions::xssafe($status)."'}";
            exit();
        }
		elseif( $_POST['req'] == 'first_request' && $error_visa_response_project == true)
        {
            echo "{status : 3, form_content : '<div class=\"h2_title\">" . addslashes($visa->errorMessageVisa) .
                "</div><p class=\"buttons\"><input type=\"button\" onclick=\"destroyModal(\'modal_" .$id_action . "\')\" class=\"button\" value=\"OK\" id=\"submit\" name=\"submit\"></p>', height : 'auto', width : 'auto', 'mode_frm' : '', validate : 'OK', 'action_status' : '".functions::xssafe($status)."'}";
            exit();
        }
        elseif( $_POST['req'] == 'first_request' && $error_visa_workflow == true)
        {
            echo "{status : 4, error : '".addslashes(_NO_NEXT_STEP_VISA)."', validate : 'OK', 'action_status' : '".functions::xssafe($status)."'}";
            exit();
        }
        elseif( $_POST['req'] == 'first_request' && $confirm == true)
        {
            echo "{status : 2, confirm_content : '".addslashes(_ACTION_CONFIRM." ".functions::xssafe($label_action))."', validate : '"._VALIDATE."', cancel : '"._CANCEL."', label_action : '".addslashes(functions::xssafe($label_action))."', 'action_status' : '".functions::xssafe($status)."'}";
            exit();
        }
        elseif( $_POST['req'] == 'first_request' && $confirm == false && $action_page == 'close_mail_with_attachment')
        {
            echo "{status : 3, form_content : '<div class=\"h2_title\">" . addslashes(_ADD_ATTACHMENT_OR_NOTE) .
                "</div><p class=\"buttons\"><input type=\"button\" class=\"button\" value=\""._CANCEL."\" onclick=\"destroyModal(\'modal_" .$id_action . "\')\" id=\"submit\" name=\"submit\"></p>', height : '250px', width : '300px', 'mode_frm' : '', validate : 'OK', 'action_status' : '".functions::xssafe($status)."'}";
            exit();
        }
        else
        {
            if($confirm == false)
            {
                $_SESSION['action_error'] = $label_action.' : '._ERROR_SCRIPT;
            }
            for($i=0; $i<count($etapes);$i++)
            {
                if($etapes[$i] <> 'status')
                {
                    if( function_exists('manage_'.$etapes[$i]) )
                    {
                        try
                        {
                            if($_POST['req'] == 'second_request')
                            {
                                $res_action = call_user_func('manage_'.$etapes[$i],$arr_id, $bool_history, $id_action, $label_action, $status, $_POST['coll_id'], $_POST['table'], get_values_in_array($_POST['form_values'])  );
                            }
                            else
                            {
                                $res_action = call_user_func('manage_'.$etapes[$i],$arr_id, $bool_history, $id_action, $label_action, $status, $_POST['coll_id'], $_POST['table']);
                            }
                        }
                        catch(Exception $e)
                        {
                            echo "{status : 9, error_txt : '".addslashes(functions::xssafe($_SESSION['action_error']))."'}";
                            exit();
                        }
                    }
                    else
                    {
                        echo "{status : 9, error_txt : '".addslashes(functions::xssafe($_SESSION['action_error']))."'}";
                        exit();
                    }
                }
            }
            //print_r($res_action);
            if($res_action == false)
            {
                echo "{status : 9, error_txt : '".addslashes(functions::xssafe($_SESSION['action_error']))."'}";
                exit();
            }
            $comp = ", page_result  : ''";
            if(isset($res_action['page_result']) && !empty($res_action['page_result']))
            {
                $comp = ", page_result  : '".$res_action['page_result']."'";
            }
            if(isset($res_action['table_dest']) && !empty($res_action['table_dest']))
            {
                $comp .= ", table : '".$res_action['table_dest']."'";
            }
			
			
			if(isset($res_action['newResultId']) && !empty($res_action['newResultId']))
            {
                $comp .= ", newResultId : '".$res_action['newResultId']."'";
            }
			if(isset($res_action['action_status']) && !empty($res_action['action_status']))
            {
                $comp .= ", action_status : '".$res_action['action_status']."'";
            }
			
            $_SESSION['action_error'] = _ACTION_DONE.' : '.$label_action;
            echo "{status : 0, error_txt : '".addslashes(functions::xssafe($_SESSION['action_error']))."'".$comp.", result_id : '".$res_action['result']."'}";
        }
    }
    // Save action in history if needed
    if($bool_history=='Y')
    {
		$db = new Database();
        require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
        $hist = new history();
        $arr_res = explode('#', $res_action['result']);
        for($i=0; $i<count($arr_res );$i++)
        {
            if(!empty($arr_res[$i]))
            {
                $what = '';
                if (isset($_SESSION['current_basket']['id']) && !empty($_SESSION['current_basket']['id'])) {
                    $stmt = $db->query("SELECT basket_name FROM baskets WHERE basket_id = ?", array($_SESSION['current_basket']['id']));
                    while($data = $stmt->fetchObject()) {
                        $what = $data->basket_name;
                    }
                    $what .= ' : ';
                }
                //$what .= $label_action.'('._NUM.$arr_res[$i].') ';
                $what .= $label_action;
                if(isset($res_action['history_msg']) && !empty($res_action['history_msg']))
                {
                    $what .= $res_action['history_msg'];
                }
                $_SESSION['info_basket'] = $what . ' ';
                $_SESSION['info'] = $what . ' ';
                $_SESSION['cpt_info_basket'] = 0;
                $hist->add(
                    $_POST['table'],
                    $arr_res[$i],'ACTION#'.$id_action, $id_action,
                    $what, $_SESSION['config']['databasetype'], $_POST['module']);
            }
        }
    }

    exit();
}
