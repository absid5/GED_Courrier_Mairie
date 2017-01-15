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
*/

/**
* @brief   Displays document list in search mode
*
* @file
* @author Yves Christian Kpakpo <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup basket
*/

require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR
            ."class".DIRECTORY_SEPARATOR."class_lists.php";

$core_tools = new core_tools();
$list       = new lists();

//reset session current_basket clause
$_SESSION['current_basket']['clause']='';

//Parameters
$urlParameters = '';
    //Mode
    $mode = 'normal';
    if(isset($_REQUEST['mode'])&& !empty($_REQUEST['mode']))
    {
        $mode = $core_tools->wash($_REQUEST['mode'], "alphanum", _MODE);
        
    }
    $urlParameters .= '&mode='.$mode;

    //No details
    if(isset($_REQUEST['nodetails'])) {
        $urlParameters .= '&nodetails';
    }

    //module
    if(isset($_REQUEST['modulename'])) {
        $urlParameters .= '&modulename='.$_REQUEST['modulename'];
    }

    //Form
    if(isset($_REQUEST['action_form'])) {
        $urlParameters .= '&action_form='.$_REQUEST['action_form'];
    }

    if($_SESSION['save_list']['fromDetail'] == "true") {
        $urlParameters .= '&start='.$_SESSION['save_list']['start'];
        $urlParameters .= '&lines='.$_SESSION['save_list']['lines'];
        $urlParameters .= '&order='.$_SESSION['save_list']['order'];
        $urlParameters .= '&order_field='.$_SESSION['save_list']['order_field'];
        if ($_SESSION['save_list']['template'] <> "") {
        	$urlParameters .= '&template='.$_SESSION['save_list']['template'];
        }
        $_SESSION['save_list']['fromDetail'] = "false";
        $_SESSION['save_list']['url'] = $urlParameters;
    }
	$_SESSION['save_list']['start'] = "";
	$_SESSION['save_list']['lines'] = "";
	$_SESSION['save_list']['order'] = "";
	$_SESSION['save_list']['order_field'] = "";
	$_SESSION['save_list']['template'] = "";  
    
//Begin
if($mode == 'normal') {
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
    $page_path = $_SESSION['config']['businessappurl'].'index.php?page=list_results_mlb&dir=indexing_searching';
    $page_label = _RESULTS;
    $page_id = "search_adv_result_mlb";
    $core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
    /***********************************************************/
    
    $saveTool       = true;
    $useTemplate    = true;
    $exportTool     = true;
    
} else {
    $core_tools->load_html();
    $core_tools->load_header('', true, false);
    $time = $core_tools->get_session_time_expire();
    ?>
    <body>
    <div id="container">
        <div class="error" id="main_error"><?php functions::xecho($_SESSION['error']);?></div>
        <div class="info" id="main_info"><?php functions::xecho($_SESSION['info']);?></div>
    <?php
     $core_tools->load_js();
}

//List
$target = $_SESSION['config']['businessappurl'].'index.php?page=documents_list_mlb_search_adv&dir=indexing_searching'.$urlParameters;
$listContent = $list->loadList($target, true, 'divList', 'false');
echo '<br /><br />'.$listContent;

if($mode == 'popup' || $mode == 'frame')
{  
    echo '</div></body></html>';
}