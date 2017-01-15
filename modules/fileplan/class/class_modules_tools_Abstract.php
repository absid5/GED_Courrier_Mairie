<?php
/*
*
*   Copyright 2013-2016 Maarch
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
* @brief    modules tools Class for fileplan module, 
*           contains all the functions to handle fileplan
*
* @file     class_modules_tools.php
* @author   Yves Christian Kpakpo <dev@maarch.org>
* @date     $date$
* @version  $Revision$
* @ingroup  fileplan
*/

// Loads the required class
try {
    require_once("modules/fileplan/fileplan_tables.php");
} catch (Exception $e){
    functions::xecho($e->getMessage()).' // ';
}

abstract class fileplan_Abstract
{
    /**
    * Build Maarch module tables into sessions vars with a xml configuration file
    *
    *
    */
    public function build_modules_tables() {
        
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . "modules"
            . DIRECTORY_SEPARATOR . "fileplan" . DIRECTORY_SEPARATOR . "xml"
            . DIRECTORY_SEPARATOR . "config.xml"
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                  . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                  . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR
                  . "fileplan" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
                  . "config.xml";
        } else {
            $path = "modules" . DIRECTORY_SEPARATOR . "fileplan"
                  . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
                  . "config.xml";
        }
        $xmlconfig = simplexml_load_file($path);


