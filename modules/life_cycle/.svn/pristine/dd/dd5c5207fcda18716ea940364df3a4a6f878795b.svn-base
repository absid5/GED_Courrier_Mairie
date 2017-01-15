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
 * @brief Batch to process purge
 *
 * @file
 * @author  Laurent Giovannoni  <dev@maarch.org>
 * @date $date$
 * @version $Revision$
 * @ingroup life_cycle
 */

/*****************************************************************************
WARNING : THIS BATCH ERASE RESOURCES IN DATABASE AND IN DOCSERVERS 
Please note this batch deletes resources in the database 
and storage spaces (docservers). 
You need to run only if it is set -> Make especially careful to 
define the where clause.
FOR THE CASE OF AIP : to be used only if the AIP are single resources.
*****************************************************************************/

/**
 * *****   LIGHT PROBLEMS without an error semaphore
 *  101 : Configuration file missing
 *  102 : Configuration file does not exist
 *  103 : Error on loading config file
 *  104 : SQL Query Error
 *  105 : a parameter is missing
 *  106 : Maarch_CLITools is missing
 *  107 : Stack empty for the request
 *  108 : There are still documents to be processed
 *  109 : An instance of the batch for the required policy and cyle is already
 *        in progress
 *  110 : Problem with collection parameter
 *  111 : Problem with the php include path
 *  112 : AIP not able to be purged
 *  113 : Security problem with where clause
 * ****   HEAVY PROBLEMS with an error semaphore
 *  13  : Docserver not found
 */

date_default_timezone_set('Europe/Paris');
try {
    include('load_process_purge.php');
} catch (IncludeFileError $e) {
    echo "Maarch_CLITools required ! \n (pear.maarch.org)\n";
    exit(106);
}

/******************************************************************************/
/* beginning */

if ($GLOBALS['PurgeMode'] == "contacts") {
    $GLOBALS['state'] = "DELETE_CONTACTS_ON_DB";
} else {
    $GLOBALS['state'] = "SELECT_RES";
}

