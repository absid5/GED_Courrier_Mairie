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
* @brief Maarch REST class
*
* @file
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

//declaration of descriptions services vars
if (!isset ($REST_dispatch_map)) {
    $REST_dispatch_map = Array ();
}

/**
 * Class for manage REST web service
 */
class MyRestServer extends webService
{
    var $dispatchMap;
    var $crudMethod;
    var $resController;
    var $requestedCollection;
    var $requestedResource;
    var $requestedResourceId;
    var $atomFileContent;
    
    function __construct()
    {
        global $REST_dispatch_map;
        $this->dispatchMap = $REST_dispatch_map;
        //$this->retrieveHttpMethod();
        $this->parseTheRequest();
    }
    
    /*function retrieveHttpMethod()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->crudMethod = 'create';
        } elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->crudMethod = 'retrieve';
        } if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $this->crudMethod = 'update';
        } if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $this->crudMethod = 'delete';
        }
    }*/
    
    /**
     * parse the request
     * @return nothing
     */
    function parseTheRequest()
    {
        if (
            strtolower($_SERVER['QUERY_STRING']) == 'cmis/id=workspace://'
            || strtolower($_SERVER['QUERY_STRING']) == 'cmis&repositoryId=' 
                . $this->getUuid()
        ) {
            $this->makeAtomEntryRootFolder();
        } else {
            $restRequest = explode('/', $_SERVER['QUERY_STRING']);
            //var_dump($restRequest);
            if ($restRequest[1] <> '') {
                $this->requestedCollection = $restRequest[1];
            }
            if ($restRequest[2] <> '') {
                $this->requestedResource = $restRequest[2];
            }
            if ($restRequest[3] <> '') {
                $this->requestedResourceId = $restRequest[3];
            }
            if (
                isset($_REQUEST['atomFileContent']) 
                && !empty($_REQUEST['atomFileContent'])
            ) {
                $this->atomFileContent = $_REQUEST['atomFileContent'];
            }
            //var_dump($this);
        }
    }
    
    /**
     * parse the requested resource object and call the good method of 
     * the requested resource controller
     * @return call of the good method
     */
    public function call()
    {
      // var_dump($this->dispatchMap);
      // var_dump($this->requestedResource);
      // var_dump($this->dispatchMap[$this->requestedResource]['pathToController']);
        if (
            file_exists(
                $this->dispatchMap[$this->requestedResource]['pathToController']
            )
        ) {
            require_once(
                $this->dispatchMap[$this->requestedResource]['pathToController']
            );
            $objectControllerName = $this->requestedResource . 'CMIS';
            $objectController = new $objectControllerName();
            $args = array(
                'atomFileContent' => $this->atomFileContent,
                'requestedResourceId' => $this->requestedResourceId,
                'requestedCollection' => $this->requestedCollection
            );
            return call_user_func_array(
                array($objectController, 'entryMethod'), $args
            );
        }
    }
    
    /**
     * generate REST server
     */
    function makeRESTServer()
    {
        //only for tests
        header("Content-type: text/xml");
        $this->call();
    }
    
    /**
     * Get the uuid from the database or generate it
     * @return string the uuid
     */
    function getUuid()
    {
        $func = new functions();
        $db = new Database();
        $uuid = '';
        $reqUuid = "select param_value_string from parameters "
        . "where id = 'cmis_uuid'";
        $stmt = $db->query($reqUuid);
        while ($reqResult = $stmt->fetchObject()) {
            $uuid = $reqResult->param_value_string;
        }
        if (empty($uuid)) {
            $uuid = $func->gen_uuid();
            $reqInsertUuid = 
                "insert into parameters (id, param_value_string) values "
                . "('cmis_uuid', ?)";
            $stmt = $db->query($reqInsertUuid, array($uuid));
        }
        return $uuid;
    }
    
