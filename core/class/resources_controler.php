<?php
/*
*   Copyright 2011-2015 Maarch
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
* @brief  Contains the controler of the Resource Object
*
*
* @file
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

// To activate de debug mode of the class
$_ENV['DEBUG'] = false;
/*
define("_CODE_SEPARATOR","/");
define("_CODE_INCREMENT",1);
*/

// Loads the required class
try {
    require_once 'core/class/resources.php';
    require_once 'core/core_tables.php';
    require_once 'core/class/class_functions.php';
    require_once 'core/class/docservers_controler.php';
    require_once 'core/class/class_resource.php';
} catch (Exception $e) {
    echo functions::xssafe($e->getMessage()).' // ';
}

/**
* @brief  Controler of the Resource Object
*
* @ingroup core
*/
class resources_controler
{
    #####################################
    ## Web Service de versement de données issue du gros scanner
    #####################################
    public function storeResource($encodedFile, $dataObject, $collId, $table, $fileFormat, $status)
    {
        try {
            $func = new functions();
            $theData = $func->object2array($dataObject);
            if (count($theData) == 1) {
                $data = array();
                if (is_array($theData['datas'])) {
                    array_push($data, $theData['datas']);
                } else {
                    array_push($data, $theData);
                }
            } else {
                $data = $theData;
            }
            for ($i=0;$i< count($data);$i++) {
                $data[$i]['column'] = strtolower($data[$i]['column']);
            }
            
            $returnCode = 0;
            $db = new Database();
            //copy sended file on tmp 
            $fileContent = base64_decode($encodedFile);
            $random = rand();
            $fileName = 'tmp_file_' . $random . '.' . $fileFormat;
            $Fnm = $_SESSION['config']['tmppath'] . $fileName;
            $inF = fopen($Fnm,"w");
            fwrite($inF, $fileContent);
            fclose($inF);
            //store resource on docserver
            $docserverControler = new docservers_controler();
            $fileInfos = array(
                'tmpDir'      => $_SESSION['config']['tmppath'],
                'size'        => filesize($Fnm),
                'format'      => $fileFormat,
                'tmpFileName' => $fileName,
            );
            //print_r($fileInfos);
            $storeResult = array();
            $storeResult = $docserverControler->storeResourceOnDocserver(
                $collId, $fileInfos
            );
            if (!empty($storeResult['error'])) {
                $returnResArray = array(
                    'returnCode' => (int) -3,
                    'resId' => '',
                    'error' => $storeResult['error'],
                );
                return $returnResArray;
            }
            //print_r($storeResult);exit;
            //store resource metadata in database
            $resource = new resource();
            
            $data = $this->prepareStorage(
                $data, 
                $storeResult['docserver_id'],
                $status,
                $fileFormat
            );
            unlink($Fnm);
            //var_dump($data);exit;
            $resId = $resource->load_into_db(
                $table, 
                $storeResult['destination_dir'],
                $storeResult['file_destination_name'],
                $storeResult['path_template'],
                $storeResult['docserver_id'], 
                $data,
                $_SESSION['config']['databasetype'],
                true
            );
            if (!is_numeric($resId)) {
                $returnResArray = array(
                    'returnCode' => (int) -2,
                    'resId' => '',
                    'error' => 'Pb with SQL insertion : ' .$resId ,
                );
                return $returnResArray;
            }
            if ($resId == 0) {
                $resId = '';
            }
            $returnResArray = array(
                'returnCode' => (int) 0,
                'resId' => $resId,
                'error' => '',
            );
            return $returnResArray;
        } catch (Exception $e) {
            $returnResArray = array(
                'returnCode' => (int) -1,
                'resId' => '',
                'error' => 'unknown error' . $e->getMessage(),
            );
            return $returnResArray;
        }
    }

