<?php
require_once 'xmlParser.class.php';
require_once 'cmis_class_object.php';
require_once 'cmis_class_property.php';
//require_once 'PropertyBoolean.class.php';
//require_once 'PropertyDateTime.class.php';
//require_once 'PropertyInteger.class.php';
//require_once 'PropertyDecimal.class.php';
//require_once 'PropertyHtml.class.php';
//require_once 'PropertyId.class.php';
//require_once 'PropertyString.class.php';
//require_once 'PropertyUri.class.php';

class resCMIS extends objectCMIS
{
    var $strReturn = 'THE RESULT IS A RES WITH ';

    private $versionable;
    private $contentStreamAllowed ;

    //private $renditions;

    //properties
    private $name;
    private $isImmutable;
    /*   //  version : not supported
    private $isLatestVersion;
    private $isMajorVersion;
    private $isLatestMajorVersion;
    private $versionLabel;
    private $versionSeriesId;
    private $versionSeriesCheckedOut;
    private $versionSeriesCheckedOutBy;
    private $versionSeriesCheckedOutId;
    private $checkinComment;
    */
    private $contentStreamLength;
    private $contentStreamBase64;
    private $contentStreamMimeType;
    private $contentStreamFileName;
    private $contentStreamId;

    //res_id = objectId
    private $type;
    private $contact;
    private $entity_label;
    private $dest_user;
    //doc_date = creationDate
    private $process_limit_date;
    private $url;

    private $propertiesInXml ;

    public function entryMethod($atomFileContent, $requestedResourceId, $requestedCollection)
    {
        $view = '';
        if (isset($requestedCollection) && !empty($requestedCollection)) {
            $sec = new security();
            $view = $sec->retrieve_view_from_coll_id($requestedCollection);
        }
        if (empty($view)) {
            echo "<br />ERREUR : the collection not exists.<br />";
        }
        if (isset($atomFileContent) && !isset($requestedResourceId)) {
            //new xml parser
            $xmlParser = new xmlParser();
            //build a query from xml file
            $query = $xmlParser->parseQuery($atomFileContent);
            //get the list of documents : execute query
            if ($documents = $query->executeQuery()){
                $title = 'Result for '.$query->getStatement();
                $resAtom = resCMIS::getFeed($documents, trim($title));
                echo $resAtom->saveXML();
            } else{
                //TODO throw constraintException or runtime
                echo "<br />ERREUR : constraintException or runtime.<br />";
            }
        } elseif (!isset($atomFileContent) && isset($requestedResourceId)){

            //retrieve the document whose id is $requestedResourceId
            if ($res = $this->getDocument(intval($requestedResourceId), $view)){
                $resAtom = $res->toAtomXml();
                echo $resAtom->saveXML();
            } else {
                //TODO throw objectNotFound
                echo "<br />ERREUR : objectNotFound.<br />";
            }
        } else {
            //TODO throw notSupported or invalidArgument
            echo "<br />ERREUR : notSupported.<br />";
        }
       //return $requestedResourceId . ' ' . $atomFileContent;
    }