    /**
     * generate CMIS catalog
     */
    function makeCMISCatalog()
    {
        header("Content-type: text/xml");
        echo '<?xml version="1.0" encoding="utf-8"?>
<app:service xmlns:app="http://www.w3.org/2007/app"
xmlns:atom="http://www.w3.org/2005/Atom"
xmlns:cmisra="http://docs.oasis-open.org/ns/cmis/restatom/200908/"
xmlns:cmis="http://docs.oasis-open.org/ns/cmis/core/200908/"
xmlns:maarch="http://www.maarch.org">
    <app:workspace>
        <atom:title>Maarch main Repository</atom:title>
        <collection href="' . $_SESSION['config']['coreurl'] 
            . 'ws_server.php?CMIS">
            <atom:title>Maarch root collection</atom:title>
            <cmisra:collectionType>root</cmisra:collectionType>
        </collection>
        <cmisra:repositoryInfo>
            <cmis:repositoryId>' . $this->getUuid() . '</cmis:repositoryId>
            <cmis:repositoryName>Maarch main Repository</cmis:repositoryName>
            <cmis:repositoryDescription>Maarch main Repository</cmis:repositoryDescription>
            <cmis:vendorName>Maarch</cmis:vendorName>
            <cmis:productName>MaarchCourrier</cmis:productName>
            <cmis:productVersion>1.6.0</cmis:productVersion>
            <cmis:rootFolderId>workspace://</cmis:rootFolderId>
            <cmis:capabilities>
                <cmis:capabilityACL>none</cmis:capabilityACL>
                <cmis:capabilityAllVersionsSearchable>false</cmis:capabilityAllVersionsSearchable>
                <cmis:capabilityChanges>none</cmis:capabilityChanges>
                <cmis:capabilityContentStreamUpdatability>none</cmis:capabilityContentStreamUpdatability>
                <cmis:capabilityGetDescendants>true</cmis:capabilityGetDescendants>
                <cmis:capabilityGetFolderTree>false</cmis:capabilityGetFolderTree>
                <cmis:capabilityMultifiling>false</cmis:capabilityMultifiling>
                <cmis:capabilityPWCSearchable>false</cmis:capabilityPWCSearchable>
                <cmis:capabilityPWCUpdatable>false</cmis:capabilityPWCUpdatable>
                <cmis:capabilityQuery>metadataonly</cmis:capabilityQuery>
                <cmis:capabilityRenditions>none</cmis:capabilityRenditions>
                <cmis:capabilityUnfiling>true</cmis:capabilityUnfiling>
                <cmis:capabilityVersionSpecificFiling>false</cmis:capabilityVersionSpecificFiling>
                <cmis:capabilityJoin>none</cmis:capabilityJoin>
            </cmis:capabilities>
            <cmis:cmisVersionSupported>1.0</cmis:cmisVersionSupported>
        </cmisra:repositoryInfo>
        <cmisra:uritemplate>
            <cmisra:template>' . $_SESSION['config']['coreurl'] 
                . 'ws_server.php?CMIS/id={id}</cmisra:template>
            <cmisra:type>entrybyid</cmisra:type>
            <cmisra:mediatype>application/atom+xml;type=entry</cmisra:mediatype>
        </cmisra:uritemplate>
        <cmisra:uritemplate>
            <cmisra:template>' . $_SESSION['config']['coreurl'] 
                . 'ws_server.php?CMIS/path={path}</cmisra:template>
            <cmisra:type>folderbypath</cmisra:type>
            <cmisra:mediatype>application/atom+xml;type=entry</cmisra:mediatype>
        </cmisra:uritemplate>
        <cmisra:uritemplate>
            <cmisra:template>' . $_SESSION['config']['coreurl'] 
                . 'ws_server.php?CMIS/type={id}/</cmisra:template>
            <cmisra:type>query</cmisra:type>
            <cmisra:mediatype>application/atom+xml;type=entry</cmisra:mediatype>
        </cmisra:uritemplate> 
    </app:workspace>
</app:service>';
        exit;
    }
    
