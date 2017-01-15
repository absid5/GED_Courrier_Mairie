<?php

/*
*   Copyright 2015 Maarch
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

function createPdfNotes($list_notes, $coll_id){
	require_once("modules".DIRECTORY_SEPARATOR."visa".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_modules_tools.php");
	$pdf = new PdfNotes();
	$pdf->addPage();
	$data = $pdf->LoadData($list_notes, $coll_id);
	$header = array('Nom', 'Consigne', 'Date');
	$pdf->SetFont('Arial','B',12);
	$pdf->SetY(30);
	//$pdf->Table($header, $data);
	$pdf->SetWidths(array(40,30,100));
	$pdf->SetAligns(array('C','C','C'));
	$pdf->Row(array('Nom', 'Date', 'Note'));
	$pdf->SetAligns(array('L','C','L'));
	$pdf->SetFont('Arial','',10);
	foreach($data as $d){
		$pdf->Row($d);
	}
	
	$filePathOnTmp = $_SESSION['config']['tmppath'] . DIRECTORY_SEPARATOR . "listNotes".$_SESSION['user']['UserId'].".pdf";
	$pdf->Output($filePathOnTmp, 'F');
	return $filePathOnTmp;
}

	
function concat_files($folder){
	require_once("modules".DIRECTORY_SEPARATOR."visa".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_modules_tools.php");
	$pdf = new ConcatPdf();
	$pdf->setFiles($folder);
	try {
		$pdf->concat();
	} catch (Exception $e) {
		return $e->getMessage();
	}

	

	$tmpFileName = 'tmp_print_folder_' . rand() . '.pdf';
	$filePathOnTmp = $_SESSION['config']['tmppath'] . $tmpFileName;
	
	$pdf->Output($filePathOnTmp, 'F');
	return $filePathOnTmp;
}

function ajout_bdd($tmpFolderFile, $res_id_master){
	require_once 'modules/attachments/attachments_tables.php';
	require_once 'core/class/docservers_controler.php';
	require_once "core/class/class_security.php";

	$sec = new security();
	$docserverControler = new docservers_controler();
	if (! isset($_SESSION['collection_id_choice'])
		|| empty($_SESSION['collection_id_choice'])
	) {
		$_SESSION['collection_id_choice'] = $_SESSION['user']['collections'][0];
	}

	$docserver = $docserverControler->getDocserverToInsert(
		$_SESSION['collection_id_choice']
	);
	if (empty($docserver)) {
		$_SESSION['error'] = _DOCSERVER_ERROR . ' : '
			. _NO_AVAILABLE_DOCSERVER . ". " . _MORE_INFOS . ".";
		$location = "";
	} else {
		$filesize = filesize($tmpFolderFile);
		$newSize = $docserverControler->checkSize(
                    $docserver, $filesize
		);
		if ($newSize == 0) {
			$_SESSION['error'] = _DOCSERVER_ERROR . ' : '
				. _NOT_ENOUGH_DISK_SPACE . ". " . _MORE_INFOS . ".";
			
		} else {
			$basename = pathinfo($tmpFolderFile, PATHINFO_BASENAME);
			$fileInfos = array(
				"tmpDir"      => $_SESSION['config']['tmppath'],
				"size"        => $filesize,
				"format"      => 'pdf',
				"tmpFileName" => $basename
			);
			
			$storeResult = array();
			$storeResult = $docserverControler->storeResourceOnDocserver(
				$_SESSION['collection_id_choice'], $fileInfos
			);
					
			if (isset($storeResult['error']) && $storeResult['error'] <> '') {
				$_SESSION['error'] = $storeResult['error'];
			} else {
				$resAttach = new resource();
				$title = "Dossier du document n°".$_SESSION['doc_id'];
				$_SESSION['data'] = array();
				array_push(
					$_SESSION['data'],
					array(
						'column' => "typist",
						'value' => $_SESSION['user']['UserId'],
						'type' => "string",
					)
				);
				array_push(
					$_SESSION['data'],
					array(
						'column' => "format",
						'value' => 'pdf',
						'type' => "string",
					)
				);
				array_push(
					$_SESSION['data'],
					array(
						'column' => "docserver_id",
						'value' => $storeResult['docserver_id'],
						'type' => "string",
					)
				);
				array_push(
					$_SESSION['data'],
					array(
						'column' => "status",
						'value' => 'TRA',
						'type' => "string",
					)
				);
				array_push(
					$_SESSION['data'],
					array(
						'column' => "offset_doc",
						'value' => ' ',
						'type' => "string",
					)
				);
				array_push(
					$_SESSION['data'],
					array(
						'column' => "logical_adr",
						'value' => ' ',
						'type' => "string",
					)
				);
				array_push(
					$_SESSION['data'],
					array(
						'column' => "title",
						'value' => $title,
						'type' => "string",
					)
				);
				array_push(
					$_SESSION['data'],
					array(
						'column' => "attachment_type",
						'value' => 'print_folder',
						'type' => "string",
					)
				);
				array_push(
					$_SESSION['data'],
					array(
						'column' => "coll_id",
						'value' => $_SESSION['collection_id_choice'],
						'type' => "string",
					)
				);
				array_push(
					$_SESSION['data'],
					array(
						'column' => "res_id_master",
						'value' => $_SESSION['doc_id'],
						'type' => "integer",
					)
				);
				
				
				array_push(
					$_SESSION['data'],
					array(
						'column' => "type_id",
						'value' => 0,
						'type' => "int",
					)
				);

				array_push(
					$_SESSION['data'],
					array(
						'column' => "relation",
						'value' => 1,
						'type' => "int",
					)
				);
				
				$id = $resAttach->load_into_db(
					RES_ATTACHMENTS_TABLE,
					$storeResult['destination_dir'],
					$storeResult['file_destination_name'] ,
					$storeResult['path_template'],
					$storeResult['docserver_id'], $_SESSION['data'],
					$_SESSION['config']['databasetype']
				);
				
				if ($id == false) {
					$_SESSION['error'] = $resAttach->get_error();
				} else {
					/*if ($_SESSION['history']['attachadd'] == "true") {
						$hist = new history();
						$view = $sec->retrieve_view_from_coll_id(
							$_SESSION['collection_id_choice']
						);
						$hist->add(
							$view, $_SESSION['doc_id'], "ADD", 'attachadd',
							ucfirst(_DOC_NUM) . $id . ' '
							. _NEW_ATTACH_ADDED . ' ' . _TO_MASTER_DOCUMENT
							. $_SESSION['doc_id'],
							$_SESSION['config']['databasetype'],
							'apps'
						);
						$_SESSION['info'] = _NEW_ATTACH_ADDED;
						$hist->add(
							RES_ATTACHMENTS_TABLE, $id, "ADD",'attachadd',
							$_SESSION['info'] . " (" . $title
							. ") ",
							$_SESSION['config']['databasetype'],
							'attachments'
						);
					}*/
					return $id;
				}
			}
		}
	}
}

