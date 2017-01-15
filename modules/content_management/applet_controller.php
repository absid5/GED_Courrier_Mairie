<?php

/*
*
*    Copyright 2008,2015 Maarch
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
*
*   @author <dev@maarch.org>
*/

if (
    file_exists('..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR. '..' 
                . DIRECTORY_SEPARATOR . 'core'. DIRECTORY_SEPARATOR . 'init.php'
    )
) {
    include_once '../../../../core/init.php';
} else {
    include_once '../../core/init.php';
}

require_once 'core/class/class_portal.php';
require_once 'core/class/class_functions.php';
require_once 'core/class/class_db.php';
require_once 'core/class/class_db_pdo.php';
require_once 'core/class/class_core_tools.php';
require_once 'core/core_tables.php';
require_once 'core/class/class_request.php';
require_once 'core/class/class_history.php';
require_once 'core/class/SecurityControler.php';
require_once 'core/class/class_resource.php';
require_once 'core/class/docservers_controler.php';
require_once 'core/docservers_tools.php';
require_once 'modules/content_management/class/class_content_manager_tools.php';

//Create XML
function createXML($rootName, $parameters)
{
    if ($rootName == 'ERROR') {
        $cM = new content_management_tools();
        $cM->closeReservation($_SESSION['cm']['reservationId']);
    }
    global $debug, $debugFile;
    $rXml = new DomDocument("1.0","UTF-8");
    $rRootNode = $rXml->createElement($rootName);
    $rXml->appendChild($rRootNode);
    if (is_array($parameters)) {
        foreach ($parameters as $kPar => $dPar) {
            $node = $rXml->createElement($kPar,$dPar);
            $rRootNode->appendChild($node);
        }
    } else {
        $rRootNode->nodeValue = $parameters;
    }
    if ($debug) {
        $rXml->save($debugFile);
    }
    header("content-type: application/xml");
    echo $rXml->saveXML();
    /* for the tests only
    $text = $rXml->saveXML();
    $inF = fopen('wsresult.log','a');
    fwrite($inF, $text);
    fclose($inF);*/
    exit;
}

//test if session is loaded
/*
createXML('ERROR', 'SESSION INFO ####################################'
    . $_SESSION['user']['UserId']
);
*/

$status = 'ko';
$objectType = '';
$objectTable = '';
$objectId = '';
$appPath = '';
$fileContent = '';
$fileExtension = '';
$error = '';

$cM = new content_management_tools();

