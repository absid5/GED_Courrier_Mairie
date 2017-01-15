<?php

/*
*   Copyright 2008-2016 Maarch
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
* File : attachments_content.php
*
* Add an answer in the process
*
* @package Maarch 1.5
* @license GPL
* @author <dev@maarch.org>
*/

require_once "core/class/class_security.php";
require_once "core/class/class_request.php";
require_once "core/class/class_resource.php";
require_once "apps" . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    . "class_indexing_searching_app.php";
require_once "core/class/docservers_controler.php";
require_once 'modules/attachments/attachments_tables.php';
require_once "core/class/class_history.php";
require_once 'modules/attachments/class/attachments_controler.php';


$core               = new core_tools();
$core->load_lang();
$sec                = new security();
$func               = new functions();
$db                 = new Database();
$req                = new request();
$docserverControler = new docservers_controler();
$ac                 = new attachments_controler();

$_SESSION['error'] = "";

$status = 0;
$error  = $content = $js = $parameters = '';

function _parse($text) {
    $text = str_replace("\r\n", "\n", $text);
    $text = str_replace("\r", "\n", $text);
    $text = str_replace("\n", "\\n ", $text);
    return $text;
}

function checkTransmissionError($nb) {
    if (empty($_REQUEST["transmissionType{$nb}"]) && empty($_REQUEST["transmissionChrono{$nb}"]) && empty($_REQUEST["transmissionTitle{$nb}"]))
        return false;
    if (empty($_REQUEST["transmissionType{$nb}"]) || empty($_REQUEST["transmissionChrono{$nb}"])) {
        $_SESSION['error'] .= "Transmission {$nb} : " . _ATTACHMENT_TYPES . ' ' . _MANDATORY . ". ";
        return false;
    }
    if (empty($_REQUEST["transmissionTitle{$nb}"])) {
        $_SESSION['error'] .= "Transmission {$nb} : " . _OBJECT . ' ' . _MANDATORY . ". ";
        return false;
    }
    return true;
}

function setTransmissionData($nb, $storeResult) {
    $func = new functions();
    $transmissionData = [];

    $transmissionData[] = [
        'column' => 'typist',
        'value' => $_SESSION['user']['UserId'],
        'type' => 'string'
    ];
    $transmissionData[] = [
        'column' => 'format',
        'value' => $_SESSION['upfileTransmission'][$nb]['format'],
        'type' => 'string'
    ];
    $transmissionData[] = [
        'column' => 'docserver_id',
        'value' => $storeResult['docserver_id'],
        'type' => 'string'
    ];
    if (!empty($_REQUEST["transmissionExpectedDate{$nb}"])) {
        $rturn = $_REQUEST["transmissionExpectedDate{$nb}"];
    } else {
        $rturn = 'NO_RTURN';
    }
    $transmissionData[] = [
        'column' => 'status',
        'value' => $func->protect_string_db($rturn),
        'type' => 'string'
    ];
    $transmissionData[] = [
        'column' => 'offset_doc',
        'value' => ' ',
        'type' => 'string'
    ];
    $transmissionData[] = [
        'column' => 'logical_adr',
        'value' => ' ',
        'type' => 'string'
    ];
    $transmissionData[] = [
        'column' => 'title',
        'value' => str_replace("&#039;", "'", $_REQUEST["transmissionTitle{$nb}"]),
        'type' => 'string'
    ];
    $transmissionData[] = [
        'column' => 'attachment_type',
        'value' => $func->protect_string_db($_REQUEST["transmissionType{$nb}"]),
        'type' => 'string'
    ];
    $transmissionData[] = [
        'column' => 'coll_id',
        'value' => $_SESSION['collection_id_choice'],
        'type' => 'string'
    ];
    $transmissionData[] = [
        'column' => 'res_id_master',
        'value' => $_SESSION['doc_id'],
        'type' => 'integer'
    ];
    $transmissionData[] = [
        'column' => 'identifier',
        'value' => $_REQUEST["transmissionChrono{$nb}"],
        'type' => 'string'
    ];
    $transmissionData[] = [
        'column' => 'type_id',
        'value' => 0,
        'type' => 'int'
    ];
    $transmissionData[] = [
        'column' => 'relation',
        'value' => 1,
        'type' => 'int'
    ];

    if (!empty($_REQUEST["transmissionBackDate{$nb}"])) {
        $transmissionData[] = [
            'column' => 'validation_date',
            'value' => $func->format_date_db($_REQUEST["transmissionBackDate{$nb}"]),
            'type' => 'date'
        ];
    }

    if (!empty($_REQUEST["transmissionContactidAttach{$nb}"]) && is_numeric($_REQUEST["transmissionContactidAttach{$nb}"])) {
        $transmissionData[] = [
            'column' => 'dest_contact_id',
            'value' => $_REQUEST["transmissionContactidAttach{$nb}"],
            'type' => 'integer'
        ];
    } else if (!empty($_REQUEST["transmissionContactidAttach{$nb}"]) && !is_numeric($_REQUEST["transmissionContactidAttach{$nb}"])) {
        $transmissionData[] = [
            'column' => 'dest_user',
            'value' => $_REQUEST["transmissionContactidAttach{$nb}"],
            'type' => 'string'
        ];
    }

    if (!empty($_REQUEST["transmissionAddressidAttach{$nb}"]) && is_numeric($_REQUEST["transmissionAddressidAttach{$nb}"])) {
        $transmissionData[] = [
            'column' => 'dest_address_id',
            'value' => $_REQUEST["transmissionAddressidAttach{$nb}"],
            'type' => 'integer'
        ];
    }

    return $transmissionData;
}

function setTransmissionDataPdf($nb, $storeResult) {
    $transmissionDataPdf = [];

//    $_SESSION['new_id'] = $id;
    $file    = $_SESSION['config']['tmppath'] . $_SESSION['upfileTransmission'][$nb]['fileNamePdfOnTmp'];
    $newfile = $storeResult['path_template'] . str_replace('#',"/",$storeResult['destination_dir']) . substr ($storeResult['file_destination_name'], 0, strrpos  ($storeResult['file_destination_name'], "." )) . '.pdf';

    copy($file, $newfile);

    $transmissionDataPdf[] = [
        'column' => 'typist',
        'value' => $_SESSION['user']['UserId'],
        'type' => 'string'
    ];
    $transmissionDataPdf[] = [
        'column' => 'format',
        'value' => 'pdf',
        'type' => 'string'
    ];
    $transmissionDataPdf[] = [
        'column' => 'docserver_id',
        'value' => $storeResult['docserver_id'],
        'type' => 'string'
    ];
    $transmissionDataPdf[] = [
        'column' => 'status',
        'value' => 'TRA',
        'type' => 'string'
    ];
    $transmissionDataPdf[] = [
        'column' => 'offset_doc',
        'value' => ' ',
        'type' => 'string'
    ];
    $transmissionDataPdf[] = [
        'column' => 'logical_adr',
        'value' => ' ',
        'type' => 'string'
    ];
    $transmissionDataPdf[] = [
        'column' => 'title',
        'value' => str_replace("&#039;", "'", $_REQUEST["transmissionTitle{$nb}"]),
        'type' => 'string'
    ];
    $transmissionDataPdf[] = [
        'column' => 'attachment_type',
        'value' => 'converted_pdf',
        'type' => 'string'
    ];
    $transmissionDataPdf[] = [
        'column' => 'coll_id',
        'value' => $_SESSION['collection_id_choice'],
        'type' => 'string'
    ];
    $transmissionDataPdf[] = [
        'column' => 'res_id_master',
        'value' => $_SESSION['doc_id'],
        'type' => 'integer'
    ];
    $transmissionDataPdf[] = [
        'column' => 'type_id',
        'value' => 0,
        'type' => 'int'
    ];
    $transmissionDataPdf[] = [
        'column' => 'relation',
        'value' => 1,
        'type' => 'int'
    ];

    return $transmissionDataPdf;
}

