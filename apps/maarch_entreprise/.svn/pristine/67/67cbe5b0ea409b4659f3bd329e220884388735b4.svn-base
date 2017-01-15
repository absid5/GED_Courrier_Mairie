<?php

abstract class propertyCMIS{
    
    //protected $propertyName;        
    protected $id;          
    protected $localName;
    protected $localNamespace;
    protected $queryName;
    protected $displayName;         
    protected $description;         
    protected $propertyType;        
    protected $cardinality;         
    protected $value;
    protected $updatability;        
    protected $inherited;           
    protected $required;            
    protected $queryable;           
    protected $orderable;          
    protected $choices;            
    protected $openChoice;         
    protected $defaultValue;           
    
    public function __construct(){
        $this->value = 'notset';
        $this->openChoice = true;
    }
    
    public function getQueryName(){
        return $this->queryName;
    }
    
    public function isQueryable(){
        return $this->queryable;
    }
    
    public function isOrderable(){
        return $this->orderable;
    }
    
    public function isRequired(){
        return $this->required;
    }
    
    public function setId($id) {
        $this->id = $id;
        $this->localName = 'rm_'.$id;
    }
    
    public function valueIsSet(){
        return ($this->value!=null && strcmp( $this->value, 'notset') != 0);
    }
    
    public function setNames($localName, $localNamespace, $queryName, $displayName){
        $this->localName = $localName;
        $this->localNamespace = $localNamespace;
        $this->queryName = $queryName;
        $this->displayName = $displayName;
    }
    
    /*public function setNames($name){
        $this->localName = $name;
        $this->queryName = $name;
        $this->displayName = $name;     
    }*/
        
    public function setLocalName($localName) {
        $this->localName = $localName;
    }
    public function setLocalNamespace($localNamespace) {
        $this->localNamespace = $localNamespace;
    }
    public function setQueryName($queryName) {
        $this->queryName = $queryName;
    }
    public function setDisplayName($displayName) {
        $this->displayName = $displayName;
    }
    public function setDescription($description) {
        $this->description = $description;
    }
    public function setPropertyType($propertyType) {
        $this->propertyType = $propertyType;
    }
    public function setCardinality($cardinality) {
        $this->cardinality = $cardinality;
    }
    public function setUpdatability($updatability) {
        $this->updatability = $updatability;
    }
    public function setInherited($inherited) {
        $this->inherited = $inherited;
    }
    public function setRequired($required) {
        $this->required = $required;
    }
    public function setQueryable($queryable) {
        $this->queryable = $queryable;
    }
    public function setOrderable($orderable) {
        $this->orderable = $orderable;
    }
    public function setChoices($choices) {
        $this->choices = $choices;
    }
    public function setOpenChoice($openChoice) {
        $this->openChoice = $openChoice;
    }
    public function setDefaultValue($defaultValue) {
        $this->defaultValue = $defaultValue;
    }
    
    public function setValue($value) {
        $this->value = $value;
    }
    
    public function getCardinality(){
        return $this->cardinality;
    }
    
    public function getLocalName(){
        return $this->localName;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getValue(){
        return $this->value  ;
    }
    
    public function getPropertyType() {
        return $this->propertyType;
    }
    
    protected function valueSuitable($value){
        
        $isSuitable = false;
    
        if(is_string($value) && strcmp('notset',$value)==0) {
            return true;
        }
    
        
        if($this->openChoice === true || strcmp('NotApplicable',$this->openChoice)==0){
            $isSuitable = true;
            //OpenChoice is true or NotApplicable: ;
        }
        else if($this->openChoice === false){
            if((strcmp('Single',$this->cardinality)==0) && in_array($value, $this->choices, true)){
                $isSuitable = true;
                //Cardinality = Single and value in choices;
            }
            else if((strcmp('Multi',$this->cardinality)==0)){
                $valueOk = true;
                $i=0;
                while($valueOk && $item=$value[$i++]){
                    $valueOk = in_array($item, $this->choices, true);
                }
                $isSuitable = $valueOk;
                //IF isSuitable : Cardinality = Multi and value in choices
                //ELSE : Cardinality = Multi but value not in choices
            }
        }
        
        return $isSuitable;
    }
    
    
}




class PropertyUri extends propertyCMIS{

    public function __construct( $required, $inherited, $propertyType,
            $cardinality, $updatability, $choices, $openChoice, $queryable,
            $orderable){
        
        $this->value = 'notset';
        $this->required = $required ;
        $this->inherited = $inherited ;
        $this->propertyType = $propertyType;
        $this->cardinality = $cardinality ;
        $this->updatability = $updatability ;
        $this->choices = $choices ;
        $this->openChoice = $openChoice ;
        $this->queryable = $queryable ;
        $this->orderable = $orderable;

    }

    public function setValue($value){
        if(is_string($value)){
            $value = trim($value);
            if( $this->valueSuitable($value) === true){
                $this->value = $value;
            }
        }
    }
}




class PropertyBoolean extends propertyCMIS{


    public function __construct( $required, $inherited, $propertyType,
            $cardinality, $updatability, $choices, $openChoice, $queryable,
            $orderable){
        
        $this->value = 'notset';
        $this->required = $required ;
        $this->inherited = $inherited ;
        $this->propertyType = $propertyType;
        $this->cardinality = $cardinality ;
        $this->updatability = $updatability ;
        $this->choices = $choices ;
        $this->openChoice = $openChoice ;
        $this->queryable = $queryable ;
        $this->orderable = $orderable;

    }
    
