<?php

if(isset($_REQUEST['entities']) && $_REQUEST['entities'] >= 0)
{
	$entities = explode('#', $_REQUEST['entities']);
	$entity_id =$entities[0];
	for($i=0; $i < count($_SESSION['m_admin']['entity']['entities']); $i++)
	{
		$_SESSION['m_admin']['entity']['entities'][$i]['PRIMARY'] = 'N';
		if ( $_SESSION['m_admin']['entity']['entities'][$i]['ENTITY_ID'] == $entity_id)
			$_SESSION['m_admin']['entity']['entities'][$i]['PRIMARY'] = 'Y';
	}
	
	$_SESSION['m_admin']['load_entities'] = false;
	echo "{ status : 0 }";
}
else
{
	echo "{ status : 1 }";
}
?>
