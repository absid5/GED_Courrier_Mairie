<?php
/*
*    Copyright 2008,2009,2010 Maarch
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
* @brief  Contains the controler of the usergroup object
*   create, save, modify, etc...)
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
    require_once 'core/core_tables.php';
    require_once 'modules/basket/basket_tables.php';
    require_once 'core/class/usergroups.php';
    require_once 'core/class/ObjectControlerAbstract.php';
    require_once 'core/class/ObjectControlerIF.php';
    require_once 'core/class/SecurityControler.php';

} catch (Exception $e) {
    functions::xecho($e->getMessage()) . ' // ';
}

/**
* @brief  Controler of the usergroup object
*
*<ul>
*  <li>Get an usergroup object from an id</li>
*  <li>Save in the database a usergroup</li>
*  <li>Manage the operation on the usergroups related tables in the database
*       (insert, select, update, delete)</li>
*</ul>
* @ingroup core
*/
class usergroups_controler extends ObjectControler implements ObjectControlerIF
{
    /**
    * Returns an usergroup object based on a usegroup identifier
    *
    * @param  $groupId string  Usergroup identifier
    * @param  $canBeDisabled bool  if true gets the group even if it is
    *   disabled in the database (false by default)
    * @return usergroup object with properties from the database or null
    */
    public function get($groupId, $canBeDisabled=false)
    {
        $this->set_foolish_ids(array('group_id'));
        $this->set_specific_id('group_id');
        return $this->advanced_get($groupId, USERGROUPS_TABLE);
    }

    /**
    * Returns all usergroups (enabled by default) from the database in an array
    *   of usergroup objects (ordered by group_desc by default)
    *
    * @param  $orderStr string  Order string passed to the query
    *   ("order by group_desc asc" by default)
    * @param  $enabledOnly bool  if true returns only the enabled usergroups,
    *   otherwise returns even the disabled (true by default)
    * @return Array of usergroup objects with properties from the database
    */
    public function getAllUsergroups($orderStr='order by group_desc asc',
        $enabledOnly=true)
    {
        $db = new Database();
        $query = 'select * from ' . USERGROUPS_TABLE . ' ';
        if ($enabledOnly) {
            $query .= "where enabled = 'Y'";
        }
        $query .= $orderStr;

        try {
            $stmt = $db->query($query);
        } catch (Exception $e){}

        $groups = array();
        while ($res = $stmt->fetchObject()) {
            $group = new usergroups();
            $tmpArray = array(
                'group_id'   => $res->group_id,
                'group_desc' => $res->group_desc,
                'enabled'    => $res->enabled,
            );
            $group->setArray($tmpArray);
            array_push($groups, $group);
        }
        return $groups;
    }

    /**
    * Returns in an array all the members of a usergroup (user_id only)
    *
    * @param  $groupId string  Usergroup identifier
    * @return Array of user_id or null
    */
    public function getUsers($groupId)
    {
        if (empty($groupId)) {
            return null;
        }
        $users = array();
        $db = new Database();
        $query = 'select user_id from ' . USERGROUP_CONTENT_TABLE
               . " where group_id = ?";
        try {
            $stmt = $db->query($query, array($groupId));
        } catch (Exception $e) {
            echo _NO_GROUP_WITH_ID . ' ' . functions::xssafe($groupId) . ' // ';
        }
        while ($res = $stmt->fetchObject()) {
            array_push($users, $res->user_id);
        }
        return $users;
    }

    /**
    * Returns the id of the primary group for a given user_id
    *
    * @param  $userId string  User identifier
    * @return String  group_id or null
    */
    public function getPrimaryGroup($userId)
    {
        if (empty($userId)) {
            return null;
        }
        $users = array();
        $db = new Database();
        $query = 'select group_id from ' . USERGROUP_CONTENT_TABLE
               . " where user_id = ? and primary_group = 'Y'";
        try {
            $stmt = $db->query($query, array($userId));
        } catch (Exception $e){
            echo _NO_USER_WITH_ID.' '.functions::xssafe($userId).' // ';
        }
        $res = $stmt->fetchObject();
        if (isset($res->group_id)) {
            $groupId = $res->group_id;
        } else {
            return null;
        }
        return $groupId;
    }