    public function setValue($value){
        if(is_string($value)){
            if(strcmp(trim($value), 'true')==0){
                $value = true;
            }
            else if(strcmp(trim($value), 'false')==0){
                $value = false;
            }
        }
        if(is_bool($value) && $this->valueSuitable($value) === true){
            $this->value = $value;
        }
    }
}


class PropertyDateTime extends propertyCMIS{

    private $resolution; // string enum: Year, Date, Time

    public function __construct( $required, $inherited, $propertyType,
            $cardinality, $updatability, $choices, $openChoice, $queryable,
            $orderable){
        
        $this->value = 'notset';
        $this->required = $required ;
        $this->inherited = $inherited ;
        $this->propertyType = $propertyType;
        $this->cardinality = $cardinality ;
        $this->updatability = $updatability ;
        $this->choices = $choices ;
        $this->openChoice = $openChoice ;
        $this->queryable = $queryable ;
        $this->orderable = $orderable;

    }

    public function setValue($value){
        if(is_string($value)){
            $value = trim($value);
            if($this->valueSuitable($value) === true){
                //if regex ok
                $this->value = $value;
            }
        }
    }
}



class PropertyDecimal extends propertyCMIS{

    private $precision; //integer enum: 32, 64
    private $minValue;  //decimal
    private $maxValue;  //decimal

    public function __construct( $required, $inherited, $propertyType,
            $cardinality, $updatability, $choices, $openChoice, $queryable,
            $orderable){
        
        $this->value = 'notset';
        $this->required = $required ;
        $this->inherited = $inherited ;
        $this->propertyType = $propertyType;
        $this->cardinality = $cardinality ;
        $this->updatability = $updatability ;
        $this->choices = $choices ;
        $this->openChoice = $openChoice ;
        $this->queryable = $queryable ;
        $this->orderable = $orderable;

    }
    
    public function _setValue($value){
        if(is_string($value)){
            $value = floatval($value);
        }
        if (is_float($value) && $this->valueSuitable($value) === true){         
            $this->value = $value;
        }
    }
}




class PropertyHtml extends propertyCMIS{

    public function __construct( $required, $inherited, $propertyType,
            $cardinality, $updatability, $choices, $openChoice, $queryable,
            $orderable){
        
        $this->value = 'notset';
        $this->required = $required ;
        $this->inherited = $inherited ;
        $this->propertyType = $propertyType;
        $this->cardinality = $cardinality ;
        $this->updatability = $updatability ;
        $this->choices = $choices ;
        $this->openChoice = $openChoice ;
        $this->queryable = $queryable ;
        $this->orderable = $orderable;

    }
    
    public function _setValue($value){
        if(is_string($value)){
            $value = trim($value);
            if($this->valueSuitable($value) === true){
                $this->value = $value;
            }
        }
    }

}





class PropertyId extends propertyCMIS{

    public function __construct( $required, $inherited, $propertyType,
            $cardinality, $updatability, $choices, $openChoice, $queryable,
            $orderable){
        
        $this->value = 'notset';
        $this->required = $required ;
        $this->inherited = $inherited ;
        $this->propertyType = $propertyType;
        $this->cardinality = $cardinality ;
        $this->updatability = $updatability ;
        $this->choices = $choices ;
        $this->openChoice = $openChoice ;
        $this->queryable = $queryable ;
        $this->orderable = $orderable;

    }
    
    public function setValue($value){
        if(is_string($value)){
            $value = trim($value);
        }
        if($this->valueSuitable($value) === true){
            $this->value = $value;
        }
    }
    
}



class PropertyInteger extends propertyCMIS{


    private $maxValue; //Integer
    private $minValue; //Integer


    public function __construct( $required, $inherited, $propertyType,
            $cardinality, $updatability, $choices, $openChoice, $queryable,
            $orderable){
        
        $this->value = 'notset';
        $this->required = $required ;
        $this->inherited = $inherited ;
        $this->propertyType = $propertyType;
        $this->cardinality = $cardinality ;
        $this->updatability = $updatability ;
        $this->choices = $choices ;
        $this->openChoice = $openChoice ;
        $this->queryable = $queryable ;
        $this->orderable = $orderable;

    }
    
    public function _setValue($value){
        if(is_string($value)){
            $value = intval($value);
        }
        if (is_int($value) && $this->valueSuitable($value) === true){           
            $this->value = $value;
        }
    }
}


class PropertyString extends propertyCMIS{

    private $maxLength;
    

    public function __construct($required, $inherited, $propertyType,
            $cardinality, $updatability, $choices, $openChoice, $queryable,
            $orderable){
        
        $this->value = 'notset';
        $this->required = $required ;
        $this->inherited = $inherited ;
        $this->propertyType = $propertyType;
        $this->cardinality = $cardinality ;
        $this->updatability = $updatability ;
        $this->choices = $choices ;
        $this->openChoice = $openChoice ;
        $this->queryable = $queryable ;
        $this->orderable = $orderable;

    }
    
    public function setValue($value){
        
        if(is_string($value)){
            $value = trim($value);
            if($this->valueSuitable($value) === true){
                $this->value = $value;
            }
        }
    }
    
}



