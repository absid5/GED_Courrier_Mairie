<?php
/*
*
*   Copyright 2013 Maarch
*
*   This file is part of Maarch Framework.
*
*   Maarch Framework is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Maarch Framework is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief    List of positions for autocompletion
*
* @file     positions_checked_list_autocompletion.php
* @author   Yves Christian Kpakpo <dev@maarch.org>
* @date     $date$
* @version  $Revision$
* @ingroup  fileplan
*/

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once "modules" . DIRECTORY_SEPARATOR . "fileplan" . DIRECTORY_SEPARATOR
. "class" . DIRECTORY_SEPARATOR . "class_modules_tools.php";

$db 		= new Database();
$fileplan 	= new fileplan();

$where = "";
$content = "";
$actual_position_id = "";
$positions_array = $path_array = array();

$path_to_script = $_SESSION['config']['businessappurl']
."index.php?display=true&module=fileplan&page=fileplan_ajax_script";

if (isset($_REQUEST['res_id']) && !empty($_REQUEST['res_id'])) {
	
	//Build res_array
	$res_array = $fileplan->buildResArray($_REQUEST['res_id']);
	//
	$resIdArray = array();
	$resIdArray = explode (',', $_REQUEST['res_id']);

	if(count($resIdArray)>1){
		$multi_doc=true;
	}else{
		$multi_doc=false;
	}
	//Get uuthorized fileplans
	$authorizedFileplans =  $fileplan->getAuthorizedFileplans();
	
	//For each ressource
	for($i = 0; $i < count($resIdArray); $i++) {
		$allIsChecked = false;
		//Separate coll_id from res_id
		$tmp = explode('@@', $resIdArray[$i]);

		//Search for the fileplans and positions
		$path_array = $fileplan->whereAmISetted($authorizedFileplans, $tmp[0], $tmp[1]);
		
		for($j = 0; $j < count($path_array); $j++) {
			//Get the state of checkbox
			$state = $fileplan->getPositionState($path_array[$j]['FILEPLAN_ID'], $path_array[$j]['POSITION_ID'], $res_array);
			//Set the tate
			$_SESSION['checked_positions'][$path_array[$j]['FILEPLAN_ID']][$path_array[$j]['POSITION_ID']] = $state;
		}
	}
}
if (!empty($_REQUEST['fileplan_id'])) {
	if(!empty($_REQUEST['param'])){
		$html = "<form id='formFileplan'>";
		$html .= "\n<ul id='positionsList'>\n";
		$where=" and translate(
		    LOWER(position_label),
		    'âãäåÁÂÃÄÅèééêëÈÉÉÊËìíîïìÌÍÎÏÌóôõöÒÓÔÕÖùúûüÙÚÛÜ',
		    'aaaaAAAAAeeeeeEEEEEiiiiiIIIIIooooOOOOOuuuuUUUU'
		) like translate(
		    ?,
		    'âãäåÁÂÃÄÅèééêëÈÉÉÊËìíîïìÌÍÎÏÌóôõöÒÓÔÕÖùúûüÙÚÛÜ',
		    'aaaaAAAAAeeeeeEEEEEiiiiiIIIIIooooOOOOOuuuuUUUU'
		) ";
		$_SESSION['origin_positions']='';
		$fileplan_id = $_REQUEST['fileplan_id'];

		$stmt = $db->query(
			"select position_id, position_label, parent_id from fp_view_fileplan where fileplan_id = ?"
			.$where." and position_enabled = ? order by position_label asc "
			,array($fileplan_id,'%'.strtolower($_REQUEST['param']).'%','Y'));

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$categories[] = array(
				'parent_id' => $row['parent_id'],
				'fileplan_id' => $row['position_id'],
				'nom_fileplan' => $row['position_label']
				);
		}
		foreach ($categories AS $noeud)
		{
			$tmp = explode('@@', $_REQUEST['res_id']);

			if($multi_doc==false){
				$stmt2 = $db->query(
					"select fileplan_id, position_id from fp_res_fileplan_positions where"
					." res_id = ? and position_id = ?"
					,array($tmp[1],$noeud['fileplan_id']));
				$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

				if(!$row2){
					$html .= "<li style='margin-left:10px;'><input type='checkbox' name='position[]' id='position_".$noeud['fileplan_id']."' value='".$noeud['fileplan_id']."' onClick=\"saveCheckedState('". $_SESSION['config']['businessappurl']
						."index.php?display=true&module=fileplan&page=fileplan_ajax_script"
						. "&fileplan_id=".$_REQUEST['fileplan_id']."&mode=checkPosition', this);\"/>" . $noeud['nom_fileplan'];
				}else{
					$_SESSION['origin_positions'][]=$noeud['fileplan_id'];
					$html .= "<li style='margin-left:10px;'><input type='checkbox' name='position[]' id='position_".$noeud['fileplan_id']."' value='".$noeud['fileplan_id']."' checked='checked' onClick=\"saveCheckedState('". $_SESSION['config']['businessappurl']
						."index.php?display=true&module=fileplan&page=fileplan_ajax_script"
						. "&fileplan_id=".$_REQUEST['fileplan_id']."&mode=checkPosition', this);\"/>" . $noeud['nom_fileplan'];
				}

			}else{
				$html .= "<li style='margin-left:10px;'><input type='checkbox' name='position[]' id='position_".$noeud['fileplan_id']."' value='".$noeud['fileplan_id']."' onClick=\"saveCheckedState('". $_SESSION['config']['businessappurl']
						."index.php?display=true&module=fileplan&page=fileplan_ajax_script"
						. "&fileplan_id=".$_REQUEST['fileplan_id']."&mode=checkPosition', this);\"/>" . $noeud['nom_fileplan'];
			}


			$html .= "</li>\n";
		}
		$html .= "</ul>\n";
		$html.="</form>";
	}else{
		$_SESSION['origin_positions']='';
		$fileplan_id = $_REQUEST['fileplan_id'];

		$stmt = $db->query(
			"select position_id, position_label, parent_id from fp_view_fileplan where"
			." fileplan_id = ? and position_enabled = ? order by position_label asc "
			,array($fileplan_id,'Y'));

		$categories = array();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$categories[] = array(
				'parent_id' => $row['parent_id'],
				'fileplan_id' => $row['position_id'],
				'nom_fileplan' => $row['position_label']
				);
		}

		echo afficher_arbo(0, 0, $categories, $multi_doc);
	}
}
function afficher_arbo($parent, $niveau, $array, $multi_doc)
{
	$html = "<form id='formFileplan'>";
	$niveau_precedent = 0;

	if (!$niveau && !$niveau_precedent) $html .= "\n<ul id='positionsList'>\n";

	foreach ($array AS $noeud)
	{
		if ($parent == $noeud['parent_id'])
		{
			if ($niveau_precedent < $niveau) $html .= "\n<ul>\n";
			$tmp = explode('@@', $_REQUEST['res_id']);


			if($multi_doc==false){
			$db = new Database();
			$stmt2 = $db->query(
				"select fileplan_id, position_id from fp_res_fileplan_positions"
				." where res_id = ? and position_id= ?"
				,array($tmp[1],$noeud['fileplan_id']));
			$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
			if(!$row2){
				$html .= "<li style='margin-left:20px;'><input type='checkbox' name='position[]' id='position_".$noeud['fileplan_id']."' value='".$noeud['fileplan_id']."' onClick=\"saveCheckedState('". $_SESSION['config']['businessappurl']
					."index.php?display=true&module=fileplan&page=fileplan_ajax_script"
					. "&fileplan_id=".$_REQUEST['fileplan_id']."&mode=checkPosition', this);\"/>" . $noeud['nom_fileplan'];
			}else{
				$_SESSION['origin_positions'][]=$noeud['fileplan_id'];
				$html .= "<li style='margin-left:20px;'><input type='checkbox' name='position[]' id='position_".$noeud['fileplan_id']."' value='".$noeud['fileplan_id']."' checked='checked' onClick=\"saveCheckedState('". $_SESSION['config']['businessappurl']
					."index.php?display=true&module=fileplan&page=fileplan_ajax_script"
					. "&fileplan_id=".$_REQUEST['fileplan_id']."&mode=checkPosition', this);\"/>" . $noeud['nom_fileplan'];
			}
		}else{
			$html .= "<li style='margin-left:20px;'><input type='checkbox' name='position[]' id='position_".$noeud['fileplan_id']."' value='".$noeud['fileplan_id']."' onClick=\"saveCheckedState('". $_SESSION['config']['businessappurl']
					."index.php?display=true&module=fileplan&page=fileplan_ajax_script"
					. "&fileplan_id=".$_REQUEST['fileplan_id']."&mode=checkPosition', this);\"/>" . $noeud['nom_fileplan'];
		}

		$niveau_precedent = $niveau;
		$html .= afficher_arbo($noeud['fileplan_id'], ($niveau + 1), $array, $multi_doc);
		}
	}

	if (($niveau_precedent == $niveau) && ($niveau_precedent != 0)) $html .= "</ul>\n</li>\n";
	else if ($niveau_precedent == $niveau) $html .= "</ul>\n";
	else $html .= "</li>\n";
	$html.="</form>";
	return $html;
}
echo $html;
