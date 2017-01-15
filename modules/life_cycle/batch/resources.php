<?php

/*
 *  Copyright 2008-2015 Maarch
 *
 *  This file is part of Maarch Framework.
 *
 *  Maarch Framework is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  Maarch Framework is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @brief API to interface with objects in the database
 * @file
 * @author  Laurent Giovannoni  <dev@maarch.org>
 * @date $date$
 * @version $Revision$
 * @ingroup life_cycle
 */

/**
 * Retrieve the path of source file to process
 * @param bigint $resId Id of the resource to process
 * @param string $docserverToPurge Id of the docsever to purge
 * @param string $dirToCompress path to the target dir to compress 
 * @return array $returnArray array of information to retrieve the source file
 *               docserverId, basePath, fileName, offsetDoc
 */
function getSourceResourcePath(
    $resId, 
    $docserverToPurge='', 
    $returnArray=false
) {
    $pathArray = array();
    if ($docserverToPurge <> '') {
        $query = "select res_id, docserver_id, path, filename, offset_doc "
               . "from " . $GLOBALS['adrTable'] . " where res_id = ?"  
               . " and docserver_id = ?";
        $param = array($resId, $docserverToPurge);
    } else {
        $query = "select res_id, docserver_id, path, filename, offset_doc "
               . "from " . $GLOBALS['adrTable'] . " where res_id = ?" 
               . " order by adr_priority";
        $param = array($resId);
    }
    $stmt = Bt_doQuery($GLOBALS['db'], $query, $param);
    if ($stmt->rowCount() == 0) {
        if ($docserverToPurge <> '') {
            $query = "select res_id, docserver_id, path, filename, offset_doc "
                   . "from " . $GLOBALS['table'] . " where res_id = ?" 
                   . " and docserver_id = ?";
            $param = array($resId, $docserverToPurge);
        } else {
            $query = "select res_id, docserver_id, path, filename, offset_doc"
                   . " from " . $GLOBALS['table'] . " where res_id = ?";
            $param = array($resId);
        }
        $stmt = Bt_doQuery($GLOBALS['db'], $query, $param);
    }
    $resRecordset = $stmt->fetchObject();
    $resPath = '';
    $resFilename = '';
    $resDocserverId = '';
    $resDocserverTypeId = '';
    $resFingerprintMode = '';
    if (isset($resRecordset->path)) {
        $resPath = $resRecordset->path;
    }
    if (isset($resRecordset->filename)) {
        $resFilename = $resRecordset->filename;
    }
    if (isset($resRecordset->docserver_id)) {
        $resDocserverId = $resRecordset->docserver_id;
    }
    if (isset($resRecordset->offset_doc) 
        && $resRecordset->offset_doc <> ''
        && $resRecordset->offset_doc <> ' '
    ) {
        //purge a container
        if (
            $docserverToPurge <> '' 
            && $GLOBALS['docservers'][$GLOBALS['currentStep']]
                ['is_container'] == 'Y'
        ) {
            $sourceFilePath = $resRecordset->path . $resRecordset->filename;
        } else {
            $sourceFilePath = $resRecordset->path . $resRecordset->filename 
                            . DIRECTORY_SEPARATOR . $resRecordset->offset_doc;
        }
        array_push(
            $pathArray, 
            array(
                'docserverId' => $docserverToPurge, 
                'basePath' => $resRecordset->path, 
                'fileName' => $resRecordset->filename, 
                'offsetDoc' => $resRecordset->offset_doc,
            )
        );
    } else {
        $sourceFilePath = $resPath . $resFilename;
        array_push(
            $pathArray, 
            array(
                'docserverId' => $docserverToPurge, 
                'basePath' => $resPath, 
                'fileName' => $resFilename,
            )
        );
    }
    $query = "select path_template, docserver_type_id from " 
           . _DOCSERVERS_TABLE_NAME . " where docserver_id = ?";
    $stmt = Bt_doQuery($GLOBALS['db'], $query, array($resDocserverId));
    $docserverRecordset = $stmt->fetchObject();
    if (isset($docserverRecordset->docserver_type_id)) {
        $resDocserverTypeId = $docserverRecordset->docserver_type_id;
    }
    $resPathTemplate = '';
    if (isset($docserverRecordset->path_template)) {
        $resPathTemplate = $docserverRecordset->path_template;
    }
    $GLOBALS['docserverSourcePath'] = $resPathTemplate;
    $GLOBALS['logger']->write(
        'Docserver source path:' . $GLOBALS['docserverSourcePath'], 'DEBUG'
    );
    $query = "select fingerprint_mode from " 
           . _DOCSERVER_TYPES_TABLE_NAME . " where docserver_type_id = ?";
    $stmt = Bt_doQuery($GLOBALS['db'], $query, array($resDocserverTypeId));
    $docserverTypeRecordset = $stmt->fetchObject();
    if (isset($docserverTypeRecordset->fingerprint_mode)) {
        $resFingerprintMode = $docserverTypeRecordset->fingerprint_mode;
    }
    $GLOBALS['docserverSourceFingerprint'] = $resFingerprintMode;
    $GLOBALS['logger']->write(
        'Docserver source fingerprint:' 
        . $GLOBALS['docserverSourceFingerprint'], 'DEBUG'
    );
    $sourceFilePath = $GLOBALS['docserverSourcePath'] . $sourceFilePath;
    $sourceFilePath = str_replace('#', DIRECTORY_SEPARATOR, $sourceFilePath);
    if ($returnArray) {
        return $pathArray;
    } else {
        return $sourceFilePath;
    }
}

