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
*   along with Maarch Framework. If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief Contains the docservers_controler Object
* (herits of the BaseObject class)
*
* @file
* @author Luc KEULEYAN - BULL
* @author Laurent Giovannoni
* @date $date$
* @version $Revision$
* @ingroup core
*/

//Loads the required class
try {
    require_once 'core/class/class_request.php';
    require_once 'core/class/docservers.php';
    require_once 'core/docservers_tools.php';
    require_once 'core/core_tables.php';
    require_once 'core/class/ObjectControlerAbstract.php';
    require_once 'core/class/ObjectControlerIF.php';
    require_once 'core/class/class_security.php';
    require_once 'core/class/class_resource.php';
    require_once 'core/class/class_history.php';
} catch (Exception $e) {
    functions::xecho($e->getMessage()) . ' // ';
}

/**
 * Class for controling docservers objects from database
 */
class docservers_controler
    extends ObjectControler
    implements ObjectControlerIF
{

    /**
     * Save given object in database:
     * - make an update if object already exists,
     * - make an insert if new object.
     * Return updated object.
     * @param docservers $docservers
     * @return array
     */
    public function save($docserver, $mode='')
    {
        //var_dump($docserver);
        $control = array();
        if (!isset($docserver) || empty($docserver)) {
            $control = array(
                'status' => 'ko',
                'value' => '',
                'error' => _DOCSERVER_EMPTY,
            );
            return $control;
        }
        $docserver = $this->isADocserver($docserver);
        $this->set_foolish_ids(
            array(
                'docserver_id',
                'docserver_type_id',
                'coll_id',
                'docserver_location_id',
            )
        );
        $this->set_specific_id('docserver_id');
        if ($mode == 'up') {
            $control = $this->control($docserver, 'up');
            if ($control['status'] == 'ok') {
                //Update existing docserver
                if ($this->update($docserver)) {
                    $this->createPackageInformation($docserver);
                    $control = array(
                        'status' => 'ok',
                        'value' => $docserver->docserver_id,
                    );
                    //history
                    if ($_SESSION['history']['docserversadd'] == 'true') {
                        $history = new history();
                        $history->add(
                            _DOCSERVERS_TABLE_NAME,
                            $docserver->docserver_id,
                            'UP','docserversadd',
                            _DOCSERVER_UPDATED . ' : '
                            . $docserver->docserver_id,
                            $_SESSION['config']['databasetype']
                        );
                    }
                } else {
                    $control = array(
                        'status' => 'ko',
                        'value' => '',
                        'error' => _PB_WITH_DOCSERVER,
                    );
                }
                return $control;
            }
        } else {
            $control = $this->control($docserver, 'add');
            if ($control['status'] == 'ok') {
                //Insert new docserver
                if ($this->insert($docserver)) {
                    $this->createPackageInformation($docserver);
                    $control = array(
                        'status' => 'ok',
                        'value' => $docserver->docserver_id,
                    );
                    //history
                    if ($_SESSION['history']['docserversadd'] == 'true') {
                        $history = new history();
                        $history->add(
                            _DOCSERVERS_TABLE_NAME,
                            $docserver->docserver_id,
                            'ADD','docserversadd',
                            _DOCSERVER_ADDED . ' : ' . $docserver->docserver_id,
                            $_SESSION['config']['databasetype']
                        );
                    }
                } else {
                    $control = array(
                        'status' => 'ko',
                        'value' => '',
                        'error' => _PB_WITH_DOCSERVER,
                    );
                }
            }
        }
        return $control;
    }

    /**
    * control the docserver object before action
    *
    * @param  $docserver docserver object
    * @return array ok if the object is well formated, ko otherwise
    */
    private function control($docserver, $mode)
    {
        $f = new functions();
        $error = '';
        if ($mode == 'add') {
            // Update, so values exist
            if (isset($docserver->docserver_id)
                && $docserver->docserver_id <> ''
            ) {
                $docserver->docserver_id = 
                    $f->wash(
                        $docserver->docserver_id,
                        'nick',
                        _DOCSERVER_ID . ' ',
                        'yes', 0, 32
                    );
            } else {
                $error .= _DOCSERVER_ID . ' ' . _IS_EMPTY . '#';
            }
        }
        $docserver->docserver_type_id = 
            $f->wash(
                $docserver->docserver_type_id,
                'no',
                _DOCSERVER_TYPES . ' ',
                'yes',
                0,
                32
            );
        $docserver->device_label = 
            $f->wash(
                $docserver->device_label,
                'no',
                _DEVICE_LABEL . ' ',
                'yes',
                0,
                255
            );
        if ($docserver->is_readonly == '') {
            $docserver->is_readonly = 'false';
        }
        $docserver->is_readonly = 
            $f->wash(
                $docserver->is_readonly,
                'no',
                _IS_READONLY . ' ',
                'yes',
                0,
                5
            );
        if ($docserver->is_readonly == 'false') {
            $docserver->is_readonly = false;
        } else {
            $docserver->is_readonly = true;
        }
        if (isset($docserver->size_limit_number)
            && !empty($docserver->size_limit_number)
        ) {
            $docserver->size_limit_number = 
                $f->wash(
                    $docserver->size_limit_number,
                    'no',
                    _SIZE_LIMIT . ' ',
                    'yes',
                    0,
                    255
                );
            if ($docserver->size_limit_number == 0) {
                $error .= _SIZE_LIMIT . ' ' . _IS_EMPTY . '#';
            }
            if ($this->sizeLimitControl($docserver)) {
                $error .= _SIZE_LIMIT_UNAPPROACHABLE . '#';
            }
            if ($this->actualSizeNumberControl($docserver)) {
                $error .= _SIZE_LIMIT_LESS_THAN_ACTUAL_SIZE . '#';
            }
        } else {
            $error .= _SIZE_LIMIT . ' ' . _IS_EMPTY . '#';
        }
        $docserver->path_template = 
            $f->wash(
                $docserver->path_template,
                'no',
                _PATH_TEMPLATE . ' ',
                'yes',
                0,
                255
            );
        if (!is_dir($docserver->path_template)) {
            $error .= _PATH_OF_DOCSERVER_UNAPPROACHABLE . '#';
        } else {
            // $Fnm = $docserver->path_template . 'test_docserver.txt';
            if (!is_writable($docserver->path_template)
                || !is_readable($docserver->path_template)
            ) {
                $error .= _THE_DOCSERVER_DOES_NOT_HAVE_THE_ADEQUATE_RIGHTS;
            }
        }
        $docserver->coll_id = 
            $f->wash(
                $docserver->coll_id,
                'no',
                _COLLECTION . ' ',
                'yes',
                0,
                32
            );
        $docserver->priority_number = 
            $f->wash(
                $docserver->priority_number,
                'num',
                _PRIORITY . ' ',
                'yes',
                0,
                6
            );
        $docserver->docserver_location_id = 
            $f->wash(
                $docserver->docserver_location_id,
                'no',
                _DOCSERVER_LOCATIONS . ' ',
                'yes',
                0,
                32
            );
        $docserver->adr_priority_number = 
            $f->wash(
                $docserver->adr_priority_number,
                'num',
                _ADR_PRIORITY . ' ',
                'yes',
                0,
                6
            );
        if ($mode == 'add'
            && $this->docserversExists($docserver->docserver_id)
        ) {
            $error .= $docserver->docserver_id . ' ' . _ALREADY_EXISTS . '#';
        }
        if (!$this->adrPriorityNumberControl($docserver)) {
            $error .= _PRIORITY . ' ' . $docserver->adr_priority_number . ' '
                    . _ALREADY_EXISTS_FOR_THIS_TYPE_OF_DOCSERVER . '#';
        }
        if (!$this->priorityNumberControl($docserver)) {
            $error .= _ADR_PRIORITY . $docserver->priority_number . '  '
                    . _ALREADY_EXISTS_FOR_THIS_TYPE_OF_DOCSERVER . '#';
        }
        $error .= $_SESSION['error'];
        //TODO:rewrite wash to return errors without html
        $error = str_replace('<br />', '#', $error);
        $return = array();
        if (!empty($error)) {
                $return = array(
                    'status' => 'ko',
                    'value' => $docserver->docserver_id,
                    'error' => $error,
                );
        } else {
            $return = array(
                'status' => 'ok',
                'value' => $docserver->docserver_id,
            );
        }
        return $return;
    }

    /**
    * method to create package information file on the root of the docserver
    *
    * @param  $docserver docserver object
    */
    private function createPackageInformation($docserver)
    {
        if (is_writable($docserver->path_template)
            && is_readable($docserver->path_template)
        ) {
            require_once('core' . DIRECTORY_SEPARATOR . 'class'
                . DIRECTORY_SEPARATOR . 'docserver_types_controler.php');
            $docserverTypeControler = new docserver_types_controler();
            $docserverTypeObject = $docserverTypeControler->get(
                $docserver->docserver_type_id
            );
            $Fnm = $docserver->path_template . DIRECTORY_SEPARATOR
                 . 'package_information';
            if (file_exists($Fnm)) {
                unlink($Fnm);
            }
            $inF = fopen($Fnm, 'a');
            fwrite(
                $inF,
                _DOCSERVER_TYPE_ID . ' : '
                . $docserverTypeObject->docserver_type_id . '\r\n'
            );
            fwrite(
                $inF,
                _DOCSERVER_TYPE_LABEL . ' : '
                . $docserverTypeObject->docserver_type_label . '\r\n'
            );
            fwrite(
                $inF,
                _IS_CONTAINER . ' : ' . $docserverTypeObject->is_container
                . '\r\n'
            );
            fwrite(
                $inF,
                _CONTAINER_MAX_NUMBER . ' : '
                . $docserverTypeObject->container_max_number . '\r\n'
            );
            fwrite(
                $inF,
                _IS_COMPRESSED . ' : ' . $docserverTypeObject->is_compressed
                . '\r\n'
            );
            fwrite(
                $inF,
                _COMPRESS_MODE . ' : '
                . $docserverTypeObject->compression_mode . '\r\n'
            );
            fwrite(
                $inF,
                _IS_META . ' : ' . $docserverTypeObject->is_meta . '\r\n'
            );
            fwrite(
                $inF,
                _META_TEMPLATE . ' : ' . $docserverTypeObject->meta_template
                . '\r\n'
            );
            fwrite(
                $inF,
                _IS_LOGGED . ' : ' . $docserverTypeObject->is_logged . '\r\n'
            );
            fwrite(
                $inF,
                _LOG_TEMPLATE . ' : ' . $docserverTypeObject->log_template
                . '\r\n'
            );
            fwrite(
                $inF,
                _IS_SIGNED . ' : ' . $docserverTypeObject->is_signed . '\r\n'
            );
            fwrite(
                $inF,
                _FINGERPRINT_MODE . ' : '
                . $docserverTypeObject->fingerprint_mode . '\r\n'
            );
            fclose($inF);
        }
    }

    /**
    * Inserts in the database (docservers table) a docserver object
    *
    * @param  $docserver docserver object
    * @return bool true if the insertion is complete, false otherwise
    */
    private function insert($docserver)
    {
        $db = new Database();
        //Giving automatised values
        $docserver->enabled = 'Y';
        $docserver->creation_date = $db->current_datetime();
        //Inserting object
        $result = $this->advanced_insert($docserver);
        return $result;
    }

    /**
    * Updates in the database (docserver table) a docservers object
    *
    * @param  $docserver docserver object
    * @return bool true if the update is complete, false otherwise
    */
    private function update($docserver)
    {
        return $this->advanced_update($docserver);
    }

    /**
     * Get docservers with given id.
     * Can return null if no corresponding object.
     * @param $id Id of docservers to get
     * @return docservers
     */
    public function get($docserver_id)
    {
        //var_dump($docserver_id);
        $this->set_foolish_ids(array('docserver_id'));
        $this->set_specific_id('docserver_id');
        $docserver = $this->advanced_get($docserver_id, _DOCSERVERS_TABLE_NAME);
        //var_dump($docserver);
        if (get_class($docserver) <> 'docservers') {
            return null;
        } else {
            //var_dump($docserver);
            return $docserver;
        }
    }

    /**
     * get docservers with given id for a ws.
     * Can return null if no corresponding object.
     * @param $docserver_id of docservers to send
     * @return docservers
     */
    public function getWs($docserver_id)
    {
        $this->set_foolish_ids(array('docserver_id'));
        $this->set_specific_id('docserver_id');
        $docserver = $this->advanced_get($docserver_id, _DOCSERVERS_TABLE_NAME);
        if (get_class($docserver) <> 'docservers') {
            return null;
        } else {
            $docserver = $docserver->getArray();
            return $docserver;
        }
    }

    /**
     * Delete given docserver from database.
     * @param docservers $docservers
     */
    public function delete($docserver)
    {
        if ($docserver->docserver_id <> 'TEMPLATES') {
            $func = new functions();
            $control = array();
            if (!isset($docserver) || empty($docserver)) {
                $control = array(
                    'status' => 'ko',
                    'value' => '',
                    'error' => _DOCSERVER_EMPTY,
                );
                return $control;
            }
            $docserver = $this->isADocserver($docserver);
            if (!$this->docserversExists($docserver->docserver_id)) {
                $control = array(
                    'status' => 'ko',
                    'value' => '',
                    'error' => _DOCSERVER_NOT_EXISTS,
                );
                return $control;
            }
            if ($this->adrxLinkExists(
                $docserver->docserver_id,
                $docserver->coll_id
                )
            ) {
                $control = array('status' => 'ko', 'value' => '',
                'error' => _DOCSERVER_ATTACHED_TO_ADR_X);
                return $control;
            }
            if ($this->resxLinkExists(
                $docserver->docserver_id,
                $docserver->coll_id
            )
            ) {
                $control = array(
                    'status' => 'ko',
                    'value' => '',
                    'error' => _DOCSERVER_ATTACHED_TO_RES_X,
                );
                return $control;
            }
            $db = new Database();
            $query = "delete from " . _DOCSERVERS_TABLE_NAME
                   . " where docserver_id = ?";
            try {
                $stmt = $db->query($query, array($docserver->docserver_id));
            } catch (Exception $e) {
                $control = array(
                    'status' => 'ko',
                    'value' => '',
                    'error' => _CANNOT_DELETE_DOCSERVER_ID
                    . ' ' . $docserver->docserver_id,
                );
            }
            $control = array(
                'status' => 'ok',
                'value' => $docserver->docserver_id,
            );
            if ($_SESSION['history']['docserversdel'] == 'true') {
                $history = new history();
                $history->add(
                    _DOCSERVERS_TABLE_NAME,
                    $docserver->docserver_id,
                    'DEL','docserversdel',
                    _DOCSERVER_DELETED . ' : ' . $docserver->docserver_id,
                    $_SESSION['config']['databasetype']
                );
            }
        } else {
            $control = array(
                'status' => 'ko',
                'value' => '',
                'error' => _CANNOT_DELETE_DOCSERVER_ID
                . ' ' . $docserver->docserver_id,
            );
        }
        return $control;
    }

    /**
    * Disables a given docservers
    *
    * @param  $docserver docservers object
    * @return bool true if the disabling is complete, false otherwise
    */
    public function disable($docserver)
    {
        if ($docserver->docserver_id <> 'TEMPLATES') {
            $control = array();
            if (!isset($docserver) || empty($docserver)) {
                $control = array(
                    'status' => 'ko',
                    'value' => '',
                    'error' => _DOCSERVER_EMPTY,
                );
                return $control;
            }
            $docserver = $this->isADocserver($docserver);
            $this->set_foolish_ids(array('docserver_id'));
            $this->set_specific_id('docserver_id');
            if ($this->advanced_disable($docserver)) {
                $control = array(
                    'status' => 'ok',
                    'value' => $docserver->docserver_id,
                );
                if ($_SESSION['history']['docserversban'] == 'true') {
                    $history = new history();
                    $history->add(
                        _DOCSERVERS_TABLE_NAME,
                        $docserver->docserver_id,
                        'BAN','docserversban',
                        _DOCSERVER_DISABLED . ' : ' . $docserver->docserver_id,
                        $_SESSION['config']['databasetype']
                    );
                }
            } else {
                $control = array(
                    'status' => 'ko',
                    'value' => '',
                    'error' => _PB_WITH_DOCSERVER,
                );
            }
        } else {
            $control = array(
                'status' => 'ko',
                'value' => '',
                'error' => _CANNOT_SUSPEND_DOCSERVER . ' ' . $docserver->docserver_id,
            );
        }
        return $control;
    }

    /**
    * Enables a given docserver
    *
    * @param  $docserver docservers object
    * @return bool true if the enabling is complete, false otherwise
    */
    public function enable($docserver)
    {
        $control = array();
        if (!isset($docserver) || empty($docserver)) {
            $control = array(
                'status' => 'ko',
                'value' => '',
                'error' => _DOCSERVER_EMPTY,
            );
            return $control;
        }
        $docserver = $this->isADocserver($docserver);
        $this->set_foolish_ids(array('docserver_id'));
        $this->set_specific_id('docserver_id');
        if ($this->advanced_enable($docserver)) {
            $control = array(
                'status' => 'ok',
                'value' => $docserver->docserver_id,
            );
            if ($_SESSION['history']['docserversallow'] == 'true') {
                $history = new history();
                $history->add(
                    _DOCSERVERS_TABLE_NAME,
                    $docserver->docserver_id,
                    'VAL','docserversallow',
                    _DOCSERVER_ENABLED . ' : ' . $docserver->docserver_id,
                    $_SESSION['config']['databasetype']
                );
            }
        } else {
            $control = array(
                'status' => 'ko',
                'value' => '',
                'error' => _PB_WITH_DOCSERVER,
            );
        }
        return $control;
    }

    /**
    * Fill a docserver object with an object if it's not a docserver
    *
    * @param  $object ws docserver object
    * @return object docservers
    */
    private function isADocserver($object)
    {
        if (get_class($object) <> 'docservers') {
            $func = new functions();
            $docserverObject = new docservers();
            $array = array();
            $array = $func->object2array($object);
            foreach (array_keys($array) as $key) {
                $docserverObject->{$key} = $array[$key];
            }
            return $docserverObject;
        } else {
            return $object;
        }
    }

    /**
    * Test if a given docserver exists
    *
    * @param  $docserver docservers object
    * @return bool true if exists, false otherwise
    */
    public function docserversExists($docserver_id)
    {
        if (!isset($docserver_id) || empty($docserver_id)) {
            return false;
        }
        $db = new Database();
        $query = "select docserver_id from " . _DOCSERVERS_TABLE_NAME
               . " where docserver_id = ?";
        try{
            $stmt = $db->query($query, array($docserver_id));
        } catch (Exception $e) {
            echo _UNKNOWN . _DOCSERVER . ' ' . functions::xssafe($docserver_id) . ' // ';
        }
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    /**
    *Check if the docserver is linked to a ressource
    *@param docserver_id docservers
    *@return bool true if it's linked
    */
    public function resxLinkExists($docserver_id, $coll_id)
    {
        if ($coll_id == 'templates') {
            return false;
        }
        $security = new security();
        $db = new Database();
        $tableName = $security->retrieve_table_from_coll($coll_id);
        if (!isset($tableName) || empty($tableName)) {
            return false;
        }
        $query = "select docserver_id from " . $tableName
               . " where docserver_id = ?";
        $stmt = $db->query($query, array($docserver_id));
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    /**
    *Check if the docserver is linked to a ressource address
    *@param docserver_id docservers
    *@return bool true if it's linked
    */
    public function adrxLinkExists($docserver_id, $coll_id)
    {
        $security = new security();
        $db = new Database();
        $adrName = $security->retrieveAdrFromColl($coll_id);
        if (!isset($adrName) || empty($adrName)) {
            return false;
        }
        $query = "select docserver_id from " . $adrName
               . " where docserver_id = ?";
        $stmt = $db->query($query, array($docserver_id));
        if ($stmt->rowCount() > 0) {
            return true;
        }
    }

    /**
    * Check if two docservers have the same priorities
    *
    * @param $docserver docservers object
    * @return bool true if the control is ok
    */
    private function adrPriorityNumberControl($docserver)
    {
        $func = new functions();
        if (!isset($docserver)
            || empty($docserver)
            || empty($docserver->adr_priority_number)
        ) {
            return false;
        }
        $db = new Database();
        $query = "select adr_priority_number from " . _DOCSERVERS_TABLE_NAME
               . " where adr_priority_number = ? AND docserver_type_id = ?"
               . " AND docserver_id <> ?";
        $stmt = $db->query(
            $query, 
            array(
                $docserver->adr_priority_number, 
                $docserver->docserver_type_id, 
                $docserver->docserver_id 
            )
        );
        if ($stmt->rowCount() > 0) {
            return false;
        }
        return true;
    }

    /**
    * Check if two docservers have the same priorities
    *
    * @param $docserver docservers object
    * @return bool true if the control is ok
    */
    private function priorityNumberControl($docserver)
    {
        $func = new functions();
        if (!isset($docserver)
            || empty($docserver)
            || empty($docserver->priority_number)
        ) {
            return false;
        }
        $db = new Database();
        $query = "select priority_number from " . _DOCSERVERS_TABLE_NAME
               . " where priority_number = ? AND docserver_type_id = ?"
               . " AND docserver_id <> ?";
        $stmt = $db->query(
            $query, 
            array(
                $docserver->priority_number, 
                $docserver->docserver_type_id, 
                $docserver->docserver_id 
            )
        );
        if ($stmt->rowCount() > 0) {
            return false;
        }
        return true;
    }

    /**
    * Check if the docserver actual size is less than the size limit
    *
    * @param $docserver docservers object
    * @return bool true if the control is ok
    */
    public function actualSizeNumberControl($docserver)
    {
        if (!isset($docserver) || empty($docserver)) {
            return false;
        }
        $size_limit_number = floatval($docserver->size_limit_number);
        $size_limit_number = $size_limit_number * 1000 * 1000 * 1000;
        $db = new Database();
        $query = "select actual_size_number from "  . _DOCSERVERS_TABLE_NAME
               . " where docserver_id = ?";
        $stmt = $db->query($query, array($docserver->docserver_id));
        $queryResult = $stmt->fetchObject();
        if (isset($queryResult->actual_size_number)) {
            $actual_size_number = floatval($queryResult->actual_size_number);
        } else {
            $actual_size_number = 0;
        }
        if ($size_limit_number < $actual_size_number) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Check if the docserver size has not reached the limit
    *
    * @param $docserver docservers object
    * @return bool true if the control is ok
    */
    private function sizeLimitControl($docserver)
    {
        $docserver->size_limit_number = floatval($docserver->size_limit_number);
        $maxsizelimit = floatval(
            $_SESSION['docserversFeatures']['DOCSERVERS']['MAX_SIZE_LIMIT']
        ) * 1000 * 1000 * 1000;
        if (!isset($docserver) || empty($docserver)) {
            return false;
        }
        if ($docserver->size_limit_number < $maxsizelimit) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get docservers to insert a new doc.
     * Can return null if no corresponding object.
     * @param  $coll_id  string Collection identifier
     * @return docservers
     */
    public function getDocserverToInsert($collId)
    {
        $db = new Database();
        $query = "select priority_number, docserver_id from "
               . _DOCSERVERS_TABLE_NAME . " where is_readonly = 'N' and "
               . " enabled = 'Y' and coll_id = ? order by priority_number";
        $stmt = $db->query($query, array($collId));
        $queryResult = $stmt->fetchObject();
        if ($queryResult->docserver_id <> '') {
            $docserver = $this->get($queryResult->docserver_id);
            if (isset($docserver->docserver_id)) {
                return $docserver;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * Store a new doc in a docserver.
     * @param   $collId collection resource
     * @param   $fileInfos infos of the doc to store, contains :
     *          tmpDir : path to tmp directory
     *          size : size of the doc
     *          format : format of the doc
     *          tmpFileName : file name of the doc in Maarch tmp directory
     * @return  array of docserver data for res_x else return error
     */
    public function storeResourceOnDocserver($collId, $fileInfos)
    {
        $docserver = $this->getDocserverToInsert($collId);
        $tmpSourceCopy = '';
        $func = new functions();
        if (empty($docserver)) {
            $storeInfos = array(
                'error' => _DOCSERVER_ERROR . ' : '
                . _NO_AVAILABLE_DOCSERVER . ' .  ' . _MORE_INFOS . '.',
            );
            return $storeInfos;
        }
        $newSize = $this->checkSize($docserver, $fileInfos['size']);
        if ($newSize == 0) {
            $storeInfos = array(
                'error' => _DOCSERVER_ERROR . ' : '
                . _NOT_ENOUGH_DISK_SPACE . ' .  ' . _MORE_INFOS . '.',
            );
            return $storeInfos;
        }
        if ($fileInfos['tmpDir'] == '') {
            $tmp = $_SESSION['config']['tmppath'];
        } else {
            $tmp = $fileInfos['tmpDir'];
        }
        $d = dir($tmp);
        $pathTmp = $d->path;
        while ($entry = $d->read()) {
            if ($entry == $fileInfos['tmpFileName']) {
                $tmpSourceCopy = $pathTmp . $entry;
                $theFile = $entry;
                break;
            }
        }
        $d->close();
        $pathOnDocserver = array();
        $pathOnDocserver = Ds_createPathOnDocServer(
            $docserver->path_template
        );
        $docinfo = $this->getNextFileNameInDocserver(
            $pathOnDocserver['destinationDir']
        );
        if ($docinfo['error'] <> '') {
             $_SESSION['error'] = _FILE_SEND_ERROR . '. '._TRY_AGAIN . '. '
                                . _MORE_INFOS . ' : <a href=\'mailto:'
                                . $_SESSION['config']['adminmail'] . '\'>'
                                . $_SESSION['config']['adminname'] . '</a>';
        }
        require_once('core' . DIRECTORY_SEPARATOR . 'class'
            . DIRECTORY_SEPARATOR . 'docserver_types_controler.php');
        $docserverTypeControler = new docserver_types_controler();
        $docserverTypeObject = $docserverTypeControler->get(
            $docserver->docserver_type_id
        );
        $docinfo['fileDestinationName'] .= '.'
            . strtolower($func->extractFileExt($tmpSourceCopy));
        $copyResultArray = Ds_copyOnDocserver(
            $tmpSourceCopy,
            $docinfo,
            $docserverTypeObject->fingerprint_mode
        );

        if (isset($copyResultArray['error']) && $copyResultArray['error'] <> '') {
            //second chance
            $docinfo = array();
            $copyResultArray = array();
            $docinfo = $this->getNextFileNameInDocserver(
                $pathOnDocserver['destinationDir']
            );
            if ($docinfo['error'] <> '') {
                 $_SESSION['error'] = _FILE_SEND_ERROR . '. '._TRY_AGAIN . '. '
                                    . _MORE_INFOS . ' : <a href=\'mailto:'
                                    . $_SESSION['config']['adminmail'] . '\'>'
                                    . $_SESSION['config']['adminname'] . '</a>';
            }
            $docinfo['fileDestinationName'] .= '.'
                . strtolower($func->extractFileExt($tmpSourceCopy));
            $copyResultArray = Ds_copyOnDocserver(
                $tmpSourceCopy,
                $docinfo,
                $docserverTypeObject->fingerprint_mode
            );
            if (isset($copyResultArray['error']) && $copyResultArray['error'] <> '') {
                $storeInfos = array('error' => $copyResultArray['error']);
                return $storeInfos;
            }
        }
        $destinationDir = $copyResultArray['destinationDir'];
        $fileDestinationName = $copyResultArray['fileDestinationName'];
        $destinationDir = substr(
            $destinationDir,
            strlen($docserver->path_template)
        ) . DIRECTORY_SEPARATOR;
        $destinationDir = str_replace(
            DIRECTORY_SEPARATOR,
            '#',
            $destinationDir
        );
        $this->setSize($docserver, $newSize);
        $storeInfos = array(
            'path_template' => $docserver->path_template,
            'destination_dir' => $destinationDir,
            'docserver_id' => $docserver->docserver_id,
            'file_destination_name' => $fileDestinationName,
        );
        return $storeInfos;
    }

    /**
    * Checks the size of the docserver plus a new file to see
    * if there is enough disk space
    *
    * @param  $docserver docservers object
    * @param  $filesize integer File size
    * @return integer New docserver size or 0 if not enough disk space available
    */
    public function checkSize($docserver, $filesize)
    {
        $new_docserver_size = $docserver->actual_size_number + $filesize;
        if ($docserver->size_limit_number > 0
            && $new_docserver_size >= $docserver->size_limit_number
        ) {
            return 0;
        } else {
            return $new_docserver_size;
        }
    }

    /**
    * Calculates the next file name in the docserver
    * @param $pathOnDocserver docservers path
    * @return array Contains 3 items :
    * subdirectory path and new filename and error
    */
    public function getNextFileNameInDocserver($pathOnDocserver)
    {
        umask(0022);
        //Scans the docserver path
        $fileTab = scandir($pathOnDocserver);
        //Removes . and .. lines
        array_shift($fileTab);
        array_shift($fileTab);

        if (file_exists(
            $pathOnDocserver . DIRECTORY_SEPARATOR . 'package_information'
        )
        ) {
            unset($fileTab[array_search('package_information', $fileTab)]);
        }
        if (is_dir($pathOnDocserver . DIRECTORY_SEPARATOR . 'BATCH')) {
            unset($fileTab[array_search('BATCH', $fileTab)]);
        }
        $nbFiles = count($fileTab);
        //Docserver is empty
        if ($nbFiles == 0 ) {
            //Creates the directory
            if (!mkdir($pathOnDocserver . '0001', 0770)) {
                return array(
                    'destinationDir' => '',
                    'fileDestinationName' => '',
                    'error' => 'Pb to create directory on the docserver:'
                    . $pathOnDocserver,
                );
            } else {
                Ds_setRights($pathOnDocserver . '0001' . DIRECTORY_SEPARATOR);
                $destinationDir = $pathOnDocserver . '0001'
                                . DIRECTORY_SEPARATOR;
                $fileDestinationName = '0001';
                return array(
                    'destinationDir' => $destinationDir,
                    'fileDestinationName' => $fileDestinationName,
                    'error' => '',
                );
            }
        } else {
            //Gets next usable subdirectory in the docserver
            $destinationDir = $pathOnDocserver
                . str_pad(
                    count($fileTab),
                    4,
                    '0',
                    STR_PAD_LEFT
                )
                . DIRECTORY_SEPARATOR;
            $fileTabBis = scandir(
                $pathOnDocserver
                . strval(str_pad(count($fileTab), 4, '0', STR_PAD_LEFT))
            );
            //Removes . and .. lines
            array_shift($fileTabBis);
            array_shift($fileTabBis);
            $nbFilesBis = count($fileTabBis);
            //If number of files => 1000 then creates a new subdirectory
            if ($nbFilesBis >= 1000 ) {
                $newDir = ($nbFiles) + 1;
                if (!mkdir(
                    $pathOnDocserver
                    . str_pad($newDir, 4, '0', STR_PAD_LEFT), 0770
                )
                ) {
                    return array(
                        'destinationDir' => '',
                        'fileDestinationName' => '',
                        'error' => 'Pb to create directory on the docserver:'
                        . $pathOnDocserver
                        . str_pad($newDir, 4, '0', STR_PAD_LEFT),
                    );
                } else {
                    Ds_setRights(
                        $pathOnDocserver
                        . str_pad($newDir, 4, '0', STR_PAD_LEFT)
                        . DIRECTORY_SEPARATOR
                    );
                    $destinationDir = $pathOnDocserver
                        . str_pad($newDir, 4, '0', STR_PAD_LEFT)
                        . DIRECTORY_SEPARATOR;
                    $fileDestinationName = '0001';
                    return array(
                        'destinationDir' => $destinationDir,
                        'fileDestinationName' => $fileDestinationName,
                        'error' => '',
                    );
                }
            } else {
                //Docserver contains less than 1000 files
                $newFileName = $nbFilesBis + 1;
                $greater = $newFileName;
                for ($n = 0;$n < count($fileTabBis);$n++) {
                    $currentFileName = array();
                    $currentFileName = explode('.', $fileTabBis[$n]);
                    if ((int) $greater <= (int) $currentFileName[0]) {
                        if ((int) $greater == (int) $currentFileName[0]) {
                            $greater ++;
                        } else {
                            //$greater < current
                            $greater = (int) $currentFileName[0] + 1;
                        }
                    }
                }
                $fileDestinationName = str_pad($greater, 4, '0', STR_PAD_LEFT);
                return array(
                    'destinationDir' => $destinationDir,
                    'fileDestinationName' => $fileDestinationName,
                    'error' => '',
                );
            }
        }
    }

    /**
    * Sets the size of the docserver
    * @param $docserver docservers object
    * @param $newSize integer New size of the docserver
    */
    public function setSize($docserver, $newSize)
    {
        $db = new Database();
        $stmt = $db->query(
            "update " . _DOCSERVERS_TABLE_NAME
            . " set actual_size_number = ? where docserver_id = ?",
            array(
                $newSize,
                $docserver->docserver_id
            )
        );
        
        return $newSize;
    }

    /**
     * Get the network link of a resource on a docserver
     * @param   bigint $gedId id of th resource
     * @param   string $tableName name of the res table
     * @param   string $adrTable name of the res address table
     * @return  array of net address to the docserver
     */
    public function retrieveDocserverNetLinkOfResource(
        $gedId,
        $tableName,
        $adrTable
    ) {
        $adr = array();
        $resource = new resource();
        $whereClause = ' and 1=1';
        $adr = $resource->getResourceAdr(
            $tableName,
            $gedId,
            $whereClause,
            $adrTable
        );
        if ($adr['status'] == 'ko') {
            $result = array(
                'status' => 'ko',
                'value' => '',
                'error' => _RESOURCE_NOT_EXISTS,
            );
        } else {
            //TODO : MANAGEMENT OF GEOLOCALISATION FAILOVER
            //$resource->show_array($adr);
            $docserver = $adr[0][0]['docserver_id'];
            //retrieve infos of the docserver
            $docserverObject = $this->get($docserver);
            //retrieve infos of the docserver type
            require_once('core' . DIRECTORY_SEPARATOR . 'class'
                . DIRECTORY_SEPARATOR . 'docserver_locations_controler.php');
            $docserverLocationControler = new docserver_locations_controler();
            $docserverLocationObject =
                $docserverLocationControler->get(
                    $docserverObject->docserver_location_id
                );
            $result = array(
                'status' => 'ok',
                'value' => $docserverLocationObject->net_link,
                'error' => '',
            );
        }
        return $result;
    }

    /**
     * 
     * Get a resources at a specific address in adr table or res table
     * @param array $adr
     */
    public function viewResourceAdr($adr) {
        //retrieve infos of the docserver type
        require_once('core' . DIRECTORY_SEPARATOR . 'class'
        . DIRECTORY_SEPARATOR . 'docserver_types_controler.php');
        $history = new history();
        $coreTools = new core_tools();
        $fingerprintFromDb = $adr['fingerprint'];
        //$format = $adr[0][$cptDocserver]['format'];
        $docserverObject = $this->get($adr['docserver_id']);
        $docserver = $docserverObject->path_template;
        $file = $docserver . $adr['path']
          . $adr['filename'];
        $file = str_replace('#', DIRECTORY_SEPARATOR, $file);
        $docserverTypeControler = new docserver_types_controler();
        $docserverTypeObject = $docserverTypeControler->get(
            $docserverObject->docserver_type_id
        );
        if (!file_exists($file)) {
            $concatError .= _FILE_NOT_EXISTS_ON_THE_SERVER . ' : '
                          . $file . '||';
            $history->add(
                $tableName, $gedId, 'ERR', 'docserverserr',
                _FAILOVER . ' ' . _DOCSERVERS . ' '
                . $adr['docserver_id'] . ':'
                . _FILE_NOT_EXISTS_ON_THE_SERVER . ' : '
                . $file, $_SESSION['config']['databasetype']
            );
        } else {
            $fingerprintFromDocserver = Ds_doFingerprint(
                $file, $docserverTypeObject->fingerprint_mode
            );
            $adrToExtract = array();
            $adrToExtract = $adr;
            $adrToExtract['path_to_file'] = $file;
            $docserverTypeControler = new docserver_types_controler();
            $docserverTypeObject = $docserverTypeControler->get(
                $docserverObject->docserver_type_id
            );

            if ($docserverTypeObject->is_container == 'Y'
                && $adr['offset_doc'] == ''
            ) {
                $error = true;
                $concatError .=
                    _PB_WITH_OFFSET_OF_THE_DOC_IN_THE_CONTAINER . '||';
                $history->add(
                    $tableName, $gedId, 'ERR', 'docserverserr',
                    _FAILOVER . ' ' . _DOCSERVERS . ' '
                    . $adr['docserver_id'] . ':'
                    . _PB_WITH_OFFSET_OF_THE_DOC_IN_THE_CONTAINER,
                    $_SESSION['config']['databasetype']
                );
            }
            //manage compressed resource
            if ($docserverTypeObject->is_compressed == 'Y') {
                $extract = array();
                $extract = Ds_extractArchive(
                    $adrToExtract,
                    $docserverTypeObject->fingerprint_mode
                );
                if ($extract['status'] == 'ko' || !is_array($extract)) {
                    $error = true;
                    $concatError .= $extract['error'] . '||';
                    $history->add(
                        $tableName, $gedId, 'ERR', 'docserverserr',
                        _FAILOVER . ' ' . _DOCSERVERS . ' '
                        . $adr['docserver_id'] . ':'
                        . $extract['error'],
                        $_SESSION['config']['databasetype']
                    );
                } else {
                    $file = $extract['path'];
                    $mimeType = $extract['mime_type'];
                    $format = $extract['format'];
                    //to control fingerprint of the offset
                    $fingerprintFromDocserver = $extract['fingerprint'];
                }
            } else {
                $mimeType = Ds_getMimeType(
                    $adrToExtract['path_to_file']
                );
                $format = substr(
                    $adrToExtract['filename'],
                    strrpos($adrToExtract['filename'], '.') + 1
                );
            }
            //manage view of the file
            $use_tiny_mce = false;
            if (strtolower($format) == 'maarch'
                && $coreTools->is_module_loaded('templates')
            ) {
                $mode = 'content';
                $type_state = true;
                $use_tiny_mce = true;
                $mimeType = 'application/maarch';
            } else {
                require_once 'core/docservers_tools.php';
                $arrayIsAllowed = array();
                $arrayIsAllowed = Ds_isFileTypeAllowed($file);
                $type_state = $arrayIsAllowed['status'];
            }
            //if fingerprint from db = 0 we do not control fingerprint
            if ($fingerprintFromDb == '0'
                || ($fingerprintFromDb == $fingerprintFromDocserver)
            ) {
                if ($type_state <> false) {
                    if ($_SESSION['history']['resview'] == 'true') {
                        require_once(
                            'core' . DIRECTORY_SEPARATOR
                            . 'class' . DIRECTORY_SEPARATOR
                            . 'class_history.php'
                        );
                        $history->add(
                            $tableName, $gedId, 'VIEW', 'resview',
                            _VIEW_DOC_NUM . $gedId,
                            $_SESSION['config']['databasetype'],
                            'indexing_searching'
                        );
                    }
                    //count number of viewed in listinstance for
                    //the user
                    if ($coreTools->is_module_loaded('entities')
                        && $coreTools->is_module_loaded('basket')
                    ) {
                        require_once(
                            'modules' . DIRECTORY_SEPARATOR
                            . 'entities' . DIRECTORY_SEPARATOR
                            . 'class' . DIRECTORY_SEPARATOR
                            . 'class_manage_entities.php'
                        );
                        $ent = new entity();
                        $ent->increaseListinstanceViewed($gedId);
                    }
                    $encodedContent = '';
                    if (file_exists($file) && !$error) {
                        if ($calledByWS) {
                            $content = '';
                            $handle = fopen($file, 'r');
                            if ($handle) {
                                while (!feof($handle)) {
                                    $content .= fgets($handle, 4096);
                                }
                                fclose($handle);
                            }
                            $encodedContent = base64_encode($content);
                        } else {
                            $fileNameOnTmp = 'tmp_file_' . rand()
                                . '.' . strtolower($format);
                            $filePathOnTmp = $_SESSION['config']
                                ['tmppath'] . DIRECTORY_SEPARATOR
                                . $fileNameOnTmp;
                            copy($file, $filePathOnTmp);
                        }
                        $result = array(
                            'status' => 'ok',
                            'mime_type' => $mimeType,
                            'ext' => $format,
                            'file_content' => $encodedContent,
                            'tmp_path' => $_SESSION['config']
                            ['tmppath'],
                            'file_path' => $filePathOnTmp,
                            'called_by_ws' => $calledByWS,
                            'error' => '',
                        );
                        if (isset($extract)
                            && file_exists($extract['tmpArchive'])
                        ) {
                            Ds_washTmp($extract['tmpArchive']);
                        }
                        return $result;
                    } else {
                        $concatError .= _FILE_NOT_EXISTS . '||';
                        $history->add(
                            $tableName, $gedId, 'ERR', 'docserverserr',
                            _FAILOVER . ' ' . _DOCSERVERS . ' '
                            . $adr['docserver_id']
                            . ':' . _FILE_NOT_EXISTS,
                            $_SESSION['config']['databasetype']
                        );
                    }
                } else {
                    $concatError .= _FILE_TYPE . ' ' . _UNKNOWN . '||';
                    $history->add(
                        $tableName, $gedId, 'ERR', 'docserverserr',
                        _FAILOVER . ' ' . _DOCSERVERS . ' '
                        . $adr['docserver_id'] . ':'
                        . _FILE_TYPE . ' ' . _UNKNOWN,
                        $_SESSION['config']['databasetype']
                    );
                }
            } else {
                $concatError .= _PB_WITH_FINGERPRINT_OF_DOCUMENT . '||';
                $history->add(
                    $tableName, $gedId, 'ERR', 'docserverserr',
                    _FAILOVER . ' ' . _DOCSERVERS . ' '
                    . $adr['docserver_id'] . ':'
                    . _PB_WITH_FINGERPRINT_OF_DOCUMENT,
                    $_SESSION['config']['databasetype']
                );
            }
            if (file_exists($extract['tmpArchive'])) {
                Ds_washTmp($extract['tmpArchive']);
            }
        }
        //if errors :
        $result = array(
            'status' => 'ko',
            'mime_type' => '',
            'ext' => '',
            'file_content' => '',
            'tmp_path' => '',
            'file_path' => '',
            'called_by_ws' => $calledByWS,
            'error' => $concatError,
        );
        return $result;
    }
    
    /**
     * View the resource, returns the content of the resource
     * @param   bigint $gedId id of th resource
     * @param   string $tableName name of the res table
     * @param   string $adrTable name of the res address table
     * @return  array of elements to view the resource :
     *          status, mime_type, extension,
     *          file_content, tmp_path, file_path, called_by_ws error
     */
    public function viewResource(
        $gedId,
        $tableName,
        $adrTable,
        $calledByWS=false
    ) {
        $history = new history();
        $coreTools = new core_tools();
        //$whereClause = '';
        //THE TEST HAVE TO BE DONE BEFORE !!!
        $whereClause = ' and 1=1';
/*
        if (isset($_SESSION['origin']) && ($_SESSION['origin'] <> 'basket'
            && $_SESSION['origin'] <> 'workflow')
        ) {
            if (isset(
                $_SESSION['user']['security']
                [$_SESSION['collection_id_choice']]
                )
            ) {
                $whereClause = ' and( '
                             . $_SESSION['user']['security']
                             [$_SESSION['collection_id_choice']]['DOC']['where']
                             . ' ) ';
            } else {
                $whereClause = ' and 1=1';
            }
        } else {
            $whereClause = ' and 1=1';
        }
*/
        $adr = array();
        $resource = new resource();
        $adr = $resource->getResourceAdr(
            $tableName,
            $gedId,
            $whereClause,
            $adrTable
        );
        //$coreTools->show_array($adr);
        if ($adr['status'] == 'ko') {
            $result = array(
                'status' => 'ko',
                'mime_type' => '',
                'ext' => '',
                'file_content' => '',
                'tmp_path' => '',
                'file_path' => '',
                'called_by_ws' => $calledByWS,
                'error' => _NO_RIGHT_ON_RESOURCE_OR_RESOURCE_NOT_EXISTS,
            );
            $history->add(
                $tableName,
                $gedId,
                'ERR','docserverserr',
                _NO_RIGHT_ON_RESOURCE_OR_RESOURCE_NOT_EXISTS,
                $_SESSION['config']['databasetype']
            );
        } else {
            require_once('core' . DIRECTORY_SEPARATOR . 'class'
                . DIRECTORY_SEPARATOR . 'docserver_types_controler.php');
            $docserverTypeControler = new docserver_types_controler();
            $concatError = '';
            //failover management
            for (
                $cptDocserver = 0;
                $cptDocserver < count($adr[0]);
                $cptDocserver++
            ) {
                $error = false;
                //retrieve infos of the docserver
                $fingerprintFromDb = $adr[0][$cptDocserver]['fingerprint'];
                $format = $adr[0][$cptDocserver]['format'];
                $docserverObject = $this->get(
                    $adr[0][$cptDocserver]['docserver_id']
                );
                $docserver = $docserverObject->path_template;
                $file = $docserver . $adr[0][$cptDocserver]['path']
                      . $adr[0][$cptDocserver]['filename'];
                $file = str_replace('#', DIRECTORY_SEPARATOR, $file);
                $docserverTypeObject = $docserverTypeControler->get(
                    $docserverObject->docserver_type_id
                );
                if (!file_exists($file)) {
                    $concatError .= _FILE_NOT_EXISTS_ON_THE_SERVER . ' : '
                                  . $file . '||';
                    $history->add(
                        $tableName, $gedId, 'ERR','docserverserr',
                        _FAILOVER . ' ' . _DOCSERVERS . ' '
                        . $adr[0][$cptDocserver]['docserver_id'] . ':'
                        . _FILE_NOT_EXISTS_ON_THE_SERVER . ' : '
                        . $file, $_SESSION['config']['databasetype']
                    );
                } else {
                    $fingerprintFromDocserver = Ds_doFingerprint(
                        $file, $docserverTypeObject->fingerprint_mode
                    );
                    $adrToExtract = array();
                    $adrToExtract = $adr[0][$cptDocserver];
                    $adrToExtract['path_to_file'] = $file;
                    //retrieve infos of the docserver type
                    require_once('core' . DIRECTORY_SEPARATOR . 'class'
                    . DIRECTORY_SEPARATOR . 'docserver_types_controler.php');
                    $docserverTypeControler = new docserver_types_controler();
                    $docserverTypeObject = $docserverTypeControler->get(
                        $docserverObject->docserver_type_id
                    );
                    if ($docserverTypeObject->is_container == "Y"
                        && $adr[0][$cptDocserver]['offset_doc'] == ''
                    ) {
                        $error = true;
                        $concatError .=
                            _PB_WITH_OFFSET_OF_THE_DOC_IN_THE_CONTAINER . '||';
                        $history->add(
                            $tableName, $gedId, 'ERR','docserverserr',
                            _FAILOVER . ' ' . _DOCSERVERS . ' '
                            . $adr[0][$cptDocserver]['docserver_id'] . ':'
                            . _PB_WITH_OFFSET_OF_THE_DOC_IN_THE_CONTAINER,
                            $_SESSION['config']['databasetype']
                        );
                    }
                    //manage compressed resource
                    if ($docserverTypeObject->is_compressed == "Y") {
                        $extract = array();
                        $extract = Ds_extractArchive(
                            $adrToExtract,
                            $docserverTypeObject->fingerprint_mode
                        );
                        if ($extract['status'] == 'ko' || !is_array($extract)) {
                            $error = true;
                            $concatError .= $extract['error'] . '||';
                            $history->add(
                                $tableName, $gedId, 'ERR','docserverserr',
                                _FAILOVER . ' ' . _DOCSERVERS . ' '
                                . $adr[0][$cptDocserver]['docserver_id'] . ':'
                                . $extract['error'],
                                $_SESSION['config']['databasetype']
                            );
                        } else {
                            $file = $extract['path'];
                            $mimeType = $extract['mime_type'];
                            $format = $extract['format'];
                            //to control fingerprint of the offset
                            $fingerprintFromDocserver = $extract['fingerprint'];
                        }
                    } else {
                        $mimeType = Ds_getMimeType(
                            $adrToExtract['path_to_file']
                        );
                    }
                    //manage view of the file
                    $use_tiny_mce = false;
                    if (strtolower($format) == 'maarch'
                        && $coreTools->is_module_loaded('templates')
                    ) {
                        $mode = 'content';
                        $type_state = true;
                        $use_tiny_mce = true;
                        $mimeType = 'application/maarch';
                    } else {
                        require_once 'core/docservers_tools.php';
                        $arrayIsAllowed = array();
                        $arrayIsAllowed = Ds_isFileTypeAllowed($file);
                        $type_state = $arrayIsAllowed['status'];
                    }
                    //if fingerprint from db = 0 we do not control fingerprint
                    if ($fingerprintFromDb == '0'
                        || ($fingerprintFromDb == $fingerprintFromDocserver)
                        || $docserverTypeObject->fingerprint_mode == 'NONE'
                    ) {
                        if ($type_state <> false) {
                            if ($_SESSION['history']['resview'] == 'true') {
                                require_once(
                                    'core' . DIRECTORY_SEPARATOR
                                    . 'class' . DIRECTORY_SEPARATOR
                                    . 'class_history.php'
                                );
                                $history->add(
                                    $tableName, $gedId, 'VIEW','resview',
                                    _VIEW_DOC_NUM . $gedId,
                                    $_SESSION['config']['databasetype'],
                                    'indexing_searching'
                                );
                            }
                            //count number of viewed in listinstance for
                            //the user
                            if ($coreTools->is_module_loaded('entities')
                                && $coreTools->is_module_loaded('basket')
                            ) {
                                require_once(
                                    'modules' . DIRECTORY_SEPARATOR
                                    . 'entities' . DIRECTORY_SEPARATOR
                                    . 'class' . DIRECTORY_SEPARATOR
                                    . 'class_manage_entities.php'
                                );
                                $ent = new entity();
                                $ent->increaseListinstanceViewed($gedId);
                            }
                            $encodedContent = '';
                            if (file_exists($file) && !$error) {
                                if ($calledByWS) {
                                    $content = '';
                                    /*$content = file_get_contents(
                                        $file, FILE_BINARY
                                    );*/
                                    $handle = fopen($file, 'r');
                                    if ($handle) {
                                        while (!feof($handle)) {
                                            $content .= fgets($handle, 4096);
                                        }
                                        fclose($handle);
                                    }
                                    $encodedContent = base64_encode($content);
                                } else {
                                    $fileNameOnTmp = 'tmp_file_' . rand()
                                        . '.' . strtolower($format);
                                    $filePathOnTmp = $_SESSION['config']
                                        ['tmppath'] . DIRECTORY_SEPARATOR
                                        . $fileNameOnTmp;
                                    copy($file, $filePathOnTmp);
                                }
                                $result = array(
                                    'status' => 'ok',
                                    'mime_type' => $mimeType,
                                    'ext' => $format,
                                    'file_content' => $encodedContent,
                                    'tmp_path' => $_SESSION['config']
                                    ['tmppath'],
                                    'file_path' => $filePathOnTmp,
                                    'called_by_ws' => $calledByWS,
                                    'error' => '',
                                );
                                if (isset($extract)
                                    && file_exists($extract['tmpArchive'])
                                ) {
                                    Ds_washTmp($extract['tmpArchive']);
                                }
                                return $result;
                            } else {
                                $concatError .= _FILE_NOT_EXISTS . '||';
                                $history->add(
                                    $tableName, $gedId, 'ERR','docserverserr',
                                    _FAILOVER . ' ' . _DOCSERVERS . ' '
                                    . $adr[0][$cptDocserver]['docserver_id']
                                    . ':' . _FILE_NOT_EXISTS,
                                    $_SESSION['config']['databasetype']
                                );
                            }
                        } else {
                            $concatError .= _FILE_TYPE . ' ' . _UNKNOWN . '||';
                            $history->add(
                                $tableName, $gedId, 'ERR','docserverserr',
                                _FAILOVER . ' ' . _DOCSERVERS . ' '
                                . $adr[0][$cptDocserver]['docserver_id'] . ':'
                                . _FILE_TYPE . ' ' . _UNKNOWN,
                                $_SESSION['config']['databasetype']
                            );
                        }
                    } else {
                        $concatError .= _PB_WITH_FINGERPRINT_OF_DOCUMENT . '||';
                        $history->add(
                            $tableName, $gedId, 'ERR','docserverserr',
                            _FAILOVER . ' ' . _DOCSERVERS . ' '
                            . $adr[0][$cptDocserver]['docserver_id'] . ':'
                            . _PB_WITH_FINGERPRINT_OF_DOCUMENT,
                            $_SESSION['config']['databasetype']
                        );
                    }
                    if (file_exists($extract['tmpArchive'])) {
                        Ds_washTmp($extract['tmpArchive']);
                    }
                }
            }
        }
        //if errors :
        $result = array(
            'status' => 'ko',
            'mime_type' => '',
            'ext' => '',
            'file_content' => '',
            'tmp_path' => '',
            'file_path' => '',
            'called_by_ws' => $calledByWS,
            'error' => $concatError,
        );
        return $result;
    }
}

