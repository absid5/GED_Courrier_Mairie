<?php
/*
*    Copyright 2008,2016 Maarch
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
* Module : Tags
* 
* This module is used to store ressources with any keywords
* V: 1.0
*
* @file
* @author Alex Orluc
* @date $date$
* @version $Revision$
*/



core_tools::load_lang();
$core_tools = new core_tools();
$core_tools->test_admin('admin_thesaurus', 'thesaurus');

// Default mode is add
$mode = 'list';
if (isset($_REQUEST['mode']) && !empty($_REQUEST['mode'])) {
    $mode = $_REQUEST['mode'];
}



try{
    require_once 'core/class/ActionControler.php';
    require_once 'core/class/ObjectControlerAbstract.php';
    require_once 'core/class/ObjectControlerIF.php';
    require_once 'modules/thesaurus/class/class_modules_tools.php' ;

    if ($mode == 'list') {
        require_once 'core/class/class_request.php' ;
        require_once 'apps' . DIRECTORY_SEPARATOR
                     . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR
                     . 'class' . DIRECTORY_SEPARATOR . 'class_list_show.php' ;
    }
} catch (Exception $e) {
    functions::xecho($e->getMessage());
}


$func = new functions();

//Get list of all templates
if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
    $_REQUEST['id'] = htmlspecialchars_decode($_REQUEST['id']);
    $thesaurus_id = $func->protect_string_db($_REQUEST['id']);
}


if (isset($_REQUEST['thesaurus_submit'])) {
    // Action to do with db
    validate_thesaurus_submit();

} else {
    // Display to do

    $state = true;
	    
    switch ($mode) {
        case 'up' :
           	display_up($thesaurus_id);
         	
            $_SESSION['service_thesaurus'] = 'thesaurus_init';
            core_tools::execute_modules_services(
                $_SESSION['modules_services'], 'event_init', 'include'
            );
            location_bar_management($mode);
            break;
        case 'add' :
            display_add();
            $_SESSION['service_thesaurus'] = 'thesaurus_init';
            core_tools::execute_modules_services(
                $_SESSION['modules_services'], 'thesaurus_init', 'include'
            );
            location_bar_management($mode);
            break;
        case 'del' :
            display_del($thesaurus_id);
            break;
        case 'list' :
            $thesauruslist = display_list();
            location_bar_management($mode);
            break;
    }
	
    include('manage_thesaurus_list.php');
}

/**
 * Management of the location bar
 */
function location_bar_management($mode)
{
    $pageLabels = array('add'  => _ADDITION,
                    'up'   => _MODIFICATION,
                    'list' => _MANAGE_THESAURUS
               );
    $pageIds = array('add' => 'thesaurus_add',
                  'up' => 'thesaurus_up',
                  'list' => 'thesaurus_list'
            );
    $init = false;
    if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == 'true') {
        $init = true;
    }

    $level = '';
    if (isset($_REQUEST['level'])
        && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3
            || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1)) {
        $level = $_REQUEST['level'];
    }

    $pagePath = $_SESSION['config']['businessappurl'] . 'index.php?page='
               . 'manage_thesaurus_list_controller&module=thesaurus&mode=' . $mode ;
    $pageLabel = $pageLabels[$mode];
    $pageId = $pageIds[$mode];
    $ct = new core_tools();
    $ct->manage_location_bar($pagePath, $pageLabel, $pageId, $init, $level);
}

/**
 * Initialize session parameters for add display
 */
function display_add()
{
    
    require_once "core" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    ."class_security.php";
    
    if (!isset($_SESSION['m_admin']['init'])) {
        init_session();
    }
    
    //Recuperation du nombre de documents taggués
    $sec = new Security();
    $arrayColl = $sec->retrieve_insert_collections();
    $_SESSION['m_admin']['thesaurus']['coll_id'] = $arrayColl;
    //var_dump($arrayColl); exit();
    return $state;
    
}

/**
 * Initialize session parameters for update display
 * @param String $statusId
 */
