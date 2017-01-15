<?php

/*
*    Copyright 2008-2015 Maarch
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
* Chrono number Class
*
* Contains all the specific functions of chrono number
*
* @package  Maarch LetterBox 3.0
* @version 3.0
* @since 06/2007
* @license GPL
* @author  Loïc Vinet  <dev@maarch.org>
*
*/

require_once 'core/core_tables.php';

abstract class chrono_Abstract
{
    public function get_chrono_number($resId, $view)
    {
        $db = new Database();
        $stmt = $db->query(
            "SELECT alt_identifier FROM " . $view . " where res_id = ?",
            array($resId)
        );
        $res = $stmt->fetchObject();
        return $res->alt_identifier;
    }
    /**
    * Return an array with all structure readed in chorno.xml
    *
    * @param string $xml_file add or up (a supprimer)
    */
    public function get_structure($idChrono)
    {
        $globality = array();
        $parameters = array();
        $chronoArr = array();

        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps'
            . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
            . 'chrono.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                  . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                  . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
                  . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . 'xml'
                  . DIRECTORY_SEPARATOR . 'chrono.xml';
        } else {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                  . 'chrono.xml';
        }
        $chronoConfig = simplexml_load_file($path);
        if ($chronoConfig) {
            foreach ($chronoConfig -> CHRONO as $chronoTag) {
                if ($chronoTag->id == $idChrono) {
                    $chronoId = (string) $chronoTag->id;
                    $separator = (string) $chronoTag->separator;
                    array_push(
                        $parameters,
                        array(
                            'ID' => $chronoId ,
                            'SEPARATOR' => $separator,
                        )
                    );
                    foreach ($chronoTag->ELEMENT as $item) {
                        $type = $item->type;
                        $value = (string) $item->value;
                        array_push(
                            $chronoArr,
                            array(
                                'TYPE' => $type,
                                'VALUE' => $value,
                            )
                        );
                    }
                }
            }
            array_push(
                $globality,
                array(
                    'PARAMETERS' => $parameters,
                    'ELEMENTS' => $chronoArr,
                )
            );

            return $globality;
        } else {
            echo "chrono::get_structure error";
        }

    }

    public function convert_date_field($chronoArray)
    {
        for ($i = 0; $i <= count($chronoArray); $i ++) {
            if (isset($chronoArray[$i]['TYPE'])
                && $chronoArray[$i]['TYPE'] == "date"
            ) {
                if ($chronoArray[$i]['VALUE'] == "year") {
                    $chronoArray[$i]['VALUE'] = date('Y');
                } else if ($chronoArray[$i]['VALUE'] == "month") {
                    $chronoArray[$i]['VALUE'] = date('m');
                } else if ($chronoArray[$i]['VALUE'] == "day") {
                    $chronoArray[$i]['VALUE'] = date('d');
                } else if ($chronoArray[$i]['VALUE'] == "full_date") {
                    $chronoArray[$i]['VALUE'] = date('dmY');
                }
            }
        }
        return $chronoArray;
    }


    public function convert_maarch_var($chronoArray, $phpVar)
    {
        for ($i = 0; $i <= count($chronoArray); $i ++) {
            if (isset($chronoArray[$i]['TYPE'])
            && $chronoArray[$i]['TYPE'] == "maarch_var"
            ) {
                if ($chronoArray[$i]['VALUE'] == "entity_id") {
                    $chronoArray[$i]['VALUE'] = $phpVar['entity_id'];;
                } else if ($chronoArray[$i]['VALUE'] == "type_id") {
                    $chronoArray[$i]['VALUE'] = $phpVar['type_id'];;
                }
            }
        }
        return $chronoArray;
    }


    public function convert_maarch_forms($chronoArray, $forms)
    {
        for ($i = 0; $i <= count($chronoArray); $i ++) {
            if (isset($chronoArray[$i]['TYPE'])
                && $chronoArray[$i]['TYPE'] == "maarch_form"
            ) {
                foreach ($forms as $key => $value) {
                    if ($chronoArray[$i]['VALUE'] == $key) {
                        $chronoArray[$i]['VALUE'] = $value;
                    }
                }
            }
        }
        return $chronoArray;
    }


    public function convert_maarch_functions($chronoArray, $phpVar = 'false')
    {
        for ($i = 0; $i <= count($chronoArray); $i ++) {
            if (isset($chronoArray[$i]['TYPE'])
                && $chronoArray[$i]['TYPE'] == "maarch_functions"
            ) {
                if ($chronoArray[$i]['VALUE'] == "chr_global") {
                    $chronoArray[$i]['VALUE'] = $this->execute_chrono_for_this_year();
                } else if ($chronoArray[$i]['VALUE'] == "chr_by_entity") {
                    $chronoArray[$i]['VALUE'] = $this->execute_chrono_by_entity(
                        $phpVar['entity_id']
                    );
                } else if ($chronoArray[$i]['VALUE'] == "chr_by_category") {
                    $chronoArray[$i]['VALUE'] = $this->execute_chrono_by_category(
                        $phpVar['category_id']
                    );
                } else if ($chronoArray[$i]['VALUE'] == "category_char") {
                    $chronoArray[$i]['VALUE'] = $this->_executeCategoryChar(
                        $phpVar
                    );
                } else if ($chronoArray[$i]['VALUE'] == "chr_by_res_id") {
                    $chronoArray[$i]['VALUE'] = $this->execute_chrono_by_res_id(
                        $phpVar['res_id']
                    );
                } else if ($chronoArray[$i]['VALUE'] == "chr_by_folder") {
                   $chronoArray[$i]['VALUE'] = $this->execute_chrono_by_folder(
                       $phpVar['folder_id']
                   );
                }
            }
        }
        return $chronoArray;
    }


    public function execute_chrono_for_this_year()
    {
        $db = new Database();
        //Get the crono key for this year
        $stmt = $db->query(
            "SELECT param_value_int FROM " . PARAM_TABLE
            . " WHERE id = 'chrono_global_" . date('Y') . "' "
        );
        if ($stmt->rowCount() == 0) {
            $chrono = $this->_createNewChronoGlobal($db);
        } else {
            $fetch = $stmt->fetchObject();
            $chrono = $fetch->param_value_int;
        }
        $this->_updateChronoForThisYear($chrono, $db);
        return $chrono;
    }

    public function execute_chrono_by_res_id($res_id)
    {
        $db = new Database();
        //Get res_id of document
        if($res_id==''){
            $stmt = $db->query(
                "SELECT res_id FROM res_letterbox ORDER BY res_id DESC LIMIT 1"
            );
        }else{
            $stmt = $db->query(
                "SELECT res_id FROM res_letterbox WHERE res_id=?",
                array($res_id)
            );
        }

        $fetch = $stmt->fetchObject();
        $chrono = $fetch->res_id;
        return $chrono;
    }


    public function execute_chrono_by_entity($entity)
    {
        $db = new Database();
        //Get the crono key for this year
        $stmt = $db->query(
            "SELECT param_value_int FROM " . PARAM_TABLE
            . " WHERE id = ?",
            array('chrono_' . $entity . '_' . date('Y'))
        );
        if ($stmt->rowCount() == 0) {
            $chrono = $this->_createNewChronoForEntity($db, $entity);
        } else {
            $fetch = $stmt->fetchObject();
            $chrono = $fetch->param_value_int;
        }
        $this->_updateChronoForEntity($chrono, $db, $entity);
        return $entity . "/" . $chrono;
        //return $entity;
    }

    public function execute_chrono_by_category($category)
    {
        $db = new Database();
        //Get the crono key for this year
        $stmt = $db->query(
            "SELECT param_value_int FROM " . PARAM_TABLE
            . " WHERE id = ?",
            array('chrono_' . $category . '_' . date('Y'))
        );
        if ($stmt->rowCount() == 0) {
            $chrono = $this->_createNewChronoForCategory($db, $category);
        } else {
            $fetch = $stmt->fetchObject();
            $chrono = $fetch->param_value_int;
        }
        $this->_updateChronoForCategory($chrono, $db, $category);
        return "/" . $chrono;
        //return $category;

    }

    public function execute_chrono_by_folder($folder)
    {
        $db = new Database();
        $folders_system_id = $_SESSION['folderId'];
        //Get the crono key for this folder
        $stmt = $db->query(
                "SELECT param_value_int FROM " . PARAM_TABLE
            . " WHERE id = ? ",
            array('chrono_folder_' . $folders_system_id)
        );
        if ($stmt->rowCount() == 0) {
                $chrono = $this->_createNewChronoForFolder($db, $folder);
        } else {
                $fetch = $stmt->fetchObject();
                $chrono = $fetch->param_value_int;
        }
        $this->_updateChronoForFolder($chrono, $db, $folder);
        return $chrono;
    }

    protected function _executeCategoryChar($phpVar)
    {
        if (! $phpVar['category_id']) {
            return "category::php_var error";
        } else {
            if ($phpVar['category_id'] == "incoming") {
                return "A";
            } else if ($phpVar['category_id'] == "outgoing") {
                return "D";
            } else {
                return '';
            }
        }
    }

    //For global chrono
    protected function _updateChronoForThisYear($actualChrono, $db)
    {
        $actualChrono++;
        $db->query(
            "UPDATE " . PARAM_TABLE . " SET param_value_int = ?  WHERE id = 'chrono_global_" . date('Y') . "' ",
            array($actualChrono)
        );
    }

    protected function _createNewChronoGlobal($db)
    {
        $db->query(
            "INSERT INTO " . PARAM_TABLE . " (id, param_value_int) VALUES "
            . "('chrono_global_" . date('Y') . "', '1')"
        );
        return 1;
    }


    //For specific chrono =>category
    protected function _updateChronoForCategory($actualChrono, $db, $category)
    {
        $actualChrono++;
        $db->query(
            "UPDATE " . PARAM_TABLE . " SET param_value_int = ? WHERE id = ? ",
            array($actualChrono, 'chrono_' . $category . '_' . date('Y'))
        );
    }

    protected function _createNewChronoForCategory($db, $category)
    {
        $db->query(
            "INSERT INTO " . PARAM_TABLE . " (id, param_value_int) VALUES "
            . "(?, '1')",
            array('chrono_' . $category . '_' . date('Y'))
        );
        return 1;
    }


    //For specific chrono =>entity
    protected function _updateChronoForEntity($actualChrono, $db, $entity)
    {
        $actualChrono++;
        $db->query(
            "UPDATE " . PARAM_TABLE . " SET param_value_int = ?  WHERE id = ? ",
            array($actualChrono, 'chrono_' . $entity . '_' . date('Y'))
        );
    }

    protected function _createNewChronoForEntity($db, $entity)
    {
        $db->query(
            "INSERT INTO " . PARAM_TABLE . " (id, param_value_int) VALUES "
            . "(?, '1')",
            array('chrono_' . $entity . '_' . date('Y'))
        );
        return 1;
    }
    
    // For specific chrono => folder
    protected function _updateChronoForFolder($actualChrono, $db, $folder)
    {
        $actualChrono++;
        $db->query(
                "UPDATE " . PARAM_TABLE . " SET param_value_int = ?  WHERE id = ? ",
            array($actualChrono, 'chrono_folder_' . $folder)
        );
    }
    
    protected function _createNewChronoForFolder($db, $folder)
    {
        $db->query(
                "INSERT INTO " . PARAM_TABLE . " (id, param_value_int) VALUES "
            . "(?, '1')",
            array('chrono_folder_' . $folder)
        );
        return 1;
    }
    public function generate_chrono($chronoId, $phpVar='false', $form='false')
    {
        $tmp = $this->get_structure($chronoId);
        $elements = $tmp[0]['ELEMENTS'];
        $parameters = $tmp[0]['PARAMETERS'];

        //Launch any conversion needed for value in the chrono array
        $elements = $this->convert_date_field($elements); //For type date
        $elements = $this->convert_maarch_var($elements, $phpVar); //For php var in maarch
        $elements = $this->convert_maarch_functions($elements, $phpVar);
        $elements = $this->convert_maarch_forms($elements, $form); //For values used in forms

        //Generate chrono string
        $string = $this->convert_in_string($elements, $parameters);
        return $string;
    }


    public function convert_in_string($elements, $parameters)
    {
        $separator = $parameters[0]['SEPARATOR'];

        $thisString = '';
        //Explode each elements of this array
        foreach ($elements as $array) {
            $thisString .= $separator;
            $thisString .= $array['VALUE'];
        }

        //$thisString = substr($thisString, 1);
        return $thisString;
    }
}
