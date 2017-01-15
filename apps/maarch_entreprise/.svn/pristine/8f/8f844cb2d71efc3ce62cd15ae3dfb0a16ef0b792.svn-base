<?php

/*
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

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_list_show.php");
$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();
$core_tools->test_service('reports', 'reports');
$db = new Database();

$req = new request();
$list = new list_show();

if(isset($_REQUEST['user']) && $_REQUEST['user'] != '')
{
	$stmt = $db->query('SELECT lastname, firstname FROM '.$_SESSION['tablename']['users']." WHERE user_id =?", array($_REQUEST['user']));
	if($stmt->rowCount() == 0)
	{
		?>
		<div class="error"><?php echo _USER.' '._UNKNOWN;?></div>
		<?php
	}

	$res = $stmt->fetchObject();
	$user_name = $res->firstname.' '.$res->lastname;

	$select[$_SESSION['tablename']['history']] = array();
	array_push($select[$_SESSION['tablename']['history']],'id','event_type','event_date' );
	$where = " (".$_SESSION['tablename']['history'].".event_type = 'LOGIN' or ".$_SESSION['tablename']['history'].".event_type = 'LOGOUT') AND ".$_SESSION['tablename']['history'].".user_id = ? ";
	$arrayPDO = array($_REQUEST['user']);
	$req = new request();
	$tab = $req->PDOselect($select, $where, $arrayPDO, " ORDER BY ".$_SESSION['tablename']['history'].".event_date DESC ", $_SESSION['config']['databasetype'], $limit="500",false);

	if (count($tab) > 0)
	{
		for ($i=0;$i<count($tab);$i++)
		{
			for ($j=0;$j<count($tab[$i]);$j++)
			{
				foreach(array_keys($tab[$i][$j]) as $value)
				{
					if($tab[$i][$j][$value] == "id")
					{
						$tab[$i][$j]["label"]=_ID;
						$tab[$i][$j]["size"]="20";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="left";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=true;
						//$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
						$tab[$i][$j]["value"]=$tab[$i][$j]['value'];
					}
					if($tab[$i][$j][$value]=="event_type"){
						$tab[$i][$j]["label"]=_ACTION;
						$tab[$i][$j]["size"]="30";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="center";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=true;
						//$tab[$i][$j]["value_export"] = $core_tools->is_var_in_history_keywords_tab($tab[$i][$j]['value']);
						$tab[$i][$j]["value"] = $core_tools->is_var_in_history_keywords_tab($tab[$i][$j]['value']);
					}

					if($tab[$i][$j][$value]=="event_date"){
						$tab[$i][$j]["label"]=_DATE;
						$tab[$i][$j]["size"]="30";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="center";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=true;
						//$tab[$i][$j]["value_export"] = $funct -> dateformat($tab[$i][$j]['value']);
						$tab[$i][$j]["value"] = functions::dateformat($tab[$i][$j]['value']);
					}
				}
			}
		}
		$title = _TITLE_STATS_USER_LOG.' :  '.$user_name.' ('.$_REQUEST['user'].')';
		?><div align="center"><?php $list->list_simple($tab, $i, $title, 'folder_id', 'istats_result', false, "", 'listing spec', '', 400, 500);  ?></div>
		<?php
	}
	else
	{
		$title = _TITLE_STATS_USER_LOG.' :  '.$user_name.' ('.$_REQUEST['user'].')';
		echo '<h3>'.functions::xssafe($title).'</h3>';
		?><div align="center"><?php echo _NO_RESULTS;?></div>
		<?php
	}
}
else
{
?>
	<div class="error"><?php echo _USER.' '._IS_EMPTY;?></div>
<?php
	exit();
}
