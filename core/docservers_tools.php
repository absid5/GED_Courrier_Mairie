<?php

/*
*   Copyright 2008-2011 Maarch
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
*   along with Maarch Framework. If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief API to manage docservers
*
* @file
* @author Laurent Giovannoni
* @date $date$
* @version $Revision$
* @ingroup core
*/

//Loads the required class
try {
    require_once 'core/class/docservers.php';
    require_once 'core/class/docservers_controler.php';
    require_once 'core/core_tables.php';
} catch (Exception $e) {
    functions::xecho($e->getMessage()) . ' // ';
}

/**
 * copy doc in a docserver.
 * @param   string $sourceFilePath collection resource
 * @param   array $infoFileNameInTargetDocserver infos of the doc to store,
 *          contains : subdirectory path and new filename
 * @param   string $docserverSourceFingerprint
 * @return  array of docserver data for res_x else return error
 */
function Ds_copyOnDocserver(
    $sourceFilePath,
    $infoFileNameInTargetDocserver,
    $docserverSourceFingerprint='NONE'
) {
    error_reporting(0);
    $destinationDir = $infoFileNameInTargetDocserver['destinationDir'];
    $fileDestinationName =
        $infoFileNameInTargetDocserver['fileDestinationName'];
    $sourceFilePath = str_replace('\\\\', '\\', $sourceFilePath);
    if (file_exists($destinationDir . $fileDestinationName)) {
        $storeInfos = array('error' => _FILE_ALREADY_EXISTS);
        return $storeInfos;
    }
    if (!is_dir($destinationDir)) {
        mkdir($destinationDir, 0770, true);
        Ds_setRights($destinationDir);
    }
    $cp = copy($sourceFilePath, $destinationDir . $fileDestinationName);
    Ds_setRights($destinationDir . $fileDestinationName);
    if ($cp == false) {
        $storeInfos = array('error' => _DOCSERVER_COPY_ERROR);
        return $storeInfos;
    }
    $fingerprintControl = array();
    $fingerprintControl = Ds_controlFingerprint(
        $sourceFilePath,
        $destinationDir . $fileDestinationName,
        $docserverSourceFingerprint
    );
    if ($fingerprintControl['status'] == 'ko') {
        $storeInfos = array('error' => $fingerprintControl['error']);
        return $storeInfos;
    }

    /*$ofile = fopen($destinationDir.$fileDestinationName, 'r');
    if (Ds_isCompleteFile($ofile)) {
        fclose($ofile);
    } else {
        $storeInfos = array('error' => _COPY_OF_DOC_NOT_COMPLETE);
        return $storeInfos;
    }*/
    if (isset($GLOBALS['currentStep'])) {
        $destinationDir = str_replace(
            $GLOBALS['docservers'][$GLOBALS['currentStep']]['docserver']
            ['path_template'],
            '',
            $destinationDir
        );
    }
    $destinationDir = str_replace(
        DIRECTORY_SEPARATOR,
        '#',
        $destinationDir
    );
    $storeInfos = array(
        'destinationDir' => $destinationDir,
        'fileDestinationName' => $fileDestinationName,
        'fileSize' => filesize($sourceFilePath),
    );
    if (isset($GLOBALS['TmpDirectory']) && $GLOBALS['TmpDirectory'] <> '') {
        Ds_washTmp($GLOBALS['TmpDirectory'], true);
    }
    return $storeInfos;
}

/**
 * Compute the path in the docserver for a batch
 * @param $docServer docservers path
 * @return @return array Contains 2 items : subdirectory path and error
 */
