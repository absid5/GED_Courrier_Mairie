<?php
/*
*    Copyright 2008,2009 Maarch
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
* @brief  Advanced search form error page
*
* @file search_adv_error.php
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup indexing_searching_mlb
*/
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");

$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();

$mode = 'normal';
if(isset($_REQUEST['mode'])&& !empty($_REQUEST['mode']))
{
    $mode = $core_tools->wash($_REQUEST['mode'], "alphanum", _MODE);
}
if($mode == 'normal')
{
    $core_tools->test_service('adv_search_mlb', 'apps');
/****************Management of the location bar  ************/
$init = false;
if(isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true")
{
    $init = true;
}
$level = "3";
if(isset($_REQUEST['level'] ) && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1))
{
    $level = 3 ;
}
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=search_adv_result&dir=indexing_searching';
$page_label = _RESULTS;
$page_id = "search_adv_result_apps";
$core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
}
elseif($mode == 'popup' || $mode == 'frame')
{
    $core_tools->load_html();
    $core_tools->load_header();
    $time = $core_tools->get_session_time_expire('', true, false);
    ?><body>
    <div id="container">

            <div class="error" id="main_error">
                <?php functions::xecho($_SESSION['error']);?>
            </div>
            <div class="info" id="main_info">
                <?php functions::xecho($_SESSION['info']);?>
            </div><?php
}
?>
<h1><i class="fa fa-search fa-2x"></i> <?php echo _ADV_SEARCH_TITLE;?></h1>
<div id="inner_content">
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <?php echo($_SESSION['error_search']);
    $_SESSION['error_search'] = "";
    ?>
</div>
<?php if($mode == 'popup' || $mode == 'frame')
{
    echo '</div>';
    if($mode == 'popup')
    {
    ?><br/><div align="center"><input type="button" name="close" class="button" value="<?php echo _CLOSE_WINDOW;?>" onclick="self.close();" /></div> <?php
    }
    $core_tools->load_js();
    echo '</body></html>';
}