function display_up($thesaurus_id)
{
    $thesaurus = new thesaurus();
    $state = true;
    $thesaurus_info = $thesaurus->getInfoThesaurusById($thesaurus_id);
    if (empty($thesaurus_info)) {
        $state = false;
    } else {
        //put_in_session('tag', $tag->getArray());
        $_SESSION['m_admin']['thesaurus']['thesaurus_id'] = $thesaurus_info->thesaurus_id;
        $_SESSION['m_admin']['thesaurus']['thesaurus_name'] = $thesaurus_info->thesaurus_name;
        $_SESSION['m_admin']['thesaurus']['thesaurus_description'] = $thesaurus_info->thesaurus_description;
        $_SESSION['m_admin']['thesaurus']['thesaurus_name_associate'] = $thesaurus_info->thesaurus_name_associate;
        $_SESSION['m_admin']['thesaurus']['thesaurus_parent_id'] = $thesaurus_info->thesaurus_parent_id;
        $_SESSION['m_admin']['thesaurus']['used_for'] = $thesaurus_info->used_for;
                                                                                                      
    }   
}

/**
 * Delete given thesaurus if exists and initialize session parameters
 * @param string $thesaurus_id
 */
function display_del($thesaurus_id) {
    if (!$_SESSION['m_admin']['thesaurus']['coll_id']){
        $_SESSION['m_admin']['thesaurus']['coll_id'] = 'letterbox_coll';
    }

    $coll_id = $_SESSION['m_admin']['thesaurus']['coll_id'];
    
    $thesaurus = new thesaurus();
    $thesaurus_name = $thesaurus->get_by_label($thesaurus_id);
    if (isset($thesaurus_id)) {
        // Deletion
        $control = $thesaurus->delete($thesaurus_id);
        if (!$control) {
            $_SESSION['error'] = str_replace("#", "<br />", $control['error']);
        } else {
            $_SESSION['info'] = _THESAURUS_DELETED.' : '. str_replace("''", "'", $thesaurus_name);
        }
        ?><script type="text/javascript">window.top.location='<?php
            echo $_SESSION['config']['businessappurl']
                . 'index.php?page=manage_thesaurus_list_controller&mode=list&module='
                . 'thesaurus&order=' . $_REQUEST['order'] . '&order_field='
                . $_REQUEST['order_field'] . '&start=' . $_REQUEST['start']
                . '&what=' . $_REQUEST['what'];
        ?>';</script>
        <?php
        exit();
    } else {
        // Error management
        $_SESSION['error'] = _THESAURUS.' '._UNKNOWN;
    }
}

/**
 * Initialize session parameters for list display
 */
