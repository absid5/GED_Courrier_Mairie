<?php
if(!isset($_REQUEST['id_action']) || empty($_REQUEST['id_action']))
{
	echo "{status : 0, msg : '"._MISSING_ACTION_ID."'}";
	exit();
}

for($i=0; $i < count($_SESSION['m_admin']['basket']['all_actions']); $i++)
{
	if($_SESSION['m_admin']['basket']['all_actions'][$i]['ID'] == trim($_REQUEST['id_action']))
	{
		echo "{status : 2, msg :'"._SIMPLE_ACTION."'}";
		exit();
	}
}

echo "{status : 0, msg : '"._MISSING_ACTION_ID."'}";
exit();
?>
