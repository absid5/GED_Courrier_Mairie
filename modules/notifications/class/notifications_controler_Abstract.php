<?php

/*
*    Copyright 2008-2016 Maarch
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
* @brief  Contains the controler of life_cycle object (create, save, modify, etc...)
* 
* 
* @file
* @author Luc KEULEYAN - BULL
* @author Laurent Giovannoni
* @date $date$
* @version $Revision$
* @ingroup life_cycle
*/

// To activate de debug mode of the class
$_ENV['DEBUG'] = false;

// Loads the required class
try {
    require_once ("modules/notifications/class/notifications.php");
    require_once ("modules/notifications/notifications_tables_definition.php");
    require_once ("core/class/ObjectControlerAbstract.php");
    require_once ("core/class/ObjectControlerIF.php");
    require_once ("core/class/class_history.php");
} catch (Exception $e) {
    functions::xecho($e->getMessage()) . ' // ';
}

/**
* @brief  Controler of the notification object 
*
* @ingroup notifications
*/
abstract class notifications_controler_Abstract extends ObjectControler implements ObjectControlerIF
{

    /**
    * Returns an templates_assoc object based on a templates_assoc identifier
    *
    * @param  $ta_sid string  templates_assoc identifier
    * @return templates_assoc object with properties from the database or null
    */
    public function get($notification_sid) {
        
        $this->set_specific_id('notification_sid');
        $notification = $this->advanced_get($notification_sid, _NOTIFICATIONS_TABLE_NAME);
        
        if (get_class($notification) <> "notifications") {
            return null;
        } else {
            return $notification;
        }
    }

    public function getByNotificationId($notificationId) {
        $query = "SELECT * FROM " . _NOTIFICATIONS_TABLE_NAME . " WHERE notification_id = ?"; 
        $dbConn = new Database();
        $stmt = $dbConn->query($query, array($notificationId));
        $notifObj = $stmt->fetchObject();
        return $notifObj;
    }
    