    /**
     * generate the atom:entry root folder
     */
    function makeAtomEntryRootFolder()
    {
        header("Content-type: text/xml");
        echo '<?xml version="1.0" encoding="UTF-8"?>
<atom:entry xmlns:atom="http://www.w3.org/2005/Atom" 
xmlns:cmis="http://docs.oasis-open.org/ns/cmis/core/200908/" 
xmlns:cmisra="http://docs.oasis-open.org/ns/cmis/restatom/200908/" 
xmlns:app="http://www.w3.org/2007/app"
xmlns:maarch="http://www.maarch.org">
  <atom:author>
    <atom:name>Maarch</atom:name>
  </atom:author>
  <atom:id>ROOT</atom:id>
  <atom:published>2010-09-28T11:51:47Z</atom:published>
  <atom:title>' . $_SESSION['config']['applicationname'] . '</atom:title>
  <app:edited>2012-11-21T14:44:59Z</app:edited>
  <atom:updated>2012-11-21T14:44:59Z</atom:updated>
  <cmisra:object xmlns:ns3="http://docs.oasis-open.org/ns/cmis/messaging/200908/">
    <cmis:properties>
      <cmis:propertyId queryName="cmis:allowedChildObjectTypeIds" displayName="Allowed Child Object Types Ids" localName="allowedChildObjectTypeIds" propertyDefinitionId="cmis:allowedChildObjectTypeIds"/>
      <cmis:propertyId queryName="cmis:objectTypeId" displayName="Object Type Id" localName="objectTypeId" propertyDefinitionId="cmis:objectTypeId">
        <cmis:value>cmis:folder</cmis:value>
      </cmis:propertyId>
      <cmis:propertyString queryName="cmis:path" displayName="Path" localName="path" propertyDefinitionId="cmis:path">
        <cmis:value>/</cmis:value>
      </cmis:propertyString>
      <cmis:propertyString queryName="cmis:name" displayName="Name" localName="name" propertyDefinitionId="cmis:name">
        <cmis:value>Company Home</cmis:value>
      </cmis:propertyString>
      <cmis:propertyDateTime queryName="cmis:creationDate" displayName="Creation Date" localName="creationDate" propertyDefinitionId="cmis:creationDate">
        <cmis:value>2010-09-28T12:51:47.684+01:00</cmis:value>
      </cmis:propertyDateTime>
      <cmis:propertyString queryName="cmis:changeToken" displayName="Change token" localName="changeToken" propertyDefinitionId="cmis:changeToken"/>
      <cmis:propertyString queryName="cmis:lastModifiedBy" displayName="Last Modified By" localName="lastModifiedBy" propertyDefinitionId="cmis:lastModifiedBy">
        <cmis:value>admin</cmis:value>
      </cmis:propertyString>
      <cmis:propertyString queryName="cmis:createdBy" displayName="Created by" localName="createdBy" propertyDefinitionId="cmis:createdBy">
        <cmis:value>System</cmis:value>
      </cmis:propertyString>
      <cmis:propertyId queryName="cmis:objectId" displayName="Object Id" localName="objectId" propertyDefinitionId="cmis:objectId">
        <cmis:value>workspace://SpacesStore/87b2f129-3ad0-4a46-a6ea-05ecbfb54aa1</cmis:value>
      </cmis:propertyId>
      <cmis:propertyId queryName="cmis:baseTypeId" displayName="Base Type Id" localName="baseTypeId" propertyDefinitionId="cmis:baseTypeId">
        <cmis:value>cmis:folder</cmis:value>
      </cmis:propertyId>
      <cmis:propertyId queryName="alfcmis:nodeRef" displayName="Alfresco Node Ref" localName="nodeRef" propertyDefinitionId="alfcmis:nodeRef">
        <cmis:value>workspace://SpacesStore/87b2f129-3ad0-4a46-a6ea-05ecbfb54aa1</cmis:value>
      </cmis:propertyId>
      <cmis:propertyDateTime queryName="cmis:lastModificationDate" displayName="Last Modified Date" localName="lastModificationDate" propertyDefinitionId="cmis:lastModificationDate">
        <cmis:value>2012-11-21T14:44:59.164Z</cmis:value>
      </cmis:propertyDateTime>
      <cmis:propertyId queryName="cmis:parentId" displayName="Parent Id" localName="parentId" propertyDefinitionId="cmis:parentId"/>
      <aspects:aspects xmlns="http://www.alfresco.org" xmlns:aspects="http://www.alfresco.org">
        <appliedAspects>P:app:uifacets</appliedAspects>
        <properties>
          <cmis:propertyString xmlns="http://docs.oasis-open.org/ns/cmis/core/200908/" xmlns:ns0="http://docs.oasis-open.org/ns/cmis/core/200908/" propertyDefinitionId="app:icon">
            <value>space-icon-default</value>
          </cmis:propertyString>
          <cmis:propertyString xmlns="http://docs.oasis-open.org/ns/cmis/core/200908/" xmlns:ns0="http://docs.oasis-open.org/ns/cmis/core/200908/" propertyDefinitionId="cm:description">
            <value>The company root space</value>
          </cmis:propertyString>
          <cmis:propertyString xmlns="http://docs.oasis-open.org/ns/cmis/core/200908/" xmlns:ns0="http://docs.oasis-open.org/ns/cmis/core/200908/" propertyDefinitionId="cmis:policyText"/>
          <cmis:propertyString xmlns="http://docs.oasis-open.org/ns/cmis/core/200908/" xmlns:ns0="http://docs.oasis-open.org/ns/cmis/core/200908/" propertyDefinitionId="cm:title">
            <value>Company Home</value>
          </cmis:propertyString>
        </properties>
        <appliedAspects>P:cm:titled</appliedAspects>
        <appliedAspects>P:sys:localized</appliedAspects>
      </aspects:aspects>
    </cmis:properties>
  </cmisra:object>
  <atom:link rel="service" href="' . $_SESSION['config']['coreurl'] . 'ws_server.php?cmis&amp;repositoryId=' . $this->getUuid() . '" type="application/atomsvc+xml"/>
  
</atom:entry>';
        exit;
    }
}

