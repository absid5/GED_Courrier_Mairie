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
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_manage_status.php");
require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_list_show.php");
require_once('modules'.DIRECTORY_SEPARATOR.'reports'.DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_graphics.php");
$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();
$core_tools->test_service('reports', 'reports');
$db = new Database();
$req = new request();
$list = new list_show();
$graph = new graphics();
$sec = new security();

$entitiesChosen = str_replace("'", "", $_POST['entities_chosen']);
$entities_chosen=explode("#",$entitiesChosen);
// $entities_chosen=join(",",$entities_chosen);

$status_obj = new manage_status();
$ind_coll = $sec->get_ind_collection('letterbox_coll');
$table = $_SESSION['collections'][$ind_coll]['table'];
$view = $_SESSION['collections'][$ind_coll]['view'];
$search_status = $status_obj->get_searchable_status();

//print_r($search_status);

$id_report = $_REQUEST['id_report'];
if(empty($id_report))
{
	?>
	<div class="error"><?php echo _REPORT.' '._UNKNOWN;?></div>
	<?php
	exit();
}

$report_type = $_REQUEST['report_type'];
if(empty($report_type))
{
	?>
	<div class="error"><?php echo _ERROR_REPORT_TYPE;?></div>
	<?php
	exit();
}

$period_type = $_REQUEST['period_type'];
if(empty($period_type))
{
	?>
	<div class="error"><?php echo _ERROR_PERIOD_TYPE;?></div>
	<?php
	exit();
}
$default_year = date('Y');
$where_date = '';
$arrayPDO = array();
$date_title = '';

$arrayPDOStatus = array();
$str_status = '(';
	for($i=0;$i<count($search_status);$i++)
	{
		$str_status .= "?,";
		$arrayPDOStatus = array_merge($arrayPDOStatus, array($search_status[$i]['ID']));
	}
	$str_status = preg_replace('/,$/', ')', $str_status);
	$str_status2 = "('COU','END','NEW','RET','SIG','UNS','VAL','VIS','SMART','MAQUAL','BRSAS','XML','XML_SENT','WAIT_REPLY','BAP','DAV','AVD','APP')";

	if($period_type == 'period_year')
	{
		if(empty($_REQUEST['the_year']) || !isset($_REQUEST['the_year']))
		{
			?>
			<div class="error"><?php echo _YEAR.' '._MISSING;?></div>
			<?php
			exit();
		}
		if(	!preg_match('/^[1-2](0|9)[0-9][0-9]$/', $_REQUEST['the_year']))
		{
			?>
			<div class="error"><?php echo _YEAR.' '._WRONG_FORMAT;?></div>
			<?php
			exit();
		}
		
		$where_date = $req->extract_date('creation_date', 'year')." = :creation_date_year";
		$arrayPDO = array_merge($arrayPDO, array(":creation_date_year" =>$_REQUEST['the_year']));
		
		$date_title = _FOR_YEAR.' '.$_REQUEST['the_year'];
	}
	else if($period_type == 'period_month')
	{
		$arr_month = array('01','02','03','04','05','06','07','08','09','10','11','12');
		if(empty($_REQUEST['the_month']) || !isset($_REQUEST['the_month']))
		{
			?>
			<div class="error"><?php echo _MONTH.' '._MISSING;?></div>
			<?php
			exit();
		}
		if(	!in_array($_REQUEST['the_month'], $arr_month))
		{
			?>
			<div class="error"><?php echo _MONTH.' '._WRONG_FORMAT;?></div>
			<?php
			exit();
		}
		$where_date = $req->extract_date('creation_date', 'year')." = '".$default_year."' and ".$req->extract_date('creation_date', 'month')." = :creation_date_month";
		$arrayPDO = array_merge($arrayPDO, array(":creation_date_month" => $_REQUEST['the_month']));
		$month = '';
		switch($_REQUEST['the_month'])
		{
			case '01':
			$month = _JANUARY;
			break;
			case '02':
			$month = _FEBRUARY;
			break;
			case '03':
			$month = _MARCH;
			break;
			case '04':
			$month = _APRIL;
			break;
			case '05':
			$month = _MAY;
			break;
			case '06':
			$month = _JUNE;
			break;
			case '07':
			$month = _JULY;
			break;
			case '08':
			$month = _AUGUST;
			break;
			case '09':
			$month = _SEPTEMBER;
			break;
			case '10':
			$month = _OCTOBER;
			break;
			case '11':
			$month = _NOVEMBER;
			case '12':
			$month = _DECEMBER;
			break;
			default:
			$month = '';
		}
		$date_title = _FOR_MONTH.' '.$month;
	}
	else if($period_type == 'custom_period')
	{
		if(isset($_REQUEST['date_start']) && $_REQUEST['date_start'] <> '')
		{
			$where_date  .= " AND ".$req->extract_date('creation_date')." > :date_start";
			$arrayPDO = array_merge($arrayPDO, array(":date_start" => functions::format_date_db($_REQUEST['date_start'])));
			$date_title .= strtolower(_SINCE).' '.$_REQUEST['date_start'].' ';
		}

		if(isset($_REQUEST['date_fin']) && $_REQUEST['date_fin'] <> '')
		{
			$where_date  .= " AND ".$req->extract_date('creation_date')." < :date_fin";
			$arrayPDO = array_merge($arrayPDO, array(":date_fin" => functions::format_date_db($_REQUEST['date_fin'])));
			$date_title.= strtolower(_FOR).' '.$_REQUEST['date_fin'].' ';
		}
		if(empty($where_date))
		{
			$where_date = $req->extract_date('creation_date', 'year')." = '".$default_year."'";
			$date_title = _FOR_YEAR.' '.$default_year;
		}
	}
	else
	{
		?>
		<div class="error"><?php echo _PERIOD.' '._MISSING;?></div>
		<?php
		exit();
	}

	$where_date = preg_replace('/^ AND/', '', $where_date);

	if($id_report == 'process_delay')
	{
	
		if (!$_REQUEST['entities_chosen']){
	    	$stmt = $db->query("SELECT type_id, description FROM ".$_SESSION['tablename']['doctypes']." WHERE enabled = 'Y' order by description");
		}else{
		    $stmt = $db->query("SELECT type_id, description FROM ".$_SESSION['tablename']['doctypes']." WHERE enabled = 'Y' and type_id IN (?) order by description", array($entities_chosen));
		}

		$doctypes = array();
		
		while($res = $stmt->fetchObject())
		{
			array_push($doctypes, array('ID' => $res->type_id, 'LABEL' => $res->description));
		}

		if($report_type == 'graph')
		{
			$val_an = array();
			$_SESSION['labels1'] = array();
		}
		elseif($report_type == 'array')
		{
			$data = array();
		}
		$has_data = false;

		$totalDocTypes = count($doctypes);


		for($i=0; $i<count($doctypes);$i++)
		{
			$arrayPDO = array_merge($arrayPDO, array(":doctypeId" => $doctypes[$i]['ID']));
			$stmt = $db->query("SELECT ".$view.".doctypes_second_level_label,".$req->get_date_diff($view.'.closing_date', $view.'.creation_date' )." AS delay 
				FROM ".$view." inner join mlb_coll_ext on ".$view.".res_id = mlb_coll_ext.res_id 
				WHERE  ".$where_date." AND ".$view.".closing_date is NOT NULL AND ".$view.".status not in ('DEL','BAD') and ".$view.".type_id = :doctypeId", $arrayPDO);

			$stmt2 = $db->query( "SELECT doctypes_second_level_label FROM doctypes INNER JOIN doctypes_second_level 
						  ON doctypes.doctypes_second_level_id = doctypes_second_level.doctypes_second_level_id
						  WHERE doctypes.type_id=?", array($doctypes[$i]['ID']));
			$res2 = $stmt2->fetchObject();

			if( $stmt->rowCount() > 0)
			{
				$tmp = 0;
				$nbDoc=0;
				while($res = $stmt->fetchObject())
				{
					if($res->delay <> ""){
						$tmp = $tmp + $res->delay;
						$nbDoc++;
					}
				}
				if ($nbDoc == 0) $nbDoc = 1;
				if($report_type == 'graph')
				{
					array_push($val_an, (string)round($tmp / $nbDoc,0));
				}
				elseif($report_type == 'array')
				{
					array_push($data, array('SSCHEMISE' => $res2->doctypes_second_level_label, 'LABEL' => functions::show_string($doctypes[$i]['LABEL']), 'VALUE' => (string)round($tmp / $nbDoc,0)));
				}
				if($tmp / $nbDoc > 0)
				{
					$has_data = true;
				}
			}
			else
			{
				if($report_type == 'graph')
				{
					array_push($val_an, 0);
				}
				elseif($report_type == 'array')
				{
					array_push($data, array('SSCHEMISE' => $res2->doctypes_second_level_label, 'LABEL' => functions::show_string($doctypes[$i]['LABEL']), 'VALUE' => _UNDEFINED));
				}
			}
			if($report_type == 'graph')
			{
				array_push($_SESSION['labels1'], utf8_decode(functions::show_string($doctypes[$i]['LABEL'])));
			}
		}

		if($report_type == 'graph')
		{
			$largeur=50*$totalDocTypes;
			if ($totalDocTypes<20){
				$largeur=1000;
			}
			
			$title = _PROCESS_DELAY_GENERIC_EVALUATION_REPORT_BY_TYPE.' '.$date_title ;
			$src1 = $_SESSION['config']['businessappurl']."index.php?display=true&module=reports&page=graphs&type=histo&largeur=$largeur&hauteur=600&marge_bas=300&title=".$title."&labelY="._N_DAYS;
			for($i=0;$i<count($_SESSION['labels1']);$i++)
			{
				//$src1 .= "&labels[]=".$_SESSION['labels1'][$i];
			}
			$_SESSION['GRAPH']['VALUES']='';
			for($i=0;$i<count($val_an);$i++)
			{
				$_SESSION['GRAPH']['VALUES'][$i]=$val_an[$i];
				//$src1 .= "&values[]=".$val_an[$i];
			}
		}
		elseif($report_type == 'array')
		{

			// Tri du tableau $data
			foreach ($data as $key => $value) {
				$ssChemise[$key] = $value['SSCHEMISE'];
				$document[$key]  = $value['LABEL'];
			}
			array_multisort($ssChemise, SORT_ASC, $document, SORT_ASC, $data);
			array_unshift($data, array('SSCHEMISE' => _SUBFOLDER, 'LABEL' => _DOCTYPE, 'VALUE' => _PROCESS_DELAY));
		}

		

		if ( $has_data)
		{
			if($report_type == 'graph')
			{
				echo "{label: ['".utf8_encode(str_replace(",", "','", addslashes(implode(",", $_SESSION['labels1']))))."'] ".
					", data: ['".utf8_encode(str_replace(",", "','", addslashes(implode(",", $_SESSION['GRAPH']['VALUES']))))."']".
					", title: '".addslashes($title)."'}";
				exit;
			}
			elseif($report_type == 'array')
			{
				$data2=urlencode(json_encode($data));
				$form =	"<input type='button' class='button' value='Exporter les données' onclick='record_data(\"" . $_SESSION['config']['businessappurl']."index.php?display=true&dir=reports&page=record_data \",\"".$data2."\")' style='float:right;'/>";
				echo $form;

				$graph->show_stats_array($title, $data);
			}
		}
		else
		{
			$error = _NO_DATA_MESSAGE;
echo "{status : 2, error_txt : '".addslashes(functions::xssafe($error))."'}";
		}

	}
	if($id_report == 'process_delay_generic_evaluation')
	{	
		$data = "";
	//Gestion du graphique par année
		
	//récupération des libellés de mois
		$_SESSION['month'] = array();
		$_SESSION['month'][1] = _JANUARY;
		$_SESSION['month'][2] = _FEBRUARY;
		$_SESSION['month'][3] = _MARCH;
		$_SESSION['month'][4] = _APRIL;
		$_SESSION['month'][5] = _MAY;
		$_SESSION['month'][6] = _JUNE;
		$_SESSION['month'][7] = _JULY;
		$_SESSION['month'][8] = _AUGUST;
		$_SESSION['month'][9] = _SEPTEMBER;
		$_SESSION['month'][10] = _OCTOBER;
		$_SESSION['month'][11] = _NOVEMBER;
		$_SESSION['month'][12] = _DECEMBER;
		
		if($report_type == 'graph')
		{
			$val_an = array();
			$_SESSION['labels1'] = $_SESSION['month'];
		}
		elseif($report_type == 'array')
		{
			$data = array();
		}
		
	//Gestion en mode année
		if ($period_type == 'period_year')
		{
			for($i=1; $i<= 12; $i++)
			{
				if (!isset($where_date) || empty($where_date)) {
					$period = date("Y");
					$arrayPDO = array();
				} else {
					 $period = substr($where_date, -19);
				}
				$stmt = $db->query("SELECT ".$req->get_date_diff($view.'.closing_date', $view.'.creation_date')." as diff_date FROM ".$view." inner join mlb_coll_ext on ".$view.".res_id = mlb_coll_ext.res_id WHERE ".$view.".status not in ('DEL','BAD') 
					AND ".$view.".closing_date is NOT NULL 
					AND date_part( 'month', ".$view.".creation_date)  = ?
					and date_part( 'year', ".$view.".creation_date)  = ?", array($i,$_POST['the_year']));
				if( $stmt->rowCount() > 0)
				{
					$tmp = 0;
					$nbDoc = 0;
					while($elm = $stmt->fetch(PDO::FETCH_ASSOC))
					{
						if ($elm['diff_date'] <> "") {
							$tmp = $tmp + $elm['diff_date'];
							$nbDoc++;
						}
					}
					if ($nbDoc == 0) $nbDoc = 1;
					if($report_type == 'graph')
					{
						array_push($val_an, (string)round($tmp / $nbDoc,0));
					}
					elseif($report_type == 'array')
					{
						array_push($data, array('LABEL' => $_SESSION['month'][$i], 'VALUE' => (string)round($tmp / $nbDoc,0)));
					}
					if($tmp / $nbDoc > 0)
					{
						$has_data = true;
					}
				}
				else
				{
					if($report_type == 'graph')
					{
						array_push($val_an, 0);
					}
					elseif($report_type == 'tab')
					{
						array_push($data, array('LABEL' => $_SESSION['month'][$i], 'VALUE' => _UNDEFINED));
					}
				}
			}
			$title = _REPORTS_EVO_PROCESS.' '.$date_title ;
			if($report_type == 'graph')
			{
				$src1 = $_SESSION['config']['businessappurl']."index.php?display=true&module=reports&page=graphs&type=courbe&largeur=1000&hauteur=400&title=".$title."&labelX="._MONTH."&labelY="._N_DAYS;
				for($k=1;$k<=count($_SESSION['labels1']);$k++)
				{
					$src1 .= "&labels[]=".$_SESSION['labels1'][$k];
				}
				for($l=0;$l<count($val_an);$l++)
				{
					$src1 .= "&values[]=".$val_an[$l];
				}
			}
			elseif($report_type == 'array')
			{
				array_unshift($data, array('LABEL' => _MONTH, 'VALUE' => _PROCESS_DELAI_AVG));
			}
		}
	//Gestion du graphique par mois
		if ($period_type == 'period_month')
		{
			if($report_type == 'graph')
			{
				$val_mois = array();
			}
			elseif($report_type == 'graph')
			{
				$data = array();
			}

			$mois = mktime( 0, 0, 0, $_REQUEST['the_month'], 1, date("Y") );
			$max = date("t",$mois);
			

			for($i=1; $i<= $max; $i++)
			{
				
				$stmt = $db->query("SELECT ".$req->get_date_diff('closing_date', 'creation_date' )." as diff_date FROM ".$view." WHERE status not in ('DEL','BAD') and date_part( 'month', closing_date)  = ? and date_part( 'year', closing_date)  = ".date('Y')." and date_part( 'day', closing_date)  = ".$i." and ".$view.".closing_date is not null", array($_REQUEST['the_month']));
				
				if( $stmt->rowCount() > 0)
				{
					$tmp = 0;
					$nbDoc = 0;
					while($elm = $stmt->fetch(PDO::FETCH_ASSOC))
					{
						if ($elm['diff_date'] <> "") {
							$tmp = $tmp + $elm['diff_date'];
							$nbDoc++;
						}
					}
					if ($nbDoc == 0) $nbDoc = 1;
					if($report_type == 'graph')
					{
						array_push($val_mois, (string) $tmp / $nbDoc);
					}
					elseif($report_type == 'array')
					{
						array_push($data, array('LABEL' => $i, 'VALUE' => (string) $tmp / $nbDoc));
					}
					$has_data = true;
				}
				else
				{
					if($report_type == 'graph')
					{
						array_push($val_mois, 0);
					}
					elseif($report_type == 'array')
					{
						array_push($data, array('LABEL' => $i, 'VALUE' => _UNDEFINED));
					}
				}
			} 
			
			$title2 = _REPORTS_EVO_PROCESS;
			if($report_type == 'graph')
			{
				
				$src2 = $_SESSION['config']['businessappurl']."index.php?display=true&module=reports&page=graphs&type=courbe&largeur=1000&hauteur=406&title=".$title2."&labelX="._DAYS."&labelY="._N_DAYS;
				
				$label_month = array();
				for($k=1;$k<=$max;$k++)
				{
					$src2 .= "&labels[]=".$k;
					$label_month[$k] = $k;
				}
				for($l=0;$l<count($val_mois);$l++)
				{
					$src2 .= "&values[]=".$val_mois[$l];
				}
				
			}
			elseif($report_type == 'array')
			{
				array_unshift($data, array('LABEL' => _DAYS, 'VALUE' => _PROCESS_DELAI_AVG));
				
			}
		}

		
		
		if ($period_type == 'period_year' && $has_data)
		{
			if($report_type == 'graph')
			{
				echo "{label: ['".html_entity_decode(str_replace(",", "','", addslashes(implode(",", $_SESSION['labels1']))))."'] ".
					", data: ['".utf8_encode(str_replace(",", "','", addslashes(implode(",", $val_an))))."']".
					", title: '".addslashes($title1)."'}";
				exit;
			}
				elseif($report_type  == 'array')
				{
					$data2=urlencode(json_encode($data));
					$form =	"<input type='button' class='button' value='Exporter les données' onclick='record_data(\"" . $_SESSION['config']['businessappurl']."index.php?display=true&dir=reports&page=record_data \",\"".$data2."\")' style='float:right;'/>";
					echo $form;

					$graph->show_stats_array($title1, $data);
				}
		}
			elseif ($period_type == 'period_month' && $has_data)
			{
				if($report_type == 'graph')
				{
					// var_dump($val_mois);
					echo "{label: ['".utf8_encode(str_replace(",", "','", addslashes(implode(",", $label_month))))."'] ".
						", data: ['".utf8_encode(str_replace(",", "','", addslashes(implode(",", $val_mois))))."']".
						", title: '".addslashes($title2)."'}";
					exit;
				}
				elseif($report_type == 'array')
				{
					$data2=urlencode(json_encode($data));
					$form =	"<input type='button' class='button' value='Exporter les données' onclick='record_data(\"" . $_SESSION['config']['businessappurl']."index.php?display=true&dir=reports&page=record_data \",\"".$data2."\")' style='float:right;'/>";
					echo $form;

					$graph->show_stats_array($title2, $data);
				}
			}
			else
			{
				$error = _NO_DATA_MESSAGE;
				echo "{status : 2, error_txt : '".addslashes(functions::xssafe($error))."'}";
			}

			
		}
		else if($id_report == 'mail_typology')
		{
			$has_data = false;
			$title = _MAIL_TYPOLOGY_REPORT.' '.$date_title ;
			
			if (!$_REQUEST['entities_chosen']){
		    	$stmt = $db->query("SELECT type_id, description FROM ".$_SESSION['tablename']['doctypes']." WHERE enabled = 'Y' order by description");
			}else{
			    $stmt = $db->query("SELECT type_id, description FROM ".$_SESSION['tablename']['doctypes']." WHERE enabled = 'Y' and type_id IN (?) order by description", array($entities_chosen));
			}

			if($report_type == 'graph')
			{
				$vol_an = array();
				$vol_mois = array();
				$_SESSION['labels1'] = array();
			}
			elseif($report_type == 'array')
			{
				$data = array();

			}

			$totalCourrier=array();
			$totalEntities = count($entities);
			$z=0;
			while($line = $stmt->fetchObject())
			{
				$stmt2 = $db->query("SELECT count(*) as total FROM ".$view." inner join mlb_coll_ext on ".$view.".res_id = mlb_coll_ext.res_id WHERE ".$where_date." and type_id = ".$line->type_id." and ".$view.".status not in ('DEL','BAD')", $arrayPDO);
				$res = $stmt2->fetchObject();

				$stmt3 = $db->query( "SELECT doctypes_second_level_label FROM doctypes inner join doctypes_second_level 
						  on doctypes.doctypes_second_level_id = doctypes_second_level.doctypes_second_level_id
						  WHERE doctypes.type_id= ? ", array($line->type_id));
				$res3 = $stmt3->fetchObject();


				if($report_type == 'graph')
				{
					array_push($_SESSION['labels1'], (string)utf8_decode($line->description));
					array_push($vol_an, $res->total);
				}
				elseif($report_type == 'array')
				{
					array_push($data, array('SSCHEMISE' => $res3->doctypes_second_level_label, 'LABEL' =>$line->description, 'VALUE' => $res->total ));
					array_push($totalCourrier, $res->total);
				}

				if($res->total > 0)
				{
					$has_data = true;
				}
				$totalDocTypes=$z++;
			}

			if($report_type == 'array'){

				$totalCourriers=array_sum($totalCourrier);
				array_push($data, array('SSCHEMISE' => '_', 'LABEL' => 'Total :', 'VALUE' => $totalCourriers ));
			}

			if($report_type == 'graph')
			{
				$largeur=50*$totalDocTypes;

				if ($totalDocTypes<20){
					$largeur=1000;
				}

				$src1 = $_SESSION['config']['businessappurl']."index.php?display=true&module=reports&page=graphs&type=histo&largeur=$largeur&hauteur=600&marge_bas=300&title=".$title;
				$_SESSION['GRAPH']['VALUES']='';
				for($i=0;$i<count($vol_an);$i++)
				{
					$_SESSION['GRAPH']['VALUES'][$i]=$vol_an[$i];
					//$src1 .= "&values[]=".$vol_an[$i];
				}
			}
			elseif($report_type == 'array')
			{
				// Tri du tableau $data
				foreach ($data as $key => $value) {
					$ssChemise[$key] = $value['SSCHEMISE'];
					$document[$key]  = $value['LABEL'];
				}
				array_multisort($ssChemise, SORT_ASC, $document, SORT_ASC, $data);
				array_unshift($data, array('SSCHEMISE'=> _SUBFOLDER, 'LABEL' => _DOCTYPE, 'VALUE' => _NB_MAILS1));
			}
		
			if($has_data)
			{
				if($report_type == 'graph')
				{
					echo "{label: ['".utf8_encode(str_replace(",", "','", addslashes(implode(",", $_SESSION['labels1']))))."'] ".
						", data: ['".utf8_encode(str_replace(",", "','", addslashes(implode(",", $_SESSION['GRAPH']['VALUES']))))."']".
						", title: '".addslashes($title)."'}";
					exit;
				}
				elseif($report_type == 'array')
				{
					$data2=urlencode(json_encode($data));
					$form =	"<input type='button' class='button' value='Exporter les données' onclick='record_data(\"" . $_SESSION['config']['businessappurl']."index.php?display=true&dir=reports&page=record_data \",\"".$data2."\")' style='float:right;'/>";
					echo $form;

					$graph->show_stats_array($title, $data);
				}
			}
			else
			{
				$error = _NO_DATA_MESSAGE;
				echo "{status : 2, error_txt : '".addslashes(functions::xssafe($error))."'}";
			}
			exit();
		}
		else if($id_report == 'mail_vol_by_cat')
		{
			$has_data = false;
			$title = _MAIL_VOL_BY_CAT_REPORT.' '.$date_title ;
			if($report_type == 'graph')
			{
				$vol_an = array();
				$vol_mois = array();
				$_SESSION['labels1'] = array();
			}
			elseif($report_type == 'array')
			{
				$data = array();
			}

			$totalCourrier=array();
			$totalEntities = count($entities);


			foreach(array_keys($_SESSION['coll_categories']['letterbox_coll']) as $key)
			{
				if($key!='default_category'){
					$stmt = $db->query("SELECT count(*) as total FROM ".$view." inner join mlb_coll_ext on ".$view.".res_id = mlb_coll_ext.res_id WHERE ".$view.".status not in ('DEL','BAD')  and ".$where_date." and ".$view.".category_id = '".$key."'", $arrayPDO);
					$res = $stmt->fetchObject();

					if($report_type == 'graph')
					{
						array_push($_SESSION['labels1'], utf8_decode(functions::wash_html($_SESSION['coll_categories']['letterbox_coll'][$key], 'NO_ACCENT')));
						array_push($vol_an, $res->total);
					}
					elseif($report_type == 'array')
					{
						array_push($data, array('LABEL' => $_SESSION['coll_categories']['letterbox_coll'][$key], 'VALUE' => $res->total ));
						array_push($totalCourrier, $res->total);
					}

					if($res->total > 0)
					{
						$has_data = true;
					}
				}
			}

			if($report_type == 'array'){
				$totalCourriers=array_sum($totalCourrier);
				array_push($data, array('LABEL' => 'Total :', 'VALUE' => $totalCourriers ));
			}

			if($report_type == 'graph')
			{
				$largeur=50*$totalEntities;
				if ($totalEntities<5){
					$largeur=1000;
				}

				$src1 = $_SESSION['config']['businessappurl']."index.php?display=true&module=reports&page=graphs&type=histo&largeur=$largeur&hauteur=600&marge_bas=150&title=".$title;

				$_SESSION['GRAPH']['VALUES']='';
				for($i=0;$i<count($vol_an);$i++)
				{
					//$src1 .= "&values[]=".$vol_an[$i];
					$_SESSION['GRAPH']['VALUES'][$i]=$vol_an[$i];
				}
			}
			elseif($report_type == 'array')
			{
				array_unshift($data, array('LABEL' => _CATEGORY, 'VALUE' => _NB_MAILS1));
			}
	
			if($has_data)
			{
				if($report_type == 'graph')
				{
					echo "{label: ['".utf8_encode(str_replace(",", "','", addslashes(implode(",", $_SESSION['labels1']))))."'] ".
						", data: ['".utf8_encode(str_replace(",", "','", addslashes(implode(",", $_SESSION['GRAPH']['VALUES']))))."']".
						", title: '".addslashes($title)."'}";
					exit;
				}
				elseif($report_type == 'array')
				{
					$data2 = urlencode(json_encode($data));
					$form =	"<input type='button' class='button' value='Exporter les données' onclick='record_data(\"" . $_SESSION['config']['businessappurl']."index.php?display=true&dir=reports&page=record_data \",\"".$data2."\")' style='float:right;'/>";
					echo $form;
					
					$graph->show_stats_array($title, $data);
				}
			}
			else
			{
				$error = _NO_DATA_MESSAGE;
				echo "{status : 2, error_txt : '".addslashes(functions::xssafe($error))."'}";
			}
			exit();
		}
