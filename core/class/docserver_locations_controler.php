<?php

/*
*   Copyright 2008-2011 Maarch
*
*   This file is part of Maarch Framework.
*
*   Maarch Framework is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Maarch Framework is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief  Contains the docserver_locations_controler Object 
* (herits of the BaseObject class)
* 
* 
* @file
* @author Luc KEULEYAN - BULL
* @author Laurent Giovannoni
* @date $date$
* @version $Revision$
* @ingroup core
*/

// To activate de debug mode of the class
$_ENV['DEBUG'] = false;

// Loads the required class
try {
    require_once ('core/core_tables.php');
    require_once ('core/class/docserver_locations.php');
    require_once ('core/class/ObjectControlerAbstract.php');
    require_once ('core/class/ObjectControlerIF.php');
    //require_once('apps/maarch_entreprise/tools/Net_Ping-2.4.5/Ping.php');
} catch (Exception $e){
    functions::xecho($e->getMessage()) . ' // ';
}

/**
* @brief  Controler of the docserver_locations object 
*
*<ul>
*  <li>Get an docserver_locations object from an id</li>
*  <li>Save in the database a docserver_locations</li>
*  <li>Manage the operation on the docserver_locations related tables 
*      in the database (insert, select, update, delete)
*  </li>
*</ul>
* @ingroup core
*/
class docserver_locations_controler extends ObjectControler 
    implements ObjectControlerIF
{
    public function testMethod($myVar) 
    {
        return $myVar;
    }
    
    /**
     * Save given object in database:
     * - make an update if object already exists,
     * - make an insert if new object.
     * Return updated object.
     * @param docservers_locations $docservers_locations
     * @return array
     */
    public function save($docserverLocation, $mode = '')
    {
        $control = array();
        if (!isset($docserverLocation) || empty($docserverLocation)) {
            $control = array(
                'status' => 'ko', 
                'value' => '', 
                'error' => _DOCSERVER_EMPTY
            );
            return $control;
        }
        $docserverLocation = $this->isADocserverLocation($docserverLocation);
        $this->set_foolish_ids(array('docserver_location_id'));
        $this->set_specific_id('docserver_location_id');
        if ($mode == 'up') {
            $control = $this->control($docserverLocation, 'up');
            if ($control['status'] == 'ok') {
                //Update existing docserver
                if ($this->update($docserverLocation)) {
                    $control = array(
                        'status' => 'ok', 
                        'value' => $docserverLocation->docserver_location_id
                    );
                    //history
                    if ($_SESSION['history']['docserverslocationsadd'] 
                        == 'true') {
                        $history = new history();
                        $history->add(
                            _DOCSERVER_LOCATIONS_TABLE_NAME, 
                            $docserverLocation->docserver_location_id, 
                            'UP', 'docserverslocationsadd',
                            _DOCSERVER_LOCATION_UPDATED . ' : ' 
                            . $docserverLocation->docserver_location_id, 
                            $_SESSION['config']['databasetype']
                        );
                    }
                } else {
                    $control = array(
                        'status' => 'ko', 
                        'value' => '', 
                        'error' => _PB_WITH_DOCSERVER_LOCATION
                    );
                }
                return $control;
            }
        } else {
            $control = $this->control($docserverLocation, 'add');
            if ($control['status'] == 'ok') {
                //Insert new docserver
                if ($this->insert($docserverLocation)) {
                    $control = array(
                        'status' => 'ok', 
                        'value' => $docserverLocation->docserver_location_id
                    );
                    //history
                    if ($_SESSION['history']['docserverslocationsadd'] 
                        == 'true') {
                        $history = new history();
                        $history->add(
                            _DOCSERVER_LOCATIONS_TABLE_NAME, 
                            $docserverLocation->docserver_location_id, 
                            'ADD', 'docserverslocationsadd',
                            _DOCSERVER_LOCATION_ADDED . ' : ' 
                            . $docserverLocation->docserver_location_id, 
                            $_SESSION['config']['databasetype']
                        );
                    }
                } else {
                    $control = array(
                        'status' => 'ko', 
                        'value' => '', 
                        'error' => _PB_WITH_DOCSERVER_LOCATION
                    );
                }
            }
        }
        return $control;
    }
    
    /**
    * control the docserver location object before action
    *
    * @param  $docserverLocations docserver location object
    * @return array ok if the object is well formated, ko otherwise
    */
    private function control($docserverLocations, $mode)
    {
        $f = new functions();
        $error = '';
        if (isset($docserverLocations->docserver_location_id) 
            && !empty($docserverLocations->docserver_location_id)) {
            // Update, so values exist
            $docserverLocations->docserver_location_id = 
                $f->wash(
                    $docserverLocations->docserver_location_id, 
                    'nick', _DOCSERVER_LOCATION_ID . ' ', 
                    'yes', 0, 32
                );
        } else {
            $error .= _DOCSERVER_LOCATION_ID . ' ' . _IS_EMPTY . '#';
        }
        $docserverLocations->ipv4 = 
            $f->wash(
                $docserverLocations->ipv4, 
                'no', _IPV4 . ' ', 
                'yes', 
                0, 
                255
            );
        if (!$this->ipv4Control($docserverLocations->ipv4)) {    
            $error .= _IP_V4_FORMAT_NOT_VALID . '#';
        }
        /*if (!empty($docserverLocations->ipv4)) {
            if (!$this->pingIpv4($docserverLocations->ipv4))
                $error .= _IP_V4_ADRESS_NOT_VALID . '#';
        }*/
        $docserverLocations->ipv6 = 
            $f->wash(
                $docserverLocations->ipv6, 
                'no', 
                _IPV6 . ' ', 
                'no', 
                0, 
                255
            );
        if (!$this->ipv6Control($docserverLocations->ipv6)) {    
            $error .= _IP_V6_NOT_VALID . '#';
        }
        $docserverLocations->net_domain = 
            $f->wash(
                $docserverLocations->net_domain, 
                'no', 
                _NET_DOMAIN . ' ', 
                'no', 
                0, 
                32
            );
        $docserverLocations->mask = 
            $f->wash(
                $docserverLocations->mask, 
                'no', 
                _MASK . ' ', 
                'no', 
                0, 
                255
            );
        if (!$this->maskControl($docserverLocations->mask)) {    
            $error .= _MASK_NOT_VALID . '#';
        }
        $docserverLocations->net_link = 
            $f->wash(
                $docserverLocations->net_link, 
                'no', 
                _NET_LINK . ' ', 
                'no', 
                0, 
                255
            );
        if ($mode == 'add' 
            && $this->docserverLocationExists(
                $docserverLocations->docserver_location_id
            )) {    
            $error .= $docserverLocations->docserver_location_id 
                    . ' ' . _ALREADY_EXISTS . '#';
        }
        $error .= $_SESSION['error'];
        //TODO:rewrite wash to return errors without html
        $error = str_replace('<br />', '#', $error);
        $return = array();
        if (!empty($error)) {
                $return = array(
                    'status' => 'ko', 
                    'value' => $docserverLocations->docserver_location_id, 
                    'error' => $error
                );
        } else {
            $return = array(
                'status' => 'ok', 
                'value' => $docserverLocations->docserver_location_id
            );
        }
        return $return;
    }
    
    /**
    * Inserts in the database (docserver_locations table) 
    * a docserver_locations object
    *
    * @param  $docserverLocation docserver_locations object
    * @return bool true if the insertion is complete, false otherwise
    */
    private function insert($docserverLocation)
    {
        return $this->advanced_insert($docserverLocation);
    }

    /**
    * Updates in the database (docserver_locations table) 
    * a docserver_locations object
    *
    * @param  $docserverLocation docserver_locations object
    * @return bool true if the update is complete, false otherwise
    */
    private function update($docserverLocation)
    {
        return $this->advanced_update($docserverLocation);
    }
    
    /**
    * Returns an docserver_locations object based 
    * on a docserver_locations identifier
    *
    * @param  $docserverLocationId string  docserver_locations identifier
    * @param  $comp_where string  where clause arguments 
    * (must begin with and or or)
    * @param  $can_be_disabled bool  if true gets the docserver_location 
    * even if it is disabled in the database (false by default)
    * @return docserver_locations object with properties from the database 
    * or null
    */
    public function get(
        $docserverLocationId, 
        $comp_where = '', 
        $can_be_disabled = false
    )
    {
        $this->set_foolish_ids(array('docserver_location_id'));
        $this->set_specific_id('docserver_location_id');
        $docserverLocation = $this->advanced_get(
            $docserverLocationId, _DOCSERVER_LOCATIONS_TABLE_NAME
        );

        if (isset ($docserverLocationId)) {
            return $docserverLocation;
        } else {
            return null;
        }
    }
    
    /**
    * get docserver_locations with given id for a ws.
    * Can return null if no corresponding object.
    * @param $docserverLocationId of docserver_location to send
    * @return docserver_locations
    */
    public function getWs($docserverLocationId)
    {
        $this->set_foolish_ids(array('docserver_location_id'));
        $this->set_specific_id('docserver_location_id');
        $docserverLocation = $this->advanced_get(
            $docserverLocationId, _DOCSERVER_LOCATIONS_TABLE_NAME
        );
        if (get_class($docserverLocation) <> 'docserver_locations') {
            return null;
        } else {
            $docserverLocation = $docserverLocation->getArray();
            return $docserverLocation;
        }
    }

    /**
    * Deletes in the database (docserver_locations related tables) 
    * a given docserver_locations (docserver_location_id)
    *
    * @param  $docserverLocationId string  docserver_locations identifier
    * @return bool true if the deletion is complete, false otherwise
    */
    public function delete($docserverLocation)
    {
        $func = new functions();
        $control = array();
        if (!isset($docserverLocation) || empty($docserverLocation)) {
            $control = array(
                'status' => 'ko', 
                'value' => '', 
                'error' => _DOCSERVER_LOCATION_EMPTY
            );
            return $control;
        }
        $docserverLocation = $this->isADocserverLocation($docserverLocation);
        if (!$this->docserverLocationExists(
            $docserverLocation->docserver_location_id
        )
        ) {
            $control = array(
                'status' => 'ko', 
                'value' => '', 
                'error' => _DOCSERVER_LOCATION_NOT_EXISTS
            );
            return $control;
        }
        if ($this->linkExists($docserverLocation->docserver_location_id)) {
            $control = array(
                'status' => 'ko', 
                'value' => '', 
                'error' => _LINK_EXISTS
            );
            return $control;
        }
        $db = new Database();
        $query = "delete from " . _DOCSERVER_LOCATIONS_TABLE_NAME 
               . " where docserver_location_id = ?";
        try {
            $stmt = $db->query($query, array($docserverLocation->docserver_location_id));
        } catch (Exception $e) {
            $control = array(
                'status' => 'ko', 
                'value' => '', 
                'error' => _CANNOT_DELETE_DOCSERVER_LOCATION_ID 
                . ' ' . $docserverLocation->docserver_location_id
            );
        }
        $control = array(
                'status' => 'ok', 
                'value' => $docserverLocation->docserver_location_id
            );
        if ($_SESSION['history']['docserverslocationsdel'] == 'true') {
            $history = new history();
            $history->add(
                _DOCSERVER_LOCATIONS_TABLE_NAME, 
                $docserverLocation->docserver_location_id, 
                'DEL', 'docserverslocationsdel',_DOCSERVER_LOCATION_DELETED . ' : ' 
                . $docserverLocation->docserver_location_id, 
                $_SESSION['config']['databasetype']
            );
        }
        return $control;
    }

    /**
    * Disables a given docserver_locations
    * 
    * @param  $docserverLocation docserver_locations object 
    * @return array
    */
    public function disable($docserverLocation)
    {
        $control = array();
        if (!isset($docserverLocation) || empty($docserverLocation)) {
            $control = array(
                'status' => 'ko', 
                'value' => '', 
                'error' => _DOCSERVER_LOCATION_EMPTY
            );
            return $control;
        }
        $docserverLocation = $this->isADocserverLocation($docserverLocation);
        if ($this->linkExists($docserverLocation->docserver_location_id)) {
            $control = array(
                'status' => 'ko', 
                'value' => '', 
                'error' => _LINK_EXISTS
            );
            return $control;
        }
        $this->set_foolish_ids(array('docserver_location_id'));
        $this->set_specific_id('docserver_location_id');
        if ($this->advanced_disable($docserverLocation)) {
            $control = array(
                'status' => 'ok', 
                'value' => $docserverLocation->docserver_location_id
            );
            if ($_SESSION['history']['docserverslocationsban'] == 'true') {
                $history = new history();
                $history->add(
                    _DOCSERVER_LOCATIONS_TABLE_NAME, 
                    $docserverLocation->docserver_location_id, 
                    'BAN', 'docserverslocationsban',
					_DOCSERVER_LOCATION_DISABLED . ' : ' 
                    . $docserverLocation->docserver_location_id, 
                    $_SESSION['config']['databasetype']
                );
            }
        } else {
            $control = array(
                'status' => 'ko', 
                'value' => '', 
                'error' => _PB_WITH_DOCSERVER_LOCATION
            );
        }
        return $control;
    }
    
    /**
    * Enables a given docserver_locations
    * 
    * @param  $docserverLocation docserver_locations object  
    * @return array
    */
    public function enable($docserverLocation)
    {
        $control = array();
        if (!isset($docserverLocation) || empty($docserverLocation)) {
            $control = array(
                'status' => 'ko', 
                'value' => '', 
                'error' => _DOCSERVER_LOCATION_EMPTY
            );
            return $control;
        }
        $docserverLocation = $this->isADocserverLocation($docserverLocation);
        $this->set_foolish_ids(array('docserver_location_id'));
        $this->set_specific_id('docserver_location_id');
        if ($this->advanced_enable($docserverLocation)) {
            $control = array(
                'status' => 'ok', 
                'value' => $docserverLocation->docserver_location_id
            );
            if ($_SESSION['history']['docserverslocationsallow'] == 'true') {
                $history = new history();
                $history->add(
                    _DOCSERVER_LOCATIONS_TABLE_NAME, 
                    $docserverLocation->docserver_location_id, 
                    'BAN', 'docserverslocationsallow',_DOCSERVER_LOCATION_ENABLED . ' : ' 
                    . $docserverLocation->docserver_location_id, 
                    $_SESSION['config']['databasetype']
                );
            }
        } else {
            $control = array(
                'status' => 'ko', 
                'value' => '', 
                'error' => _PB_WITH_DOCSERVER_LOCATION
            );
        }
        return $control;
    }
    
    /**
    * Fill a docserver_locations object with an object if it's not 
    * a docserver_locations
    *
    * @param  $object ws docserver_locations object
    * @return object docserver_locations
    */
    private function isADocserverLocation($object)
    {
        if (get_class($object) <> 'docserver_locations') {
            $func = new functions();
            $docserverLocationsObject = new docserver_locations();
            $array = array();
            $array = $func->object2array($object);
            foreach (array_keys($array) as $key) {
                $docserverLocationsObject->{$key} = $array[$key];
            }
            return $docserverLocationsObject;
        } else {
            return $object;
        }
    }

    /** 
    * Checks if a docserver_locations exists
    * 
    * @param $docserverLocationId docserver_locations object
    * @return bool true if the docserver_locations exists
    */
    public function docserverLocationExists($docserverLocationId)
    {
        if (!isset ($docserverLocationId) || empty ($docserverLocationId))
            return false;
        $db = new Database();
        $query = "select docserver_location_id from " 
               . _DOCSERVER_LOCATIONS_TABLE_NAME 
               . " where docserver_location_id = ?";
        try {
            $stmt = $db->query($query, array($docserverLocationId));
        } catch (Exception $e) {
            echo _UNKNOWN . _DOCSERVER_LOCATION . ' ' 
                . functions::xssafe($docserverLocationId) . ' // ';
        }
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    /**
    *  Checks if a docserver_locations is linked
    * 
    * @param $docserverLocationId docserver_locations object
    * @return bool true if the docserver_locations is linked
    */
    public function linkExists($docserverLocationId)
    {
        if (!isset($docserverLocationId) || empty($docserverLocationId))
            return false;
        $db=new Database();
        $query = "select docserver_location_id from " . _DOCSERVERS_TABLE_NAME 
               . " where docserver_location_id = ?";
        $stmt = $db->query($query, array($docserverLocationId));
        if ($stmt->rowCount()>0) {
            return true;
        }
    }
    
    /** 
    *  Check if the docserver location ipV4 is valid
    * 
    *  @param ipv4 docservers 
    *  @return bool true if it's valid  
    * 
    */     
    public function ipv4Control($ipv4)
    {
        if (empty($ipv4))
        return true;
        $ipv4 = htmlspecialchars($ipv4);    
        if (preg_match(
            "/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])"
            . "(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", 
            $ipv4
        )
        ) {        
            return true;
        } else {
            return false;
        }
    }
    
    /**
    * Check if the docserver location ipV6 is valid
    * 
    * @param ipv6 docservers 
    * @return bool true if it's valid 
    */    
    public function ipv6Control($ipv6)
    {
        if (empty($ipv6))
            return true;
        $ipv6 = htmlspecialchars($ipv6);
        $patternIpv6 = '/^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|'
                . '(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|'
                . '(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4}'
                . ')|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}'
                . '[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:'
                . '){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]'
                . '{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b('
                . '(25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5'
                . '])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:)'
                . '{0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.)'
                . '{3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|'
                . '(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})'
                . '|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|'
                . '(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::'
                . '([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})'
                . '|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})'
                . '|(([0-9A-Fa-f]{1,4}:){1,7}:))$/';        
        if (preg_match($patternIpv6, $ipv6)) {        
            return true;
        } else {
            return false;
        }
    }
    
    /** 
    * Check if the docserver location mask is valid
    * 
    * @param mask docservers 
    * @return bool true if it's valid  
    */    
    public function maskControl($mask)
    {
        if (empty($mask))
            return true;
        $mask = htmlspecialchars($mask);
        if (preg_match(
            "/^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}0$/", $mask
        )
        ) {        
            return true;
        } else {
            return false;
        }
    }
    
    /**
    * Returns in an array all the docservers of a docserver 
    * location (docserver_id only) 
    * 
    * @param  $docserverLocationId string  Docserver_location identifier
    * @return Array of docserver_id or null
    */
    public function getDocservers($docserverLocationId)
    {        
        if (empty($docserverLocationId))
            return null;
        $docservers = array();
        $db=new Database();
        $query = "select docserver_id from " . _DOCSERVERS_TABLE_NAME 
               . " where docserver_location_id = ?";
        try{
            $stmt = $db->query($query, array($docserverLocationId));
        } catch (Exception $e) {
                    echo _NO_DOCSERVER_LOCATION_WITH_ID . ' ' 
                    . functions::xssafe($docserverLocationId) . ' // ';
        }
        while ($res = $stmt->fetchObject()) {
            array_push($docservers, $res->docserver_id);
        }
        return $docservers;
    }

    /**
    * Return all docservers locations ID
    * @return array of docservers locations
    */
    public function getAllId($can_be_disabled = false)
    {
        $db = new Database();
        $query = "select docserver_location_id from " 
               . _DOCSERVER_LOCATIONS_TABLE_NAME . " ";
        if (!$can_be_disabled)
            $query .= " where enabled = 'Y'";
        try {
            $stmt = $db->query($query);
        } catch (Exception $e) {
            echo _NO_DOCSERVER_LOCATION . ' // ';
        }
        if ($stmt->rowCount() > 0) {
            $result = array ();
            $cptId = 0;
            while ($queryResult = $stmt->fetchObject()) {
                $result[$cptId] = $queryResult->docserver_location_id;
                $cptId++;
            }
            return $result;
        } else {
            return null;
        }
    }
    
    /**
    * Ping the ipv4
    * 
    * @param ipv4 docservers
    * @return bool true if valid     
    */
    public function pingIpv4 ($ipv4)
    {
        $ping = Net_Ping::factory();
        if (PEAR::isError($ping)) {
            return false;
        } else {
            $response = $ping->ping($ipv4);
            if ($response->getReceived() == $response->getTransmitted()) {
                return true;
            } else {
                return false;
            }
        }
    }
}