function Ds_createPathOnDocServer($docServer)
{
    error_reporting(0);
    umask(0022);
    if (!is_dir($docServer . date('Y') . DIRECTORY_SEPARATOR)) {
        mkdir($docServer . date('Y') . DIRECTORY_SEPARATOR, 0770);
        Ds_setRights($docServer . date('Y') . DIRECTORY_SEPARATOR);
    }
    if (!is_dir(
        $docServer . date('Y') . DIRECTORY_SEPARATOR.date('m')
        . DIRECTORY_SEPARATOR
    )
    ) {
        mkdir(
            $docServer . date('Y') . DIRECTORY_SEPARATOR.date('m')
            . DIRECTORY_SEPARATOR,
            0770
        );
        Ds_setRights(
            $docServer . date('Y') . DIRECTORY_SEPARATOR.date('m')
            . DIRECTORY_SEPARATOR
        );
    }
    if (isset($GLOBALS['wb']) && $GLOBALS['wb'] <> '') {
        $path = $docServer . date('Y') . DIRECTORY_SEPARATOR.date('m')
              . DIRECTORY_SEPARATOR . 'BATCH' . DIRECTORY_SEPARATOR 
              . $GLOBALS['wb'] . DIRECTORY_SEPARATOR;
        if (!is_dir($path)) {
            mkdir($path, 0770, true);
            Ds_setRights($path);
        } else {
            return array(
                'destinationDir' => $path,
                'error' => 'Folder alreay exists, workbatch already exist:'
                . $path,
            );
        }
    } else {
        $path = $docServer . date('Y') . DIRECTORY_SEPARATOR.date('m')
              . DIRECTORY_SEPARATOR;
    }
    return array(
        'destinationDir' => $path,
        'error' => '',
    );
}

/**
 * Extract a file from an archive
 * @param   $fileInfos infos of the doc to store, contains :
 *          tmpDir : path to tmp directory
 *          path_to_file : path to the file in the docserver
 *          filename : name of the file
 *          offset_doc : offset of the doc in the container
 *          $fingerprintMode
 * @return  array with path of the extracted doc
 */
