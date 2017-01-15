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
* @brief  Contains the controler of life_cycle object 
* (create, save, modify, etc...)
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
    require_once ("modules/life_cycle/class/lc_cycles.php");
    require_once ("modules/life_cycle/life_cycle_tables_definition.php");
    require_once ("core/class/ObjectControlerAbstract.php");
    require_once ("core/class/ObjectControlerIF.php");
    require_once ("core/class/SecurityControler.php");
} catch (Exception $e) {
    functions::xecho($e->getMessage()) . ' // ';
}

/**
* @brief  Controler of the lc_cycles object 
*
*<ul>
*  <li>Get an lc_cycles object from an id</li>
*  <li>Save in the database a lc_cycles</li>
*  <li>Manage the operation on the lc_cycles related tables in the database 
*  (insert, select, update, delete)</li>
*</ul>
* @ingroup life_cycle
*/
class lc_cycles_controler extends ObjectControler implements ObjectControlerIF
{
    /**
    * Save given object in database:
    * - make an update if object already exists,
    * - make an insert if new object.
    * @param object $cycle
    * @param string mode up or add
    * @return array
    */
    public function save($cycle, $mode='') 
    {
        $control = array();
        if (!isset($cycle) || empty($cycle)) {
            $control = array(
                "status" => "ko", 
                "value" => "", 
                "error" => _CYCLE_ID_EMPTY,
            );
            return $control;
        }
        $cycle = $this->isACycle($cycle);
        $this->set_foolish_ids(array('policy_id', 'cycle_id'));
        $this->set_specific_id('cycle_id');
        if ($mode == "up") {
            $control = $this->control($cycle, "up");
            if ($control['status'] == "ok") {
                //Update existing cycle
                if ($this->update($cycle)) {
                    $control = array(
                        "status" => "ok", 
                        "value" => $cycle->cycle_id,
                    );
                    //history
                    if ($_SESSION['history']['lcadd'] == "true") {
                        $history = new history();
                        $history->add(
                            _LC_CYCLES_TABLE_NAME, 
                            $cycle->cycle_id, 
                            "UP", 'lcadd',
                            _LC_CYCLE_UPDATED." : ".$cycle->cycle_id, 
                            $_SESSION['config']['databasetype']
                        );
                    }
                } else {
                    $control = array(
                        "status" => "ko", 
                        "value" => "", 
                        "error" => _PB_WITH_CYCLE,
                    );
                }
                return $control;
            }
        } else {
            $control = $this->control($cycle, "add");
            if ($control['status'] == "ok") {
                //Insert new cycle
                if ($this->insert($cycle)) {
                    $control = array(
                        "status" => "ok", 
                        "value" => $cycle->cycle_id,
                    );
                    //history
                    if ($_SESSION['history']['lcadd'] == "true") {
                        $history = new history();
                        $history->add(
                            _LC_CYCLES_TABLE_NAME, 
                            $cycle->cycle_id, 
                            "ADD", 'lcadd',
                            _LC_CYCLE_UPDATED." : ".$cycle->cycle_id, 
                            $_SESSION['config']['databasetype']
                        );
                    }
                } else {
                    $control = array(
                        "status" => "ko", 
                        "value" => "", 
                        "error" => _PB_WITH_CYCLE,
                    );
                }
            }
        }
        return $control;
    }

    /**
    * control the cycle object before action
    *
    * @param  object $cycle cycle object
    * @param  string $mode up or add
    * @return array ok if the object is well formated, ko otherwise
    */
    private function control($cycle, $mode) 
    {
        $f = new functions();
        $sec = new SecurityControler();
        $error = "";
        if (isset($cycle->cycle_id) && !empty($cycle->cycle_id)) {
            // Update, so values exist
            $cycle->cycle_id = $f->wash($cycle->cycle_id, "nick", _LC_CYCLE_ID." ", "yes", 0, 32);
        }
        $cycle->policy_id = $f->wash($cycle->policy_id, "no", _POLICY_ID." ", 'yes', 0, 32);
        $cycle->cycle_desc = $f->wash($cycle->cycle_desc, "no", _CYCLE_DESC." ", 'yes', 0, 255);
        $cycle->sequence_number = $f->wash($cycle->sequence_number, "num", _SEQUENCE_NUMBER." ", 'yes', 0, 255);
        $cycle->break_key = $f->wash($cycle->break_key, "no", _BREAK_KEY." ", 'no', 0, 255);
        if ($sec->isUnsecureRequest($cycle->where_clause)) {
            $error .= _WHERE_CLAUSE_NOT_SECURE."#";
        } elseif (!$this->where_test($cycle->where_clause)) {
            $error .= _PB_WITH_WHERE_CLAUSE."#";
        }
        $cycle->where_clause = $f->wash($cycle->where_clause, "no", _WHERE_CLAUSE." ", 'yes', 0, 255);
        $cycle->validation_mode = $f->wash($cycle->validation_mode, "no", _VALIDATION_MODE." ", 'yes', 0, 32);
        if ($mode == "add" && $this->cycleExists($cycle->cycle_id)) {
            $error .= $cycle->cycle_id." "._ALREADY_EXISTS."#";
        }
        $error .= $_SESSION['error'];
        //TODO:rewrite wash to return errors without html
        $error = str_replace("<br />", "#", $error);
        $return = array();
        if (!empty($error)) {
            $return = array(
                "status" => "ko", 
                "value" => $cycle->cycle_id, 
                "error" => $error,
            );
        } else {
            $return = array(
                "status" => "ok", 
                "value" => $cycle->cycle_id,
            );
        }
        return $return;
    }

