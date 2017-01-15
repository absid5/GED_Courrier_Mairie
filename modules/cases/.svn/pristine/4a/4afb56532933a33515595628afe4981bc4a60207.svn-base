<?php

/*
*    Copyright 2008,2009 Maarch
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

/**
* @brief  Displays a document logs
*
* @file hist_doc.php
* @author Loic Vinet <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup cases
*/
$case_id = $result[$theline][0]['case_id'];


$db = new Database();


$status_obj = new manage_status();
$status = $status_obj->get_not_searchable_status();
$sec = new security();
$func = new functions();
$status_str = '';
$extension_icon = '';
$array_what = array();
for ($i = 0; $i < count($status); $i ++) {
	$status_str .=	"?,";
	$array_what[] = $status[$i]['ID'];
}

if ($status_str <> '') {
    $status_str = preg_replace('/,$/', '', $status_str);
    $where_request.= "  status not in (".$status_str.") ";    
} else {
    $where_request .= " 1=1 ";
}

//$status_str = preg_replace('/,$/', '', $status_str);
//$where_request = "  status not in (".$status_str.") ";
//$where_clause = $sec->get_where_clause_from_coll_id($_SESSION['collection_id_choice']);
$where_clause =" case_id = ? ";
$array_what[] = $case_id;

if (! empty($where_request)) {
	if (isset($_SESSION['searching']['where_clause_bis'])
	    && $_SESSION['searching']['where_clause_bis'] <> ""
	) {
		$where_clause = "((".$where_clause.") or (".$_SESSION['searching']['where_clause_bis']."))";
	}
	$where_request = '('.$where_request.') and ('.$where_clause.')';
} else {
	if ($_SESSION['searching']['where_clause_bis'] <> "") {
		$where_clause = "((".$where_clause.") or (".$_SESSION['searching']['where_clause_bis']."))";
	}
	$where_request = $where_clause;
}
$where_request = str_replace("()", "(1=-1)", $where_request);
$where_request = str_replace("and ()", "", $where_request);


require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR.'class_contacts_v2.php');
$contact = new contacts_v2();

$stmt = $db->query(
	"select res_id, status, subject, contact_firstname, contact_lastname, contact_society, user_lastname, user_firstname, dest_user, type_label, creation_date, entity_label, category_id, exp_user_id, category_id as category_img, process_limit_date, priority"
	. " from ".$_SESSION['collections'][0]['view']
	. " where ".$where_request." order by res_id" 
	,$array_what);

if($stmt->rowCount() >0)
{
	require_once("core/class/class_security.php");
	$security = new security();
	$external = '<table border="0" style="font-size:9px; margin:0px;" width="100%"  cellspacing="0">';
	while ($ext_result=$stmt->fetchObject())
	{
		$res_status = $status_obj->get_status_data($ext_result->status);
		$right = $security->test_right_doc($_SESSION['collections'][0]['id'],$ext_result->res_id);
		if($right==false) {
			$external .='<tr class="col"  style="color:#BBBBBB;"> <a href="#" title="'. _DETAILS.'">';
		} else {
			$external .='<tr class="col"  onclick="location.href=\''.$_SESSION['config']['businessappurl'].'index.php?page='.$this->detail_destination.'&amp;id='.$ext_result->res_id.'\';" onmouseover="document.body.style.cursor=\'pointer\';" onmouseout="document.body.style.cursor=\'auto\';"><a href="'.$_SESSION['config']['businessappurl'].'index.php?page='.$this->detail_destination.'&amp;id='.$ext_result->res_id.'" title="'. _DETAILS.'">';
		}
		$external .='<td width="8%" >&nbsp;</td>';
		$external .='<td width="40px"><img src="'.$res_status['IMG_SRC'].'" alt = "'.$res_status['LABEL'].'" title = "'.$res_status['LABEL'].'"></td>';
		//$external .='<td width="40px"><p><img src="'. get_img_cat($ext_result->category_id, $extension_icon).'" title="'.$_SESSION['coll_categories']['letterbox_coll'][$ext_result->category_id].'" alt="'.$_SESSION['coll_categories']['letterbox_coll'][$ext_result->category_id].'"></p></td>';
		$external .='<td width="40px" ><b><p align="center" title="'._GED_NUM.' : '.$ext_result->res_id.'" alt="'._GED_NUM.' : '.$ext_result->res_id.'">'.$func->cut_string($ext_result->res_id,50).'</td></b></p>';
		$external .='<td ><p title="'. _SUBJECT . ' : '.functions::show_string($ext_result->subject).'" alt="'._SUBJECT.' : '.functions::show_string($ext_result->subject).'">'.$func->cut_string(functions::show_string($ext_result->subject),70).'</p></td>';
		//$external .='<td width="100px"><p>'.$ext_result->dest_user.'</td></p>';
		$contactInfo = $contact->get_contact_information_from_view($ext_result->category_id, $ext_result->contact_lastname, $ext_result->contact_firstname, $ext_result->contact_society, $ext_result->user_lastname, $ext_result->user_firstname);
		$external .='<td  ><p title="'._CONTACT.' : '.functions::show_string($contactInfo).'" alt="'._CONTACT.' : '.functions::show_string($contactInfo).'">'.functions::show_string($contactInfo).'</p></td>';
		//$external .='<td  ><p title="'._TYPE.' : '.functions::show_string($ext_result->type_label).'" alt="'._TYPE.' : '.functions::show_string($ext_result->type_label).'">('.functions::show_string($ext_result->type_label).')</p></td>';
		$external .='<td  ><p title="'._ENTITY.' : '.functions::show_string($ext_result->entity_label).'" alt="'._ENTITY.' : '.functions::show_string($ext_result->entity_label).'"><b>'.functions::show_string($ext_result->entity_label).'</b></p></td>';
		$external .='<td ><p title="'._PROCESS_LIMIT_DATE.' : '.functions::format_date_db($ext_result->process_limit_date,false).'" alt="'._PROCESS_LIMIT_DATE.' : '.functions::format_date_db($ext_result->process_limit_date,false).'">'.functions::format_date_db($ext_result->process_limit_date,false).'</p></td>';
		$external .='</a></tr>';
	 }
   	 $external .='</table>';
}
