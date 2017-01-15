<?php
/*
*   Copyright 2008-2012 Maarch
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
* @brief   Contains all the functions to use a maarch portal
*
* @file
* @author  Laurent Giovannoni  <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

require_once 'class_functions.php';

/**
* @brief   Contains all the functions to use a maarch portal
*
* @ingroup core
*/
class portal extends functions
{
    /**
    * Loads Maarch portal configuration into sessions  from an xml configuration file (core/xml/config.xml)
    */
    public function build_config() {
        if(!file_exists(dirname(__FILE__) . '/../xml/config.xml')) {
            $this->createXmlCoreConfig();
        }
        $xmlconfig = simplexml_load_file(
            dirname(__FILE__) . '/../xml/config.xml'
        );
        foreach($xmlconfig->CONFIG as $CONFIG) {
            $_SESSION['config']['defaultlang'] = (string) $CONFIG->defaultlanguage;
            if(isset($CONFIG->default_timezone) && !empty($CONFIG->default_timezone)) {
                $_SESSION['config']['default_timezone'] = (string) $CONFIG->default_timezone;
            } else {
                $_SESSION['config']['default_timezone'] = 'Europe/Paris';
            }
        }
        $corePath = str_replace('class', '', dirname(__FILE__));
        $corePath = str_replace('core' . DIRECTORY_SEPARATOR, '', $corePath);
        $_SESSION['config']['corepath'] = $corePath;
        $_SESSION['config']['tmppath'] = $corePath . 'apps' 
            . DIRECTORY_SEPARATOR . 'maarch_entreprise'
            . DIRECTORY_SEPARATOR . 'tmp'. DIRECTORY_SEPARATOR;
        $_SESSION['config']['defaultpage'] = $corePath . 'index.php';
        $_SESSION['config']['coreurl'] = Url::coreurl();
        $i=0;
        foreach($xmlconfig->BUSINESSAPPS as $BUSINESSAPPS) {
            $_SESSION['businessapps'][$i] = array("appid" => (string) $BUSINESSAPPS->appid, "comment" => (string) $BUSINESSAPPS->comment);
            $i++;
        }
    }

    /**
    * Unset session variabless
    */
    public function unset_session() {
        unset($_SESSION['config']);
        unset($_SESSION['businessapps']);
    }
    
    /**
    * Create the xml core config file : core/xml/config.xml
    */
    private function createXmlCoreConfig()
    {
        if (!copy(
                dirname(__FILE__) . '/../xml/config.xml.default', 
                dirname(__FILE__) . '/../xml/config.xml'
            )
        ) {
            echo 'ERROR WITH CREATION OF XML CORE CONFIG FILE IN ' .
                dirname(__FILE__) . '/../xml/config.xml';
            exit;
        }
    }
}
