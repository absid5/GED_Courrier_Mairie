<?php

require_once 'apps/maarch_entreprise/class/cmis/cmis_res_controller.php';
require_once 'apps/maarch_entreprise/class/cmis/xmlParser.class.php';
require_once 'apps/maarch_entreprise/class/cmis/cmis_class_object.php';
require_once 'apps/maarch_entreprise/class/cmis/cmis_class_property.php';


class folderCMIS extends objectCMIS
{

    //properties
    protected $name ;
    protected $parentId;
    protected $path;
    protected $allowedChildObjectTypeIds ;
    private   $typist;
    private   $folderId;

    //
    protected $propertiesInXml;

    public function __construct() {

        $this->Id = "cmis:folder";
        $this->queryName = "cmis:folder";
        $this->baseId = "cmis:folder";
        $this->fileable = true;
        $this->queryable = true;
        $this->localName = "cmis:folder";
        $this->displayName = "cmis:folder";
        $this->creatable = true;
        $this->fulltextIndexed = 'none';



        //properties
        $this->parentId = new PropertyId(false, false, 'Id', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);;

        $this->name = new PropertyString($required, $inherited, $propertyType,
            $cardinality, $updatability, $choices, $openChoice, $queryable,
            $orderable);
        $this->name->setRequired(true);
        $this->name->setInherited(false);
        $this->name->setPropertyType('String');
        $this->name->setCardinality('Single');
        $this->name->setId('name');
        $this->name->setOpenChoice(true);

        $this->objectId = new PropertyId(false, false, 'Id', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->objectId->setId('objectId');

        $this->baseTypeId = new PropertyId(false, false, 'Id', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->baseTypeId->setId('baseTypeId');

        $this->objectTypeId = new PropertyId(true, false, 'Id', 'Single',
                 'oncreate', 'NotApplicable', 'NotApplicable', false, false);
        $this->objectTypeId->setId('objectTypeId');
        $this->objectTypeId->setValue('cmis:folder');

        $this->createdBy = new PropertyString(false, false, 'String', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->createdBy->setId('createdBy');

        $this->creationDate = new PropertyDateTime(false, false, 'DateTime', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->creationDate->setId('creationDate');

        $this->lastModifiedBy = new PropertyString(false, false, 'String', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->lastModifiedBy->setId('lastModifiedBy');

        $this->lastModificationDate = new PropertyDateTime(false, false, 'DateTime', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->lastModificationDate->setId('lastModificationDate');

        $this->changeToken = new PropertyString(false, false, 'String', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->changeToken->setId('changeToken');

        $this->parentId = new PropertyId(false, false, 'Id', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->parentId->setId('parentId');

        $this->folderId = new PropertyId(true, false, 'Id', 'Single',
                'ReadOnly', 'NotApplicable', 'NotApplicable', true, true);
        $this->folderId->setId('folderId');


        $this->path = new PropertyString(false, false, 'String', 'Single',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->path->setId('path');

        $this->allowedChildObjectTypeIds = new PropertyId(false, false, 'Id', 'Multi',
                 'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->allowedChildObjectTypeIds->setId('allowedChildObjectTypeIds');

        $this->typist = new PropertyString(false, false, 'String', 'Single',
                'ReadOnly', 'NotApplicable', 'NotApplicable', false, false);
        $this->typist->setId('typist');

        $this->properties = array(
                $this->name, $this->objectId, $this->baseTypeId, $this->objectTypeId, $this->createdBy, $this->creationDate,
                $this->lastModifiedBy, $this->lastModificationDate, $this->changeToken, $this->parentId, $this->folderId,
                $this->path, $this->allowedChildObjectTypeIds, $this->typist
        );

        $this->propertiesInXml = array( $this->folderId, $this->typist, $this->creationDate);
    }


    public function setNameValue($name){
        $this->name->setValue($name);
    }

    public function setFolderIdValue($id){
        $this->folderId->setValue($id);
        $this->objectId->setValue($id);
    }

    public function setCreationDateValue($creationDate){
        $this->creationDate->setValue($creationDate);
    }

    public function setLastModificationDateValue($modificationDate){
        $this->lastModificationDate->setValue($modificationDate);
    }

    public function setTypistValue($typist){
        $this->typist->setValue($typist);
    }

    public function getNameValue(){
        return $this->name->getValue();
    }

    public function getFolderIdValue(){
        return $this->folderId->getValue();
    }

    public function setName($name){
        $this->name  =  $name;
    }

    public function setObjectId($objectId){
        $this->objectId  = $objectId ;
    }

    public function setBaseTypeId($baseTypeId){
        $this->baseTypeId  =  $baseTypeId;
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

    public function setChangeToken($changeToken){
        $this->changeToken  =  $changeToken;
    }

    public function setParentId($parentId){
        $this->parentId  =  $parentId;
    }

    public function setPath($path){
        $this->path  =  $path;
    }

    public function setAllowedChildObjecTypeIds( $allowedChildObjecTypeIds ){
        $this->allowedChildObjectTypeIds  =  $allowedChildObjecTypeIds;
    }


    public function getProperties(){
        return $this->properties;
    }




    public function entryMethod ($atomFileContent, $requestedResourceId)
    {

        if(isset($atomFileContentf) && !isset($requestedResourceId)){
            $str = 'AtomFileContent create folder ';

            //new parser atom xml file
            $xmlParser = new xmlParser();

            echo $atomFileContent;

            //build a new folder from atom xml file (the folder is a cmis folder)
            $newCmisFolder = $xmlParser->getCmisFolderFromXml($atomFileContent);


            if( $this->creatable){
                if($newCmisFolder->folderId->valueIsSet() && $newCmisFolder->name->valueIsSet() ){
                    $this->saveFolder($newCmisFolder);
                }
                else{
                    //TODO throw constraintException
                    echo "<br />ERREUR : constraintException.<br />";
                }
            }
            else{
                //TODO throw constraintException
                echo "<br />ERREUR : constraintException.<br />";
            }

        }
        elseif(!isset($atomFileContent) && isset($requestedResourceId)){
            //get the folder whose Id is $requestedResourceId (whith its documents)
            if($folder = $this->getFolderByFolderId($requestedResourceId)){

                $documents = $folder->getDocumentsInFolder($requestedResourceId);
                $title = trim('Documents in folder '.$requestedResourceId);
                $folderXml = resCMIS::getFeed($documents, $title, $folder);

                echo $folderXml->saveXML();
            }
            else{
            //TODO throw objectNotFound
                echo "<br />ERREUR : objectNotFound.<br />";
            }
        }
        else{
            //TODO throw notSupported or invalidArgument
                echo "<br />ERREUR : notSupported or invalidArgument.<br />";
        }

        //return $requestedResourceId . ' ' . $atomFileContent;
    }


    private function getFolderByFolderId($requestedResourceId){
        require_once('core/class/class_db_pdo.php');
        $db = new Database();

        $requestedResourceId = pg_escape_string($requestedResourceId);
        $statement = "select * from folders where folder_id = '".$requestedResourceId."'";

        $result = $db->query($statement);

        if($result === false){
            //TODO throw objectNotFound or invalidArgument or runtime
            echo "<br />ERREUR : requête non exécutée runtime.<br />";
            return null;
        }

        $folder = new folderCMIS();
        //$folder->FolderCmis();

        //premier tuple retourne
        if ($recordset = $result->fetchObject()) {
            $folder->setFolderIdValue($recordset->folder_id);
            $folder->setNameValue($recordset->folder_name);
            $folder->setCreationDateValue($recordset->creation_date);
            $folder->setLastModificationDateValue($recordset->last_modified_date);
            $folder->setTypistValue($recordset->typist);
        }
        else{
            //TODO throw objectNotFound
            echo "<br />ERREUR : fichier inexistant objectNotFound.<br />";
            return null;
        }

        return $folder;
    }

    private function getDocumentsInFolder($requestedResourceId){
        require_once('core/class/class_db_pdo.php');
        $db = new Database();

        $requestedResourceId = pg_escape_string($requestedResourceId);

        //$resArray = null
        $statement = "select * from res_view_letterbox where folder_id = '".$requestedResourceId."'";

        $result = $db->query($statement);

        //TODO gerer les cas d erreurs
        if($result === false){
            //TODO throw objectNotFound or invalidArgument or runtime
            echo "<br />ERREUR : requete non réalisée runtime.<br />";
        }

        $resArray = array();
        while ($recordset = $result->fetchObject()) {
            //var_dump($recordset);
            $documentCmis = new resCMIS();
            $documentCmis->DocumentCmis();
            $documentCmis->setRes(
                    array('res_id' => $recordset->res_id,
                            'type_label'=> $recordset->type_label,
                            'contact_society' => $recordset->contact_society ,
                            'contact_firstname' => $recordset->contact_firstname ,
                            'contact_lastname' => $recordset->contact_lastname ,
                            'entity_label' => $recordset->entity_label ,
                            'dest_user' => $recordset->dest_user ,
                            'doc_date' => $recordset->doc_date ,
                            'process_limit_date' => $recordset->process_limit_date,
                            'author' => $recordset->author
                        ));
            array_push($resArray, $documentCmis);
         }

         return $resArray;
    }

    private function saveFolder($newCmisFolder){
        $foldertype_id = 5;
        require_once('core/class/class_db_pdo.php');
        $db = new Database();
        
        $creation_date = $db->current_datetime();

        //TODO gerer les cas d erreurs

        if(!$newCmisFolder->isWellFormed()){
            //TODO throw constraintException
            echo "<br />ERREUR : création du fichier non réalisée : toutes les propriétés requises n'ont pas été renseignées.<br />";
            return;
        }


        //insert into folders ( folder_id , foldertype_id , folder_name , creation_date ) values ( 'folderid' , 5 , 'foldername' , current_date)
        $statement = "insert into folders ( folder_id , foldertype_id , folder_name , creation_date ) values ( '"
                .pg_escape_string($newCmisFolder->getFolderIdValue())."' , '"
                .pg_escape_string($foldertype_id)."' , '"
                .pg_escape_string($newCmisFolder->getNameValue())."' , "
                .pg_escape_string($creation_date)." ) ";

        $result = $db->query($statement);

        //TODO gerer les cas d erreurs
        if($result === false){
            //TODO throw storageException
            echo "<br />ERREUR : création du fichier non réalisée storageException.<br />";
        }

    }


    private function isWellFormed(){
        $folderOk = true;
        $i=0;
        $properties = $this->getProperties();
        while($folderOk && $property=$properties[$i++]){
            //echo '<br />'.$property->getLocalName()." = ".$property->getValue().'<br />';
            $folderOk = !$property->isRequired() || $property->valueIsSet();
        }

        return $folderOk;
    }


    public function toAtomXml(){
        //creer un document atom xml
        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->xmlStandalone = true;
        $doc->formatOutput = true;

        /**/
        $this->getAtomXmlEntry($doc);

        return $doc;
    }

    public function getAtomXmlEntry(&$doc, &$feed){

        if(isset($feed)){
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
        //MUST be the CMIS property cmis:createdBy
        $eAuthor = $doc->createElement('atom:author');
        $root->appendChild($eAuthor);
        //$uri = 'uri';
        $name = $_SESSION['user']['FirstName'].' '.$_SESSION['user']['LastName'];
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

        //atom:Id
        //SHOULD be derived from cmis:objectId.
        //This Id MUST be compliant with atom's specification and be a valId URI
        if($this->objectId->valueIsSet()){
            $Id = $this->objectId->getValue();
            $eId = $doc->createElement('atom:Id', $Id);
            $root->appendChild($eId);
        }


        //atom:title
        //MUST be the CMIS property cmis:name
        if($this->name->valueIsSet()){
            $title = $this->name->getValue();
            $attTypeTitle = 'text';
            $eTitle = $doc->createElement('atom:title', $title);
            $root->appendChild($eTitle);
            $eTitle->setAttribute('type', $attTypeTitle);
        }


        //atom:updated
        //SHOULD be the latest time the folder or its contents was updated
        //If unknown by the underlying repository, it MUST be the current time
        if($this->lastModificationDate->valueIsSet()){
            $updated = $this->lastModificationDate->getValue();
            $eUpdated = $doc->createElement('atom:updated', $updated);
            $root->appendChild($eUpdated);
        }


        //atom:published
        if($this->creationDate->valueIsSet()){
            $published = $this->creationDate->getValue();
            $ePublished = $doc->createElement('atom:published', $published);
            $root->appendChild($ePublished);
        }

        //atom:summary
        //$summary = 'summary';
        //$attTypeSummary = 'text';
        //$eSummary = $doc->createElement('atom:summary', $summary);
        //$root->appendChild($eSummary);
        //$eSummary->setAttribute('type', $attTypeSummary);


        //atom:link rel="self"
        //points to the URI to retrieve this atom entry
        /*
        $attHrefLinkSelf = 'href link self';
        $eLinkSelf = $doc->createElement('atom:link');
        $root->appendChild($eSummary);
        $eLinkSelf->setAttribute('rel', 'self');
        $eLinkSelf->setAttribute('href', $attHrefLinkSelf);
        */


        //atom:link rel="edit"
        //points to the URI to update this atom entry via POST
        /*$attHrefLinkEdit = 'href link edit';
        $eLinkEdit = $doc->createElement('atom:link');
        $root->appendChild($eSummary);
        $eLinkEdit->setAttribute('rel', 'edit');
        $eLinkEdit->setAttribute('href', $attHrefLinkEdit);*/


        //atom:link rel="http://docs.oasis-open.org/ns/cmis/link/200908/allowableactions"
        //points to the allowable actions document for this object


        //atom:link rel="describedby"
        //points to the type definition as an atom entry for the type of this folder entry
        /*$attHrefLinkDesc = 'href link described by';
        $eLinkDesc = $doc->createElement('atom:link');
        $root->appendChild($eLinkDesc);
        $eLinkDesc->setAttribute('rel', 'describedby');
        $eLinkDesc->setAttribute('type', 'application/atom+xml;type=entry');
        $eLinkDesc->setAttribute('href', $attHrefLinkDesc);
         */


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


        //atom:link rel="alternate"
        //this is used to identify the renditions available for the specified object
        /*$attHrefAlternate = 'href link alternate';
        $eLinkAlternate = $doc->createElement('atom:link');
        $root->appendChild($eLinkAlternate);
        $eLinkAlternate->setAttribute('rel', 'alternate');
        $eLinkAlternate->setAttribute('href', $attHrefAlternate);*/



        //atom:link type="application/atom+xml;type=feed" rel="up"
        //points to the atom entry for the parent
        //if the root folder, this link will not be present
        /*$attHrefLinkUp = 'href link up';
        $eLinkUp = $doc->createElement('atom:link');
        $root->appendChild($eLinkUp);
        $eLinkUp->setAttribute('rel', 'up');
        $eLinkUp->setAttribute('type', 'application/atom+xml;type=feed');
        $eLinkUp->setAttribute('href', $attHrefLinkUp);*/


        //atom:link type="application/atom+xml;type=feed" rel="down"
        //atom:link type="application/cmistree+xml" rel="down" href="http://.../tree"
        //points to the children of this folder
        //application/atom+xml : Points to the atom feed document representing the children feed for this same folder
        //application/cmistree+xml: Points to the descendants feed of the same folder


        //atom:link type="application/atom+xml;type=feed" rel="http://docs.oasis-open.org/ns/cmis/link/200908/foldertree" href="http://.../foldertree"
        //points to the folder tree for this folder


        //atom:link type="application/atom+xml;type=feed" rel="http://docs.oasis-open.org/ns/cmis/link/200908/relationships" href="http://.../relationships"
        //points to the relationships feed for this object
        /*$attRel = 'rel link relationships';
        $eLinkRel = $doc->createElement('atom:link');
        $root->appendChild($eLinkRel);
        $eLinkRel->setAttribute('rel', $attRel);
        $eLinkRel->setAttribute('type', 'application/atom+xml;type=feed');*/


        //atom:link type="application/atom+xml;type=feed" rel="http://docs.oasis-open.org/ns/cmis/link/200908/policies" href="http://.../policies"
        //points to the policy feed for this object
        /*$attPol = 'rel link policies';
        $eLinkPol = $doc->createElement('atom:link');
        $root->appendChild($eLinkPol);
        $eLinkPol->setAttribute('rel', $attPol);
        $eLinkPol->setAttribute('type', 'application/atom+xml;type=feed');*/


        //atom:link type="application/cmisacl+xml" rel="http://docs.oasis-open.org/ns/cmis/link/200908/acl" href="http://.../acl"
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
        //getFolderPropertiesXml($eProperties);
        foreach($this->propertiesInXml as $property){
            $propertyValue = $property->getValue();
            if($property->valueIsSet()){
                $eProperty = $doc->createElement('cmis:property');
                $eProperties->appendChild($eProperty);
                $eProperty->setAttribute('localName', $property->getLocalName());
                $eProperty->setAttribute('propertyDefinitionId', $property->getId());

                if(strcmp($property->getCardinality(), 'Single')==0 ){
                    $eValue = $doc->createElement('cmis:value', $propertyValue);
                    $eProperty->appendChild($eValue);
                }
                else{
                    //au moins une valeur non nulle
                    foreach($propertyValue as $value){
                        if($value != null && strcmp($value,'notset')!=0){
                            $eValue = $doc->createElement('cmis:value', $value);
                            $eProperty->appendChild($eValue);
                        }
                    }
                }
            }
        }

    }



}

