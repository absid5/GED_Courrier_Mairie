<?php

class Query{

	//string select + from + where
	var $statement;  
	//boolean false
	var $searchAllVersions;
	//boolean false
	var $includeAllowableActions;
	//string : "none", "source", "target", "both"
	var $includeRelationships;
	//string
	var $renditionFilter;
	//integer : limit
	var $maxItems;
	//integer : offset
	var $skipCount;
	
	
	//
	var $sqlStatement;	
	
	var $keyWords = array (
			' * ',
			'select ',
			' from ',
			' where ',
			' order ',
			' by ',
			' desc ',
			' asc ',
			' and ',
			' or ', 
			' as ',
			' score ',
			' join ',
			' on ',
			' outer ',
			' inner ',
			' left ',
			' in ',
			' not ',
			' like ',
			' any ',
			' is ',
			' null ',
			' contains ',
			' in_folder ',
			' in_tree ',
		);
	
	var $notSupported = array( 
			" as ", 
			" join " , " outer ", " inner ", " on ", " left ", 
			" in_tree ", " in_tree(", 
			" contains ", " contains(", 
			" score ", " score(",
			" in_folder ", " in_folder("  // TODO 
		);
	
	var $maarchColumn = array(
			//res properties
			'cmis:objectId' => 'res_id',
			'maarch:type' => 'type_label',
			'maarch:entity ' => 'entity_label',
			'maarch:dest_user' => 'dest_user',
			'maarch:doc_date' => 'doc_date',
			'maarch:process_limit_date' => 'process_limit_date',
			'cmis:creationDate' => 'doc_date'
		);
	
	var $maarchTable = array(
			//res object type
			"cmis:document" => "res_view_letterbox"
		);
	
	
	
	public function __construct(){
		$this->statement = "";
		$this->searchAllVersions = false;
		$this->includeAllowableActions = false;
		$this->includeRelationships = "none";
		$this->renditionFilter = "";
		$this->skipCount = 0;
	}
	
	public function getStatement(){
		return $this->statement;
	}
	
	public function setStatement($statement){
		$this->statement = trim($statement);
	}
	
	public function setSearchAllVersions($searchAllVersions){
		//not supported
		$this->searchAllVersions = $searchAllVersions;
	}
	
	public function setIncludeAllowableActions($includeAllowableActions){
		//not supported
		$this->includeAllowableActions = $includeAllowableActions;
	}
	
	public function setIncludeRelationships($includeRelationships){
		// should be "none", "source", "target" or "both"
		//not supported
		$this->includeRelationships = $includeRelationships;
	}
	
	public function setRenditionFilter($renditionFilter){
		//not supported
		$this->renditionFilter = $renditionFilter ;
	}
	
	public function setMaxItems($maxItems){
		//not supported
		$this->maxItems = $maxItems ;
	}
	
	public function setSkipCount($skipCount){
		//not supported
		$this->skipCount = $skipCount;
	}
	
