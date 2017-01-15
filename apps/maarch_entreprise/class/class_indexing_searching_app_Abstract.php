<?php

/*
 *
 *    Copyright 2005-2015 Maarch
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
* modules tools Class for workflow
*
*  Contains all the functions to load modules tables for workflow
*
* @package  maarch
* @version 3.0
* @since 10/2005
* @license GPL v3
* @author  Laurent Giovannoni  <dev@maarch.org>
*
*/

abstract class indexing_searching_app_Abstract extends Database
{
    function __construct()
    {
        parent::__construct();
    }

    public function is_filetype_allowed($ext)
    {
        if (file_exists($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR."extensions.xml"))
        {
            $path = $_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR."extensions.xml";
        }
        else
        {
            $path = 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR."extensions.xml";
        }
        $xmlconfig = simplexml_load_file($path);

        $ext_list = array();
        $i = 0;
        foreach($xmlconfig->FORMAT as $FORMAT)
        {
            $ext_list[$i] = array("name" => (string) $FORMAT->name, "mime" => (string) $FORMAT->mime);
            $i++;
        }

        $type_state = false;
        for ($i=0;$i<count($ext_list);$i++)
        {
            if ($ext_list[$i]['name'] == strtoupper($ext))
            {
                $mime_type = $ext_list[$i]['mime'];
                $type_state = true;
            //  $i = count($ext_list);
                break;
            }
        }
        return $type_state;
    }