/**
 * Retrieve the break key value of the resource
 * @param bigint $resId Id of the resource to process
 * @param string $breakKey break key
 * @return undefined returns the value of the break key or false if error 
 */
function getBreakKeyValue($resId, $breakKey)
{
    if ($resId <> '' && $breakKey <> '') {
        $query = "select " . $breakKey . " "
               . "from " . $GLOBALS['view'] . " where res_id = ?";
    } else {
        return false;
    }
    $stmt = Bt_doQuery($GLOBALS['db'], $query, array($resId));
    if ($stmt->rowCount() == 0) {
        return false;
    } else {
        $resBreakKey = $stmt->fetchObject();
        $breakKeyValue = $resBreakKey->{$breakKey};
    }
    return $breakKeyValue;
}

/**
 * Updating the database with the location information of the document on the
 * new docserver
 * @param bigint $currentRecordInStack Id of the resource to process
 * @param array  $resInContainer current container, array of res_id
 * @param string $path location of the resource on the docserver
 * @param string $fileName file name of the resource on the docserver
 * @param string $offsetDoc offset in the container of the resource 
 *               on the docserver
 * @return nothing
 */
function updateDatabase(
    $currentRecordInStack, 
    $resInContainer, 
    $path, 
    $fileName, 
    $offsetDoc='' 
) {
    if ($GLOBALS['databasetype'] == 'POSTGRESQL') {
		$stmt = Bt_doQuery($GLOBALS['db'], 'START TRANSACTION');
	}
    if (is_array($resInContainer) && count($resInContainer) > 0) {
        for ($cptRes = 0;$cptRes < count($resInContainer);$cptRes++) {
            doUpdateDb(
                $resInContainer[$cptRes]['res_id'], 
                $path, $fileName, $resInContainer[$cptRes]['offset_doc'], 
                $resInContainer[$cptRes]['fingerprint']
            );
        }
    } else {
        if ($currentRecordInStack['res_id'] <> '') {
            doUpdateDb(
                $currentRecordInStack['res_id'], $path, $fileName, $offsetDoc, 
                $currentRecordInStack['fingerprint']
            );
        }
    }
    $stmt = Bt_doQuery($GLOBALS['db'], 'COMMIT');
}