    private function prepareStorage($data, $docserverId, $status, $fileFormat)
    {
        $statusFound = false;
        $typistFound = false;
        $typeIdFound = false;
        $toAddressFound = false;
        $userPrimaryEntity = false;
        $destinationFound = false;
        $initiatorFound = false;
        $db = new Database();
        for ($i=0;$i<count($data);$i++) {
            if (strtoupper($data[$i]['type']) == 'INTEGER' || strtoupper($data[$i]['type']) == 'FLOAT') {
                if ($data[$i]['value'] == '') {
                    $data[$i]['value'] = '0';
                }
            }
            if (strtoupper($data[$i]['type']) == 'STRING') {
               $data[$i]['value'] = $data[$i]['value'];
               $data[$i]['value'] = str_replace(";", "", $data[$i]['value']);
               $data[$i]['value'] = str_replace("--", "", $data[$i]['value']);
            }
            if (strtoupper($data[$i]['column']) == strtoupper('status')) {
                $statusFound = true;
            }
            if (strtoupper($data[$i]['column']) == strtoupper('typist')) {
                $typistFound = true;
            }
            if (strtoupper($data[$i]['column']) == strtoupper('type_id')) {
                $typeIdFound = true;
            }
            if (strtoupper($data[$i]['column']) == strtoupper('custom_t10')) {
                require_once 'core/class/class_db_pdo.php';
                $db = new Database();
                $mail = array();
                $theString = str_replace(">", "", $data[$i]['value']);
                $mail = explode("<", $theString);
                $queryUser = "SELECT user_id FROM users WHERE mail = ? and status = 'OK'";
                $stmt = $db->query($queryUser, array($mail[count($mail) -1]));
                $userIdFound = $stmt->fetchObject();
                if (!empty($userIdFound->user_id)) {
                    $toAddressFound = true;
                    $destUser = $userIdFound->user_id;

	                $queryUserEntity = "SELECT entity_id FROM users_entities WHERE primary_entity = 'Y' and user_id = ?";
	                $stmt = $db->query($queryUserEntity, array($destUser));
	                $userEntityId = $stmt->fetchObject();
	                if (!empty($userEntityId->entity_id)) {
	                	$userEntity = $userEntityId->entity_id;
	                	$userPrimaryEntity = true;
	                }
                }else{
                $queryEntity = "SELECT entity_id FROM entities WHERE email = ? and enabled = 'Y'";
                $stmt = $db->query($queryEntity, array($mail[count($mail) -1]));
                $entityIdFound = $stmt->fetchObject();
                $userEntity = $entityIdFound->entity_id;
                    if(!empty($userEntity)){
                        $userPrimaryEntity = true;
                        }
                        
                }
            }
        }
        if (!$typistFound && !$toAddressFound) {
            array_push(
                $data,
                array(
                    'column' => 'typist',
                    'value' => 'auto',
                    'type' => 'string',
                )
            );
        }
        if (!$typeIdFound) {
            array_push(
                $data,
                array(
                    'column' => 'type_id',
                    'value' => '10',
                    'type' => 'string',
                )
            );
        }
        if (!$statusFound) {
            array_push(
                $data,
                array(
                    'column' => 'status',
                    'value' => $status,
                    'type' => 'string',
                )
            );
        }
        if ($toAddressFound) {
            array_push(
                $data,
                array(
                    'column' => 'dest_user',
                    'value' => $destUser,
                    'type' => 'string',
                )
            );
            array_push(
                $data,
                array(
                    'column' => 'typist',
                    'value' => $destUser,
                    'type' => 'string',
                )
            );
        }
        if ($userPrimaryEntity) {
            for ($i=0;$i<count($data);$i++) {
                if (strtoupper($data[$i]['column']) == strtoupper('destination')) {
                    if ($data[$i]['value'] == "") {
                        $data[$i]['value'] = $userEntity;
                    }
                    $destinationFound = true;
                    break;
                }
            }
            if (!$destinationFound) {
                array_push(
                    $data,
                    array(
                        'column' => 'destination',
                        'value' => $userEntity,
                        'type' => 'string',
                    )
                );
            }
        }
        if ($userPrimaryEntity) {
            for ($i=0;$i<count($data);$i++) {
                if (strtoupper($data[$i]['column']) == strtoupper('initiator')) {
                    if ($data[$i]['value'] == "") {
                        $data[$i]['value'] = $userEntity;
                    }
                    $initiatorFound = true;
                    break;
                }
            }
            if (!$initiatorFound) {
                array_push(
                    $data,
                    array(
                        'column' => 'initiator',
                        'value' => $userEntity,
                        'type' => 'string',
                    )
                );
            }
        }    
        array_push(
            $data,
            array(
                'column' => 'format',
                'value' => $fileFormat,
                'type' => 'string',
            )
        );
        array_push(
            $data,
            array(
                'column' => 'offset_doc',
                'value' => '',
                'type' => 'string',
            )
        );
        array_push(
            $data,
            array(
                'column' => 'logical_adr',
                'value' => '',
                'type' => 'string',
            )
        );
        array_push(
            $data,
            array(
                'column' => 'docserver_id',
                'value' => $docserverId,
                'type' => 'string',
            )
        );
        return $data;
    }
    
