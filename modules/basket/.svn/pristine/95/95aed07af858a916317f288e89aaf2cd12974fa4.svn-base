<?php
/*
*
*    Copyright 2008-2015 Maarch
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
* @brief Manages the redirection of baskets during missing mode
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup basket
*/

$_SESSION['error'] = "";
$core_tools = new core_tools();
$core_tools->load_lang();
require_once('modules'.DIRECTORY_SEPARATOR.'basket'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_modules_tools.php');
$db = new Database();
$bask = new basket();
$res = array();
//print_r($_REQUEST);
if(isset($_REQUEST['baskets_owner']) && !empty($_REQUEST['baskets_owner']))
{
	$baskets_owner = trim($_REQUEST['baskets_owner']);
	$nb_baskets = $bask->get_numbers_of_baskets($baskets_owner);
	//echo 'nb_baskets '.$nb_baskets."<br/>";
	for($i=0; $i< $nb_baskets ;$i++)
	{
		if(isset($_REQUEST['user_'.$i]) && !empty($_REQUEST['user_'.$i]))
		{
			$tmp = trim($_REQUEST['user_'.$i]);
			$user_id = substr($tmp, strpos($tmp, '(') +1);

			$user_id = str_replace(')', '', $user_id);
			$stmt = $db->query("select user_id from ".$_SESSION['tablename']['users']." where user_id = ?  and enabled = 'Y'",array($user_id)); //and status = 'OK'
			
			if($stmt->rowCount() == 1)
			{
				if(isset($_REQUEST['basket_'.$i]) && !empty($_REQUEST['basket_'.$i]))
				{
					if(isset($_REQUEST['virtual_'.$i]) && !empty($_REQUEST['virtual_'.$i]))
					{
						$virtual = trim($_REQUEST['virtual_'.$i]);
					}
					else
					{
						$virtual = 'N';
					}

					if(isset($_REQUEST['originalowner_'.$i]) && !empty($_REQUEST['originalowner_'.$i]))
					{
						$original_owner = trim($_REQUEST['originalowner_'.$i]);
					}
					else
					{
						$original_owner = $baskets_owner;
					}
					$basket_id = trim($_REQUEST['basket_'.$i]);
					if(preg_match('/_/', $basket_id))
					{
						$basket_id = preg_replace('/_.+$/', '', $basket_id);
					}
					array_push($res, array('basket_id' => $basket_id, 'user_id' => $user_id, 'is_virtual' => $virtual, 'basket_owner' => $original_owner));
				}
				else
				{
					$_SESSION['error'] .= _ERROR_WITH_BASKET_ID;
				}

			}
			else
			{
				$_SESSION['error'] .= _ERROR_WITH_USER.' '.$tmp;
			}
		}
	}
}
else
{
	$_SESSION['error'] = _BASKETS_OWNER.' '._MISSING;
}
if(!empty($_SESSION['error']))
{
	echo $_SESSION['error'];
}
else
{
	for($i=0; $i<count($res);$i++)
	{
		$stmt = $db->query("INSERT into ".$_SESSION['tablename']['bask_users_abs']." (user_abs, new_user, basket_id, is_virtual, basket_owner) VALUES (?, ?, ?, ?, ?)",array($baskets_owner,$res[$i]['user_id'],$res[$i]['basket_id'],$res[$i]['is_virtual'],$res[$i]['basket_owner']));
	}
	$stmt = $db->query("UPDATE ".$_SESSION['tablename']['users']." set status = 'ABS' where user_id = ?",array($baskets_owner));
	if($_SESSION['history']['userabs'] == "true")
	{
		require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
		$history = new history();
		$stmt = $db->query("select firstname, lastname from ".$_SESSION['tablename']['users']." where user_id = ?",array($baskets_owner));
		$res = $stmt->fetchObject();
		$history->add($_SESSION['tablename']['users'],$baskets_owner,"ABS",'userabs', _ABS_USER.' : '.$res->firstname.' '.$res->lastname, $_SESSION['config']['databasetype']);
	}
	if($baskets_owner == $_SESSION['user']['UserId'])
	{
		?>
		<script type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&page=logout&abs_mode';</script>
		<?php
	}
	else
	{
		?>
		<script type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=users_management_controler&admin=users&mode=list';</script>
		<?php
	}
	exit();
}
?>
