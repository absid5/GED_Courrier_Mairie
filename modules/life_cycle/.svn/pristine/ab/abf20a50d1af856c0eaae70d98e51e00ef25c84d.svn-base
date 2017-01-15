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
 * @brief created the Archival Information Package (AIP) which contains :
 *      Content Information (CI), Preservation Description Information (PDI), 
 *      history (PDIHistory) and Packaging Information (PI)
 * OAIS features
 * @file
 * @author  Laurent Giovannoni  <dev@maarch.org>
 * @date $date$
 * @version $Revision$
 * @ingroup life_cycle
 */
 
/**
 * Created the Archival Information Package (AIP) which contains :
 *      Content Information (CI), Preservation Description Information (PDI), 
 *      history (PDIHistory) and Packaging Information (PI)
 * @param array $resInContainer array of resources in a container
 *              res_id, source_path, fingerprint
 * @return array $resInContainer array of resources in a container
 *              res_id, source_path, fingerprint, offset_doc
 */
function createAip($resInContainer) 
{
    if ($GLOBALS['func']->isDirNotEmpty($GLOBALS['tmpDirectory'])) {
        Bt_exitBatch(
            19, 'tmp dir not empty:' . $GLOBALS['tmpDirectory']
        );
    }
    $arrayOfFileToCompress = array();
    $tmpDir = $GLOBALS['tmpDirectory'] . DIRECTORY_SEPARATOR . mt_rand();
    mkdir($tmpDir);
    $newSourceFilePath = $GLOBALS['tmpDirectory'] . DIRECTORY_SEPARATOR 
                       . mt_rand();
    for ($cptRes = 0;$cptRes < count($resInContainer);$cptRes++) {
        $resInContainer[$cptRes]['offset_doc'] =
            str_pad($cptRes, 4, '0', STR_PAD_LEFT) . '.' 
            . strtolower(
                $GLOBALS['func']->extractFileExt(
                    $resInContainer[$cptRes]['source_path']
                )
            );
        $cp = copy(
            $resInContainer[$cptRes]['source_path'], 
            $tmpDir . DIRECTORY_SEPARATOR 
            . $resInContainer[$cptRes]['offset_doc']
        );
        Ds_setRights(
            $tmpDir . DIRECTORY_SEPARATOR 
            . $resInContainer[$cptRes]['offset_doc']
        );
        if ($cp == false) {
            $storeInfos = array('error' => _DOCSERVER_COPY_ERROR);
            return $storeInfos;
        }
        array_push(
            $arrayOfFileToCompress, 
            $tmpDir . DIRECTORY_SEPARATOR 
            . $resInContainer[$cptRes]['offset_doc']
        );
        /*$resInContainer[$cptRes]['offset_doc'] = 'CI.' 
            . strtolower(
                $GLOBALS['docservers'][$GLOBALS['currentStep']]
                ['compression_mode']
            ) 
            . '#' . $resInContainer[$cptRes]['offset_doc'];*/
    }
    //CI compression
    $newSourceFilePath = doCompression('CI', $arrayOfFileToCompress, $tmpDir);
    $result['resInContainer'] = $resInContainer;
    $result['newSourceFilePath'] = $newSourceFilePath;
    if ($GLOBALS['enableHistory']) {
        createPDIHistory($resInContainer);
    }
    if ($GLOBALS['enablePdi']) {
        createPDI($resInContainer);
    }
    $piArray = array();
    $piArray['CIFingerprint'] =
        Ds_doFingerprint(
            $result['newSourceFilePath'], 
            $GLOBALS['docservers'][$GLOBALS['currentStep']]['fingerprint_mode']
        );
    $piArray['fingerprintMode'] =
        $GLOBALS['docservers'][$GLOBALS['currentStep']]['fingerprint_mode'];
    $piArray['aiuCount'] = count($resInContainer);
    $piArray['ciName'] = 'CI.' 
        . strtolower(
            $GLOBALS['docservers'][$GLOBALS['currentStep']]['compression_mode']
        );
    $piArray['compressionModeCI'] =
        $GLOBALS['docservers'][$GLOBALS['currentStep']]['compression_mode'];
    if ($GLOBALS['enablePdi']) {
        $piArray['pdiName'] = 'PDI.' 
            . strtolower(
                $GLOBALS['docservers'][$GLOBALS['currentStep']]['compression_mode']
            );
    }
    if ($GLOBALS['enableHistory']) {
        $piArray['pdiHistoryName'] = 'PDI_HISTORY.' 
            . strtolower(
                $GLOBALS['docservers'][$GLOBALS['currentStep']]['compression_mode']
            );
    }
    $piArray['compressionModeHistory'] =
        $GLOBALS['docservers'][$GLOBALS['currentStep']]['compression_mode'];
    //PDI compression
    if ($GLOBALS['enablePdi']) {
        $pdiName = $GLOBALS['tmpDirectory'] . DIRECTORY_SEPARATOR . 'pdi.xml';
        $pdiName = doCompression('PDI', $pdiName);
        $piArray['PDIFingerprint'] = Ds_doFingerprint(
            $pdiName, 
            $GLOBALS['docservers'][$GLOBALS['currentStep']]['fingerprint_mode']
        );
    }
    //PDI_HISTORY compression
    if ($GLOBALS['enableHistory']) {
        $pdiHistoryName = $GLOBALS['tmpDirectory'] . DIRECTORY_SEPARATOR .
             'pdi_history.xml';
        $pdiHistoryName = doCompression('PDI_HISTORY', $pdiHistoryName);
        $piArray['PDIHISTORYFingerprint'] =
            Ds_doFingerprint(
                $pdiHistoryName, 
                $GLOBALS['docservers'][$GLOBALS['currentStep']]['fingerprint_mode']
            );
    }
    
    createPackagingInformation($piArray);
    
    //AIP compression
    //$aipName = $GLOBALS['tmpDirectory'] . DIRECTORY_SEPARATOR . mt_rand();
    $piName = $GLOBALS['tmpDirectory'] . DIRECTORY_SEPARATOR 
        . 'packaging_information.xml';
    $arrayOfFileToCompress = array();
    array_push($arrayOfFileToCompress, $newSourceFilePath);
    if ($GLOBALS['enableHistory']) {
        array_push($arrayOfFileToCompress, $pdiHistoryName);
    }
    if ($GLOBALS['enablePdi']) {
        array_push($arrayOfFileToCompress, $pdiName);
    }
    array_push($arrayOfFileToCompress, $piName);
    $newSourceFilePath = doCompression('AIP', $arrayOfFileToCompress);
    rename(
        $newSourceFilePath, 
        str_replace(
            strtolower(
                $GLOBALS['docservers'][$GLOBALS['currentStep']]
                ['compression_mode']
            ), 'aip', $newSourceFilePath
        )
    );
    Ds_setRights(
        str_replace(
            strtolower(
                $GLOBALS['docservers'][$GLOBALS['currentStep']]
                ['compression_mode']
            ), 'aip', $newSourceFilePath
        )
    );
    $result = array();
    $result['resInContainer'] = $resInContainer;
    $result['newSourceFilePath'] = str_replace(
        strtolower(
            $GLOBALS['docservers'][$GLOBALS['currentStep']]['compression_mode']
        ), 'aip', $newSourceFilePath
    );
    return $result;
}

