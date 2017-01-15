<?php

include_once '../../core/init.php';
require_once 'core/class/class_portal.php';
require_once 'core/class/class_functions.php';
require_once 'core/class/class_db.php';
require_once 'core/class/class_core_tools.php';
require_once 'core/core_tables.php';
require_once 'core/class/class_request.php';
require_once 'core/class/class_history.php';
require_once 'core/class/SecurityControler.php';
require_once 'core/class/class_resource.php';
require_once 'core/class/docservers_controler.php';
require_once 'core/docservers_tools.php';
require_once 'modules/content_management/class/class_content_manager_tools.php';

function writeLogIndex($EventInfo)
{
    $logFileOpened = fopen($_SESSION['config']['corepath'] . '/modules/visa/log/appletController_' . date('Y') . '_' . date('m'). '_' . date('d') . '.log', 'a');
    fwrite($logFileOpened, '[' . date('d') . '/' . date('m') . '/' . date('Y')
        . ' ' . date('H') . ':' . date('i') . ':' . date('s') . '] ' . $EventInfo
        . "\r\n"
    );
    fclose($logFileOpened);
}



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
$signfileContent = '';
$fileExtension = '';
$error = '';

$cM = new content_management_tools();

if (
    !empty($_REQUEST['action'])
    && !empty($_REQUEST['objectType'])
    && !empty($_REQUEST['objectTable'])
    && !empty($_REQUEST['objectId'])
) {
    $objectType = $_REQUEST['objectType'];
    $objectTable = $_REQUEST['objectTable'];
    $_REQUEST['objectId'] = str_replace("\\", "", $_REQUEST['objectId']);
    $_REQUEST['objectId'] = str_replace("/", "", $_REQUEST['objectId']);
    $_REQUEST['objectId'] = str_replace("..", "", $_REQUEST['objectId']);
    $objectId = $_REQUEST['objectId'];
    $appPath = 'start';
    if ($_REQUEST['action'] == 'editObject') {
        //createXML('ERROR', $objectType . ' ' . $objectId);
        $core_tools = new core_tools();
        $core_tools->test_user();
        $core_tools->load_lang();
        $function = new functions();
        
        include 'modules/visa/retrieve_attachment_from_cm.php';
	   
        $status = 'ok';
        $content = file_get_contents($filePathOnTmp, FILE_BINARY);
		$encodedContent = base64_encode($content);
        $fileContent = $encodedContent;
		
		
		$signfileOnDs = $_SESSION['user']['pathToSignature'];
		
		
		$content_sign = file_get_contents($signfileOnDs, FILE_BINARY);
		$encodedContentSign = base64_encode($content_sign);
        $signfileContent = $encodedContentSign;
			
		/********************************/
		
		
        $result = array(
            'STATUS' => $status,
            'OBJECT_TYPE' => $objectType,
            'OBJECT_TABLE' => $objectTable,
            'OBJECT_ID' => $objectId,
            'APP_PATH' => $appPath,
            'FILE_CONTENT' => $fileContent,
            'IMG_FILE_CONTENT' => $signfileContent,
            'FILE_EXTENSION' => $fileExtension,
            'LICENCE_NUM' => $_SESSION['modules_loaded']['visa']['licence_number'],
            'ERROR' => '',
            'END_MESSAGE' => '',
        );
        unlink($filePathOnTmp);
        createXML('SUCCESS', $result);
    } elseif ($_REQUEST['action'] == 'saveObject') {
		if (!empty($_REQUEST['errorCode'])
            && !empty($_REQUEST['errorCode'])){
			$result = array(
				'NEW_ID' => 0,
                'END_MESSAGE' => $_REQUEST['errorCode'],
            );
            createXML('ERROR', $result);
		}
        elseif (
            !empty($_REQUEST['fileContent'])
            && !empty($_REQUEST['fileExtension'])
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
					writeLogIndex("Lancement sauvegarde");
                    include 'modules/visa/save_attach_res_from_cm.php';
					writeLogIndex("Fin sauvegarde");
                //THE RETURN
				$result = array(
					'NEW_ID' => $_SESSION['visa']['last_ans_signed'],
					'END_MESSAGE' => $_SESSION['error'] . _END_OF_EDITION,
				);
				writeLogIndex(print_r($result,true));
				createXML('ERROR', $result);
				//unset($_SESSION['visa']['last_ans_signed']);
            }
        } else {
            $result = array(
                'ERROR' => _FILE_CONTENT_OR_EXTENSION_EMPTY,
            );
            createXML('ERROR', $result);
        }
    } 
} else {
    $result = array(
        'STATUS' => $status,
        'OBJECT_TYPE' => $objectType,
        'OBJECT_TABLE' => $objectTable,
        'OBJECT_ID' => $objectId,
        'APP_PATH' => $appPath,
        'FILE_CONTENT' => $fileContent,
        'IMG_FILE_CONTENT' => $signfileContent,
        'FILE_EXTENSION' => $fileExtension,
		'LICENCE_NUM' => $_SESSION['modules_loaded']['visa']['licence_number'],
        'ERROR' => 'missing parameters, action:' . $_REQUEST['action']
            . ', objectType:' . $_REQUEST['objectType']
            . ', objectTable:' . $_REQUEST['objectTable']
            . ', objectId:' . $_REQUEST['objectId'],
        'END_MESSAGE' => '',
    );
    createXML('ERROR', $result);
}