function Ds_extractArchive($fileInfos, $fingerprintMode)
{
    //var_dump($fileInfos);
    if (!isset($fileInfos['tmpDir']) || $fileInfos['tmpDir'] == '') {
        $tmp = $_SESSION['config']['tmppath'];
    } else {
        $tmp = $fileInfos['tmpDir'];
    }
    $fileNameOnTmp = $tmp . rand() . '_'
                   . md5_file($fileInfos['path_to_file'])
                   . '_' . $fileInfos['filename'];
    $cp = copy($fileInfos['path_to_file'], $fileNameOnTmp);
    Ds_setRights($fileNameOnTmp);
    if ($cp == false) {
        $result = array(
            'status' => 'ko',
            'path' => '',
            'mime_type' => '',
            'format' => '',
            'tmpArchive' => '',
            'fingerprint' => '',
            'error' => _TMP_COPY_ERROR,
        );
        return $result;
    } else {
        $execError = '';
        $tmpArchive = uniqid(rand());
        if (mkdir($tmp . $tmpArchive)) {
            //try to extract the offset if it's possible
            if (DIRECTORY_SEPARATOR == '/') {
                $command = '7z x -y -o'
                         . escapeshellarg($tmp . $tmpArchive) . ' '
                         . escapeshellarg($fileNameOnTmp) . ' '
                         . escapeshellarg($fileNameOnTmp);
            } else {
                $command = '"'
                    . str_replace(
                        '\\',
                        '\\\\',
                        $_SESSION['docserversFeatures']['DOCSERVERS']
                        ['PATHTOCOMPRESSTOOL']
                    )
                    . '" x -y -o' . escapeshellarg($tmp . $tmpArchive)
                    . ' ' . escapeshellarg($fileNameOnTmp) . ' '
                    . escapeshellarg($fileNameOnTmp);
            }
            $tmpCmd = '';
            exec($command, $tmpCmd, $execError);
            if ($execError > 0) {
                if (DIRECTORY_SEPARATOR == '/') {
                    //else try to extract only the first container
                    $command = '7z x -y -o'
                             . escapeshellarg($tmp . $tmpArchive) . ' '
                             . escapeshellarg($fileNameOnTmp);
                } else {
                    $command = '"'
                        . str_replace(
                            '\\',
                            '\\\\',
                            $_SESSION['docserversFeatures']['DOCSERVERS']
                            ['PATHTOCOMPRESSTOOL']
                        )
                        . '" x -y -o'
                        . escapeshellarg($tmp . $tmpArchive) . ' '
                        . escapeshellarg($fileNameOnTmp);
                }
                $tmpCmd = '';
                exec($command, $tmpCmd, $execError);
                if ($execError > 0) {
                    $result = array(
                        'status' => 'ko',
                        'path' => '',
                        'mime_type' => '',
                        'format' => '',
                        'tmpArchive' => '',
                        'fingerprint' => '',
                        'error' => _PB_WITH_EXTRACTION_OF_CONTAINER . '#'
                        . $execError,
                    );
                    return $result;
                }
            }
        } else {
            $result = array(
                'status' => 'ko',
                'path' => '',
                'mime_type' => '',
                'format' => '',
                'tmpArchive' => '',
                'fingerprint' => '',
                'error' => _PB_WITH_EXTRACTION_OF_CONTAINER . '#' . $tmp
                . $tmpArchive,
            );
            return $result;
        }
        $format = substr(
            $fileInfos['offset_doc'],
            strrpos($fileInfos['offset_doc'], '.') + 1
        );
        if (!file_exists(
            $tmp . $tmpArchive . DIRECTORY_SEPARATOR
            . $fileInfos['offset_doc']
        )
        ) {
            $classScan = dir($tmp . $tmpArchive);
            while (($fileScan = $classScan->read()) != false) {
                if ($fileScan == '.' || $fileScan == '..') {
                    continue;
                } else {
                    preg_match("'CI|.tar'", $fileScan, $out);
                    if (isset($out[0]) && count($out[0]) == 1) {
                        $execError = '';
                        $tmpArchiveBis = uniqid(rand());
                        if (mkdir(
                            $tmp . $tmpArchive . DIRECTORY_SEPARATOR
                            . $tmpArchiveBis
                        )
                        ) {
                            if (DIRECTORY_SEPARATOR == '/') {
                                $commandBis = '7z x -y -o'
                                            . escapeshellarg(
                                                $tmp . $tmpArchive
                                                . DIRECTORY_SEPARATOR
                                                . $tmpArchiveBis
                                            )
                                            . ' '
                                            . escapeshellarg(
                                                $tmp . $tmpArchive
                                                . DIRECTORY_SEPARATOR
                                                . $fileScan
                                            )
                                            . ' ' .$fileInfos['offset_doc'];
                            } else {
                                $commandBis = '"'
                                            . str_replace(
                                                '\\',
                                                '\\\\',
                                                $_SESSION
                                                ['docserversFeatures']
                                                ['DOCSERVERS']
                                                ['PATHTOCOMPRESSTOOL']
                                            )
                                            . '" x -y -o'
                                            . escapeshellarg(
                                                $tmp . $tmpArchive
                                                . DIRECTORY_SEPARATOR
                                                . $tmpArchiveBis
                                            )
                                            . ' '
                                            . escapeshellarg(
                                                $tmp . $tmpArchive
                                                . DIRECTORY_SEPARATOR
                                                . $fileScan
                                            )
                                            . ' ' .$fileInfos['offset_doc'];
                            }
                            $tmpCmd = '';
                            exec($commandBis, $tmpCmd, $execError);
                            if ($execError > 0) {
                                $result = array(
                                    'status' => 'ko',
                                    'path' => '',
                                    'mime_type' => '',
                                    'format' => '',
                                    'tmpArchive' => '',
                                    'fingerprint' => '',
                                    'error' =>
                                    _PB_WITH_EXTRACTION_OF_CONTAINER . '#'
                                    . $execError,
                                );
                            }
                        } else {
                            $result = array(
                                'status' => 'ko',
                                'path' => '',
                                'mime_type' => '',
                                'format' => '',
                                'tmpArchive' => '',
                                'fingerprint' => '',
                                'error' => _PB_WITH_EXTRACTION_OF_CONTAINER
                                . '#' . $tmp . $tmpArchive
                                . DIRECTORY_SEPARATOR . $tmpArchiveBis,
                            );
                            return $result;
                        }
                        $path = str_replace(
                            $fileScan,
                            '',
                            $tmp . $tmpArchive . DIRECTORY_SEPARATOR
                            . $tmpArchiveBis . DIRECTORY_SEPARATOR
                            . $fileInfos['offset_doc']
                        );
                        $path = str_replace(
                            '#',
                            DIRECTORY_SEPARATOR,
                            $path
                        );
                        $result = array(
                            'status' => 'ok',
                            'path' => $path,
                            'mime_type' => Ds_getMimeType($path),
                            'format' => $format,
                            'fingerprint' =>
                            Ds_doFingerprint($path, $fingerprintMode),
                            'tmpArchive' => $tmp . $tmpArchive,
                            'error' => '',
                        );
                        unlink(
                            $tmp . $tmpArchive . DIRECTORY_SEPARATOR
                            . $fileScan
                        );
                        break;
                    }
                }
            }
        } else {
            $result = array(
                'status' => 'ok',
                'path' => $tmp . $tmpArchive . DIRECTORY_SEPARATOR
                . $fileInfos['offset_doc'],
                'mime_type' =>
                Ds_getMimeType(
                    $tmp . $tmpArchive . DIRECTORY_SEPARATOR
                    . $fileInfos['offset_doc']
                )
                ,
                'format' => $format,
                'tmpArchive' => $tmp . $tmpArchive,
                'fingerprint' =>
                Ds_doFingerprint(
                    $tmp . $tmpArchive . DIRECTORY_SEPARATOR
                    . $fileInfos['offset_doc'],
                    $fingerprintMode
                )
                ,
                'error' => '',
            );
        }
        unlink($fileNameOnTmp);
        return $result;
    }
}
/**
 * Compute the fingerprint of a resource
 * @param   string $path path of the resource
 * @param   string $fingerprintMode (md5, sha512, ...)
 * @return  string the fingerprint
 */
