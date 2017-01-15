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
 * @brief Custom file to create the Preservation Description Information (PDI)
 * OAIS features
 * @file
 * @author  Laurent Giovannoni  <dev@maarch.org>
 * @date $date$
 * @version $Revision$
 * @ingroup life_cycle
 */

/**
 * Created the Preservation Description Information (PDI)
 * Created an xml file in the temporary directory of the batch
 * @param array $resInContainer array of resources in a container
 *              res_id, source_path, fingerprint
 * @return nothing
 */
function createPDI($resInContainer)
{
    $tmpXML = $GLOBALS['tmpDirectory'] . DIRECTORY_SEPARATOR . 'pdi.xml';
    $docXML = new DomDocument('1.0', 'utf-8');
    $docXML->preserveWhiteSpace = true;
    $docXML->formatOutput = true;
    //root
    $root = $docXML->createElement('ROOT');
    $docXML->appendChild($root);
    $commentString = _PDI_COMMENT_ROOT;
    $commentNodeRoot = $docXML->createComment($commentString);
    $root->appendChild($commentNodeRoot);
    //access rights
    $accessRights = $docXML->createElement('ACCESS_RIGHTS');
    $root->appendChild($accessRights);
    $query = "select * from security where coll_id = ?";
    $stmt = Bt_doQuery(
        $GLOBALS['db3'], 
        $query, 
        array($GLOBALS['collection'])
    );
    while ($securityRecordset = $stmt->fetchObject()) {
        //an access right
        $accessRight = $docXML->createElement('ACCESS_RIGHT');
        $accessRights->appendChild($accessRight);
        $group = $docXML->createElement(
            'GROUP', 
            $securityRecordset->group_id
        );
        $accessRight->appendChild($group);
        $collection = $docXML->createElement(
            'COLLECTION', $securityRecordset->coll_id
        );
        $accessRight->appendChild($collection);
        $whereClause = $docXML->createElement(
            'WHERE_CLAUSE', $securityRecordset->where_clause
        );
        $accessRight->appendChild($whereClause);
        $comment = $docXML->createElement(
            'COMMENT', $securityRecordset->maarch_comment
        );
        $accessRight->appendChild($comment);
        $canInsert = $docXML->createElement(
            'CAN_INSERT', $securityRecordset->can_insert
        );
        $accessRight->appendChild($canInsert);
        $canUpdate = $docXML->createElement(
            'CAN_UPDATE', $securityRecordset->can_update
        );
        $accessRight->appendChild($canUpdate);
        $canDelete = $docXML->createElement(
            'CAN_DELETE', $securityRecordset->can_delete
        );
        $accessRight->appendChild($canDelete);
    }
    for ($cptRes = 0;$cptRes < count($resInContainer);$cptRes++) {
        //a record
        $pdi = $docXML->createElement('PDI');
        $root->appendChild($pdi);
        $pdi->setAttributeNode(
            new DOMAttr('AIU', $resInContainer[$cptRes]['offset_doc'])
        );
        $pdi->setAttributeNode(
            new DOMAttr('RES_ID', $resInContainer[$cptRes]['res_id'])
        );
        $query = "select * from " . $GLOBALS['view'] 
               . " where res_id = ? " 
               . $GLOBALS['creationDateClause'];
        $stmt = Bt_doQuery(
            $GLOBALS['db3'], 
            $query, 
            array($resInContainer[$cptRes]['res_id'])
        );
        while ($resRecordset = $stmt->fetchObject()) {
            //a record
            //a provenance
            $provenance = $docXML->createElement('PROVENANCE');
            $pdi->appendChild($provenance);
            if (isset($resRecordset->publisher)) {
                $publisher = $docXML->createElement(
                    'PUBLISHER', $resRecordset->publisher
                );
            } else {
                $publisher = $docXML->createElement('PUBLISHER', '');
            }
            $publisher->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $provenance->appendChild($publisher);
            if (isset($resRecordset->contributor)) {
                $contributor = $docXML->createElement(
                    'CONTRIBUTOR', $resRecordset->contributor
                );
            } else {
                $contributor = $docXML->createElement('CONTRIBUTOR', '');
            }
            $contributor->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $provenance->appendChild($contributor);
            $typist = $docXML->createElement(
                'TYPIST', $resRecordset->typist
            );
            $typist->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $provenance->appendChild($typist);
            if (isset($resRecordset->author)) {
                $author = $docXML->createElement(
                    'AUTHOR', $resRecordset->author
                );
            } else {
                $author = $docXML->createElement('AUTHOR', '');
            }
            $author->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $provenance->appendChild($author);
            if (isset($resRecordset->source)) {
                $source = $docXML->createElement(
                    'SOURCE', $resRecordset->source
                );
            } else {
                $source = $docXML->createElement('SOURCE', '');
            }
            $source->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $provenance->appendChild($source);
            if (isset($resRecordset->scan_date)) {
                $scanDate = $docXML->createElement(
                    'SCAN_DATE', $resRecordset->scan_date
                );
            } else {
                $scanDate = $docXML->createElement('SCAN_DATE', '');
            }    
            $scanDate->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $provenance->appendChild($scanDate);
            if (isset($resRecordset->scan_user)) {
                $scanUser = $docXML->createElement(
                    'SCAN_USER', $resRecordset->scan_user
                );
            } else {
                $scanUser = $docXML->createElement('SCAN_USER', '');
            }
            $scanUser->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $provenance->appendChild($scanUser);
            if (isset($resRecordset->scan_location)) {
                $scanLocation = $docXML->createElement(
                    'SCAN_LOCATION', $resRecordset->scan_location
                );
            } else {
                $scanLocation = $docXML->createElement(
                    'SCAN_LOCATION', ''
                );
            }
            $scanLocation->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $provenance->appendChild($scanLocation);
            if (isset($resRecordset->scan_wkstation)) {
                $scanWkstation = $docXML->createElement(
                    'SCAN_WKSTATION', $resRecordset->scan_wkstation
                );
            } else {
                $scanWkstation = $docXML->createElement(
                    'SCAN_WKSTATION', ''
                );
            }
            $scanWkstation->setAttributeNode(
                new DOMAttr('SOURCE', 'RES')
            );
            $provenance->appendChild($scanWkstation);
            if (isset($resRecordset->scan_batch)) {
                $scanBatch = $docXML->createElement(
                    'SCAN_BATCH', $resRecordset->scan_batch
                );
            } else {
                $scanBatch = $docXML->createElement('SCAN_BATCH', '');
            }
            $scanBatch->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $provenance->appendChild($scanBatch);
            if (isset($resRecordset->envelop_id)) {
                $envelopId = $docXML->createElement(
                    'ENVELOP_ID', $resRecordset->envelop_id
                );
            } else {
                $envelopId = $docXML->createElement('ENVELOP_ID', '');
            }
            $envelopId->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $provenance->appendChild($envelopId);
            if (isset($resRecordset->origin)) {
                $origin = $docXML->createElement(
                    'ORIGIN', $resRecordset->origin
                );
            } else {
                $origin = $docXML->createElement('ORIGIN', '');
            }
            $origin->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $provenance->appendChild($origin);
            if (isset($resRecordset->is_ingoing)) {
                $isIngoing = $docXML->createElement(
                    'IS_INGOING', $resRecordset->is_ingoing
                );
            } else {
                $isIngoing = $docXML->createElement('IS_INGOING', '');
            }
            $isIngoing->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $provenance->appendChild($isIngoing);
            if ($GLOBALS['enableHistory']) {
                if (isset($resRecordset->history)) {
                    $history = $docXML->createElement(
                        'HISTORY', $resRecordset->history
                    );
                } else {
                    $history = $docXML->createElement('HISTORY', '');
                }
                $history->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
                $provenance->appendChild($history);
                $commentString = _PDI_COMMENT_HISTORY;
                $commentNodeHistory = $docXML->createComment($commentString);
                $history->appendChild($commentNodeHistory);
            }
            //a reference
            $reference = $docXML->createElement('REFERENCE');
            $pdi->appendChild($reference);
            if (isset($resRecordset->title)) {
                $title = $docXML->createElement(
                    'TITLE', htmlentities($resRecordset->title)
                );
            } else {
                $title = $docXML->createElement('TITLE', '');
            }
            $title->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $title->setAttributeNode(new DOMAttr('LABEL', _TITLE2));
            $reference->appendChild($title);
            if (isset($resRecordset->subject)) {
                $subject = $docXML->createElement(
                    'SUBJECT', $resRecordset->subject
                );
            } else {
                $subject = $docXML->createElement('SUBJECT', '');
            }
            $subject->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $subject->setAttributeNode(new DOMAttr('LABEL', _SUBJECT));
            $reference->appendChild($subject);
            if (isset($resRecordset->description)) {
                $description = $docXML->createElement(
                    'DESCRIPTION', $resRecordset->description
                );
            } else {
                $description = $docXML->createElement('DESCRIPTION', '');
            }
            $description->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $reference->appendChild($description);
            if (isset($resRecordset->identifier)) {
                $identifier = $docXML->createElement(
                    'IDENTIFIER', $resRecordset->identifier
                );
            } else {
                $identifier = $docXML->createElement('IDENTIFIER', '');
            }
            $identifier->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $reference->appendChild($identifier);
            if (isset($resRecordset->coverage)) {
                $coverage = $docXML->createElement(
                    'COVERAGE', $resRecordset->coverage
                );
            } else {
                $coverage = $docXML->createElement('COVERAGE', '');
            }
            $coverage->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $reference->appendChild($coverage);
            if (isset($resRecordset->doc_date)) {
                $docDate = $docXML->createElement(
                    'DOC_DATE', $resRecordset->doc_date
                );
            } else {
                $docDate = $docXML->createElement('DOC_DATE', '');
            }
            $docDate->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $reference->appendChild($docDate);
            if (isset($resRecordset->type_id)) {
                $typeId = $docXML->createElement(
                    'TYPE_ID', $resRecordset->type_id
                );
            } else {
                $typeId = $docXML->createElement('TYPE_ID', '');
            }
            $typeId->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $reference->appendChild($typeId);
            if (isset($resRecordset->type_label)) {
                $typeLabel = $docXML->createElement(
                    'TYPE_LABEL', $resRecordset->type_label
                );
            } else {
                $typeLabel = $docXML->createElement('TYPE_LABEL', '');
            }
            $typeLabel->setAttributeNode(new DOMAttr('SOURCE', 'VIEW'));
            $reference->appendChild($typeLabel);
            //customs
            if (isset($resRecordset->doc_custom_t1)) {
                $customT1 = $docXML->createElement(
                    'CUSTOM_T1', $resRecordset->doc_custom_t1
                );
            } else {
                $customT1 = $docXML->createElement('CUSTOM_T1', '');
            }
            $customT1->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $customT1->setAttributeNode(
                new DOMAttr('LABEL', _IDENTIFIER)
            );
            $reference->appendChild($customT1);
            if (isset($resRecordset->doc_custom_n1)) {
                $customN1 = $docXML->createElement(
                    'CUSTOM_N1', $resRecordset->doc_custom_n1
                );
            } else {
                $customN1 = $docXML->createElement('CUSTOM_N1', '');
            }
            $customN1->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $customN1->setAttributeNode(new DOMAttr('LABEL', _AMOUNT));
            $reference->appendChild($customN1);
            if (isset($resRecordset->doc_custom_d1)) {
                $customD1 = $docXML->createElement(
                    'CUSTOM_D1', $resRecordset->doc_custom_d1
                );
            } else {
                $customD1 = $docXML->createElement('CUSTOM_D1', '');
            }
            $customD1->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $customD1->setAttributeNode(new DOMAttr('LABEL', _CUSTOM_D1));
            $reference->appendChild($customD1);
            if (isset($resRecordset->doc_custom_t2)) {
                $customT2 = $docXML->createElement(
                    'CUSTOM_T2', $resRecordset->doc_custom_t2
                );
            } else {
                $customT2 = $docXML->createElement('CUSTOM_T2', '');
            }
            $customT2->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $customT2->setAttributeNode(
                new DOMAttr('LABEL', _CONTACT_NAME)
            );
            $reference->appendChild($customT2);
            if (isset($resRecordset->doc_custom_t3)) {
                $customT3 = $docXML->createElement(
                    'CUSTOM_T3', $resRecordset->doc_custom_t3
                );
            } else {
                $customT3 = $docXML->createElement('CUSTOM_T3', '');
            }
            $customT3->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $customT3->setAttributeNode(new DOMAttr('LABEL', _COUNTRY));
            $reference->appendChild($customT3);
            if (isset($resRecordset->doc_custom_t4)) {
                $customT4 = $docXML->createElement(
                    'CUSTOM_T4', $resRecordset->doc_custom_t4
                );
            } else {
                $customT4 = $docXML->createElement('CUSTOM_T4', '');
            }
            $customT4->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $customT4->setAttributeNode(new DOMAttr('LABEL', _CUSTOMER));
            $reference->appendChild($customT4);
            if (isset($resRecordset->doc_custom_t5)) {
                $customT5 = $docXML->createElement(
                    'CUSTOM_T5', $resRecordset->doc_custom_t5
                );
            } else {
                $customT5 = $docXML->createElement('CUSTOM_T5', '');
            }
            $customT5->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $customT5->setAttributeNode(new DOMAttr('LABEL', _PO_NUMBER));
            $reference->appendChild($customT5);
            //a fixity
            $fixity = $docXML->createElement('FIXITY');
            $pdi->appendChild($fixity);
            if (isset($resRecordset->fingerprint)) {
                $fingerprint = $docXML->createElement(
                    'FINGERPRINT', $resRecordset->fingerprint
                );
            } else {
                $fingerprint = $docXML->createElement('FINGERPRINT', '');
            }
            $fingerprint->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $fixity->appendChild($fingerprint);
            if (isset($resRecordset->scan_postmark)) {
                $scanPostmark = $docXML->createElement(
                    'SCAN_POSTMARK', $resRecordset->scan_postmark
                );
            } else {
                $scanPostmark = $docXML->createElement(
                    'SCAN_POSTMARK', ''
                );
            }
            $scanPostmark->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $fixity->appendChild($scanPostmark);
            if (isset($resRecordset->filesize)) {
                $filesize = $docXML->createElement(
                    'FILESIZE', $resRecordset->filesize
                );
            } else {
                $filesize = $docXML->createElement('FILESIZE', '');
            }
            $filesize->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $fixity->appendChild($filesize);
            if (isset($resRecordset->page_count)) {
                $pageCount = $docXML->createElement(
                    'PAGE_COUNT', $resRecordset->page_count
                );
            } else {
                $pageCount = $docXML->createElement('PAGE_COUNT', '');
            }
            $pageCount->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $fixity->appendChild($pageCount);
            //a context
            $context = $docXML->createElement('CONTEXT');
            $pdi->appendChild($context);
            if (isset($resRecordset->res_id)) {
                $resId = $docXML->createElement(
                    'RES_ID', $resRecordset->res_id
                );
            } else {
                $resId = $docXML->createElement('RES_ID', '');
            }
            $resId->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $context->appendChild($resId);
            $collection = $docXML->createElement(
                'COLLECTION', $GLOBALS['collection']
            );
            $collection->setAttributeNode(
                new DOMAttr('SOURCE', 'LIFECYCLE')
            );
            $context->appendChild($collection);
            $fingerprintMode = $docXML->createElement(
                'FINGERPRINT_MODE', 
                $GLOBALS['docserverSourceFingerprint']
            );
            $fingerprintMode->setAttributeNode(
                new DOMAttr('SOURCE', 'LIFECYCLE')
            );
            $context->appendChild($fingerprintMode);
            $format = $docXML->createElement(
                'FORMAT', $resRecordset->format
            );
            $format->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $context->appendChild($format);
            if (isset($resRecordset->creation_date)) {
                $creationDate = $docXML->createElement(
                    'CREATION_DATE', $resRecordset->creation_date
                );
            } else {
                $creationDate = $docXML->createElement(
                    'CREATION_DATE', ''
                );
            }
            $creationDate->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $context->appendChild($creationDate);
            if (isset($resRecordset->doc_language)) {
                $docLanguage = $docXML->createElement(
                    'DOC_LANGUAGE', $resRecordset->doc_language
                );
            } else {
                $docLanguage = $docXML->createElement('DOC_LANGUAGE', '');
            }
            $docLanguage->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $context->appendChild($docLanguage);
            if (isset($resRecordset->relation)) {
                $relation = $docXML->createElement(
                    'RELATION', $resRecordset->relation
                );
            } else {
                $relation = $docXML->createElement('RELATION', '');
            }
            $relation->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $context->appendChild($relation);
            if (isset($resRecordset->coverage)) {
                $coverage = $docXML->createElement(
                    'COVERAGE', $resRecordset->coverage
                );
            } else {
                $coverage = $docXML->createElement('COVERAGE', '');
            }
            $coverage->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $context->appendChild($coverage);
            if (isset($resRecordset->rights)) {
                $rights = $docXML->createElement(
                    'RIGHTS', $resRecordset->rights
                );
            } else {
                $rights = $docXML->createElement('RIGHTS', '');
            }
            $rights->setAttributeNode(new DOMAttr('SOURCE', 'RES'));
            $context->appendChild($rights);
        }
    }
    //save the xml
    $docXML->save($tmpXML);
}