if (isset($_POST['add']) && $_POST['add']) {
    if (empty($_SESSION['upfile']['tmp_name'])) {
        $_SESSION['error'] .= _FILE_MISSING . ". ";
    } elseif ($_SESSION['upfile']['size'] == 0) {
        $_SESSION['error'] .= _FILE_EMPTY . ". ";
    }

    if ($_SESSION['upfile']['error'] == 1) {
        $filesize = $func->return_bytes(ini_get("upload_max_filesize"));
        $_SESSION['error'] = _ERROR_FILE_UPLOAD_MAX . "(" . round(
            $filesize / 1024, 2
        ) . "Ko Max).<br />";
    }

    for ($nb = 1; checkTransmissionError($nb); $nb++) {
        if (empty($_SESSION['upfileTransmission'][$nb]['tmp_name'])) {
            $_SESSION['error'] .= "Transmission {$nb} : " . _FILE_MISSING . '. ';
        } elseif ($_SESSION['upfileTransmission'][$nb]['size'] == 0) {
            $_SESSION['error'] .= "Transmission {$nb} : " . _FILE_EMPTY . '. ';
        }
    }

    $attachment_types = '';
    if (! isset($_REQUEST['attachment_types']) || empty($_REQUEST['attachment_types'])) {
        $_SESSION['error'] .= _ATTACHMENT_TYPES . ' ' . _MANDATORY . ". ";
    } else {
        $attachment_types = $func->protect_string_db($_REQUEST['attachment_types']);
    }

    $title = '';
    if (! isset($_REQUEST['title']) || empty($_REQUEST['title'])) {
        $_SESSION['error'] .= _OBJECT . ' ' . _MANDATORY . ". ";
    } else {
        $title = $_REQUEST['title'];
        $title = str_replace("&#039;", "'", $title);
    }
    
    if (empty($_SESSION['error'])) {
        require_once 'core/docservers_tools.php';
        $arrayIsAllowed = array();
        $arrayIsAllowed = Ds_isFileTypeAllowed(
            $_SESSION['config']['tmppath'] . $_SESSION['upfile']['fileNameOnTmp']
        );
        if ($arrayIsAllowed['status'] == false) {
            $_SESSION['error'] = _WRONG_FILE_TYPE
                . ' ' . $arrayIsAllowed['mime_type'];
            $_SESSION['upfile'] = array();
        } else {
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
                // some checking on docserver size limit
                $newSize = $docserverControler->checkSize(
                    $docserver, $_SESSION['upfile']['size']
                );
                if ($newSize == 0) {
                    $_SESSION['error'] = _DOCSERVER_ERROR . ' : '
                        . _NOT_ENOUGH_DISK_SPACE . ". " . _MORE_INFOS . ".";
                    ?>
                    <script type="text/javascript">
                        var eleframe1 =  window.parent.top.document.getElementById('list_attach');
                        eleframe1.location.href = '<?php
                    echo $_SESSION['config']['businessappurl'];
                    ?>index.php?display=true&module=attachments&page=frame_list_attachments&attach_type_exclude=converted_pdf,print_folder&mode=normal&load';
                    </script>
                    <?php
                    exit();
                } else {
                    $fileInfos = array(
                        "tmpDir"      => $_SESSION['config']['tmppath'],
                        "size"        => $_SESSION['upfile']['size'],
                        "format"      => $_SESSION['upfile']['format'],
                        "tmpFileName" => $_SESSION['upfile']['fileNameOnTmp'],
                    );

                    $storeResult = array();
                    $storeResult = $docserverControler->storeResourceOnDocserver(
                        $_SESSION['collection_id_choice'], $fileInfos
                    );

                    if (isset($storeResult['error']) && $storeResult['error'] <> '') {
                        $_SESSION['error'] = $storeResult['error'];
                    } else {
                        $resAttach = new resource();
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
                                'value' => $_SESSION['upfile']['format'],
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
                                'value' => 'A_TRA',
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
                                'value' => $attachment_types,
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
                        if ($_SESSION['origin'] == "scan") {
                            array_push(
                                $_SESSION['data'],
                                array(
                                    'column' => "scan_user",
                                    'value' => $_SESSION['user']['UserId'],
                                    'type' => "string",
                                )
                            );
                            array_push(
                                $_SESSION['data'],
                                array(
                                    'column' => "scan_date",
                                    'value' => $req->current_datetime(),
                                    'type' => "function",
                                )
                            );
                        }
                        if (isset($_REQUEST['back_date']) && $_REQUEST['back_date'] <> '') {
                            array_push(
                                $_SESSION['data'],
                                array(
                                    'column' => "validation_date",
                                    'value' => $func->format_date_db($_REQUEST['back_date']),
                                    'type' => "date",
                                )
                            );
                        }

                        if (isset($_REQUEST['contactidAttach']) && $_REQUEST['contactidAttach'] <> '' && is_numeric($_REQUEST['contactidAttach'])) {
                            array_push(
                                $_SESSION['data'],
                                array(
                                    'column' => "dest_contact_id",
                                    'value' => $_REQUEST['contactidAttach'],
                                    'type' => "integer",
                                )
                            );
                        } else if (isset($_REQUEST['contactidAttach']) && $_REQUEST['contactidAttach'] != '' && !is_numeric($_REQUEST['contactidAttach'])) {
                            $_SESSION['data'][] = [
                                'column' => 'dest_user',
                                'value' => $_REQUEST['contactidAttach'],
                                'type' => 'string',
                            ];
                        }

                        if (isset($_REQUEST['addressidAttach']) && $_REQUEST['addressidAttach'] <> '' && is_numeric($_REQUEST['addressidAttach'])) {
                            array_push(
                                $_SESSION['data'],
                                array(
                                    'column' => "dest_address_id",
                                    'value' => $_REQUEST['addressidAttach'],
                                    'type' => "integer",
                                )
                            );
                        }
                        array_push(
                            $_SESSION['data'],
                            array(
                                'column' => "identifier",
                                'value' => $_REQUEST['chrono'],
                                'type' => "string",
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
							$storeResult['docserver_id'],
                            $_SESSION['data'],
							$_SESSION['config']['databasetype']
						);

                        for ($nb = 1; checkTransmissionError($nb); $nb++) {
                            $fileInfosTr = [
                                'tmpDir'      => $_SESSION['config']['tmppath'],
                                'size'        => $_SESSION['upfileTransmission'][$nb]['size'],
                                'format'      => $_SESSION['upfileTransmission'][$nb]['format'],
                                'tmpFileName' => $_SESSION['upfileTransmission'][$nb]['fileNameOnTmp'],
                            ];

                            $storeResultTr = $docserverControler->storeResourceOnDocserver($_SESSION['collection_id_choice'], $fileInfosTr);

                            $resAttach->load_into_db(
                                RES_ATTACHMENTS_TABLE,
                                $storeResultTr['destination_dir'],
                                $storeResultTr['file_destination_name'] ,
                                $storeResultTr['path_template'],
                                $storeResultTr['docserver_id'],
                                setTransmissionData($nb, $storeResultTr),
                                $_SESSION['config']['databasetype']
                            );

                            if ($_SESSION['modules_loaded']['attachments']['convertPdf'] == true && $_SESSION['upfileTransmission'][$nb]['fileNamePdfOnTmp'] != '') {
                                $resAttach->load_into_db(
                                    RES_ATTACHMENTS_TABLE,
                                    $storeResultTr['destination_dir'],
                                    substr($storeResultTr['file_destination_name'], 0, strrpos($storeResultTr['file_destination_name'], "." )) . '.pdf' ,
                                    $storeResultTr['path_template'],
                                    $storeResultTr['docserver_id'],
                                    setTransmissionDataPdf($nb, $storeResultTr),
                                    $_SESSION['config']['databasetype']
                                );
                            }
                        }
                        unset($_SESSION['transmissionContacts']);
                        
                        //copie de la version PDF de la pièce si mode de conversion sur le client
                        if ($_SESSION['modules_loaded']['attachments']['convertPdf'] == true && $_SESSION['upfile']['fileNamePdfOnTmp'] != '' && isset($_REQUEST['templateOffice'])){
							$_SESSION['new_id'] = $id;
                            $file    = $_SESSION['config']['tmppath'].$_SESSION['upfile']['fileNamePdfOnTmp'];
                            $newfile = $storeResult['path_template'].str_replace('#',"/",$storeResult['destination_dir']).substr ($storeResult['file_destination_name'], 0, strrpos  ($storeResult['file_destination_name'], "." )).".pdf";
                            
                            copy($file, $newfile);
							
							$_SESSION['data_pdf'] = array();
							
							array_push(
								$_SESSION['data_pdf'],
								array(
									'column' => "typist",
									'value' => $_SESSION['user']['UserId'],
									'type' => "string",
								)
							);
							
							array_push(
								$_SESSION['data_pdf'],
								array(
									'column' => "format",
									'value' => 'pdf',
									'type' => "string",
								)
							);
							array_push(
								$_SESSION['data_pdf'],
								array(
									'column' => "docserver_id",
									'value' => $storeResult['docserver_id'],
									'type' => "string",
								)
							);
							array_push(
								$_SESSION['data_pdf'],
								array(
									'column' => "status",
									'value' => 'TRA',
									'type' => "string",
								)
							);
							array_push(
								$_SESSION['data_pdf'],
								array(
									'column' => "offset_doc",
									'value' => ' ',
									'type' => "string",
								)
							);
							array_push(
								$_SESSION['data_pdf'],
								array(
									'column' => "logical_adr",
									'value' => ' ',
									'type' => "string",
								)
							);
							array_push(
								$_SESSION['data_pdf'],
								array(
									'column' => "title",
									'value' => $title,
									'type' => "string",
								)
							);
							array_push(
								$_SESSION['data_pdf'],
								array(
									'column' => "attachment_type",
									'value' => 'converted_pdf',
									'type' => "string",
								)
							);
							array_push(
								$_SESSION['data_pdf'],
								array(
									'column' => "coll_id",
									'value' => $_SESSION['collection_id_choice'],
									'type' => "string",
								)
							);
							array_push(
								$_SESSION['data_pdf'],
								array(
									'column' => "res_id_master",
									'value' => $_SESSION['doc_id'],
									'type' => "integer",
								)
							);
						
							if (isset($_SESSION['upfile']['outgoingMail']) && $_SESSION['upfile']['outgoingMail']){
								array_push(
									$_SESSION['data_pdf'],
									array(
										'column' => "type_id",
										'value' => 1,
										'type' => "int",
									)
								);
							}
							else {
								array_push(
									$_SESSION['data_pdf'],
									array(
										'column' => "type_id",
										'value' => 0,
										'type' => "int",
									)
								);

							}

							array_push(
								$_SESSION['data_pdf'],
								array(
									'column' => "relation",
									'value' => 1,
									'type' => "int",
								)
							);

							$id_up = $resAttach->load_into_db(
								RES_ATTACHMENTS_TABLE,
								$storeResult['destination_dir'],
								substr ($storeResult['file_destination_name'], 0, strrpos  ($storeResult['file_destination_name'], "." )).".pdf" ,
								$storeResult['path_template'],
								$storeResult['docserver_id'], $_SESSION['data_pdf'],
								$_SESSION['config']['databasetype']
							);
							
                            unset($_SESSION['upfile']['fileNamePdfOnTmp']);
                        }
                        
                        if ($id == false) {
                            $_SESSION['error'] = $resAttach->get_error();
                        } else {
                            // Delete temporary backup
                            $db->query("DELETE FROM res_attachments WHERE res_id = ? and status = 'TMP' and typist = ?", 
                                            array($_SESSION['attachmentInfo']['inProgressResId'], $_SESSION['user']['UserId']));
                            unset($_SESSION['attachmentInfo']);
                            unset($_SESSION['resIdVersionAttachment']);
                            unset($_SESSION['targetAttachment']);

                            if ($_SESSION['history']['attachadd'] == "true") {
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
                            }
                        }
                    }
                }
            }
            
            if ( empty($_SESSION['error']) || $_SESSION['error'] == _NEW_ATTACH_ADDED ) {
                $new_nb_attach = 0;
                $stmt = $db->query("select res_id from "
                    . $_SESSION['tablename']['attach_res_attachments']
                    . " where status <> 'DEL' and attachment_type <> 'converted_pdf' and attachment_type <> 'print_folder' and res_id_master = ?", array($_SESSION['doc_id']));
                if ($stmt->rowCount() > 0) {
                    $new_nb_attach = $stmt->rowCount();
                }
                if (isset($_REQUEST['fromDetail']) && $_REQUEST['fromDetail'] == 'create') {
					//Redirection vers bannette MyBasket s'il s'agit d'un courrier spontané et que l'utilisateur connecté est le destinataire du courrier
					if (isset($_SESSION['upfile']['outgoingMail']) && $_SESSION['upfile']['outgoingMail'] && $_SESSION['user']['UserId'] == $_SESSION['details']['diff_list']['dest']['users'][0]['user_id']){
						$js .= "window.parent.top.location.href = 'index.php?page=view_baskets&module=basket&baskets=MyBasket&resid=".$_SESSION['doc_id']."&directLinkToAction';";
					}
					else {
						$js .= "window.parent.top.location.reload()";
					}
                } else {
                    $js .= 'var eleframe1 =  window.parent.top.document.getElementById(\'list_attach\');';
                    $js .= 'eleframe1.src = \''.$_SESSION['config']['businessappurl'].'index.php?display=true&module=attachments&page=frame_list_attachments&attach_type_exclude=converted_pdf,print_folder&load\';';
                    $js .= 'var nb_attach = '. $new_nb_attach.';';
                    $js .= 'window.parent.top.document.getElementById(\'nb_attach\').innerHTML = nb_attach;';
                }
            } else {
                $error = $_SESSION['error'];
                $status = 1;
            }
        }
    } else {
        $error = $_SESSION['error'];
        $status = 1;
    }
    if (!isset($_SESSION['new_id'])) $_SESSION['new_id'] = 0;

    echo "{status : " . $status . ", content : '" . addslashes(_parse($content)) . "', error : '" . addslashes($error) . "', majFrameId : ".functions::xssafe($_SESSION['new_id']).", exec_js : '".addslashes($js)."'}";
    unset($_SESSION['new_id']);
    exit();
} else if (isset($_POST['edit']) && $_POST['edit']) {
    $title = '';

    if (!isset($_REQUEST['title']) || empty($_REQUEST['title'])) {
        $_SESSION['error'] .= _OBJECT . ' ' . _MANDATORY . ". ";
        $status = 1;
    } else {
        $title = $_REQUEST['title'];
        $title = str_replace("&#039;", "'", $title);
    }

    if ($status <> 1) {
        if ($_REQUEST['new_version'] == "yes") {
            $isVersion = 1;
            if ((int)$_REQUEST['relation'] > 1) {
                $column_res = 'res_id_version';
            } else {
                $column_res = 'res_id';
            }
            $stmt = $db->query("SELECT attachment_type, identifier, relation, attachment_id_master 
                                FROM res_view_attachments
                                WHERE ".$column_res." = ? and res_id_master = ?
                                ORDER BY relation desc", array($_REQUEST['res_id'],$_SESSION['doc_id']));
            $previous_attachment = $stmt->fetchObject();

            $fileInfos = array(
                "tmpDir"      => $_SESSION['config']['tmppath'],
                "size"        => $_SESSION['upfile']['size'],
                "format"      => $_SESSION['upfile']['format'],
                "tmpFileName" => $_SESSION['upfile']['fileNameOnTmp'],
            );

            $storeResult = array();
            
            $storeResult = $docserverControler->storeResourceOnDocserver(
                $_SESSION['collection_id_choice'], $fileInfos
            );

            if (isset($storeResult['error']) && $storeResult['error'] <> '') {
                $_SESSION['error'] = $storeResult['error'];
            } else {
                $resAttach = new resource();
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
                        'value' => $_SESSION['upfile']['format'],
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
                if (!empty($_REQUEST['effectiveDateStatus'])) {
                    $_SESSION['data'][] = [
                        'column' => 'status',
                        'value'  => $_REQUEST['effectiveDateStatus'],
                        'type'   => 'string'
                    ];
                } else {
                    array_push(
                        $_SESSION['data'],
                        array(
                            'column' => "status",
                            'value' => 'A_TRA',
                            'type' => "string",
                        )
                    );
                }
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
                        'value' => $previous_attachment->attachment_type,
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
                if ((int)$previous_attachment->attachment_id_master == 0) {
                    array_push(
                        $_SESSION['data'],
                        array(
                            'column' => "attachment_id_master",
                            'value' => $_REQUEST['res_id'],
                            'type' => "integer",
                        )
                    );
                } else {
                    array_push(
                        $_SESSION['data'],
                        array(
                            'column' => "attachment_id_master",
                            'value' => (int)$previous_attachment->attachment_id_master,
                            'type' => "integer",
                        )
                    );                    
                }

                if ($_SESSION['origin'] == "scan") {
                    array_push(
                        $_SESSION['data'],
                        array(
                            'column' => "scan_user",
                            'value' => $_SESSION['user']['UserId'],
                            'type' => "string",
                        )
                    );
                    array_push(
                        $_SESSION['data'],
                        array(
                            'column' => "scan_date",
                            'value' => $req->current_datetime(),
                            'type' => "function",
                        )
                    );
                }
                if (isset($_REQUEST['back_date']) && $_REQUEST['back_date'] <> '') {
                    array_push(
                        $_SESSION['data'],
                        array(
                            'column' => "validation_date",
                            'value' => $func->format_date_db($_REQUEST['back_date']),
                            'type' => "date",
                        )
                    );
                }

                if (!empty($_REQUEST['effectiveDate'])) {
                    $_SESSION['data'][] = [
                        'column' => 'effective_date',
                        'value'  => $func->format_date_db($_REQUEST['effectiveDate']),
                        'type'   => 'date'
                    ];
                }

                if (isset($_REQUEST['contactidAttach']) && $_REQUEST['contactidAttach'] != '' && is_numeric($_REQUEST['contactidAttach'])) {
                        $_SESSION['data'][] = [
                            'column' => 'dest_contact_id',
                            'value' => $_REQUEST['contactidAttach'],
                            'type' => 'integer'
                        ];
                } else if (isset($_REQUEST['contactidAttach']) && $_REQUEST['contactidAttach'] != '' && !is_numeric($_REQUEST['contactidAttach'])) {
                        $_SESSION['data'][] = [
                            'column' => 'dest_user',
                            'value' => $_REQUEST['contactidAttach'],
                            'type' => 'string'
                        ];
                }

                if (isset($_REQUEST['addressidAttach']) && $_REQUEST['addressidAttach'] <> '') {
                    array_push(
                        $_SESSION['data'],
                        array(
                            'column' => "dest_address_id",
                            'value' => $_REQUEST['addressidAttach'],
                            'type' => "integer",
                        )
                    );
                }
                array_push(
                    $_SESSION['data'],
                    array(
                        'column' => "identifier",
                        'value' => $previous_attachment->identifier,
                        'type' => "string",
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

                $relation = (int)$previous_attachment->relation;
                $relation++;

                array_push(
                    $_SESSION['data'],
                    array(
                        'column' => "relation",
                        'value' => $relation,
                        'type' => "int",
                    )
                );

                $id = $resAttach->load_into_db(
                    'res_version_attachments',
                    $storeResult['destination_dir'],
                    $storeResult['file_destination_name'] ,
                    $storeResult['path_template'],
                    $storeResult['docserver_id'], $_SESSION['data'],
                    $_SESSION['config']['databasetype']
                );
                
                //copie de la version PDF de la pièce si mode de conversion sur le client
                if ($_SESSION['modules_loaded']['attachments']['convertPdf'] == true && $_SESSION['upfile']['fileNamePdfOnTmp'] != ''){
					$_SESSION['new_id'] = $id;
                    $file = $_SESSION['config']['tmppath'].$_SESSION['upfile']['fileNamePdfOnTmp'];
                    $newfile = $storeResult['path_template'].str_replace('#',"/",$storeResult['destination_dir']).substr ($storeResult['file_destination_name'], 0, strrpos  ($storeResult['file_destination_name'], "." )).".pdf";
                    
                    copy($file, $newfile);
                    $_SESSION['data_pdf'] = array();
							
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "typist",
							'value' => $_SESSION['user']['UserId'],
							'type' => "string",
						)
					);
					
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "format",
							'value' => 'pdf',
							'type' => "string",
						)
					);
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "docserver_id",
							'value' => $storeResult['docserver_id'],
							'type' => "string",
						)
					);
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "status",
							'value' => 'TRA',
							'type' => "string",
						)
					);
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "offset_doc",
							'value' => ' ',
							'type' => "string",
						)
					);
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "logical_adr",
							'value' => ' ',
							'type' => "string",
						)
					);
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "title",
							'value' => $title,
							'type' => "string",
						)
					);
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "attachment_type",
							'value' => 'converted_pdf',
							'type' => "string",
						)
					);
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "coll_id",
							'value' => $_SESSION['collection_id_choice'],
							'type' => "string",
						)
					);
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "res_id_master",
							'value' => $_SESSION['doc_id'],
							'type' => "integer",
						)
					);
					$old_pdf_id = $ac->getCorrespondingPdf($_REQUEST['res_id']);
					if (isset($old_pdf_id) && $old_pdf_id != 0) $infos_old_pdf = $ac->getAttachmentInfos($old_pdf_id);
					if ((isset($_SESSION['upfile']['outgoingMail']) && $_SESSION['upfile']['outgoingMail']) || ($infos_old_pdf['type_id']==1)){
						array_push(
							$_SESSION['data_pdf'],
							array(
								'column' => "type_id",
								'value' => 1,
								'type' => "int",
							)
						);
					}
					else {
						array_push(
							$_SESSION['data_pdf'],
							array(
								'column' => "type_id",
								'value' => 0,
								'type' => "int",
							)
						);

					}

					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "relation",
							'value' => 1,
							'type' => "int",
						)
					);

					$id_up = $resAttach->load_into_db(
						RES_ATTACHMENTS_TABLE,
						$storeResult['destination_dir'],
						substr ($storeResult['file_destination_name'], 0, strrpos  ($storeResult['file_destination_name'], "." )).".pdf" ,
						$storeResult['path_template'],
						$storeResult['docserver_id'], $_SESSION['data_pdf'],
						$_SESSION['config']['databasetype']
					);
                    unset($_SESSION['upfile']['fileNamePdfOnTmp']);
                }
                
                if ($previous_attachment->relation == 1) {
					$pdf_id = $ac->getCorrespondingPdf($_REQUEST['res_id']);
					if (isset($pdf_id) && $pdf_id != 0) $stmt = $db->query("UPDATE res_attachments SET status = 'OBS' WHERE res_id = ?", array($pdf_id) );
                    $stmt = $db->query("UPDATE res_attachments SET status = 'OBS' WHERE res_id = ?",array($_REQUEST['res_id']));
                } else {
					$pdf_id = $ac->getCorrespondingPdf($_REQUEST['res_id']);
					if (isset($pdf_id) && $pdf_id != 0) $stmt = $db->query("UPDATE res_attachments SET status = 'OBS' WHERE res_id = ?", array($pdf_id) );
                    $stmt = $db->query("UPDATE res_version_attachments SET status = 'OBS' WHERE res_id = ?",array($_REQUEST['res_id']));
                }

            }
        } else {
            //UPDATE VERSION
            $isVersion = 0;
            $set_update = "";
            $arrayPDO = array();

            $set_update = " title = :title";
            $arrayPDO = array_merge($arrayPDO, array(":title" => $title));

            if (isset($_REQUEST['back_date']) && $_REQUEST['back_date'] <> "") {
                $set_update .= ", validation_date = '".$req->format_date_db($_REQUEST['back_date'])."'";
            } else {
                $set_update .= ", validation_date = null";
            }

            if (!empty($_REQUEST['effectiveDate'])) {
                $set_update .= ", effective_date = '".$req->format_date_db($_REQUEST['effectiveDate'])."'";
            }

            if (isset($_REQUEST['contactidAttach']) && $_REQUEST['contactidAttach'] != '' && is_numeric($_REQUEST['contactidAttach'])) {
                $set_update .= ", dest_user = null, dest_contact_id = ".$_REQUEST['contactidAttach'].", dest_address_id = ".$_REQUEST['addressidAttach'];
            } else if (isset($_REQUEST['contactidAttach']) && $_REQUEST['contactidAttach'] != '' && !is_numeric($_REQUEST['contactidAttach'])) {
                $set_update .= ", dest_user = '".$_REQUEST['contactidAttach']."', dest_contact_id = null, dest_address_id = null";
            } else {
                $set_update .= ", dest_user = null, dest_contact_id = null, dest_address_id = null";
            }

            if ((int)$_REQUEST['relation'] > 1) {
                $column_res = 'res_id_version';
            } else {
                $column_res = 'res_id';
            }

            $stmt = $db->query("SELECT fingerprint FROM res_view_attachments WHERE ".$column_res." = ? and res_id_master = ? and status <> 'OBS'"
                                , array($_REQUEST['res_id'], $_SESSION['doc_id']));
            $res = $stmt->fetchObject();

            require_once 'core/class/docserver_types_controler.php';
            require_once 'core/docservers_tools.php';
            $docserverTypeControler = new docserver_types_controler();
            $docserverInfo = $docserverControler->getDocserverToInsert($collId);
            $docserver = $docserverControler->get($docserverInfo->docserver_id);
            $docserverTypeObject = $docserverTypeControler->get($docserver->docserver_type_id);
            $NewHash = Ds_doFingerprint($_SESSION['upfile']['tmp_name'], $docserverTypeObject->fingerprint_mode);
            $OriginalHash = $res->fingerprint;

            if ($_SESSION['upfile']['upAttachment'] && $OriginalHash <> $NewHash) {
                $fileInfos = array(
                    "tmpDir"      => $_SESSION['config']['tmppath'],
                    "size"        => $_SESSION['upfile']['size'],
                    "format"      => $_SESSION['upfile']['format'],
                    "tmpFileName" => $_SESSION['upfile']['fileNameOnTmp'],
                );

                $storeResult = array();
                $storeResult = $docserverControler->storeResourceOnDocserver(
                    $_SESSION['collection_id_choice'], $fileInfos
                );

                $filetmp = $storeResult['path_template'];
                $tmp = $storeResult['destination_dir'];
                $tmp = str_replace('#',DIRECTORY_SEPARATOR,$tmp);
                $filetmp .= $tmp;
                $filetmp .= $storeResult['file_destination_name'];
                require_once 'core/class/docserver_types_controler.php';
                require_once 'core/docservers_tools.php';
                $docserverTypeControler = new docserver_types_controler();
                $docserver = $docserverControler->get($storeResult['docserver_id']);
                $docserverTypeObject = $docserverTypeControler->get($docserver->docserver_type_id);
                $fingerprint = Ds_doFingerprint($filetmp, $docserverTypeObject->fingerprint_mode);
                $filesize = filesize($filetmp);
                $set_update .= ", fingerprint = :fingerprint";
                $set_update .= ", filesize = :filesize";
                $set_update .= ", path = :path";
                $set_update .= ", filename = :filename";
                $arrayPDO = array_merge($arrayPDO, 
                        array(  ":fingerprint" => $fingerprint, 
                                ":filesize" => $filesize, 
                                ":path" => $storeResult['destination_dir'],
                                ":filename" => $storeResult['file_destination_name'])
                        );
                // $set_update .= ", docserver_id = ".$storeResult['docserver_id'];
                
                //copie de la version PDF de la pièce si mode de conversion sur le client
                if ($_SESSION['modules_loaded']['attachments']['convertPdf'] == true && $_SESSION['upfile']['fileNamePdfOnTmp'] != ''){
					 $_SESSION['new_id'] = $id;
                    $file = $_SESSION['config']['tmppath'].$_SESSION['upfile']['fileNamePdfOnTmp'];
                    $newfile = $storeResult['path_template'].str_replace('#',"/",$storeResult['destination_dir']).substr ($storeResult['file_destination_name'], 0, strrpos  ($storeResult['file_destination_name'], "." )).".pdf";
                    
                    copy($file, $newfile);
                   
					$_SESSION['data_pdf'] = array();
							
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "typist",
							'value' => $_SESSION['user']['UserId'],
							'type' => "string",
						)
					);
					
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "format",
							'value' => 'pdf',
							'type' => "string",
						)
					);
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "docserver_id",
							'value' => $storeResult['docserver_id'],
							'type' => "string",
						)
					);
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "status",
							'value' => 'TRA',
							'type' => "string",
						)
					);
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "offset_doc",
							'value' => ' ',
							'type' => "string",
						)
					);
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "logical_adr",
							'value' => ' ',
							'type' => "string",
						)
					);
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "title",
							'value' => $title,
							'type' => "string",
						)
					);
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "attachment_type",
							'value' => 'converted_pdf',
							'type' => "string",
						)
					);
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "coll_id",
							'value' => $_SESSION['collection_id_choice'],
							'type' => "string",
						)
					);
					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "res_id_master",
							'value' => $_SESSION['doc_id'],
							'type' => "integer",
						)
					);
					
					$old_pdf_id = $ac->getCorrespondingPdf($_REQUEST['res_id']);
					if (isset($old_pdf_id) && $old_pdf_id != 0) $infos_old_pdf = $ac->getAttachmentInfos($old_pdf_id);
					if ((isset($_SESSION['upfile']['outgoingMail']) && $_SESSION['upfile']['outgoingMail']) || ($infos_old_pdf['type_id']==1)){
						array_push(
							$_SESSION['data_pdf'],
							array(
								'column' => "type_id",
								'value' => 1,
								'type' => "int",
							)
						);
					}
					else {
						array_push(
							$_SESSION['data_pdf'],
							array(
								'column' => "type_id",
								'value' => 0,
								'type' => "int",
							)
						);

					}

					array_push(
						$_SESSION['data_pdf'],
						array(
							'column' => "relation",
							'value' => 1,
							'type' => "int",
						)
					);
					$resAttach = new resource();
					$id_up = $resAttach->load_into_db(
						RES_ATTACHMENTS_TABLE,
						$storeResult['destination_dir'],
						substr ($storeResult['file_destination_name'], 0, strrpos  ($storeResult['file_destination_name'], "." )).".pdf" ,
						$storeResult['path_template'],
						$storeResult['docserver_id'], $_SESSION['data_pdf'],
						$_SESSION['config']['databasetype']
					);
                }
            }

            $set_update .= ", doc_date = ".$req->current_datetime().", updated_by = :updated_by";
            if (!empty($_REQUEST['effectiveDateStatus'])) {
                $set_update .= ", status = :effectiveStatus";
                $arrayPDO = array_merge($arrayPDO, array(":effectiveStatus" => $_REQUEST['effectiveDateStatus']));
            } else {
                $set_update .= ", status = 'A_TRA'";
            }
            $arrayPDO = array_merge($arrayPDO, array(":updated_by" => $_SESSION['user']['UserId']));

            if (isset($storeResult['error']) && $storeResult['error'] <> '') {
                $_SESSION['error'] = $storeResult['error'];
            } else {
                $arrayPDO = array_merge($arrayPDO, array(":res_id" => $_REQUEST['res_id']));
                if ((int)$_REQUEST['relation'] == 1) {
					$pdf_id = $ac->getCorrespondingPdf($_REQUEST['res_id']);
					if (isset($pdf_id) && $pdf_id != 0 && (!empty($_SESSION['upfile']['fileNamePdfOnTmp']))){
                        $stmt = $db->query("UPDATE res_attachments SET status = 'DEL' WHERE res_id = ?", array($pdf_id) );
                    }
                    $stmt = $db->query("UPDATE res_attachments SET " . $set_update . " WHERE res_id = :res_id", $arrayPDO);
                } else {
					$pdf_id = $ac->getCorrespondingPdf($_REQUEST['res_id']);
					if (isset($pdf_id) && $pdf_id != 0 && (!empty($_SESSION['upfile']['fileNamePdfOnTmp']))){
                        $stmt = $db->query("UPDATE res_attachments SET status = 'OBS' WHERE res_id = ?", array($pdf_id) );
                    }
                    $stmt = $db->query("UPDATE res_version_attachments SET " . $set_update . " WHERE res_id = :res_id", $arrayPDO);
                }
            }
            unset($_SESSION['upfile']['fileNamePdfOnTmp']);
        }

        // Delete temporary backup
        $stmt = $db->query("SELECT attachment_id_master 
                            FROM res_version_attachments
                            WHERE res_id = ? and status = 'TMP' and res_id_master = ?" 
                            , array($_SESSION['attachmentInfo']['inProgressResId'], $_SESSION['doc_id']));
        $previous_attachment = $stmt->fetchObject();

        $db->query("DELETE FROM res_version_attachments WHERE attachment_id_master = ? and status = 'TMP'", array($previous_attachment->attachment_id_master));
        unset($_SESSION['attachmentInfo']);
        unset($_SESSION['resIdVersionAttachment']);
        unset($_SESSION['targetAttachment']);

        // Add in history
        if ($_SESSION['history']['attachup'] == "true") {
            $hist = new history();
            $view = $sec->retrieve_view_from_coll_id(
                $_SESSION['collection_id_choice']
            );
            $hist->add(
                $view, $_SESSION['doc_id'], "UP", 'attachup',
                ucfirst(_DOC_NUM) . $id . ' '
                . _ATTACH_UPDATED,
                $_SESSION['config']['databasetype'],
                'apps'
            );
            $_SESSION['info'] = _ATTACH_UPDATED;
            $hist->add(
                RES_ATTACHMENTS_TABLE, $id, "UP",'attachup',
                $_SESSION['info'] . " (" . $title
                . ") ",
                $_SESSION['config']['databasetype'],
                'attachments'
            );
        }

        if (empty($_SESSION['error'])) {
            $js .= 'var eleframe1 =  window.top.document.getElementsByName(\'list_attach\');';
            if (isset($_REQUEST['fromDetail']) && $_REQUEST['fromDetail'] == 'attachments') {
                $js .= 'eleframe1[0].src = \''.$_SESSION['config']['businessappurl'].'index.php?display=true&module=attachments&page=frame_list_attachments&load';
                $js .= '&attach_type_exclude=response_project,signed_response,outgoing_mail_signed,converted_pdf,outgoing_mail,print_folder,aihp&fromDetail=attachments';
            } else if (isset($_REQUEST['fromDetail']) && $_REQUEST['fromDetail'] == 'response'){
                $js .= 'eleframe1[1].src = \''.$_SESSION['config']['businessappurl'].'index.php?display=true&module=attachments&page=frame_list_attachments&load';
                $js .= '&attach_type=response_project,outgoing_mail_signed,signed_response,outgoing_mail,aihp&fromDetail=response';
            } else {
                $js .= 'eleframe1[0].src = \''.$_SESSION['config']['businessappurl'].'index.php?display=true&module=attachments&page=frame_list_attachments&attach_type_exclude=converted_pdf,print_folder&load';
            }
            $js .='\';';
        } else {
            $error = $_SESSION['error'];
            $status = 1;
        }

    } else {
        $error = $_SESSION['error'];
        $status = 1;
    }

    if (!isset($_SESSION['new_id'])) $_SESSION['new_id'] = 0;
    echo "{status : " . $status . ", content : '" . addslashes(_parse($content)) . "', title : '" . addslashes($title) . "', isVersion : " . $isVersion . ", error : '" . addslashes($error) . "', majFrameId : ".$_SESSION['new_id'].", exec_js : '".addslashes($js)."', cur_id : ".$_REQUEST['res_id']."}";
    unset($_SESSION['new_id']);
    exit();
}

