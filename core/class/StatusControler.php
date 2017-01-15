<?php
/*
*    Copyright 2008-2011 Maarch
*
*  This file is part of Maarch Framework.
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
*    along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief  Contains the controler of the Status Object (create, save, modify)
*
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

// Loads the required class
try {
    require_once 'core/class/class_db.php';
    require_once 'core/core_tables.php';
    require_once 'core/class/Status.php';
    require_once 'core/class/ObjectControlerAbstract.php';
    // require_once 'core/class/ObjectControlerIF.php';
    require_once 'core/class/class_history.php';
} catch (Exception $e){
    echo functions::xssafe($e->getMessage()).' // ';
}

/**
* @brief  Controler of the Status Object
*
*<ul>
*  <li>Get an status object from an id</li>
*  <li>Save in the database a status</li>
*  <li>Manage the operation on the status related tables in the database
*  (insert, select, update, delete)</li>
*</ul>
* @ingroup core
*/
class Maarch_Core_Class_StatusControler
    extends ObjectControler
   // implements ObjectControlerIF
{

    /**
    * Return an Status Object based on a status identifier
    *
    * @param  $status_id string  Status identifier
    * @return Status object with properties
    *     from the database or null
    */
    public function get($status_id)
    {
        if (empty($status_id)) {
            return null;
        }

        self::set_foolish_ids(array('id'));
        self::set_specific_id('id');
        $status = self::advanced_get($status_id, STATUS_TABLE);

        if (isset($status)) {
            return $status;
        } else {
            return null;
        }
    }

    /**
    * Saves in the database a Status object
    *
    * @param  $status Status object to be saved
    * @param  $mode string  Saving mode : add or up
    * @param  $params More parameters,
    *             array('modules_services' => $_SESSION['modules_services']
    *                                         type array,
    *                 'log_status_up'    => 'true' / 'false':
    *                                         log status modification ,
    *                 'log_status_add'   => 'true' / 'false': log status
    *                                          addition,
    *                 'databasetype'     => Type of the database
    *                 )
    * @return array (  'status' => 'ok' / 'ko',
    *                  'value'  => User identifier or empty in case of error,
    *                  'error'  => Error message, defined only in case of error
    *               )
    */
    public function save($status, $mode, $params)
    {
        $control = array();
        // If status not defined or empty, return an error
        if (!isset($status) || empty($status)) {
            $control = array('status' => 'ko',
                             'value'  => '',
                             'error'  => _STATUS_EMPTY
                       );
            return $control;
        }

        // If mode not up or add, return an error
        if (!isset($mode) || empty($mode)
            || ($mode <> 'add' && $mode <> 'up' )) {
            $control = array('status' => 'ko',
                             'value'  => '',
                             'error'  => _MODE . ' ' ._UNKNOWN
                        );
            return $control;
        }

        $status = self::isAStatus($status);
        self::set_foolish_ids(array('id'));
        self::set_specific_id('id');

        // Data checks
        $control = self::control($status, $mode, $params);

        if ($control['status'] == 'ok') {
            $core = new core_tools();
            $_SESSION['service_tag'] = 'status_' . $mode;
            $core->execute_modules_services(
                $params['modules_services'], 'status_add_db', 'include'
            );

            if ($mode == 'up') {
                //Update existing status
                if (self::update($status)) {
                    $control = array('status' => 'ok',
                                     'value'  => $status->id
                               );
                    //log
                    if ($params['log_status_up'] == 'true') {
                        $history = new history();
                        $history->add(
                            STATUS_TABLE, $status->id, 'UP', 'statusup',
                            _STATUS_MODIFIED . ' : ' . $status->id,
                            $params['databasetype']
                        );
                    }
                } else {
                    $control = array('status' => 'ko',
                                     'value'  => '',
                                     'error'  => _PB_WITH_STATUS_UPDATE
                                );
                }
            } else { //mode == add
                if (self::insert($status)) {
                    $control = array('status' => 'ok',
                                     'value'  => $status->id);
                    //log
                    if ($params['log_status_add'] == 'true') {
                        $history = new history();
                        $history->add(
                            STATUS_TABLE, $status->id, 'ADD', 'statusadd',
                            _STATUS_ADDED . ' : ' . $status->id,
                            $params['databasetype']
                        );
                    }
                } else {
                    $control = array('status' => 'ko',
                                     'value'  => '',
                                     'error'  => _PB_WITH_STATUS
                                );
                }
            }
        }
        unset($_SESSION['service_tag']);
        return $control;
    }

    /**
    * Fill a Status object with an object if it's not a Status
    *
    * @param  $object ws Status object
    * @return object Status
    */
    private function isAStatus($object)
    {
        if (get_class($object) <> 'Status') {
            $func = new functions();
            $statusObject = new Status();
            $array = array();
            $array = $func->object2array($object);
            foreach (array_keys($array) as $key) {
                $statusObject->{$key} = $array[$key];
            }
            return $statusObject;
        } else {
            return $object;
        }
    }

    /**
    * Control the data of Status object
    *
    * @param  $status Status object
    * @param  $mode Mode (add or up)
    * @param  $params More parameters,
    *                 array('modules_services' => $_SESSION['modules_services']
    *                                               type array,
    *                     'log_status_up'      => 'true' / 'false': log status
    *                                               modification,
    *                     'log_status_add'     => 'true' / 'false': log status
    *                                               addition,
    *                     'databasetype'       => Type of the database
    *                )
    * @return array (  'status' => 'ok' / 'ko',
    *                  'value'  => Status identifier or empty in case of error,
    *                  'error'  => Error message, defined only in case of error
    *                  )
    */
    private function control($status, $mode, $params=array())
    {
        $error = "";
        $f = new functions();
        $status->id = $f->wash($status->id, 'no', _THE_ID . ' ', 'yes', 0, 10);

        if ($mode == 'add') {
            if (self::statusExists($status->id)) {
                $error .= _STATUS . ' ' . _ALREADY_EXISTS . '#';
            }
        }

        $status->label_status =  $f->wash($status->label_status, 'no', _DESC, 'yes', 0, 50);

        $status->is_system =  $f->wash($status->is_system, 'no', _IS_SYSTEM);
        
        $status->img_filename = $status->img_filename;
        $status->maarch_module = 'apps';

        if (!isset($status->can_be_searched)
            || ($status->can_be_searched != 'Y'
                && $status->can_be_searched != 'N')) {
          $status->can_be_searched = 'Y';
        }

        if (!isset($status->can_be_modified)
            || ($status->can_be_modified != 'Y'
                && $status->can_be_modified != 'N')) {
          $status->can_be_modified = 'Y';
        }
        
        if (!isset($status->is_folder_status)
            || ($status->is_folder_status != 'N'
                && $status->is_folder_status != 'Y')) {
          $status->is_folder_status = 'N';
        }

        $_SESSION['service_tag'] = 'status_check';
        $core = new core_tools();
        $core->execute_modules_services(
            $params['modules_services'], 'status_check', 'include'
        );

        $error .= $_SESSION['error'];
        //TODO:rewrite wash to return errors without html and not in the session
        $error = str_replace("<br />", "#", $error);
        $return = array();
        if (!empty($error)) {
                $return = array('status' => 'ko',
                                'value'  => $status->id,
                                'error'  => $error
                          );
        } else {
            $return = array('status' => 'ok',
                            'value'  => $status->id
                      );
        }
        unset($_SESSION['service_tag']);
        return $return;
    }

    /**
    * Inserts in the database (status table) a Status object
    *
    * @param  $status Status object
    * @return bool true if the insertion is complete, false otherwise
    */
    private function insert($status)
    {
        return self::advanced_insert($status);
    }

    /**
    * Updates a status in the database (status table) with a Status object
    *
    * @param  $status Status object
    * @return bool true if the update is complete, false otherwise
    */
    private function update($status)
    {
       return self::advanced_update($status);
    }

    /**
    * Deletes in the database (status table) a given status (status_id)
    *
    * @param  $status_id string  Status identifier
    * @return bool true if the deletion is complete, false otherwise
    */
    public function delete($status, $params = array())
    {
        $control = array();
        if (!isset($status) || empty($status)) {
            $control = array('status' => 'ko',
                             'value'  => '',
                             'error'  => _STATUS_EMPTY
                       );
            return $control;
        }
        $status = self::isAStatus($status);
        if (!self::statusExists($status->id)) {
            $control = array('status' => 'ko',
                             'value'  => '',
                             'error'  => _STATUS_NOT_EXISTS
                       );
            return $control;
        }

        self::set_foolish_ids(array('id'));
        self::set_specific_id('id');
        if (self::advanced_delete($status) == true) {
            if (isset($params['log_status_del'])
                && ($params['log_status_del'] == "true"
                    || $params['log_status_del'] == true)) {
                $history = new history();
                $history->add(
                    STATUS_TABLE, $status->id, 'DEL', 'statusdel',
					_STATUS_DELETED . ' : '
                    . $status->id, $params['databasetype']
                );
            }
            $control = array('status' => 'ok',
                             'value'  => $status->id
                      );
        } else {
            $control = array('status' => 'ko',
                             'value'  => $status->id,
                             'error'  => $error
                          );
        }
        return $control;
    }


    /**
    * Asserts if a given status (status_id) exists in the database
    *
    * @param  $status_id String Status identifier
    * @return bool true if the status exists, false otherwise
    */
    public function statusExists($status_id)
    {
        if (!isset($status_id) || empty($status_id)) {
            return false;
        }

        self::$db = new Database();

        $func = new functions();
        $query = 'select id from ' . STATUS_TABLE . " where id = ?";

        $stmt = self::$db->query($query, array($status_id));

        if ($stmt->rowCount() > 0) {
            return true;
        }

        return false;
    }
    
    /**
    * Return all status infos
    * @return array of stauts
    */
    public function getAllInfos() {
        $db = new Database();
        $query = "select * from " . STATUS_TABLE . " order by label_status";
        try {
            $stmt = $db->query($query);
        } catch (Exception $e) {
            echo _NO_STATUS . ' // ';
        }
        if ($stmt->rowCount() > 0) {
            $result = array ();
            $cptId = 0;
            while ($queryResult = $stmt->fetchObject()) {
                $result[$cptId]['id'] = $queryResult->id;
                $result[$cptId]['label'] = $queryResult->label_status;
                $cptId++;
            }
            return $result;
        } else {
            return null;
        }
    }
}
