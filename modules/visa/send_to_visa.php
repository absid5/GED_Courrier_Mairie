<?php

/*
*   Copyright 2008-2015 Maarch
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
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once "modules" . DIRECTORY_SEPARATOR . "visa" . DIRECTORY_SEPARATOR
			. "class" . DIRECTORY_SEPARATOR
			. "class_modules_tools.php";
/**
* $confirm  bool true
*/
 
$error_visa = false;
$error_visa_response_project = false;

if (isset($_SESSION['error_visa']) && $_SESSION['error_visa'] <> '') {
	$error_visa = true;
}

$visa = new visa();
$curr_visa_wf = $visa->getWorkflow($_SESSION['doc_id'], $_SESSION['current_basket']['coll_id'], 'VISA_CIRCUIT');

if (count($curr_visa_wf['visa']) == 0 && count($curr_visa_wf['sign']) == 0){
	$error_visa = true;
}

$visa->checkResponseProject($_SESSION['doc_id'], $_SESSION['current_basket']['coll_id']);
if ($visa->errorMessageVisa){
	$error_visa_response_project = true;
}

$confirm = true;
/**
* $etapes  array Contains only one etap, the status modification
*/
$etapes = array('empty_error');
 
function manage_empty_error($arr_id, $history, $id_action, $label_action, $status)
{
	$_SESSION['action_error'] = '';
	$result = '';
	$res_id = $arr_id[0];

	$visa = new visa();
	$visa->setStatusVisa($_SESSION['doc_id'], $_SESSION['current_basket']['coll_id']);

	// for($i=0; $i<count($arr_id );$i++)
	// {
	// 	$result .= $arr_id[$i].'#';
	// }
	return array('result' => $res_id.'#', 'history_msg' => '');
}