/*
'<atom:link rel="self" href="http://cmis.alfresco.com/cmisatom/371554cd-ac06-40ba-98b8-e6b60275cca7/entry?id=workspace%3A%2F%2FSpacesStore%2F87b2f129-3ad0-4a46-a6ea-05ecbfb54aa1" type="application/atom+xml;type=entry" cmisra:id="workspace://SpacesStore/87b2f129-3ad0-4a46-a6ea-05ecbfb54aa1"/>
  <atom:link rel="enclosure" href="http://cmis.alfresco.com/cmisatom/371554cd-ac06-40ba-98b8-e6b60275cca7/entry?id=workspace%3A%2F%2FSpacesStore%2F87b2f129-3ad0-4a46-a6ea-05ecbfb54aa1" type="application/atom+xml;type=entry"/>
  <atom:link rel="edit" href="http://cmis.alfresco.com/cmisatom/371554cd-ac06-40ba-98b8-e6b60275cca7/entry?id=workspace%3A%2F%2FSpacesStore%2F87b2f129-3ad0-4a46-a6ea-05ecbfb54aa1" type="application/atom+xml;type=entry"/>
  <atom:link rel="describedby" href="http://cmis.alfresco.com/cmisatom/371554cd-ac06-40ba-98b8-e6b60275cca7/type?id=cmis%3Afolder" type="application/atom+xml;type=entry"/>
  <atom:link rel="http://docs.oasis-open.org/ns/cmis/link/200908/allowableactions" href="http://cmis.alfresco.com/cmisatom/371554cd-ac06-40ba-98b8-e6b60275cca7/allowableactions?id=workspace%3A%2F%2FSpacesStore%2F87b2f129-3ad0-4a46-a6ea-05ecbfb54aa1" type="application/cmisallowableactions+xml"/>
  <atom:link rel="down" href="http://cmis.alfresco.com/cmisatom/371554cd-ac06-40ba-98b8-e6b60275cca7/children?id=workspace%3A%2F%2FSpacesStore%2F87b2f129-3ad0-4a46-a6ea-05ecbfb54aa1" type="application/atom+xml;type=feed"/>
  <atom:link rel="down" href="http://cmis.alfresco.com/cmisatom/371554cd-ac06-40ba-98b8-e6b60275cca7/descendants?id=workspace%3A%2F%2FSpacesStore%2F87b2f129-3ad0-4a46-a6ea-05ecbfb54aa1" type="application/cmistree+xml"/>
  <atom:link rel="http://docs.oasis-open.org/ns/cmis/link/200908/foldertree" href="http://cmis.alfresco.com/cmisatom/371554cd-ac06-40ba-98b8-e6b60275cca7/foldertree?id=workspace%3A%2F%2FSpacesStore%2F87b2f129-3ad0-4a46-a6ea-05ecbfb54aa1" type="application/cmistree+xml"/>
  <atom:link rel="http://docs.oasis-open.org/ns/cmis/link/200908/acl" href="http://cmis.alfresco.com/cmisatom/371554cd-ac06-40ba-98b8-e6b60275cca7/acl?id=workspace%3A%2F%2FSpacesStore%2F87b2f129-3ad0-4a46-a6ea-05ecbfb54aa1" type="application/cmisacl+xml"/>
  <atom:link rel="http://docs.oasis-open.org/ns/cmis/link/200908/policies" href="http://cmis.alfresco.com/cmisatom/371554cd-ac06-40ba-98b8-e6b60275cca7/policies?id=workspace%3A%2F%2FSpacesStore%2F87b2f129-3ad0-4a46-a6ea-05ecbfb54aa1" type="application/atom+xml;type=feed"/>
  <atom:link rel="http://docs.oasis-open.org/ns/cmis/link/200908/relationships" href="http://cmis.alfresco.com/cmisatom/371554cd-ac06-40ba-98b8-e6b60275cca7/relationships?id=workspace%3A%2F%2FSpacesStore%2F87b2f129-3ad0-4a46-a6ea-05ecbfb54aa1" type="application/atom+xml;type=feed"/>';
  */
