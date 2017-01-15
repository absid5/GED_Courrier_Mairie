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
* @brief  Contains the template Object (herits of the BaseObject class)
* 
* 
* @file
* @author Laurent Giovannoni
* @date $date$
* @version $Revision$
* @ingroup templates
*/

$sessionName = 'templates';
$pageName = 'templates_management_controler';
$tableName = 'templates';
$idName = 'template_id';

$mode = 'add';

$core = new core_tools();
$core->load_lang();

$core->test_admin('admin_templates', 'templates');

if (isset($_REQUEST['mode']) && !empty($_REQUEST['mode'])) {
    $mode = $_REQUEST['mode'];
} else {
    $mode = 'list'; 
}

try{
	
    require_once('modules/templates/class/templates_controler.php');
    require_once('core/class/class_request.php');
    require_once('core/admin_tools.php');
    if ($mode == 'list') {
        require_once('modules/templates/lang/fr.php');
        require_once(
            'apps/' . $_SESSION['config']['app_id'] 
            . '/class/class_list_show.php'
        );
    }
} catch (Exception $e) {
    functions::xecho($e->getMessage());
}

if (isset($_REQUEST['submit'])) {
    // Action to do with db
    validate_cs_submit($mode);
} else {
    // Display to do
    if (isset($_REQUEST['id']) && !empty($_REQUEST['id']))
        $template_id = $_REQUEST['id'];
    $state = true;
    switch ($mode) {
        case 'up' :
            $state = display_up($template_id); 
            location_bar_management($mode);
            break;
        case 'add' :
            display_add(); 
            location_bar_management($mode);
            break;
        case 'del' :
            display_del($template_id); 
            break;
        case 'list' :
            $templates_list = display_list(); 
            location_bar_management($mode);
            break;
        case 'allow' :
            display_enable($template_id); 
            location_bar_management($mode);
        case 'ban' :
            display_disable($template_id); 
            location_bar_management($mode);
    }
    $checkedOFFICE = '';
    $checkedHTML = '';
    if (
        $_SESSION['m_admin']['templates']['template_type'] == '' 
        || $_SESSION['m_admin']['templates']['template_type'] == 'OFFICE'
    ) {
        $checkedOFFICE = 'checked="checked"';
    } elseif ($_SESSION['m_admin']['templates']['template_type'] == 'HTML') {
        $checkedHTML = 'checked="checked"';
    } elseif ($_SESSION['m_admin']['templates']['template_type'] == 'TXT') {
        $checkedTXT = 'checked="checked"';
    }
    include('templates_management.php');
}

/**
 * Initialize session variables
 */
function init_session()
{
    $_SESSION['m_admin']['templates'] = array();
    $_SESSION['m_admin']['templatesStyles'] = array();
    $_SESSION['m_admin']['templatesTargets'] = array();
    $_SESSION['m_admin']['templatesDatasources'] = array();
    $_SESSION['m_admin']['templatesEntities'] = array();
    $_SESSION['m_admin']['templatesEntitiesOrg'] = array();
    $_SESSION['m_admin']['templatesEntitiesSelected'] = array();
}

/**
 * Management of the location bar  
 */
function location_bar_management($mode)
{
    $pageName = 'templates_management_controler';
    
    $page_labels = array(
        'add' => _ADDITION, 
        'up' => _MODIFICATION, 
        'list' => _TEMPLATES_LIST
    );
    $page_ids = array(
        'add' => 'template_add', 
        'up' => 'template_up', 
        'list' => 'templates_list'
    );

    $init = false;
    if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == 'true') 
        $init = true;

    $level = '';
    if (
        isset($_REQUEST['level']) 
        && ($_REQUEST['level'] == 2 
        || $_REQUEST['level'] == 3 
        || $_REQUEST['level'] == 4 
        || $_REQUEST['level'] == 1)
    ) {
        $level = $_REQUEST['level'];
    }
    $page_path = $_SESSION['config']['businessappurl'] . 'index.php?page=' 
        . $pageName . '&module=templates&mode=' . $mode;
    $page_label = $page_labels[$mode];
    $page_id = $page_ids[$mode];
    $ct=new core_tools();
    $ct->manage_location_bar($page_path, $page_label, $page_id, $init, $level);

}

