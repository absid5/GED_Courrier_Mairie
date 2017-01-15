<?php
$confirm = false;
$etapes = array('form');
$multipleEntities = false; 

if ($multipleEntities) {
    $frm_width='600px';
    $frm_height = '400px';
 } else {
    $frm_width='355px';
    $frm_height = '400px';
}    


require("modules/entities/entities_tables.php");

 function get_form_txt($values, $path_manage_action,  $id_action, $table, $module, $coll_id, $mode )
 {
    $multipleEntities = false; 
    
    $services = array();
    $db = new Database();

    //print_r($_SESSION['user']['redirect_groupbasket'][$_SESSION['current_basket']['id']][$id_action]['entities']);
    preg_match("'^ ,'",$_SESSION['user']['redirect_groupbasket'][$_SESSION['current_basket']['id']][$id_action]['entities'], $out);
    if(count($out[0]) == 1) {
        $_SESSION['user']['redirect_groupbasket'][$_SESSION['current_basket']['id']][$id_action]['entities'] = 
            substr(
                    $_SESSION['user']['redirect_groupbasket'][$_SESSION['current_basket']['id']][$id_action]['entities'], 
                    2, 
                    strlen($_SESSION['user']['redirect_groupbasket'][$_SESSION['current_basket']['id']][$id_action]['entities'])
                );
    }
    //print_r($_SESSION['user']['redirect_groupbasket'][$_SESSION['current_basket']['id']][$id_action]['entities']);
    if(!empty($_SESSION['user']['redirect_groupbasket'][$_SESSION['current_basket']['id']][$id_action]['entities']))
    {
        $stmt = $db->query("SELECT entity_id, entity_label FROM ".ENT_ENTITIES." WHERE entity_id in ("
            .$_SESSION['user']['redirect_groupbasket'][$_SESSION['current_basket']['id']][$id_action]['entities'].") 
            and enabled= 'Y' order by entity_label");
        while($res = $stmt->fetchObject())
        {
            array_push($services, array( 'ID' => $res->entity_id, 'LABEL' => functions::show_string($res->entity_label)));
        }
    }
    $users = array();
    if(!empty($_SESSION['user']['redirect_groupbasket'][$_SESSION['current_basket']['id']][$id_action]['users_entities']) )
    {
        $stmt = $db->query("SELECT distinct ue.user_id, u.lastname, u.firstname FROM ".ENT_USERS_ENTITIES." ue, "
            .$_SESSION['tablename']['users']." u WHERE ue.entity_id in ("
            .$_SESSION['user']['redirect_groupbasket'][$_SESSION['current_basket']['id']][$id_action]['users_entities'].") 
            and u.user_id = ue.user_id and (u.status = 'OK' or u.status = 'ABS') order by u.lastname asc");
        while($res = $stmt->fetchObject())
        {
            array_push($users, array( 'ID' => $res->user_id, 'NOM' => functions::show_string($res->lastname), "PRENOM" => functions::show_string($res->firstname)));
        }
    }

    //Init some vars
    $haveEntities = $haveUsers = false;
    if(!empty($_SESSION['user']['redirect_groupbasket'][$_SESSION['current_basket']['id']][$id_action]['entities'])) {
      $haveEntities = true;
    }
    if(!empty($_SESSION['user']['redirect_groupbasket'][$_SESSION['current_basket']['id']][$id_action]['users_entities'])) {
        $haveUsers = true;
    }
    
    //
    $frm_str = '<div id="frm_error_'.$id_action.'" class="error"></div>';
    $frm_str .= '<h2 class="title">'._REDIRECT_FOLDER.' '._NUM;
    $values_str = '';
    for($i=0; $i < count($values);$i++)
    {
        $values_str .= $values[$i].', ';
    }
    $values_str = preg_replace('/, $/', '', $values_str);
    $frm_str .= $values_str;
    $frm_str .= '</h2><br/><br/>';
   
    $frm_str .='<div id="form3">'; 
    $frm_str .= '<form name="frm_redirect" id="frm_redirect" method="post" class="forms" action="#">';
    $frm_str .= '<input type="hidden" name="chosen_action" id="chosen_action" value="end_action" />'; 
    
    if($haveEntities) {
        $frm_str .= '<hr />';
        $frm_str .= '<label for="redirect_dep"><b>'._REDIRECT_TO_OTHER_DEP.' :</b></label>';
        
        if ($multipleEntities === true) {
            $frm_str .='<table align="center" width="100%" border="0">'; 
            $frm_str .='<tr>'; 
            $frm_str .='<td colspan="3"><input type="radio" name="redirect_target" id="redirect_dep" class="check" value="department"></td>';
            $frm_str .='<td width="40%" align="center">'; 
            $frm_str .='<select name="entitieslist[]" id="entitieslist" size="7" '; 
            $frm_str .='onChange="document.getElementById(\'redirect_dep\').checked=true;" ';
            $frm_str .='ondblclick="moveclick($(entitieslist), $(entities_chosen));" multiple="multiple" >'; 
            for($i=0; $i < count($services); $i++) {
                $frm_str .='<option value="'.$services[$i]['ID'].'" >'.functions::show_string($services[$i]['LABEL']).'</option>';
            }
            $frm_str .='</select>'; 
            $frm_str .='<br/>';
            $frm_str .='</td>'; 
            $frm_str .='<td width="20%" align="center">'; 
            $frm_str .='<input type="button" class="button" value="'. _ADD.' &gt;&gt;" onclick="Move($(entitieslist), $(entities_chosen));" />'; 
            $frm_str .='<br />'; 
            $frm_str .='<br />'; 
            $frm_str .='<input type="button" class="button" value="&lt;&lt; '._REMOVE.'" onclick="Move($(entities_chosen), $(entitieslist));" />'; 
            $frm_str .='</td>'; 
            $frm_str .='<td width="40%" align="center">'; 
            $frm_str .='<select name="entities_chosen[]" id="entities_chosen" size="7" '; 
            $frm_str .='onChange="document.getElementById(\'redirect_dep\').checked=true;" ';
            $frm_str .='ondblclick="moveclick($(entities_chosen), $(entitieslist));" multiple="multiple">'; 
            $frm_str .='</select>'; 
            $frm_str .='<br/>'; 
            $frm_str .='</td>'; 
            $frm_str .='</tr>'; 
            $frm_str .='</table>'; 
        } else {
            $frm_str .='<p>';
            $frm_str .='<input type="radio" name="redirect_target" id="redirect_dep" class="check" value="department">&nbsp;';
            $frm_str .= '<select name="department" id="department" onChange="document.getElementById(\'redirect_dep\').checked=true;">';
            $frm_str .='<option value="">'._CHOOSE_DEPARTMENT.'</option>';
            for($i=0; $i < count($services); $i++) {
                 $frm_str .='<option value="'.$services[$i]['ID'].'" >'.functions::show_string($services[$i]['LABEL']).'</option>';
            }
            $frm_str .='</select>';
            $frm_str .='</p>';
        }
    }
    
    if($haveUsers) {
        $frm_str .='<hr />';
        $frm_str .='<label for="redirect_user"><b>'._REDIRECT_TO_USER.' :</b></label>';
        $frm_str .='<p>';
        $frm_str .='<input type="radio" name="redirect_target" id="redirect_user" class="check" value="user">&nbsp;';
        $frm_str .='<select name="user" id="user" onChange="document.getElementById(\'redirect_user\').checked=true;">';
        $frm_str .='<option value="">'._CHOOSE_USER2.'</option>';
        for($i=0; $i < count($users); $i++) {
            $frm_str .='<option value="'.$users[$i]['ID'].'">'.$users[$i]['NOM'].' '.$users[$i]['PRENOM'].'</option>';
        }
        $frm_str .='</select>';
        $frm_str .='</p>';
    }
    
    if($haveEntities || $haveUsers) {
        $frm_str .='<hr />';
        $frm_str .='<input type="checkbox" name="redirect_documents" id="redirect_documents" value="Y">'._REDIRECT_ALL_DOCUMENTS_IN_FOLDER;
    }
    
    $frm_str .='<hr />';
    $frm_str .='<div align="center">';
    if($haveEntities || $haveUsers) {
        $frm_str .=' <input type="button" name="redirect" value="'._REDIRECT
             .'" id="redirect" class="button" onclick="valid_action_form( \'frm_redirect\', \''
             .$path_manage_action.'\', \''. $id_action.'\', \''.$values_str.'\', \''.$table.'\', \''
             .$module.'\', \''.$coll_id.'\', \''.$mode.'\');" />&nbsp;';
    }
    $frm_str .='<input type="button" name="cancel" id="cancel" class="button"  value="'._CANCEL.'" onclick="destroyModal(\'modal_'.$id_action.'\');"/>';
    $frm_str .='</div">';
    $frm_str .='</form>';
    $frm_str .='</div>';
    
    return addslashes($frm_str);
 }

function check_form($form_id, $values)
{
    $target = get_value_fields($values, 'redirect_dep');
    if (empty($target)) {
        $target = get_value_fields($values, 'redirect_user');
    }
    
    if($target == 'department')
    {
        $dep = get_value_fields($values, 'department');
        if($dep == '')
        {
            $_SESSION['action_error'] = _MUST_CHOOSE_DEP;
            return false;
        }
        else
        {
            return true;
        }
    }
    else if($target == 'user')
    {
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
    }
    else
    {
        $_SESSION['action_error'] = _MUST_CHOOSE_DEP_OR_USER;
        return false;
    }
 }

function manage_form($arr_id, $history, $id_action, $label_action, $status,  $coll_id, $table, $values_form )
{
    if(empty($values_form) || count($arr_id) < 1)
    {
        return false;
    }
    
    $redirect_doc = _documents_are_redirected($values_form);
    $target = _redirection_target($values_form);
    
    $db = new Database();

    $arr_list = '';

    for($j=0; $j<count($values_form); $j++)
    {
        if($values_form[$j]['ID'] == "department" && $values_form[$j]['ID'] ==  $target)
        {
            //Get entity for history info
            $queryEntityLabel = "SELECT entity_label FROM entities WHERE entity_id=?";
            $stmt = $db->query($queryEntityLabel, array($values_form[$j]['VALUE']));
            while ($entityLabel = $stmt->fetchObject()) {
                $zeEntityLabel = $entityLabel->entity_label;
            }
            
            $msg = _TO." : ".functions::protect_string_db($zeEntityLabel)." ("
                .functions::protect_string_db($values_form[$j]['VALUE']).")";
                
            //Get the model list used by default
            require_once 'modules' . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR
                . 'class' . DIRECTORY_SEPARATOR . 'class_manage_listdiff.php';
            $diffList = new diffusion_list();
            $_SESSION['redirect']['diff_list'] = $diffList->get_listmodel(
                    'entity_id',
                    $values_form[$j]['VALUE']
                );
            //
            for($i=0; $i < count($arr_id); $i++)
            {
                $arr_list .= $arr_id[$i].'#';

                //Destination
                $db->query("UPDATE ".$table." SET destination = ? WHERE folders_system_id = ? ", array($values_form[$j]['VALUE'], $arr_id[$i]));
                
                //Redirect all documents
                if ($redirect_doc === true) {
                    redirect_documents($arr_id[$i], 'destination', $values_form[$j]['VALUE'], $coll_id, $msg);
                }
                
                //Dest user
                if(isset($_SESSION['redirect']['diff_list']['dest']['user_id']) && !empty($_SESSION['redirect']['diff_list']['dest']['user_id']))
                {
                    $db->query("UPDATE ".$table." SET dest_user = ? WHERE folders_system_id = ? ", array($_SESSION['redirect']['diff_list']['dest']['user_id'], $arr_id[$i]));
                
                    //Redirect all documents
                    if ($redirect_doc === true) {
                        redirect_documents($arr_id[$i], 'dest_user', $_SESSION['redirect']['diff_list']['dest']['user_id'], $coll_id, $msg);
                    }
                }
            }
            
            $_SESSION['action_error'] = _REDIRECT_TO_DEP_OK;

            return array('result' => $arr_list, 'history_msg' => $msg );
        }
        elseif($values_form[$j]['ID'] == "user" && $values_form[$j]['ID'] ==  $target)
        {
            $msg = _TO." : "._USER." ("
                .functions::protect_string_db($values_form[$j]['VALUE']).")";
                
            for($i=0;$i<count($arr_id);$i++)
            {
                $arr_list .= $arr_id[$i].'#';
                
                // Update dest_user in folders table
                $db->query("UPDATE ".$table." SET dest_user = ? WHERE folders_system_id = ?", array($values_form[$j]['VALUE'], $arr_id[$i]));
  
                //Redirect all documents in res table
                if ($redirect_doc === true) {
                    redirect_documents($arr_id[$i], 'dest_user', $values_form[$j]['VALUE'], $coll_id, $msg);
                }
            }        
            $_SESSION['action_error'] = _REDIRECT_TO_USER_OK;
            
            return array('result' => $arr_list, 'history_msg' => $msg);
        }
    }
    return false;
}

function _redirection_target($values_form) {
    $target = '';
    for($i=0; $i<count($values_form); $i++)
    {
        if($values_form[$i]['ID'] == "redirect_user" && $values_form[$i]['VALUE'] == 'user') {
            $target = 'user';
            break;
            
        } else if($values_form[$i]['ID'] == "redirect_dep" && $values_form[$i]['VALUE'] == 'department') {
            $target = 'department';
            break;
        }
    }
    
    return $target;
}

function _documents_are_redirected($values_form) {
    $redirect = false;
    for($i=0; $i<count($values_form); $i++)
    {
        if($values_form[$i]['ID'] == "redirect_documents" && $values_form[$i]['VALUE'] == 'Y')
        {
            $redirect = true;
            break;
        }
    }
    
    return $redirect;
}

function redirect_documents($id, $field, $value, $coll_id, $history_msg='') {

    $db = new Database();
    
    $id_action = '1';
    
    // Gets the action informations from the database
    $stmt = $db->query("SELECT * FROM ".$_SESSION['tablename']['actions']." WHERE id = ?", array($id_action));
    $res = $stmt->fetchObject();
    $label_action = $res->label_action;
    
    //Get table collection
    require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
    $sec = new security();
    $table = $sec->retrieve_table_from_coll($coll_id);
    $stmt = $db->query("SELECT res_id FROM ".$table." WHERE folders_system_id = ? and status <> 'FOLDDEL'", array($id));
    
    require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
    $hist = new history();
        
    while($res = $stmt->fetchObject()) {
    
        $db->query("UPDATE ".$table." SET ".$field." = ? WHERE res_id = ?", array($value, $res->res_id));
        
        $what = '';
        if (isset($_SESSION['current_basket']['id']) && !empty($_SESSION['current_basket']['id'])) {
            $what = $_SESSION['current_basket']['label'].' : ';
        }
        $what .= $label_action.'('._NUM.$res->res_id.') ';
        $what .= $history_msg;
        $hist->add(
                    $table,
                    $res->res_id,'ACTION#'.$id_action, $id_action,
                    $what, $_SESSION['config']['databasetype'], $_POST['module']);
    }   
}
            
function manage_unlock($arr_id, $history, $id_action, $label_action, $status, $coll_id, $table)
{
    $db = new Database();

    for($i=0; $i<count($arr_id );$i++)
    {
        $db->query("UPDATE ".$table. " SET video_user = '', video_time = 0 WHERE folders_system_id = ?", array($arr_id[$i]));

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
