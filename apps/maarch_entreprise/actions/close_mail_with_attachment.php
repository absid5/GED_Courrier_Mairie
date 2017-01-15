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
* @brief   Action : simple confirm
*
* Open a modal box to confirm a status modification. Used by the core (manage_action.php page).
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/

/**
* $confirm  bool true
*/
/**
Permet de vérifier si il existe des pièces jointes ou des notes pour le courrier lors de la cloture. Si il n'y a pas de pièce jointe, un message s'affiche pour indiquer qu'il faut mettre une pièce jointe sinon si il y a une pice jointe ou une note, le cloture se fait normalement.
*/
require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_request.php');
$db = new Database();
$stmt = $db->query("SELECT count(res_id_master) FROM res_attachments WHERE res_id_master = ?", array($_POST['values']));
$nbAttachments = $stmt->fetchObject(); 
$nbAttachments = json_decode(json_encode($nbAttachments), True);

$stmt = $db->query("SELECT count(identifier) FROM notes WHERE identifier = ?", array($_POST['values']));
$nbNotes = $stmt->fetchObject();
$nbNotes = json_decode(json_encode($nbNotes), True);

if($nbAttachments['count'] == 0 && $nbNotes == 0){
 	$confirm = false;
}elseif($nbAttachments['count'] != 0 || $nbNotes['count'] != 0){
	$confirm = true;	
}

/**
* $etapes  array Contains only one etap, the status modification
*/
 $etapes = array('close');


function manage_close($arr_id, $history, $id_action, $label_action, $status)
{
	$result = '';
	require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_security.php');
	require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_request.php');
	$sec = new security();
	$db = new Database();

	$ind_coll = $sec->get_ind_collection($_POST['coll_id']);
	$ext_table = $_SESSION['collections'][$ind_coll]['extensions'][0];
	for($i=0; $i<count($arr_id );$i++)
	{
		$result .= $arr_id[$i].'#';
		$db->query("UPDATE ".$ext_table. " SET closing_date = CURRENT_TIMESTAMP WHERE res_id = ?", array($arr_id[$i]));

	}
	return array('result' => $result, 'history_msg' => '');
 }
