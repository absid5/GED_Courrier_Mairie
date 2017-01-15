<?php
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_manage_status.php");
require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_list_show.php");
require_once('modules'.DIRECTORY_SEPARATOR.'reports'.DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_graphics.php");
require_once('modules'.DIRECTORY_SEPARATOR.'entities'.DIRECTORY_SEPARATOR.'entities_tables.php');

$_ENV['date_pattern'] = "/^[0-3][0-9]-[0-1][0-9]-[1-2][0-9][0-9][0-9]$/";


$graph = new graphics();
$req = new request();
$db = new Database();
$sec = new security();

$entities_chosen=explode("#",$_POST['entities_chosen']);
$entities_chosen="'".join("','",$entities_chosen)."'";

$period_type = $_REQUEST['period_type'];
$status_obj = new manage_status();
$ind_coll = $sec->get_ind_collection('letterbox_coll');
$table = $_SESSION['collections'][$ind_coll]['table'];
$view = $_SESSION['collections'][$ind_coll]['view'];
$search_status = $status_obj->get_searchable_status();
$default_year = date('Y');
$report_type = $_REQUEST['report_type'];
$core_tools = new core_tools();
$core_tools->load_lang();


//Limitation aux documents pouvant être recherchés
$str_status = '(';
for($i=0;$i<count($search_status);$i++)
{
	$str_status .= "'".$search_status[$i]['ID']."',";
}
$str_status = preg_replace('/,$/', ')', $str_status);

//Récupération de l'ensemble des types de documents
if (!$_REQUEST['entities_chosen']){
	$stmt = $db->query("select entity_id, short_label from ".ENT_ENTITIES." where enabled = 'Y' order by short_label");
}else{
	$stmt = $db->query("select entity_id, short_label from ".ENT_ENTITIES." where enabled = 'Y' and entity_id IN (".$entities_chosen.") order by short_label",array());
}


$entities = array();
while($res = $stmt->fetchObject())
{
    array_push($entities, array('ID' => $res->entity_id, 'LABEL' => $res->short_label));
}
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
	$where_date = " and ".$req->extract_date('creation_date', 'year')." = '".$_REQUEST['the_year']."'";
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
	$where_date = " and ".$req->extract_date('creation_date', 'year')." = '".$default_year."' and ".$req->extract_date('creation_date', 'month')." = '".$_REQUEST['the_month']."'";
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
	if (empty($_REQUEST['date_start']) && empty($_REQUEST['date_fin'])){
		echo '<div class="error">'._DATE.' '._IS_EMPTY.''.$_REQUEST['date_start'].'</div>';
		exit();
	}
	
	
	if( preg_match($_ENV['date_pattern'],$_REQUEST['date_start'])==false  && $_REQUEST['date_start'] <> ''  )
	{
		
		echo '<div class="error">'._WRONG_DATE_FORMAT.' : '.$_REQUEST['date_start'].'</div>';
		exit();
	
	}
	if( preg_match($_ENV['date_pattern'],$_REQUEST['date_fin'])==false && $_REQUEST['date_fin'] <> '' )
	{
		
		echo '<div class="error">'._WRONG_DATE_FORMAT.' : '.$_REQUEST['date_fin'].'</div>';
		exit();

	}

	if(isset($_REQUEST['date_start']) && $_REQUEST['date_start'] <> '')
	{
		$where_date  .= " AND ".$req->extract_date('creation_date')." > '".$db->format_date_db($_REQUEST['date_start'])."'";
		$date_title .= strtolower(_SINCE).' '.$_REQUEST['date_start'].' ';
	}

	if(isset($_REQUEST['date_fin']) && $_REQUEST['date_fin'] <> '')
	{
		$where_date  .= " AND ".$req->extract_date('creation_date')." < '".$db->format_date_db($_REQUEST['date_fin'])."'";
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

$has_data = false;
$title = _MAIL_VOL_BY_ENT_REPORT.' '.$date_title ;
$db = new Database();


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
//Utilisation de la clause de sécurité de Maarch

$where_clause = $sec->get_where_clause_from_coll_id('letterbox_coll');
//var_dump($where_clause);
if ($where_clause)
	$where_clause = " and ".$where_clause;

$totalCourrier=array();
$totalEntities = count($entities);	
	
for($i=0; $i<count($entities);$i++)
{
	$stmt = $db->query("select count(*) as total from ".$view." inner join mlb_coll_ext on ".$view.".res_id = mlb_coll_ext.res_id where destination = ? and ".$view.".status not in ('DEL','BAD') ".$where_date." ",array($entities[$i]['ID']));
	//$db->show();
	$res = $stmt->fetchObject();
	/*
		$db->query("select count(*) as total from ".$view." where status in ".$str_status."   ".$where_date." and category_id = '".$key."'");
	*/
		
		if($report_type == 'graph')
		{
				array_push($_SESSION['labels1'], addslashes(utf8_decode($db->wash_html($entities[$i]['LABEL'], 'NO_ACCENT'))));
				array_push($vol_an, $res->total);
		}
		elseif($report_type == 'array')
		{
			array_push($data, array('LABEL' => $entities[$i]['LABEL'], 'VALUE' => $res->total ));
			array_push($totalCourrier, $res->total);		
		}

		if ($res->total<>0){
			$has_data = true;
		}
		
}

if($report_type == 'array'){

	$totalCourriers=array_sum($totalCourrier);
	array_push($data, array('LABEL' => 'Total des courriers rattachés à une entité existante :', 'VALUE' => $totalCourriers ));
}

if($report_type == 'graph')
{
	$largeur=50*$totalEntities;
	if ($totalEntities<20){
		$largeur=1000;
	}

	$src1 = $_SESSION['config']['businessappurl']."index.php?display=true&module=reports&page=graphs&type=histo&largeur=$largeur&hauteur=600&marge_bas=300&title=".$title;
	$_SESSION['GRAPH']['VALUES']='';
	for($i=0;$i<count($vol_an);$i++)
	{
		//$src1 .= "&values[]=".$vol_an[$i];
		$_SESSION['GRAPH']['VALUES'][$i]=$vol_an[$i];
	}
}
elseif($report_type == 'array')
{
	array_unshift($data, array('LABEL' => _ENTITY, 'VALUE' => _NB_MAILS1));
}


if($has_data)
{
	if($report_type == 'graph')
	{
		$labels1 = "'".implode("','", $_SESSION['labels1'])."'";
		//var_dump($labels1);

		echo "{label: [".utf8_encode($labels1)."] ".
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

?>