if (isset($_REQUEST['id'])) {

    $_SESSION['targetAttachment'] = 'edit';

    if ((int)$_REQUEST['relation'] > 1) {
        $column_res = 'res_id_version';
    } else {
        $column_res = 'res_id';
    }
    
    $stmt = $db->query("SELECT validation_date, effective_date, attachment_type, title, dest_user, dest_contact_id, dest_address_id, dest_address_id as address_id, relation, format, identifier, status
                        FROM res_view_attachments 
                        WHERE ".$column_res." = ? and res_id_master = ?
                        ORDER BY relation desc", array($_REQUEST['id'], $_SESSION['doc_id']));
    $data_attachment = $stmt->fetchObject();
    $attachmentFormat = $data_attachment->format;
    $statusEditAttachment = $data_attachment->status;
    //var_dump($data_attachment);
    if ($data_attachment->relation == 1) {
        $res_table = 'res_attachments';
    } else {
        $res_table = 'res_version_attachments';  
    }

    $viewResourceArr = $docserverControler->viewResource(
        $_REQUEST['id'], 
        $res_table, 
        'adr_x', 
        false
    );

    $_SESSION['upfile']['size']          = filesize($viewResourceArr['file_path']);
    $_SESSION['upfile']['format']        = $viewResourceArr['ext'];
    $fileNameOnTmp                       = str_replace($viewResourceArr['tmp_path'].DIRECTORY_SEPARATOR, '', $viewResourceArr['file_path']);
    $_SESSION['upfile']['fileNameOnTmp'] = $fileNameOnTmp;

} else {
    $_SESSION['targetAttachment'] = 'add';

    $stmt = $db->query("SELECT subject, exp_contact_id, dest_contact_id, exp_user_id, address_id, dest_user_id, alt_identifier FROM res_view_letterbox WHERE res_id = ?",array($_SESSION['doc_id']));
    $data_attachment = $stmt->fetchObject();

    unset($_SESSION['upfile']);
    unset($_SESSION['upfileTransmission']);

}

$stmt = $db->query('SELECT creation_date FROM res_letterbox WHERE res_id = ?', [$_SESSION['doc_id']]);
$dataForDate = $stmt->fetchObject();

if ($data_attachment->dest_contact_id <> "") {
    $stmt = $db->query('SELECT is_corporate_person, is_private, contact_lastname, contact_firstname, society, society_short, address_num, address_street, address_town, lastname, firstname 
                        FROM view_contacts 
                        WHERE contact_id = ? and ca_id = ?', array($data_attachment->dest_contact_id,$data_attachment->address_id));
} else if ($data_attachment->exp_contact_id <> "") {
    $stmt = $db->query('SELECT is_corporate_person, is_private, contact_lastname, contact_firstname, society, society_short, address_num, address_street, address_town, lastname, firstname 
                        FROM view_contacts 
                        WHERE contact_id = ? and ca_id = ?', array($data_attachment->exp_contact_id,$data_attachment->address_id));       
} else if ($data_attachment->dest_user != '') {
    $stmt = $db->query('SELECT lastname, firstname FROM users WHERE user_id = ?', [$data_attachment->dest_user]);
} else if ($data_attachment->exp_user_id != '') {
    $stmt = $db->query('SELECT lastname, firstname FROM users WHERE user_id = ?', [$data_attachment->exp_user_id]);
} else if ($data_attachment->dest_user_id != '') {
    $stmt = $db->query('SELECT lastname, firstname FROM users WHERE user_id = ?', [$data_attachment->dest_user_id]);
}

if ($data_attachment->exp_contact_id <> '' || $data_attachment->dest_contact_id <> '') {
    $res = $stmt->fetchObject();
    if ($res->is_corporate_person == 'Y') {
        $data_contact = $res->society;
        if (!empty ($res->society_short)) {
            $data_contact .= ' ('.$res->society_short.')';
        }
        if (!empty($res->lastname) || !empty($res->firstname)) {
            $data_contact .= ' - ' . $res->lastname . ' ' . $res->firstname;
        }
        $data_contact .= ', ';
    } else {
        $data_contact .= $res->contact_lastname . ' ' . $res->contact_firstname;
        if (!empty ($res->society)) {
            $data_contact .= ' (' .$res->society . ')';
        }
        $data_contact .= ', ';
    }
    if ($res->is_private == 'Y') {
        $data_contact .= '(' . _CONFIDENTIAL_ADDRESS . ')';
    } else {
        $data_contact .= $res->address_num . ' ' . $res->address_street . ' ' . strtoupper($res->address_town);
    }
} else if ($data_attachment->exp_user_id != '' || $data_attachment->dest_user != '' || $data_attachment->dest_user_id != '') {
    $res = $stmt->fetchObject();
    if (!empty($res->lastname) || !empty($res->firstname)) {
        $data_contact .= $res->lastname . ' ' . $res->firstname;
    }
//si multicontact
} else {
    $stmt = $db->query("SELECT cr.address_id, c.contact_id, c.is_corporate_person, c.society, c.society_short, c.firstname, c.lastname,ca.is_private,ca.address_street, ca.address_num, ca.address_town 
                        FROM contacts_res cr, contacts_v2 c, contact_addresses ca 
                        WHERE cr.res_id = ? and cast(c.contact_id as char) = cast(cr.contact_id as char) and ca.contact_id=c.contact_id and ca.id=cr.address_id",array($_SESSION['doc_id']));
    $i=0;
    while($multi_contacts_attachment = $stmt->fetchObject()){
        if(is_integer($multi_contacts_attachment->contact_id)){
            $format_contact='';
            $stmt2 = $db->query('SELECT is_corporate_person, is_private, contact_lastname, contact_firstname, society, society_short, address_num, address_street, address_town, lastname, firstname 
                            FROM view_contacts 
                            WHERE contact_id = ? and ca_id = ?',array($multi_contacts_attachment->contact_id,$multi_contacts_attachment->address_id));

            $res = $stmt2->fetchObject();
            if ($res->is_corporate_person == 'Y') {
                $format_contact = $res->society;
                if (!empty ($res->society_short)) {
                    $format_contact .= ' ('.$res->society_short.')';
                }
                if (!empty($res->lastname) || !empty($res->firstname)) {
                    $format_contact .= ' - ' . $res->lastname . ' ' . $res->firstname;
                }
                $format_contact .= ', ';
            } else {
                $format_contact .= $res->contact_lastname . ' ' . $res->contact_firstname;
                if (!empty ($res->society)) {
                    $format_contact .= ' (' .$res->society . ')';
                }
                $format_contact .= ', ';
            }
            if ($res->is_private == 'Y') {
                $format_contact .= '('._CONFIDENTIAL_ADDRESS.')';
            } else {
                $format_contact .= $res->address_num .' ' . $res->address_street .' ' . strtoupper($res->address_town);                         
            }
            $contacts[] = array(
                'contact_id'     => $multi_contacts_attachment->contact_id,
                'firstname'      => $multi_contacts_attachment->firstname,
                'lastname'       => $multi_contacts_attachment->lastname,
                'society'        => $multi_contacts_attachment->society,
                'address_id'     => $multi_contacts_attachment->address_id,
                'format_contact' => $format_contact
            );

            if($i==0){
                $data_contact                    = $format_contact; 
                $data_attachment->exp_contact_id = $multi_contacts_attachment->contact_id;
            }
            $i++;
        } 
    }
}

unset($_SESSION['transmissionContacts']);
$content .= '<div class="error" style="left:10px;" id="divErrorAttachment" onClick="this.style.display=\'none\'">' . $_SESSION['error'];

$_SESSION['error'] = "";

$objectTable = $sec->retrieve_table_from_coll($_SESSION['collection_id_choice']);
$content .= '</div>';
$content .= '<div class="info" style="left:10px;" id="divInfoAttachment" onClick="this.style.display=\'none\'">' . $_SESSION['info'].'</div>';
if (isset($_REQUEST['id'])) {
    $title = _MODIFY_ANSWER;
} else {
    $title = _ATTACH_ANSWER;
}

$content .= '<h2>&nbsp;' . $title;

//multicontact
if (!empty($contacts)) {
    $content .= ' pour le contact : <select style="background-color: #FFF;border: 1px solid #999;color: #666;text-align: left;" id="selectContactIdRes" onchange="loadSelectedContact()">';

    foreach ($contacts as $key => $value) {
        $content .= '<option value="'.$value['contact_id'].'#'.$value['address_id'].'#'.$value['format_contact'].'">'.$value['format_contact'].'</option>';
        //$content .= '<input type="hidden" id="format_list_contact_'.$value['contact_id'].'_res" value="'.$value['format_contact'].'"/>';
    }
    $content .= '</select>';
    $content .= '<script>$("contactidAttach").value='.$contacts[0]['contact_id'].';$("addressidAttach").value='.$contacts[0]['address_id'].';launch_autocompleter2_contacts_v2("'. $_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=autocomplete_contacts", "contact_attach", "show_contacts_attach", "", "contactidAttach", "addressidAttach")</script>';
}

$content .= '</h2>';

$content .= '<form enctype="multipart/form-data" method="post" name="formAttachment" id="formAttachment" action="#" class="forms" style="width:500px;float:left;margin-left:-5px;background-color:#deedf3">';
$content .= '<div class="transmissionDiv">';
$content .= '<hr style="width:85%;margin-left:0px">';
$content .= '<input type="hidden" id="category_id" value="outgoing"/>';
if (isset($_REQUEST['id'])) {
    $content .= '<input type="hidden" name="res_id" id="res_id" value="'.$_REQUEST['id'].'"/>';
    $content .= '<input type="hidden" name="relation" id="relation" value="'.$_REQUEST['relation'].'"/>';
    $_SESSION['relationAttachment'] = $_REQUEST['relation'];
    $_SESSION['resIdVersionAttachment'] = $_REQUEST['id'];
}
$content .= '<input type="hidden" name="fromDetail" id="fromDetail" value="'.$_REQUEST['fromDetail'].'"/>';

if (!isset($_REQUEST['id'])) {
    if(isset($_SESSION['attachment_types']['transmission'])){
        $function_transmssion = 'disableTransmissionButton(this.options[this.selectedIndex].value);';
        $function_transmssion2 = 'showOrButtonForAttachment();';
    }else{
        $function_transmssion = '';
        $function_transmssion2 = '';
    }
    //On recherche le type de document attaché à ce courrier
    //var_dump($_SESSION['doc_id']);
    $stmt = $db->query("SELECT type_id FROM res_letterbox WHERE res_id = ?",array($_SESSION['doc_id']));
    $type_id = $stmt->fetchObject();
    //var_dump ($type_id->type_id);
    $type_id = $type_id->type_id;
    //On recherche le sve_type
    $stmt = $db->query("SELECT * FROM mlb_doctype_ext WHERE type_id = ?",array($type_id));
    $sve = $stmt->fetchObject();
    $sve_type = $sve->process_mode;
    //On met tous les attachments ayant le type_sve attaché au courrier dans un tableau
    $attachments_types_for_process = array();
    foreach($_SESSION['attachment_types_with_process'] as $key => $value){
        // var_dump($key);
        // var_dump($value);
        if($sve_type == $value or $value == ''){
            //var_dump($_SESSION['attachment_types'][$key]);
            //$attachments_types_for_process[] = $key;
            //$attachments_types_for_process = array($key => $attachments_types_for_process);
            $attachments_types_for_process[$key] = $_SESSION['attachment_types'][$key];

        }

    }
    //var_dump($attachments_types_for_process);
    //var_dump($_SESSION['attachment_types']);

    $content .= '<p>';
    $content .= '<label>' . _ATTACHMENT_TYPES . '</label>';
    $content .= '<select name="attachment_types" id="attachment_types" onchange="affiche_chrono();'.$function_transmssion.'select_template(\'' . $_SESSION['config']['businessappurl']
        . 'index.php?display=true&module=templates&page='
        . 'select_templates\', this.options[this.selectedIndex].value);"/>';
    $content .= '<option value="">' . _CHOOSE_ATTACHMENT_TYPE . '</option>';
    

    foreach(array_keys($attachments_types_for_process) as $attachmentType) {
        if(empty($_SESSION['attachment_types_get_chrono'][$attachmentType][0])){
            $_SESSION['attachment_types_get_chrono'][$attachmentType] = '';
        }
        if($_SESSION['attachment_types_show'][$attachmentType] == "true"){
            $content .= '<option value="' . $attachmentType . '" with_chrono = "'. $_SESSION['attachment_types_with_chrono'][$attachmentType].'" get_chrono = "'. $_SESSION['attachment_types_get_chrono'][$attachmentType].'"';

            if(isset($_GET['cat']) && $_GET['cat'] == 'outgoing' && $attachmentType == 'outgoing_mail'){
                $content .= ' selected = "selected"';
                $content .= '<script>$("attachment_types").onchange();</script>';
            }
            $content .= '>';
            $content .= $_SESSION['attachment_types'][$attachmentType];
            $content .= '</option>';
        }
    }

    $content .= '</select>&nbsp;<span class="red_asterisk" id="attachment_types_mandatory"><i class="fa fa-star"></i></span>';
    $content .= '</p>';
    $content .= '<p>';
    $content .= '<label id="chrono_label" style="display:none">'. _CHRONO_NUMBER.'</label>';
    $content .= '<input type="text" name="chrono_display" id="chrono_display" style="display:none" disabled class="readonly"/>';
    $content .= '<select name="get_chrono_display" id="get_chrono_display" style="display:none" onchange="$(\'chrono\').value=this.options[this.selectedIndex].value"/>';
    $content .= '<input type="hidden" name="chrono" id="chrono" value="' . $data_attachment->alt_identifier . '"/>';
    $content .= '</p>';
    $content .= '<p style="text-align:left;margin-left:74.5%;"></p>';
    $content .= '<p>';
    $content .= '<label>'. _FILE.' <span><i class="fa fa-paperclip fa-lg" title="'._LOADED_FILE.'" style="cursor:pointer;" id="attachment_type_icon" onclick="$(\'attachment_type_icon\').setStyle({color: \'#009DC5\'});$(\'attachment_type_icon2\').setStyle({color: \'#666\'});$(\'templateOffice\').setStyle({display: \'none\'});$(\'templateOffice\').disabled=true;$(\'edit\').setStyle({display: \'none\'});$(\'choose_file\').setStyle({display: \'inline-block\'});document.getElementById(\'choose_file\').contentDocument.getElementById(\'file\').click();"></i> <i class="fa fa-file-text-o fa-lg" title="'._GENERATED_FILE.'" style="cursor:pointer;color:#009DC5;" id="attachment_type_icon2" onclick="$(\'attachment_type_icon2\').setStyle({color: \'#009DC5\'});$(\'attachment_type_icon\').setStyle({color: \'#666\'});$(\'templateOffice\').setStyle({display: \'inline-block\'});$(\'templateOffice\').disabled=false;$(\'choose_file\').setStyle({display: \'none\'});"></i></span></label>';
    $content .= '<select name="templateOffice" id="templateOffice" style="display:inline-block;" onchange="showEditButton();'.$function_transmssion2.'">';
    $content .= '<option value="">'. _CHOOSE_MODEL.'</option>';

    $content .= '</select>';
    $content .= '<iframe style="display:none; width:210px" name="choose_file" id="choose_file" frameborder="0" scrolling="no" height="25" src="' . $_SESSION['config']['businessappurl']
        . 'index.php?display=true&module=attachments&page=choose_attachment"></iframe>';

    $content .='&nbsp;<span class="red_asterisk" id="templateOffice_mandatory"><i class="fa fa-star"></i></span>';
    
    $content .= '</p>';
}

if (isset($statusEditAttachment) && $statusEditAttachment == 'TMP') {
    $content .= '<p align="middle"><span style="color:green">'._RETRIEVE_BACK_UP.'</span></p>';
}
if (isset($_REQUEST['id'])) {
    $content .= '<p>';
    $content .= '<label id="chrono_label" >'. _ATTACHMENT_TYPES.'</label>';
    $content .= '<input type="text" name="attachment_type" id="attachment_type" value="' . $_SESSION['attachment_types'][$data_attachment->attachment_type] . '" disabled class="readonly"/>';
    $content .= '</p>';
    $content .= '<p>';
    $content .= '<label id="chrono_label" >'. _CHRONO_NUMBER.'</label>';
    $content .= '<input type="text" name="chrono_display" id="chrono_display" value="' . $data_attachment->identifier . '" disabled class="readonly"/>';
    $content .= '</p>';
}
$content .= '<p>';
$content .= '<label>'. _OBJECT .'</label>';
$content .= '<input type="text" name="title" id="title" maxlength="250" value="';
if (isset($_REQUEST['id'])) {
    $content .= str_replace('"', '&quot;', $data_attachment->title);
} else {
    $content .= $req->show_string(substr($data_attachment->subject, 0, 250));
}
$content .= '"/>&nbsp;<span class="red_asterisk" id="templateOffice_mandatory"><i class="fa fa-star"></i></span>';
$content .= '</p>';
$content .= '<p>';
$content .= '<label>'. _BACK_DATE.'</label>';
if (isset($_REQUEST['id'])) {
    $content .= '<input type="text" name="back_date" id="back_date" onClick="showCalender(this);" onfocus="checkBackDate(this)" value="';
    $content .= $req->format_date_db($data_attachment->validation_date);
    $content .= '"/>';
    $content .= '</p>';
    if ($data_attachment->attachment_type == 'transmission' && ($data_attachment->status == "RTURN" || $data_attachment->status == "EXP_RTURN")) {
        $content .= '<p>';
        $content .= '<label>'. "Date de retour effective".'</label>';
        $content .= '<input type="text" name="effectiveDate" id="effectiveDate" onblur="setRturnForEffectiveDate()" onClick="showCalender(this);" onfocus="checkBackDate(this)" style="width: 75px" value="';
        $content .= $req->format_date_db($data_attachment->effective_date);
        $content .= '" />';
        $content .= '<select name="effectiveDateStatus" id="effectiveDateStatus" style="margin-left: 20px;width: 105px" />';
        $content .= '<option value="EXP_RTURN">Attente retour</option>';
        if ($data_attachment->status == "RTURN")
            $content .= '<option selected="selected" value="RTURN">Retourné</option>';
        else
            $content .= '<option value="RTURN">Retourné</option>';
        $content .= '</select>';
    }
} else {
    $content .= '<input type="text" name="back_date" id="back_date" onClick="showCalender(this);" onfocus="checkBackDate(this)" value="" />';
}
$content .= "<input type='hidden' name='dataCreationDate' id='dataCreationDate' value='{$dataForDate->creation_date}' />";
$content .= '</p>';
$content .= '<div>';
$content .= '<label>'. _DEST_USER_PJ;
if ($core->test_admin('my_contacts', 'apps', false)) {
    $content .= ' <a href="#" id="create_multi_contact" title="' . _CREATE_CONTACT
            . '" onclick="new Effect.toggle(\'create_contact_div_attach\', '
            . '\'blind\', {delay:0.2});return false;" '
            . 'style="display:inline;" ><i class="fa fa-pencil fa-lg" title="' . _CREATE_CONTACT . '"></i></a>';
}
$content .= '</label>';
$content .= '<span style="position:relative;"><input type="text" name="contact_attach" onblur="display_contact_card(\'visible\', \'contact_card_attach\');" onkeyup="erase_contact_external_id(\'contact_attach\', \'contactidAttach\');erase_contact_external_id(\'contact_attach\', \'addressidAttach\');" id="contact_attach" value="';
$content .= $data_contact;
$content .= '"/><div id="show_contacts_attach" class="autocomplete autocompleteIndex" style="width: 100%;left: 0px;"></div><div class="autocomplete autocompleteIndex" id="searching_autocomplete" style="display: none;text-align:left;padding:5px;width: 100%;left: 0px;"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> chargement ...</div></span>';
$content .='<a href="#" id="contact_card_attach" title="'._CONTACT_CARD.'" onclick="document.getElementById(\'info_contact_iframe_attach\').src=\'' . $_SESSION['config']['businessappurl']
    . 'index.php?display=false&dir=my_contacts&page=info_contact_iframe&seeAllAddresses&contactid=\'+document.getElementById(\'contactidAttach\').value+\'&addressid=\'+document.getElementById(\'addressidAttach\').value+\'\';new Effect.toggle(\'info_contact_div_attach\', '
    . '\'blind\', {delay:0.2});return false;"'
    . ' style="visibility:hidden;"> <i class="fa fa-book fa-lg"></i></a>';
$content .= '</div>';
$content .= '<input type="hidden" id="contactidAttach" name="contactidAttach" value="';
if (isset($_REQUEST['id']) && !empty($data_attachment->dest_contact_id)) {
    $content .= $data_attachment->dest_contact_id;
} else if (isset($_REQUEST['id']) && !empty($data_attachment->dest_user)){
    $content .= $data_attachment->dest_user;
} else if ($data_attachment->exp_contact_id){
    $content .= $data_attachment->exp_contact_id;
} else if ($data_attachment->dest_contact_id) {
    $content .= $data_attachment->dest_contact_id;
} else if ($data_attachment->exp_user_id) {
    $content .= $data_attachment->exp_user_id;
} else if ($data_attachment->dest_user_id) {
    $content .= $data_attachment->dest_user_id;
}

$content .= '"/>';
$content .= '<input type="hidden" id="addressidAttach" name="addressidAttach" value="';

if (isset($_REQUEST['id'])) {
    $content .= $data_attachment->dest_address_id;
} else if ($data_attachment->address_id <> ''){
    $content .= $data_attachment->address_id;
}

$content .= '"/>';

$langArrayForTransmission = [
    _ATTACHMENT_TYPES,
    _CHRONO_NUMBER,
    _FILE,
    _OBJECT,
    _BACK_DATE,
    _DEST_USER_PJ,
    _EDIT_MODEL,
    _CREATE_CONTACT
];
$canCreateContact = $core->test_admin('my_contacts', 'apps', false);
if (!$canCreateContact)
    $canCreateContact = 0;

if(!isset($_REQUEST['id'])) {
    $content .= '<p><div style="float: left;margin-bottom: 5px">';
    $content .= '<input type="button" value="';
    $content .= _EDIT_MODEL;
    $content .= '" name="edit" id="edit" style="display:none;margin-top: 0" class="button" '
        . 'onclick="window.open(\'' . $_SESSION['config']['businessappurl'] . 'index.php?display=true&module=content_management&page=applet_popup_launcher&objectType=attachmentVersion&objectId=\'+$(\'templateOffice\').value+\'&attachType=\'+$(\'attachment_types\').value+\'&objectTable=' . $objectTable . '&contactId=\'+$(\'contactidAttach\').value+\'&addressId=\'+$(\'addressidAttach\').value+\'&chronoAttachment=\'+$(\'chrono\').value+\'&titleAttachment=\'+cleanTitle($(\'title\').value)+\'&back_date=\'+$(\'back_date\').value+\'&resMaster=' . $_SESSION['doc_id']
        . '\', \'\', \'height=200, width=250,scrollbars=no,resizable=no,directories=no,toolbar=no\');$(\'add\').value=\'Edition en cours ...\';setInterval(function() {checkEditingDoc(\''.$_SESSION['user']['UserId'].'\')}, 500);$(\'add\').disabled=\'disabled\';$(\'add\').style.opacity=\'0.5\';this.hide();"/>';
    $content .= '<span style="display: none" id="divOr0">&nbsp;ou&nbsp;</span>';
    $content .= '</div>';

    $content .= '<div style="float: left">';
    if(isset($_SESSION['attachment_types']['transmission'])){
        $content .= '<i id="newTransmissionButton0" title="Nouvelle transmission" style="opacity: 0.5;cursor: pointer" class="fa fa-plus-circle fa-2x"
                         onclick="addNewTransmission(\'' . $_SESSION['config']['businessappurl'] . '\', \'' . $_SESSION['doc_id'] . '\', ' . $canCreateContact . ', \'' . addslashes(implode('#', $langArrayForTransmission)) . '\')"></i>';
    }
    $content .= '</div></p>';
}

if (isset($_REQUEST['id']) && ($data_attachment->status <> 'TMP' || ($data_attachment->status == 'TMP' && $data_attachment->relation > 1))) {
    $content .= '<p>';
    $content .= '<label>'. _CREATE_NEW_ATTACHMENT_VERSION.'</label>';
    $content .= '<input type="radio" name="new_version" id="new_version_yes" value="yes" onclick="setButtonStyle(\'yes\', \''.$attachmentFormat.'\', $(\'hiddenValidateStatus\').value)"/>'._YES;
    $content .= '&nbsp;&nbsp;';
    $content .= '<input type="radio" name="new_version" id="new_version_no" checked value="no" onclick="setButtonStyle(\'no\', \''.$attachmentFormat.'\', $(\'hiddenValidateStatus\').value)"/>'._NO;
    $content .= '</p>';
}

$content .= '</div>';

    $content .= '<div id="transmission"></div>';
    $content .= '<input type="hidden" id="hiddenValidateStatus" value="1"/>';
        $content .= '<p class="buttons">';
            if (isset($_REQUEST['id']) && $attachmentFormat != "pdf") {
                $content .= '<input type="button" value="';
                $content .= _EDIT_MODEL;
                $content .= '" name="editModel" id="editModel" class="button" onclick="$(\'hiddenValidateStatus\').value=\'2\';$(\'edit\').style.visibility=\'visible\';window.open(\''
                    . $_SESSION['config']['businessappurl'] . 'index.php?display=true&module=content_management&page=applet_popup_launcher&objectType=attachmentUpVersion&objectId='.$_REQUEST['id'].'&contactId=\'+$(\'contactidAttach\').value+\'&addressId=\'+$(\'addressidAttach\').value+\'&titleAttachment=\'+cleanTitle($(\'title\').value)+\'&back_date=\'+$(\'back_date\').value+\'&objectTable=res_view_attachments&resMaster='.$_SESSION['doc_id']
                    .'\', \'\', \'height=200, width=250,scrollbars=no,resizable=no,directories=no,toolbar=no\');$(\'edit\').value=\'Edition en cours ...\';setInterval(function() {checkEditingDoc(\''.$_SESSION['user']['UserId'].'\')}, 500);$(\'edit\').disabled=\'disabled\';$(\'edit\').style.opacity=\'0.5\';this.hide();"/>';
            } /*else {
                                    $content .= '" name="edit" id="edit" style="display:none" class="button" '
                                                .'onclick="window.open(\''. $_SESSION['config']['businessappurl'] . 'index.php?display=true&module=content_management&page=applet_popup_launcher&objectType=attachmentVersion&objectId=\'+$(\'templateOffice\').value+\'&objectTable='. $objectTable .'&contactId=\'+$(\'contactidAttach\').value+\'&chronoAttachment=\'+$(\'chrono\').value+\'&resMaster='.$_SESSION['doc_id']
                                                .'\', \'\', \'height=200, width=250,scrollbars=no,resizable=no,directories=no,toolbar=no\');"/>';
                                }*/

            $content .= '&nbsp;';
            $content .= '&nbsp;';
            $content .= '<input type="button" value="';
            $content .=  _VALIDATE;
            if (isset($_REQUEST['id'])) {
                $content .= '" name="edit" id="edit" class="button" onclick="ValidAttachmentsForm(\'' . $_SESSION['config']['businessappurl'] ;
            } else {
                $content .= '" name="add" id="add" class="button" onclick="simpleAjax(\'' . $_SESSION['config']['businessappurl'].'index.php?display=true&module=attachments&page=unsetReservedChronoNumber\');ValidAttachmentsForm(\'' . $_SESSION['config']['businessappurl'] ;
            }
            $content .= 'index.php?display=true&module=attachments&page=attachments_content\', \'formAttachment\',\''._ID_TO_DISPLAY.'\')"/>';

            $content .= '&nbsp;';
            $content .= '&nbsp;';
            $content .= '<label>&nbsp;</label>';

            $content .= '<input type="button" value="';
            $content .=  _CANCEL;
            $content .= '" name="cancel" class="button"  onclick="simpleAjax(\'' . $_SESSION['config']['businessappurl'].'index.php?display=true&module=attachments&page=unsetReservedChronoNumber\');';
            if (isset($_REQUEST['id'])) {
                $content .= 'simpleAjax(\'' . $_SESSION['config']['businessappurl'].'index.php?display=true&module=attachments&page=unsetTemporarySaved&mode=edit\');destroyModal(\'form_attachments\');"/>';
            } else {
                $content .= 'simpleAjax(\'' . $_SESSION['config']['businessappurl'].'index.php?display=true&module=attachments&page=unsetTemporarySaved&mode=add\');destroyModal(\'form_attachments\');"/>';
            }

        $content .= '</p>';
        $content .= '&nbsp;';
        $content .= '&nbsp;';
        $content .= '</p>';
    $content .= '</form>';

    if (!isset($_REQUEST['id'])) {
        $content .= '<script>launch_autocompleter_contacts_v2("'. $_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=autocomplete_contacts", "contact_attach", "show_contacts_attach", "", "contactidAttach", "addressidAttach")</script>';
    } else {
        $content .= '<script>launch_autocompleter2_contacts_v2("'. $_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=autocomplete_contacts", "contact_attach", "show_contacts_attach", "", "contactidAttach", "addressidAttach")</script>';
    }

    $content .= '<script>display_contact_card(\'visible\', \'contact_card_attach\');</script>';

    if ($core->test_admin('my_contacts', 'apps', false)) {
        $content .= '<div id="create_contact_div_attach" style="display:none;float:right;width:65%;background-color:#deedf3">';
            $content .= '<iframe width="100%" height="550" src="' . $_SESSION['config']['businessappurl']
                    . 'index.php?display=false&dir=my_contacts&page=create_contact_iframe&fromAttachmentContact=Y&transmissionInput=0" name="contact_iframe_attach" id="contact_iframe_attach"'
                    . ' scrolling="auto" frameborder="0" style="display:block;">'
                    . '</iframe>';
        $content .= '</div>';
    }
    $content .= '<div id="info_contact_div_attach" style="display:none;float:right;width:70%;background-color:#deedf3">';
        $content .= '<iframe width="100%" height="800" name="info_contact_iframe_attach" id="info_contact_iframe_attach"'
                . ' scrolling="auto" frameborder="0" style="display:block;">'
                . '</iframe>';
    $content .= '</div>';
$content .= '<div style="float: right; width: 65%">';
$content .= '<iframe src="'.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=view_resource_controler&id='
    . functions::xssafe($_SESSION['doc_id']) . '&coll_id=' . $coll_id .
    '" name="viewframevalid_attachment" id="viewframevalid_attachment"  scrolling="auto" frameborder="0" style="width:100% !important;height:900px;" onmouseover="this.focus()"></iframe>';
$content .= '</div>';
if(!isset($_REQUEST['id'])){
    $content .= '<script>var height = (parseInt($(\'form_attachments\').style.height.replace(/px/,""))-40)+"px";$(\'formAttachment\').style.height = height;$(\'viewframevalid_attachment\').style.height = height;$(\'formAttachment\').style.overflow = "auto";</script>';
}else{
    $content .= '<script>var height = (parseInt(window.top.window.$(\'form_attachments\').style.height.replace(/px/,""))-40)+"px";window.top.window.$(\'formAttachment\').style.height = height;window.top.window.$(\'viewframevalid_attachment\').style.height = height;window.top.window.$(\'formAttachment\').style.overflow = "auto";</script>';
}

echo "{status : " . $status . ", content : '" . addslashes(_parse($content)) . "', error : '" . addslashes($error) . "', exec_js : '".addslashes($js)."'}";
exit ();