    /**
    * Returns in an array all the baskets associated with a usergroup
    *   (basket_id only)
    *
    * @param  $groupId string  Usergroup identifier
    * @return Array of basket_id or null
    */
    public function getBaskets($groupId)
    {
        if (empty($groupId)) {
            return null;
        }
        $baskets = array();
        $db = new Database();
        $query = 'select basket_id from ' . GROUPBASKET_TABLE
               . " where group_id = ?";
        try {
            $stmt = $db->query($query, array($groupId));
        } catch (Exception $e) {
            echo _NO_GROUP_WITH_ID.' '.functions::xssafe($groupId).' // ';
        }
        while ($res = $stmt->fetchObject()) {
            array_push($baskets, $res->basket_id);
        }
        return $baskets;
    }

    /**
    * Returns in an array all the services linked to a usergroup
    *   (service_id only)
    *
    * @param  $groupId string  Usergroup identifier
    * @return Array of service_id or null
    */
    public function getServices($groupId)
    {
        if (empty($groupId)) {
            return null;
        }
        $db = new Database();
        $query = 'select service_id from ' . USERGROUPS_SERVICES_TABLE
               . " where group_id = ?";
        try {
            $stmt = $db->query($query, array($groupId));
        } catch (Exception $e){
            echo _NO_GROUP_WITH_ID . ' ' . functions::xssafe($groupId) . ' // ';
        }

        $services = array();
        while ($queryResult = $stmt->fetchObject()) {
            array_push($services, trim($queryResult->service_id));
        }
        return $services;
    }


