<?php
/*
*   Copyright 2008-2011 Maarch
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
* @brief  French welcome page
* 
* @file
* @author  Loïc Vinet  <dev@maarch.org>
* @author  Claire Figueras  <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/

$_SESSION['FOLDER']['SEARCH'] = array();

$core_tools = new core_tools();
$_SESSION['location_bar']['level2']['path']	= "";
$_SESSION['location_bar']['level2']['label'] = "";
$_SESSION['location_bar']['level2']['id'] = "";
$_SESSION['location_bar']['level3']['path'] = "";
$_SESSION['location_bar']['level3']['label'] = "";
$_SESSION['location_bar']['level3']['id'] = "";
$_SESSION['location_bar']['level4']['path'] = "";
$_SESSION['location_bar']['level4']['label'] = "";
$_SESSION['location_bar']['level4']['id'] = "";
$core_tools->manage_location_bar();
?>
<h1><i class="fa fa-ellipsis-v fa-2x" style="opacity: 0"></i><?php echo _WELCOME;?></h1>
<div id="inner_content" class="clearfix">
<?php
$core_tools->execute_app_services($_SESSION['app_services'], "welcome.php");
$core_tools->execute_modules_services($_SESSION['modules_services'], 'welcome', "include");
?>
</div>