/**
 * Validate a submit (add or up),
 * up to saving object
 */
function validate_cs_submit($mode)
{
    $pageName = 'templates_management_controler';
    $templatesControler = new templates_controler();
    $status = array();
    $status['order'] = $_REQUEST['order'];
    $status['order_field'] = $_REQUEST['order_field'];
    $status['what'] = $_REQUEST['what'];
    $status['start'] = $_REQUEST['start'];
    $templates = new templates();
    if (isset($_REQUEST['id'])) $templates->template_id = $_REQUEST['id'];
    if (isset($_REQUEST['template_label'])) $templates->template_label 
        = $_REQUEST['template_label'];
    if (isset($_REQUEST['template_comment'])) $templates->template_comment 
        = $_REQUEST['template_comment'];
    if (isset($_REQUEST['template_style'])) $templates->template_style 
        = $_REQUEST['template_style'];
    if (isset($_REQUEST['template_datasource'])) $templates->template_datasource 
        = $_REQUEST['template_datasource'];
    if (isset($_REQUEST['template_target'])) {
        $templates->template_target = $_REQUEST['template_target'];
        if ($_REQUEST['template_target'] == "") {
        	$templates->template_attachment_type = "all";
        } else {
        	$templates->template_attachment_type = $_REQUEST['template_attachment_type'];        	
        }
    }
    if (isset($_REQUEST['template_type'])) {
        $templates->template_type = $_REQUEST['template_type'];
        if ($templates->template_type == 'HTML') {
            if (isset($_REQUEST['template_content'])) {
                $templates->template_content = $_REQUEST['template_content'];
            }
        } elseif ($templates->template_type == 'TXT') {
            if (isset($_REQUEST['template_content_txt'])) $templates->template_content 
                = $_REQUEST['template_content_txt'];
        }
    }
    $_SESSION['m_admin']['templatesEntitiesSelected'] = array();
    for ($i=0;$i<count($_REQUEST['entities_chosen']); $i++) {
        array_push(
            $_SESSION['m_admin']['templatesEntitiesSelected'], 
            $_REQUEST['entities_chosen'][$i]
        );
    }
    $control = array();
    $control = $templatesControler->save($templates, $mode);
    if (!empty($control['error']) && $control['error'] <> 1) {
        // Error management depending of mode
        $_SESSION['error'] = str_replace('#', '<br />', $control['error']);
        At_putInSession('status', $status);
        At_putInSession('templates', $templates->getArray());
        switch ($mode) {
            case 'up':
                if (!empty($_REQUEST['id'])) {
                    header('location: ' . $_SESSION['config']['businessappurl'] 
                        . 'index.php?page=' . $pageName . '&mode=up&id=' 
                        . $_REQUEST['id'] . '&module=templates'
                    );
                } else {
                    header('location: ' . $_SESSION['config']['businessappurl'] 
                        . 'index.php?page=' . $pageName . '&mode=list&module=templates&order=' 
                        . $status['order'] . '&order_field=' . $status['order_field'] 
                        . '&start=' . $status['start'] . '&what=' . $status['what']
                    );
                }
                exit;
            case 'add':
                header('location: ' . $_SESSION['config']['businessappurl'] 
                    . 'index.php?page=' . $pageName 
                    . '&mode=add&module=templates'
                );
                exit;
        }
    } else {
        if ($mode == 'add')
            $_SESSION['info'] = _TEMPLATE_ADDED;
         else
            $_SESSION['info'] = _TEMPLATE_UPDATED;
        unset($_SESSION['m_admin']);
        header('location: ' . $_SESSION['config']['businessappurl'] 
            . 'index.php?page=' . $pageName . '&mode=list&module=templates&order=' 
            . $status['order'] . '&order_field=' . $status['order_field'] 
            . '&start=' . $status['start'] . '&what=' . $status['what']
        );
    }
}