    /**
    * Saves in the database a usergroup object
    *
    * @param  $group usergroup object to be saved
    * @param  $security Security access data, array(
    *       'COLL_ID' => collection identifier,
    *       'WHERE_CLAUSE' => where clause on the view,
    *       'COMMENT' => comment on the access,
    *       'WHERE_TARGET' => target view (DOC = view of the collection),
    *       'RIGHTS_BITMASK' => Access bitmask = rights allowed for the access
    *           on the where target,
    *       'START_DATE' => Start date of the access(NOT FULLY IMPLEMENTED YET),
    *       'STOP_DATE' => Start date of the access (NOT FULLY IMPLEMENTED YET)
    *                                               )
    * @param  $services Array of services identifier
    * @param  $mode Mode (add or up)
    * @param  $params More parameters, array(
    *      'modules_services'      => $_SESSION['modules_services'] type array,
    *      'log_group_up'          => 'true' / 'false': log group modification ,
    *      'log_group_add'         => 'true' / 'false': log group addition ,
    *      'databasetype'          => Type of the database,
    *      'user_id'               => Current user identifier (used to process
    *                                 context variables : @entities, ...)
    *       )
    * @return array (
    *       'status' => 'ok' / 'ko',
    *       'value'  => Usergroup identifier or empty in case of error,
    *       'error'  => Error message, defined only in case of error
    *       )
    */
    public function save($group, $security = array(), $services = array(),
        $mode = '', $params = array())
    {
        $control = array();
        $secCtrl = new SecurityControler();
        $sec = new security();
        $func = new functions();
        // If usergroup not defined or empty, return an error
        if (!isset($group) || empty($group)) {
            $control = array(
                'status' => 'ko',
                'value' => '',
                'error' => _GROUP_EMPTY,
            );
            return $control;
        }
        // If mode not up or add, return an error
        if (!isset($mode) || empty($mode)
            || ($mode <> 'add' && $mode <> 'up' )
        ) {
            $control = array(
                'status' => 'ko',
                'value' => '',
                'error' => _MODE . ' ' . _UNKNOWN,
            );
            return $control;
        }
        $group = $this->_isAGroup($group);
        $this->set_foolish_ids(array('group_id'));
        $this->set_specific_id('group_id');
        // Data checks
        $control = $this->control($group, $security, $services, $mode, $params);
        // Security checks
        for ($i = 0; $i < count($security); $i ++) {
            $view = $sec->retrieve_view_from_coll_id(
                $security[$i]['COLL_ID']
            );
            if ($secCtrl->isUnsecureRequest($security[$i]['WHERE_CLAUSE'])) {
                $res['RESULT'] = false;
                $res['TXT'] = WHERE_CLAUSE_NOT_SECURE;
                $control = array(
                    'status' => 'ko',
                    'value'  => '',
                    'error'  => WHERE_CLAUSE_NOT_SECURE
                );
            } else {
               $res = $secCtrl->check_where_clause(
                    $security[$i]['COLL_ID'], $security[$i]['WHERE_TARGET'],
                    $security[$i]['WHERE_CLAUSE'], $view, $params['user_id']
                );
            }
            if ($res['RESULT'] == false) {
                $control = array(
                    'status' => 'ko',
                    'value'  => '',
                    'error'  => $res['TXT']
                );
                break;
            }
        }
        /*print_r($res);
        print_r($control);exit;*/
        if ($control['status'] == 'ok') {
            $secCtrl->deleteForGroup($group->group_id);
            for ($i = 0; $i < count($security); $i ++) {
                if ($security[$i] <> "") {
                    $values = array(
                        'group_id'       => $group->group_id,
                        'coll_id'        => $security[$i]['COLL_ID'],
                        'where_clause'   => $security[$i]['WHERE_CLAUSE'],
                        'maarch_comment' => $security[$i]['COMMENT'],
                        'where_target'   => $security[$i]['WHERE_TARGET']
                        );

                    $bitmask = '0';
                    if (isset($security[$i]['RIGHTS_BITMASK'])
                        && !empty($security[$i]['RIGHTS_BITMASK'])
                    ) {
                        $bitmask = (string) $security[$i]['RIGHTS_BITMASK'];
                    }
                    $values['rights_bitmask'] = $bitmask;

                    if (isset($security[$i]['START_DATE'])
                        && !empty($security[$i]['START_DATE'])
                    ) {
                        $values['mr_start_date'] = $func->format_date_db(
                            $security[$i]['START_DATE']
                        );
                    }
                    if (isset($security[$i]['STOP_DATE'])
                        && !empty($security[$i]['STOP_DATE'])
                    ) {
                        $values['mr_stop_date'] = $func->format_date_db(
                            $security[$i]['STOP_DATE']
                        );
                    }

                    $sec = new SecurityObj();
                    $sec->setArray($values);
                    $secCtrl->save($sec);
                }
            }
            $this->deleteServicesForGroup($group->group_id);
            for ($i = 0; $i < count($services); $i ++) {
                if (!empty($services[$i])) {
                    $this->insertServiceForGroup(
                        $group->group_id, $services[$i]
                    );
                }
            }
            $core = new core_tools();

            $_SESSION['service_tag'] = 'group_' . $mode;
            $core->execute_modules_services(
                $params['modules_services'], 'groups_add_db', 'include'
            );

            if ($mode == 'up') {
                //Update existing group
                if ($this->update($group)) {
                    $control = array(
                        'status' => 'ok',
                        'value'  => $group->group_id
                    );
                    //log
                    if ($params['log_group_up'] == 'true') {
                        $history = new history();
                        $history->add(
                            USERGROUPS_TABLE, $group->group_id, 'UP', 'usergroupup',
                            _GROUP_UPDATE . ' : ' . $group->group_id,
                            $params['databasetype']
                        );
                    }
                } else {
                    $control = array(
                        'status' => 'ko',
                        'value'  => '',
                        'error'  => _PB_WITH_GROUP_UPDATE,
                    );
                }
            } else { //mode == add
                if ($this->insert($group)) {
                    $control = array(
                        'status' => 'ok',
                        'value'  => $group->group_id
                    );
                    //log
                    if ($params['log_group_add'] == 'true') {
                        $history = new history();
                        $history->add(
                            USERGROUPS_TABLE, $group->group_id, 'ADD','usergroupadd',
                            _GROUP_ADDED.' : '.$group->group_id,
                            $params['databasetype']
                        );
                    }
                } else {
                    $control = array(
                        'status' => 'ko',
                        'value'  => '',
                        'error'  => _PB_WITH_USERGROUP,
                    );
                }
            }
        }
        unset($_SESSION['service_tag']);
        return $control;
    }