    /**
    * Inserts in the database (lc_cycles table) a lc_cycles object
    *
    * @param  $cycle lc_cycles object
    * @return bool true if the insertion is complete, false otherwise
    */
    private function insert($cycle) 
    {
        return $this->advanced_insert($cycle);
    }

    /**
    * Updates in the database (lc_cycles table) a lc_cycles object
    *
    * @param  $cycle lc_cycles object
    * @return bool true if the update is complete, false otherwise
    */
    private function update($cycle) 
    {
        return $this->advanced_update($cycle);
    }

    /**
    * Returns an lc_cycles object based on a lc_cycles identifier
    *
    * @param  $cycle_id string  lc_cycles identifier
    * @param  $comp_where string  where clause arguments 
    * (must begin with and or or)
    * @param  $can_be_disabled bool  if true gets the cycle even if it is 
    * disabled in the database (false by default)
    * @return lc_cycles object with properties from the database or null
    */
    public function get($cycle_id, $comp_where='', $can_be_disabled=false) 
    {
        $this->set_foolish_ids(array('policy_id', 'cycle_id'));
        $this->set_specific_id('cycle_id');
        $cycle = $this->advanced_get($cycle_id, _LC_CYCLES_TABLE_NAME);
        //var_dump($policy);
        if (get_class($cycle) <> "lc_cycles") {
            return null;
        } else {
            //var_dump($cycle);
            return $cycle;
        }
    }

    /**
    * get lc_cycles with given id for a ws.
    * Can return null if no corresponding object.
    * @param $cycle_id of cycle to send
    * @return cycle
    */
    public function getWs($cycle_id) 
    {
        $this->set_foolish_ids(array('policy_id', 'cycle_id'));
        $this->set_specific_id('cycle_id');
        $cycle = $this->advanced_get($cycle_id, _LC_CYCLES_TABLE_NAME);
        if (get_class($cycle) <> "lc_cycles") {
            return null;
        } else {
            $cycle = $cycle->getArray();
            return $cycle;
        }
    }