/**
 * e-signature of the resource
 * @param bigint $resId Id of the resource to process
 * @return nothing
 */
function esign($resId)
{
    if (empty($GLOBALS['manageEsign']->error)) {
        $resultDataSignatureEx = array();
        $resultDataGetArchiveEx = array();
        $GLOBALS['manageEsign']->connectToDB();
        $fingerprint = $GLOBALS['manageEsign']->getFingerprintTosign(
            $resId, 
            $GLOBALS['table']
        );
        $fingerprintMode = $GLOBALS['manageEsign']->getFingerprintMode(
            $resId, 
            $GLOBALS['table']
        );
        //########################signatureEx########################
        //echo "--------------call the signature ws\r\n";
        $signatureEx = new signatureEx();
        $signatureExResponse = new signatureExResponse();
        $signatureEx->requestId = $resId;
        $signatureEx->transactionId = 
            $GLOBALS['manageEsign']->esignConfig['service']['D2S']['transaction_id'];
        $signatureEx->tag = 'Maarch life_cycle module';
        $signatureEx->dataToSign = new dataType();
        $dataStr = new dataString();
        $dataStr->_ = '<Manifest/>';
        $signatureEx->dataToSign->value = $dataStr;
        $signatureEx->dataToSign->binaryValue = 0;
        $signatureEx->signatureFormat = 'XADES';
        $signatureEx->signatureType = 'DETACHED';
        $HASH_B64 = base64_encode($fingerprint);
        $HASH_ALGO = $fingerprintMode;
        $signatureEx->signatureParameter = '<Parameters><Manifest><Reference><DigestValue>'
            . $HASH_B64 . '</DigestValue><DigestMethod>'
            . $HASH_ALGO . '</DigestMethod></Reference></Manifest></Parameters>';
        //echo "--------------process the return\r\n";
        //var_dump($signatureEx);exit;
        $signatureExResponse = $GLOBALS['D2S']->signatureEx($signatureEx);
        //var_dump($signatureExResponse);exit;
        if ($signatureExResponse->signatureExResult->opStatus <> 0) {
            Bt_exitBatch(
                30,
                'problem with esign of resource :' . $resId
                    . ' Dictao return code:'
                    . $signatureExResponse->signatureExResult->opStatus
            );
        } else {
            //opStatus
            $opstatus = $signatureExResponse->signatureExResult->opStatus;
            //D2SStatus
            $D2SStatus = $signatureExResponse->signatureExResult->D2SStatus;
            //theSignature
            $theSignature = $signatureExResponse->signatureExResult->D2SSignature->value->_;
            $resultDataSignatureEx['esign_content'] = $theSignature;
            //D2SArchiveId
            $D2SArchiveId = $signatureExResponse->signatureExResult->D2SArchiveId;
            $resultDataSignatureEx['esign_proof_id'] = $D2SArchiveId;
            //requestId
            $requestId = $signatureExResponse->signatureExResult->requestId;
            //echo "--------------store the esign in DB\r\n";
            $GLOBALS['manageEsign']->putInfoInDB(
                $resId, 
                $GLOBALS['table'], 
                $resultDataSignatureEx, 
                $GLOBALS['databasetype']
            );
            //########################getArchiveEx########################
            //echo "--------------call the proof ws\r\n";
            $getArchiveEx = new getArchiveEx();
            $getArchiveExResponse = new getArchiveExResponse();
            $getArchiveEx->requestId = $resId;
            $getArchiveEx->archiveId = $D2SArchiveId;
            //echo "--------------process the return\r\n";
            $getArchiveExResponse = $GLOBALS['D2S']->getArchiveEx($getArchiveEx);
            if ($getArchiveExResponse->getArchiveExResult->opStatus <> 0) {
                Bt_exitBatch(
                    30,
                    'problem with esign proof of resource :' . $resId
                        . ' Dictao return code:'
                        . $signatureExResponse->getArchiveExResult->opStatus
                );
            } else {
                //opStatus
                $opstatus = $getArchiveExResponse->getArchiveExResult->opStatus;
                //requestId
                $requestId = $getArchiveExResponse->getArchiveExResult->requestId;
                //D2SProof
                $D2SProof = $getArchiveExResponse->getArchiveExResult->D2SProof;
                $resultDataGetArchiveEx['esign_proof_content'] = $D2SProof;
                //echo "--------------store the proof in DB\r\n";
                $GLOBALS['manageEsign']->putInfoInDB(
                    $resId, 
                    $GLOBALS['table'], 
                    $resultDataGetArchiveEx, 
                    $GLOBALS['databasetype']
                );
            }
        }
    } else {
        Bt_exitBatch(
            30,
            'esign WS not available : ' . $GLOBALS['manageEsign']->error . $resId
        );
    }
}