    /**
    * Control the data of usergroups object
    *
    * @param  $group Usergroups object
    * @param  $security Security access data
    * @param  $services Array of services identifier
    * @param  $mode Mode (add or up)
    * @param  $params More parameters, array(
    *           'modules_services'  => $_SESSION['modules_services'] type array,
    *           'log_group_up'      => 'true' / 'false': log group modification,
    *           'log_group_add'     => 'true' / 'false': log group addition ,
    *           'databasetype'      => Type of the database
    *          )
    * @return array (
    *           'status' => 'ok' / 'ko',
    *           'value'  => Usergroup identifier or empty in case of error,
    *           'error'  => Error message, defined only in case of error
    *          )
    */
    private function control($group, $security, $services, $mode,
        $params = array())
    {
        $error = "";
        $func = new functions();

        $group->group_id = $func->wash($group->group_id, 'no', _THE_GROUP, 'yes', 0, 32);

        if (isset($group->group_desc) && !empty($group->group_desc)) {
            $group->group_desc  = $func->wash($group->group_desc, 'no', _GROUP_DESC, 'yes', 0, 255);
        }

        if ($mode == "add" && $this->groupExists($group->group_id)) {
            $func->add_error(
                $group->group_id . ' ' . _ALREADY_EXISTS . "<br />"
            );
        }

        $_SESSION['service_tag'] = 'group_check';
        $core = new core_tools();
        $core->execute_modules_services(
            $params['modules_services'], 'group_check', 'include'
        );

        $error .= $_SESSION['error'];
        //TODO:rewrite wash to return errors without html and not in the session
        $error = str_replace("<br />", '#', $error);
        $return = array();
        if (!empty($error)) {
            $return = array(
                'status' => 'ko',
                'value'  => $group->group_id,
                'error'  => $error
            );
        } else {
            $return = array(
                'status' => 'ok',
                'value'  => $group->group_id
            );
        }
        unset($_SESSION['service_tag']);
        return $return;
    }

    /**
    * Inserts in the database (usergroups table) a usergroup object
    *
    * @param  $group usergroups object
    * @return bool true if the insertion is complete, false otherwise
    */
    private function insert($group)
    {
        return $this->advanced_insert($group);
    }

    /**
    * Updates a usergroup in the database (usergroups table) with an usergroup
    *   object
    *
    * @param  $group usergroup object
    * @return bool true if the update is complete, false otherwise
    */
    private function update($group)
    {
        return $this->advanced_update($group);
    }

    /**
    * Deletes in the database (usergroups related tables) a given usergroup
    *
    * @param  $group usergroup object
    * @return bool true if the deletion is complete, false otherwise
    */
    public function delete($group, $params = array())
    {
        $control = array();
        if (!isset($group) || empty($group)) {
            $control = array(
                'status' => 'ko',
                'value'  => '',
                'error'  => _GROUP_EMPTY,
            );
            return $control;
        }
        $group = $this->_isAGroup($group);
        if (!$this->groupExists($group->group_id)) {
            $control = array(
                'status' => 'ko',
                'value'  => '',
                'error'  => _GROUP_NOT_EXISTS,
            );
            return $control;
        }

        $this->set_foolish_ids(array('group_id'));
        $this->set_specific_id('group_id');

        $groupId = $group->__get('group_id');
        $ok = $this->advanced_delete($group);
        if ($ok) {
            $ok = $this->_cleanUsergroupContent($groupId);
        } else {
            $control = array(
                'status' => 'ko',
                'value'  => '',
                'error'  => _IMPOSSIBLE_TO_DELETE_USER,
            );
        }

        if ($ok) {
            $ok = $this->deleteServicesForGroup($groupId);
        } elseif (!isset($control['status']) || $control['status'] <> 'ko' ) {
            $control = array(
                'status' => 'ko',
                'value'  => '',
                'error'  => _PB_WITH_USERGROUP_CONTENT_CLEANING,
            );
        }

        if ($ok) {
            $secCtrl = new SecurityControler();
            $ok = $secCtrl->deleteForGroup($groupId);
        } elseif (!isset($control['status']) || $control['status'] <> 'ko' ) {
            $control = array(
                'status' => 'ko',
                'value' => '',
                'error' => _PB_WITH_USERGROUP_CONTENT_CLEANING,
            );
        }

        if (!$ok
            && (!isset($control['status']) || $control['status'] <> 'ko' )
        ) {
            $control = array(
                'status' => 'ko',
                'value'  => '',
                'error'  => _PB_WITH_SECURITY_CLEANING,
            );
        }

        if (isset($control['status']) && $control['status'] == 'ok') {
            if (isset($params['log_group_del'])
                && ($params['log_group_del'] == 'true'
                    || $params['log_group_del'] == true)
            ) {
                $history = new history();
                $history->add(
                    USERGROUPS_TABLE, $group->group_id, 'DEL','usergroupdel',
                    _DELETED_GROUP . ' : ' . $group->group_id,
                    $params['databasetype']
                );
            }
        }
        return $control;
    }

