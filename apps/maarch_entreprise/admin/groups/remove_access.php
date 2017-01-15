<?php

if(isset($_REQUEST['security']) && $_REQUEST['security'] >= 0)
{
	$access_ind = explode('#', $_REQUEST['security']);
	
	for($i=0;$i<count($access_ind);$i++)
	{
		if($access_ind <> '')
		{
			unset($_SESSION['m_admin']['groups']['security'][$access_ind[$i]]);
		}
	}
	$_SESSION['m_admin']['groups']['security'] = array_values($_SESSION['m_admin']['groups']['security']);
	$_SESSION['m_admin']['load_security'] = false;
	echo "{ status : 0 }";
}
else
{
	echo "{ status : 1 }";
}
?>
