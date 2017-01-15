<?php
/*
*
*    Copyright 2008,2012 Maarch
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
*
*   @author  Cyril Vazquez <dev@maarch.org>
*/

$admin = new core_tools();
$admin->test_admin('admin_parameters', 'apps');
$_SESSION['m_admin']= array();
/****************Management of the location bar  ************/
$init = false;
if(isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true")
{
    $init = true;
}
$level = "";
if(isset($_REQUEST['level']) && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1))
{
    $level = $_REQUEST['level'];
}
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=admin_parameter&admin=parameter';
$page_label = _PARAMETER;
$page_id = "admin_parameter";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
#******************************************************************************

require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_list_show.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("core/core_tables.php");

# Prepare view for UP/ADD
#******************************************************************************
$view = new DOMDocument();
@$view->loadHTMLFile(
    $_SESSION['config']['corepath'] .
    "apps" . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']. DIRECTORY_SEPARATOR 
    . 'template' . DIRECTORY_SEPARATOR . 'admin_parameter.html' 
);
$xview = new DOMXPath($view);
# Set id attributes in view
$ids = $xview->query('//*[@id]');
for($i=0, $l=$ids->length; $i<$l; $i++) {
    $id = $ids->item($i);
    $id->setIdAttribute('id', false);
    $id->setIdAttribute('id', true);
}
 
# Set action mode
$mode = $view->getElementById("mode");
$mode->setAttribute('value', $_REQUEST['mode']);

# Translate
$title = $view->getElementsByTagName('h1')->item(0);
if($text = @constant($title->nodeValue))
    $title->nodeValue = $text;

$labels = $view->getElementsByTagName('label');
for($i=0, $l=$labels->length; $i<$l; $i++) {
    $label = $labels->item($i);
    $const = $label->nodeValue;
    if($text = @constant($const))
        $label->nodeValue = $text;
}
$buttons = $xview->query('//input[@type="button"]');
for($i=0, $l=$buttons->length; $i<$l; $i++) {
    $button = $buttons->item($i);
    $value = $button->getAttribute('value');
    if($text = @constant($value))
        $button->setAttribute('value', $text);
}
$options = $xview->query('//option');
for($i=0, $l=$options->length; $i<$l; $i++) {
    $option = $options->item($i);
    $label = $option->nodeValue;
    if($text = @constant($label))
        $option->nodeValue = $text;
}

$datalist = $view->getElementById("messages");
$options = $datalist->getElementsByTagName("option");
for($i=0, $l=$options->length; $i<$l; $i++) {
    $option = $options->item($i);
    $id = $option->getAttribute('id');
    if($message = @constant($id))
        $option->setAttribute('value', $message);
}

# Manage local path
$cancel_btn = $view->getElementById("cancel");
$cancel_btn->setAttribute(
    'onclick',
    "goTo('index.php?admin=parameters&page=admin_parameters');"
);

# Switch on mode/action
#******************************************************************************
switch($_REQUEST['mode']) {
case 'add':
    echo $view->saveHTML();
    break;
    
case 'up':
case 'del':
    $db = new Database();
    $stmt = $db->query(
        "SELECT * FROM " . PARAM_TABLE
        . " WHERE id = ? ",
        array($_REQUEST['id'])
    );
    $param = $stmt->fetchObject();

    # param id (readonly)
    $param_id = $view->getElementById("id");
    $param_id->setAttribute('value', $param->id);
    $param_id->setAttribute('readonly', 'true');
    $param_id->setAttribute('disabled', 'true');
       
    # param description
    $param_description = $view->getElementById("description");
    $param_description->nodeValue = $param->description;
    
    # param value & type
    if($param->param_value_string != '')
        $type = 'string';
    elseif($param->param_value_int != '')
        $type = 'int';
    elseif($param->param_value_date != '') 
        $type = 'date';

    # Set value
    $param_value_string = $view->getElementById("param_value_string");
    $param_value_string->setAttribute('value', $param->param_value_string);
    $param_value_int = $view->getElementById("param_value_int");
    $param_value_int->setAttribute('value', $param->param_value_int);
    $param_value_date = $view->getElementById("param_value_date");
    $param_value_date->setAttribute('value', $param->param_value_date);
    
    # Set type (readonly)
    $param_type = $view->getElementById("type");
    $param_type->setAttribute('readonly', 'true');
    $param_type->setAttribute('disabled', 'true');
    if($type_option = $xview->query('//option[@value="'.$type.'"]')->item(0))
        $type_option->setAttribute('selected', 'true');
    
    echo $view->saveHTML();
    break;
}
