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
* File : admin_entities.php
*
* @brief Entities Administration summary Page
*
* @package  Maarch Framework 3.0
* @version 1
* @since 03/2009
* @license GPL
* @author  Cédric Ndoumba  <dev@maarch.org>
*/

$admin = new core_tools();
$admin->test_admin('manage_entities', 'entities');
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
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=admin_entities&module=entities';
$page_label = _ENTITIES;
$page_id = "admin_entities";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
unset($_SESSION['m_admin']);

?>
<h1><i class="fa fa-sitemap fa-2x"></i> <?php echo _ENTITIES;?></h1>

<div id="inner_content" class="clearfix">
<div class="block">
<h2 style="text-align:center;"><?php echo _ENTITIES;?></h2>
    <div class="admin_item" title="<?php echo _MANAGE_ENTITIES_DESC;?>" onclick="window.top.location='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=manage_entities&amp;module=entities';">
        <div><i class="fa fa-sitemap fa-4x"></i></div>
        <div>
                <strong><?php echo _MANAGE_ENTITIES;?></strong>
        </div>
    </div>
    <div class="admin_item" title="<?php echo _ENTITY_TREE_DESC;?>" onclick="window.top.location='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=view_tree_entities&amp;module=entities';">
        <div><i class="fa fa-code-fork fa-4x"></i></div>
        <div>
                <strong><?php echo _ENTITY_TREE;?></strong>
         </div>
    </div>
    <?php if($admin->test_admin('admin_difflist_types', 'entities', false)){ ?>
    <div class="admin_item" title="<?php echo _DIFFLIST_TYPES_DESC;?>" onclick="window.top.location='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=admin_difflist_types&amp;module=entities';">
        <div><i class="fa fa-share-alt fa-4x"></i></div>
        <div>
                <strong><?php echo _DIFFLIST_TYPES;?></strong>
         </div>
    </div>
    <?php } ?>
    <?php if($admin->test_admin('admin_listmodels', 'entities', false)){ ?>
    <div class="admin_item" title="<?php echo _LISTMODELS_DESC;?>" onclick="window.top.location='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=admin_listmodels&amp;module=entities';">
        <div><i class="fa fa-share-alt-square fa-4x"></i></div>
        <div>
                <strong><?php echo _LISTMODELS;?></strong>
         </div>
    </div>
    <?php } ?>
<div class="clearfix"></div>
    </div>
</div>