    #####################################
    ## Store datas of the resource in extension table 
    #####################################
    public function storeExtResource($resId, $data, $table)
    {
        try {
        	if ($resId <> "") {
	            $func = new functions();
	            $data = $func->object2array($data);
	            $queryExtFields = '(';
	            $queryExtValues = '(';
                $queryExtValuesFinal = '('; 
                $parameters = array();
	            $db = new Database();
                $findProcessLimitDate = false;
                $findProcessNotes = false;
                $delayProcessNotes = 0;

                for ($i=0;$i<count($data);$i++) {
                    if ($data[$i]['column'] == 'process_limit_date') {
                        $findProcessLimitDate = true;
                    }
                    // if ($data[$i]['column'] == 'process_notes') {
                    //     $findProcessNotes = true;
                    //     $delayProcessNotes = $data[$i]['value'];
                    // }
					if ($data[$i]['column'] == 'process_notes') {
		                $findProcessNotes = true;
		                $donnees = explode(',',$data[$i]['value']);
		                $delayProcessNotes = $donnees['0'];
		                $calendarType = $donnees['1'];
		            }
                }

                if ($table == 'mlb_coll_ext') {
                    if ($delayProcessNotes > 0) {
                        $processLimitDate = $this->retrieveProcessLimitDate(
                            $resId, 
                            $delayProcessNotes,
                        	$calendarType
                        );
                    } else {
                        $processLimitDate = $this->retrieveProcessLimitDate($resId);
                    }
                    //echo $processLimitDate;
        		}
        		
        		if (!$findProcessLimitDate && $processLimitDate <> '') {
            		array_push(
            		    $data,
            		    array(
            			'column' => 'process_limit_date',
            			'value' => $processLimitDate,
            			'type' => 'date',
            		    )
            		);
        		}

		        //var_dump($data);
	            for ($i=0;$i<count($data);$i++) {
	                if (strtoupper($data[$i]['type']) == 'INTEGER' || strtoupper($data[$i]['type']) == 'FLOAT') {
	                    if ($data[$i]['value'] == '') {
	                        $data[$i]['value'] = '0';
	                    }
	                    $data[$i]['value'] = str_replace(',' , '.', $data[$i]['value']);
	                }
		            if (strtoupper($data[$i]['column']) == strtoupper('category_id')) {
		                $categoryId = $data[$i]['value'];
		            }
		            if (strtoupper($data[$i]['column']) == strtoupper('alt_identifier') && $data[$i]['value'] == "") {
		            	require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    						. DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'class_chrono.php';
		            	$chronoX = new chrono();
		            	for ($iColl=0; $iColl<=count($_SESSION['collections']); $iColl++) {
		            		if ($_SESSION['collections'][$iColl]['extensions'][0] == $table) {
		            			$resViewTable = $_SESSION['collections'][$iColl]['view'];
		            			break;
		            		}
		            	}
		            	$stmt = $db->query("SELECT destination, type_id FROM ".$resViewTable." WHERE res_id = ?", array($resId));
		            	$resView = $stmt->fetchObject();
				        $myVars = array(
				            'entity_id' => $resView->destination,
				            'type_id' => $resView->type_id,
				            'category_id' => $categoryId,
				            'folder_id' => "",
				        );
				        $myChrono = $chronoX->generate_chrono($categoryId, $myVars, 'false');
				        $data[$i]['value'] = $myChrono;		                
		            }
                    if (strtoupper($data[$i]['column']) == strtoupper('exp_contact_id') && $data[$i]['value'] <> "" && !is_numeric($data[$i]['value'])) {
                        $theString = str_replace(">", "", $data[$i]['value']);
                        $mail = explode("<", $theString);
                        $stmt = $db->query("SELECT contact_id FROM view_contacts WHERE email = ? and enabled = 'Y' order by creation_date asc", array($mail[count($mail) -1]));
                        $contact = $stmt->fetchObject();

                        if ($contact->contact_id <> "") {
                            $data[$i]['value'] = $contact->contact_id;
                        } else {
                            $data[$i]['value'] = 0;
                        }
                    }
                    if (strtoupper($data[$i]['column']) == strtoupper('address_id') && $data[$i]['value'] <> "" && !is_numeric($data[$i]['value'])) {
                        $theString = str_replace(">", "", $data[$i]['value']);
                        $mail = explode("<", $theString);
                        $stmt = $db->query("SELECT ca_id FROM view_contacts WHERE email = ? and enabled = 'Y' order by creation_date asc", array($mail[count($mail) -1]));
                        $contact = $stmt->fetchObject();
                        if ($contact->ca_id <> "") {
                            $data[$i]['value'] = $contact->ca_id;
                        } else {
                            $data[$i]['value'] = 0;
                        }
                    }
	                //COLUMN
	                $data[$i]['column'] = strtolower($data[$i]['column']);
	                $queryExtFields .= $data[$i]['column'] . ',';
	                //VALUE
	                if ($data[$i]['type'] == 'string' || $data[$i]['type'] == 'date') {
	                    $queryExtValues .= "'" . $data[$i]['value'] . "',";
	                } else {
	                    $queryExtValues .= $data[$i]['value'] . ",";
	                }
                    $parameters[] = $data[$i]['value'];
                    $queryExtValuesFinal .= "?,";
	            }
	            $queryExtFields = preg_replace('/,$/', ',res_id)', $queryExtFields);
	            $queryExtValues = preg_replace(
	                '/,$/', ',' . $resId . ')', $queryExtValues
	            );
                $queryExtValuesFinal = preg_replace(
                    '/,$/', ',' . $resId . ')', $queryExtValuesFinal
                );
	            /*$queryExt = " insert into " . $table . " " . $queryExtFields
	                   . ' values ' . $queryExtValues ;*/
                $queryExt = " insert into " . $table . " " . $queryExtFields
                       . ' values ' . $queryExtValuesFinal ;
                //echo $queryExt;exit;
	            $returnCode = 0;
	            if ($db->query($queryExt, $parameters)) {
	                $returnResArray = array(
	                    'returnCode' => (int) 0,
	                    'resId' => $resId,
	                    'error' => '',
	                );
	            } else {
	                $returnResArray = array(
	                    'returnCode' => (int) -2,
	                    'resId' => '',
	                    'error' => 'Pb with SQL insertion',
	                );
	            }
	            return $returnResArray;
            } else {
	            $returnResArray = array(
	                'returnCode' => (int) -3,
	                'resId' => '',
	                'error' => 'resId is not set',
	            );
	            return $returnResArray;            	
            }
        } catch (Exception $e) {
            $returnResArray = array(
                'returnCode' => (int) -1,
                'resId' => '',
                'error' => 'unknown error' . $e->getMessage(),
            );
            return $returnResArray;
        }
    }

