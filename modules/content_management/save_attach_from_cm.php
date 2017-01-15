<?php

//case of res -> update attachment
require_once 'modules/attachments/attachments_tables.php';
$docserverControler = new docservers_controler();
$docserver = $docserverControler->getDocserverToInsert(
    $_SESSION['cm']['collId']
);
$collId = $_SESSION['cm']['collId'];
$_SESSION['cm']['collId'] = '';

if (empty($docserver)) {
    $location = '';
    $result = array('ERROR' => _DOCSERVER_ERROR . ' : '
        . _NO_AVAILABLE_DOCSERVER . '. ' . _MORE_INFOS
    );
    createXML('ERROR', $result);
} else {
    // some checking on docserver size limit
    $newSize = $docserverControler->checkSize(
        $docserver, filesize($_SESSION['config']['tmppath'] . $tmpFileName)
    );
    if ($newSize == 0) {
        $result = array('ERROR' => _DOCSERVER_ERROR . ' : '
            . _NOT_ENOUGH_DISK_SPACE . '. ' . _MORE_INFOS
        );
        createXML('ERROR', $result);
    } else {
        $fileInfos = array(
            'tmpDir'      => $_SESSION['config']['tmppath'],
            'size'        => filesize($_SESSION['config']['tmppath'] . $tmpFileName),
            'format'      => strtoupper($fileExtension),
            'tmpFileName' => $tmpFileName,
        );

        $storeResult = array();
        $storeResult = $docserverControler->storeResourceOnDocserver(
            $collId, $fileInfos
        );
        if (isset($storeResult['error']) && $storeResult['error'] <> '') {
            $result = array('ERROR' => $storeResult['error']);
            createXML('ERROR', $result);
        } else {
            require_once 'core/docservers_tools.php';
            require_once 'core/class/docserver_types_controler.php';
            $docserver = $docserverControler->get($storeResult['docserver_id']);
            $docserverTypeControler = new docserver_types_controler();
            $docserverTypeObject = $docserverTypeControler->get($docserver->docserver_type_id);
            $query = "update " . RES_ATTACHMENTS_TABLE 
                . " set docserver_id = ? "
                . ", path = ? "
                . ", filename = ? "
                . ", filesize = ? "
                . ", fingerprint = ? "
                . "where res_id = ?";
				
			//copie de la version PDF de la pièce si mode de conversion sur le client
			if (
                $_SESSION['modules_loaded']['attachments']['convertPdf'] == true 
                && $tmpFilePdfName != ''
            ) {
				$file = $_SESSION['config']['tmppath'].$tmpFilePdfName;
				$newfile = $storeResult['path_template']
                    . str_replace('#',"/",$storeResult['destination_dir'])
                    . substr (
                        $storeResult['file_destination_name'], 
                        0, 
                        strrpos($storeResult['file_destination_name'], "." )
                    ).".pdf";
				
				copy($file, $newfile);
				$_SESSION['new_id'] = $objectId;
			}
            $dbAttachment = new Database();
            $stmt = $dbAttachment->query(
                $query, 
                array(
                    $storeResult['docserver_id'],
                    $storeResult['destination_dir'],
                    $storeResult['file_destination_name'],
                    filesize($_SESSION['config']['tmppath'] . $tmpFileName),
                    Ds_doFingerprint($_SESSION['config']['tmppath'] 
                        . $tmpFileName, $docserverTypeObject->fingerprint_mode,
                    $objectId
                )
            ));
            if ($_SESSION['history']['attachup'] == 'true') {
                $hist = new history();
                $sec = new security();
                $view = $sec->retrieve_view_from_coll_id(
                    $collId
                );
                $query = "select res_id_master from " . RES_ATTACHMENTS_TABLE
                    . " where res_id = ?";
                $stmt = $dbAttachment->query($query, array($objectId));
                $lineDoc = $stmt->fetchObject();
                $hist->add(
                    $view, $lineDoc->res_id_master, 'UP', 'attachup',
                    ucfirst(_DOC_NUM) . $objectId . ' '
                    . _ATTACH_UP . ' ' . _TO_MASTER_DOCUMENT
                    . $lineDoc->res_id_master,
                    $_SESSION['config']['databasetype'],
                    'apps'
                );
                $hist->add(
                    RES_ATTACHMENTS_TABLE, $objectId, 'UP','attachup',
                    ATTACHMENT_UP,
                    $_SESSION['config']['databasetype'],
                    'attachments'
                );
            }
        }
    }
}
