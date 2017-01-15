<?php
//FOR ADD RES VERSIONS

if ($_SESSION['cm']['resMaster'] <> '') {
    $resMaster = $_SESSION['cm']['resMaster'];
} else {
    $resMaster = $_SESSION['doc_id'];
}

$_SESSION['cm']['resMaster'] = '';

$security = new security();
$versionTable = $security->retrieve_version_table_from_coll_id(
    $_SESSION['cm']['collId']
);

$docserverControler = new docservers_controler();
$docserver = $docserverControler->getDocserverToInsert(
    $_SESSION['cm']['collId']
);
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
        $dbVersion = new Database();

        $query = "select relation, fingerprint, docserver_id from " 
            . $versionTable
            . " where res_id_master = ?"
            . " and status <> 'DEL' order by res_id desc";
        $stmt = $dbVersion->query($query, array($resMaster));
        $resVer = $stmt->fetchObject();
        $lastVersion = $resVer->relation;
        $lastFingerprint = $resVer->fingerprint;
        $docserverId = $resVer->docserver_id;
        $newVersion = (integer) $lastVersion + 1;
        require_once 'core/class/docserver_types_controler.php';
        require_once 'core/docservers_tools.php';
        $docserverTypeControler = new docserver_types_controler();
        $docserverTypeObject = $docserverTypeControler->get(
            $docserver->docserver_type_id
        );
         $fingerprintFromTmp = Ds_doFingerprint(
            $_SESSION['config']['tmppath'] . $tmpFileName, 
            $docserverTypeObject->fingerprint_mode
        );
        //$result = array('ERROR' => $fingerprintFromTmp . '<>' . $lastFingerprint);
        //createXML('ERROR', $result);
        if ($fingerprintFromTmp <> $lastFingerprint) {
            $fileInfos = array(
                'tmpDir'      => $_SESSION['config']['tmppath'],
                'size'        => filesize($_SESSION['config']['tmppath'] . $tmpFileName),
                'format'      => strtoupper($fileExtension),
                'tmpFileName' => $tmpFileName,
            );
            $storeResult = array();
            $storeResult = $docserverControler->storeResourceOnDocserver(
                $_SESSION['cm']['collId'], $fileInfos
            );
            if (isset($storeResult['error']) && $storeResult['error'] <> '') {
                $result = array('ERROR' => $storeResult['error']);
                createXML('ERROR', $result);
            } else {
                $resVersion = new resource();
                $_SESSION['data'] = array();
                array_push(
                    $_SESSION['data'],
                    array(
                        'column' => 'typist',
                        'value' => $_SESSION['user']['UserId'],
                        'type' => 'string',
                    )
                );
                array_push(
                    $_SESSION['data'],
                    array(
                        'column' => 'format',
                        'value' => $fileExtension,
                        'type' => 'string',
                    )
                );
                array_push(
                    $_SESSION['data'],
                    array(
                        'column' => 'docserver_id',
                        'value' => $storeResult['docserver_id'],
                        'type' => 'string',
                    )
                );
                array_push(
                    $_SESSION['data'],
                    array(
                        'column' => 'status',
                        'value' => 'NEW',
                        'type' => 'string',
                    )
                );
                array_push(
                    $_SESSION['data'],
                    array(
                        'column' => 'relation',
                        'value' => $newVersion,
                        'type' => 'int',
                    )
                );
                array_push(
                    $_SESSION['data'],
                    array(
                        'column' => 'offset_doc',
                        'value' => ' ',
                        'type' => 'string',
                    )
                );
                array_push(
                    $_SESSION['data'],
                    array(
                        'column' => 'logical_adr',
                        'value' => ' ',
                        'type' => 'string',
                    )
                );
                array_push(
                    $_SESSION['data'],
                    array(
                        'column' => 'title',
                        'value' => 'new version of original resource',
                        'type' => 'string',
                    )
                );
                array_push(
                    $_SESSION['data'],
                    array(
                        'column' => 'coll_id',
                        'value' => $_SESSION['cm']['collId'],
                        'type' => 'string',
                    )
                );
                array_push(
                    $_SESSION['data'],
                    array(
                        'column' => 'res_id_master',
                        'value' => $resMaster,
                        'type' => 'integer',
                    )
                );
                array_push(
                    $_SESSION['data'],
                    array(
                        'column' => 'type_id',
                        'value' => 0,
                        'type' => 'int',
                    )
                );
                //$_SESSION['error'] = 'test';
                $id = $resVersion->load_into_db(
                    $versionTable,
                    $storeResult['destination_dir'],
                    $storeResult['file_destination_name'] ,
                    $storeResult['path_template'],
                    $storeResult['docserver_id'], 
                    $_SESSION['data'],
                    $_SESSION['config']['databasetype']
                );
				
                if ($id == false) {
                    $result = array('ERROR' => $resVersion->get_error());
                    createXML('ERROR', $result);
                } else {
                    if ($_SESSION['history']['cmadd'] == "true") {
                        $hist = new history();
                        $sec = new security();
                        $view = $sec->retrieve_view_from_coll_id(
                            $_SESSION['cm']['collId']
                        );
                        $hist->add(
                            $view, $resMaster, 'ADD', 'cmadd',
                            ucfirst(_DOC_NUM) . $id . ' '
                            . _NEW_VERSION_ADDED . ' ' . _TO_MASTER_DOCUMENT
                            . $resMaster,
                            $_SESSION['config']['databasetype'],
                            'apps'
                        );
                        $hist->add(
                            $versionTable, $id, 'ADD','cmadd',
                            ' new version of original resource',
                            $_SESSION['config']['databasetype'],
                            'content_management'
                        );
                    }
                }
            }
        } else {
            $result = array('END_MESSAGE' => _NO_UPDATE);
            createXML('SUCCESS', $result);
        }
    }
}
