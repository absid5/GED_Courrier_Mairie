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
 * @brief API to manage the transfer, compression and decompression of resources
 * @file
 * @author  Laurent Giovannoni  <dev@maarch.org>
 * @date $date$
 * @version $Revision$
 * @ingroup life_cycle
 */

/**
 * Do the 7z compression on a file, list of files or a directory
 * @param string $targeFile path to the target file to compress 
 * @param array $arrayOfFileToCompress list of file path to compress
 * @param string $dirToCompress path to the target dir to compress 
 * @return string path of the compressed resource
 */
function doCompression($targeFile, $arrayOfFileToCompress, $dirToCompress='') 
{
    $listOfFileToCompress = '';
    if ($dirToCompress <> '') {
        //$listOfFileToCompress = " -r " . $dirToCompress;
        $listOfFileToCompress = $dirToCompress . DIRECTORY_SEPARATOR . "*";
    } else {
        if (!is_array($arrayOfFileToCompress)) {
            $arr = array();
            $arr[0] = $arrayOfFileToCompress;
            $arrayOfFileToCompress = $arr;
        }
        $tmpCmd = "";
        for ($cpt = 0;$cpt < count($arrayOfFileToCompress);$cpt++) {
            $listOfFileToCompress .=
                " " . escapeshellarg($arrayOfFileToCompress[$cpt]);
        }
    }
    if (DIRECTORY_SEPARATOR == "/") {
        $command = "7z a -y -mx -t"
                 . strtolower(
                     $GLOBALS['docservers'][$GLOBALS['currentStep']]
                     ['compression_mode']
                 ) 
                 . " " . escapeshellarg(
                     $GLOBALS['tmpDirectory'] . $targeFile
                 )
                 . " " . $listOfFileToCompress;
    } else {
        $command = "\"" . str_replace(
            "\\", "\\\\", $GLOBALS['docserversFeatures']
            ['DOCSERVERS']['PATHTOCOMPRESSTOOL']
        )
        . "\" a -y -t" . strtolower(
            $GLOBALS['docservers'][$GLOBALS['currentStep']]['compression_mode']
        )
        . " " . escapeshellarg($GLOBALS['tmpDirectory'] . $targeFile) 
        . " " . $listOfFileToCompress;
    }
    //echo $command."\r\n";exit;
    $execError = '';
    exec($command, $tmpCmd, $execError);
    if ($execError > 0) {
        Bt_exitBatch(
            23, 'Pb with compression:' .$command . ' ' . print_r($tmpCmd)
        );
    }
    return $GLOBALS['tmpDirectory'] . $targeFile . "." 
        . strtolower(
            $GLOBALS['docservers'][$GLOBALS['currentStep']]['compression_mode']
        );
}

/**
 * Do the 7z decompression
 * @param string $path path to the target file to decompress 
 * @return nothing
 */
function extractAip($path) 
{
    $path = str_replace('#', DIRECTORY_SEPARATOR, $path);
    $fileNameOnTmp = $GLOBALS['tmpDirectory'] . rand();
    $cp = copy($path, $fileNameOnTmp);
    Ds_setRights($fileNameOnTmp);
    $control = array();
    $control = Ds_controlFingerprint(
        $path, $fileNameOnTmp, $GLOBALS['docserverSourceFingerprint']
    );
    if ($control['error'] <> "") {
        Bt_exitBatch(
            22, $control['error']
        );
    }
    if (DIRECTORY_SEPARATOR == "/") {
        $command = "7z x -y -o" . escapeshellarg($GLOBALS['tmpDirectory']) 
                 . " " . escapeshellarg($fileNameOnTmp);
    } else {
        $command = "\"" . str_replace(
            "\\", "\\\\", 
            $GLOBALS['docserversFeatures']['DOCSERVERS']['PATHTOCOMPRESSTOOL']
        )
        . "\" x -y -o" . escapeshellarg($GLOBALS['tmpDirectory']) . " " 
        . escapeshellarg($fileNameOnTmp);
    }
    $tmpCmd = '';
    $execError = '';
    exec($command, $tmpCmd, $execError);
    if ($execError > 0) {
        Bt_exitBatch(
            24, 'Pb with extract:' . $command . ' ' . print_r($tmpCmd)
        );
    }
    unlink($fileNameOnTmp);
    mkdir($GLOBALS['tmpDirectory'] . "CI", 0777);
    $fileNameOnTmp = $GLOBALS['tmpDirectory'] . "CI." 
        . strtolower(
            $GLOBALS['docservers'][$GLOBALS['currentStep']]['compression_mode']
        );
    if (DIRECTORY_SEPARATOR == "/") {
        $command = "7z x -y -o" 
                 . escapeshellarg($GLOBALS['tmpDirectory'] . "CI") . " " 
                 . escapeshellarg($fileNameOnTmp);
    } else {
        $command = "\"" . 
            str_replace(
                "\\", "\\\\", 
                $GLOBALS['docserversFeatures']['DOCSERVERS']
                ['PATHTOCOMPRESSTOOL']
            )
            . "\" x -y -o" . escapeshellarg($GLOBALS['tmpDirectory'] . "CI") 
            . " " . escapeshellarg($fileNameOnTmp);
    }
    $tmpCmd = "";
    exec($command, $tmpCmd, $execError);
    if ($execError > 0) {
        Bt_exitBatch(
            24, 'Pb with extract:' . $command . ' ' . print_r($tmpCmd)
        );
    }
}

