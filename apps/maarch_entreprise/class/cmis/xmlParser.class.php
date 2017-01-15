<?php
require_once 'cmis_class_query.php';

class xmlParser{
    

    
    public function parseQuery($atomFileContent){
        
        $query = new Query();
        //$atomXmlDocument = base64_decode($atomFileContent);
        $atomXmlDocument = $atomFileContent;
        
        //
        $example = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        .'<cmis:query xmlns:cmis="http://docs.oasis-open.org/ns/cmis/core/200908/" xmlns:cmism="http://docs.oasis-open.org/ns/cmis/messaging/200908/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:app="http://www.w3.org/2007/app" xmlns:cmisra="http://docs.oasis-open.org/ns/cmis/restatom/200908/" xmlns:maarch="http://www.maarch.org">'
        .'   <cmis:statement>SELECT * FROM cmis:document</cmis:statement>'
        .'   <cmis:searchAllVersions>true</cmis:searchAllVersions>'
        .'   <cmis:includeAllowableActions>false</cmis:includeAllowableActions>'
        .'   <cmis:includeRelationships>none</cmis:includeRelationships>' 
        .'   <cmis:renditionFilter>*</cmis:renditionFilter>'
        .'   <cmis:maxItems>50</cmis:maxItems>'
        .'   <cmis:skipCount>0</cmis:skipCount>'
        .'</cmis:query>';
                
        $document_xml = new DomDocument('1.0', 'utf-8');
        $document_xml->loadXML($atomXmlDocument);
        //$document_xml->loadXML($example);
        
        $elements = $document_xml->getElementsByTagName("query");
        $tree = $elements->item(0);
        $children = $tree->childNodes;
        
        foreach($children as $child){
            $name = $child->nodeName;
            //echo '<br />'.$name.'<br />';
            switch($name){
                case 'cmis:statement': 
                    $query->setStatement($child->nodeValue);break;
                case 'cmis:searchAllVersions': 
                    $searchAllVersions = (strtolower($child->nodeValue)==='true');
                    $query->setSearchAllVersions($searchAllVersions);break;
                case 'cmis:includeAllowableActions': 
                    $includeAllowableActions = (strtolower($child->nodeValue)==='true');
                    $query->setIncludeAllowableActions($includeAllowableActions);break;
                case 'cmis:includeRelationships': 
                    $query->setIncludeRelationships($child->nodeValue);break;
                case 'cmis:renditionFilter': 
                    $query->setRenditionFilter($child->nodeValue);break;
                case 'cmis:maxItems': 
                    $query->setMaxItems(intval($child->nodeValue));break;
                case 'cmis:skipCount': 
                    $query->setSkipCount(intval($child->nodeValue));break;
                default : ;break;
            }
        }
        
        return $query;
    }   
    

    public function getCmisFolderFromXml($atomFileContent){
        
        $newFolder = new folderCMIS();
        $newFolder->FolderCmis();
        
        //$atomXmlDocument = base64_decode($atomFileContent);
        $atomXmlDocument = $atomFileContent;
        $document_xml = new DomDocument('1.0', 'utf-8');
        $document_xml->loadXML($atomXmlDocument);
        
        $elements = $document_xml->getElementsByTagName("entry");
        $tree = $elements->item(0);
        $children = $tree->childNodes;
        
        //$error = true if cmis:objectTypeId != cmis:folder     
        $error = false;
        
        foreach($children as $child){
            if($error) break;
            $name = $child->nodeName;
            
            switch($name){
                case 'title': 
                    $newFolder->setNameValue($child->nodeValue);
                    break;
                case 'cmisra:object':
                    $objectsChildren = $child->childNodes;
                    
                    //properties
                    foreach($objectsChildren as $objectsChild){
                        if($error) break;
                        if(strcmp($objectsChild->nodeName, 'cmis:properties')==0 ){
            
                            //property
                            foreach($objectsChild->childNodes as $property){
                                if($error) break;
                                if($property->hasAttributes()){
                                    
                                    if((strcmp($property->nodeName, 'cmis:propertyId')==0)) {                                   
                                        $propertyDefinitionId= $property->attributes->getNamedItem('propertyDefinitionId')->nodeValue;                                      
                                        if(strcmp($propertyDefinitionId, 'cmis:objectTypeId')==0){                  
                                            foreach($property->childNodes as $node){
                                                if(strcmp($node->nodeName, 'value')==0){
                                                    $error = (strcmp($value->nodeValue, 'cmis:folder')!=0);
                                                }
                                            }                                                                       
                                        }
                                        elseif(strcmp($propertyDefinitionId, 'folderId')==0){
                                            foreach($property->childNodes as $node){
                                                if(strcmp($node->nodeName, 'cmis:value')==0){
                                                    $newFolder->setFolderIdValue($node->nodeValue);
                                                }
                                            }                                           
                                        }                                   
                                    }
                                    
                                }                               
                            }
                        }                   
                    }                   
                    break;
                default :
                    break;
            }
        }
        
        
        return $error ? null : $newFolder;
    }
    
    
    
    
}


