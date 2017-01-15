<?php
/*
*
*   Copyright 2012-2016 Maarch
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
 * @brief    modules tools Class for sendmail,
 *           contains all the functions to handle sendmail
 *
 * @file     class_modules_tools.php
 * @author   <dev@maarch.org>
 * @date     $date$
 * @version  $Revision$
 * @ingroup  sendmail
 */

// Loads the required class
try {
    require_once("core/class/class_db.php");
    require_once ("core/class/class_security.php");
    require_once("modules/sendmail/sendmail_tables.php");
} catch (Exception $e){
    functions::xecho($e->getMessage()).' // ';
}


abstract class SendmailAbstract extends Database
{
    /**
     * Build Maarch module tables into sessions vars with a xml configuration
     * file
     */
    public function build_modules_tables() {
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . "modules"
            . DIRECTORY_SEPARATOR . "sendmail" . DIRECTORY_SEPARATOR . "xml"
            . DIRECTORY_SEPARATOR . "config.xml"
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR
                . "sendmail" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
                . "config.xml";
        } else {
            $path = "modules" . DIRECTORY_SEPARATOR . "sendmail"
                . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
                . "config.xml";
        }
        $xmlconfig = simplexml_load_file($path);
        $_SESSION['sendmail'] = array();

        //Lang file
        include_once 'modules' . DIRECTORY_SEPARATOR . 'sendmail'
            . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR
            . $_SESSION['config']['lang'] . '.php';

        //Status
        $_SESSION['sendmail']['status'] = array();
        if (count($xmlconfig->STATUS ) > 0) {
            foreach($xmlconfig->STATUS as $status) {
                foreach($status->STS as $STS) {
                    $label = (string)$STS->LABEL;
                    if (!empty($label) && defined($label) && constant($label) <> NULL) {
                        $label = constant($label);
                    }
                    $_SESSION['sendmail']['status'][(string)$STS->ID]['id'] = (string)$STS->ID;
                    $_SESSION['sendmail']['status'][(string)$STS->ID]['label'] = $label;
                    $_SESSION['sendmail']['status'][(string)$STS->ID]['img'] = (string)$STS->IMG;
                }
            }
        }

