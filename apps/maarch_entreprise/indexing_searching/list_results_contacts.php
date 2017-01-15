<?php
/*
*
*    Copyright 2014 Maarch
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
* @brief   Displays contacts list in search mode
*
* @file
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup basket
*/

require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR
            ."class".DIRECTORY_SEPARATOR."class_lists.php";

$core_tools = new core_tools();
$list       = new lists();

$core_tools->test_user();
$core_tools->test_service('search_contacts', 'apps');


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
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=list_results_contacts&dir=indexing_searching';
$page_label = _RESULTS;
$page_id = "search_contacts_result";
$core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/

$saveTool       = true;
$useTemplate    = false;
$exportTool     = true;

//List
$target = $_SESSION['config']['businessappurl'].'index.php?page=my_contacts&dir=my_contacts&mode=search';
$listContent = $list->loadList($target, true, 'divList', 'false');

?>

    <table width="100%" style="margin-bottom: -10px">
        <tr>
            <td align="right">
                <input class="button" type="button" align="right" value="<?php echo _SEARCH_ADDRESSES;?>" onclick="window.location.href='<?php echo $_SESSION['config']['businessappurl'] . 'index.php?page=list_results_addresses&dir=indexing_searching&fromSearchContacts'?>'"/>      
            </td>
        </tr>
    </table>

<?php

echo $listContent;