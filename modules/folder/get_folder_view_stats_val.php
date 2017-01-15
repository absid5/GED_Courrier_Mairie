<?php

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_list_show.php");
$core_tools = new core_tools();
$core_tools->load_lang();
$db = new Database();
$req = new request();
$list = new list_show();
if(isset($_REQUEST['type_report']) && $_REQUEST['type_report'] == 'foldertype')
{
	$stmt = $db->query("SELECT DISTINCT record_id FROM ".$_SESSION['tablename']['history']." WHERE event_type = 'VIEW' AND table_name = ? ", array($_SESSION['tablename']['fold_folders']));
	if($stmt->rowCount() > 0)
	{
		$where = ' f.folders_system_id in (';
			$arrayPDO = array();
		while($res = $stmt->fetchObject())
		{
            if(!empty($res->record_id)) {
                $where .= '?,';
                $arrayPDO = array_merge($arrayPDO, array($res->record_id));
            }
		}
		$where = preg_replace('/,$/', ')', $where);
		$stmt = $db->query("SELECT ft.foldertype_id, ft.foldertype_label, count(f.folders_system_id) as nbr 
							FROM  ".$_SESSION['tablename']['fold_folders']." f,  ".$_SESSION['tablename']['fold_foldertypes']." ft 
							WHERE f.foldertype_id = ft.foldertype_id and ".$where." group by ft.foldertype_label, ft.foldertype_id", $arrayPDO);
	}
	$tab=array();
	while($line = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$temp= array();
		foreach (array_keys($line) as $resval)
		{
			if (!is_int($resval))
			{
				array_push($temp,array('column'=>$resval,'value'=>$line[$resval]));
			}
		}
		array_push($tab,$temp);
	}
	if (count($tab) > 0)
	{
		for ($i=0;$i<count($tab);$i++)
		{
			for ($j=0;$j<count($tab[$i]);$j++)
			{
				foreach(array_keys($tab[$i][$j]) as $value)
				{
					if($tab[$i][$j][$value] == "foldertype_id")
					{
						$tab[$i][$j]["label"]=strtoupper(_ID);
						$tab[$i][$j]["size"]="10";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="center";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=true;
					//	$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
					}

					if($tab[$i][$j][$value]=="foldertype_label"){
						$tab[$i][$j]["label"]=strtoupper(_FOLDERTYPE);
						$tab[$i][$j]["size"]="50";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="left";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=true;
					//	$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
					}

					if($tab[$i][$j][$value]=="nbr"){
						$tab[$i][$j]["label"]=strtoupper(_NB_FOLDERS);
						$tab[$i][$j]["size"]="20";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="center";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=true;
					//	$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
					}
				}
			}
		}
		$title = _NB_VIEWED_FOLDERS.' '._TITLE_STATS_CHOICE_FOLDER_TYPE;
		?>
		<div align="center"><?php $list->list_simple($tab, $i, $title, 'foldertype_id', 'istats_result', false, '', 'listing spec', '', 400, 500);?></div>
		<?php
	}
} // FIN 
elseif(isset($_REQUEST['type_report']) && $_REQUEST['type_report'] == 'usergroup')
{
	$stmt = $db->query("SELECT g.group_id AS id, g.group_desc AS label, (SELECT COUNT(DISTINCT h.record_id) 
						FROM ".$_SESSION['tablename']['history']." h INNER JOIN ".$_SESSION['tablename']['usergroup_content']." u ON h.user_id = u.user_id 
						WHERE h.event_type = 'VIEW' AND h.table_name = ? AND u.group_id = g.group_id ) AS nbr 
						FROM ".$_SESSION['tablename']['usergroups']." g", array($_SESSION['tablename']['fold_folders']));

	$tab=array();
	while($line = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$temp= array();

		foreach (array_keys($line) as $resval)
		{
			if (!is_int($resval))
			{
				array_push($temp,array('column'=>$resval,'value'=>$line[$resval]));
			}
		}
		array_push($tab,$temp);
	}

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
						$tab[$i][$j]["label"]=strtoupper(_ID);
						$tab[$i][$j]["size"]="15";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="center";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=true;
						//$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
					}
					if($tab[$i][$j][$value]=="label")
					{
						$tab[$i][$j]["label"]=strtoupper(_GROUP);
						$tab[$i][$j]["size"]="60";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="left";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=true;
					//	$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
					}

					if($tab[$i][$j][$value]=="nbr")
					{
						$tab[$i][$j]["label"]=strtoupper(_NB_FOLDERS);
						$tab[$i][$j]["size"]="20";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="center";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=true;
						//$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
					}
				}
			}
		}
		$title = _FOLDER_VIEW_STAT.' '._TITLE_STATS_CHOICE_GROUP;
		?>
		<div align="center"><?php $list->list_simple($tab, $i, $title, 'id', 'istats_result', false, '', 'listing spec', '', 400, 500, '', false);?></div>
		<?php
	}

}// FIN elseif(isset($_REQUEST['type_report']) && $_REQUEST['type_report'] == 'usergroup')
elseif(isset($_REQUEST['type_report']) && $_REQUEST['type_report'] == 'user')
{
	$whereUser = "AND u.user_id = ''";
	if(isset($_REQUEST['user']) && $_REQUEST['user'] != '')
	{
		$stmt = $db->query("SELECT user_id FROM ".$_SESSION['tablename']['users']." WHERE user_id = ?", array($_REQUEST['user']));
		if($stmt->rowCount() == 0)
		{
			?>
			<div class="error"><?php echo _USER.' '._UNKNOWN;?></div>
			<?php
		}
		else
		{
			$whereUser = "AND u.user_id = :User";
			$arrayPDO = array(":foldFolder" => $_SESSION['tablename']['fold_folders'], ":User" => $_REQUEST['user']);
			$stmt = $db->query("SELECT u.user_id, u.lastname ,u.firstname, (SELECT COUNT(DISTINCT h.record_id) 
								FROM ".$_SESSION['tablename']['history']." h INNER JOIN ".$_SESSION['tablename']['users']." u ON h.user_id = u.user_id 
								WHERE h.event_type = 'VIEW' AND h.table_name = :foldFolder ".$whereUser.") AS nbr 
								FROM ".$_SESSION['tablename']['users']." u WHERE u.enabled = 'Y' ".$whereUser, $arrayPDO);

			$tab=array();
			while($line = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$temp= array();
				foreach (array_keys($line) as $resval)
				{
					if (!is_int($resval))
					{
						array_push($temp,array('column'=>$resval,'value'=>$line[$resval]));
					}
				}
				array_push($tab,$temp);
			}

			if (count($tab) > 0)
			{
				for ($i=0;$i<count($tab);$i++)
				{
					for ($j=0;$j<count($tab[$i]);$j++)
					{
						foreach(array_keys($tab[$i][$j]) as $value)
						{
							if($tab[$i][$j][$value] == "user_id")
							{
								$tab[$i][$j]["label"]=strtoupper(_ID);
								$tab[$i][$j]["size"]="15";
								$tab[$i][$j]["label_align"]="left";
								$tab[$i][$j]["align"]="center";
								$tab[$i][$j]["valign"]="bottom";
								$tab[$i][$j]["show"]=true;
								//$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
							}

							if($tab[$i][$j][$value]=="firstname")
							{
								$tab[$i][$j]["label"]=_FIRSTNAME_UPPERCASE;
								$tab[$i][$j]["size"]="20";
								$tab[$i][$j]["label_align"]="left";
								$tab[$i][$j]["align"]="left";
								$tab[$i][$j]["valign"]="bottom";
								$tab[$i][$j]["show"]=true;

								//$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
							}
							if($tab[$i][$j][$value]=="lastname")
							{
								$tab[$i][$j]["label"]=strtoupper(_LASTNAME);
								$tab[$i][$j]["size"]="20";
								$tab[$i][$j]["label_align"]="left";
								$tab[$i][$j]["align"]="left";
								$tab[$i][$j]["valign"]="bottom";
								$tab[$i][$j]["show"]=true;
								//$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
							}

							if($tab[$i][$j][$value]=="nbr")
							{
								$tab[$i][$j]["label"]=strtoupper(_NB_FOLDERS);
								$tab[$i][$j]["size"]="20";
								$tab[$i][$j]["label_align"]="left";
								$tab[$i][$j]["align"]="center";
								$tab[$i][$j]["valign"]="bottom";
								$tab[$i][$j]["show"]=true;
								//$tab[$i][$j]["value_export"] = $tab[$i][$j]['value'];
							}
						}
					}
				}
			}
			$title = _FOLDER_VIEW_STAT.' '._TITLE_STATS_CHOICE_USER2.' "'.$_REQUEST['user'].'" : '.$line['nbr'];
			?>
			<div align="center"><?php $list->list_simple($tab, $i, $title, 'id', 'istats_result', false, '', 'listing spec', '', 400, 500, '', false);?></div>
			<?php
		}
	}
	else
	{
		?>
			<div class="error"><?php echo _USER.' '._IS_EMPTY;?></div>
		<?php
	}
} //FIN elseif(isset($_REQUEST['type_report']) && $_REQUEST['type_report'] == 'user')
elseif(isset($_REQUEST['type_report']) && $_REQUEST['type_report'] == 'period')
{
	$requestdate = '';
	$periodTitle = '';
	$periodTitle2 = '';
	$arrayPDO = array();
	if(isset($_REQUEST['date_start']) && $_REQUEST['date_start'] <> ''){
		$requestdate  .= " AND ".$req->extract_date('event_date')." > :dateStart";
		$arrayPDO = array_merge($arrayPDO, array(":dateStart" => functions::format_date_db($_REQUEST['date_start'])));
		$periodTitle.= _TITLE_STATS_DU.' '.$_REQUEST['date_start'].' ';
		$periodTitle2.= strtolower(_SINCE).' '.$_REQUEST['date_start'].' ';
	}

	if(isset($_REQUEST['date_fin']) && $_REQUEST['date_fin'] <> ''){
		$requestdate  .= " AND ".$req->extract_date('event_date')." < :dateFin";
		$arrayPDO = array_merge($arrayPDO, array(":dateFin" => functions::format_date_db($_REQUEST['date_start'])));
		$periodTitle.= _TITLE_STATS_DU.' '.$_REQUEST['date_fin'].' ';
		$periodTitle2.= strtolower(_FOR).' '.$_REQUEST['date_fin'].' ';
	}

	$arrayPDO = array_merge($arrayPDO, array(":foldFolder" => $_SESSION['tablename']['fold_folders']));
	$stmt = $db->query("SELECT COUNT(DISTINCT record_id) AS nbr 
						FROM ".$_SESSION['tablename']['history']." 
						WHERE event_type = 'VIEW' AND table_name = :foldFolder ".$requestdate, $arrayPDO );

	$line = $stmt->fetch(PDO::FETCH_ASSOC);

	if($line['nbr'] > 0)
	{
		$title = _NB_VIEWED_FOLDERS.' '.$periodTitle2.' : '.$line['nbr'];
	}
	else
	{
		$title = _TITLE_STATS_NO_FOLDERS_VIEW.'<br/>'.$periodTitle;
	}

	echo '<br/><h2>'.$title.'</h2>';
}