        //History
        $hist = $xmlconfig->HISTORY;
        $_SESSION['history']['mailadd'] = (string) $hist->mailadd;
        $_SESSION['history']['mailup'] = (string) $hist->mailup;
        $_SESSION['history']['maildel'] = (string) $hist->maildel;
    }

    public function countUserEmails($id, $coll_id, $owner=false) {
        $nbr = 0;
        $db = new Database();
        $arrayPDO = array();
        if ( $owner=== true) {
            $where = " and user_id = :user_id ";
            $arrayPDO = array(":user_id" => $_SESSION['user']['UserId']);
        } else {
            $where = "";
        }

        $arrayPDO = array_merge($arrayPDO, array(":res_id" => $id));
        $arrayPDO = array_merge($arrayPDO, array(":coll_id" => $coll_id));
        $stmt = $db->query("select email_id from "
            . EMAILS_TABLE
            . " where res_id = :res_id and coll_id = :coll_id ".$where,
            $arrayPDO
        );
        // $db->show();
        $nbr = $stmt->rowCount();

        return $nbr;
    }

    public function CheckEmailAdress($adress) {
        $error = '';
        if (!empty($adress)) {

            $adressArray = explode(',', trim($adress));
            for($i=0; $i < count($adressArray); $i++) {
                if (!empty($adressArray[$i])) {
                    $this->wash($adressArray[$i], 'mail', _EMAIL.": ".$adressArray[$i], 'yes', 0, 255);
                    if (!empty($_SESSION['error'])) {
                        $error .= $_SESSION['error'];$_SESSION['error']='';
                    }
                }
            }
            $error = str_replace("<br />", "###", $error);
        }
        return $error;
    }

    public function haveJoinedFiles($id) {

        $db = new Database();
        $stmt = $db->query("select email_id from "
            . EMAILS_TABLE
            . " where email_id = ? and (is_res_master_attached ='Y' or"
            . " res_attachment_id_list <> '' or"
            . " res_version_id_list <> '' or"
            . " note_id_list <> '')", array($id ));
        // $db->show();
        if ($stmt->rowCount() > 0)
            return true;
        else
            return false;
    }

    public function getJoinedFiles($coll_id, $table, $id, $from_res_attachment=false) {
        $joinedFiles = array();
        $db = new Database();
        if ($from_res_attachment === false) {
            require_once('core/class/class_security.php');
            $sec = new security();
            $versionTable = $sec->retrieve_version_table_from_coll_id(
                $coll_id
            );

            //Have version table
            if ($versionTable <> '') {
                $stmt = $db->query("select res_id from "
                    . $versionTable . " where res_id_master = ? and status <> 'DEL' order by res_id desc", array($id));
                $line = $stmt->fetchObject();
                $lastVersion = $line->res_id;
                //Have new version
                if ($lastVersion <> '') {
                    $stmt = $db->query(
                        "select res_id, description, subject, title, format, filesize, relation from "
                        . $versionTable . " where res_id = ? and status <> 'DEL'",array($lastVersion)
                    );
                    // $db->show();
                    //Get infos
                    while($res = $stmt->fetchObject()) {
                        $label = '';
                        //Tile, or subject or description
                        if (strlen(trim($res->title)) > 0)
                            $label = $res->title;
                        elseif (strlen(trim($res->subject)) > 0)
                            $label = $res->subject;
                        elseif (strlen(trim($res->description)) > 0)
                            $label = $res->description;

                        array_push($joinedFiles,
                            array('id' => $res->res_id, //ID
                                'label' => $this->show_string($label), //Label
                                'format' => $res->format, //Format
                                'filesize' => $res->filesize, //Filesize
                                'is_version' => true, //Have version bool
                                'version' => $res->relation //Version
                            )
                        );
                    }
                }
            }

            $stmt = $db->query(
                "select res_id, description, subject, title, format, filesize, relation from "
                . $table . " where res_id = ? and status <> 'DEL'", array($id )
            );
        } else {
            require_once 'modules/attachments/attachments_tables.php';
            $stmt = $db->query(
                "SELECT rva.res_id, rva.description, rva.subject, rva.title, rva.format, rva.filesize, rva.res_id_master, rva.attachment_type, rva.identifier, cv2.society, cv2.firstname, cv2.lastname, rva.filename, rva.path
                FROM res_view_attachments rva LEFT JOIN contacts_v2 cv2 ON rva.dest_contact_id = cv2.contact_id WHERE rva.res_id_master = ? and rva.coll_id = ? and rva.status <> 'DEL' and rva.status <> 'OBS' and rva.attachment_type NOT IN ('converted_pdf','print_folder') ORDER BY rva.attachment_type, rva.description",
                array($id, $coll_id)
            );
        }
        // $db->show();

        while($res = $stmt->fetchObject()) {

            //check converted_pdf
            $pathConvert = $res->path;
            $realFilename = explode('.', $res->filename);
            $filenameConvert = $realFilename[0].'.pdf';
            //var_dump($filenameConvert);
            $stmt2 = $db->query(
                "SELECT rva.res_id
                FROM res_view_attachments rva  WHERE rva.path = ? and rva.filename = ? and rva.attachment_type = 'converted_pdf'",
                array($pathConvert, $filenameConvert)
            );
            $converted = $stmt2->fetchObject();
            $label = '';
            //Tile, or subject or description
            if (strlen(trim($res->title)) > 0)
                $label = $res->title;
            elseif (strlen(trim($res->subject)) > 0)
                $label = $res->subject;
            elseif (strlen(trim($res->description)) > 0)
                $label = $res->description;

            array_push($joinedFiles,
                array('id' => $res->res_id, //ID
                    'label' => $this->show_string($label), //Label
                    'format' => $res->format, //Format
                    'filesize' => $res->filesize, //Filesize
                    'is_version' => false, //
                    'version' => '', //
                    'attachment_type' => $res->attachment_type,
                    'identifier' => $res->identifier,
                    'society' => $res->society,
                    'firstname' => $res->firstname,
                    'lastname' => $res->lastname,
                    'converted_pdf' => $converted->res_id
                )
            );
        }

        return $joinedFiles;
    }

    public function rawToHtml($text) {
        //...
        $text = str_replace("\r\n", PHP_EOL, $text);
        $text = str_replace("\r", PHP_EOL, $text);
        //

        //$text = str_replace(PHP_EOL, "<br />", $text);
        //
        return $text;
    }

    public function htmlToRaw($text) {
        //
        $text = str_replace("<br>", "\n", $text);
        $text = str_replace("<br/>", "\n", $text);
        $text = str_replace("<br />", "\n", $text);
        $text = strip_tags($text);
        //
        return $text;
    }

    public function cleanHtml($htmlContent){

        $htmlContent = str_replace(';', '###', $htmlContent);
        $htmlContent = str_replace('--', '___', $htmlContent);

        $allowedTags = '<html><head><body><title>'; //Structure
        $allowedTags .= '<h1><h2><h3><h4><h5><h6><b><i><tt><u><strike><blockquote><pre><blink><font><big><small><sup><sub><strong><em>'; // Text formatting
        $allowedTags .='<p><br><hr><center><div><span>'; // Text position
        $allowedTags .= '<li><ol><ul><dl><dt><dd>'; // Lists
        $allowedTags .= '<img><a>'; // Multimedia
        $allowedTags .= '<table><tr><td><th><tbody><thead><tfooter><caption>'; // Tables
        $allowedTags .= '<form><input><textarea><select>'; // Forms
        $htmlContent = strip_tags($htmlContent, $allowedTags);

        return $htmlContent;
    }

    public function getEmail($id, $owner=true) {
        $email = array();
        if (!empty($id)) {
            $db = new Database();
            if ( $owner=== true) {

                $where = " and user_id = ? ";
                $arrayPDO = array($_SESSION['user']['UserId']);
                $stmt = $db->query("select * from "
                    . EMAILS_TABLE
                    . " where email_id = ? " . $where, array( $id, $_SESSION['user']['UserId']));
            } else {
                $where = "";
                $stmt = $db->query("select * from "
                    . EMAILS_TABLE
                    . " where email_id = ? " . $where, array($id));
            }

            if ($stmt->rowCount() > 0) {
                $res = $stmt->fetchObject();
                $email['id'] = $res->email_id;
                $email['collId'] = $res->coll_id;
                $email['resId'] = $res->res_id;
                $email['userId'] = $res->user_id;
                $email['to'] = array();
                if (!empty($res->to_list)) {
                    $email['to'] = explode(',', $res->to_list);
                }
                $email['cc'] = array();
                if (!empty($res->cc_list)) {
                    $email['cc'] = explode(',', $res->cc_list);
                }
                $email['cci'] = array();
                if (!empty($res->cci_list)) {
                    $email['cci'] = explode(',', $res->cci_list);
                }
                $email['version'] = array();
                if (!empty($res->res_version_id_list)) {
                    $email['version'] = explode(',', $res->res_version_id_list);
                }
                $email['attachments'] = array();
                if (!empty($res->res_attachment_id_list)) {
                    $email['attachments'] = explode(',', $res->res_attachment_id_list);
                }
                $email['notes'] = array();
                if (!empty($res->note_id_list)) {
                    $email['notes'] = explode(',', $res->note_id_list);
                }
                $email['object'] = $this->show_string($res->email_object);
                $body = str_replace('###', ';', $res->email_body);
                $body = str_replace('___', '--', $body);
                $email['body'] = $this->show_string($body);
                $email['resMasterAttached'] = $res->is_res_master_attached;
                $email['isHtml'] = $res->is_html;
                $email['status'] = $res->email_status;
                $email['creationDate'] = $this->format_date_db($res->creation_date);
                $email['sendDate'] = $this->format_date_db($res->send_date);
                $email['sender_email'] = $res->sender_email;
            }
        }

        return $email;
    }

    public function updateAdressInputField($ajaxPath, $adressArray, $inputField, $readOnly=false) {
        $content = '';
        //Init with loading div
        $content .= '<div id="loading_'.$inputField.'" style="display:none;"><i class="fa fa-spinner fa-spin" title="loading..."></i></div>';
        // $content .=  print_r($adressArray, true);
        //Get info from session array and display tag
        if (isset($adressArray[$inputField]) && count($adressArray[$inputField]) > 0) {
            foreach($adressArray[$inputField] as $key => $adress)	{
                if (!empty($adress)) {
                    $content .= '<div class="email_element" id="'.$key.'_'.$adress.'">'.$adress;
                    if ($readOnly === false) {
                        $content .= '&nbsp;<div class="email_delete_button" id="'.$key.'"'
                            . 'onclick="updateAdress(\''.$ajaxPath
                            .'&mode=adress\', \'del\', \''.$adress.'\', \''
                            .$inputField.'\', this.id);" alt="'._DELETE.'" title="'
                            ._DELETE.'">x</div>';
                    }
                    $content .= '</div>';
                }
            }
        }
        return $content;
    }

    public function getResource($collectionArray, $coll_id, $res_id) {
        $viewResourceArr = array();

        for ($i=0;$i<count($collectionArray);$i++) {
            if ($collectionArray[$i]['id'] == $coll_id) {
                //Get table
                $table = $collectionArray[$i]['table'];
                //Get adress
                $adrTable = $collectionArray[$i]['adr'];
                //Get versions table
                $versionTable = $collectionArray[$i]['version_table'];
                break;
            }
        }

        if (!empty($res_id) && !empty($table) && !empty($adrTable)) {
            //docserver
            require_once('core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
                . 'docservers_controler.php');
            $docserverControler = new docservers_controler();
            $docserverLocation = array();
            // echo '--> '.$coll_id.'/'.$res_id.'/'.$table.'/'.$adrTable.PHP_EOL;
            $docserverLocation = $docserverControler->retrieveDocserverNetLinkOfResource(
                $res_id, $table, $adrTable
            );
            //View resource controler
            $viewResourceArr = $docserverControler->viewResource(
                $res_id,
                $table,
                $adrTable,
                false
            );
            //Reajust some info
            if (strtoupper($viewResourceArr['ext']) == 'HTML'
                && $viewResourceArr['mime_type'] == "text/plain"
            ) {
                $viewResourceArr['mime_type'] = "text/html";
            }
            $db = new Database();
            $stmt = $db->query(
                "select res_id, description, subject, title, format, filesize from "
                . $table . " where res_id = ? and status <> 'DEL'", array($res_id));
            $res = $stmt->fetchObject();
            $label = '';
            //Tile, or subject or description
            if (strlen(trim($res->title)) > 0)
                $label = $res->title;
            elseif (strlen(trim($res->subject)) > 0)
                $label = $res->subject;
            elseif (strlen(trim($res->description)) > 0)
                $label = $res->description;
            $viewResourceArr['label'] = $this->show_string($label);

            //$viewResourceArr['status'] /ko /ok
            //$viewResourceArr['error']
            // $this->show_array($viewResourceArr);
        }

        return $viewResourceArr;
    }

    public function getAttachment($coll_id, $res_id_master, $res_attachment) {

        require_once 'modules/attachments/attachments_tables.php';
        require_once 'core/core_tables.php';
        require_once 'core/docservers_tools.php';

        $viewAttachmentArr = array();

        $db = new Database();
        $stmt = $db->query(
            "select description, subject, title, docserver_id, path, filename, format from "
            . RES_ATTACHMENTS_TABLE . " where res_id = ? and coll_id = ? and res_id_master = ? ",
            array($res_attachment, $coll_id, $res_id_master)
        );
        if ($stmt->rowCount() > 0) {
            $line = $stmt->fetchObject();
            //Tile, or subject or description
            if (strlen(trim($line->title)) > 0)
                $label = $line->title;
            elseif (strlen(trim($line->subject)) > 0)
                $label = $line->subject;
            elseif (strlen(trim($line->description)) > 0)
                $label = $line->description;
            //
            $docserver = $line->docserver_id;
            $path = $line->path;
            $filename = $line->filename;
            $format = $line->format;
            $stmt = $db->query(
                "select path_template from " . _DOCSERVERS_TABLE_NAME
                . " where docserver_id = ? ",
                array($docserver)
            );
            //$db->show();
            $lineDoc = $stmt->fetchObject();
            $docserver = $lineDoc->path_template;
            $file = $docserver . $path . $filename;
            $file = str_replace("#", DIRECTORY_SEPARATOR, $file);
            if (file_exists($file)) {
                $mimeType = Ds_getMimeType($file);

                $fileNameOnTmp = 'tmp_file_' . rand()
                    . '.' . strtolower($format);
                $filePathOnTmp = $_SESSION['config']
                    ['tmppath'] . DIRECTORY_SEPARATOR
                    . $fileNameOnTmp;
                copy($file, $filePathOnTmp);

                $viewAttachmentArr = array(
                    'status' => 'ok',
                    'label' => $this->show_string($label),
                    'mime_type' => $mimeType,
                    'ext' => $format,
                    'file_content' => '',
                    'tmp_path' => $_SESSION['config']
                    ['tmppath'],
                    'file_path' => $filePathOnTmp,
                    'called_by_ws' => '',
                    'error' => ''
                );
            } else {
                $viewAttachmentArr = array(
                    'status' => 'ko',
                    'label' => '',
                    'mime_type' => '',
                    'ext' => '',
                    'file_content' => '',
                    'tmp_path' => '',
                    'file_path' => '',
                    'called_by_ws' => '',
                    'error' => _FILE_NOT_EXISTS_ON_THE_SERVER
                );

            }
        } else {
            $viewAttachmentArr = array(
                'status' => 'ko',
                'label' => '',
                'mime_type' => '',
                'ext' => '',
                'file_content' => '',
                'tmp_path' => '',
                'file_path' => '',
                'called_by_ws' => '',
                'error' => _NO_RIGHT_ON_RESOURCE_OR_RESOURCE_NOT_EXISTS
            );
        }

        // $this->show_array($viewAttachmentArr);

        return $viewAttachmentArr;
    }

    public function getVersion($collectionArray, $coll_id, $res_id_master, $res_version) {

        $viewVersionArr = array();

        //Parse collection
        for ($i=0;$i<count($collectionArray);$i++) {
            if ($collectionArray[$i]['id'] == $coll_id) {
                //Get table
                $table = $collectionArray[$i]['table'];
                //Get adress
                $adrTable = $collectionArray[$i]['adr'];
                //Get versions table
                $versionTable = $collectionArray[$i]['version_table'];
                break;
            }
        }

        //Have version table
        if ($versionTable <> '') {
            $db = new Database();
            $stmt = $db->query("select res_id, description, subject, title, docserver_id, "
                . "path, filename,format, filesize, relation from "
                . $versionTable . " where res_id = ? and res_id_master = ? and status <> 'DEL'",
                array($res_version, $res_id_master)
            );
            //$db->show();
            //Have new version
            if ($stmt->rowCount() > 0) {

                $line = $stmt->fetchObject();
                //Tile, or subject or description
                if (strlen(trim($line->title)) > 0)
                    $label = $line->title;
                elseif (strlen(trim($line->subject)) > 0)
                    $label = $line->subject;
                elseif (strlen(trim($line->description)) > 0)
                    $label = $line->description;

                //Get file from docserver
                require_once 'core/core_tables.php';
                require_once 'core/docservers_tools.php';

                $docserver = $line->docserver_id;
                $path = $line->path;
                $filename = $line->filename;
                $format = $line->format;
                $stmt = $db->query(
                    "select path_template from " . _DOCSERVERS_TABLE_NAME
                    . " where docserver_id = ? ", array($docserver)
                );
                //$db->show();
                $lineDoc = $stmt->fetchObject();
                $docserver = $lineDoc->path_template;
                $file = $docserver . $path . $filename;
                $file = str_replace("#", DIRECTORY_SEPARATOR, $file);

                //Is there a corresponding file?
                if (file_exists($file)) {
                    $mimeType = Ds_getMimeType($file);

                    $fileNameOnTmp = 'tmp_file_' . rand()
                        . '.' . strtolower($format);
                    $filePathOnTmp = $_SESSION['config']
                        ['tmppath'] . DIRECTORY_SEPARATOR
                        . $fileNameOnTmp;
                    copy($file, $filePathOnTmp);

                    $viewVersionArr = array(
                        'status' => 'ok',
                        'label' => $this->show_string($label),
                        'mime_type' => $mimeType,
                        'ext' => $format,
                        'file_content' => '',
                        'tmp_path' => $_SESSION['config']
                        ['tmppath'],
                        'file_path' => $filePathOnTmp,
                        'called_by_ws' => '',
                        'error' => ''
                    );
                } else {
                    $viewVersionArr = array(
                        'status' => 'ko',
                        'label' => '',
                        'mime_type' => '',
                        'ext' => '',
                        'file_content' => '',
                        'tmp_path' => '',
                        'file_path' => '',
                        'called_by_ws' => '',
                        'error' => _FILE_NOT_EXISTS_ON_THE_SERVER
                    );
                }
            } else {
                $viewVersionArr = array(
                    'status' => 'ko',
                    'label' => '',
                    'mime_type' => '',
                    'ext' => '',
                    'file_content' => '',
                    'tmp_path' => '',
                    'file_path' => '',
                    'called_by_ws' => '',
                    'error' => _NO_RIGHT_ON_RESOURCE_OR_RESOURCE_NOT_EXISTS
                );
            }
        } else {
            $viewVersionArr = array(
                'status' => 'ko',
                'label' => '',
                'mime_type' => '',
                'ext' => '',
                'file_content' => '',
                'tmp_path' => '',
                'file_path' => '',
                'called_by_ws' => '',
                'error' => _NO_VERSION_TABLE_FOR_THIS_COLLECTION
            );
        }
        // $this->show_array($viewVersionArr);

        return $viewVersionArr;
    }

    public function createFilename($label, $extension){
        $search = array (
            utf8_decode('@[éèêë]@i'), utf8_decode('@[ÊË]@i'), utf8_decode('@[àâä]@i'), utf8_decode('@[ÂÄ]@i'),
            utf8_decode('@[îï]@i'), utf8_decode('@[ÎÏ]@i'), utf8_decode('@[ûùü]@i'), utf8_decode('@[ÛÜ]@i'),
            utf8_decode('@[ôö]@i'), utf8_decode('@[ÔÖ]@i'), utf8_decode('@[ç]@i'), utf8_decode('@[^a-zA-Z0-9_-s.]@i'));

        $replace = array ('e', 'E','a', 'A','i', 'I', 'u', 'U', 'o', 'O','c','_');

        $filename = preg_replace($search, $replace, utf8_decode($label.".".$extension));
        // $filename = preg_replace("/[^a-z0-9_-s.]/i","_", $label.".".$extension);

        return (substr($filename, 0, 30) . '.' . $extension);
    }

    public function createNotesFile($coll_id, $id, $notesArray) {
        require_once "modules" . DIRECTORY_SEPARATOR . "notes" . DIRECTORY_SEPARATOR
            . "class" . DIRECTORY_SEPARATOR
            . "class_modules_tools.php";
        $notes_tools = new notes();

        $db = new Database();

        if (count($notesArray) > 0) {
            //Format
            $format = 'html';
            //Mime type
            $mimeType = 'text/html';
            //Filename
            $fileName = "notes_".date(dmY_Hi).".".$format;
            //File path
            $fileNameOnTmp = 'tmp_file_' . rand()
                . '.' . strtolower($format);
            $filePathOnTmp = $_SESSION['config']
                ['tmppath'] . DIRECTORY_SEPARATOR
                . $fileNameOnTmp;

            //Create file
            if (file_exists($filePathOnTmp)) {
                unlink($filePathOnTmp);
            }

            //File content
            $content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" '
                . '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" >';
            $content .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">';
            $content .= "<head><title>Maarch Notes</title><meta http-equiv='Content-Type' "
                . "content='text/html; charset=UTF-8' /><meta content='fr' "
                . "http-equiv='Content-Language'/><meta http-equiv='cache-control' "
                . "content='no-cache'/><meta http-equiv='pragma' content='no-cache'>"
                . "<meta http-equiv='Expires' content='0'></head>";
            $content .= "<body onload='javascript:window.print();' style='font-size:8pt'>";
            $content .= "<h2>"._NOTES."</h2>";
            $content .= "<table cellpadding='4' cellspacing='0' border='1' width='100%'>";

            for($i=0; $i < count($notesArray); $i++) {
                $stmt = $db->query("select n.date_note, n.note_text, u.lastname, "
                    . "u.firstname from " . NOTES_TABLE . " n inner join ". USERS_TABLE
                    . " u on n.user_id  = u.user_id where n.id = ? and identifier = ? and coll_id = ? order by date_note desc",
                    array($notesArray[$i], $id, $coll_id)
                );

                if($stmt->rowCount() > 0) {

                    $line = $stmt->fetchObject();

                    $user = functions::show_string($line->firstname . " " . $line->lastname);
                    $notes = functions::show_string($line->note_text);
                    $date = functions::dateformat($line->date_note);

                    $content .= "<tr height='130px'>";
                    $content .= "<td width='15%'>";
                    $content .= "<h3>"._USER.": ". $user."</h3>";
                    $content .= "<h3>"._DATE.": ". $date."</h3>";
                    $content .= "</td>";
                    $content .= "<td width='85%'>";
                    $content .= $notes;
                    $content .= "</td>";
                    $content .= "</tr>";
                }
            }

            $content .= "</table>";
            $content .= "</body></html>";
            //Write file
            $inF = fopen($filePathOnTmp,"w");
            fwrite($inF, $content);
            fclose($inF);

            $viewAttachmentArr = array(
                'status' => 'ok',
                'label' => '',
                'mime_type' => $mimeType,
                'ext' => $format,
                'file_content' => '',
                'tmp_path' => $_SESSION['config']
                ['tmppath'],
                'file_path' => $filePathOnTmp,
                'filename' => $fileName,
                'called_by_ws' => '',
                'error' => ''
            );

            // $this->show_array($viewAttachmentArr);

            return $viewAttachmentArr;
        } else {
            return false;
        }
    }

    public function getAttachedEntitiesMails($user_id="") {
        $db = new Database;
        $arrayEntitiesMails = array();

        if ($user_id <> "") {
            $stmt = $db->query("SELECT e.short_label, e.email, e.entity_id FROM users_entities ue, entities e
                                WHERE ue.user_id = ? and ue.entity_id = e.entity_id and enabled = 'Y' order by e.short_label", array($user_id));
        } else {
            $stmt = $db->query("SELECT short_label, email, entity_id FROM entities WHERE enabled = 'Y'");
        }

        $userEntities = array();
        while ($res = $stmt->fetchObject()) {
            $userEntities[]=$res->entity_id;
            if ($res->email <> "") {
                $arrayEntitiesMails[$res->entity_id.','.$res->email] = $res->short_label . " (". $res->email .")";
            }
        }

        $getXml = false;

        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'modules'
            . DIRECTORY_SEPARATOR . 'sendmail'. DIRECTORY_SEPARATOR . 'batch'
            . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'externalMailsEntities.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
                . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'modules'
                . DIRECTORY_SEPARATOR . 'sendmail'. DIRECTORY_SEPARATOR . 'batch'
                . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'externalMailsEntities.xml';
            $getXml = true;
        } else if (file_exists($_SESSION['config']['corepath'] . 'modules' . DIRECTORY_SEPARATOR . 'sendmail'. DIRECTORY_SEPARATOR . 'batch'
            . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'externalMailsEntities.xml')) {
            $path = $_SESSION['config']['corepath'] . 'modules' . DIRECTORY_SEPARATOR . 'sendmail'. DIRECTORY_SEPARATOR . 'batch'
                . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'externalMailsEntities.xml';
            $getXml = true;
        }

        if ($getXml) {
            $xml = simplexml_load_file($path);

            if ($xml <> false) {
                require_once("modules/entities/class/class_manage_entities.php");
                $entities = new entity();

                foreach ($xml->externalEntityMail as $EntityMail) {
                    $shortLabelEntity = $entities->getentityshortlabel((string)$EntityMail->targetEntityId);
                    if (!in_array((string)$EntityMail->targetEntityId.",".(string)$EntityMail->EntityMail, array_keys($arrayEntitiesMails))
                        && (in_array((string)$EntityMail->targetEntityId, $userEntities) || (string)$EntityMail->targetEntityId == "")
                        && (string)$EntityMail->EntityMail <> "") {
                        if ((string)$EntityMail->targetEntityId<>"") {
                            $EntityName = $shortLabelEntity;
                        } else {
                            $EntityName = (string)$EntityMail->defaultName;
                        }
                        $arrayEntitiesMails[(string)$EntityMail->targetEntityId.",".(string)$EntityMail->EntityMail] = $EntityName . " (" . (string)$EntityMail->EntityMail .")";
                    }
                }
            }
        }
        asort($arrayEntitiesMails);

        return $arrayEntitiesMails;
    }

    public function checkAttachedEntitiesMails(){
        $db = new Database;
        $core_tools = new core_tools();

        $entitiesMails = array();
        if ($core_tools->test_service('use_mail_services', 'sendmail', false)) {
            $entitiesMails = $this->getAttachedEntitiesMails($_SESSION['user']['UserId']);
        }

        $stmt = $db->query("SELECT mail FROM users WHERE user_id = ? ", array($_SESSION['user']['UserId']));
        $res = $stmt->fetchObject();

        if ($res->mail<>"") {
            $entitiesMails[$res->mail] = "";
        }

        return $entitiesMails;
    }

    public function explodeSenderEmail($senderEmail){
        if (strpos($senderEmail, ",") === false) {
            return $senderEmail;
        } else {
            $explode = explode(",", $senderEmail);
            return $explode[1];
        }
    }

}

