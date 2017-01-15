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
* @brief   Docserver Administration summary Page
*
* Architecture Administration summary Page
*
* @file
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

$admin = new core_tools();
$admin->test_admin('admin_docservers', 'apps');
/****************Management of the location bar  ************/
$init = false;
if(isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true") {
	$init = true;
}
$level = "";
if(isset($_REQUEST['level']) && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1)) {
	$level = $_REQUEST['level'];
}
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=admin_docservers&admin=docservers';
$page_label = _ADMIN_DOCSERVERS;
$page_id = "admin_docservers";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
unset($_SESSION['m_admin']);
?>
<h1><i class="fa fa-hdd-o fa-2x"></i> <?php echo _ADMIN_DOCSERVERS;?></h1>
<div id="inner_content" class="clearfix">
<div class="block">
	<h2><?php echo _ADMIN_DOCSERVERS;?></h2>
	<div class="admin_item" title="<?php echo _MANAGE_DOCSERVERS_LOCATIONS_DESC;?>" onclick="window.top.location='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=docserver_locations_management_controler&mode=list&admin=docservers';">
		<div>
			<i class="fa fa-hdd-o fa-4x"></i><br />
			<strong><?php echo _MANAGE_DOCSERVERS_LOCATIONS;?></strong>
		</div>
	</div>
	<div class="admin_item" title="<?php echo _MANAGE_DOCSERVERS_DESC;?>" onclick="window.top.location='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=docservers_management_controler&mode=list&admin=docservers';">
		<div>
			<i class="fa fa-hdd-o fa-4x"></i><br />
			<strong><?php echo _MANAGE_DOCSERVERS;?></strong>
		</div>
	</div>
	<div class="admin_item" title="<?php echo _MANAGE_DOCSERVER_TYPES_DESC;?>" onclick="window.top.location='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=docserver_types_management_controler&mode=list&admin=docservers';">
		<div>
			<i class="fa fa-hdd-o fa-4x"></i><br />
			<strong><?php echo _MANAGE_DOCSERVER_TYPES;?></strong>
		</div>
	</div>
	<div class="clearfix"></div>
	</div>
</div>
