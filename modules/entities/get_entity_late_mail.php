<?php

/*
*    Copyright 2008,20015 Maarch
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
require_once('modules'.DIRECTORY_SEPARATOR.'entities'.DIRECTORY_SEPARATOR.'entities_tables.php');

$_ENV['date_pattern'] = "/^[0-3][0-9]-[0-1][0-9]-[1-2][0-9][0-9][0-9]$/";


$graph = new graphics();
$req = new request();
$db = new Database();
$sec = new security();


$entities_chosen=explode("#",$_POST['entities_chosen']);
$entities_chosen="'".join("','",$entities_chosen)."'";


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

$title = _ENTITY_LATE_MAIL.' '.$date_title ;
$db = new Database();

//Récupération de l'ensemble des types de documents
if (!$_REQUEST['entities_chosen']){
    $stmt = $db->query("select entity_id, short_label from ".ENT_ENTITIES." where enabled = 'Y' order by short_label");
}else{
    $stmt = $db->query("select entity_id, short_label from ".ENT_ENTITIES." where enabled = 'Y' and entity_id IN (".$entities_chosen.") order by short_label");
}
//$db->show();

$entities = array();
while($res = $stmt->fetchObject())
{
    array_push($entities, array('ID' => $res->entity_id, 'LABEL' => $res->short_label));
}

if($report_type == 'graph')
{
    $vol = array();
    $_SESSION['labels1'] = array();
}
elseif($report_type == 'array')
{
    $data = array();
}
$has_data = false;

//Utilisation de la clause de sécurité de Maarch

$where_clause = $sec->get_where_clause_from_coll_id('letterbox_coll');
//var_dump($where_clause);
if ($where_clause)
    $where_clause = " and ".$where_clause;
	
$totalCourrier=array();
$totalEntities = count($entities);

for($i=0; $i<count($entities);$i++)
{
    $valid = true;
   /* if ($_SESSION['user']['entities']){
        foreach($_SESSION['user']['entities'] as $user_ent){
            if ($entities[$i]['ID'] == $user_ent['ENTITY_ID']){
                $valid = true;
            }
        }
    }*/
    if ($valid == 'true' || $_SESSION['user']['UserId'] == "superadmin")
    {
/*
    $this->query("select l.res_id  from ".$_SESSION['ressources']['letterbox_view']." r, ".$_SESSION['tablename']['listinstance']." l  where r.res_id=l.res_id and l.item_id='".$user['ID']."'  and item_type = 'user_id' and  r.flag_alarm1 = 'N' and (r.status = 'NEW' or r.status = 'COU') and date(r.alarm1_date) =date(now()) and l.item_mode = 'dest' and item_type='user_id'");
*/
        $stmt = $db->query("SELECT count(*) AS total FROM ".$view
                    ." inner join mlb_coll_ext on ".$view.".res_id = mlb_coll_ext.res_id 
                    WHERE destination = ? and ".$view.".status not in ('DEL','BAD') and date(".$view.".process_limit_date) <= date(now()) and ".$view.".closing_date is null",array($entities[$i]['ID']));
        //$db->show();

		if( $stmt->rowCount() > 0)
        {
            $tmp = 0;
            $res = $stmt->fetchObject();

            if($report_type == 'graph')
            {

                array_push($vol, $res->total);
            }
            elseif($report_type == 'array')
            {
                array_push($data, array('LABEL' => $entities[$i]['LABEL'], 'VALUE' => $res->total ));
                array_push($totalCourrier, $res->total);
            }
            if($res->total > 0)
            {
                $has_data = true;
            }
        }
        else
        {
            if($report_type == 'graph')
            {

                array_push($vol, 0);
            }
            elseif($report_type == 'array')
            {
                array_push($data, array('LABEL' => $entities[$i]['LABEL'], 'VALUE' => _UNDEFINED));
            }
        }
        if($report_type == 'graph')
        {
            array_push($_SESSION['labels1'], functions::wash_html($entities[$i]['LABEL'], 'NO_ACCENT'));
        }
    }
}

if ($report_type == 'array'){
    $totalCourriers=array_sum($totalCourrier);
    array_push($data, array('LABEL' => 'Total des courriers rattachés à une entité existante :', 'VALUE' => $totalCourriers));
}

if($report_type == 'graph')
{
    $largeur=50*$totalEntities;
    if ($totalEntities<20){
        $largeur=1000;
    }

    $src1 = $_SESSION['config']['businessappurl']."index.php?display=true&module=reports&page=graphs&type=histo&largeur=$largeur&hauteur=600&marge_bas=300&title=".$title;
    for($i=0;$i<count($_SESSION['labels1']);$i++)
    {
        //$src1 .= "&labels[]=".$_SESSION['labels1'][$i];
    }
    $_SESSION['GRAPH']['VALUES']='';
    for($i=0;$i<count($vol);$i++)
    {
        //$src1 .= "&values[]=".$vol[$i];
        $_SESSION['GRAPH']['VALUES'][$i]=$vol[$i];
    }
}
elseif($report_type == 'array')
{
    array_unshift($data, array('LABEL' => _ENTITY, 'VALUE' =>_NB_MAILS1));
}

if ($has_data) {
    if($report_type == 'graph') {
        echo "{label: ['".str_replace(",", "','", addslashes(implode(",", $_SESSION['labels1'])))."'] ".
            ", data: ['".utf8_encode(str_replace(",", "','", addslashes(implode(",", $_SESSION['GRAPH']['VALUES']))))."']".
            ", title: '".addslashes($title)."'}";
        exit;
    } elseif($report_type == 'array') {
	
		$data2 = urlencode(json_encode($data));
		$form =	"<input type='button' class='button' value='Exporter les données' onclick='record_data(\"" . $_SESSION['config']['businessappurl']."index.php?display=true&dir=reports&page=record_data \",\"".$data2."\")' style='float:right;'/>";
		echo $form;
		
        $graph->show_stats_array($title, $data);
    }
} else {
    $error = _NO_DATA_MESSAGE;
    echo "{status : 2, error_txt : '".addslashes(functions::xssafe($error))."'}";
}
exit();

?>
