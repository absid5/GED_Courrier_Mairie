<?php

if(isset($_REQUEST['usergroups']) && $_REQUEST['usergroups'] >= 0)
{
	$group_ind = explode('#', $_REQUEST['usergroups']);
	$group_id =$group_ind[0];
	for($i=0; $i < count($_SESSION['m_admin']['users']['groups']); $i++)
	{
		$_SESSION['m_admin']['users']['groups'][$i]["PRIMARY"] = 'N';
		if ( $_SESSION['m_admin']['users']['groups'][$i]["GROUP_ID"] == $group_id)
			$_SESSION['m_admin']['users']['groups'][$i]["PRIMARY"] = 'Y';
	}
	
	$_SESSION['m_admin']['load_group'] = false;
	echo "{ status : 0 }";
}
else
{
	echo "{ status : 1 }";
}
?>
