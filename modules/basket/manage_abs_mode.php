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
* @brief Sets a user in missing mode
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup basket
*/

$core_tools = new core_tools();
$core_tools->load_lang();

if(isset($_REQUEST['submit']) && isset($_REQUEST['user_id']) && !empty($_REQUEST['user_id']))
{
	$db = new Database();

	require_once('modules'.DIRECTORY_SEPARATOR.'basket'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_modules_tools.php');
	$stmt = $db->query("UPDATE ".$_SESSION['tablename']['users']." set status = 'ABS' WHERE user_id = ?",array($_REQUEST['user_id']));
	
	if($_SESSION['history']['userabs'] == "true")
	{
		require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
		$history = new history();
		$stmt = $db->query("SELECT firstname, lastname FROM ".$_SESSION['tablename']['users']." WHERE user_id = ?",array($_REQUEST['user_id']));
		$res = $stmt->fetchObject();
		$history->add($_SESSION['tablename']['users'],$_SESSION['user']['UserId'],"ABS",'userabs', _ABS_USER.' : '.$res->firstname.' '.$res->lastname, $_SESSION['config']['databasetype']);
	}


}
if($_REQUEST['user_id'] == $_SESSION['user']['UserId'])
{
?>
<script >window.top.location='<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&page=logout';</script>
<?php
}
else
{
?>	<script type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=users&admin=users';</script>	<?php
}?>
