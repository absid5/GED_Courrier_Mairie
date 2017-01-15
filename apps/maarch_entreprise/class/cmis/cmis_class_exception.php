<?php


class CMISException extends Exception{
	
	private $cause;
	
	public function __construct($message, $code, $cause){
		parent::__construct($message, $code);
		$this->cause = $cause;
	}
	
	public function toXml(){
		
		$doc = new DOMDocument('1.0', 'utf-8');
		$doc->formatOutput = true;
		
		$root = $doc->createElement('Exception');
		$doc->appendChild($root);
		
		$eCode = $doc->createElement('code',$this->getCode());
		$eCause = $doc->createElement('cause',$this->getCause());
		$eMessage = $doc->createElement('message',$this->getMessage());
		
		$root->appendChild($eCode);
		$root->appendChild($eCause);
		$root->appendChild($eMessage);
		
		return $doc->saveXML();		
	}
	
	public function getCause(){
		return $this->getCause();
	}
	
}

