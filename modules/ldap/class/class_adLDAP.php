<?php /**
 * PHP LDAP CLASS FOR MANIPULATING ACTIVE DIRECTORY
 * Version 3.0
 *
 * PHP Version 5 with SSL and LDAP support
 *
 * Written by Scott Barnett, Richard Hyland
 *   email: scott@wiggumworld.com, adldap@richardhyland.com
 *   http://adldap.sourceforge.net/
 *
 * Copyright (c) 2006-2009 Scott Barnett, Richard Hyland
 *
 * We'd appreciate any improvements or additions to be submitted back
 * to benefit the entire community :)
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * @category ToolsAndUtilities
 * @package adLDAP
 * @author Scott Barnett, Richard Hyland
 * @copyright (c) 2006-2009 Scott Barnett, Richard Hyland
 * @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html LGPLv2.1
 * @version 3.0
 * @link http://adldap.sourceforge.net/
 */


 /**
* Main adLDAP class
*
* Can be initialised using $adldap = new adLDAP();
*
* Something to keep in mind is that Active Directory is a permissions
* based directory. If you bind as a domain user, you can't fetch as
* much information on other users as you could as a domain admin.
*
* Before asking questions, please read the Documentation at
* http://adldap.sourceforge.net/wiki/doku.php?id=api
*/
class LDAP {

    /**
     * Define the different types of account in AD
     */
    const ADLDAP_NORMAL_ACCOUNT = 805306368;
    const ADLDAP_WORKSTATION_TRUST = 805306369;
    const ADLDAP_INTERDOMAIN_TRUST = 805306370;
    const ADLDAP_SECURITY_GLOBAL_GROUP = 268435456;
    const ADLDAP_DISTRIBUTION_GROUP = 268435457;
    const ADLDAP_SECURITY_LOCAL_GROUP = 536870912;
    const ADLDAP_DISTRIBUTION_LOCAL_GROUP = 536870913;


    protected $_domain=NULL;

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
    * Use SSL, your server needs to be setup, please see
    * http://adldap.sourceforge.net/wiki/doku.php?id=ldap_over_ssl
    *
    * @var bool
    */
    protected $_use_ssl=false;

    /*
    * Connection and bind default variables
    *
    * @var mixed
    * @var mixed
    */
    protected $_conn;
    protected $_bind;


    function __construct($domain,$login,$password,$ssl='false'){

        $this->_domain = $domain;
        $this->_login = $login;
        $this->_password = $password;
        $this->_use_ssl = ($ssl == 'true');

        // Connect to the AD/LDAP server as the username/password
        if ($this->_use_ssl)
        {
            //echo "ldaps://".$this->_domain;
            $this->_conn = ldap_connect("ldaps://".$this->_domain);
        }
        else
        {
            $this->_conn = ldap_connect($this->_domain);
        }

        // Set some ldap options for talking to AD
        ldap_set_option($this->_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->_conn, LDAP_OPT_REFERRALS, 0);

        // Bind as a domain admin if they've set it up
        if ($this->_login!=NULL && $this->_password!=NULL){
            //$this->_bind = @ldap_bind($this->_conn,$this->_login."@".$this->_domain,$this->_password);
            //echo '<pre>';
            //var_dump($this);
            //echo '</pre>';
            $this->_bind = @ldap_bind($this->_conn,$this->_login,$this->_password);
            if (!$this->_bind){
                if ($this->_use_ssl)
                {
                    throw new Exception ('FATAL: AD bind failed. Either the LDAPS connection failed or the login credentials are incorrect.');
                }
                else
                {
                    throw new Exception ("FATAL: AD bind failed. Check the login credentials.");
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
        echo 'ici1'.'<br />';
        if ($login==NULL || $password==NULL){ return (false);}

        // Bind as the user
        echo 'ici2'.'<br />';
        try{ $this->_bind = ldap_bind($this->_conn,$login,$password); }
        catch(Exception $e){}

        echo 'ici3'.'<br />';
        if (!$this->_bind){ return (false);}

        // Cnce we've checked their details, kick back into admin mode if we have it
        echo 'ici4'.'<br />';
        if ($this->_login!=NULL && !$prevent_rebind){
            $this->_bind = @ldap_bind($this->_conn,$this->_login,$this->_password);
            if (!$this->_bind){ echo ("FATAL: AD rebind failed."); exit(); } // This should never happen in theory
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
            $fields[] = "distinguishedname";

        if(empty($dn))
            $dn="DC=".str_replace(".",",DC=",$this->_domain);

        $entries = array();

        $filter="(&(objectCategory=group)(distinguishedName=".$group_dn.")".$filter.")";
        $sr=ldap_search($this->_conn,$dn,$filter,$fields);
        $entries = ldap_get_entries($this->_conn, $sr);

        if($entries['count'] != 1)
            return array();

        $ad_info_group = array();

        foreach($fields as $fd)
        {
            if( $fd == 'memberof')
            {
                unset($entries[0][$fd]['count']);
                $ad_info_group[$fd] = $entries[0][$fd];
            }
            else if( $fd == 'objectguid' && !empty($entries[0][$fd][0]) )
            {
                $ad_info_group[$fd] = bin2hex($entries[0][$fd][0]);
            }
            else if( $fd == 'objectguid' && empty($entries[0][$fd][0]) )
            {
                //Le groupe n'a pas de objectguid (pb sur l'annuaire)
                return array();
            }
            else if( $fd == 'member')
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
            $fields[] = "distinguishedname";

        $entries = array();

        $filter = "(&(objectClass=user)(objectCategory=person)".$filter.")";
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

        for ($i=0; $i < (count($entries)-1); $i++)
        {
            foreach($fields as $fd)
            {
                if( $fd == 'objectguid' && !empty($entries[$i][$fd][0]) )
                {
                    $ad_users[$i][$fd] = bin2hex($entries[$i][$fd][0]);
                }
                else if( $fd == 'objectguid' && empty($entries[$i][$fd][0]) )
                {
                    //L'utilisateur n'a pas de objectguid (pb sur l'annuaire)
                    unset($ad_users[$i]);
                    break;
                }
                else if( $fd == 'memberof')
                {
                    unset($entries[$i][$fd]['count']);
                    $ad_users[$i][$fd] = $entries[$i][$fd];
                }
                else if( $fd == 'useraccountcontrol')
                {
                    if( ($entries[$i][$fd][0] & 2) == 0)
                        $ad_users[$i][$fd] = 'Y';
                    else
                        $ad_users[$i][$fd] = 'N';
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
            $fields[]="distinguishedname";

        $entries = array();

        //Search for each filter
        $filter = "(&(objectClass=group)".$filter.")";

        $sr=ldap_search($this->_conn,$dn,$filter,$fields);
        $entries = ldap_get_entries($this->_conn, $sr);

        for ($i=0; $i< ( count($entries) -1); $i++)
        {
            foreach($fields as $fd)
            {
                if( $fd == 'objectguid' && !empty($entries[$i][$fd][0]) )
                    $ad_groups[$i][$fd] = bin2hex($entries[$i][$fd][0]);

                else if( $fd == 'objectguid' && empty($entries[$i][$fd][0]) )
                {
                    //Le groupe n'a pas de objectguid (pb sur l'annuaire)
                    unset($ad_groups[$i]);
                    break;
                }
                else if( $fd == 'memberof')
                {
                    unset($entries[$i][$fd]['count']);
                    $ad_groups[$i][$fd] = $entries[$i][$fd];
                }
                else if( $fd == 'member')
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

        return ($ad_groups);
    }

}

?>