    #####################################
    ## Retrieve process_limit_date for resource in extension table if mlb
    #####################################
    public function retrieveProcessLimitDate($resId, $defaultDelay = 0, $calendarType = FALSE)
    { 
        $processLimitDate = '';
        if ($resId <> '') {
            $db = new Database();
            $stmt = $db->query("select creation_date, admission_date, "
                . "type_id from res_view_letterbox where res_id = ?"
		        , array($resId)
            );
            $line = $stmt->fetchObject();
            if ($line->type_id <> '') {
                $typeId = $line->type_id;
                $admissionDate = $line->admission_date;
                $creationDate = $line->creation_date;
                $stmtDelay = $db->query("select process_delay from mlb_doctype_ext where type_id = ?" 
                    , array($line->type_id)
                );
                $lineDelay = $stmtDelay->fetchObject();
                $delay = $lineDelay->process_delay;
            }
            if ($admissionDate == '') {
                $dateToCompute = $creationDate;
            } else {
                $dateToCompute = $admissionDate;
            }
            if ($defaultDelay > 0) {
                $delay = $defaultDelay;
            } elseif ($delay == 0) {
                $delay = 5;
            }
            require_once('core/class/class_alert_engine.php');
            $alert_engine = new alert_engine();
            if (isset($dateToCompute) && !empty($dateToCompute)) {

                $convertedDate = $alert_engine->dateFR2Time(
                   str_replace("-", "/", $db->format_date_db($dateToCompute,'true','','true')), true
                ); 
                $date = $alert_engine->WhenOpenDay($convertedDate, $delay, false ,$calendarType);

            } else {
                $date = $alert_engine->date_max_treatment($delay, false);
            }
            $processLimitDate = $db->dateformat($date, '-');
        }
        return $processLimitDate;
    }
    