/**
 * Initialize session parameters for update display
 * @param Long $template_id
 */
function display_up($templateId)
{
    $state=true;
    $templatesControler = new templates_controler();
    $stylesArray = array();
    if (is_dir('custom/' . $_SESSION['custom_override_id'] . '/modules/templates/templates/styles')) {
       $dir = 'custom/' . $_SESSION['custom_override_id'] . '/modules/templates/templates/styles';
    } else {
        $dir = 'modules/templates/templates/styles/';
    }
    $stylesArray = $templatesControler->getTemplatesStyles(
        $dir, 
        $stylesArray
    );
    $xmlfile = 'modules/templates/xml/datasources.xml';
    $xmlfileCustom = $_SESSION['config']['corepath'] 
    . 'custom/' . $_SESSION['custom_override_id'] . '/' . $xmlfile;
     if (file_exists($xmlfileCustom)) {
        $xmlfile = $xmlfileCustom;
    }
    $datasourcesArray = array();
    $datasourcesArray = $templatesControler->getTemplatesDatasources($xmlfile);
    $targetsArray = array();
    $targetsArray = $templatesControler->getTemplatesTargets();
    $templates = $templatesControler->get($templateId);
    /*echo '<pre>';
    print_r($stylesArray);
    echo '</pre>';*/
    $entities = $templatesControler->getAllItemsLinkedToModel($templateId);
    require_once 'modules/entities/class/EntityControler.php';
    $entityControler = new EntityControler();
    $entitiesOrg = $entityControler->getAllEntities();
    /*echo '<pre>';
    print_r($entities);
    echo '</pre>';*/
    if (empty($templates)) {
        $state = false; 
    } else {
        At_putInSession('templates', $templates->getArray());
        At_putInSession('templatesStyles', $stylesArray);
        At_putInSession('templatesTargets', $targetsArray);
        At_putInSession('templatesDatasources', $datasourcesArray);
        At_putInSession('templatesEntities', $entities);
        At_putInSession('templatesEntitiesOrg', $entitiesOrg);
    }
    return $state;
}

/**
 * Initialize session parameters for add display with given docserver
 */
function display_add()
{
    $sessionName = 'templates';
    if (!isset($_SESSION['m_admin'][$sessionName])) {
        init_session();
    }
    $templatesControler = new templates_controler();
    $stylesArray = array();
    //echo 'custom/' . $_SESSION['custom_override_id'] . '/modules/templates/templates/styles';exit;
    if (is_dir('custom/' . $_SESSION['custom_override_id'] . '/modules/templates/templates/styles')) {
       $dir = 'custom/' . $_SESSION['custom_override_id'] . '/modules/templates/templates/styles';
    } else {
        $dir = 'modules/templates/templates/styles/';
    }
    $stylesArray = $templatesControler->getTemplatesStyles(
        $dir, 
        $stylesArray
    );
    $xmlfile = 'modules/templates/xml/datasources.xml';
    $xmlfileCustom = $_SESSION['config']['corepath'] 
    . 'custom/' . $_SESSION['custom_override_id'] . '/' . $xmlfile;
     if (file_exists($xmlfileCustom)) {
        $xmlfile = $xmlfileCustom;
    }
    $datasourcesArray = array();
    $datasourcesArray = $templatesControler->getTemplatesDatasources($xmlfile);
    $targetsArray = array();
    $targetsArray = $templatesControler->getTemplatesTargets();
    require_once 'modules/entities/class/EntityControler.php';
    $entityControler = new EntityControler();
    $entitiesOrg = $entityControler->getAllEntities();
    At_putInSession('templatesStyles', $stylesArray);
    At_putInSession('templatesTargets', $targetsArray);
    At_putInSession('templatesDatasources', $datasourcesArray);
    At_putInSession('templatesEntitiesOrg', $entitiesOrg);
}

