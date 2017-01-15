<?php

/*
*    Copyright 2008-2015 Maarch
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

	require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_functions.php");
	
	$core_tools = new core_tools();
	$core_tools->test_user();
	$core_tools->test_service('reports', 'reports');
	
	if(isset($_POST["data"])){
		try{
			$functions = new functions();
			$_POST['data'] = urldecode($_POST['data']);
			
			$data = json_decode($_POST['data']);
			//var_dump($data);
			$_SESSION['export']['filename'] = 'export_reports_maarch.csv';
			$contenu = '';
			$fp = fopen('apps/maarch_entreprise/tmp/export_reports_maarch.csv', 'w');
			
            $data_to_array = $functions->object2array($data);
			
			foreach($data_to_array as $line){
				//conversion en UTF-8
				$line['LABEL'] = $functions->wash_html($line['LABEL'], "UTF-8");
				$line['VALUE'] = $functions->wash_html($line['VALUE'], "UTF-8");
				fputcsv($fp, $line, ';');
			}
			
			fclose($fp);
			$return['status'] = 1;
		} catch(Exeption $e){
			$return['response'] = "ERROR : " . $e;
			$return['status'] = 0;
		}
		
	}
	echo json_encode($return);
?>