/**
 * Created the history of AIP (PDIHistory)
 * Created an xml file in the temporary directory of the batch
 * @param array $resInContainer array of resources in a container
 *              res_id, source_path, fingerprint
 * @return nothing
 */
function createPDIHistory($resInContainer) 
{
    $tmpXML = $GLOBALS['tmpDirectory'] . DIRECTORY_SEPARATOR 
        . 'pdi_history.xml';
    $docXML = new DomDocument('1.0', 'utf-8');
    $docXML->preserveWhiteSpace = true;
    $docXML->formatOutput = true;
    //root
    $root = $docXML->createElement('ROOT');
    $docXML->appendChild($root);
    $commentString = _HISTORY_COMMENT_ROOT;
    $commentNodeRoot = $docXML->createComment($commentString);
    $root->appendChild($commentNodeRoot);
    for ($cptRes = 0;$cptRes < count($resInContainer);$cptRes++) {
        //a record
        $pdiHistory = $docXML->createElement('PDI_HISTORY');
        $pdiHistory->setAttributeNode(
            new DOMAttr('AIU', $resInContainer[$cptRes]['offset_doc'])
        );
        $pdiHistory->setAttributeNode(
            new DOMAttr('RES_ID', $resInContainer[$cptRes]['res_id'])
        );
        $root->appendChild($pdiHistory);
        $query = "select * from history where record_id = ? "
               . " and (table_name = ? or table_name = ? or table_name = ?) "
               . " order by event_date";
        $stmt = Bt_doQuery(
            $GLOBALS['db3'], 
            $query,
            array(
                $resInContainer[$cptRes]['res_id'],
                $GLOBALS['table'],
                $GLOBALS['adrTable'],
                $GLOBALS['view'],
            )
        );
        while ($historyRecordset = $stmt->fetchObject()) {
            //an event
            $event = $docXML->createElement('EVENT');
            $pdiHistory->appendChild($event);
            $eventType = $docXML->createElement(
                'EVENT_TYPE', $historyRecordset->event_type
            );
            $event->appendChild($eventType);
            $eventType->setAttributeNode(new DOMAttr('SOURCE', 'HIST'));
            $userId = $docXML->createElement(
                'USER_ID', $historyRecordset->user_id
            );
            $event->appendChild($userId);
            $userId->setAttributeNode(new DOMAttr('SOURCE', 'HIST'));
            $eventDate = $docXML->createElement(
                'EVENT_DATE', $historyRecordset->event_date
            );
            $event->appendChild($eventDate);
            $eventDate->setAttributeNode(new DOMAttr('SOURCE', 'HIST'));
            $idModule = $docXML->createElement(
                'ID_MODULE', $historyRecordset->id_module
            );
            $event->appendChild($idModule);
            $idModule->setAttributeNode(new DOMAttr('SOURCE', 'HIST'));
            $info = $docXML->createElement(
                'INFO', $GLOBALS['func']->wash_html(
                    $historyRecordset->info, 'NO_ACCENT'
                )
            );
            $event->appendChild($info);
            $info->setAttributeNode(new DOMAttr('SOURCE', 'HIST'));
        }
    }
    //save the xml
    $docXML->save($tmpXML);
}

