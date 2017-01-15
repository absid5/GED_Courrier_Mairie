<?php

if(isset($_REQUEST['entities']) && $_REQUEST['entities'] >= 0)
{
	$entities = explode('#', $_REQUEST['entities']);
	unset($entities[count($entities) -1]);
	$to_unset = array();
	for($i=0;$i<count($entities);$i++)
	{ 
		for($j=0; $j<count($_SESSION['m_admin']['entity']['entities']);$j++)
		{
			if(!empty($entities[$i]) && trim($entities[$i]) == trim($_SESSION['m_admin']['entity']['entities'][$j]['ENTITY_ID']))
			{
				array_push($to_unset, $j);
				break;
			}
		}
	}
	for($i=0;$i<count($to_unset);$i++)
	{
		unset($_SESSION['m_admin']['entity']['entities'][$to_unset[$i]]);
	}
	$_SESSION['m_admin']['entity']['entities'] = array_values($_SESSION['m_admin']['entity']['entities']);
	$_SESSION['m_admin']['load_entities'] = false;
	echo "{ status : 0 }";
}
else
{
	echo "{ status : 1 }";
}
?>