    public function DocumentCmis()
    {
        $this->id = "cmis:document";
        $this->queryName = "cmis:document";
        $this->baseId = "cmis:document";
        $this->versionable = false;
        $this->fileable = true;
        $this->localName = "cmis:document";
        $this->displayName = "cmis:document";
        $this->creatable = false;

        //properties
        $this->name = new PropertyString($required, $inherited, $propertyType,
            $cardinality, $updatability, $choices, $openChoice, $queryable,
            $orderable);
        $this->name->setId('cmis:name');
        $this->name->setInherited(false);
        $this->name->setPropertyType('String');
        $this->name->setCardinality('Single');

        $this->parentId = new PropertyId(false, false, 'Id', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);

        $this->objectId = new PropertyId(false, false, 'Id', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->objectId->setId("cmis:objectId");
        $this->objectId->setQueryName("cmis:objectId");
        $this->objectId->setDisplayName("cmis:objectId");

        $this->baseTypeId = new PropertyId(false, false, 'Id', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->baseTypeId->setId("cmis:baseTypeId");
        $this->baseTypeId->setDisplayName("cmis:baseTypeId");

        $this->objectTypeId = new PropertyId(true, false, 'Id', 'Single',
                 'oncreate', 'NotApplicable', 'NotApplicable', false, false);
        $this->objectTypeId->setId('objectTypeId');

        $this->createdBy = new PropertyString(false, false, 'String', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->createdBy->setId('createdBy');
        $this->createdBy->setQueryName("cmis:createdBy");

        $this->creationDate = new PropertyDateTime(false, false, 'DateTime', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->creationDate->setId('creationDate');
        $this->creationDate->setQueryName("cmis:creationDate");

        $this->lastModifiedBy = new PropertyString(false, false, 'String', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->lastModifiedBy->setId('lastModifiedBy');
        $this->lastModifiedBy->setQueryName("cmis:lastModifiedBy");

        $this->lastModificationDate = new PropertyDateTime(false, false, 'DateTime', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->lastModificationDate->setId('lastModificationDate');
        $this->lastModificationDate->setQueryName("cmis:lastModificationDate");

        $this->changeToken = new PropertyString(false, false, 'String', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->changeToken->setId('changeToken');

        //true
        $this->isImmutable = new PropertyBoolean(false, false, 'Boolean', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->isImmutable->setId('isImmutable');
        $this->isImmutable->setValue(true);

        /*
        $this->isLatestVersion = new PropertyBoolean(false, false, 'Boolean', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->isLatestVersion->setId('isLatestVersion');

        $this->isMajorVersion = new PropertyBoolean(false, false, 'Boolean', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->isMajorVersion->setId('isMajorVersion');

        $this->isLatestMajorVersion = new PropertyBoolean(false, false, 'Boolean', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->isLatestMajorVersion->setId('isLatestMajorVersion');

        $this->versionLabel = new PropertyString(false, false, 'String', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->versionLabel->setId('versionLabel');

        $this->versionSeriesId = new PropertyId(false, false, 'Id', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->versionSeriesId->setId('versionSeriesId');

        $this->versionSeriesCheckedOut = new PropertyBoolean(false, false, 'Boolean', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->versionSeriesCheckedOut->setId('versionSeriesCheckedOut');

        $this->versionSeriesCheckedOutBy = new PropertyString(false, false, 'String', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->versionSeriesCheckedOutBy->setId('versionSeriesCheckedOutBy');

        $this->versionSeriesCheckedOutId = new PropertyId(false, false, 'Id', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->versionSeriesCheckedOutId->setId('versionSeriesCheckedOutId');

        $this->checkinComment = new PropertyString(false, false, 'String', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->checkinComment->setId('checkinComment');
        */

        $this->contentStreamLength = new PropertyInteger(false, false, 'Integer', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->contentStreamLength->setId('contentStreamLength');

        $this->contentStreamBase64 = new PropertyString(false, false, 'String', 'Single',
                'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->contentStreamBase64->setId('contentStreamBase64');

        $this->contentStreamMimeType = new PropertyString(false, false, 'String', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->contentStreamMimeType->setId('contentStreamMimeType');

        $this->contentStreamFileName = new PropertyString($required, $inherited, $propertyType,
            $cardinality, $updatability, $choices, $openChoice, $queryable,
            $orderable);
        $this->contentStreamFileName->setRequired(false);
        $this->contentStreamFileName->setInherited(false);
        $this->contentStreamFileName->setPropertyType('String');
        $this->contentStreamFileName->setCardinality('Single');
        $this->contentStreamFileName->setId('contentStreamFileName');

        $this->contentStreamId = new PropertyId(false, false, 'Id', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->contentStreamId->setId('contentStreamId');

        $this->type = new PropertyString(false, false, 'String', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->type->setId('type');
        $this->type->setQueryName("maarch:type");

        $this->contact = new PropertyString(false, false, 'String', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->contact->setId('contact');

        $this->entity_label = new PropertyString(false, false, 'String', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->entity_label->setId('entity_label');
        $this->entity_label->setQueryName("maarch:entity");

        $this->dest_user = new PropertyString(false, false, 'String', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->dest_user->setId('dest_user');
        $this->dest_user->setQueryName("maarch:dest_user");

        //$this->doc_date; //=creationDate
        $this->process_limit_date = new PropertyDateTime(false, false, 'DateTime', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->process_limit_date->setId('process_limit_date');

        $this->url = new PropertyString(true, false, 'Url', 'Single',
                'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->url->setId('url');

        $this->properties = array(
            "name"=>$this->name, "objectId"=>$this->objectId, "baseTypeId"=>$this->baseTypeId, "objectTypeId"=>$this->objectTypeId,
            "createdBy"=>$this->createdBy, "creationDate"=>$this->creationDate, "lastModifiedBy"=>$this->lastModifiedBy,
            "lastModificationDate"=>$this->lastModificationDate, "changeToken"=>$this->changeToken, "isImmutable"=>$this->isImmutable,
            //"isLatestVersion"=>$this->isLatestVersion, "isMajorVersion"=>$this->isMajorVersion, "isLatestMajorVersion"=>$this->isLatestMajorVersion,
            //"versionLabel"=>$this->versionLabel, "versionSeriesId"=>$this->versionSeriesId, "versionSeriesCheckedOut"=>$this->versionSeriesCheckedOut,
            //"versionSeriesCheckedOutBy"=>$this->versionSeriesCheckedOutBy, "versionSeriesCheckedOutId"=>$this->versionSeriesCheckedOutId, "checkinComment"=>$this->checkinComment,
            "contentStreamLength"=>$this->contentStreamLength, "contentStreamBase64"=>$this->contentStreamBase64, "contentStreamMimeType"=>$this->contentStreamMimeType,
            "contentStreamFileName"=>$this->contentStreamFileName, "contentStreamId"=>$this->contentStreamId, "type"=>$this->type,
            "contact"=>$this->contact, "entity_label"=>$this->entity_label, "dest_user"=>$this->dest_user, "process_limit_date"=>$this->process_limit_date,
            "url"=>$this->url
        );

        $this->propertiesInXml = array(
            "objectId"=>$this->objectId, "type"=>$this->type, "contact"=>$this->contact,
            "entity_label"=>$this->entity_label, "dest_user"=>$this->dest_user,
            "process_limit_date"=>$this->process_limit_date,
            "contentStreamLength"=>$this->contentStreamLength, "contentStreamBase64"=>$this->contentStreamBase64,
            "contentStreamMimeType"=>$this->contentStreamMimeType,
            "contentStreamFileName"=>$this->contentStreamFileName, "contentStreamId"=>$this->contentStreamId,
            "url"=>$this->url
        );
    }


    public function getProperties()
    {
        return $this->properties;
    }

    public function getDocument($id, $view)
    {
        require_once('core/class/class_db_pdo.php');
        require_once('core/class/class_security.php');
        $db = new Database();
        //$id = $db->escape_string($id);
        $result = $db->query("select res_id, type_label, contact_society, contact_firstname, contact_lastname, "
                        ."entity_label, dest_user, doc_date, process_limit_date, author "
                ."from " . $view . " "
                ."where res_id = ? ", array($id));

        //TODO
        if ($result === false){
            //TODO throw objectNotFound or invalidArgument or runtime
            echo "<br />ERREUR : requête non exécutée runtime.<br />";
            return null;
        }

        if ($recordset = $result->fetchObject()) {
            $resArray = array(
                    'res_id' => $recordset->res_id,
                    'type_label'=> $recordset->type_label,
                    'contact_society' => $recordset->contact_society,
                    'contact_firstname' => $recordset->contact_firstname,
                    'contact_lastname' => $recordset->contact_lastname,
                    'entity_label' => $recordset->entity_label,
                    'dest_user' => $recordset->dest_user,
                    'doc_date' => $recordset->doc_date,
                    'process_limit_date' => $recordset->process_limit_date,
                    'author' => $recordset->author
            );

            $documentCmis = new resCMIS();
            $documentCmis->DocumentCmis();
            $documentCmis->setRes($resArray);

            require_once('core/class/docservers_controler.php');
            $dsController = new docservers_controler();
            $theFileArray = array();
            $theFileArray = $dsController->viewResource($id, $view, '', true);

            if (strcmp($theFileArray['status'], 'ok') == 0){
                //$documentCmis->contentStreamLength->setValue($theFileArray['']);
                $documentCmis->contentStreamBase64->setValue($theFileArray['file_content']);
                $documentCmis->contentStreamMimeType->setValue($theFileArray['mime_type']);
                //$documentCmis->contentStreamFileName->setValue($theFileArray['']);
                //$documentCmis->contentStreamId->setValue($theFileArray['']);
            }

            return $documentCmis;
        }

        echo '<br />ERREUR : document inexistant.<br />';
        return null;
    }

    public function setRes($document){

        $this->objectId->setValue($document['res_id']);
        $this->type->setValue($document['type_label'])   ;
        $this->contact->setValue($document['contact_society'].' '.$document['contact_firstname'].' '.$document['contact_lastname'])   ;
        $this->entity_label->setValue($document['entity_label'])   ;
        $this->dest_user->setValue($document['dest_user'])   ;
        $this->creationDate->setValue($document['doc_date'])   ;
        $this->process_limit_date->setValue($document['process_limit_date'])   ;
        $this->createdBy->setValue($document['author']) ;
    }


    public function getPropertyByQueryName($queryName){
        $queryName = trim($queryName);
        $found = false;
        $i = 0;
        $properties = array_values($this->properties);

        while(!$found && ($property = $properties[$i++])){
            $found = (strcmp($queryName, $property->getQueryName()) == 0);
        }

        return ($found) ?  $property : null;
    }


    public function toAtomXml(){


        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->xmlStandalone = true;
        $doc->formatOutput = true;

        $this->getAtomXmlEntry($doc, $feed);

        return $doc;
    }



    public function getAtomXmlEntry(&$doc, &$feed){

        if (isset($feed)){
            $root = $doc->createElement('atom:entry');
            $feed->appendChild($root);
        }
        else{
            //atom:entry
            $root = $doc->createElementNS('http://www.w3.org/2005/Atom', 'atom:entry');
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:cmisra', 'http://docs.oasis-open.org/ns/cmis/restatom/200908/');
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:cmis', 'http://docs.oasis-open.org/ns/cmis/core/200908/');
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:maarch', 'http://www.maarch.org');
            $doc->appendChild($root);
        }



        //atom:author
        $eAuthor = $doc->createElement('atom:author');
        $root->appendChild($eAuthor);
        //$uri = 'uri';
        $name = $this->contact->getValue();  //'name';
        //$email = 'email';
        //$eUri = $doc->createElement('atom:uri', $uri);
        $eName = $doc->createElement('atom:name', $name);
        //$eEmail = $doc->createElement('atom:email', $email);
        $eAuthor->appendChild($eName);
        //$eAuthor->appendChild($eUri);
        //$eAuthor->appendChild($eEmail);


        //atom:content
        $attSrcContent = 'src';
        $eContent = $doc->createElement('atom:content');
        $root->appendChild($eContent);
        //$eContent->setAttribute('src', $attSrcContent);


        //atom:id
        //derived from cmis:objectId. This id MUST be compliant with atom's specification and be a valid URI
        if ($this->objectId->valueIsSet()){
            $id = $this->objectId->getValue();  //'id';
            $eId = $doc->createElement('atom:id', $id);
            $root->appendChild($eId);
        }


        //atom:title
        //cmis:name property
        if ($this->name->valueIsSet()){
            $title = $this->name->getValue();   //'title';
            $attTypeTitle = 'text';
            $eTitle = $doc->createElement('atom:title', $title);
            $root->appendChild($eTitle);
            $eTitle->setAttribute('type', $attTypeTitle);
        }

        //atom:updated
        //cmis:lastModificationDate
        if ($this->lastModificationDate->valueIsSet()){
            $updated = $this->lastModificationDate->getValue();   //'updated';
            $eUpdated = $doc->createElement('atom:updated', $updated);
            $root->appendChild($eUpdated);
        }


        //atom:published
        //cmis:creationDate
        if ($this->creationDate->valueIsSet()){
            $published = $this->creationDate->getValue(); //'published';
            $ePublished = $doc->createElement('atom:published', $published);
            $root->appendChild($ePublished);
        }

        //atom:summary
        /*$summary = 'summary'; //$this->getSummary();
        $attTypeSummary = 'text';
        $eSummary = $doc->createElement('atom:summary', $summary);
        $root->appendChild($eSummary);
        $eSummary->setAttribute('type', $attTypeSummary);*/


        //atom:link rel="self"
        //points to an URI that returns the atom entry for this document
        /*$attHrefLinkSelf = 'href link self';
        $eLinkSelf = $doc->createElement('atom:link');
        $root->appendChild($eSummary);
        $eLinkSelf->setAttribute('rel', 'self');
        $eLinkSelf->setAttribute('href', $attHrefLinkSelf);*/


        //atom:link rel="edit"
        //points to an URI that accepts PUT of atom entry
        /*$attHrefLinkEdit = 'href link edit';
         $eLinkEdit = $doc->createElement('atom:link');
        $root->appendChild($eSummary);
        $eLinkEdit->setAttribute('rel', 'edit');
        $eLinkEdit->setAttribute('href', $attHrefLinkEdit);*/


        //atom:link rel="http://docs.oasis-open.org/ns/cmis/link/200908/allowableactions"
        //points to the allowable actions document for this object


        //atom:link rel="describedby"
        //points to the type definition as an atom entry for the type of this document entry
        /*$attHrefLinkDesc = 'href link described by';
        $eLinkDesc = $doc->createElement('atom:link');
        $root->appendChild($eLinkDesc);
        $eLinkDesc->setAttribute('rel', 'describedby');
        $eLinkDesc->setAttribute('type', 'application/atom+xml;type=entry');
        $eLinkDesc->setAttribute('href', $attHrefLinkDesc);*/


        //atom:link rel="working-copy"
        //points to the private working copy if it exists

        //atom:link rel="service"
        //points to service document containing the CMIS repository.
        //The service document MUST contain only one workspace element
        //Media Type: application/atomsvc+xml
        /*$attHrefLinkService = 'href link service';
        $eLinkService = $doc->createElement('atom:link');
        $root->appendChild($eLinkService);
        $eLinkService->setAttribute('rel', 'service');
        $eLinkService->setAttribute('type', 'application/atomsvc+xml');
        $eLinkService->setAttribute('href', $attHrefLinkService);*/


        //atom:link rel="edit-media"
        //Same as setContentStream
        /*$attHrefLinkEditMedia = 'href link edit media';
        $eLinkEditMedia = $doc->createElement('atom:link');
        $root->appendChild($eLinkEditMedia);
        $eLinkEditMedia->setAttribute('rel', 'edit-media');
        $eLinkEditMedia->setAttribute('href', $attHrefLinkService);*/


        //atom:link rel="alternate"
        //used to identify the renditions available for the specified object
        /*$attHrefAlternate = 'href link alternate';
        $eLinkAlternate = $doc->createElement('atom:link');
        $root->appendChild($eLinkAlternate);
        $eLinkAlternate->setAttribute('rel', 'alternate');
        $eLinkAlternate->setAttribute('href', $attHrefAlternate);*/


        //atom:link type="application/atom+xml;type=feed" rel="up"
        //points to the atom feed containing the set of parents
        //If there is only one parent, the repository MAY point this link relation directly to the atom entry of the parent
        /*$attHrefLinkUp = 'href link up';
        $eLinkUp = $doc->createElement('atom:link');
        $root->appendChild($eLinkUp);
        $eLinkUp->setAttribute('rel', 'up');
        $eLinkUp->setAttribute('type', 'application/atom+xml;type=feed');
        $eLinkUp->setAttribute('href', $attHrefLinkUp);*/


        //atom:link type="application/atom+xml;type=feed" rel="version-history"
        //points to atom feed containing the versions of this document
        //If the document is not versionable, this link relation may not be on the resource
        /*if ($this->versionable){
         $attHrefLinkVersHist = 'href link version-history';
        $eLinkVersHist = $doc->createElement('atom:link');
        $root->appendChild($eLinkVersHist);
        $eLinkVersHist->setAttribute('rel', 'version-history');
        $eLinkVersHist->setAttribute('type', 'application/atom+xml;type=feed');
        $eLinkVersHist->setAttribute('href', $attHrefLinkVersHist);
        }
        */


        //atom:link type="application/atom+xml;type=entry" rel="current-version"
        //points to the latest version of the document
        //Uses query parameter 'returnVersion' and enumReturnVersion
        //If this version is the current-version, this link relation may not be on the resource


        //atom:link type="application/atom+xml;type=feed" rel="http://docs.oasis-open.org/ns/cmis/link/200908/relationships"
        //points to the relationships feed for this object
        /*$attRel = 'rel link relationships';
        $eLinkRel = $doc->createElement('atom:link');
        $root->appendChild($eLinkRel);
        $eLinkRel->setAttribute('rel', $attRel);
        $eLinkRel->setAttribute('type', 'application/atom+xml;type=feed');*/


        //atom:link type="application/atom+xml;type=feed" rel="http://docs.oasis-open.org/ns/cmis/link/200908/policies"
        //points to the policy feed for this object
        /*$attPol = 'rel link policies';
        $eLinkPol = $doc->createElement('atom:link');
        $root->appendChild($eLinkPol);
        $eLinkPol->setAttribute('rel', $attPol);
        $eLinkPol->setAttribute('type', 'application/atom+xml;type=feed');*/


        //atom:link type="application/cmisacl+xml" rel="http://docs.oasis-open.org/ns/cmis/link/200908/acl"
        //points to ACL document for this object
        /*$attAcl = 'rel link acl';
        $eLinkAcl = $doc->createElement('atom:link');
        $root->appendChild($eLinkAcl);
        $eLinkAcl->setAttribute('rel', $attAcl);
        $eLinkAcl->setAttribute('type', 'application/atom+xml;type=feed');*/


        //cmisra:object
        $eObject = $doc->createElement('cmisra:object');
        $root->appendChild($eObject);

        //  cmis:properties
        $eProperties = $doc->createElement('cmis:properties');
        $eObject->appendChild($eProperties);


        //    cmis:property
        //      cmis:value
        foreach($this->propertiesInXml as $property){
            $propertyValue = $property->getValue();
            if ($property->valueIsSet()){
                $eProperty = $doc->createElement('cmis:property'.$property->getPropertyType());
                $eProperties->appendChild($eProperty);
                $eProperty->setAttribute('localName', $property->getLocalName());
                $eProperty->setAttribute('propertyDefinitionId', $property->getId());

                if (strcmp($property->getCardinality(), 'Single')==0 ){
                    $eValue = $doc->createElement('cmis:value', $propertyValue);
                    $eProperty->appendChild($eValue);
                }
                else{
                    //au moins une valeur non nulle
                    foreach($propertyValue as $value){
                        if ($value != null){
                            $eValue = $doc->createElement('cmis:value', $value);
                            $eProperty->appendChild($eValue);
                        }
                    }
                }
            }
        }

        return $root;
    }






    public function setName($name){
        $this->name  =  $name;
    }

    public function setObjectId($objectId){
        $this->objectId  =  $objectId;
    }

    public function setBaseTypeId($baseTypeId){
        $this->baseTypeId =  $baseTypeId;
    }

    public function setObjectTypeId($objectTypeId){
        $this->objectTypeId  =  $objectTypeId;
    }

    public function setCreatedBy($createdBy){
        $this->createdBy  =  $createdBy;
    }

    public function setCreationDate($creationDate){
        $this->creationDate  =  $creationDate;
    }

    public function setLastModifiedBy($lastModifiedBy){
        $this->lastModifiedBy  =  $lastModifiedBy;
    }

    public function setLastModificationDate($lastModificationDate){
        $this->lastModificationDate  =  $lastModificationDate;
    }

    public function setContentStreamLength($contentStreamLength){
        $this->contentStreamLength  =  $contentStreamLength;
    }

    public function setContentStreamMimeType($contentStreamMimeType){
        $this->contentStreamMimeType  =  $contentStreamMimeType;
    }

    public function setContentStreamFileName($contentStreamFileName){
        $this->contentStreamFileName  =  $contentStreamFileName;
    }

    public function setContentStreamId($contentStreamId){
        $this->contentStreamId  =  $contentStreamId;
    }

    public function setUrl($value){
        $this->url->setValue($value);
    }


}

