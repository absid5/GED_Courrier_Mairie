<?php
/*
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
*/

/**
* @brief Contacts Administration view tree
*
* @file
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

$admin = new core_tools();
$admin->test_admin('admin_contacts', 'apps');

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
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=view_tree_contacts';
$page_label = _VIEW_TREE_CONTACTS;
$page_id = "view_tree_contacts";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
unset($_SESSION['m_admin']);
?>

<h1><i class="fa fa-code-fork fa-2x"></i> <?php echo _VIEW_TREE_CONTACTS;?></h1>
<div id="inner_content" class="clearfix">
	<div class="block">
    <table width="100%" border="0">
        <tr>
            <td>
                <iframe name="show_trees" id="show_trees" width="900px" height="650" frameborder="0" scrolling="auto" src="<?php echo $_SESSION['config']['businessappurl']."index.php?display=true&page=show_tree_contacts";?>"></iframe>
            </td>
        </tr>
    </table>
    </div>
</div>
