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
$admin->test_admin('admin_difflist_types', 'entities');
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
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=admin_difflist_type&module=entities';
$page_label = _DIFFLIST_TYPE;
$page_id = "admin_difflist_type";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
#******************************************************************************
require_once("core/class/usergroups_controler.php");
$ugc = new usergroups_controler();

require_once("modules/entities/class/class_manage_listdiff.php");
$difflist = new diffusion_list();

# Prepare view for UP/ADD
#******************************************************************************
$view = new DOMDocument();
$view->loadHTMLFile(
    __DIR__ . '/html/admin_difflist_type.html' 
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

# Manage local path
$cancel_btn = $view->getElementById("cancel");
$cancel_btn->setAttribute(
    'onclick',
    "goTo('index.php?module=entities&page=admin_difflist_types');"
);

# Get data for UP/DEL
#******************************************************************************
if($_REQUEST['mode'] == 'up' || $_REQUEST['mode'] == 'del') {
    $difflist_type = $difflist->get_difflist_type($_REQUEST['id']);
    $difflist_type_roles = $difflist->get_difflist_type_roles($difflist_type);
}
$difflist_roles = $difflist->list_difflist_roles();

# Switch on mode/action
#******************************************************************************
switch($_REQUEST['mode']) {
case 'add':
    # difflist_type_roles
    echo '<h1><i class="fa fa-share-alt fa-2x"></i> '._ADD.' '._DIFFLIST_TYPE.'</h1>';
    $all_roles = $view->getElementById("all_roles");
    foreach($difflist_roles as $role_id => $role_label) {
        $option = $view->createElement('option', $role_label);
        $option->setAttribute('value', $role_id);
        $all_roles->appendChild($option);
    }
    echo $view->saveXML();
    break;
    
case 'up':
    # difflist_type id
    echo '<h1><i class="fa fa-share-alt fa-2x"></i> '._MODIFY.' '._DIFFLIST_TYPE.' ('.$difflist_type->difflist_type_id.')</h1>';
    $difflist_type_id = $view->getElementById("difflist_type_id");
    $difflist_type_id->setAttribute('value', $difflist_type->difflist_type_id);
    $difflist_type_id->setAttribute('readonly', 'true');
    $difflist_type_id->setAttribute('disabled', 'true');
       
    # difflist_type Label
    $difflist_type_label = $view->getElementById("difflist_type_label");
    $difflist_type_label->setAttribute('value', $difflist_type->difflist_type_label);
    
    # difflist_type_roles
    $all_roles = $view->getElementById("all_roles");
    $selected_roles = $view->getElementById("selected_roles");
    foreach($difflist_roles as $role_id => $role_label) {
        $option = $view->createElement('option', $role_label);
        $option->setAttribute('value', $role_id);
        if(!isset($difflist_type_roles[$role_id])) {
            $all_roles->appendChild($option);
        } else {
            $selected_roles->appendChild($option);
        }
    }
    
    # Allow entities
    $allow_entities = $view->getElementById("allow_entities");
    if($difflist_type->allow_entities == 'Y')
        $allow_entities->setAttribute('checked', 'true');
    
    # Display
    echo $view->saveXML();
    break;

case "del":
    if($difflist_type->is_system != 'Y') {
        $difflist->delete_difflist_type($_REQUEST['id']);
    }
    echo "<script type='text/javascript'> goTo('".$_SESSION['config']['businessappurl']."index.php?page=admin_difflist_types&module=entities');</script>";
    break;
}