if (!empty($_REQUEST['action'])
    && !empty($_REQUEST['objectType'])
    && !empty($_REQUEST['objectTable'])
    && !empty($_REQUEST['objectId'])
    && isset($_REQUEST['uniqueId']))
{
    $objectType = $_REQUEST['objectType'];
    $objectTable = $_REQUEST['objectTable'];
    // $_REQUEST['objectId'] = str_replace("\\", "", $_REQUEST['objectId']);
    // $_REQUEST['objectId'] = str_replace("/", "", $_REQUEST['objectId']);
    $_REQUEST['objectId'] = str_replace("..", "", $_REQUEST['objectId']);
    $objectId = $_REQUEST['objectId'];
    $uniqueId = $_REQUEST['uniqueId'];
    $appPath = 'start';
    if ($_REQUEST['action'] == 'editObject') {
        //createXML('ERROR', $objectType . ' ' . $objectId);
        $core_tools = new core_tools();
        $core_tools->test_user();
        $core_tools->load_lang();
        $function = new functions();
        if (
            $objectType <> 'template' 
            && $objectType <> 'templateStyle'
            && $objectType <> 'attachmentFromTemplate'
            && $objectType <> 'attachment'
            && $objectType <> 'attachmentVersion'
            && $objectType <> 'outgoingMail'
            && $objectType <> 'attachmentUpVersion'
            && $objectType != 'transmission'
        ) {
            //case of res -> master or version
            include 'modules/content_management/retrieve_res_from_cm.php';
        } elseif ($objectType == 'attachment' || $objectType == 'attachmentUpVersion') {
            //case of res -> update attachment
            include 'modules/content_management/retrieve_attachment_from_cm.php';
        } else {
            //case of template, templateStyle, or new attachment generation
            include 'modules/content_management/retrieve_template_from_cm.php';
        }
        $status = 'ok';
        $content = file_get_contents($filePathOnTmp, FILE_BINARY);
		$encodedContent = base64_encode($content);
        $fileContent = $encodedContent;
		
		if ($_SESSION['modules_loaded']['attachments']['convertPdf'] == "true"){
			
			if ($_SESSION['modules_loaded']['attachments']['useExeConvert'] == "false"){
				//Transmission du fichier VBS de conversion
				if (
					file_exists('custom'.DIRECTORY_SEPARATOR. $_SESSION['custom_override_id']
								. DIRECTORY_SEPARATOR . 'modules'. DIRECTORY_SEPARATOR . 'content_management'
								. DIRECTORY_SEPARATOR . 'DOC2PDF_VBS.vbs'
					)
				) {
					$vbsFile = 'custom/'. $_SESSION['custom_override_id'] .'/modules/content_management/DOC2PDF_VBS.vbs';
				} else {
					$vbsFile = 'modules/content_management/DOC2PDF_VBS.vbs';
				}		
				$content_vbsFile = file_get_contents($vbsFile, FILE_BINARY);
				$encodedContent_vbsFile = base64_encode($content_vbsFile);
			}
			else{
				if (
					file_exists('custom'.DIRECTORY_SEPARATOR. $_SESSION['custom_override_id']
								. DIRECTORY_SEPARATOR . 'modules'. DIRECTORY_SEPARATOR . 'content_management'
								. DIRECTORY_SEPARATOR . 'dist' . DIRECTORY_SEPARATOR . 'Word2Pdf.exe'
					)
				) {
					$exeFile = 'custom/'. $_SESSION['custom_override_id'] .'/modules/content_management/dist/Word2Pdf.exe';
				} else {
					$exeFile = 'modules/content_management/dist/Word2Pdf.exe';
				}		
				$content_exeFile = file_get_contents($exeFile, FILE_BINARY);
				$encodedContent_exeFile = base64_encode($content_exeFile);
			}
			
		}
		        
		if ($_SESSION['modules_loaded']['attachments']['convertPdf'] == "true"){
			if ($_SESSION['modules_loaded']['attachments']['useExeConvert'] == "false"){
				$result = array(
					'STATUS' => $status,
					'OBJECT_TYPE' => $objectType,
					'OBJECT_TABLE' => $objectTable,
					'OBJECT_ID' => $objectId,
					'UNIQUE_ID' => $uniqueId,
					'APP_PATH' => $appPath,
					'FILE_CONTENT' => $fileContent,
					'FILE_CONTENT_VBS' => $encodedContent_vbsFile,
					'VBS_PATH' => $_SESSION['modules_loaded']['attachments']['vbs_convert_path'],
					'USE_EXE_CONVERT' => "false",
					'FILE_EXTENSION' => $fileExtension,
					'ERROR' => '',
					'END_MESSAGE' => '',
				);
			}
			else{
				$result = array(
					'STATUS' => $status,
					'OBJECT_TYPE' => $objectType,
					'OBJECT_TABLE' => $objectTable,
					'OBJECT_ID' => $objectId,
					'UNIQUE_ID' => $uniqueId,
					'APP_PATH' => $appPath,
					'FILE_CONTENT' => $fileContent,
					'FILE_CONTENT_EXE' => $encodedContent_exeFile,
					'USE_EXE_CONVERT' => "true",
					'FILE_EXTENSION' => $fileExtension,
					'ERROR' => '',
					'END_MESSAGE' => '',
				);
			}
		}
		else{
			$result = array(
				'STATUS' => $status,
				'OBJECT_TYPE' => $objectType,
				'OBJECT_TABLE' => $objectTable,
				'OBJECT_ID' => $objectId,
				'UNIQUE_ID' => $uniqueId,
				'APP_PATH' => $appPath,
				'FILE_CONTENT' => $fileContent,
				'FILE_EXTENSION' => $fileExtension,
				'ERROR' => '',
				'END_MESSAGE' => '',
			);
		}
        unlink($filePathOnTmp);
        createXML('SUCCESS', $result);
    } elseif ($_REQUEST['action'] == 'saveObject') {
        if (
            !empty($_REQUEST['fileContent'])
            && !empty($_REQUEST['fileExtension'])
            && preg_match('/[0-9a-z]{1,5}$/i', $_REQUEST['fileExtension'])
        ) {
            $fileEncodedContent = str_replace(
                ' ',
                '+',
                $_REQUEST['fileContent']
            );
            $_REQUEST['fileExtension'] = str_replace("\\", "", $_REQUEST['fileExtension']);
            $_REQUEST['fileExtension'] = str_replace("/", "", $_REQUEST['fileExtension']);
            $_REQUEST['fileExtension'] = str_replace("..", "", $_REQUEST['fileExtension']);
            $fileExtension = $_REQUEST['fileExtension'];
            $fileContent = base64_decode($fileEncodedContent);
            //copy file on Maarch tmp dir
            $tmpFileName = 'cm_tmp_file_' . $_SESSION['user']['UserId']
                . '_' . rand() . '.' . strtolower($fileExtension);
            $inF = fopen($_SESSION['config']['tmppath'] . $tmpFileName, 'w');
            fwrite($inF, $fileContent);
            fclose($inF);
			
			//Récupération de la version pdf du document
			if ($_SESSION['modules_loaded']['attachments']['convertPdf'] == "true" && ($objectType == 'attachmentFromTemplate' || $objectType == 'attachment' || $objectType == 'attachmentUpVersion' || $objectType == 'attachmentVersion' || $objectType == 'outgoingMail' || $objectType == 'resourceEdit' || $objectType == 'transmission') && isset($_REQUEST['pdfContent'])){
				$pdfEncodedContent = str_replace(
					' ',
					'+',
					$_REQUEST['pdfContent']
				);
				$pdfContent = base64_decode($pdfEncodedContent);
				//copy file on Maarch tmp dir
				$tmpFilePdfName = 'cm_tmp_file_pdf_' . $_SESSION['user']['UserId']
					. '_' . rand() . '.pdf';
				$inFpdf = fopen($_SESSION['config']['tmppath'] . $tmpFilePdfName, 'w');
				fwrite($inFpdf, $pdfContent);
				fclose($inFpdf);
			}
			
            $arrayIsAllowed = array();
            $arrayIsAllowed = Ds_isFileTypeAllowed(
                $_SESSION['config']['tmppath'] . $tmpFileName
            );
            if ($arrayIsAllowed['status'] == false) {
                $result = array(
                    'ERROR' => _WRONG_FILE_TYPE
                    . ' ' . $arrayIsAllowed['mime_type']
                );
                createXML('ERROR', $result);
            } else {
                //depending on the type of object, the action is not the same
                if ($objectType == 'resource') {
                    include 'modules/content_management/save_new_version_from_cm.php';
                }
                if ($objectType == 'resourceEdit') {
                    include 'modules/content_management/save_editRes_from_cm.php';
                } elseif ($objectType == 'attachmentFromTemplate') {
                    include 'modules/content_management/save_attach_res_from_cm.php';
                } elseif ($objectType == 'attachment') {
                    include 'modules/content_management/save_attach_from_cm.php';
                } elseif ($objectType == 'templateStyle' || $objectType == 'template') {
                    include 'modules/content_management/save_template_from_cm.php';
                } elseif ($objectType == 'attachmentVersion' || $objectType == 'attachmentUpVersion') {
                    include 'modules/content_management/save_attachment_from_cm.php';
                } elseif ($objectType == 'outgoingMail') {
                    include 'modules/content_management/save_outmail_from_cm.php';
                } elseif ($objectType == 'transmission') {
                    include 'modules/content_management/save_transmission_from_cm.php';
                }

                if ($_REQUEST['step']== 'end') {
                    $file = $_SESSION['config']['tmppath'].'applet_'.$_REQUEST['userMaarch'].'.lck';
                    unlink($file);
                }

                //THE RETURN
                if (!empty($_SESSION['error'])) {
                    $result = array(
                        'END_MESSAGE' => $_SESSION['error'] . _END_OF_EDITION,
                    );
                    createXML('ERROR', $result);
                } else {
                    $cM->closeReservation($_SESSION['cm']['reservationId']);
                    $result = array(
                        'END_MESSAGE' => _UPDATE_OK,
                    );
                    createXML('SUCCESS', $result);
                }
            }
        } else {
            $result = array(
                'ERROR' => _FILE_CONTENT_OR_EXTENSION_EMPTY,
            );
            createXML('ERROR', $result);
        }
    } elseif ($_REQUEST['action'] == 'sendPsExec') {
        $pathToPsExec = 'modules/content_management/dist/PsExec.exe';
        if (file_exists($pathToPsExec)) {
            $content = file_get_contents($pathToPsExec, FILE_BINARY);
            $encodedContent = base64_encode($content);
            $fileContent = $encodedContent;
            $status = 'ok';
            $error = '';
            $success = 'SUCCESS';
        } else {
            $status = 'ko';
            $error = 'file not exists on the server: ' . $pathToPsExec;
            $success = 'ERROR';
        }
        $result = array(
            'STATUS' => $status,
            'OBJECT_TYPE' => $objectType,
            'OBJECT_TABLE' => $objectTable,
            'OBJECT_ID' => $objectId,
            'APP_PATH' => $appPath,
            'FILE_CONTENT' => $fileContent,
            'FILE_EXTENSION' => $fileExtension,
            'ERROR' => $error,
            'END_MESSAGE' => '',
        );
        createXML($success, $result);
    }
} else {
    $result = array(
        'STATUS' => $status,
        'OBJECT_TYPE' => $objectType,
        'OBJECT_TABLE' => $objectTable,
        'OBJECT_ID' => $objectId,
        'APP_PATH' => $appPath,
        'FILE_CONTENT' => $fileContent,
        'FILE_EXTENSION' => $fileExtension,
        'ERROR' => 'missing parameters, action:' . $_REQUEST['action']
            . ', objectType:' . $_REQUEST['objectType']
            . ', objectTable:' . $_REQUEST['objectTable']
            . ', objectId:' . $_REQUEST['objectId'],
        'END_MESSAGE' => '',
    );
    createXML('ERROR', $result);
}