        //History
        $hist = $xmlconfig->HISTORY;
        $_SESSION['history']['fileplanadd'] = (string) $hist->fileplanadd;
        $_SESSION['history']['fileplanup'] = (string) $hist->fileplanup;
        $_SESSION['history']['fileplandel'] = (string) $hist->fileplandel;
    }
    /**
    *
    *
    *
    */
    public function getFileplan($fileplan_id, $root_mode = true) {
    
        $fileplan = array();
        
        $db = new Database();

        $stmt = $db->query(
            "select * from " 
            . FILEPLAN_TABLE 
            . " where fileplan_id = ?" 
        ,array($fileplan_id));

        if($stmt->rowCount() > 0) {        
            $res = $stmt->fetchObject();
            //Root mode
            ($root_mode === true)? $id = '##ROOT##' : $id = $res->fileplan_id;
            
            $fileplan = array(
                'ID' => $id , 
                'LABEL' => functions::show_string($res->fileplan_label),
                'USER' => $res->user_id,
                'ENTITY' => $res->entity_id,
                'IS_SERIAL' => $res->is_serial_id,
                'ENABLED' => $res->enabled
            );
        }
        return $fileplan;
    }
    /**
    *
    *
    *
    */
    public function isPersonnalFileplan($fileplan_id) {
        
        $db = new Database();

        $stmt = $db->query(
            "select fileplan_id from " 
            . FILEPLAN_TABLE . " where fileplan_id = ? and user_id = ?"
        ,array($fileplan_id,$_SESSION['user']['UserId']));
        
        return ($stmt->rowCount() > 0)? true : false;
    }
    /**
    *
    *
    *
    */
    public function isSerialFileplan($fileplan_id) {
        $db = new Database();
        $stmt = $db->query(
            "select fileplan_id from " 
            . FILEPLAN_TABLE . " where fileplan_id = ? and is_serial_id = ?"
        ,array($fileplan_id,'Y'));
        
        return ($stmt->rowCount() > 0)? true : false;
    }
    /**
    *
    *
    *
    */
    public function fileplanHasPositions($fileplan_id) {

        $db = new Database();
        $stmt = $db->query(
            "select position_id from " 
            . FILEPLAN_POSITIONS_TABLE . " where fileplan_id = ?"
        ,array($fileplan_id));
        
        return ($stmt->rowCount() > 0)? true : false;
    }
    /**
    *
    *
    *
    */
    public function userCanChangeFileplan($fileplan_id) {
    
        if ($this->isPersonnalFileplan($fileplan_id)) {
            return true;
        } else {
            $core_tools = new core_tools();
            if ($core_tools->test_service('put_doc_in_fileplan', 'fileplan', false)) {
                return true;
            } else {
                return false;
            }
        }
    }
    /**
    *
    *
    *
    */
    public function getEntitiesFileplan() {
    
        $fileplan = array();
        // if (isset($_SESSION['user']['entities']) && count($_SESSION['user']['entities']) > 0) {
            // $entities = array();
            // for($i=0; $i < count($_SESSION['user']['entities']); $i++) {
                // array_push($entities, "'".$_SESSION['user']['entities'][$i]['ENTITY_ID']."'");
            // }
            $db = new Database();
            // $this->query(
                // "select * from " 
                // . FILEPLAN_TABLE . " where entity_id in(" 
                // . join(',', $entities) . ")"
            // );
            $stmt = $db->query(
                "select * from " 
                . FILEPLAN_TABLE . " where user_id is null"
            );
            
            if($stmt->rowCount() > 0) {
                while($res = $stmt->fetchObject()) {
                    array_push(
                        $fileplan , 
                        array(
                            'ID' =>   $res->fileplan_id, 
                            'LABEL' => functions::show_string($res->fileplan_label),
                            'ENABLED' => $res->enabled
                        )
                    );
                }
            }
        // }
        
        return $fileplan;
    }
    /**
    *
    *
    *
    */
    public function getUserFileplan() {
    
        $fileplan = array();
        
        $db = new Database();
        $stmt = $db->query(
            "select * from " 
            . FILEPLAN_TABLE . " where user_id = ?"
        ,array($_SESSION['user']['UserId']));

        if($stmt->rowCount() > 0) {        
            $res = $stmt->fetchObject();
            array_push(
                $fileplan , 
                array(
                    'ID' =>   $res->fileplan_id, 
                    'LABEL' => functions::show_string($res->fileplan_label),
                    'ENABLED' => $res->enabled
                )
            );
        }
        return $fileplan;
    }
    /**
    *
    *
    *
    */
    public function getAuthorizedFileplans() {
    
        $authorizedFileplans = array();
        
        //Get user fileplan
        $userFileplanArray = $this->getUserFileplan();
        
        //Get global fileplans
        $entitiesFileplanArray = $this->getEntitiesFileplan();

        // print_r($userFileplanArray);
        // print_r($entitiesFileplanArray);
        
        $authorizedFileplans = array_merge ($userFileplanArray, $entitiesFileplanArray);
        
        return $authorizedFileplans;
    }
    /**
    *
    *
    *
    */
    public function positionAlreadyExists($fileplan_id, $position_id) {
        $db = new Database();
        $stmt = $db->query(
            "select position_id from " 
            . FILEPLAN_POSITIONS_TABLE
            . "  where position_id = ? and fileplan_id = ?"
            ,array($position_id,$fileplan_id));
            
        if($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    /**
    * Build position path
    *
    *
    */
    protected function _buildPositionPath($fileplan_id, $position_array, $position_id) {

        if (!empty($fileplan_id) && !empty($position_id)) {
            $db = new Database();
            $stmt = $db->query(
                "select position_label, parent_id from " 
                . FILEPLAN_POSITIONS_TABLE 
                . " where fileplan_id = ? and position_id = ? "
            ,array($fileplan_id,$position_id));
           
            $res = $stmt->fetchObject();
            $position_name = functions::show_string($res->position_label);
            $parent_id = $res->parent_id;
            
            if (!empty($position_name)){
                array_push($position_array , $position_name);
            }
            if (!empty($parent_id)){
                $position_array = $this->_buildPositionPath($fileplan_id, $position_array, $parent_id);
            }
        }
        return $position_array;
    }
    /**
    * Return position path
    *
    *
    */
    public function getPositionPath($fileplan_id, $position_id, $full_path = false, $separator='/') {
        
        $position_path = '';
        
        if (!empty($fileplan_id) && !empty($position_id)) {
            //
            $position_array = array();
            //Build path
            $position_array = $this->_buildPositionPath($fileplan_id, $position_array, $position_id);
            //Ascendant sort
            krsort($position_array);
            //Join with separator
            $position_path = join($separator, $position_array);
            //Full path
            if ($full_path) {
                $fileplan_array = $this->getFileplan($fileplan_id, false);
                $position_path = $fileplan_array['LABEL'].$separator.$position_path;
            }   
        }
        
        return  $position_path;
    }
    /**
    * Get position name
    *
    *
    */
    public function getPosition($fileplan_id, $position_id, $onlyThisField = '') {
        
        if (!empty($fileplan_id) && !empty($position_id)) {
            $db = new Database();
            $stmt = $db->query(
                "select * from " 
                . FILEPLAN_VIEW . " where fileplan_id = ?"
                . " and position_id = ?"
                ,array($fileplan_id,$position_id));
             
            $line = $stmt->fetchObject();
            if(!empty($onlyThisField)){
                return (isset($line->{$onlyThisField}))? functions::show_string($line->{$onlyThisField}) : false;
            } else {
                $positionArray = array();
                array_push(
                $positionArray , 
                array(
                    'ID' => $line->position_id, 
                    'LABEL' => functions::show_string($line->position_label), 
                    'PARENT_ID' => $line->parent_id,
                    'COUNT_DOCUMENT' => $line->count_document
                    )
                );
                return $positionArray;
            }
        }
    }
    /**
    * Truncate string
    *
    *
    */
    public function truncate($string, $limit = 30, $etc = "...") {
                
        if (strlen($string) > $limit) {
        
            $string = substr($string, 0, $limit);
            // $string = substr($string,0,strrpos($string," "));

            $string = $string.$etc;
        }
        
        return $string;
    }
    /**
    * Truncate around the middle
    *
    *
    */
    public function m_truncate($string, $limit = 30, $etc = "...") {
    
        if (strlen($string) > $limit) {
        
            $first_part = $this->truncate(
                            $this->truncate(
                                    $string
                                    , strlen($string)/2
                                    , ""
                                    )
                            , $limit/2
                            , ""
                            );
                            
            $second_part = $this->truncate(
                            strrev(
                                    $this->truncate(
                                        strrev($string)
                                        , strlen($string)/2
                                        , ""
                                        )
                                    )
                            , $limit/2
                            , ""
                            );
                            
            $string = $first_part.$etc.$second_part;
        }
        
        return $string;
    }
    /**
    * Get positions tree
    *
    *
    */
    public function getPositionsTree($fileplan_id, $positions, $parent='',  $tabspace = '') {

        if(is_array($parent)) {
            // echo 'IS_ARRAY<br/>';
            for ($i=0; $i < count($parent); $i++) {
                $tmp = array();
                array_push($positions, 
                            array(
                                'ID' =>   $parent[$i]['ID'], 
                                'LABEL' => functions::show_string($parent[$i]['LABEL']),
                                'PARENT_ID' => $parent[$i]['PARENT_ID'],
                                'COUNT_DOCUMENT' => $parent[$i]['COUNT_DOCUMENT']
                                )
                        );

                $tmp = $this->_getChildrensTree($fileplan_id, $tmp, $parent[$i]['ID'], $tabspace);
                $positions = array_merge($positions, $tmp);
            }
        } else {
             // echo 'IS_NOT_ARRAY<br/>';
            $positions = $this->_getChildrensTree($fileplan_id, $positions, '', $tabspace);
        }
        return $positions;
    }
    /**
    *
    *
    *
    */
    protected function _getChildrensTree($fileplan_id, $positions, $parent = '', $tabspace = '') {

        $db = new Database();
        if (functions::protect_string_db(trim($parent)) == '') {

            $stmt = $db->query(
                    "select position_id, position_label, parent_id, count_document from "
                    . FILEPLAN_VIEW 
                    . " where fileplan_id = ?" 
                    . " and parent_id is null" 
                    . " order by position_label asc"
                    ,array($fileplan_id));
        } else {
            $stmt = $db->query(
                "select position_id, position_label, parent_id, count_document from "
                . FILEPLAN_VIEW." where fileplan_id = ?"
                . " and parent_id = ?"
                . " order by position_label asc"
                ,array($fileplan_id,trim($parent)));
        }
        if($stmt->rowCount() > 0) {
        
            $espace = $tabspace.'&emsp;';
            
            while($line = $stmt->fetchObject()) {

                 array_push(
                        $positions, 
                        array(
                            'ID' =>$line->position_id, 
                            'LABEL' =>  $espace.functions::show_string($line->position_label), 
                            'PARENT_ID' =>$line->parent_id,
                            'COUNT_DOCUMENT' => $line->count_document
                            )
                        );       
                if (!empty($line->position_id)) {
                    $fp = new fileplan();
                    $stmt2 = $db->query("select position_id from "
                        . FILEPLAN_VIEW." where fileplan_id = ?"
                        . " and parent_id = ?"
                        ,array($fileplan_id,$line->position_id));
                    
                    $tmp = array();
                    if($stmt2->rowCount() > 0) {

                        $tmp = $fp->_getChildrensTree($fileplan_id, $tmp, $line->position_id, $espace);
                        $positions = array_merge($positions, $tmp);
                    }
                }
            }
        }
        return $positions;
    }
    /**
    *
    *
    *
    */
    public function getParents($positions, $fileplan_id, $position_id) {
    
        if (!empty($fileplan_id) && !empty($position_id)) {
            $db = new Database();

            $stmt = $db->query(
                    "select parent_id from "
                    . FILEPLAN_VIEW . " where fileplan_id = ?"
                    . " and position_id = ?"
                    ,array($fileplan_id,$position_id));
            $res = $stmt->fetchObject();
            
            if(!empty($res->parent_id )) {
 
                $db = new Database();
                $stmt = $db->query(
                    "select position_id, position_label, parent_id, count_document from "
                    . FILEPLAN_VIEW." where fileplan_id = ?"
                    . " and position_id = ?" 
                    ,array($fileplan_id,$res->parent_id));
                    
                $line = $stmt->fetchObject();
                array_push(
                        $positions, 
                        array(
                            'ID' => $line->position_id, 
                            'LABEL' =>  functions::show_string($line->position_label), 
                            'PARENT_ID' => $line->parent_id,
                            'COUNT_DOCUMENT' => $line->count_document
                            )
                        );
        
                if(!empty($line->parent_id )) {
                    $positions = $this->getParents($positions, $fileplan_id, $line->position_id);
                }
            }
        }
        return $positions;
    }
    /**
    *
    *
    *
    */
    public function isEnable($fileplan_id, $position_id) {
        $db = new Database();
        $stmt = $db->query(
            "select position_id from " 
            . FILEPLAN_POSITIONS_TABLE
            . "  where fileplan_id = ?"
            . " and position_id = ?" 
            . " and enabled = ?"
        ,array($fileplan_id,$position_id,'Y'));
            
        if($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    /**
    *
    *
    *
    */
    public function buildResArray($res_string) {
    
        if (strlen(trim($res_string)) > 0) {
            $resIdArray = $res_array = array();
            $resIdArray = explode (',', $res_string);
            
            //Separate coll_id and res_id
            for($i = 0; $i < count($resIdArray); $i++) {
                //Build res_array
                $tmp = explode('@@', $resIdArray[$i]);
                array_push($res_array, 
                    array(
                        'COLL_ID' => $tmp[0],
                        'RES_ID' => $tmp[1]
                    )
                );
            }
            
            return $res_array;
        } else {
            return false;
        }
    }
    /**
    *
    *
    *
    */
    public function set($fileplan_id, $position_id,  $res_id, $coll_id) {
        if (!empty($fileplan_id) 
            && !empty($position_id) 
            && !empty($res_id) 
            && !empty($coll_id) 
        ) {
            $db = new Database();
            $stmt = $db->query(
                    "SELECT fileplan_id FROM "
                    . FILEPLAN_RES_POSITIONS_TABLE
                    . " WHERE fileplan_id = ?"
                    . " AND position_id = ?" 
                    . " AND res_id = ?"
                    . " AND coll_id = ?"
            ,array($fileplan_id,$position_id,$res_id,$coll_id));

            if($stmt->rowCount() == 0) {
                $stmt = $db->query(
                    "INSERT INTO ".FILEPLAN_RES_POSITIONS_TABLE
                    . " (fileplan_id, res_id, coll_id,position_id) VALUES (?,?,?,?)"
                    ,array($fileplan_id,$res_id,$coll_id,$position_id));
            }
        } else {
            return false;
        }
    }
    /**
    *
    *
    *
    */
    public function remove($fileplan_id, $position_id, $resid_array) {
        if (!empty($fileplan_id) 
            && !empty($position_id) 
            && count($resid_array) > 0
        ) {
            $db = new Database();
            for($i=0; $i < count($resid_array); $i++) {
                $stmt = $db->query(
                    "DELETE FROM "
                    . FILEPLAN_RES_POSITIONS_TABLE
                    . " WHERE fileplan_id = ?" 
                    . " AND position_id = ?"
                    . " AND res_id = ?"
                    . " AND coll_id = ?"
                ,array($fileplan_id,$position_id,$resid_array[$i]['RES_ID'],$resid_array[$i]['COLL_ID']));

            }
        } else {
            return false;
        }
    }
    /**
    *
    *
    *
    */
    public function whereAmISetted($authorizedFileplans, $coll_id,  $res_id) {
        if (count($authorizedFileplans) > 0 
            && !empty($coll_id) 
            && !empty($res_id) 
        ) {
            $fileplans_array = array();
            for($i=0; $i < count($authorizedFileplans); $i++) {
                array_push($fileplans_array, $authorizedFileplans[$i]['ID']);
            }
            //$fileplans = join(',', $fileplans_array);
            
            $db = new Database();
            $stmt = $db->query(
                    "SELECT fileplan_id, position_id FROM "
                    . FILEPLAN_RES_POSITIONS_TABLE
                    . " WHERE fileplan_id in  (?"
                    . ") AND coll_id = ?"
                    . " AND res_id = ?"
            ,array($fileplans_array,$coll_id,$res_id));
            
            $positions = array();
            if($stmt->rowCount() > 0) {
                
                while($line = $stmt->fetchObject()) {
                 array_push(
                        $positions, 
                        array(
                            'FILEPLAN_ID' => $line->fileplan_id, 
                            'POSITION_ID' => $line->position_id
                        )
                    );
                }
            }
            return $positions;
        } else {
            return false;
        }
    }
    /**
    *
    *
    *
    */
    public function getPositionState($fileplan_id, $position_id, $resid_array) {
        if (!empty($fileplan_id) 
            && !empty($position_id) 
            && count($resid_array) > 0
        ) {
            $db = new Database();
            
            $nb_res = count($resid_array);
            $nb_match = 0;
            
            for($i=0; $i < $nb_res; $i++) {
                
                $stmt = $db->query(
                    "SELECT * FROM "
                    . FILEPLAN_RES_POSITIONS_TABLE
                    . " WHERE fileplan_id = ?"
                    . " AND position_id = ?"
                    . " AND res_id = ?"
                    . " AND coll_id = ?"
                ,array($fileplan_id,$position_id,$resid_array[$i]['RES_ID'],$resid_array[$i]['COLL_ID']));
        
                if($stmt->rowCount() > 0) {
                    $nb_match ++;
                }
            }
            
            if ($nb_match == 0) {
                return 'false';
            } else if ($nb_match == $nb_res){
                return 'true';
            } else {
                return 'partial';
            }           
        } else {
            return false;
        }
    }
}
