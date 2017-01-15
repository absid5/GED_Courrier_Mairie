<?php
/*
*
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
* @brief Cancels the missing mode for a user
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup basket
*/

$core_tools = new core_tools();
$core_tools->load_lang();

if(isset($_REQUEST['submit']))
{
	$db = new Database();
	require_once('modules'.DIRECTORY_SEPARATOR.'basket'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_modules_tools.php');
	$bask = new basket();
	$bask->cancel_abs($_SESSION['m_admin']['users']['user_id']);

}
?>
<script >window.top.location='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=users_management_controler&mode=up&admin=users&id=<?php functions::xecho($_SESSION['m_admin']['users']['user_id']);?>&start=0&order=asc&order_field=&what=#top';</script>
