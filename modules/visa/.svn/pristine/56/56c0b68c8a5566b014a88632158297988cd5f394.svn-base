<?php

/*
*    Copyright 2008 - 2015 Maarch
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

	require_once('core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR 
        . 'class_request.php');
	require_once('core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR 
        . 'class_db.php');
		
	require_once 'modules/attachments/attachments_tables.php';
	
	$core_tools = new core_tools();
	$core_tools->test_user();

	$db = new Database();
	$stmt = $db->query("SELECT status from res_view_attachments where attachment_type= ? and res_id_master = ? ", array('response_project', $_REQUEST['res_id']));
	while($line = $stmt->fetchObject()){
		if ($line->status == 'TRA' || $line->status == 'A_TRA' ){
			echo "{status:0}";	
			exit();			
		}
	}
	
	echo "{status:1}";	
	exit();	
	
?>