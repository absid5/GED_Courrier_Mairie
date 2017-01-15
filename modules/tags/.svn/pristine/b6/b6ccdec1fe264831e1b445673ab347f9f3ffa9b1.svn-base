<?php
/*
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
* Module : Tags
* 
* This module is used to store ressources with any keywords
* V: 1.0
*
* @file
* @author Loic Vinet
* @date $date$
* @version $Revision$
* 
* 
* Icones d'administration des tags
*/


$admin = new core_tools();
$admin->test_admin('admin_tag', 'tags');
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
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=admin_tag&module=tags';
$page_label = _TAG_DEFAULT;
$page_id = "admin_tag";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
unset($_SESSION['m_admin']);
?>
<h1><i class="fa fa-tags fa-2x"> </i> <?php echo _TAG_ADMIN;?></h1>
<div id="inner_content" class="clearfix">
    <h2 class="admin_subtitle block" ><?php echo _ADMIN_NOTIFICATIONS;?></h2>
    <div  class="admin_item" id="admin_structures" title="<?php echo _MANAGE_EVENTS;?>" onclick="window.top.location='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=manage_events_list_controller&module=notifications&mode=list';">
        <div class="sum_margin">
                <strong><?php echo _MANAGE_EVENTS;?></strong><br/>
                <em><?php echo _MANAGE_EVENTS_DESC;?></em>
        </div>
    </div>

    <div class="admin_item" id="admin_subfolders" title="<?php echo _TEST_SENDMAIL;?>" onclick="window.top.location='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=test_sendmail&module=notifications';">
        <div class="sum_margin">
                <strong><?php echo _TEST_SENDMAIL;?></strong><br/>
                <em><?php echo _TEST_SENDMAIL_DESC;?></em>
         </div>
    </div>

  
</div>
