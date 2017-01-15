<?php
/*
*   Copyright 2008-2012 Maarch
*
*   This file is part of Maarch Framework.
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
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief  Script called by an ajax object to process the document type 
* change during indexing (index_mlb.php), process limit date calcul and 
* possible services from apps or module
*
* @file change_doctype.php
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup indexing_searching_mlb
*/
require_once('core/class/class_security.php');
require_once('apps/' . $_SESSION['config']['app_id'] . '/class/class_types.php');

$db = new Database();
$core = new core_tools();
$core->load_lang();
$type = new types();

if (!isset($_REQUEST['type_id']) || empty($_REQUEST['type_id'])) {
    $_SESSION['error'] = _DOCTYPE.' '._IS_EMPTY;
    echo "{status : 1, error_txt : '".addslashes(functions::xssafe($_SESSION['error']))."'}";
    exit();
}

if (!isset($_REQUEST['id_action']) || empty($_REQUEST['id_action'])) {
    $_SESSION['error'] = _ACTION_ID.' '._IS_EMPTY;
    echo "{status : 1, error_txt : '".addslashes(functions::xssafe($_SESSION['error']))."'}";
    exit();
}
$id_action = $_REQUEST['id_action'];

if (
    isset($_REQUEST['res_id']) 
    && !empty($_REQUEST['res_id']) 
    && isset($_REQUEST['coll_id']) 
    && !empty($_REQUEST['coll_id'])
) {
    $res_id = $_REQUEST['res_id'];
    $coll_id = $_REQUEST['coll_id'];
}
if ($coll_id == '') {
    $coll_id = $_REQUEST['coll_id'];
}
if ($coll_id == '') {
    $coll_id = 'letterbox_coll';
}
if ($res_id == '') {
    $res_id = $_REQUEST['res_id'];
}
if (isset($_REQUEST['admission_date']) 
    && !empty($_REQUEST['admission_date'])
) {
    $admissionDate = $_REQUEST['admission_date'];
}

if (!isset($_REQUEST['priority_id']) || $_REQUEST['priority_id'] == '') {
    echo "{status : 1, error_txt : '".addslashes(_PRIORITY . ' ' . _IS_EMPTY)."'}";
    exit();
} else {
    $priorityId = $_SESSION['process_mode_priority'][$_SESSION['process_mode']];
    $_SESSION['process_mode'] = NULL;
    if ($_SESSION['mail_priorities_attribute'][$priorityId] <> 'false') {
        $priorityDelay = $_SESSION['mail_priorities_attribute'][$priorityId];
    }
}

// Process limit date calcul
//Bug fix if delay process is disabled in services
if ($core->service_is_enabled('param_mlb_doctypes')) {
    $stmt = $db->query("SELECT process_delay FROM " 
        . $_SESSION['tablename']['mlb_doctype_ext'] . " WHERE type_id = ?", 
        array($_REQUEST['type_id'])
    );

    $res = $stmt->fetchObject();
    $delay = $res->process_delay;
}
$mandatory_indexes = $type->get_mandatory_indexes($_REQUEST['type_id'], $coll_id);
$indexes = $type->get_indexes($_REQUEST['type_id'], $coll_id);
//var_dump($coll_id);exit;
$opt_indexes = '';
if (preg_match("/MSIE 6.0/", $_SERVER["HTTP_USER_AGENT"])) {
    $browser_ie = true;
    $display_value = 'block';
} elseif (
    preg_match('/msie/i', $_SERVER["HTTP_USER_AGENT"]) 
    && !preg_match('/opera/i', $HTTP_USER_AGENT)
) {
    $browser_ie = true;
    $display_value = 'block';
} else {
    $browser_ie = false;
    $display_value = 'table-row';
}
$opt_indexes  = '';
if(count($indexes) > 0)
{
    if((isset($res_id) && isset($coll_id)) && (!empty($res_id) && !empty($coll_id)))
    {
        $sec = new security();
        $table = $sec->retrieve_table_from_coll($coll_id);
        if(!empty($table))
        {
            $fields = 'res_id ';
            foreach(array_keys($indexes) as $key)
            {
                $fields .= ', '.$key;
            }
            $stmt = $db->query("SELECT " . $fields . " FROM " . $table 
                . " WHERE res_id = ?", array($res_id)
            );
            $values_fields = $stmt->fetchObject();
        }
    }
    $opt_indexes .= '<hr />';

    $opt_indexes .= '<h4 onclick="new Effect.toggle(\'doctype_fields\', \'blind\', {delay:0.2});'
        . 'whatIsTheDivStatus(\'doctype_fields\', \'divStatus_doctype_fields\');" '
        . 'class="categorie" style="width:90%;" onmouseover="this.style.cursor=\'pointer\';">';
    $opt_indexes .= ' <span id="divStatus_doctype_fields" style="color:#1C99C5;"><i class="fa fa-minus-square-o"></i></span>&nbsp;' 
        . _DOCTYPE_INDEXES;
    $opt_indexes .= '</h4>';

    $opt_indexes .= '<div id="doctype_fields"  style="display:inline">';
    $opt_indexes .= '<div>';
    
    $opt_indexes .= '<table width="100%" align="center" border="0">';
    foreach (array_keys($indexes) as $key) {
        $mandatory = false;
        if (in_array($key, $mandatory_indexes)) {
            $mandatory = true;
        }
            $opt_indexes .= '<tr >';
            $opt_indexes.='<td><label for="' . functions::xssafe($key) . '" class="form_title" >' 
                . $indexes[$key]['label'].'</label></td>';
            $opt_indexes .='<td>&nbsp;</td>';
            $opt_indexes .='<td class="indexing_field">';
            if ($indexes[$key]['type_field'] == 'input') {
                if ($indexes[$key]['type'] == 'date') {
                    $opt_indexes .='<input name="' . functions::xssafe($key) . '" type="text" id="' 
                        . $key . '" value="';
                    if (isset($values_fields->{$key})) {
                        $opt_indexes .= functions::format_date_db(
                            functions::xssafe($values_fields->{$key}), true
                        );
                    } elseif ($indexes[$key]['default_value'] <> false) {
                        $opt_indexes .= functions::format_date_db(
                            functions::xssafe($indexes[$key]['default_value']), true
                        );
                    }
                    $opt_indexes .= '" onclick="clear_error(\'frm_error_' 
                        . $id_action . '\');showCalender(this);"/>';
                } else {
                    $opt_indexes .= '<input name="'.functions::xssafe($key).'" type="text" id="' 
                        . $key . '" value="';
                    if (isset($values_fields->{$key})) {
                        $opt_indexes .= functions::show_string(
                            functions::xssafe($values_fields->{$key}), true
                        );
                    } else if ($indexes[$key]['default_value'] <> false) {
                        $opt_indexes .= functions::show_string(
                            functions::xssafe($indexes[$key]['default_value']), true
                        );
                    }
                    $opt_indexes .= '" onclick="clear_error(\'frm_error_' 
                        . $id_action . '\');" />';
                }
            } else {
                $opt_indexes .= '<select name="'.functions::xssafe($key).'" id="'.functions::xssafe($key).'" >';
                    $opt_indexes .= '<option value="">'._CHOOSE.'...</option>';
                    for ($i=0; $i<count($indexes[$key]['values']);$i++) {
                        $opt_indexes .= '<option value="' 
                            . functions::xssafe($indexes[$key]['values'][$i]['id']) . '"';
                        if ($indexes[$key]['values'][$i]['id'] 
                            == $values_fields->{$key}) {
                            $opt_indexes .= 'selected="selected"';
                        } elseif (
                            $indexes[$key]['default_value'] <> false 
                            && $indexes[$key]['values'][$i]['id'] 
                                == $indexes[$key]['default_value']
                        ) {
                            $opt_indexes .= 'selected="selected"';
                        }
                        $opt_indexes .= ' >' . functions::xssafe($indexes[$key]['values'][$i]['label']) 
                            . '</option>';
                    }
                $opt_indexes .= '</select>';
            }
            $opt_indexes .='</td>';
            if ($mandatory) {
                $opt_indexes .='<td><span class="red_asterisk" id="' 
                    . $key . '_mandatory">';
                //$opt_indexes .= 'inline';
                $opt_indexes .= '*</span>&nbsp;</td>';
            } else {
                $opt_indexes .='<td><span style="visibility:hidden;" id="' 
                    . $key . '_mandatory">';
                $opt_indexes .= '*</span>&nbsp;</td>';
            }
            //$opt_indexes .= ';">*</span>&nbsp;</td>';
        $opt_indexes .= '</tr>';
    }
    $opt_indexes .= '</table>';
    
    $opt_indexes .= '</div></div>';
}

