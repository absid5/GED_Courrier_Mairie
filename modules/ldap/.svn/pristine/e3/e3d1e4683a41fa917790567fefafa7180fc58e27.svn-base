<?php 

class LDAP {

	protected $_domain=NULL;
	protected $_hostname=NULL;
   
    /**
    * Optional account with higher privileges for searching
    * This should be set to a domain admin account
    * 
    * @var string
    * @var string
    */
	protected $_login=NULL;
    protected $_password=NULL;
    
    /**
    *  
    * @var bool
    */
	protected $_ssl=false;
    
  	/*
	* Connection and bind default variables
    * 
    * @var mixed
    * @var mixed
    */
	protected $_conn;
	protected $_bind;

	
	function __construct($domain,$login,$password,$ssl='false',$hostname){
		
		$this->_domain = $domain;
		$this->_login = $login;
		$this->_password = $password;
		$this->_ssl = ($ssl == 'true');
		if( !empty($hostname) ) $this->_hostname = $hostname;
		
		// Connect to the openLDAP server as the username/password
		if ($this->_ssl)
		{
			if( isset($this->_hostname) )
				$this->_conn = ldap_connect("ldaps://".$this->_hostname);
			else
				$this->_conn = ldap_connect("ldaps://".$this->_domain);
		} 
		else 
		{
			if( isset($this->_hostname) )
				$this->_conn = ldap_connect($this->_hostname);
			else
				$this->_conn = ldap_connect($this->_domain);
		}
		
		
		
		// Set some ldap options for talking to sunLDAP
		ldap_set_option($this->_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($this->_conn, LDAP_OPT_REFERRALS, 0);
		
		// Bind as a domain admin if they've set it up
		if ($this->_login!=NULL && $this->_password!=NULL){
			$this->_bind = @ldap_bind($this->_conn,$this->_login,$this->_password);
			if (!$this->_bind){
				if ($this->_ssl)
				{
					throw new Exception ('FATAL: OpenLDAP bind failed. Either the LDAPS connection failed or the login credentials are incorrect.');
				} 
				else 
				{
					throw new Exception ("FATAL: OpenLDAP bind failed. Check the login credentials.");
				}
			}
		}
	}

	/**
    * Default Destructor
    * 
    * Closes the LDAP connection
    * 
    * @return void
    */
	function __destruct(){ ldap_close($this->_conn); }

    /**
    * Validate a user's login credentials
    * 
    * @param string $username A user's AD username
    * @param string $password A user's AD password
    * @param bool optional $prevent_rebind
    * @return bool
    */
	public function authenticate($login,$password,$prevent_rebind=false){
    
		// Prevent null binding
		if ($login==NULL || $password==NULL){ return (false); }
		
		//On recherche le DN de l'utilisateur
		$sr=ldap_search($this->_conn,"DC=".str_replace(".",",DC=",$this->_domain),"(uid=".$login.")",array("dn"));
		
		$entries = ldap_get_entries($this->_conn, $sr);
		
		if($entries['count'] != 1)
			return false;
			
		// Bind as the user	
		try{ $this->_bind = @ldap_bind($this->_conn,$entries[0]['dn'],$password); }
		catch(Exception $e){}
		
		if (!$this->_bind){ return (false); }
		
		// Cnce we've checked their details, kick back into admin mode if we have it
		if ($this->_login!=NULL && !$prevent_rebind){
			$this->_bind = @ldap_bind($this->_conn,$this->_login,$this->_password);
			if (!$this->_bind){ exit("FATAL: AD rebind failed."); } // This should never happen in theory
		}
		return (true);
	}

	//*****************************************************************************************************************
	// GROUP FUNCTIONS


  	/**
    * Group Information.  Returns an array of information about a group.
    * The group name is the distinguishedname
    * 
    * @param string $group_dn The group distinguishedname to retrieve info about
    * @param array $fields Fields to retrieve
    * @return array
    */
	public function group_info($group_dn,$fields=array(),$dn='',$filter=''){
	
		if ($group_dn==NULL){ return (false); }
		if (!$this->_bind){ return (false); }
		
		if(count($fields) < 1)
			$fields[] = "dn";
			
		if(empty($dn))
			$dn="DC=".str_replace(".",",DC=",$this->_domain);
			
		$entries = array();
		$filter = "(&".$filter."(entrydn=".$group_dn."))";
		
		$sr=ldap_search($this->_conn,$group_dn,$filter,$fields);
		$entries = ldap_get_entries($this->_conn, $sr);
		
		if($entries['count'] != 1)
		return array();
		
		$ad_info_group = array();
		
		foreach($fields as $fd)
		{
			if( $entries[0][$fd]['count'] > 1 )
			{
				unset($entries[0][$fd]['count']);
				$ad_info_group[$fd] = $entries[0][$fd];
			}
			else
			{
				$ad_info_group[$fd] = $entries[0][$fd][0];
			}
		}
		
		return $ad_info_group;
	} 
	
	
	/**
    * Return a list of all users in AD
    * 
    * @param bool $include_desc Return a description of the user
    * @param string $search Search parameter
    * @param bool $sorted Sort the user accounts
    * @return array
    */
    public function all_users($fields=array(),$dn='',$filter=''){
		
		if(empty($dn))
			$dn="DC=".str_replace(".",",DC=",$this->_domain);
			 
		if (!$this->_bind){ return (false); }
		
		if(count($fields) < 1)
			$fields[] = "dn";
		
		$entries = array();
		
		$filter = "(&".$filter.")";
		$sr=ldap_search($this->_conn,$dn,$filter,$fields);
		
		/*Condition qui permet de  parcourir tout l'annuaire en prenant les utilisateurs par ordres alphabétiques. Ainsi, on peut surpasser les 1000 utilisateurs*/
        $countResult=ldap_count_entries($this->_conn,$sr); 

        if($countResult == 1000)
        {
            // loop trough the number 97-122 (ASCII number for the characters a-z)
            for($a=97;$a<=122;$a++)
            {
                // translate the number to a character
                $character = chr($a);
                // the new search filter withs returns all users with a last name starting with $character
                $filterNew = "(&(sn=$character*)(objectClass=user)(objectCategory=person)".$filter.")";
                $results = ldap_search($this->_conn, $dn, $filterNew);

                $users = array();
                $users = ldap_get_entries($this->_conn,$results);
                $entries = array_merge($entries, $users);                
            }
        }
        else
        {
             $users = ldap_get_entries($this->_conn,$sr);
             $entries = array_merge($entries, $users);
        }
        		
		
		/*permet de récupérer les utilisateurs (va prendre que les 1000 premiers. Il faudra commenter la condition ci-dessus*/
		//$entries = array_merge(ldap_get_entries($this->_conn, $sr),$entries);
		
		$ad_users = array();
		//for ($i=0; $i < 10; $i++)
		for ($i=0; $i < (count($entries)-1); $i++)
		{
			foreach($fields as $fd)
			{
				if( $entries[$i][$fd]['count'] > 1 )
				{
					unset($entries[$i][$fd]['count']);
					$ad_users[$i][$fd] = $entries[$i][$fd];
				}
				else
				{
					$ad_users[$i][$fd] = $entries[$i][$fd][0];
				}
			}
		}
		
		return $ad_users;
    }
	
	public function all_groups($fields=array(),$dn='',$filter=''){
		
		if(empty($dn))
			$dn="DC=".str_replace(".",",DC=",$this->_domain);
		
        if (!$this->_bind){ return (false); }
        
		if(count($fields) < 1)
			$fields[]="dn";
		
		$entries = array();
		
		//Search for each filter
		$filter = "(&".$filter.")";
			
		$sr=ldap_search($this->_conn,$dn,$filter,$fields);
		$entries = ldap_get_entries($this->_conn, $sr);
		
		for ($i=0; $i< ( count($entries) -1); $i++)
		{
			foreach($fields as $fd)
			{
				if( $entries[$i][$fd]['count'] > 1 )
				{
					unset($entries[$i][$fd]['count']);
					$ad_groups[$i][$fd] = $entries[$i][$fd];
				}
				else
				{
					$ad_groups[$i][$fd] = $entries[$i][$fd][0];
				}
			}
		}
		
		return $ad_groups;
    }
    
}

?>