    public function show_index_frame($ext)
    {
        if (empty($ext))
        {
            return false;
        }
        if (file_exists($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR."extensions.xml"))
        {
            $path = $_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR."extensions.xml";
        }
        else
        {
            $path = 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR."extensions.xml";
        }
        $xmlconfig = simplexml_load_file($path);
        foreach($xmlconfig->FORMAT as $FORMAT)
        {
            if (strtoupper($ext) == (string) $FORMAT->name)
            {
                if ( $FORMAT->index_frame_show == "true")
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        return false;
    }
    public function filetypes_showed_indexation()
    {
        if (file_exists($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR."extensions.xml"))
        {
            $path = $_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR."extensions.xml";
        }
        else
        {
            $path = 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR."extensions.xml";
        }
        $xmlconfig = simplexml_load_file($path);
        $ext_list = array();
        foreach($xmlconfig->FORMAT as $FORMAT)
        {
            if ((string) $FORMAT->index_frame_show == "true")
            {
                array_push($ext_list,(string) $FORMAT->name);
            }
        }

        return $ext_list;
    }

    public function get_mime_type($ext)
    {
        if (file_exists($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR."extensions.xml"))
        {
            $path = $_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR."extensions.xml";
        }
        else
        {
            $path = 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR."extensions.xml";
        }
        $xmlconfig = simplexml_load_file($path);
        $ext_list = array();
        $i = 0;
        foreach($xmlconfig->FORMAT as $FORMAT)
        {
            $ext_list[$i] = array("name" => (string) $FORMAT->name, "mime" => (string) $FORMAT->mime);
            $i++;
        }
        for ($i=0;$i<count($ext_list);$i++)
        {
            if ($ext_list[$i]['name'] == strtoupper($ext))
            {
                $mime_type = $ext_list[$i]['mime'];
                $type_state = true;
                $i = count($ext_list);
                return $mime_type;
                break;
            }
        }
        return false;
    }


    public function update_mail($post, $typeform, $id_to_update, $coll_id)
    {
        require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
        require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
        require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
        $hist = new history();
        $func = new functions();
        $sec = new security();
        $data_res = array();
        $data_ext = array();
        $request = new request();
        $core = new core_tools();
        $db = new Database();

        $table = $sec->retrieve_table_from_coll($coll_id);
        $view = $sec->retrieve_view_from_coll_id($coll_id);
        $ind_coll = $sec->get_ind_collection($coll_id);
        $table_ext = $_SESSION['collections'][$ind_coll]['extensions'][0];
        if (!$table)
        {
            $_SESSION['error'] .= _COLL_HAS_NO_TABLE;
        }
        if (!empty($_SESSION['error']))
        {
            //$_SESSION['error_page'] = $_SESSION['error'];
            $error = $_SESSION['error'];
            $_SESSION['error']= '';
            ?>
            <script type="text/javascript">
               // window.opener.reload();
                var error_div = $('main_error');
                if (error_div)
                {
                    error_div.innerHTML = '<?php functions::xecho($error);?>';
                }
            </script>
            <?php
            exit();
        }
        $where = "res_id = ? ";
        $arrayPDO = array($id_to_update);

        $stmt = $db->query("SELECT category_id FROM ".$view." WHERE res_id = ?", array($id_to_update));
        $res = $stmt->fetchObject();
        $cat_id = $res->category_id;
        if (empty($cat_id) || !isset($cat_id))
        {
            $cat_id = 'empty';
        }
        // Simple cases
        foreach(array_keys($post) as $key)
        {
            if ($_ENV['categories'][$cat_id][$key]['modify'] == true)
            {
                if ($_ENV['categories'][$cat_id][$key]['mandatory'] == true  && $post[$key] == '' )
                {
                    $_SESSION['error'] .= $_ENV['categories'][$cat_id][$key]['label'].' '._IS_EMPTY;
                }
                if ($_ENV['categories'][$cat_id][$key]['type_form'] == 'date' && !empty($post[$key]) && preg_match($_ENV['date_pattern'],$post[$key])== 0)
                {
                    $_SESSION['error'] .= $_ENV['categories'][$cat_id][$key]['label']." "._WRONG_FORMAT;
                }
                else if ($_ENV['categories'][$cat_id][$key]['type_field'] == 'date' && $_ENV['categories'][$cat_id][$key]['table'] <> 'none' && !empty($post[$key]))
                {
                    if ($_ENV['categories'][$cat_id][$key]['table'] == 'res')
                    {
                        array_push($data_res, array('column' => $key, 'value' => $func->format_date_db($post[$key]), 'type' => "date"));
                    }
                    else if ($_ENV['categories'][$cat_id][$key]['table'] == 'coll_ext')
                    {
                        array_push($data_ext, array('column' => $key, 'value' => $func->format_date_db($post[$key]), 'type' => "date"));
                    }
                }
                if ($_ENV['categories'][$cat_id][$key]['type_form'] == 'integer'  && preg_match("/^[0-9]+$/",$post[$key])== 0)
                {
                    $_SESSION['error'] .= $_ENV['categories'][$cat_id][$key]['label']." "._WRONG_FORMAT;
                }
                else if ($_ENV['categories'][$cat_id][$key]['type_field'] == 'integer' && $_ENV['categories'][$cat_id][$key]['table'] <> 'none' && $post[$key] != '')
                {
                    if ($_ENV['categories'][$cat_id][$key]['table'] == 'res')
                    {
                        array_push($data_res, array('column' => $key, 'value' => $post[$key], 'type' => "integer"));
                    }
                    else if ($_ENV['categories'][$cat_id][$key]['table'] == 'coll_ext')
                    {
                        array_push($data_ext, array('column' => $key, 'value' => $post[$key], 'type' => "integer"));
                    }
                }
                if ($_ENV['categories'][$cat_id][$key]['type_form'] == 'radio' && !empty($post[$key]) && !in_array($post[$key], $_ENV['categories'][$cat_id][$key]['values']))
                {
                    $_SESSION['error'] .= $_ENV['categories'][$cat_id][$key]['label']." "._WRONG_FORMAT;
                }
                if ($_ENV['categories'][$cat_id][$key]['type_field'] == 'string' && $_ENV['categories'][$cat_id][$key]['table'] <> 'none' && !empty($post[$key]))
                {
                    if ($_ENV['categories'][$cat_id][$key]['table'] == 'res')
                    {
                        array_push($data_res, array('column' => $key, 'value' => $post[$key], 'type' => "string"));
                    }
                    else if ($_ENV['categories'][$cat_id][$key]['table'] == 'coll_ext')
                    {
                        array_push($data_ext, array('column' => $key, 'value' => $post[$key], 'type' => "string"));
                    }
                }
            }
        }

        require_once('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_types.php');
        $type = new types();

        $type_id =  $post['type_id'];
        $indexes = $type->get_indexes($type_id,$coll_id, 'minimal');
        $val_indexes = array();
        for ($i=0; $i<count($indexes);$i++)
        {
            $val_indexes[$indexes[$i]] = $post[$indexes[$i]];
        }
        $test_type = $type->check_indexes($type_id, $coll_id,$val_indexes );
        if ($test_type)
        {
            $data_res = $type->fill_data_array($type_id, $coll_id, $val_indexes, $data_res);
        }

        ///////////////////////// Other cases
        //process date
        if (
            isset($_ENV['categories'][$cat_id]['other_cases']['process_limit_date']) 
            && !empty($post['process_limit_date']) 
            && preg_match($_ENV['date_pattern'], $post['process_limit_date'])== 0)
        {
            $_SESSION['error'] .= $_ENV['categories'][$cat_id]['other_cases']['process_limit_date']['label']." "._WRONG_FORMAT;
        }
        if (!empty($post['process_limit_date'])) {

        $processLimitDate = new datetime($post['process_limit_date']);
        $processLimitDate = date_add($processLimitDate,date_interval_create_from_date_string('23 hours + 59 minutes + 59 seconds'));
        $processLimitDate = (array) $processLimitDate; 

            array_push(
                $data_ext, 
                array(
                    'column' => 'process_limit_date', 
                    'value' => $func->format_date_db($processLimitDate['date'],'true','','true'), 
                    'type' => "date"
                )
            );
        }
        
        if ($core->is_module_loaded('folder'))
        {

            $stmt = $db->query("SELECT folders_system_id FROM ".$table." WHERE res_id = ?", array($id_to_update));
            $res = $stmt->fetchObject();
            $old_folder_id = $res->folders_system_id;
            $market = '';
            if (isset($post['folder']))
            {
                $market = $post['folder'];
            }
            $project_id = '';
            $market_id = '';
            if (isset($_ENV['categories'][$cat_id]['other_cases']['folder']) && $_ENV['categories'][$cat_id]['other_cases']['folder']['mandatory'] == true )
            {
                if (empty($market) )
                {
                    $_SESSION['error'] .= $_ENV['categories'][$cat_id]['other_cases']['folder']['label'].' '._IS_EMPTY;
                }
            }
            if (!empty($market) )
            {
                if (!preg_match('/\([0-9]+\)$/', $market))
                {
                    $_SESSION['error'] .= $_ENV['categories'][$cat_id]['other_cases']['folder']['label']." "._WRONG_FORMAT.". "._USE_AUTOCOMPLETION;
                } else {
                    $market_id = str_replace(')', '', substr($market, strrpos($market,'(')+1));
                    $stmt = $db->query("SELECT folders_system_id FROM ".$_SESSION['tablename']['fold_folders']." WHERE folders_system_id = ?", array($market_id));
                    if ($stmt->rowCount() == 0)
                    {
                        $_SESSION['error'] .= _MARKET.' '.$market_id.' '._UNKNOWN;
                    }
                }
            }
            $project = '';
            if (isset($post['project']))
            {
                $project = $post['project'];
            }
            if (isset($_ENV['categories'][$cat_id]['other_cases']['project']) && $_ENV['categories'][$cat_id]['other_cases']['project']['mandatory'] == true)
            {
                if (empty($project))
                {
                    $_SESSION['error'] .= $_ENV['categories'][$cat_id]['other_cases']['project']['label'].' '._IS_EMPTY;
                }
            }
            if (!empty($project) )
            {
                if (!preg_match('/\([0-9]+\)$/', $project))
                {
                    $_SESSION['error'] .= $_ENV['categories'][$cat_id]['other_cases']['project']['label']." "._WRONG_FORMAT;
                }
                $project_id = str_replace(')', '', substr($project, strrpos($project,'(')+1));
                $stmt = $db->query("SELECT folders_system_id FROM ".$_SESSION['tablename']['fold_folders']." WHERE folders_system_id = ?", array($project_id));
                if ($stmt->rowCount() == 0)
                {
                    $_SESSION['error'] .= _MARKET.' '.$project_id.' '._UNKNOWN;
                }
            }
            if (!empty($project_id) && !empty($market_id))
            {
                $stmt = $db->query("SELECT folders_system_id FROM ".$_SESSION['tablename']['fold_folders']." WHERE folders_system_id = ? and parent_id = ?", array($market_id, $project_id));
                if ($stmt->rowCount() == 0)
                {
                    $_SESSION['error'] .= _INCOMPATIBILITY_MARKET_PROJECT;
                }
            }

            if (empty($_SESSION['error']))
            {
                $folder_id = '';
                if (!empty($market_id))
                {
                    $folder_id = $market_id;
                }
                else if (!empty($project_id))
                {
                    $folder_id = $project_id;
                }
                if (!empty($folder_id))
                {
                    array_push($data_res, array('column' => 'folders_system_id', 'value' => $folder_id, 'type' => "integer"));
                }
                else
                {
                    array_push($data_res, array('column' => 'folders_system_id', 'value' => 'NULL', 'type' => "integer"));
                }
                if ($folder_id <> $old_folder_id && $_SESSION['history']['folderup'])
                {
                    require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
                    $hist = new history();
                    $hist->add($_SESSION['tablename']['fold_folders'], $folder_id, "UP",'folderup', _DOC_NUM.$id_to_update._ADDED_TO_FOLDER, $_SESSION['config']['databasetype'],'apps');
                    if (isset($old_folder_id) && !empty($old_folder_id))
                    {
                        $hist->add($_SESSION['tablename']['fold_folders'], $old_folder_id, "UP", 'folderup',_DOC_NUM.$id_to_update._DELETED_FROM_FOLDER, $_SESSION['config']['databasetype'],'apps');
                    }
                }
            }
        }

        if ($core->is_module_loaded('entities') && $_SESSION['details']['diff_list']['key_value'] == md5($res_id) )
        {
            //Mise a jour du dest_user :: seulement sur demande 
            if (!empty($_SESSION['details']['diff_list']['dest']['user_id'])){
                $tmp_new_user_id = $_SESSION['details']['diff_list']['dest']['user_id'];
                array_push($data_res, array('column' => 'dest_user', 'value' => $tmp_new_user_id, 'type' => "string")); 
                unset($_SESSION['details']['diff_list']['key_value']);
            }
        }


        //$this->show_array($post);
        if (empty($_SESSION['error']))
        {
            $type->inits_opt_indexes($coll_id, $id_to_update);
            //$request->show_array($data_res);
            //exit();
            $request->PDOupdate($table, $data_res, $where, $arrayPDO, $_SESSION['config']['databasetype']);
            if (count($data_ext) > 0)
            {
                $request->PDOupdate($table_ext, $data_ext, $where, $arrayPDO, $_SESSION['config']['databasetype']);
            }
            $_SESSION['info'] = _INDEX_UPDATED." (".strtolower(_NUM).$id_to_update.")";

            //$hist->add($table, $id_to_update, "UP", $_SESSION['error'], $_SESSION['config']['databasetype'],'apps');
            $hist->add(
                $table,
                $id_to_update,
                'UP',
                'resup',
                $_SESSION['info'],
                $_SESSION['config']['databasetype'],
                'apps'
            );
            
            $_SESSION['info'] = _INDEX_UPDATED;
        } else {
            $_SESSION['info'] = $_SESSION['error'];
            $_SESSION['error'] = "";            
        }
        //$_SESSION['error_page'] = $_SESSION['error'];
        //$error = $_SESSION['error'];
        // $_SESSION['error']= '';
/*        ?>
        <script type="text/javascript">
            //window.opener.reload();
            var error_div = $('main_error');
            if (error_div)
            {
                error_div.innerHTML = '<?php functions::xecho($error);?>';
            }
        </script>
        <?php*/
    }


    public function delete_doc( $id_to_delete, $coll_id)
    {
        require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
        $sec = new security();
        $table = $sec->retrieve_table_from_coll($coll_id);

        if (!$table)
        {
            $_SESSION['error'] .= _COLL_HAS_NO_TABLE;
        }
        if (!empty($_SESSION['error']))
        {
            //$_SESSION['error_page'] = $_SESSION['error'];
            ?>
            <script type="text/javascript">
                window.opener.reload();
            </script>
            <?php
        }
        else
        {
            require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
            $request = new request();
            $data = array();
            array_push($data, array('column' => 'status', 'value' => 'DEL', 'type' => 'string'));
            $where = "res_id = ? ";
            $arrayPDO = array($id_to_delete);
            $request->PDOupdate($table, $data, $where, $arrayPDO, $_SESSION['config']['databasetype']);
            $_SESSION['error'] = _DOC_DELETED." ("._NUM." : ".$id_to_delete.")";
            if ($_SESSION['history']['resdel'])
            {
                require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
                $hist = new history();
                $hist->add($table, $id_to_delete, "DEL", 'resdel', $_SESSION['error'], $_SESSION['config']['databasetype'],'indexing_searching');
            }
        }
    }

    public function update_doc_status($idDoc, $coll_id, $status)
    {
        require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
        $sec = new security();
        $table = $sec->retrieve_table_from_coll($coll_id);
        if (!$table)
        {
            $_SESSION['error'] .= _COLL_HAS_NO_TABLE;
        }
        if (!empty($_SESSION['error']))
        {
            ?>
            <script  type="text/javascript">
                window.opener.reload();
            </script>
            <?php
        }
        else
        {
            require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
            $request = new request();
            $data = array();
            array_push($data, array('column' => 'status', 'value' => $status, 'type' => 'string'));
            $where = "res_id = ?";
            $arrayPDO = array($idDoc);
            $request->PDOupdate($table, $data, $where, $arrayPDO, $_SESSION['config']['databasetype']);
            //$_SESSION['info'] = _UPDATE_DOC_STATUS." ("._NUM." : ".$idDoc.") "._TO." ".$status;
            $_SESSION['info'] = _UPDATE_DOC_STATUS ." ". _TO." ".$status;
            require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
            $hist = new history();
            $hist->add($table, $idDoc, $status, 'resup', $_SESSION['error'], $_SESSION['config']['databasetype'],'indexing_searching');
        }
    }

    public function send_criteria_data($param)
    {
        /*list_criteres = Array ("num_courrier" => Array (label => "reference courrier',
                                             parametres => Array ( ...),
                                             type => "text",
                                             ),
                       "date courrier" => array*/
    //    $this->show_array($param);
        $options_criteria_list = '<option id="default" value="">'._CHOOSE_PARAMETERS.'</option>';
        $json_tab = '';
        foreach($param as $key => $value)
        {
            $json_tab .= "'".$key."' : {";
            //echo 'key '.$key."<br/>val ";
            //$this->show_array($value);
            $options_criteria_list .= '<option id="option_'.$key.'" value="'.$value['label'].'"> '.$value['label'].'</option>';
            $json_tab .= $this->json_line($key,$value['type'],$value['param']);
            $json_tab .= '}
            ,';
        }
        $json_tab = preg_replace("/,$/", '', $json_tab);

        $tab = array($options_criteria_list, $json_tab );

        return $tab;
    }

    public function json_line($id, $field_type, $param)
    {
        $str = '';
        $init = "'label' : '".addslashes($param['field_label'])."', 'value' :'";
        $end = "'";
        //$hidden = '<input type="hidden" name="meta[]" value="" />';
        if ($field_type == 'input_text')
        {
            $str = $init.'<input type="hidden" name="meta[]" value="'.$id.'#'.$id.'#input_text" /><input name="'.$id.'"  id="'.$id.'" type="text" '.$param['other'].' value="" />'.$end;
        }
        else if ($field_type == 'textarea')
        {
            $str = $init.'<input type="hidden" name="meta[]" value="'.$id.'#'.$id.'#textarea" /><textarea name="'.$id.'"  id="'.$id.'" '.$param['other'].' rows="2" style="display:block;width:530px;"></textarea>'.$end;
        }
        elseif ($field_type == 'date_range')
        {
            $str = $init.addslashes(_SINCE).' : <input type="text" name="'.$param['id1'].'" id="'.$param['id1'].'" value="" onclick="showCalender(this);" /> '
            .addslashes(_FOR).' : <input type="text" name="'.$param['id2'].'" id="'.$param['id2'].'" value=""  onclick="showCalender(this);" />';
            $str .= '<input type="hidden" name="meta[]" value="'.$id.'#'.$param['id1'].','.$param['id2'].'#date_range" />'.$end;
        }
        elseif ($field_type == 'num_range')
        {
            $str = $init.addslashes(_NUM_BETWEEN).' : <input type="text" name="'.$param['id1'].'" id="'.$param['id1'].'" value=""/ > '
            .addslashes(_AND).' : <input type="text" name="'.$param['id2'].'" id="'.$param['id2'].'" value="" / >';
            $str .= '<input type="hidden" name="meta[]" value="'.$id.'#'.$param['id1'].','.$param['id2'].'#num_range" />'.$end;
        }
        elseif ($field_type == 'select_simple')
        {
            $str = $init.'<select name="'.$id.'" id="'.$id.'">';
            if (isset($param['default_label']) && !empty($param['default_label']))
            {
                $str .='<option value="">'.$param['default_label'].'</option>';
            }
            for ($i=0; $i<count($param['options']);$i++)
            {
                $str .= '<option value="'.addslashes($param['options'][$i]['VALUE']).'" alt="'.addslashes($param['options'][$i]['LABEL']).'" title="'.addslashes($param['options'][$i]['LABEL']).'">'.addslashes($param['options'][$i]['LABEL']).'</option>';
            }
            $str .= '</select>';
            $str .= '<input type="hidden" name="meta[]" value="'.$id.'#'.$id.'#select_simple" />'.$end;
        }
        elseif ($field_type == 'select_multiple')
        {
            $str .= '<tr><td colspan="3">'.$param['label_title'].' :</td></tr>';
            $str .= '<tr>';
                $str .= '<td width="150" align="left">';
                    $str .= '<select name="'.$param['id'].'_available[]" id="'.$param['id'].'_available" size="10" ondblclick="moveclick_ext('." '".$param['id']."_available', '".$param['id']."_chosen'".');" multiple="multiple" >';
                        for ($i=0; $i<count($param['options']);$i++)
                        {
                            $str .= '<option value="'.$param['options'][$i]['VALUE'].'"  alt="'.addslashes($param['options'][$i]['LABEL'])
                                . '" title="'.addslashes($param['options'][$i]['LABEL']).'" ';
                            if (isset($param['options'][$i]['CLASS'])) {
                                $str .= ' class="' . $param['options'][$i]['CLASS'] . '" ';
                            }
                            $str .= '>'.$param['options'][$i]['LABEL'].'</option>';
                        }
                    $str .='</select>';
                    $str .= "<br/><em><a href=\"javascript:selectall_ext( '".$param['id']."_available');\" >"._SELECT_ALL.'</a></em>';
                $str .= '</td>';
                $str .= '<td width="135" align="center">';
                    $str .= '<input type="button" class="button" value="'._ADD.'" onclick="Move_ext('."'".$param['id']."_available', '".$param['id']."_chosen'".');" /><br />';
                    $str .= '<br /><input type="button" class="button" value="'._REMOVE.'" onclick="Move_ext('." '".$param['id']."_chosen', '".$param['id']."_available'".');" />';
                $str .= '</td>';
                $str .= '<td width="150" align="left">';
                            $str .= '<select name="'.$param['id'].'_chosen[]" id="'.$param['id'].'_chosen" size="10" ondblclick="moveclick_ext('." '".$param['id']."_chosen', '".$param['id']."_available'".');" multiple="multiple" " >';
                            $str .= '</select>';
                            $str .= "<br/><em><a href=\"javascript:selectall_ext( '".$param['id']."_chosen');\" >"._SELECT_ALL.'</a></em>';
                $str .= '</td>';
            $str .= '</tr>';
            $str = addslashes($str);
            $str = $init.'<table align="center" border="0" width="100%" >'.$str.'<input type="hidden" name="meta[]" value="'.$id.'#'.$param['id'].'_chosen#select_multiple" /></table>'.$end;
        }
        elseif ($field_type == 'checkbox')
        {
            $str = $init.'<table align="center" border="0" width="100%" >';

            $tmp_ids = '';
            for ($i=0; $i < count($param['checkbox_data']);$i=$i+2)
            {
                $tmp_ids .= $param['checkbox_data'][$i]['ID'].',';
                $str .= '<tr>';
                if (isset($param['checkbox_data'][$i+1]['ID']))
                {
                    $tmp_ids .= $param['checkbox_data'][$i+1]['ID'].',';
                    $str .= '<td><input type="checkbox" class="check" name="'.$param['checkbox_data'][$i]['ID'].'" id="'.$param['checkbox_data'][$i]['ID'].'" value="'.addslashes($param['checkbox_data'][$i]['VALUE']).'" />'.addslashes($param['checkbox_data'][$i]['LABEL']).'</td>';
                    $str .= '<td><input type="checkbox"  class="check" name="'.$param['checkbox_data'][$i+1]['ID'].'" id="'.$param['checkbox_data'][$i+1]['ID'].'" value="'.addslashes($param['checkbox_data'][$i+1]['VALUE']).'" />'.addslashes($param['checkbox_data'][$i+1]['LABEL']).'</td>';

                }
                else
                {
                    $str .= '<td colspan="2"><input type="checkbox"  class="check" name="'.$param['checkbox_data'][$i]['ID'].'" id="'.$param['checkbox_data'][$i]['ID'].'" value="'.addslashes($param['checkbox_data'][$i]['VALUE']).'" />'.addslashes($param['checkbox_data'][$i]['LABEL']).'</td>';
                }
                $str .= '</tr>';
            }
            $tmp_ids = preg_replace('/,$/', '', $tmp_ids );
            $str .= '</table>';
            $str .= '<input type="hidden" name="meta[]" value="'.$id.'#'.$tmp_ids.'#checkbox" />'.$end;
        }
        elseif ($field_type == 'address')
        {
            $str = $init.'<input type="hidden" name="meta[]" value="'.$id.'#'.$param['address_data']['NUM']['ID'].','.$param['address_data']['ROAD']['ID'].','.$param['address_data']['CP']['ID'].','.$param['address_data']['CITY']['ID'].','.$param['address_data']['DISTRICTS']['ID'].'#address" />';
            $str .= '<table align="center" border="0" width="100%" >';
            $str .= '<tr>';
                $str .= '<td>'.$param['address_data']['NUM']['LABEL'].'</td><td><input type="text" name="'.$param['address_data']['NUM']['ID'].'" id="'.$param['address_data']['NUM']['ID'].'" class="small"/></td>';
                $str .= '<td>'.$param['address_data']['ROAD']['LABEL'].'</td><td><input type="text" name="'.$param['address_data']['ROAD']['ID'].'" id="'.$param['address_data']['ROAD']['ID'].'" /></td>';
            $str .= '</tr>';
            $str .= '<tr>';
                $str .= '<td>'.$param['address_data']['CP']['LABEL'].'</td><td><input type="text" name="'.$param['address_data']['CP']['ID'].'" id="'.$param['address_data']['CP']['ID'].'" class="medium" maxlength="5"/></td>';
                $str .= '<td>'.$param['address_data']['CITY']['LABEL'].'</td><td><input type="text" name="'.$param['address_data']['CITY']['ID'].'" id="'.$param['address_data']['CITY']['ID'].'" /></td>';
            $str .= '</tr>';
            if (isset($param['address_data']['DISTRICTS']))
            {
                $str .= '<tr>';
                    $str .= '<td>'.$param['address_data']['DISTRICTS']['LABEL'].'</td><td colspan="3">';
                        $str .= '<select name="'.$param['address_data']['DISTRICTS']['ID'].'" id="'.$param['address_data']['DISTRICTS']['ID'].'">';
                            $str .= '<option value="">'.$param['address_data']['DISTRICTS']['default_label'].'</option>';
                            for ($i=0; $i < count($param['address_data']['DISTRICTS']['options']); $i++)
                            {
                                $str .= '<option value="'.$param['address_data']['DISTRICTS']['options'][$i]['VALUE'].'" >'.$param['address_data']['DISTRICTS']['options'][$i]['LABEL'].'</option>';
                            }
                        $str .= '</select>';
                    $str .= '</td>';
                $str .= '</tr>';
            }
            $str .= '</table>'.$end;
        }
        elseif ($field_type == 'simple_list_or_input_text')
        {
            // td open in the showing function (js)
                $str .= '<input type="hidden" name="meta[]" value="'.$id.'#select_'.$param['id'].',input_'.$param['id'].'#simple_list_or_input_text" />';
                $str .= '<select name="select_'.$param['id'].'" id="select_'.$param['id'].'" onchange="start_action_list('."'".'div_'.$param['id']."', 'select_".$param['id']."', this.selectedIndex".')">';
                    $str .= '<option value="">'.$param['default_label_select'].'</option>';
                    $str .= '<option value="SHOW_DATA">'.$param['label_define_option']."</option>";
                    for ($i=0; $i<count($param['options']);$i++)
                    {
                        $str .= '<option value="'.addslashes($param['options'][$i]['VALUE']).'">'.addslashes($param['options'][$i]['LABEL']).'</option>';
                    }
                $str .= '</select>';
            $str .= '</td>';
            $str .= '<td>';
                $str .= '<div id="div_'.$param['id'].'" style="visibility:hidden">';
                    $str .= '<table width="100%" border="0">';
                        $str .= '<tr>';
                            $str .= '<td>'.$param['label_input'].' : <input type="text" name="input_'.$param['id'].'" id="input_'.$param['id'].'" '.$param['other'].' value="" /></td>';
                        $str .= '</tr>';
                    $str .= '</table>';
                $str .= '</div>';
            // td close in the showing function (js)
            $str = addslashes($str);
            $str =  $init.$str.$end;
        }
        elseif ($field_type == 'inputs_in_2_col')
        {
            $str = $init.'<table align="center" border="0" width="100%" >';
            $tmp = "";

            for ($i=0; $i<count($param['input_ids']) ;$i++)
            {
                $tmp .= $param['input_ids'][$i]['ID'].',';

                if ($i%2 != 1 || $i==0) // pair
                {
                    $str .= '<tr>';
                }
                $str .= '<td >'.addslashes($param['input_ids'][$i]['LABEL']).'</td><td><input type="text" name="'.$param['input_ids'][$i]['ID'].'" id="'.$param['input_ids'][$i]['ID'].'" value="" /></td>';
                if ($i%2 == 1 && $i!=0) // impair
                {
                    echo '</tr>'    ;
                }
                else
                {
                    if ($i+1 == count($param['input_ids']))
                    {
                        echo '<td  colspan="3">&nbsp;</td></tr>';
                    }
                }
            }
            $tmp = preg_replace('/,$/', '', $tmp);
            $str .= '</table>';
            $str .= '<input type="hidden" name="meta[]" value="'.$id.'#'.$tmp.'#inputs_in_2_col" />'.$end;
        }
        elseif ($field_type == 'select_or_other_data')
        {
            // td open in the showing function (js)
            $str .= '<table align="center" border="0" width="100%" >';
                $str .= '<tr>';
                        $str .= '<td>';
                            $str .= '<select name="select_'.$param['id'].'" id="select_'.$param['id'].'" onchange="start_action_list('."'".'div_'.$param['id']."', 'select_".$param['id']."', this.selectedIndex".')">';
                                $str .= '<option value="">'.$param['default_label_select'].'</option>';
                                $str .= '<option value="SHOW_DATA">'.$param['label_define_option']."</option>";
                                for ($i=0; $i<count($param['options']);$i++)
                                {
                                    $str .= '<option value="'.$param['options'][$i]['VALUE'].'">'.$param['options'][$i]['LABEL'].'</option>';
                                }
                            $str .= '</select>';
                        $str .= '</td>';
                $str .= '</tr>';
                $str .= '<tr>';
                        $str .= '<td>';
                            $str .= '<div id="div_'.$param['id'].'" style="display:none;">';
                                $str .= '<table align="center" border="0" width="100%" >';
                            $tmp = "select_".$param['id'].",";
                                for ($i=0; $i<count($param['input_ids']) ;$i++)
                                {
                                    $tmp .= $param['input_ids'][$i]['ID'].',';
                                    if ($i%2 != 1 || $i==0) // pair
                                    {
                                        $str .= '<tr>';
                                    }
                                    $str .= '<td >'.$param['input_ids'][$i]['LABEL'].' :</td><td><input type="text" name="'.$param['input_ids'][$i]['ID'].'" id="'.$param['input_ids'][$i]['ID'].'" value="" /></td>';
                                    if ($i%2 == 1 && $i!=0) // impair
                                    {
                                        echo '</tr>'    ;
                                    }
                                    else
                                    {
                                        if ($i+1 == count($param['input_ids']))
                                        {
                                            echo '<td  colspan="3">&nbsp;</td></tr>';
                                        }
                                    }
                                }
                            $tmp = preg_replace('/,$/', '', $tmp);
                                $str .= '</table>';
                            $str .= '</div>';
                            $str .= '<input type="hidden" name="meta[]" value="'.$id.'#'.$tmp.'#select_or_other_data" />';
                        $str .= '</td>';
                $str .= '</tr>';
            $str .= '</table>';
            // td close in the showing function (js)
            $str = addslashes($str);
            $str =  $init.$str.$end;
        }
        else
        {

        }
        return $str;
    }

    public function get_process_data($coll_id, $res_id)
    {
        require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
        $sec =new security();
        $view = $sec->retrieve_view_from_coll_id($coll_id);
        if (empty($view))
        {
            $view = $sec->retrieve_table_from_coll($coll_id);
        }
        $db = new Database();
        $stmt = $db->query("SELECT answer_type_bitmask, process_notes, other_answer_desc FROM ".$view." WHERE res_id = ?", array($res_id));
        $res = $stmt->fetchObject();
        $bitmask = $res->answer_type_bitmask;
        $process_notes = functions::show_string($res->process_notes);
        $other_answer_desc = functions::show_string($res->other_answer_desc);
        $contact = false;
        $mail = false;
        $AR = false;
        $fax = false;
        $email = false;
        $other = false;
        $no_answer = false;
        if ($bitmask == '000000' || $bitmask == '')
        {
            $no_answer = true;
        }
        else
        {
            /**
         * Answer type bitmask
         * 0 0 0 0 0 0
         * | | | | | |_ Simple Mail
         * | | | | |___ Registered mail
         * | | | |_____ Direct Contact
         * | | |_______ Email
         * | |_________ Fax
         * |___________ Other Answer
         **/

            if ($bitmask[0] == '1')
            {
                $other = true;
            }
            if ($bitmask[1] == '1')
            {
                $fax = true;
            }
            if ($bitmask[2] == '1')
            {
                $email = true;
            }
            if ($bitmask[3] == '1')
            {
                $contact = true;
            }
            if ($bitmask[4] == '1')
            {
                $AR = true;
            }
            if ($bitmask[5] == '1')
            {
                $mail = true;
            }
        }
        return array('process_notes' => $process_notes, 'direct_contact' => $contact, 'simple_mail' => $mail, 'registered_mail' => $AR, 'fax' => $fax, 'email' => $email, 'no_answer' => $no_answer, 'other' => $other, 'other_answer_desc' => $other_answer_desc);
    }
    
}
