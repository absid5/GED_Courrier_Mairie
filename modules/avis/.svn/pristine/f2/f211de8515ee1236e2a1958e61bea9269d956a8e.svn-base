<?php
	/*
	*   Copyright 2008-2016 Maarch and Document Image Solutions
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

	/**
	* @brief   Save the avis diffusion lis
	*
	* Save the avis diffusion list
	*
	* @file
	* @author Alex Orluc
	* @date $date$
	* @version $Revision$
	* @ingroup apps
	*/
	require_once "modules" . DIRECTORY_SEPARATOR . "avis" . DIRECTORY_SEPARATOR
			. "class" . DIRECTORY_SEPARATOR
			. "avis_controler.php";
			
			
	$res_id = $_REQUEST['res_id'];
	$coll_id = $_REQUEST['coll_id'];

	if (
		isset($_REQUEST['cons_empty']) 
		&& $_REQUEST['cons_empty'] <> ''
		&& $_REQUEST['cons_empty'] == 'true'
	) {
		echo "{status : 2}";
		$_SESSION['error_avis'] = 'Sélectionner au moins un utilisateur';
		exit();
	} else {
		$_SESSION['error_avis'] = '';
	}
	$conseillers = explode('#',$_REQUEST['conseillers']);
	$consignes = explode('#',$_REQUEST['consignes']);
	$dates = explode('#',$_REQUEST['dates']);
	$list_sign = explode('#',$_REQUEST['list_sign']);
	
	$avis = new avis_controler();
	
	$_SESSION['avis_wf']['diff_list']['avis']['users'] = array();
	
	$nbConseillers = count($conseillers);

	for ($i = 0; $i < $nbConseillers - 1; $i++){

		array_push(
			$_SESSION['avis_wf']['diff_list']['avis']['users'], 
			array(
			'user_id' => $conseillers[$i], 
			'process_comment' => $consignes[$i], 
			'process_date' => $dates[$i], 
			'viewed' => 0,
			'visible' => 'Y',
			'difflist_type' => 'AVIS_CIRCUIT'
			)
		);
	}

	$avis->saveWorkflow($res_id, $coll_id, $_SESSION['avis_wf']['diff_list'], 'AVIS_CIRCUIT');

	if ($_POST['fromDetail'] == "Y") {
		$avis->setStatusAvis($res_id, $coll_id);
	}
	
	echo "{status : 1}";
	exit();
?>