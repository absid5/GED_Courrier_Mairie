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
* @brief   Architecture Administration summary Page
*
* Architecture Administration summary Page
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

$admin = new core_tools();
$admin->test_admin('admin_architecture', 'apps');
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
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=admin_archi&admin=architecture';
$page_label = _ARCHITECTURE;
$page_id = "admin_archi";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
unset($_SESSION['m_admin']);
?>
<h1><i class="fa fa-suitcase fa-2x"></i> <?php echo _ADMIN_ARCHI;?></h1>
<div id="inner_content" class="clearfix">
<div class="block">
    <h2 style="text-align:center;"><?php echo _ARCHITECTURE;?></h2>
    <div  class="admin_item" title="<?php echo _MANAGE_STRUCTURE_DESC;?>" onclick="window.top.location='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=structures';">
               <div><i class="fa fa-folder fa-4x"></i> </div>
        <div>
                <strong><?php echo _MANAGE_STRUCTURE;?></strong><!--<br/>
                <em><?php echo _MANAGE_STRUCTURE_DESC;?></em>-->
        </div>
    </div>

    <div class="admin_item" title="<?php echo _MANAGE_SUBFOLDER_DESC;?>" onclick="window.top.location='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=subfolders';">
             <div><i class="fa fa-folder-open fa-4x"></i></div>
        <div>
                <strong><?php echo _MANAGE_SUBFOLDER;?></strong><!--<br/>
                <em><?php echo _MANAGE_SUBFOLDER_DESC;?></em>-->
         </div>
    </div>

    <div class="admin_item" title="<?php echo _MANAGE_DOCTYPES_DESC;?>" onclick="window.top.location='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=types';">
        <div><i class="fa fa-files-o fa-4x"></i></div>
        <div>
        
                <strong><?php echo _MANAGE_DOCTYPES;?></strong><!--<br/>
                <em><?php echo _MANAGE_DOCTYPES_DESC;?></em>-->
         </div>
    </div>

    <div class="admin_item" title="<?php echo _VIEW_TREE_DOCTYPES_DESC;?>" onclick="window.top.location='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=view_tree_types&admin=architecture';">
        <div><i class="fa fa-code-fork fa-4x"></i></div> 
        <div>
        
                <strong><?php echo _VIEW_TREE_DOCTYPES;?></strong><!--<br/>
                <em><?php echo _VIEW_TREE_DOCTYPES_DESC;?></em>-->
         </div>
    </div>
<div class="clearfix"></div>
</div>
</div>


