<?php

class PropertyDecimal extends propertyCMIS{

  private $precision; //integer enum: 32, 64
  private $minValue;  //decimal
  private $maxValue;  //decimal
  
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
