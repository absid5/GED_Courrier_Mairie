<?php

/*
*   Copyright 2008-2015 Maarch
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
* File : view_attachement.php
*
* View a document
*
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*/

require_once "core/class/class_security.php";
require_once 'modules/attachments/attachments_tables.php';
require_once 'core/core_tables.php';
require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
    . 'class_indexing_searching_app.php';
require_once "core/class/class_history.php";

$core = new core_tools();
$core->test_user();
$core->load_lang();
$db = new Database();
$function = new functions();
$sec = new security();
$_SESSION['error'] = "";
if (isset($_GET['id'])) {
    $sId = $_GET['id'];
} else {
    $sId = "";
}
$sId = $function->wash($_GET['id'], "num", _THE_DOC);
if (! empty($_SESSION['error'])) {
    header("location: " . $_SESSION['config']['businessappurl'] . "index.php");
    exit();
} else {

    $stmt = $db->query(
        "SELECT coll_id, res_id_master 
            FROM res_view_attachments 
            WHERE (res_id = ? OR res_id_version = ?) AND res_id_master = ? ORDER BY relation desc",array($sId,$sId,$_REQUEST['res_id_master']));
    $res = $stmt->fetchObject();
    $collId = $res->coll_id;
    $resIdMaster = $res->res_id_master;

    if ($stmt->rowCount() == 0) {
        $_SESSION['error'] = _NO_DOC_OR_NO_RIGHTS;
        header(
            "location: " . $_SESSION['config']['businessappurl']
            . "index.php"
        );
        exit();
    }

    $where2 = "";
    foreach (array_keys($_SESSION['user']['security']) as $key) {
        if ($collId == $key) {
            $where2 = " and ( " . $_SESSION['user']['security'][$key]['DOC']['where']
                    . " ) ";
        }
    }
    //fonction qui va permettre de récupérer les infos auxquels l'utilisateur à la possibilité de voir
    $security = new security();
    $right = $security->test_right_doc(
        $_SESSION['collection_id_choice'], 
        $resIdMaster
    );
    $table = $sec->retrieve_table_from_coll($collId);
    $stmt = $db->query(
        "SELECT res_id FROM " . $table . " WHERE res_id = ? ".$where2,array($resIdMaster));

    if ($stmt->rowCount() == 0 and !$right) {
        $_SESSION['error'] = _NO_DOC_OR_NO_RIGHTS;
        header(
            "location: " . $_SESSION['config']['businessappurl'] . "index.php"
        );
        exit();
    } else {
        $stmt = $db->query(
            "SELECT docserver_id, path, filename, format, title 
                FROM res_view_attachments 
                WHERE (res_id = ? OR res_id_version = ?) AND res_id_master = ? ORDER BY relation desc", array($sId,$sId,$_REQUEST['res_id_master'])
        );

        if ($stmt->rowCount() == 0) {
            $_SESSION['error'] = _NO_DOC_OR_NO_RIGHTS;
            header(
                "location: " . $_SESSION['config']['businessappurl']
                . "index.php"
            );
            exit();
        } else {
            $line = $stmt->fetchObject();
            $docserver = $line->docserver_id;
            $path = $line->path;
            $filename = $line->filename;
	    $nameShow = $function->normalize($line->title);
	    $nameShow = preg_replace('/([^.a-z0-9]+)/i', '_', $nameShow);
	    $nameShow .= '_'. date("j_m_Y__G_i");
            $format = $line->format;
            $stmt = $db->query(
                "select path_template from " . _DOCSERVERS_TABLE_NAME
                . " where docserver_id = ?",array($docserver)
            );
            //$db->show();
            $lineDoc = $stmt->fetchObject();
            $docserver = $lineDoc->path_template;
            $file = $docserver . $path . $filename;
            $file = str_replace("#", DIRECTORY_SEPARATOR, $file);

            if (strtoupper($format) == "MAARCH") {
                if (file_exists($file)) {
                    $myfile = fopen($file, "r");

                    $data = fread($myfile, filesize($file));
                    fclose($myfile);
                    $content = stripslashes($data);
                    $core->load_html();
                    $core->load_header();
                    ?>
                    <body id="validation_page" onload="javascript:moveTo(0,0);resizeTo(screen.width, screen.height);">
                    <div id="model_content" style="width:100%;"  >

                    <?php functions::xecho($content);?>

                    </div>
                    </body>
                    </html> <?php
                } else {
                    $_SESSION['error'] = _NO_DOC_OR_NO_RIGHTS . "...";
                    ?><script type="text/javascript">window.opener.top.location.href='index.php';self.close();</script><?php
                }
            } else {
                require_once 'core/docservers_tools.php';
                $arrayIsAllowed = array();
                $arrayIsAllowed = Ds_isFileTypeAllowed($file);
                if ($arrayIsAllowed['status']) {
                    if ($_SESSION['history']['attachview'] == "true") {
                        $hist = new history();
                        $hist->add(
                            $table, $sId, "VIEW", 'attachview', _VIEW_DOC_NUM . "" . $sId,
                            $_SESSION['config']['databasetype'], 'apps'
                        );
                    }
                    //WATERMARK
                    if (strtoupper($format) == 'PDF') {
                        if ($_SESSION['modules_loaded']['attachments']['watermark']['enabled'] == 'true') {
                            $table = 'res_attachments';
                            $watermarkForAttachments = true;
                            try{
                                include 'apps/maarch_entreprise/indexing_searching/watermark.php';
                            } catch(Exception $e) {
                                $logger = Logger::getLogger('loggerTechnique');
                                $logger->warn(
                                "[{$_SESSION['user']['UserId']}][View_attachment] Watermark has failed"
                                );
                            }
                        }
                    }
                    header("Pragma: public");
                    header("Expires: 0");
                    header(
                        "Cache-Control: must-revalidate, post-check=0, pre-check=0"
                    );
                    header("Cache-Control: public");
                    header("Content-Description: File Transfer");
                    header("Content-Type: " . $arrayIsAllowed['mime_type']);
                    header(
                        "Content-Disposition: inline; filename="
                        . basename($nameShow . '.' . $format) . ";"
                    );
                    header("Content-Transfer-Encoding: binary");
                    readfile($file);
                    exit();
                } else {
                    echo _FORMAT . ' ' . _UNKNOWN;
                    exit();
                }
            }
        }
    }
}