	public function to_String(){
		return   '<p><br />Query :'
				.'<br />  Statement : '. $this->statement
				.'<br />  SearchAllVersions : '. (($this->searchAllVersions)? 'true':'false')
				.'<br />  IncludeAllowableActions : '. (($this->includeAllowableActions)? 'true':'false')
				.'<br />  IncludeRelationships : '. $this->includeRelationships
				.'<br />  RenditionFilter : '. $this->renditionFilter
				.'<br />  MaxItems : '. $this->maxItems
				.'<br />  SkipCount : '. $this->skipCount
				.'<br /></p>';
	}
	
	
	//TODO exceptions
	public function executeQuery(){
		
		//format : keywords to lower case
		$tmpStatement = str_ireplace($this->keyWords, $this->keyWords, $this->statement);
		$tmpStatement = str_replace('(', ' ( ', $tmpStatement);
		$tmpStatement = str_replace(')', ' ) ', $tmpStatement);
		
		
		//pas de requete imbriquee
		if(substr_count(' '.$tmpStatement, ' select ') != 1 
				|| substr_count($tmpStatement , ' from ') != 1){
        	//TODO throw notSupported
			return null;
		}
		
		
		//not supported : contains(...), score(...), in_tree(...), in_folder(...)		
		$supportedTmpStatement = true;
		foreach ($this->notSupported as $notSupportedClause){
			$supportedTmpStatement = ($supportedTmpStatement && (stripos($tmpStatement, $notSupportedClause) === false));
			if(!$supportedTmpStatement){ 
        	//TODO throw notSupported
				return null;
			}
		}		
		
		
		//
		$arrayStatement = array();		
		$patternSql = '#^select\s+(.+)\sfrom\s+(.+)(?:where\s+(.+))?(?:\sorder\s+by\s+(.+))?$#isU';
		if(($preg = preg_match($patternSql, $tmpStatement, $arrayStatement)) !== 1){
        	//TODO throw notSupported or runtime
			return null;
		}
		
		
		$sqlStatement = array(
				'simpletable' => array(
						'select' => trim($arrayStatement[1]),
						'from'   => trim($arrayStatement[2]), 
						'where'  => trim($arrayStatement[3]),
				),
				'orderby'     =>  trim($arrayStatement[4])
			);
		
		
		//from : query only on cmis:document, only document objects are returned
		//object must be queryable
		if(strcmp('cmis:document', $sqlStatement['simpletable']['from']) != 0){
        	//TODO throw constraint ?
			return null;
		} 
		
		
		//list of selected properties
		$selectProperties = explode(',', $sqlStatement['simpletable']['select']);
		$res = new resCMIS();
		$res->DocumentCmis();
		foreach($selectProperties as $property){
			$objProperty = $res->getPropertyByQueryName($property);			
			if($objProperty == null){
        	//TODO throw constraint ?
				return null;
			}
		}
				
		
		//TODO : where (extract properties)
		//all column names must be valid queryName for queryable properties
		//object type listed in from listed
		$whereProperties = array();
		$wherePattern = '#(\w+\:\w+)#is';
		if(preg_match_all($wherePattern, $sqlStatement['simpletable']['where'], $whereProperties)){
			foreach($whereProperties[1] as $property){
				$objProperty = $res->getPropertyByQueryName($property);
				if($objProperty == null || !$objProperty->isQueryable()){
        			//TODO throw constraint ?
					return null;
				}
			}
		}
		
		
		//list of ordered properties
		$orderBy = str_ireplace(array('asc', 'desc'), array('','') , $sqlStatement['orderby']);
		$orderByProperties = explode(',', $orderBy);
		foreach($orderByProperties as $property){
			$objProperty = $res->getPropertyByQueryName($property);			
			if($objProperty == null || !$objProperty->isOrderable()){
        		//TODO throw constraint ?
				return null;
			}
		}
		
		
		//
		$tmpStatement = str_replace(array_keys($this->maarchColumn), $this->maarchColumn, $tmpStatement);
		$tmpStatement = str_replace(array_keys($this->maarchTable ), $this->maarchTable, $tmpStatement);
		$this->sqlStatement = $tmpStatement;
		
		
		require_once('core/class/class_db_pdo.php');
		$db = new Database();
		
		$result = $db->query($this->sqlStatement);
		
		$selectProperties = str_replace(array_keys($this->maarchColumn), $this->maarchColumn, $selectProperties);
		$resArray = array();
		while ($recordset = $result->fetchObject()) {
			$row = array();
			foreach($selectProperties as $selectedProperty){
				$selectedProperty = trim($selectedProperty);
				$row[$selectedProperty] = $recordset->{$selectedProperty};
			}			
			$documentCmis = new resCMIS();
			$documentCmis->DocumentCmis();
			$documentCmis->setRes($row);
			array_push($resArray, $documentCmis);
		}
		
		return $resArray;
		
	}
	
	
	
	
	
	
	
	//TODO fct a supprimer : utiliser les regex
	private function parseStatement(){ 
		$select = " select ";
		$from = " from ";
		$where = " where ";
		$orderby = " order by ";
		
		//statement ::= simpletable  [orderby]
		//simpletable ::= SELECT <selectlist> <fromclause> [<whereclause>]
		
		
		$sqlStatement = array(
				"simpletable" => array(
						"select" => array(),
						"from"   => array(),//should be "cmis:document"
						"where"  => array()
				),
				"orderby"     => array()
		);
		
		$tmpStatement = $this->statement;
		
		//select clause and from clause
		if(stripos($tmpStatement , 'select ')===0 && substr_count($tmpStatement , $select)==0 && substr_count($tmpStatement , $from)==1){
			
			//nb order by in tmpStatement
			$nbOrderBy = substr_count($tmpStatement , $orderby);
			
			if( $nbOrderBy == 1){
				//one order by in statement
				$explodedStatement = explode($orderby, $tmpStatement);
				if(count($explodedStatement) == 2){
					$sqlStatement[$orderby][0] = $explodedStatement[1];
					$tmpStatement = $explodedStatement[0];
					$nbOrderBy--;
				}
			}
			
			if($nbOrderBy == 0){
				//where clause
				$explodedStatement = explode($where, $tmpStatement, 2);
				if(count($explodedStatement) == 2){
					$sqlStatement["simpletable"][$where][0] = $explodedStatement[1];
					$tmpStatement = $explodedStatement[0];
				}
				$explodedStatement = explode($from, $tmpStatement);
				$sqlStatement["simpletable"][$from][0] = $explodedStatement[1];
				$tmpStatement = $explodedStatement[0];
				$explodedStatement = explode($select, $tmpStatement,1);
				$sqlStatement["simpletable"][$select][0] = $explodedStatement[0];
				
				$this->sqlStatement = $sqlStatement;
			}
			else{
				//error
				echo '<br /><strong>ERROR</strong><br />';
				
				$this->sqlStatement = null;
			}
		}
		else{
			//error
			echo '<br /><strong>ERROR</strong><br />';
			
			$this->sqlStatement = null;
		}
		
		return $this->sqlStatement;
	}
	
}