/**
 * Do the update with the location information of the document on the
 * new docserver
 * @param bigint $resId Id of the resource to process
 * @param string $path location of the resource on the docserver
 * @param string $fileName file name of the resource on the docserver
 * @param string $offsetDoc offset in the container of the resource 
 *               on the docserver
 * @param string $fingerprint fingerprint of the resource on the docserver
 * @return nothing
 */
function doUpdateDb($resId, $path, $fileName, $offsetDoc, $fingerprint) 
{
    $query = "update " . _LC_STACK_TABLE_NAME . " set status = 'P' where"
           . " policy_id = ? and cycle_id = ? "
           . " and cycle_step_id = ? and coll_id = ?"
           . " and res_id = ?";
    $stmt = Bt_doQuery(
        $GLOBALS['db'], 
        $query,
        array(
            $GLOBALS['policy'],
            $GLOBALS['cycle'],
            $GLOBALS['currentStep'],
            $GLOBALS['collection'],
            $resId
        )
    );
    //ADD CYCLE_DATE
    $query = "update " . $GLOBALS['table'] . " set cycle_id = ?"
           . ", is_multi_docservers = 'Y', cycle_date = " 
           . $GLOBALS['db']->current_datetime() . " where"
           . " res_id = ?";
    $stmt = Bt_doQuery(
        $GLOBALS['db'], 
        $query, 
        array(
            $GLOBALS['cycle'],
            $resId
        )
    );
    $query = "select * from " . $GLOBALS['adrTable'] 
        . " where res_id = ? order by adr_priority";
    $stmt = Bt_doQuery($GLOBALS['db'], $query, array($resId));
    if ($stmt->rowCount() == 0) {
        $query = "select docserver_id, path, filename, offset_doc, fingerprint"
               . " from " . $GLOBALS['table'] . " where res_id = ?";
        $stmt = Bt_doQuery($GLOBALS['db'], $query, array($resId));
        $recordset = $stmt->fetchObject();
        $resDocserverId = $recordset->docserver_id;
        $resPath = $recordset->path;
        $resFilename = $recordset->filename;
        $resOffsetDoc = $recordset->offset_doc;
        $fingerprintInit = $recordset->fingerprint;
        $query = "select adr_priority_number from " . _DOCSERVERS_TABLE_NAME 
               . " where docserver_id = ?";
        $stmt = Bt_doQuery($GLOBALS['db'], $query, array($resDocserverId));
        $recordset = $stmt->fetchObject();
        $query = "insert into " . $GLOBALS['adrTable'] . " (res_id, "
               . "docserver_id, path, filename, offset_doc, fingerprint, "
               . "adr_priority) values (?, ?, ?, ?, ?, ?, ?)";
        $stmt = Bt_doQuery(
            $GLOBALS['db'], 
            $query, 
            array(
                $resId,
                $resDocserverId,
                $resPath,
                $resFilename,
                $resOffsetDoc,
                $fingerprintInit,
                $recordset->adr_priority_number
            )
        );
    }
    $query = "insert into " . $GLOBALS['adrTable'] . " (res_id, docserver_id, "
           . "path, filename, offset_doc, fingerprint, adr_priority) values (" 
           . "?, ?, ?, ?, ?, ?, ?)";
    $stmt = Bt_doQuery(
        $GLOBALS['db'], 
        $query, 
        array(
            $resId,
            $GLOBALS['docservers'][$GLOBALS['currentStep']]['docserver']['docserver_id'],
            $path,
            $fileName,
            $offsetDoc,
            $fingerprint,
            $GLOBALS['docservers'][$GLOBALS['currentStep']]['docserver']['adr_priority_number']
        )
    );
    $query = "insert into " . HISTORY_TABLE . " (table_name, record_id, "
           . "event_type, user_id, event_date, info, id_module) values (" 
           . "?, ?, 'ADD', 'LC_BOT', " 
           . $GLOBALS['db']->current_datetime() 
           . ", ?, 'life_cycle')";
    $stmt = Bt_doQuery(
        $GLOBALS['db'], 
        $query, 
        array(
            $GLOBALS['table'],
            $resId,
            "process stack, policy:" 
                . $GLOBALS['policy'] . ", cycle:" . $GLOBALS['cycle'] 
                . ", cycle step:" . $GLOBALS['currentStep'] . ", collection:" 
                . $GLOBALS['collection']
        )
    );
}