function display_list() {
    $_SESSION['m_admin'] = array();
    $list = new list_show();
    $func = new functions();
    $thesaurus = new thesaurus();
    init_session();

    $select['thesaurus'] = array();
    array_push(
        $select['thesaurus'],'thesaurus_id' , 'thesaurus_name', 'thesaurus_name_associate', 'thesaurus_parent_id'
    );
    $where = '';
    $where_what = array();
    $what = '';
    if (isset($_REQUEST['what'])) {
        $what = $_REQUEST['what'];

    }
    if ($_SESSION['config']['databasetype'] == 'POSTGRESQL') {
        $where .= " (thesaurus_name ilike ? or thesaurus_name ilike ? ) ";
        $where_what[] = $what.'%';
        $where_what[] = $what.'%';

    } else {
        $where .= " (thesaurus_name like ?  or thesaurus_name like ? ) ";
        $where_what[] = $what.'%';
        $where_what[] = $what.'%';
    }

    // Checking order and order_field values
    $order = 'asc';
    if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {
        $order = trim($_REQUEST['order']);
    }

    $field = 'thesaurus_name';
    if (isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field'])) {
        $field = trim($_REQUEST['order_field']);
    }

    $orderstr = $list->define_order($order, $field);
    $request = new request();
    $tab = $request->PDOselect(
        $select, $where, $where_what, $orderstr, $_SESSION['config']['databasetype'], 
        "default", false, "", "", "", true, false, true
    );

    $nb_tes = $thesaurus->countThesaurus();
    //$request->show();
    //var_dump($tab);
    for ($i=0;$i<count($tab);$i++) {
        foreach ($tab[$i] as &$item) {
            switch ($item['column']) {
                case 'thesaurus_id':
                    format_item(
                        $item, _ID, 'auto', 'left', 'left', 'bottom', true
                    );
                    break;

                case 'thesaurus_name':
                    format_item(
                        $item, _THESAURUS_NAME, 'auto', 'left', 'left', 'bottom', true
                    );
                    break;

                case 'thesaurus_name_associate':
                    $thesaurus_name_associate = array();
                    $thesaurus_name_associate_tmp = array();
                    $thesaurus_name_associate = explode(',', $item['value']);
                    if(!empty($thesaurus_name_associate)){
                        foreach ($thesaurus_name_associate as $key => $value) {
                            $thesaurus_name_associate_id = $thesaurus->get_by_id($value);
                            $thesaurus_name_associate_tmp[] = '<a href="'.$_SESSION['config']['businessappurl'] . 'index.php?page=manage_thesaurus_list_controller&mode=up&module=thesaurus&id='.$thesaurus_name_associate_id.'&start=0&order=asc&order_field=&what=" style="text-decoration:underline;" title="Accéder au terme">'.$value.'</a>';
                        }
                        $item['value'] = implode(' / ', $thesaurus_name_associate_tmp);
                    }
                    
                    $item['value'] = $item['value'];

                    format_item(
                        $item, _THESAURUS_NAME_ASSOCIATE, 'auto', 'left', 'left', 'bottom', true
                    );
                
                    break;

                case 'thesaurus_parent_id':
                    $thesaurus_parent_id_id = $thesaurus->get_by_id($item['value']);
                    $item['value'] = '<a href="'.$_SESSION['config']['businessappurl'] . 'index.php?page=manage_thesaurus_list_controller&mode=up&module=thesaurus&id='.$thesaurus_parent_id_id.'&start=0&order=asc&order_field=&what=" style="text-decoration:underline;" title="Accéder au terme">'.$item['value'].'</a>';
                    format_item(
                        $item, _THESAURUS_PARENT_ID, 'auto', 'left', 'left', 'bottom', true
                    );
                
                    break;

            }
        }
    }
    $_SESSION['m_admin']['init'] = true;
    $result = array(
        'tab'                 => $tab,
        'what'                => $what,
        'page_name'           => 'manage_thesaurus_list_controller&mode=list',
        'page_name_add'       => 'manage_thesaurus_list_controller&mode=add',
        'page_name_up'        => 'manage_thesaurus_list_controller&mode=up',
        'page_name_del'       => 'manage_thesaurus_list_controller&mode=del',
        'page_name_val'       => '',
        'page_name_ban'       => '',
        'label_add'           => _ADD_THESAURUS,
        'title'               => _THESAURUS_LIST . ' : ' . $nb_tes . ' (' . $i . ' ' . _DISPLAYED . ')',
        'autoCompletionArray' => array(
                                     'list_script_url'  =>
                                        $_SESSION['config']['businessappurl']
                                        . 'index.php?display=true&module=thesaurus'
                                        . '&page=manage_thesaurus_list_by_name',
                                     'number_to_begin'  => 1
                                 ),

    );
    return $result;
}

/**
 * Format given item with given values, according with HTML formating.
 * NOTE: given item needs to be an array with at least 2 keys:
 * 'column' and 'value'.
 * NOTE: given item is modified consequently.
 * @param $item
 * @param $label
 * @param $size
 * @param $labelAlign
 * @param $align
 * @param $valign
 * @param $show
 */
function format_item(
    &$item, $label, $size, $labelAlign, $align, $valign, $show, $order = true
) {
    $func = new functions();
    $item['value'] = $func->show_string($item['value']);
    $item[$item['column']] = $item['value'];
    $item['label'] = $label;
    $item['size'] = $size;
    $item['label_align'] = $labelAlign;
    $item['align'] = $align;
    $item['valign'] = $valign;
    $item['show'] = $show;
    if ($order) {
        $item['order'] = $item['value'];
    } else {
        $item['order'] = '';
    }
}

