<?php

if(isset($_REQUEST['usergroups']) && $_REQUEST['usergroups'] >= 0)
{
	$group_ind = explode('#', $_REQUEST['usergroups']);
	unset($group_ind[count($group_ind) -1]);
	$to_unset = array();
	for($i=0;$i<count($group_ind);$i++)
	{ 
		for($j=0; $j<count($_SESSION['m_admin']['users']['groups']);$j++)
		{
			if(!empty($group_ind[$i]) && trim($group_ind[$i]) == trim($_SESSION['m_admin']['users']['groups'][$j]['GROUP_ID']))
			{
				array_push($to_unset, $j);
				break;
			}
		}
	}
	for($i=0;$i<count($to_unset);$i++)
	{
		unset($_SESSION['m_admin']['users']['groups'][$to_unset[$i]]);
	}
	$_SESSION['m_admin']['users']['groups'] = array_values($_SESSION['m_admin']['users']['groups']);
	$_SESSION['m_admin']['load_group'] = false;
	echo "{ status : 0 }";
}
else
{
	echo "{ status : 1 }";
}
?>