    /**
    * Deletes in the database (lc_cycles related tables) a given 
    * lc_cycles (cycle_id)
    *
    * @param  $cycle string  lc_cycles identifier
    * @return bool true if the deletion is complete, false otherwise
    */
    public function delete($cycle) 
    {
        $control = array();
        if (!isset($cycle) || empty($cycle)) {
            $control = array(
                "status" => "ko", 
                "value" => "", 
                "error" => _LC_CYCLE_EMPTY,
            );
            return $control;
        }
        $cycle = $this->isACycle($cycle);
        if (!$this->cycleExists($cycle->cycle_id)) {
            $control = array(
                "status" => "ko", 
                "value" => "", 
                "error" => _LC_CYCLE_NOT_EXISTS,
            );
            return $control;
        }
        if ($this->linkExists($cycle->policy_id, $cycle->cycle_id)) {
            $control = array(
                "status" => "ko", 
                "value" => "", 
                "error" => _LINK_EXISTS,
            );
            return $control;
        }
        $db = new Database();
        $query = "delete from "._LC_CYCLES_TABLE_NAME." where cycle_id = ?";
        try {
            $stmt = $db->query($query, array($cycle->cycle_id));
            $ok = true;
        } catch (Exception $e) {
            $control = array(
                "status" => "ko", 
                "value" => "", 
                "error" => _CANNOT_DELETE_CYCLE_ID." ".$cycle->cycle_id,
            );
            $ok = false;
        }
        $control = array(
            "status" => "ok", 
            "value" => $cycle->cycle_id,
        );
        if ($_SESSION['history']['lcdel'] == "true") {
            require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR
                ."class_history.php");
            $history = new history();
            $history->add(
                _LC_CYCLES_TABLE_NAME, $cycle->cycle_id, "DEL", 'lcdel',
                _LC_CYCLE_DELETED." : ".$cycle->cycle_id, 
                $_SESSION['config']['databasetype']
            );
        }
        return $control;
    }

    /**
    * Disables a given lc_cycles
    * 
    * @param  $cycle lc_cycles object 
    * @return bool true if the disabling is complete, false otherwise 
    */
    public function disable($cycle) 
    {
        //
    }

    /**
    * Enables a given lc_cycles
    * 
    * @param  $cycle lc_cycles object  
    * @return bool true if the enabling is complete, false otherwise 
    */
    public function enable($cycle) 
    {
        //
    }

    /**
    * Fill a cycle object with an object if it's not a cycle
    *
    * @param  $object ws cycle object
    * @return object cycle
    */
    private function isACycle($object) 
    {
        if (get_class($object) <> "lc_cycles") {
            $func = new functions();
            $cycleObject = new lc_cycles();
            $array = array();
            $array = $func->object2array($object);
            foreach (array_keys($array) as $key) {
                $cycleObject->{$key} = $array[$key];
            }
            return $cycleObject;
        } else {
            return $object;
        }
    }

    /**
    * Checks if the life cycle cycle exists
    * 
    * @param $cycle_id lc_cycle identifier
    * @return bool true if the cycle exists
    */
    public function cycleExists($cycle_id) 
    {
        if (!isset ($cycle_id) || empty ($cycle_id)) {
            return false;
        }
        $db = new Database();
        $query = "select cycle_id from " . _LC_CYCLES_TABLE_NAME 
            . " where cycle_id = ?";
        try {
            $stmt = $db->query($query, array($cycle_id));
        } catch (Exception $e) {
            echo _UNKNOWN . _LC_CYCLE . " " . $cycle_id . ' // ';
        }
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    /**
    * Checks if the life cycle cycle is linked 
    * 
    * @param $cycle_id lc_cycle identifier
    * @param $policy_id lc_cycle policy identifier
    * @return bool true if the cycle is linked
    */
    public function linkExists($policy_id, $cycle_id) 
    {
        if (!isset($policy_id) || empty($policy_id)) {
            return false;
        }
        if (!isset($cycle_id) || empty($cycle_id)) {
            return false;
        }
        $db = new Database();
        $query = "select cycle_id from "._LC_STACK_TABLE_NAME
            ." where cycle_id = ? and policy_id = ?";
        $stmt = $db->query($query, array($cycle_id, $policy_id));
        if ($stmt->rowCount() > 0) {
            return true;
        }
        $query = "select cycle_id from "._LC_CYCLE_STEPS_TABLE_NAME
            ." where cycle_id = ? and policy_id = ? ";
        $stmt = $db->query($query, array($cycle_id, $policy_id));
        if ($stmt->rowCount() > 0) {
            return true;
        }
    }

    /**
    * Return all cycles ID
    * 
    * @return array of cycles
    */
    public function getAllId($can_be_disabled=false) 
    {
        $db = new Database();
        $query = "select cycle_id from " . _LC_CYCLES_TABLE_NAME . " ";
        if (!$can_be_disabled) {
            $query .= " where enabled = 'Y'";
        }
        try {
            $stmt = $db->query($query);
        } catch (Exception $e) {
            echo _NO_LC_CYCLE . ' // ';
        }
        if ($stmt->rowCount() > 0) {
            $result = array();
            $cptId = 0;
            while ($queryResult = $stmt->fetchObject()) {
                $result[$cptId] = $queryResult->cycle_id;
                $cptId++;
            }
            return $result;
        } else {
            return null;
        }
    }

    /**
    * Displays lc_cycle according to a given policy_id 
    * 
    * @param $policy_id lc_cycle policy identifier
    * @return array lc_cycle identifier
    */
    public function getAllIdByPolicy($policy_id) 
    {
        $db = new Database();
        $query = "select cycle_id from " . _LC_CYCLES_TABLE_NAME 
            . " where policy_id = ?";
        try {
            $stmt = $db->query($query, array($policy_id));
        } catch (Exception $e) {
            echo _NO_LC_CYCLE . ' // ';
        }
        if ($stmt->rowCount() > 0) {
            $result = array();
            $cptId = 0;
            while ($queryResult = $stmt->fetchObject()) {
                $result[$cptId] = $queryResult->cycle_id;
                $cptId++;
            }
            return $result;
        } else {
            return null;
        }
    }

    /**
    * Check the where clause syntax
    *
    * @param  $where_clause string The where clause to check
    * @return bool true if the syntax is correct, false otherwise
    */
    public function where_test($where_clause) 
    {
        $res = true;
        $db = new Database();
        if (!empty($where_clause)) {
            $stmt = $db->query("select res_id from "
                      . $_SESSION['collections'][0]['view']." where "
                      . $where_clause, array(), true
            );
        }
        if (!$stmt) {
            $stmt = false;
        }
        return $stmt;
    }
}

