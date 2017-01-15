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
* @brief  controler of the view resource page
*
* @file view_resource_controler.php
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup indexing_searching
*/
$_SESSION['HTTP_REFERER'] = Url::requestUri();
if (!isset($_SESSION['user']['UserId']) && $_SESSION['user']['UserId'] == '') {
    if (trim($_SERVER['argv'][0]) <> '') {
        header('location: reopen.php?' . $_SERVER['argv'][0]);
    } else {
        header('location: reopen.php');
    }
    exit();
}
unset($_SESSION['HTTP_REFERER']);

try {
    require_once('core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR 
        . 'class_request.php');
    require_once('core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR 
        . 'class_security.php');
    require_once('core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR 
        . 'class_resource.php');
    require_once('core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR 
        . 'docservers_controler.php');
} catch (Exception $e) {
    functions::xecho($e->getMessage());
}
$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();
$function = new functions();
$sec = new security();
$mode = '';
$calledByWS = false;
//1:test the request ID
if (isset($_REQUEST['id'])) {
    $s_id = functions::xssafe($_REQUEST['id']);
} else {
    $s_id = '';
}
if (isset($_REQUEST['resIdMaster'])) {
    $resIdMaster = $_REQUEST['resIdMaster'];
} else {
    $resIdMaster = '';
}
if ($s_id == '') {
    $_SESSION['error'] = _THE_DOC . ' ' . _IS_EMPTY;
    header('location: ' . $_SESSION['config']['businessappurl'] . 'index.php');
    exit();
} else if (!is_numeric($s_id)) {
    $_SESSION['error'] = _FORMAT . ' ' . _UNKNOWN;
    header('location: ' . $_SESSION['config']['businessappurl'] . 'index.php');
    exit();
} else {
    //2:retrieve the view
    $table = '';
    if (isset($_REQUEST['collid']) && $_REQUEST['collid'] <> '') {
        $_SESSION['collection_id_choice'] = $_REQUEST['collid'];
    } else if (isset($_REQUEST['coll_id']) && $_REQUEST['coll_id'] <> '') {
        $_SESSION['collection_id_choice'] = $_REQUEST['coll_id'];
    }
    
    if (isset($_SESSION['collection_id_choice']) 
        && !empty($_SESSION['collection_id_choice'])
    ) {
        $table = $sec->retrieve_view_from_coll_id(
            $_SESSION['collection_id_choice']
        );
        if (!$table) {
            $table = $sec->retrieve_table_from_coll(
                $_SESSION['collection_id_choice']
            );
        }
    } else {
        
        if (isset($_SESSION['collections'][0]['view']) 
            && !empty($_SESSION['collections'][0]['view'])
        ) {
            $table = $_SESSION['collections'][0]['view'];
        } else {
            $table = $_SESSION['collections'][0]['table'];
        }
        $_SESSION['collection_id_choice'] = $_SESSION['collections'][0]['id'];
    }

    // Test courrier départ spontanné
    if ($resIdMaster == '') {
        $db = new Database();
        $stmt = $db->query("SELECT category_id, source FROM "
            . $table . " WHERE res_id = ? ", array($s_id));
        $res_outgoing = $stmt->fetchObject(); 

        if ($res_outgoing->category_id == 'outgoing' && $res_outgoing->source == 'with_empty_file') {
            $stmt = $db->query("SELECT res_id FROM res_view_attachments WHERE status <> 'DEL' and status <> 'OBS' "
                . "and res_id_master = ? and coll_id = ? and ((attachment_type = 'converted_pdf' and type_id = 1) "
                . "OR (attachment_type = 'outgoing_mail' and format = 'pdf')"
                . "OR (attachment_type = 'signed_response' and format = 'pdf')) order by res_id desc", 
                array($s_id, $_SESSION['collection_id_choice']));
            $res_att = $stmt->fetchObject();
            if ($stmt->rowCount() > 0) {
                ?>
                <script type="text/javascript">
                window.location.href = '<?php
                    echo $_SESSION['config']['businessappurl'];
                    ?>index.php?display=true&module=attachments&page=view_attachment&res_id_master=<?php echo $s_id;?>&id=<?php echo $res_att->res_id;?>'
                </script>
                <?php
                exit();
            }  else {
            	$stmt = $db->query("SELECT res_id FROM res_view_attachments WHERE status <> 'DEL' and status <> 'OBS' "
                    . "and res_id_master = ? and coll_id = ? and ((attachment_type = 'converted_pdf' and (type_id = 1 or type_id = 0)) "
                    . "OR (attachment_type = 'outgoing_mail' and format = 'pdf')"
                    . "OR (attachment_type = 'signed_response' and format = 'pdf')) order by res_id desc",
                    array($s_id, $_SESSION['collection_id_choice']));
            	$res_att = $stmt->fetchObject();
            	if ($stmt->rowCount() > 0) {
	            ?>
	            <script type="text/javascript">
	                window.location.href = '<?php
	                echo $_SESSION['config']['businessappurl'];
	                ?>index.php?display=true&module=attachments&page=view_attachment&res_id_master=<?php 
                        echo $s_id;?>&id=<?php echo $res_att->res_id;?>'
	            </script>
	            <?php
	            exit();
	        }
            }
        }
    }

    for ($cptColl = 0;$cptColl < count($_SESSION['collections']);$cptColl++) {
        if ($table == $_SESSION['collections'][$cptColl]['table'] 
            || $table == $_SESSION['collections'][$cptColl]['view']
        ) {
            $adrTable = $_SESSION['collections'][$cptColl]['adr'];
        }
    }
    if ($adrTable == '') {
        $adrTable = $_SESSION['collections'][0]['adr'];
    }
    
    $versionTable = $sec->retrieve_version_table_from_coll_id(
        $_SESSION['collection_id_choice']
    );
    //SECURITY PATCH
    require_once 'core/class/class_security.php';
    if ($resIdMaster <> '') {
        $idToTest = $resIdMaster;
    } else {
        $idToTest = $s_id;
    }
    $security = new security();
    $right = $security->test_right_doc(
        $_SESSION['collection_id_choice'], 
        $idToTest
    );
	
    //$_SESSION['error'] = 'coll '.$coll_id.', res_id : '.$s_id;
	if($_SESSION['origin'] = 'search_folder_tree'){
		$_SESSION['origin'] = 'search_folder_tree';
	}else{
		$_SESSION['origin'] = '';
	}	
    if (!$right) {
        $_SESSION['error'] = _NO_RIGHT_TXT;
        ?>
        <script type="text/javascript">
        window.top.location.href = '<?php
            echo $_SESSION['config']['businessappurl'];
            ?>index.php';
        </script>
        <?php
        exit();
    }
    if (
        $versionTable <> '' 
        && !isset($_REQUEST['original'])
        && !isset($_REQUEST['aVersion'])
    ) {
        $selectVersions = "SELECT res_id FROM " 
            . $versionTable . " WHERE res_id_master = ? and status <> 'DEL' order by res_id desc";
        $db = new Database();
        $stmt = $db->query($selectVersions, array($s_id));
        $lineLastVersion = $stmt->fetchObject();
        $lastVersion = $lineLastVersion->res_id;
        if ($lastVersion <> '') {
            $s_id = $lastVersion;
            $table = $versionTable;
            $adrTable = '';
        }
    } elseif(isset($_REQUEST['aVersion'])) {
        $table = $versionTable;
    }
    $docserverControler = new docservers_controler();
    $viewResourceArr = array();
    
    $viewResourceArr = $docserverControler->viewResource(
        $s_id, 
        $table, 
        $adrTable, 
        false
    );
    if ($viewResourceArr['error'] <> '') {
        //...
    } else {
        //$core_tools->show_array($viewResourceArr);
        if (strtoupper($viewResourceArr['ext']) == 'HTML' 
            && $viewResourceArr['mime_type'] == "text/plain"
        ) {
            $viewResourceArr['mime_type'] = "text/html";
        }

        //WATERMARK
        if (strtoupper($viewResourceArr['ext']) == 'PDF') {
            if ($_SESSION['features']['watermark']['enabled'] == 'true') {
                try{
                    include 'apps/maarch_entreprise/indexing_searching/watermark.php';
                } catch(Exception $e) {
                    $logger = Logger::getLogger('loggerTechnique');
                    $logger->warn(
                        "[{$_SESSION['user']['UserId']}][View_resource_controler] Watermark has failed :("
                    );
                }
            }
        }

        if ($viewResourceArr['called_by_ws']) {
            $fileContent = base64_decode($viewResourceArr['file_content']);
            $fileNameOnTmp = 'tmp_file_' . rand() . '_' 
                . md5($fileContent) . '.' 
                . strtolower($viewResourceArr['ext']);
            $filePathOnTmp = $_SESSION['config']['tmppath'] 
                . DIRECTORY_SEPARATOR . $fileNameOnTmp;
            $inF = fopen($filePathOnTmp, 'w');
            fwrite($inF, $fileContent);
            fclose($inF);
        } else {
            $filePathOnTmp = $viewResourceArr['file_path'];
        }
        if (strtolower(
            $viewResourceArr['mime_type']
        ) == 'application/maarch'
        ) {
            $myfile = fopen($filePathOnTmp, 'r');
            $data = fread($myfile, filesize($filePathOnTmp));
            fclose($myfile);
            $content = stripslashes($data);
        }
    }
    include('view_resource.php');
}