    function Demo_searchResources($searchParams)
    {
        $whereClause = '';
        if ($searchParams->countryForm <> '') {
            $whereClause .= " custom_t3 = '" . $searchParams->countryForm . "' and ";
        }
        if ($searchParams->docDateForm <> '') {
            $whereClause .= " doc_date >= '" . $searchParams->docDateForm . "'";
        }
        $listResult = array();
        try {
            $db = new Database();
            $cpt = 0;
            $stmt = $db->query("select * from res_x where " . $whereClause . " ORDER BY res_id ASC");
            if ($stmt->rowCount() > 0) {
                while ($line = $stmt->fetchObject()) {
                    $listResult[$cpt]['resid'] = $line->res_id;
                    $listResult[$cpt]['subject'] = $line->subject;
                    $listResult[$cpt]['docdate'] = $line->doc_date;
                    $cpt++;
                }
            } else {
                $error = _NO_DOC_OR_NO_RIGHTS;
            }
        } catch (Exception $e) {
            $fault = new SOAP_Fault($e->getMessage(), '1');
            return $fault->message();
        }
        $return = array(
            'status' => 'ok',
            'value' => $listResult,
            'error' => $error,
        );
        return $return;
    }

    #####################################
    ## Web Service to retrieve attachment from an identifier
    #####################################
    public function retrieveMasterResByChrono($identifier, $collId)
    {
        try {
            $db = new Database();
            $resultArr = array();
            
            if ($identifier == '') {
                $resultArr = array(
                    'returnCode' => (int) -2,
                    'resId' => '',
                    'title' => '',
                    'identifier' => '',
                    'status' => '',
                    'attachment_type' => '',
                    'dest_contact_id' => '',
                    'dest_address_id' => '',
                    'error' => 'param identifier empty',
                );
                return $resultArr;
            }

            if ($collId == '') {
                $resultArr = array(
                    'returnCode' => (int) -2,
                    'resId' => '',
                    'title' => '',
                    'identifier' => '',
                    'status' => '',
                    'attachment_type' => '',
                    'dest_contact_id' => '',
                    'dest_address_id' => '',
                    'error' => 'param collId empty',
                );
                return $resultArr;
            }

            $queryAttachments = "select * from res_attachments where "
                . "identifier = ? and coll_id = ? order by res_id";
            $stmt = $db->query(
                $queryAttachments, 
                array($identifier, $collId)
            );

            $line = $stmt->fetchObject();

            //var_dump($line);

            if ($line->res_id_master == '') {
                $resultArr = array(
                    'returnCode' => (int) -3,
                    'resId' => '',
                    'title' => '',
                    'identifier' => '',
                    'status' => '',
                    'attachment_type' => '',
                    'dest_contact_id' => '',
                    'dest_address_id' => '',
                    'error' => 'resource not found : ' 
                        . $identifier . ' ' . $collId,
                );
                return $resultArr;
            } else {
                $resultArr = array(
                    'returnCode' => (int) 0,
                    'resId' => $line->res_id_master,
                    'title' => $line->title,
                    'identifier' => $line->identifier,
                    'status' => $line->status,
                    'attachment_type' => $line->attachment_type,
                    'dest_contact_id' => $line->dest_contact_id,
                    'dest_address_id' => $line->dest_address_id,
                    'error' => '',
                );
                return $resultArr;
            }
            
            return $resultArr;
        } catch (Exception $e) {
            $resultArr = array(
                'returnCode' => (int) -1,
                'resId' => '',
                'title' => '',
                'identifier' => '',
                'status' => '',
                'attachment_type' => '',
                'dest_contact_id' => '',
                'dest_address_id' => '',
                'error' => 'unknown error : ' 
                    . $e->getMessage(),
            );
            return $resultArr;
        }
    }
}
