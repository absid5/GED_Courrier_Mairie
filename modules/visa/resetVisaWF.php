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
	* @brief   Save the visa diffusion lis
	*
	* Save the visa diffusion list
	*
	* @file
	* @author Nicolas Couture <couture@docimsol.com>
	* @date $date$
	* @version $Revision$
	* @ingroup apps
	*/
	require_once "modules" . DIRECTORY_SEPARATOR . "visa" . DIRECTORY_SEPARATOR
			. "class" . DIRECTORY_SEPARATOR
			. "class_modules_tools.php";
			
			
	$res_id = $_REQUEST['res_id'];
	$coll_id = $_REQUEST['coll_id'];

	
	$_SESSION['error_visa'] = '';
	
	$conseillers = explode('#',$_REQUEST['conseillers']);
	$consignes = explode('#',$_REQUEST['consignes']);
	$dates = explode('#',$_REQUEST['dates']);
	$list_sign = explode('#',$_REQUEST['list_sign']);
	
	$visa = new visa();
	
	$_SESSION['visa_wf']['diff_list']['visa']['users'] = array();
	$_SESSION['visa_wf']['diff_list']['sign']['users'] = array();
	
		
	$visa->saveWorkflow($res_id, $coll_id, $_SESSION['visa_wf']['diff_list'], 'VISA_CIRCUIT');

	/*if ($_POST['fromDetail'] == "Y") {
		$visa->setStatusVisa($res_id, $coll_id);
	}*/
	$_SESSION['info'] = "Réinitialisation du circuit effectuée";
	
	echo "{status : 1}";
	exit();
?>