function Ds_doFingerprint($path, $fingerprintMode)
{
    if ($fingerprintMode == 'NONE' || $fingerprintMode == '') {
        return '0';
    } else {
        return hash_file(strtolower($fingerprintMode), $path);
    }
}

 /**
 * Control fingerprint between two resources
 * @param   string $pathInit path of the resource 1
 * @param   string $pathTarget path of the resource 2
 * @param   string $fingerprintMode (md5, sha512, ...)
 * @return  array ok or ko with error
 */
function Ds_controlFingerprint(
    $pathInit,
    $pathTarget,
    $fingerprintMode='NONE'
) {
    $result = array();
    if (Ds_doFingerprint(
        $pathInit,
        $fingerprintMode
    ) <> Ds_doFingerprint($pathTarget, $fingerprintMode)
    ) {
        $result = array(
            'status' => 'ko',
            'error' => _PB_WITH_FINGERPRINT_OF_DOCUMENT . ' ' . $pathInit
            . ' '. _AND . ' ' . $pathTarget,
        );
    } else {
        $result = array(
            'status' => 'ok',
            'error' => '',
        );
    }
    return $result;
}

 /**
 * Set Rights on resources
 * @param   string $dest path of the resource
 * @return  nothing
 */
function Ds_setRights($dest)
{
    if (
        DIRECTORY_SEPARATOR == '/'
        && (isset($GLOBALS['apacheUserAndGroup'])
        && $GLOBALS['apacheUserAndGroup'] <> '')
    ) {
        exec('chown ' 
            . escapeshellarg($GLOBALS['apacheUserAndGroup']) . ' ' 
            . escapeshellarg($dest)
        );
    }
    umask(0022);
    chmod($dest, 0770);
}