/**
 * Created the Packaging Information (PI)
 * Created an xml file in the temporary directory of the batch
 * @param array $piArray array AIP content and usage
 *              CIFingerprint, fingerprintMode, aiuCount, ciName
 *              compressionModeCI, pdiName, PDIFingerprint, pdiHistoryName,
 *              PDIHISTORYFingerprint, compressionModeHistory, fingerprintMode
 * @return nothing
 */
function createPackagingInformation($piArray) 
{
    $tmpXML = $GLOBALS['tmpDirectory'] . DIRECTORY_SEPARATOR 
            . 'packaging_information.xml';
    $docXML = new DomDocument('1.0', 'utf-8');
    $docXML->preserveWhiteSpace = true;
    $docXML->formatOutput = true;
    //root
    $root = $docXML->createElement('ROOT');
    $docXML->appendChild($root);
    $commentString = _PI_COMMENT_ROOT;
    $commentNodeRoot = $docXML->createComment($commentString);
    $root->appendChild($commentNodeRoot);
    //general
    $general = $docXML->createElement('GENERAL');
    $root->appendChild($general);
    $fingerprint = $docXML->createElement(
        'FINGERPRINT', $piArray['CIFingerprint']
    );
    $general->appendChild($fingerprint);
    $commentString = _PI_COMMENT_FINGERPRINT;
    $commentNodeFingerprint = $docXML->createComment($commentString);
    $fingerprint->appendChild($commentNodeFingerprint);
    $fingerprintMode = $docXML->createElement(
        'FINGERPRINT_MODE', $piArray['fingerprintMode']
    );
    $general->appendChild($fingerprintMode);
    $aiuCount = $docXML->createElement('AIU_COUNT', $piArray['aiuCount']);
    $general->appendChild($aiuCount);
    $commentString = _PI_COMMENT_AIU;
    $commentNodeAiuCount = $docXML->createComment($commentString);
    $aiuCount->appendChild($commentNodeAiuCount);
    //content
    $content = $docXML->createElement('CONTENT');
    $root->appendChild($content);
    $commentString = _PI_COMMENT_CONTENT;
    $commentNodeContent = $docXML->createComment($commentString);
    $content->appendChild($commentNodeContent);
    $contentFile = $docXML->createElement('CONTENT_FILE', $piArray['ciName']);
    $content->appendChild($contentFile);
    $compressionMode = $docXML->createElement(
        'COMPRESSION_MODE', $piArray['compressionModeCI']
    );
    $content->appendChild($compressionMode);
    //pdi
    $pdi = $docXML->createElement('PDI');
    $root->appendChild($pdi);
    $commentString = _PI_COMMENT_PDI;
    $commentNodePdi = $docXML->createComment($commentString);
    $pdi->appendChild($commentNodePdi);
    $pdiFile = $docXML->createElement('PDI_FILE', $piArray['pdiName']);
    $pdi->appendChild($pdiFile);
    $pdiFingerprint = $docXML->createElement(
        'PDI_FINGERPRINT', $piArray['PDIFingerprint']
    );
    $pdi->appendChild($pdiFingerprint);
    $historyFile = $docXML->createElement(
        'HISTORY_FILE', $piArray['pdiHistoryName']
    );
    $pdi->appendChild($historyFile);
    $pdiHistoryFingerprint = $docXML->createElement(
        'HISTORY_FINGERPRINT', $piArray['PDIHISTORYFingerprint']
    );
    $pdi->appendChild($pdiHistoryFingerprint);
    $compressionModeHistory = $docXML->createElement(
        'COMPRESSION_MODE', $piArray['compressionModeHistory']
    );
    $pdi->appendChild($compressionModeHistory);
    $fingerprintModePDI = $docXML->createElement(
        'FINGERPRINT_MODE', $piArray['fingerprintMode']
    );
    $pdi->appendChild($fingerprintModePDI);
    //save the xml
    $docXML->save($tmpXML);
}
