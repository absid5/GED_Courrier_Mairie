<?php class log
{
	private $xml_file_path;
	private $xml_file;
	private $log_parent_node;
	private $log_node;
	private $log_date;
	
	function __construct($xml_file_path,$log_parent_node_name)
	{
		$this->xml_file_path = $xml_file_path;
		$this->xml_file = new DomDocument('1.0', 'iso-8859-1');
		$this->xml_file->preserveWhiteSpace = false;
		
		if(@$this->xml_file->load($xml_file_path))
		{
			if(!$log_parent_node =  $this->xml_file->getElementsByTagName($log_parent_node_name))
			{
				throw new Exception('Unable to find the parent node : '.$log_parent_node_name.' in the xml file '.$xml_file_path);
			}
			else
			{
				$this->log_parent_node = $log_parent_node->item(0);
			}
		}	
		else
		{
			$this->xml_file = new DomDocument('1.0', 'iso-8859-1');
			$this->xml_file->preserveWhiteSpace = false;
			$this->log_parent_node = $this->xml_file->createElement($log_parent_node_name);
			$this->xml_file->appendChild($this->log_parent_node);
		}
		$this->store();
	}
	
	function start()
	{
		$this->log_node = $this->xml_file->createElement("log");
		$this->log_node->setAttribute("date",date('dmY,H:i.s'));
		$this->log_node->setAttribute("state","start");
		$this->log_parent_node->appendChild($this->log_node);
		$this->store();
	} 
	
	function count()
	{
		if($tab_logs = $this->xml_file->getElementsByTagName("log"))
			return $tab_logs->length;
		else
			return 0;
	}
	
	function __destruct()
	{ 
		//$this->remove($this->log_node);
	}
	
	function remove($log_node)
	{
		$this->log_parent_node->removeChild($log_node);
		$this->store();
	}
	
	function add_notice($notice)
	{
		$notice_node = $this->xml_file->createElement("notice");
		$notice_text = $this->xml_file->createTextNode($notice);
		$notice_node->appendChild($notice_text);
		$this->log_node->appendChild($notice_node);
		$this->log_node->setAttribute("state","process");
		$this->store();
	}
	
	function add_error($error)
	{
		$erreur_node = $this->xml_file->createElement("error");
		$erreur_text =  $this->xml_file->createTextNode($error);
		$erreur_node->appendChild($erreur_text);
		$this->log_node->appendChild($erreur_node);
		$this->log_node->setAttribute("state","process");
		$this->store();
	}
	
	function add_fatal_error($error)
	{
		$this->add_error($error);
		$this->log_node->setAttribute("state","error");
		$this->store();
	}
	
	function end()
	{
		if($this->log_node->getAttribute("state") != "error")
			$this->log_node->setAttribute("state","done");
		$this->store();
	}
	
	function store()
	{
		$this->xml_file->formatOutput = true;
		$this->xml_file->normalize();
		if(!$this->xml_file->save($this->xml_file_path))
			throw new Exception('Unable to store the document '.$this->xml_file_path);
	}
	
	function purge($max_nb_log)
	{
		if( !$logs = $this->xml_file->getElementsByTagName("log") )
			return false;
		else
		{
			if( $this->count() > $max_nb_log && $max_nb_log != 0 )
			{
				$this->remove($logs->item(0));
				$this->purge($max_nb_log);
			}
			else
			{
				$this->store();
			}
		return true;
		}
	}
	
}
?>