/**
* get the mime type of a file with a path
* @param $filePath path of the file
* @return string of the mime type
*/
function Ds_getMimeType($filePath)
{
    //require_once 'MIME/Type.php';
    //return MIME_Type::autoDetect($filePath);
    return mime_content_type($filePath);
}

/**
* get the mime type of a file with a buffer
* @param $fileBuffer buffer of the file
* @return string of the mime type
*/
function Ds_getMimeTypeWithBuffer($fileBuffer)
{
    $finfo = new finfo(FILEINFO_MIME);
    return $finfo->buffer($fileBuffer);
}

/**
 * del tmp files
 * @param   $dir dir to wash
 * @param   $contentOnly boolean true if only the content
 * @return  boolean
 */
function Ds_washTmp($dir, $contentOnly=false)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != '.' && $object != '..') {
                if (
                    filetype($dir . DIRECTORY_SEPARATOR . $object) == 'dir'
                ) {
                    Ds_washTmp($dir . DIRECTORY_SEPARATOR . $object);
                } else {
                    unlink($dir . DIRECTORY_SEPARATOR . $object);
                }
            }
        }
        reset($objects);
        if (!$contentOnly) {
            rmdir($dir);
        }
    }
}

/**
* Return true when the file is completed
* @param  $file
* @param  $delay
* @param  $pointer position in the file
*/
function Ds_isCompleteFile($file, $delay=500, $pointer=0)
{
    if ($file == null) {
        return false;
    }
    fseek($file, $pointer);
    $currentLine = fgets($file);
    while (!feof($file)) {
        $currentLine = fgets($file);
    }
    $currentPos = ftell($file);
    //Wait $delay ms
    usleep($delay * 1000);
    if ($currentPos == $pointer) {
        return true;
    } else {
        return Ds_isCompleteFile($file, $delay, $currentPos);
    }
}

/**
 * Check the mime type of a file with the extension config file
* Return array with the status of the check and the mime type of the file
* @param  string $filePath
* @param  array
*/
function Ds_isFileTypeAllowed($filePath, $extDefault = '')
{
    
    $mimeType = Ds_getMimeType(
        $filePath
    );
    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    if ($ext == '' || $ext == 'tmp') {
        $ext = $extDefault;
    }
    if ($ext == 'html' && $mimeType == "text/plain") {
        $arrayReturn = array(
            'status' => true,
            'mime_type' => "text/html",
        );
        return $arrayReturn;
    }
    if (file_exists($_SESSION['config']['corepath'] . 'custom'
        . DIRECTORY_SEPARATOR.$_SESSION['custom_override_id']
        . DIRECTORY_SEPARATOR
        . 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
        . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
        . 'extensions.xml')
    ) {
        $path = $_SESSION['config']['corepath'] . 'custom'
        . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
        . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
        . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . 'xml'
        . DIRECTORY_SEPARATOR . 'extensions.xml';
    } else {
        $path = $_SESSION['config']['corepath'] . 'apps' 
		. DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
        . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'extensions.xml';
    }
    $xmlconfig = simplexml_load_file($path);
    $ext_list = array();
    $i = 0;
    foreach ($xmlconfig->FORMAT as $FORMAT) {
        $ext_list[$i] = array(
            'name' => (string) $FORMAT->name,
            'mime' => (string) $FORMAT->mime
        );
        $i++;
    }
    $type_state = false;
    for ($i=0;$i<count($ext_list);$i++) {
        if (
            $ext_list[$i]['mime'] == $mimeType 
            && strtolower($ext_list[$i]['name']) == $ext
        ) {

            $type_state = true;
            break;
        }
    }
    $arrayReturn = array(
        'status' => $type_state,
        'mime_type' => $mimeType,
    );
    return $arrayReturn;
}