    /**
    * Cleans the usergroup_content table in the database from a given usergroup
    *  (group_id)
    *
    * @param  $groupId string  Usergroup identifier
    * @return bool true if the cleaning is complete, false otherwise
    */
    private function _cleanUsergroupContent($groupId)
    {
        if (!isset($groupId)|| empty($groupId)) {
            return false;
        }
        $db = new Database();
        $query = 'delete from ' . USERGROUP_CONTENT_TABLE . " where group_id=?";
        try {
            $stmt = $db->query($query, array($groupId));
            $ok = true;
        } catch (Exception $e){
            echo _CANNOT_DELETE_GROUP_ID . ' ' . functions::xssafe($groupId) . ' // ';
            $ok = false;
        }

        return $ok;
    }


    /**
    * Disables a given usergroup
    *
    * @param  $group usergroup object
    * @return bool true if the disabling is complete, false otherwise
    */
    public function disable($group, $params=array())
    {
        $control = array();
        if (!isset($group) || empty($group)) {
            $control = array(
                'status' => 'ko',
                'value'  => '',
                'error'  => _GROUP_EMPTY,
            );
            return $control;
        }
        $group = $this->_isAGroup($group);
        $this->set_foolish_ids(array('group_id'));
        $this->set_specific_id('group_id');

        if ($this->advanced_disable($group)) {
            $control = array(
                'status' => 'ok',
                'value'  => $group->group_id
            );
            if (isset($params['log_group_disabled'])
                && ($params['log_group_disabled'] == 'true'
                    || $params['log_group_disabled'] == true)
            ) {
                $history = new history();
                $history->add(
                    USERGROUPS_TABLE, $group->group_id, 'BAN','usergroupban',
                    _SUSPENDED_GROUP . ' : ' . $group->group_id,
                    $params['databasetype']
                );
            }
        } else {
            $control = array(
                'status' => 'ko',
                'value'  => '',
                'error'  => _PB_WITH_GROUP_ID,
            );
        }
        return $control;
    }

    /**
    * Enables a given usergroup
    *
    * @param  $group usergroup object
    * @return bool true if the enabling is complete, false otherwise
    */
    public function enable($group, $params=array())
    {
        $control = array();
        if (!isset($group) || empty($group)) {
            $control = array(
                'status' => 'ko',
                'value'  => '',
                'error'  => _GROUP_EMPTY,
            );
            return $control;
        }
        $group = $this->_isAGroup($group);
        $this->set_foolish_ids(array('group_id'));
        $this->set_specific_id('group_id');
        if ($this->advanced_enable($group)) {
            $control = array(
                'status' => 'ok',
                'value' => $group->group_id,
            );
            if (isset($params['log_group_enabled'])
                && ($params['log_group_enabled'] == 'true'
                    || $params['log_group_enabled'] == true)
            ) {
                $history = new history();
                $history->add(
                    USERGROUPS_TABLE, $group->group_id, 'VAL','usergroupval',
                    _AUTORIZED_GROUP . ' : ' . $group->group_id,
                    $params['databasetype']
                );
            }
        } else {
            $control = array(
                'status' => 'ko',
                'value' => '',
                'error' => _PB_WITH_GROUP_ID,
            );
        }
        return $control;
    }