while ($GLOBALS['state'] <> "END") {
    if (isset($GLOBALS['logger'])) {
        $GLOBALS['logger']->write("STATE:" . $GLOBALS['state'], 'DEBUG');
    }
    switch($GLOBALS['state']) {
        /**********************************************************************/
        /*                          SELECT_RES                                */
        /*                                                                    */
        /**********************************************************************/
        case "SELECT_RES" :
            $orderBy = 'order by res_id';
            if ($GLOBALS['stackSizeLimit'] <> '') {
                $limit = ' LIMIT ' . $GLOBALS['stackSizeLimit'];
            }
            $where_clause = $GLOBALS['whereClause'];
            $query = "select res_id, docserver_id, path, filename, fingerprint from " 
                . $GLOBALS['table'] 
                . " where " . $where_clause . " " 
                . $limit . " " . $orderBy;
            $stmt = Bt_doQuery($GLOBALS['db'], $query);
            $GLOBALS['logger']->write('select res query:' . $query, 'INFO');
            $resourcesArray = array();
            if ($stmt->rowCount() > 0) {
                while ($resoucesRecordset = $stmt->fetchObject()) {
                    $queryDs = "select path_template from docservers " 
                       . " where docserver_id = ?";
                    $stmt2 = Bt_doQuery(
                        $GLOBALS['db2'], 
                        $queryDs, 
                        array($resoucesRecordset->docserver_id)
                    );
                    if ($stmt2->rowCount() == 0) {
                        Bt_exitBatch(13, 'Docserver:' 
                            . $resoucesRecordset->docserver_id . ' not found');
                        break;
                    } else {
                        $dsRecordset = $stmt2->fetchObject();
                        $dsPath = $dsRecordset->path_template;
                    }
                    array_push(
                        $resourcesArray,
                        array(
                            'res_id' => $resoucesRecordset->res_id,
                            'docserver_id' => $resoucesRecordset->docserver_id,
                            'path_template' => $dsPath,
                            'path' =>  str_replace('#', DIRECTORY_SEPARATOR, 
                                $resoucesRecordset->path),
                            'filename' => $resoucesRecordset->filename,
                            'fingerprint' => $resoucesRecordset->fingerprint,
                            'adr' => array(),
                        )
                    );
                }
            } else {
                if ($GLOBALS['PurgeMode'] == 'both') {
                    $GLOBALS['logger']->write('no resource found for collection:'
                        . $GLOBALS['collection'] . ', where clause:' 
                        . str_replace("'", "''", $GLOBALS['whereClause']), 'INFO');
                    $state = 'DELETE_CONTACTS_ON_DB';
                } else {
                    Bt_exitBatch(111, 'no resource found for collection:'
                        . $GLOBALS['collection'] . ', where clause:' 
                        . str_replace("'", "''", $GLOBALS['whereClause']));                    
                }
                break;
            }
            //var_dump($resourcesArray);
            Bt_updateWorkBatch();
            $GLOBALS['logger']->write('Batch number:' . $GLOBALS['wb'], 'INFO');
            $countRA = count($resourcesArray);
            for ($cptRes = 0;$cptRes < $countRA;$cptRes++) {
                $queryAip = "select res_id, docserver_id, path, filename, fingerprint from " 
                    . $GLOBALS['adrTable']
                    . " where res_id = ?";
                $stmt = Bt_doQuery(
                    $GLOBALS['db'], 
                    $queryAip,
                    array($resourcesArray[$cptRes]["res_id"])
                );
                $aipArray = array();
                if ($stmt->rowCount() > 0) {
                    while ($resoucesRecordsetAdr = $stmt->fetchObject()) {
                        $queryDs = "select path_template from docservers " 
                           . " where docserver_id = ?";
                        $stmt2 = Bt_doQuery(
                            $GLOBALS['db2'], 
                            $queryDs,
                            array($resoucesRecordsetAdr->docserver_id)
                        );
                        if ($stmt2->rowCount() == 0) {
                            Bt_exitBatch(13, 'Docserver:' 
                                . $resoucesRecordsetAdr->docserver_id . ' not found');
                            break;
                        } else {
                            $dsRecordset = $stmt2->fetchObject();
                            $dsPath = $dsRecordset->path_template;
                        }
                        array_push($resourcesArray[$cptRes]['adr'], array(
                                'res_id' => $resoucesRecordsetAdr->res_id,
                                'docserver_id' => $resoucesRecordsetAdr->docserver_id,
                                'path_template' => $dsPath,
                                'path' => str_replace('#', DIRECTORY_SEPARATOR, 
                                    $resoucesRecordsetAdr->path),
                                'filename' => $resoucesRecordsetAdr->filename,
                                'fingerprint' => $resoucesRecordsetAdr->fingerprint,
                            )
                        );
                    }
                }
                //history
                $query = "insert into " . HISTORY_TABLE
                       . " (table_name, record_id, event_type, user_id, "
                       . "event_date, info, id_module) values (?, ?, 'ADD', 'PURGE_BOT', '"
                       . date("d") . "/" . date("m") . "/" . date("Y") . " " . date("H") 
                       . ":" . date("i") . ":" . date("s")
                       . "', ?, 'life_cyle')";
                $stmt = Bt_doQuery(
                    $GLOBALS['db'], 
                    $query,
                    array(
                        $GLOBALS['table'],
                        $resourcesArray[$cptRes]["res_id"],
                        "purge, where clause:" 
                            . str_replace("'", "''", $GLOBALS['whereClause'])
                            . ", collection:" . $GLOBALS['collection']
                    )
                );
                $GLOBALS['totalProcessedResources']++;
            }
            //print_r($resourcesArray);
            $state = 'DELETE_RES_ON_FS';
            //$state = 'END';
            break;
        /**********************************************************************/
        /*                          DELETE_RES_ON_FS                          */
        /*                                                                    */
        /**********************************************************************/
        case "DELETE_RES_ON_FS" :
            $cptRes = 0;
            $countRA = count($resourcesArray);
            for ($cptRes = 0;$cptRes < $countRA;$cptRes++) {
                $GLOBALS['logger']->write('Prepare file deletion for res_id:' 
                    . $resourcesArray[$cptRes]["res_id"], 'INFO');
                $countAdr = count($resourcesArray[$cptRes]['adr']);
                if ($countAdr > 0) {
                    $cptAdr = 0;
                    for ($cptAdr = 0;$cptAdr < $countAdr;$cptAdr++) {
                        $path = $resourcesArray[$cptRes]['adr'][$cptAdr]['path_template'] 
                            . $resourcesArray[$cptRes]['adr'][$cptAdr]['path']
                            . $resourcesArray[$cptRes]['adr'][$cptAdr]['filename'];
                        //echo $path . PHP_EOL;
                        if (file_exists($path)) {
                            unlink($path);
                        } else {
                            $GLOBALS['logger']->write('File for the collection ' 
                                . $GLOBALS['collection'] . ' and res_id ' 
                                . $resourcesArray[$cptRes]['res_id'] . ' not exits : '
                                . $path, 'WARNING');
                        }
                    }
                } else {
                    $path = $resourcesArray[$cptRes]['path_template'] 
                          . $resourcesArray[$cptRes]['path']
                          . $resourcesArray[$cptRes]['filename'];
                    //echo $path . PHP_EOL;
                    if (file_exists($path)) {
                        unlink($path);
                    } else {
                        $GLOBALS['logger']->write('File for the collection ' 
                            . $GLOBALS['collection'] . ' and res_id ' 
                            . $resourcesArray[$cptRes]['res_id'] . ' not exits : '
                            . $path, 'WARNING');
                    }
                }
            }
            $state = 'DELETE_RES_ON_DB';
            break;
        /**********************************************************************/
        /*                          DELETE_RES_ON_DB                          */
        /*                                                                    */
        /**********************************************************************/
        case "DELETE_RES_ON_DB" :

            $arrayEntityId = array();
            $arrayEntityNbDocs = array();
            $arraySubEntitiesNbDocs = array();

            array_push($arrayEntityId, 'Nom de l\'entité');
            array_push($arrayEntityNbDocs, 'Nombre de document dans l\'entité');
            array_push($arraySubEntitiesNbDocs, 'Nombre de document dans l\'entité et sous entités');

            $repertoiredujour = date('Y-m-d-Hi');
            $chemin = $GLOBALS['exportFolder'].'DocumentsSupprimes-'.$repertoiredujour.'.csv';
            $delimiteur = ";";

            $DeletedFiles = fopen($chemin, 'w+');

            fprintf($DeletedFiles, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv(
                $DeletedFiles, 
                array(
                    "Res id", 
                    "Num Chrono", 
                    "Type de document", 
                    "Service destinataire", 
                    "Objet"
                ), 
                $delimiteur
            );

            for ($cptRes = 0;$cptRes < $countRA;$cptRes++) {

                $queryDestination = "SELECT destination FROM " . $GLOBALS['table'] 
                   . " WHERE res_id = ?";
                $stmt = Bt_doQuery(
                    $GLOBALS['db2'], 
                    $queryDestination,
                    array($resourcesArray[$cptRes]["res_id"])
                );
                if ($stmt->rowCount() > 0) {
                    $destinationRes = $stmt->fetchObject();
                    if (!in_array($destinationRes->destination, $arrayEntityId)) {
                        array_push($arrayEntityId, $destinationRes->destination);
                        array_push($arrayEntityNbDocs, 1);
                    } else {
                        $keyEntity = array_search($destinationRes->destination, $arrayEntityId);
                        $arrayEntityNbDocs[$keyEntity]++;
                    }
                }
                $deleteResQuery = '';
                $deleteAdrQuery = '';
                $deleteNotesQuery = '';
                $GLOBALS['logger']->write('Prepare sql deletion for res_id:' 
                    . $resourcesArray[$cptRes]["res_id"], 'INFO');
                $queryDeletedFile = "SELECT res_id, alt_identifier, "
                                    . "dt.description as \"type_id_label\", entity_label, subject 
                                    FROM " . $GLOBALS['view'] . " r 
                                    LEFT JOIN doctypes dt ON r.type_id = dt.type_id 
                                    WHERE res_id = ?";
                $stmt = Bt_doQuery(
                    $GLOBALS['db2'], 
                    $queryDeletedFile,
                    array($resourcesArray[$cptRes]["res_id"])
                );
                $DataDeletedFile = $stmt->fetchObject();

                fputcsv(
                    $DeletedFiles, 
                    array(
                        $DataDeletedFile->res_id, 
                        $DataDeletedFile->alt_identifier, 
                        $DataDeletedFile->type_id_label, 
                        $DataDeletedFile->entity_label, 
                        $DataDeletedFile->subject
                    ), 
                    $delimiteur
                );

                $deleteResQuery = "DELETE FROM " . $GLOBALS['table']
                   . " WHERE res_id = ?";
                $stmt = Bt_doQuery(
                    $GLOBALS['db'], 
                    $deleteResQuery,
                    array($resourcesArray[$cptRes]["res_id"])
                );

                if ($GLOBALS['extensionTable'] <> "") {
                    $deleteExtQuery = "DELETE FROM " . $GLOBALS['extensionTable']
                       . " WHERE res_id = ?";
                    $stmt = Bt_doQuery(
                        $GLOBALS['db'], 
                        $deleteExtQuery,
                        array($resourcesArray[$cptRes]["res_id"])
                    );
                }

                if ($GLOBALS['versionTable'] <> "") {
                    $deleteVersionQuery = "DELETE FROM " . $GLOBALS['versionTable']
                       . " WHERE res_id_master = ?";
                    $stmt = Bt_doQuery(
                        $GLOBALS['db'], 
                        $deleteVersionQuery,
                        array($resourcesArray[$cptRes]["res_id"])
                    );
                }

                if ($GLOBALS['adrTable'] <> "") {
                    $deleteAdrQuery = "DELETE FROM " . $GLOBALS['adrTable']
                       . " WHERE res_id = ?";
                    $stmt = Bt_doQuery(
                        $GLOBALS['db'], 
                        $deleteAdrQuery,
                        array($resourcesArray[$cptRes]["res_id"])
                    );
                }

                $deleteAdrQuery = "DELETE FROM contacts_res WHERE res_id = ?";
                $stmt = Bt_doQuery(
                    $GLOBALS['db'], 
                    $deleteAdrQuery,
                    array($resourcesArray[$cptRes]["res_id"])
                );

                $deleteNotesQuery = "DELETE FROM notes "
                   . " WHERE coll_id = ? "
                   . " and identifier = ?";
                $stmt = Bt_doQuery(
                    $GLOBALS['db'], 
                    $deleteNotesQuery, 
                    array(
                        $GLOBALS['collection'], 
                        $resourcesArray[$cptRes]["res_id"]
                    )
                );

                $deleteLinkedQuery = "DELETE FROM res_linked "
                   . " WHERE coll_id = ? "
                   . " and (res_child = ? or res_parent = ?)";
                $stmt = Bt_doQuery(
                    $GLOBALS['db'], 
                    $deleteLinkedQuery, 
                    array(
                        $GLOBALS['collection'],
                        $resourcesArray[$cptRes]["res_id"],
                        $resourcesArray[$cptRes]["res_id"]
                    )
                );

                $deleteTagsQuery = "DELETE FROM tags "
                   . " WHERE coll_id = ? "
                   . " and res_id = ?";
                $stmt = Bt_doQuery(
                    $GLOBALS['db'], 
                    $deleteTagsQuery, 
                    array(
                        $GLOBALS['collection'],
                        $resourcesArray[$cptRes]["res_id"]
                    )
                );

                $deleteAttachmentsQuery = "DELETE FROM res_attachments "
                   . " WHERE coll_id = ? "
                   . " and res_id_master = ?";
                $stmt = Bt_doQuery(
                    $GLOBALS['db'], 
                    $deleteAttachmentsQuery, 
                    array(
                        $GLOBALS['collection'],
                        $resourcesArray[$cptRes]["res_id"]
                    )
                );

                $deleteCasesQuery = "DELETE FROM cases_res "
                   . " WHERE res_id = ? ";
                $stmt = Bt_doQuery(
                    $GLOBALS['db'], 
                    $deleteCasesQuery, 
                    array($resourcesArray[$cptRes]["res_id"]));
            }

            fclose($DeletedFiles);

            $chemin = $GLOBALS['exportFolder'].'DocumentsSupprimesParEntites-'
                . $repertoiredujour . '.csv';

            $fichier_csv = fopen($chemin, 'w+');

            fprintf($fichier_csv, chr(0xEF).chr(0xBB).chr(0xBF));

            foreach ($arrayEntityId as $key => $value) {
                if ($key > 0) {
                    $subEntities_tmp = array();
                    $subEntities = array();
                    $subEntities_tmp = getEntityChildrenTree($subEntities_tmp, $value);
                    for ($iSubEntities=0;$iSubEntities<count($subEntities_tmp);$iSubEntities++) {
                        if (in_array($subEntities_tmp[$iSubEntities]['ID'], $arrayEntityId)) {
                            array_push($subEntities, $subEntities_tmp[$iSubEntities]['ID']);
                        }
                    }
                    array_push($subEntities, $value);
                    $nbDocsSubEntities = 0;
                    foreach ($subEntities as $value2) {
                        $SubEntitiesKeys = array_search($value2, $arrayEntityId);
                        $nbDocsSubEntities = $nbDocsSubEntities + $arrayEntityNbDocs[$SubEntitiesKeys];
                    }
                    $queryEntityLabel = "SELECT entity_label FROM entities WHERE entity_id = ?";
                    $stmt = Bt_doQuery($GLOBALS['db2'], $queryEntityLabel, array($value));
                    $EntityDB = $stmt->fetchObject();
                    fputcsv(
                        $fichier_csv, 
                        array(
                            $EntityDB->entity_label, 
                            $arrayEntityNbDocs[$key], 
                            $nbDocsSubEntities
                        ), 
                        $delimiteur
                    );
                } else {
                    fputcsv(
                        $fichier_csv, 
                        array(
                            $value, 
                            $arrayEntityNbDocs[$key], 
                            $arraySubEntitiesNbDocs[$key]
                        ), 
                        $delimiteur
                    );
                }
                
            }
            fclose($fichier_csv);

            if ($GLOBALS['PurgeMode'] == "both") {
                $state = 'DELETE_CONTACTS_ON_DB';
            } else {
                $state = 'END';
            }
            
            break;

        /**********************************************************************/
        /*                          DELETE_CONTACTS_ON_DB                     */
        /*                                                                    */
        /**********************************************************************/
        case "DELETE_CONTACTS_ON_DB" :

            if ($GLOBALS['CleanContactsMoral'] == "true" || $GLOBALS['CleanContactsNonMoral'] == "true") {

                $GLOBALS['logger']->write('Clean contacts case', 'INFO');

                if ($GLOBALS['CleanContactsMoral'] == "true"){
                    $GLOBALS['logger']->write('Clean Moral Contacts', 'INFO');
                    $stmt = Bt_doQuery(
                        $GLOBALS['db'], 
                        "SELECT contact_id FROM contacts_v2 WHERE is_corporate_person = 'Y' "
                    );
                } else if ($GLOBALS['CleanContactsNonMoral'] == "true"){
                    $GLOBALS['logger']->write('Clean Non Moral Contacts', 'INFO');
                     $stmt = Bt_doQuery(
                        $GLOBALS['db'], 
                        "SELECT contact_id FROM contacts_v2 WHERE is_corporate_person = 'N' "
                    );                   
                }

                while($ContactToClean = $stmt->fetchObject()){
                    $stmt2 = Bt_doQuery(
                        $GLOBALS['db'], 
                        "SELECT count(*) as total FROM res_view_letterbox WHERE contact_id = ? ", 
                        array($ContactToClean->contact_id)
                    );
                    $totalContacts = $stmt2->fetchObject();

                    $stmt3 = Bt_doQuery(
                        $GLOBALS['db'], 
                        "SELECT count(*) as total FROM contacts_res WHERE contact_id = ? ", 
                        array($ContactToClean->contact_id)
                    );
                    $totalContactsMulti = $stmt3->fetchObject();

                    if ($totalContacts->total < 1 && $totalContactsMulti->total < 1) {

                        $GLOBALS['logger']->write('Clean Contact ' . $ContactToClean->contact_id, 'DEBUG');

                        Bt_doQuery(
                            $GLOBALS['db'], 
                            "DELETE FROM contact_addresses WHERE contact_id = ?", 
                            array($ContactToClean->contact_id)
                        );

                        Bt_doQuery(
                            $GLOBALS['db'], 
                            "DELETE FROM contacts_v2 WHERE contact_id = ? ", 
                            array($ContactToClean->contact_id)
                        );                        
                    }
                }

            }

            $state = 'END';
            break;

    }

}
$GLOBALS['logger']->write('End of process', 'INFO');
Bt_logInDataBase(
    $GLOBALS['totalProcessedResources'], 0, 'process without error'
);
unlink($GLOBALS['lckFile']);
exit($GLOBALS['exitCode']);
