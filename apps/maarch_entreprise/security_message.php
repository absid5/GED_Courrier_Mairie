<?php
/*
*   Copyright 2008-2015 Maarch
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
* @brief  security message page
* 
* @file
* @author  Laurent Giovannoni  <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/

$core_tools2 = new core_tools();
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
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=security_message';
$page_label = _SECURITY_MESSAGE;
$page_id = "security_message";
$core_tools2->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
?>
<h1>
<?php echo _SECURITY_MESSAGE;?></h1>
<div id="inner_content" class="clearfix">
<?php
echo '<h3>' . _SECURITY_MESSAGE_DETAILS . ' !</h3>';
if ($_SESSION['config']['debug'] == 'true') {
    echo $_SESSION['securityMessage'];
}
?>
</div>
