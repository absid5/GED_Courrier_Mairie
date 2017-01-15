<?php

/*
*    Copyright 2010 Maarch
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
* @defgroup life_cycle life_cycle Module
*/

/**
* @brief   Module life_cycle :  Module Tools Class
*
* <ul>
* <li>Set the session variables needed to run the life_cycle module</li>
*</ul>
*
* @file
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup life_cycle
*/

/**
* @brief   Module life_cycle : Module Tools Class
*
* <ul>
* <li>Loads the tables used by the life_cycle</li>
* <li>Set the session variables needed to run the life_cycle module</li>
*</ul>
*
* @ingroup life_cycle
*/
class life_cycle extends Database
{
    function __construct()
    {
        parent::__construct();
        $this->index = array();
    }

    /**
    * Loads life_cycle  tables into sessions vars from the
    * life_cycle/xml/config.xml
    * Loads life_cycle log setting into sessions vars from the
    * life_cycle/xml/config.xml
    */
    public function build_modules_tables()
    {
        if (file_exists($_SESSION['config']['corepath'].'custom'
                        .DIRECTORY_SEPARATOR.$_SESSION['custom_override_id']
                        .DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR
                        ."life_cycle".DIRECTORY_SEPARATOR
                        ."xml".DIRECTORY_SEPARATOR."config.xml")
        ) {
            $path = $_SESSION['config']['corepath'].'custom'
                .DIRECTORY_SEPARATOR.$_SESSION['custom_override_id']
                .DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."life_cycle"
                .DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR."config.xml";
        } else {
            $path = "modules".DIRECTORY_SEPARATOR."life_cycle"
                .DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR."config.xml";
        }
        $xmlconfig = simplexml_load_file($path);
        //$CONFIG = $xmlconfig->CONFIG;
        // Loads the tables of the module life_cycle
        // into session ($_SESSION['tablename'] array)
        $TABLENAME = $xmlconfig->TABLENAME ;
        $_SESSION['tablename']['lc_cycle'] = (string) $TABLENAME->lc_cycle;
        $_SESSION['tablename']['lc_cycle_seq'] = (string) $TABLENAME
            ->lc_cycle_seq;
        $_SESSION['tablename']['lc_stack'] = (string) $TABLENAME->lc_stack;

        // Loads the log setting of the module life_cycle
        // into session ($_SESSION['history'] array)
        $HISTORY = $xmlconfig->HISTORY;
        $_SESSION['history']['lcadd'] = (string) $HISTORY->lcadd;
        $_SESSION['history']['lcup'] = (string) $HISTORY->lcup;
        $_SESSION['history']['lcdel'] = (string) $HISTORY->lcdel;
    }

    /**
    * Load into session vars all the life_cycle specific vars :
    * calls private methods
    */
    public function load_module_var_session($userData)
    {
        if (file_exists($_SESSION['config']['corepath'].'custom'
                        .DIRECTORY_SEPARATOR.$_SESSION['custom_override_id']
                        .DIRECTORY_SEPARATOR."modules"
                        .DIRECTORY_SEPARATOR."life_cycle"
                        .DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR
                        ."life_cycle_features.xml")
        ) {
            $path = $_SESSION['config']['corepath'].'custom'
                  .DIRECTORY_SEPARATOR.$_SESSION['custom_override_id']
                  .DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR
                  ."life_cycle".DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR
                  ."life_cycle_features.xml";
        } else {
            $path = "modules".DIRECTORY_SEPARATOR."life_cycle"
                  .DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR
                  ."life_cycle_features.xml";
        }
        $_SESSION['lifeCycleFeatures'] = array();
        $_SESSION['lifeCycleFeatures'] = functions::object2array(
            simplexml_load_file($path)
        );
        //functions::show_array($_SESSION['lifeCycleFeatures']);
    }
    
    public function get_indexing_cycles() 
    {
        $cycles = array();
        if (file_exists(
            $_SESSION['config']['corepath'].'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR
            . "modules" . DIRECTORY_SEPARATOR . "life_cycle"
            . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "params.xml"
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                  . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                  . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR
                  . "life_cycle" . DIRECTORY_SEPARATOR . "xml"
                  . DIRECTORY_SEPARATOR . "params.xml";
        } else if (file_exists(
            "modules" . DIRECTORY_SEPARATOR . "life_cycle" . DIRECTORY_SEPARATOR
            . "xml" . DIRECTORY_SEPARATOR . "params.xml"
        )
        ) {
            $path = "modules" . DIRECTORY_SEPARATOR . "life_cycle"
                  . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
                  . "params.xml";
        } else {
            return $cycles;
        }
        $xml = simplexml_load_file($path);
        if (!isset($xml->indexing_cycles)) {
            return $cycles;
        } 
        foreach ($xml->indexing_cycles->cycle as $cycle) {
            $cycles [] = array(
                'policy_id' => (string) $cycle->policy_id,
                'cycle_id'  => (string) $cycle->cycle_id,
            );  
        }
        return $cycles;
    }
    
   public function get_frozen_cycles() 
   {
        $cycles = array();
        if (file_exists(
            $_SESSION['config']['corepath'].'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR
            . "modules" . DIRECTORY_SEPARATOR . "life_cycle"
            . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "params.xml"
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                  . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                  . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR
                  . "life_cycle" . DIRECTORY_SEPARATOR . "xml"
                  . DIRECTORY_SEPARATOR . "params.xml";
        } else if (file_exists(
            "modules" . DIRECTORY_SEPARATOR . "life_cycle" . DIRECTORY_SEPARATOR
            . "xml" . DIRECTORY_SEPARATOR . "params.xml"
        )
        ) {
            $path = "modules" . DIRECTORY_SEPARATOR . "life_cycle"
                  . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
                  . "params.xml";
        } else {
            return $cycles;
        }
        $xml = simplexml_load_file($path);
        if (! isset($xml->frozen_cycles)) {
            return $cycles;
        } 
        foreach ($xml->frozen_cycles->cycle as $cycle) {
            $cycles [] = array(
                'policy_id' => (string) $cycle->policy_id,
                'cycle_id'  => (string) $cycle->cycle_id,
            );  
        }
        return $cycles;
    }
}