/**
 * Initialize session parameters for list display
 */
function display_list()
{
    $pageName = 'templates_management_controler';
    $idName = 'template_id';
    
    $_SESSION['m_admin'] = array();
    
    init_session();
    
    $select[_TEMPLATES_TABLE_NAME] = array();
    array_push(
        $select[_TEMPLATES_TABLE_NAME], 
        $idName, 
        'template_label', 
        'template_comment',
        'template_type'
    );
    $what = '';
    $where ='';
    $arrayPDO = array();
    if (isset($_REQUEST['what']) && !empty($_REQUEST['what'])) {
        $func = new functions();
        $what = $_REQUEST['what'];
        if ($_SESSION['config']['databasetype'] == 'POSTGRESQL') {
            $where = "template_label ilike ? ";
        } else {
            $where = "template_label like ? ";
        }
        $arrayPDO = array_merge($arrayPDO, array(strtoupper($what)."%"));
    }

    // Checking order and order_field values
    $order = 'asc';
    if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {
        $order = trim($_REQUEST['order']);
    }
    $field = $idName;
    if (isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field'])) {
        $field = trim($_REQUEST['order_field']);
    }
    $listShow = new list_show();
    $orderstr = $listShow->define_order($order, $field);
    $request = new request();
    $tab = $request->PDOselect($select,$where,$arrayPDO,$orderstr,$_SESSION['config']['databasetype']);
    for ($i=0;$i<count($tab);$i++) {
        foreach($tab[$i] as &$item) {
            switch ($item['column']) {
                case $idName:
                    At_formatItem($item, _TEMPLATE_ID,'10','left','left','bottom',true); break;
                case 'template_label':
                    At_formatItem($item, _TEMPLATE_LABEL,'30','left','left','bottom',true); break;
                case 'template_comment':
                    At_formatItem($item, _TEMPLATE_COMMENT,'40','left','left','bottom',true); break;
                case 'template_type':
                    At_formatItem($item, _TEMPLATE_TYPE,'10','left','left','bottom',true); break;
            }
        }    
    }
     
    $result = array();
    $result['tab']=$tab;
    $result['what']=$what;
    $result['page_name'] = $pageName.'&mode=list';
    $result['page_name_up'] = $pageName.'&mode=up';
    $result['page_name_del'] = $pageName.'&mode=del';
    $result['page_name_add'] = $pageName.'&mode=add';
    $result['label_add'] = _TEMPLATE_ADDITION;
    $_SESSION['m_admin']['init'] = true;
    $result['title'] = _TEMPLATES_LIST . ' : ' . count($tab) . ' ' . _TEMPLATES;
    $result['autoCompletionArray'] = array();
    $result['autoCompletionArray']['list_script_url'] 
        = $_SESSION['config']['businessappurl'] . 'index.php?display=true'
            . '&module=templates&page=templates_list_by_label';
    $result['autoCompletionArray']['number_to_begin'] = 1;
    return $result;
}

/**
 * Delete given docserver if exists and initialize session parameters
 * @param string $template_id
 */
function display_del($template_id)
{
    $templatesControler = new templates_controler();
    $templates = $templatesControler->get($template_id);
    if (isset($templates)) {
        // Deletion
        $control = array();
        $control = $templatesControler->delete($templates);
        if (!empty($control['error']) && $control['error'] <> 1) {
            $_SESSION['error'] = str_replace('#', '<br />', $control['error']);
        } else {
            $_SESSION['info'] = _TEMPLATE_DELETED . ' ' . $template_id;
        }
        $pageName = 'templates_management_controler';
        ?>
        <script type='text/javascript'>window.top.location='<?php 
            echo $_SESSION['config']['businessappurl'] . 'index.php?page=' 
                . $pageName . '&mode=list&module=templates';?>';</script>
        <?php
        exit;
    } else {
        // Error management
        $_SESSION['error'] = _TEMPLATE . ' ' . _UNKNOWN;
    }
}
