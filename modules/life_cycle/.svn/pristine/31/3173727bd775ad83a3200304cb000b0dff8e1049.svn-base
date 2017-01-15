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
* @brief  Contains the controler of life_cycle_steps object 
* (create, save, modify, etc...)
* 
* 
* @file
* @author Laurent Giovannoni
* @date $date$
* @version $Revision$
* @ingroup life_cycle
*/

// To activate de debug mode of the class
$_ENV['DEBUG'] = false;

// Loads the required class
try {
    require_once ("modules/life_cycle/class/lc_cycle_steps.php");
    require_once ("modules/life_cycle/life_cycle_tables_definition.php");
    require_once ("core/class/ObjectControlerAbstract.php");
    require_once ("core/class/ObjectControlerIF.php");
} catch (Exception $e) {
    functions::xecho($e->getMessage()) . ' // ';
}

/**
* @brief  Controler of the lc_cycle_steps object 
*
*<ul>
*  <li>Get an lc_cycle_steps object from an id</li>
*  <li>Save in the database a lc_cycle_steps</li>
*  <li>Manage the operation on the lc_cycle_steps related tables in the 
*  database (insert, select, update, delete)</li>
*</ul>
* @ingroup life_cycle
*/
class lc_cycle_steps_controler
    extends ObjectControler
    implements ObjectControlerIF
{
    
    /**
    * Saves in the database a lc_cycle_steps object 
    *
    * @param  $cycle lc_cycle_steps object to be saved
    * @return bool true if the save is complete, false otherwise
    */
    public function save($cycle, $mode="")
    {
        $control = array();
        if (!isset($cycle) || empty($cycle)) {
            $control = array(
                "status" => "ko", 
                "value" => "", 
                "error" => _CYCLE_STEP_ID_EMPTY,
            );
            return $control;
        }
        $cycle = $this->isACycleSteps($cycle);
        $this->set_foolish_ids(
            array(
                'policy_id', 
                'cycle_id', 
                'cycle_step_id', 
                'docserver_type_id',
            )
        );
        $this->set_specific_id('cycle_step_id');
        if ($mode == "up") {
            $control = $this->control($cycle, "up");
            if ($control['status'] == "ok") {
                //Update existing cycle steps
                if ($this->update($cycle)) {
                    $control = array(
                        "status" => "ok", 
                        "value" => $cycle->cycle_step_id,
                    );
                    //history
                    if ($_SESSION['history']['lcadd'] == "true") {
                        $history = new history();
                        $history->add(
                            _LC_CYCLE_STEPS_TABLE_NAME, 
                            $cycle->cycle_step_id, "UP", 'lcadd',
                            _LC_CYCLE_STEP_UPDATED." : ".$cycle->cycle_step_id, 
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
                        "value" => $cycle->cycle_step_id,
                    );
                    //history
                    if ($_SESSION['history']['lcadd'] == "true") {
                        $history = new history();
                        $history->add(
                            _LC_CYCLE_STEPS_TABLE_NAME, $cycle->cycle_step_id, 
                            "ADD", 'lcadd',
                            _LC_CYCLE_STEP_UPDATED." : ".$cycle->cycle_step_id, 
                            $_SESSION['config']['databasetype']
                        );
                    }
                } else {
                    $control = array(
                        "status" => "ko", 
                        "value" => "", 
                        "error" => _PB_WITH_CYCLE_STEPS,
                    );
                }
            }
        }
        return $control;
    }
    
    /**
    * control the cycle step object before action
    *
    * @param  object $lc_cycle_steps cycle step object
    * @param  string $mode up or add
    * @return array ok if the object is well formated, ko otherwise
    */
    private function control($lc_cycle_steps, $mode)
    {
        $f = new functions();
        $error = "";
        if (isset($lc_cycle_steps->cycle_step_id) 
            && !empty($lc_cycle_steps->cycle_step_id)
        ) {
            // Update, so values exist
            $lc_cycle_steps->cycle_step_id = 
                $f->wash(
                    $lc_cycle_steps->cycle_step_id, "nick", 
                    _LC_CYCLE_STEP_ID." ", "yes", 0, 32
                );
        }
        $lc_cycle_steps->policy_id = 
            $f->wash(
                $lc_cycle_steps->policy_id, "no", _POLICY_ID." ", 'yes', 0, 32
            );
        if (isset($lc_cycle_steps->cycle_id) 
            && !empty($lc_cycle_steps->cycle_id)
        ) {
            $lc_cycle_steps->cycle_id = $f->wash(
                $lc_cycle_steps->cycle_id, "no", _LC_CYCLE_ID." ", 
                'yes', 0, 32
            );
        } else {
            $lc_cycle_steps->policy_id = '';
            $error .= _LC_CYCLE_ID . ' ' . _MANDATORY;
        }
        $lc_cycle_steps->docserver_type_id =
            $f->wash(
                $lc_cycle_steps->docserver_type_id, "no", 
                _DOCSERVER_TYPE_ID." ", 'yes', 0, 32
            );    
        $lc_cycle_steps->cycle_step_desc =
            $f->wash(
                $lc_cycle_steps->cycle_step_desc, "no", 
                _CYCLE_STEP_DESC." ", 'yes', 0, 255
            );
        $lc_cycle_steps->sequence_number =
            $f->wash(
                $lc_cycle_steps->sequence_number, "num", 
                _SEQUENCE_NUMBER." ", 'yes', 0, 255
            );
        $lc_cycle_steps->step_operation =
            $f->wash(
                $lc_cycle_steps->step_operation, 
                "no", _STEP_OPERATION." ", 'yes', 0, 32
            );
        if ($mode == "add" 
            && $this->cycleStepExists($lc_cycle_steps->cycle_step_id)
        ) {    
            $error .= $lc_cycle_steps->cycle_step_id." "._ALREADY_EXISTS."#";
        }
        $error .= $_SESSION['error'];
        //TODO:rewrite wash to return errors without html
        $error = str_replace("<br />", "#", $error);
        $return = array();
        if (!empty($error)) {
                $return = array(
                    "status" => "ko", 
                    "value" => $lc_cycle_steps->cycle_step_id, 
                    "error" => $error,
                );
        } else {
            $return = array(
                "status" => "ok", 
                "value" => $lc_cycle_steps->cycle_step_id,
            );
        }
        return $return;
    }
    
    /**
    * Inserts in the database (lc_cycle_steps table) a lc_cycle_steps object
    *
    * @param  $cycle lc_cycle_steps object
    * @return bool true if the insertion is complete, false otherwise
    */
    private function insert($cycle)
    {
        return $this->advanced_insert($cycle);
    }

    /**
    * Updates in the database (lc_cycle_steps table) a lc_cycle_steps object
    *
    * @param  $cycle lc_cycle_steps object
    * @return bool true if the update is complete, false otherwise
    */
    private function update($cycle)
    {
        return $this->advanced_update($cycle);
    }
    
    /**
    * Returns an lc_cycle_steps object based on a lc_cycle_steps identifier
    *
    * @param  $cycle_step_id string  lc_cycle_steps identifier
    * @param  $comp_where string  where clause arguments 
    * (must begin with and or or)
    * @param  $can_be_disabled bool  if true gets the cycle even if it 
    * is disabled in the database (false by default)
    * @return lc_cycle_steps object with properties from the database or null
    */
    public function get(
        $cycle_step_id, 
        $comp_where='', 
        $can_be_disabled=false
    ) {
        $this->set_foolish_ids(
            array(
                'policy_id', 
                'cycle_id', 
                'cycle_step_id', 
                'docserver_type_id',
            )
        );
        $this->set_specific_id('cycle_step_id');
        $cycle = $this->advanced_get(
            $cycle_step_id, _LC_CYCLE_STEPS_TABLE_NAME
        );
        //var_dump($policy);
        if (get_class($cycle) <> "lc_cycle_steps") {
            return null;
        } else {
            //var_dump($cycle);
            return $cycle;
        }
    }

    /**
    * get lc_cycles_steps with given id for a ws.
    * Can return null if no corresponding object.
    * @param $cycle_step_id of cycle to send
    * @return cycle steps
    */
    public function getWs($cycle_step_id)
    {
        $this->set_foolish_ids(
            array(
                'policy_id', 
                'cycle_id', 
                'cycle_step_id', 
                'docserver_type_id',
            )
        );
        $this->set_specific_id('cycle_step_id');
        $cycle = $this->advanced_get(
            $cycle_step_id, _LC_CYCLE_STEPS_TABLE_NAME
        );
        if (get_class($cycle) <> "lc_cycle_steps") {
            return null;
        } else {
            $cycle = $cycle->getArray();
            return $cycle;
        }
    }

    /**
    * Deletes in the database (lc_cycle_steps related tables) a given 
    * lc_cycle_steps (cycle_step_id)
    *
    * @param  $cycle_step_id string  lc_cycle_steps identifier
    * @return bool true if the deletion is complete, false otherwise
    */
    public function delete($cycle_step_id)
    {
        $control = array();
        if (!isset($cycle_step_id) || empty($cycle_step_id)) {
            $control = array(
                "status" => "ko", 
                "value" => "", 
                "error" => _LC_CYCLE_EMPTY,
            );
            return $control;
        }
        $cycle = $this->isACycleSteps($cycle_step_id);
        if (!$this->cycleStepExists($cycle->cycle_step_id)) {
            $control = array(
                "status" => "ko", 
                "value" => "", 
                "error" => _LC_CYCLE_STEP_NOT_EXISTS,
            );
            return $control;
        }
        if ($this->linkExists($cycle->policy_id, $cycle->cycle_step_id)) {
            $control = array(
                "status" => "ko", 
                "value" => "", 
                "error" => _LINK_EXISTS,
            );
            return $control;
        }
        $db = new Database();
        $query = "delete from "._LC_CYCLE_STEPS_TABLE_NAME
              ." where cycle_step_id = ?";
        try {
            $stmt = $db->query($query, array($cycle->cycle_step_id));
            $ok = true;
        } catch (Exception $e) {
            $control = array(
                "status" => "ko", 
                "value" => "", 
                "error" => _CANNOT_DELETE_CYCLE_STEP_ID
                ." ".$cycle->cycle_step_id,
                );
            $ok = false;
        }
        $control = array(
            "status" => "ok", 
            "value" => $cycle->cycle_step_id,
        );
        if ($_SESSION['history']['lcdel'] == "true") {
            require_once(
                "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR
                ."class_history.php"
            );
            $history = new history();
            $history->add(
                _LC_CYCLE_STEPS_TABLE_NAME, $cycle->cycle_step_id, "DEL", 'lcdel',
                _LC_CYCLE_STEP_DELETED." : ".$cycle->cycle_step_id, 
                $_SESSION['config']['databasetype']
            );
        }
        return $control;
    }

    /**
    * Disables a given lc_cycle_steps
    * 
    * @param  $cycle lc_cycle_steps object 
    * @return bool true if the disabling is complete, false otherwise 
    */
    public function disable($cycle)
    {
        //
    }
    
    /**
    * Enables a given lc_cycle_steps
    * 
    * @param  $cycle lc_cycle_steps object  
    * @return bool true if the enabling is complete, false otherwise 
    */
    public function enable($cycle)
    {
        //
    }
    
    /**
    * Fill a cycle_steps object with an object if it's not a cycle_teps
    *
    * @param  $object ws cycle_steps object
    * @return object cycle_steps
    */
    private function isACycleSteps($object)
    {
        if (get_class($object) <> "lc_cycle_steps") {
            $func = new functions();
            $cycleStepsObject = new lc_cycle_steps();
            $array = array();
            $array = $func->object2array($object);
            foreach (array_keys($array) as $key) {
                $cycleStepsObject->{$key} = $array[$key];
            }
            return $cycleStepsObject;
        } else {
            return $object;
        }
    }
    
    /**
    * Check if the cycle step exists
    * 
    * @param $cycle_step_id lc_cycle_steps identifier
    * @return bool true if it exists
    */
    public function cycleStepExists($cycle_step_id)
    {
        if (!isset ($cycle_step_id) || empty ($cycle_step_id)) {
            return false;
        }
        $db = new Database();
        $query = "select cycle_step_id from " . _LC_CYCLE_STEPS_TABLE_NAME 
               . " where cycle_step_id = ?";
        try {
            $stmt = $db->query($query, array($cycle_step_id));
        } catch (Exception $e) {
            echo _UNKNOWN . _LC_CYCLE_STEP . " " . $cycle_step_id . ' // ';
        }

        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    /**
    * Check if the cycle step is linked
    * 
    * @param $cycle_step_id lc_cycle_steps identifier
    * @param $policy_id lc_policies identifier
    * @return bool true if it exists
    */
    public function LinkExists($policy_id, $cycle_step_id)
    {
        if (!isset($policy_id) || empty($policy_id)) {
            return false;
        }
        if (!isset($cycle_step_id) || empty($cycle_step_id)) {
            return false;
        }
        $db = new Database();
        $query = "select cycle_step_id from "._LC_STACK_TABLE_NAME
            ." where cycle_step_id = ? and policy_id = ?";
        $stmt = $db->query($query, array($cycle_step_id, $policy_id));
        if ($stmt->rowCount() > 0) {
            return true;
        }
    }
    
    /**
    * Return all cycles ID
    * @param $policy_id police_id identifier
    * @return array of cycles
    */
    public function getAllId($policy_id)
    {
        $db = new Database();
        $query = "select cycle_id from " . _LC_CYCLES_TABLE_NAME 
            . " where policy_id = ?";
        try {
            $stmt = $db->query($query, array($policy_id));
        } catch (Exception $e) {
            echo _NO_CYCLE_ID . ' // ';
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
}