    /**
    * Deletes in the database (lc_policies related tables) a given lc_policies (policy_id)
    *
    * @param  $policy object  policy object
    * @return array true if the deletion is complete, false otherwise
    */
    public function delete($notification) 
    {
        $control = array();
        if (!isset($notification) || empty($notification)) {
            $control = array('status' => 'ko',
                             'value'  => '',
                             'error'  => _NOTIF_EMPTY
                       );
            return $control;
        }
        
        $this->set_specific_id('notification_sid');
        if ($this->advanced_delete($notification) == true) {
            if (isset($params['log_notif_del'])
                && ($params['log_notif_del'] == "true"
                    || $params['log_notif_del'] == true)) {
                $history = new history();
                $history->add(
                    _NOTIFICATIONS_TABLE, $notification->notification_sid, 'DEL', 'notifdel',_NOTIF_DELETED . ' : '
                    . $notification->notification_id
                );
            }
            $control = array('status' => 'ok',
                             'value'  => $notification->notification_sid
                      );
        } else {
            $control = array('status' => 'ko',
                             'value'  => $notification->notification_sid,
                             'error'  => $error
                          );
        }
        return $control;
    }
    
    
    /**
    * Save given object in database:
    * - make an update if object already exists,
    * - make an insert if new object.
    * @param object $policy
    * @param string mode up or add
    * @return array
    */
    public function save($notification, $mode = "") 
    {
        $control = array();

        $this->set_foolish_ids(
            array(
                'notification_id',
                'event_id'
            )
        );
        // If notification not defined or empty, return an error
        if (!isset($notification) || empty($notification)) {
            $control = array('status' => 'ko',
                             'value'  => '',
                             'error'  => _NOTIF_EMPTY
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
        
        //$notification = $this->isAStatus($notification);
        $this->set_specific_id('notification_sid');
        // Data checks
        $control = $this->control($notification, $mode, $params);
        
        if ($control['status'] == 'ok') {
            $core = new core_tools();
            $_SESSION['service_tag'] = 'notif_' . $mode;
            $core->execute_modules_services(
                $params['modules_services'], 'notif_add_db', 'include'
            );
            
            if ($mode == 'up') {
                //Update existing status
                if ($this->update($notification)) {
                    $control = array('status' => 'ok',
                                     'value'  => $notification->notification_sid
                               );
                    //log
                    if ($params['log_status_up'] == 'true') {
                        $history = new history();
                        $history->add(
                            _NOTIFICATIONS_TABLE, $notification->notification_sid, 'UP','notifup',
                            _NOTIF_MODIFIED . ' : ' . $notification->notification_sid
                        );
                    }
                } else {
                    $control = array('status' => 'ko',
                                     'value'  => '',
                                     'error'  => _PB_WITH_NOTIF_UPDATE
                                );
                }
            } else { //mode == add
                if ($this->insert($notification)) {
                    $dbConn = new Database();
                    $stmt = $dbConn->query("SELECT notification_sid FROM notifications ORDER BY notification_sid DESC limit 1");
                    $result_sid = $stmt->fetchObject(); 
                    $control = array('status' => 'ok',
                                     'value'  => $result_sid->notification_sid);
                    //log
                    if ($params['log_notif_add'] == 'true') {
                        $history = new history();
                        $history->add(
                            _NOTIFICATIONS_TABLE, $notification->notification_sid, 'ADD','notifadd',
                            _NOTIF_ADDED . ' : ' . $notification->notification_sid
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
    * Control the data of Status object
    *
    * @param  $status notification object
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
    *                  'value'  => notification identifier or empty in case of error,
    *                  'error'  => Error message, defined only in case of error
    *                  )
    */
    protected function control($notification, $mode, $params=array())
    {
        $error = "";
        $f = new functions();
       
        $notification->notification_id = $f->protect_string_db(
            $f->wash($notification->notification_id, 'no', _ID, 'yes', 0, 50)
        );
        $notification->description = $f->wash($notification->description, 'no', _DESC, 'yes', 0, 255);
        
        if ($notification->is_enabled == 'false') {
            $notification->is_enabled = false;
        } else {
            $notification->is_enabled = true;
        }
        
        $notification->diffusion_type = $f->protect_string_db(
            $f->wash($notification->diffusion_type, 'no', _DIFFUSION_TYPE)
        );
        $notification->diffusion_properties = $f->protect_string_db(
            $f->wash($notification->diffusion_properties, 'no', _DIFFUSION_PROPERTIES, 'no')
        );
        $notification->attachfor_type = $f->protect_string_db(
            $f->wash($notification->attachfor_type, 'no', _ATTACHFOR_TYPE, 'no')
        );
        $notification->attachfor_properties = $f->protect_string_db(
            $f->wash($notification->attachfor_properties, 'no', _ATTACHFOR_PROPERTIES, 'no')
        );

        $_SESSION['service_tag'] = 'notif_check';
        $core = new core_tools();

        $error .= $_SESSION['error'];
        //TODO:rewrite wash to return errors without html and not in the session
        $error = str_replace("<br />", "#", $error);
        $return = array();
        if (!empty($error)) {
                $return = array('status' => 'ko',
                                'value'  => $notification->notification_sid,
                                'error'  => $error
                          );
        } else {
            $return = array('status' => 'ok',
                            'value'  => $notification->notification_sid
                      );
        }
        unset($_SESSION['service_tag']);
               
        return $return;
    }
    
    protected function insert($notification)
    {
        return $this->advanced_insert($notification);
    }

    /**
    * Updates a status in the database (status table) with a Status object
    *
    * @param  $status Status object
    * @return bool true if the update is complete, false otherwise
    */
    protected function update($notification)
    {
       //var_dump($notification); exit();
       return $this->advanced_update($notification);
    }
    
    
}
