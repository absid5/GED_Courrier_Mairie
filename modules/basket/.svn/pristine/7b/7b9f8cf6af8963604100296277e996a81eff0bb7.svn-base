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
* @brief  Page displayed when reconnecting after an absence
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup basket
*/

if (isset($_POST['value']) && $_POST['value'] == "submit")
{
	
	$db = new Database();
	require_once('modules'.DIRECTORY_SEPARATOR.'basket'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_modules_tools.php');

	$bask = new basket();
	$bask->cancel_abs($_SESSION['user']['UserId']);

	$_SESSION['abs_user_status'] = false;
	if($_SESSION['history']['userabs'] == "true")
	{
		require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
		$history = new history();
		$stmt = $db->query("select firstname, lastname from ".$_SESSION['tablename']['users']." where user_id = ?",array($this_user));
		$res = $stmt->fetchObject();
		$history->add($_SESSION['tablename']['users'],$this_user,"RET",'userabs', $res->firstname." ".$res->lastname.' '._BACK_FROM_VACATION, $_SESSION['config']['databasetype']);
	}
	?>
		 <script type="text/javascript"> window.location.href="<?php echo $_SESSION['config']['businessappurl'];?>index.php";</script>
	<?php
	exit();
}
?>
<h1 ><i class="fa fa-question-circle"  align="middle" /></i><?php echo _MISSING_ADVERT_TITLE;?></h1>
<div id="inner_content" class="clearfix">
<h2 class="tit" align="center"><?php echo _MISSING_ADVERT_01;?></h2>
<p align="center"><?php echo _MISSING_ADVERT_02;?> </p>
<p align="center"><?php echo _MISSING_CHOOSE;?></p>

<form name="redirect_form" method="post" action="<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=advert_missing&module=basket">
	<p align="center">
    <input name="value" type="hidden" value="submit">
    <input name="cancel" type="submit"  value="<?php echo _CONTINUE;?>" align="middle" class="button" />
    <input name="cancel" type="button" value="<?php echo _CANCEL;?>" onclick="window.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&page=logout';" align="middle" class="button" />
    </p>
</form>
</div>