$services = '[';
$_SESSION['indexing_services'] = array();
$_SESSION['indexing_type_id'] = $_REQUEST['type_id'];
$_SESSION['category_id_session'] = $_SESSION['indexing_type_id'];

// Module and apps services
$core->execute_modules_services(
    $_SESSION['modules_services'], 'change_doctype.php', 'include'
);
$core->execute_app_services(
    $_SESSION['app_services'], 'change_doctype.php', 'include'
);
for ($i=0;$i< count($_SESSION['indexing_services']);$i++) {
    $services .= "{ script : '" . $_SESSION['indexing_services'][$i]['script'] 
        . "', function_to_execute : '" 
        . functions::xssafe($_SESSION['indexing_services'][$i]['function_to_execute'])
        . "', arguments : '[";
    for ($j=0;$j<count($_SESSION['indexing_services'][$i]['arguments']);$j++) {
        $services .= " { id : \'" 
            . functions::xssafe($_SESSION['indexing_services'][$i]['arguments'][$j]['id'])
            . "\', value : \'" 
            . addslashes(
                $_SESSION['indexing_services'][$i]['arguments'][$j]['value']
            )
            . "\' }, ";
    }
    $services = preg_replace('/, $/', '', $services);
    $services .= "]' }, ";
}
$services = preg_replace('/, $/', '', $services);
$services .= ']';
unset($_SESSION['indexing_type_id']);
unset($_SESSION['indexing_services']);

if ($priorityDelay <> '') {
    $delay = $priorityDelay;
}

if (isset($delay) && $delay > 0) {
    require_once('core/class/class_alert_engine.php');
    $alert_engine = new alert_engine();
    if (isset($admissionDate) && !empty($admissionDate)) {
        $convertedDate = $alert_engine->dateFR2Time(str_replace("-", "/", $admissionDate));
        $date = $alert_engine->WhenOpenDay($convertedDate, $delay);
        //$date = $alert_engine->date_max_treatment($delay, false);
    } else {
        $date = $alert_engine->date_max_treatment($delay, false);
    }
    $process_date = functions::dateformat($date, '-');
    $tmpProcessDate = explode(" ", $process_date);
    $date = $tmpProcessDate[0];
    
    echo "{status : 0, process_date : '" . trim(functions::xssafe($date)) 
        . "', opt_indexes : '" . addslashes($opt_indexes) . "', services : " 
        . $services . "}";
    exit();
} else {
    echo "{status : 1, opt_indexes : '" . addslashes($opt_indexes) 
        . "', services : " . $services . "}";
    exit();
}