require_once "core/class/class_security.php";
require_once 'modules/attachments/class/attachments_controler.php';
require_once 'core/class/class_request.php';
require_once 'core/class/class_resource.php';

$sec = new security();
$ac = new attachments_controler();
$resource = new resource();
(isset($_REQUEST['join_file']) 
	&& count($_REQUEST['join_file']) > 0
)? $res_master_attached = 'Y' : $res_master_attached = 'N';
//Version attached
if (isset($_REQUEST['join_attachment']) && count($_REQUEST['join_attachment']) > 0) {
	$attachment_list = join(',', $_REQUEST['join_attachment']);
}      
//Attachments								
if (isset($_REQUEST['join_version']) && count($_REQUEST['join_version']) > 0) {
	$version_list = join(',', $_REQUEST['join_version']);
}
//Notes
if (isset($_REQUEST['notes']) && count($_REQUEST['notes']) > 0) {
	$note_list = join(',', $_REQUEST['notes']);
}
//$date = $request->current_datetime();

$list_path_folder =  array();
if ($res_master_attached == 'Y'){
	
$view = $sec->retrieve_view_from_coll_id($_SESSION['collection_id_choice']);
$where = ' and 1=1';
$adrTable = 'adr_x';
$infos_file = $resource->getResourceAdr($view, $_REQUEST['join_file'], $where, $adrTable) ;

$ds_id = $infos_file[0][0]['docserver_id'];
require_once 'core/class/docservers_controler.php';
$docserverControler = new docservers_controler();
$docserverObject = $docserverControler->get($ds_id);
$docserver_path = $docserverObject->path_template;
				
$filepath = str_replace('#', DIRECTORY_SEPARATOR, $docserver_path. DIRECTORY_SEPARATOR .$infos_file[0][0]['path'].$infos_file[0][0]['filename']);

array_push($list_path_folder, $filepath);
}

if (isset($_REQUEST['join_attachment']) && count($_REQUEST['join_attachment']) > 0){
	foreach($_REQUEST['join_attachment'] as $id_attach){
		$infos_attach = $ac->getAttachmentInfos($id_attach);
		array_push($list_path_folder, $infos_attach['pathfile_pdf']);
	}
}
if (isset($_REQUEST['notes']) && count($_REQUEST['notes']) > 0) {
	$path_file_notes = createPdfNotes($_REQUEST['notes'], $_SESSION['user']['collections'][0]);
	array_push($list_path_folder, $path_file_notes);
}
//echo print_r($list_path_folder,true);
if (count($list_path_folder) == 0){
	echo "{status : 1, error_txt : '"._NO_FILE_PRINT."'}";exit();
} 
else{
	$out_file = concat_files($list_path_folder);
	if (!file_exists($out_file)) echo "{status : -1, error_txt : '".$out_file."'}";
	else{ 
		$id_folder = ajout_bdd($out_file,$_SESSION['doc_id']);
		echo "{status : 0, id_folder : $id_folder}";
	}
	exit ();
}