/**
 * Do the minimal update for a NONE operation
 * @param bigint $resId Id of the resource to process
 * @return nothing
 */
function doMinimalUpdate($resId)
{
    $query = "update " . _LC_STACK_TABLE_NAME . " set status = 'P' where"
           . " policy_id = ? and cycle_id = ?" 
           . " and cycle_step_id = ? and coll_id = ? and res_id = ?";
    $stmt = Bt_doQuery(
        $GLOBALS['db'], 
        $query,
        array(
            $GLOBALS['policy'],
            $GLOBALS['cycle'],
            $GLOBALS['currentStep'],
            $GLOBALS['collection'],
            $resId
        )
    );
    $query = "update " . $GLOBALS['table'] . " set cycle_id = ?" 
           . ", is_multi_docservers = 'Y', cycle_date = " 
           . $GLOBALS['db']->current_datetime() . " where "
           . " res_id = ?";
    $stmt = Bt_doQuery(
        $GLOBALS['db'], 
        $query,
        array(
            $GLOBALS['cycle'],
            $resId
        )
    );
    $query = "insert into " . HISTORY_TABLE . " (table_name, record_id, "
           . "event_type, user_id, event_date, info, id_module) values (" 
           . "?, ?, 'ADD', 'LC_BOT', " 
           . $GLOBALS['db']->current_datetime() 
           . ", ?, 'life_cycle')";
    $stmt = Bt_doQuery(
        $GLOBALS['db'], 
        $query, 
        array(
            $GLOBALS['table'],
            $resId,
            "process stack, policy:" 
                . $GLOBALS['policy'] . ", cycle:" . $GLOBALS['cycle'] 
                . ", cycle step:" . $GLOBALS['currentStep'] . ", collection:" 
                . $GLOBALS['collection']
        )
    );
}

/**
 * Delete the location information of the document to purge
 * @param bigint $resId Id of the resource to process
 * @param string $dsToUpdate id of the docserver to purge
 * @return nothing
 */
