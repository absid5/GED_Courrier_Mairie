<?php

class PropertyInteger extends propertyCMIS{


  private $maxValue; //Integer
  private $minValue; //Integer
  

  public function __construct( $required, $inherited, $propertyType,
        $cardinality, $updatability, $choices, $openChoice, $queryable,
        $orderable){
          
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
}
