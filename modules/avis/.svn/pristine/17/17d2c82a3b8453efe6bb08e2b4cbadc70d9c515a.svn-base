<?php
	/*
	*   Copyright 2008-2015 Maarch and Document Image Solutions
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
	* @brief   Save the avis diffusion list
	*
	* Save the avis diffusion list
	*
	* @file
	* @author Nicolas Couture <couture@docimsol.com>
	* @date $date$
	* @version $Revision$
	* @ingroup apps
	*/
	require_once "modules" . DIRECTORY_SEPARATOR . "avis" . DIRECTORY_SEPARATOR
			. "class" . DIRECTORY_SEPARATOR
			. "avis_controler.php";
			
			
	$id_list = $_REQUEST['id_list'];
	$title = $_REQUEST['title'];
	$conseillers = explode('#',$_REQUEST['conseillers']);
	$consignes = explode('#',$_REQUEST['consignes']);

	$avis = new avis_controler();
	
	$_SESSION['avis_wf']['diff_list']['avis']['users'] = array();
	
	for ($i = 0; $i < count($conseillers) - 1; $i++){
		if ($list_sign[$i] == 0){
			array_push(
				$_SESSION['avis_wf']['diff_list']['avis']['users'], 
				array(
				'user_id' => $conseillers[$i], 
				'process_comment' => $consignes[$i], 
				'viewed' => 0,
				'visible' => 'Y',
				'difflist_type' => 'AVIS_CIRCUIT'
				)
			);
		}
	}
		
	$avis->saveModelWorkflow($id_list, $_SESSION['avis_wf']['diff_list'], 'AVIS_CIRCUIT', $title);

	$response = ['status' => 1];

	echo json_encode($response);
	exit();
?>