function init_session()
{
    $_SESSION['m_admin']['thesaurus'] = array(
        'thesaurus_id'                  => '',
        'thesaurus_name'                => '',
        'thesaurus_description'         => '',
        'thesaurus_name_associate'      => '',
        'used_for'                      => ''
   
    );
}

 /**
 * Validate a submit (add or up),
 * up to saving object
 */
function validate_thesaurus_submit() {
    $pageName = 'manage_thesaurus_list_controller';
    $func = new functions();
    $mode = 'up';
    $mode = $_REQUEST['mode'];
    $thesaurus = new thesaurus();
    
    if ($_REQUEST['thesaurus_id'])
    {
      $new_thesaurus_label = trim($func->protect_string_db($_REQUEST['thesaurus_label']));
    }
    
    $params = array();
    $new_thesaurus_name = $_REQUEST['thesaurus_name'];
    array_push($params, $_REQUEST['thesaurus_name']);
    array_push($params, $_REQUEST['thesaurus_description']);
    array_push($params, $_REQUEST['thesaurus_name_associate']);
    array_push($params, $_REQUEST['thesaurus_parent_id']);
    array_push($params, $_REQUEST['thesaurus_id']);
    array_push($params, $_REQUEST['used_for']);

    // var_dump($new_tag_label);
    if ($new_thesaurus_name == '' || !$new_thesaurus_name || empty($new_thesaurus_name))
    {
        $_SESSION['error'] = _THESAURUS_NAME_IS_EMPTY;
        header(
              'location: ' . $_SESSION['config']['businessappurl']
            . 'index.php?page=' . $pageName . '&mode='.$mode.'&id='
            . $thesaurus->thesaurus_id . '&module=thesaurus'
        );
        exit();
    }
    
    if($_SESSION['error'] <> ''){
        header(
              'location: ' . $_SESSION['config']['businessappurl']
            . 'index.php?page=' . $pageName . '&mode='.$mode.'&id='
            . $thesaurus->thesaurus_id . '&module=thesaurus'
        );
        exit();
    }
    
    $thesaurus->store($mode, $params);
    
    
    $_SESSION['m_admin']['thesaurus']['thesaurus_name'] = $new_thesaurus_name;
    $_SESSION['m_admin']['thesaurus']['coll_id'] = $coll_id;
  
    
  
    
    switch ($mode) {
        case 'up':
            if ($_SESSION['error'] == "")
                $_SESSION['info'] = _THESAURUS_UPDATED.' : '.str_replace("''", "'", $new_thesaurus_label);
            
            if (!empty($_SESSION['m_admin']['thesaurus']['thesaurus_label'])) {
                header(
                    'location: ' . $_SESSION['config']['businessappurl']
                    . 'index.php?page=' . $pageName . '&mode=up&id='
                    . $thesaurus->thesaurus_label . '&module=thesaurus'
                );
            } else {
                header(
                    'location: ' . $_SESSION['config']['businessappurl']
                    . 'index.php?page=' . $pageName . '&mode=list&module='
                    .'thesaurus&order=' . $status['order'] . '&order_field='
                    . $status['order_field'] . '&start=' . $status['start']
                    . '&what=' . $status['what']
                );
            }
            exit();
        case 'add':
            //header(
            //    'location: ' . $_SESSION['config']['businessappurl']
            //    . 'index.php?page=' . $pageName . '&mode=add&module=tags'
            //);
            $_SESSION['info'] = _THESAURUS_ADDED.' : '. str_replace("''", "'", $new_thesaurus_name);
            header(
                    'location: ' . $_SESSION['config']['businessappurl']
                    . 'index.php?page=' . $pageName . '&mode=list&module='
                    .'thesaurus&order=' . $status['order'] . '&order_field='
                    . $status['order_field'] . '&start=' . $status['start']
                    . '&what=' . $status['what']
                );
            exit();
    
    }    
}