function deleteAdrx($resId, $dsToUpdate) 
{
    if ($GLOBALS['databasetype'] == 'POSTGRESQL') {
		$stmt = Bt_doQuery($GLOBALS['db'], 'START TRANSACTION');
	}
    $query = "update " . _LC_STACK_TABLE_NAME . " set status = 'P' where"
           . " policy_id = ? and cycle_id = ?" 
           . " and cycle_step_id = ? and coll_id = ? and res_id = ?";
    $stmt = Bt_doQuery(
        $GLOBALS['db'], 
        $query,
        array(
            $GLOBALS['policy'],
            $GLOBALS['cycle'],
            $GLOBALS['currentStep'],
            $GLOBALS['collection'],
            $resId
        )
    );
    $query = "update " . $GLOBALS['table'] . " set cycle_id = ?" 
           . ", cycle_date = " 
           . $GLOBALS['db']->current_datetime() . " where "
           . " res_id = ?";
    $stmt = Bt_doQuery(
        $GLOBALS['db'], 
        $query,
        array(
            $GLOBALS['cycle'],
            $resId
        )
    );
    //$docserverSizeToUpdate = 0;
    for ($cptDs = 0;$cptDs < count($dsToUpdate);$cptDs++) {
        $query = "delete from " . $GLOBALS['adrTable'] 
            . " where res_id = ? and docserver_id = ?";
        $stmt = Bt_doQuery(
            $GLOBALS['db'], 
            $query, 
            array(
                $resId,
                $dsToUpdate[$cptDs]['docserverId']
            )
        );
    }
    $query = "insert into " . HISTORY_TABLE . " (table_name, record_id, "
           . "event_type, user_id, event_date, info, id_module) values (" 
           . "?, ?, 'ADD', 'LC_BOT', " 
           . $GLOBALS['db']->current_datetime() 
           . ", ?, 'life_cycle')";
    $stmt = Bt_doQuery(
        $GLOBALS['db'], 
        $query, 
        array(
            $GLOBALS['table'],
            $resId,
            "process stack, policy:" 
                . $GLOBALS['policy'] . ", cycle:" . $GLOBALS['cycle'] 
                . ", cycle step:" . $GLOBALS['currentStep'] . ", collection:" 
                . $GLOBALS['collection']
        )
    );
    $stmt = Bt_doQuery($GLOBALS['db'], 'COMMIT');
}

/**
 * Updating the database, but not as deletion is the latest location 
 * for the resource processed
 * @param bigint $resId Id of the resource to process
 * @return nothing
 */
function updateOnNonePurge($resId) 
{
    if ($GLOBALS['databasetype'] == 'POSTGRESQL') {
		$stmt = Bt_doQuery($GLOBALS['db'], 'START TRANSACTION');
	}
    $query = "update " . _LC_STACK_TABLE_NAME . " set status = 'P' where"
           . " policy_id = ? and cycle_id = ?" 
           . " and cycle_step_id = ? and coll_id = ? and res_id = ?";
    $stmt = Bt_doQuery(
        $GLOBALS['db'], 
        $query,
        array(
            $GLOBALS['policy'],
            $GLOBALS['cycle'],
            $GLOBALS['currentStep'],
            $GLOBALS['collection'],
            $resId
        )
    );   
    $query = "update " . $GLOBALS['table'] . " set cycle_id = ?" 
           . ", cycle_date = " 
           . $GLOBALS['db']->current_datetime() . " where "
           . " res_id = ?";
    $stmt = Bt_doQuery(
        $GLOBALS['db'], 
        $query,
        array(
            $GLOBALS['cycle'],
            $resId
        )
    );
    $query = "insert into " . HISTORY_TABLE . " (table_name, record_id, "
           . "event_type, user_id, event_date, info, id_module) values (" 
           . "?, ?, 'ADD', 'LC_BOT', " 
           . $GLOBALS['db']->current_datetime() 
           . ", ?, 'life_cycle')";
    $stmt = Bt_doQuery(
        $GLOBALS['db'], 
        $query, 
        array(
            $GLOBALS['table'],
            $resId,
            "process stack, policy:" 
                . $GLOBALS['policy'] . ", cycle:" . $GLOBALS['cycle'] 
                . ", cycle step:" . $GLOBALS['currentStep'] . ", collection:" 
                . $GLOBALS['collection'] . ", No purge for the resource " . $resId 
                . " because this is the last adr available"
        )
    );
    $stmt = Bt_doQuery($GLOBALS['db'], 'COMMIT');
}