    /**
    * Asserts if a given usergroup (group_id) exists in the database
    *
    * @param  $groupId String Usergroup identifier
    * @return bool true if the usergroup exists, false otherwise
    */
    public function groupExists($groupId)
    {
        if (!isset($groupId) || empty($groupId)) {
            return false;
        }

        $db = new Database();
        $query = 'select group_id from ' . USERGROUPS_TABLE
               . " where group_id = ?";

        try {
            $stmt = $db->query($query, array($groupId));
        } catch (Exception $e) {
            echo _UNKNOWN . _GROUP . ' ' . functions::xssafe($groupId) . ' // ';
        }

        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    /**
    * Deletes all the services for a given usergroup in the usergroups_service
    *   table
    *
    * @param  $groupId String Usergroup identifier
    * @return bool true if the deleting is complete, false otherwise
    */
    public function deleteServicesForGroup($groupId)
    {
        if (!isset($groupId)|| empty($groupId)) {
            return false;
        }
        $db = new Database();
        $query = 'delete from ' . USERGROUPS_SERVICES_TABLE
               . " where group_id=?";
        try {
            $stmt = $db->query($query, array($groupId));
            $ok = true;
        } catch (Exception $e) {
            echo _CANNOT_DELETE_GROUP_ID . ' ' . functions::xssafe($groupId) . ' // ';
            $ok = false;
        }
        return $ok;
    }

    /**
    * Inserts a given service for a given group into the usergroups_services
    *   table
    *
    * @param  $groupId String Usergroup identifier
    * @param  $serviceId String Service identifier
    * @return bool true if the insertion is complete, false otherwise
    */
    public function insertServiceForGroup($groupId, $serviceId)
    {
        if (!isset($groupId)|| empty($groupId) || !isset($serviceId)
            || empty($serviceId)
        ) {
            return false;
        }
        $db = new Database();
        $query = 'insert into ' . USERGROUPS_SERVICES_TABLE
               . " (group_id, service_id) values (?, ?)";
        try {
            $stmt = $db->query($query, array($groupId, $serviceId));
            $ok = true;
        } catch (Exception $e) {
            echo _CANNOT_INSERT . ' ' . functions::xssafe($groupId) 
                . ' ' . functions::xssafe($serviceId) . ' // ';
            $ok = false;
        }
        return $ok;
    }

    /**
    * Checks if a given user is a member of the given group
    *
    * @param  $userId String User identifier
    * @param  $groupId String Usergroup identifier
    * @return bool true if the user is a member, false otherwise
    */
    public function inGroup($userId, $groupId)
    {
        if (!isset($groupId) || empty($groupId) || !isset($userId)
            || empty($userId)
        ) {
            return false;
        }
        $db = new Database();
        $query = 'select user_id from ' . USERGROUP_CONTENT_TABLE
               . " where user_id = ? and group_id = ?";

        try {
            $stmt = $db->query($query, array($userId, $groupId));
        } catch (Exception $e) {
            echo _CANNOT_FIND . ' ' . functions::xssafe($groupId) 
                . ' ' . functions::xssafe($userId) . ' // ';
        }
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Returns the number of usergroup of the usergroups table
    *   (only the enabled by default)
    *
    * @param  $enabledOnly Bool if true counts only the enabled ones,
    *   otherwise counts all usergroups even the disabled ones (true by default)
    * @return Integer the number of usergroups in the usergroups table
    */
    public function getUsergroupsCount($enabledOnly=true)
    {
        $nb = 0;
        $db = new Database();
        $query = 'select group_id from ' . USERGROUPS_TABLE . ' ' ;
        if ($enabledOnly) {
            $query .= "where enabled ='Y'";
        }
        try {
            $stmt = $db->query($query);
        } catch (Exception $e) {

        }
        $nb = $stmt->rowCount();
        return $nb;
    }

     /**
    * Fill a group object with an object if it's not a group
    *
    * @param  $object ws group object
    * @return object usergroups
    */
    private function _isAGroup($object)
    {
        if (get_class($object) <> 'usergroups') {
            $func = new functions();
            $groupObject = new usergroups();
            $array = array();
            $array = $func->object2array($object);
            foreach (array_keys($array) as $key) {
                $userObject->{$key} = $array[$key];
            }
            return $groupObject;
        } else {
            return $object;
        }
    }
}
