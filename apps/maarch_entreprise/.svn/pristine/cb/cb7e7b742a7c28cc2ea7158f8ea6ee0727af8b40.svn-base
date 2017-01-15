<?php

abstract class objectCMIS
{
    
    protected $objectId  ;                 
    protected $createdBy  ;                
    protected $creationDate  ;             
    protected $lastModifiedBy  ;           
    protected $lastModificationDate  ;     
    protected $changeToken  ;              
    protected $localName;
    protected $localNamespace;
    protected $queryName;
    protected $displayName;
    protected $baseId;
    protected $parentId;
    protected $description ;               
    protected $creatable ;                 
    protected $fileable ;                  
    protected $queryable ;                 
    protected $controllablePolicy;         
    protected $controllableACL;            
    protected $fulltextIndexed;
    protected $includedInSupertypeQuery;
    
    protected $accessControlList;
    protected $properties;
    
    public static function getFeed($objects, $title=null, $parentObject=null){
        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->formatOutput = true;
    
        //TODO add xmlns
        $root = $doc->createElementNS('http://www.w3.org/2005/Atom', 'feed');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:cmisra', 'http://docs.oasis-open.org/ns/cmis/restatom/200908/');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:cmis', 'http://docs.oasis-open.org/ns/cmis/core/200909/');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:maarch', 'http://www.maarch.org');
        $doc->appendChild($root);
    
        $eAuthor = $doc->createElement('author');
        $root->appendChild($eAuthor);
        $name = $_SESSION['user']['FirstName'].' '.$_SESSION['user']['LastName'];  //'name';
        $eName = $doc->createElement('name', $name);
        $eAuthor->appendChild($eName);
    
         
        if(isset($title) && !empty($title)){
            $eTitle = $doc->createElement('title', $title);
            $root->appendChild($eTitle);
        }
        
        //  cmis:properties
        $eProperties = $doc->createElement('cmis:properties');
        $root->appendChild($eProperties);
        
        if($parentObject != null){
            //    cmis properties of parent
            foreach($parentObject->propertiesInXml as $property){
                $propertyValue = $property->getValue();
                if($propertyValue!=null && $property->valueIsSet()){
                    $eProperty = $doc->createElement('cmis:property'.$property->getPropertyType());
                    $eProperties->appendChild($eProperty);
                    $eProperty->setAttribute('localName', $property->getLocalName());
                    $eProperty->setAttribute('propertyDefinitionId', $property->getId());
            
                    if(strcmp($property->getCardinality(), 'Single')==0 ){
                        $eValue = $doc->createElement('cmis:value', $propertyValue); //echo $propertyValue.'<br />';
                        $eProperty->appendChild($eValue);
                    }
                    else{
                        //au moins une valeur non nulle
                        foreach($propertyValue as $value){
                            if($value != null){
                                $eValue = $doc->createElement('cmis:value', $value);
                                $eProperty->appendChild($eValue);
                            }
                        }
                    }
                }
            }   
        }
         
        $eNumItems = $doc->createElement('cmisra:numItems', count($objects));
        $root->appendChild($eNumItems);
    
        foreach($objects as $object){
            $object->getAtomXmlEntry($doc, $root);
        }
    
        return $doc;
    }
    
}