/**
 * Check the integrity of the transfer
 * @param bigint $currentRecordInStack current record to control 
 * @param array $resInContainer current container to control, array of res_id
 * @param string $destinationDir path to the target dir to decompress, control
 * @param string $fileDestinationName path of the file to control
 * @return nothing
 */
function controlIntegrityOfTransfer(
    $currentRecordInStack, $resInContainer, $destinationDir, 
    $fileDestinationName
) {
    if (is_array($resInContainer) && count($resInContainer) > 0) {
        extractAip(
            $GLOBALS['docservers'][$GLOBALS['currentStep']]['docserver']
            ['path_template'] . $destinationDir . $fileDestinationName
        );
        for ($cptRes = 0;$cptRes < count($resInContainer);$cptRes++) {
            $control = array();
            $control = Ds_controlFingerprint(
                $resInContainer[$cptRes]['source_path'], 
                $GLOBALS['tmpDirectory'] . "CI" . DIRECTORY_SEPARATOR 
                . str_replace(
                    "CI." . strtolower(
                        $GLOBALS['docservers'][$GLOBALS['currentStep']]
                        ['compression_mode']
                    )
                    . "#", "", $resInContainer[$cptRes]['offset_doc']
                ), 
                $GLOBALS['docserverSourceFingerprint']
            );
            if ($control['error'] <> "") {
                Bt_exitBatch(
                    22, $control['error']
                );
            }
        }
    } else {
        //print_r($resInContainer);
        if ($currentRecordInStack['res_id'] <> '') {
            $sourceFilePath = getSourceResourcePath(
                $currentRecordInStack['res_id']
            );
            $control = array();
            $control = Ds_controlFingerprint(
                $sourceFilePath, 
                $GLOBALS['docservers'][$GLOBALS['currentStep']]['docserver']
                ['path_template'] . str_replace(
                    "#", DIRECTORY_SEPARATOR, $destinationDir 
                    . $fileDestinationName
                ),
                $GLOBALS['docserverSourceFingerprint']
            );
            if ($control['error'] <> "") {
                Bt_exitBatch(
                    22, $control['error']
                );
            }
        }
    }
}

/**
 * Check the integrity of the source file
 * @param bigint $currentRecordInStack current record to control 
 * @return nothing
 */
function controlIntegrityOfSource($currentRecordInStack) 
{
    $sourceFilePath = getSourceResourcePath($currentRecordInStack);
    $query = "select fingerprint from " . $GLOBALS['table'] 
           . " where res_id = ?";
    $stmt = Bt_doQuery(
        $GLOBALS['db'], 
        $query,
        array($currentRecordInStack . $GLOBALS['creationDateClause'])
    );
    $resRecordset = $stmt->fetchObject();
    if (Ds_doFingerprint(
        $sourceFilePath, $GLOBALS['docserverSourceFingerprint']
    ) <> $resRecordset->fingerprint
    ) {
        Bt_exitBatch(
            25, 'Pb with fingerprint of the source:' . $currentRecordInStack 
            . ' ' . $sourceFilePath
        );
    }
    Ds_washTmp($GLOBALS['tmpDirectory'], true);
}

/**
 * Set the new size of the docserver
 * @param string $docserverId id of the docserver
 * @param bigint $newSize new size of the docserver
 * @return nothing
 */
function setSize($docserverId, $newSize) 
{
    $query = "update " . _DOCSERVERS_TABLE_NAME 
        . " set actual_size_number=? where docserver_id=?";
    $stmt = Bt_doQuery(
        $GLOBALS['db'], 
        $query,
        array(
            $newSize,
            $docserverId
        )
    );
}
