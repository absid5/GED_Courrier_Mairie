<?php

/*
*   Copyright 2012-2016 Maarch
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
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @defgroup content_management content_management Module
*/

/**
* @brief   Module content_management :  Module Tools Class
*
* <ul>
*   <li>Set the session variables needed to run the content_management module</li>
*</ul>
*
* @file
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup content_management
*/

/**
* @brief Module content_management : Module Tools Class
*
* <ul>
* <li>Loads the tables used by the content_management</li>
* <li>Set the session variables needed to run the content_management module</li>
*</ul>
*
* @ingroup content_management
*/
abstract class content_management_Abstract
{
    function __construct()
    {
        // parent::__construct();
        $this->index = array();
    }

    /**
    * Loads content_management  tables into sessions vars from the
    * content_management/xml/config.xml
    * Loads content_management log setting into sessions vars from the
    * content_management/xml/config.xml
    */
    public function build_modules_tables()
    {
        if (file_exists($_SESSION['config']['corepath'] . 'custom/'
                        . $_SESSION['custom_override_id']
                        . '/modules/content_management/xml/config.xml')
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom/'
                . $_SESSION['custom_override_id']
                . '/modules/content_management/xml/config.xml';
        } else {
            $path = 'modules/content_management/xml/config.xml';
        }
        $xmlconfig = simplexml_load_file($path);
        // Loads the log setting of the module content_management
        $HISTORY = $xmlconfig->HISTORY;
        $_SESSION['history']['cmadd'] = (string) $HISTORY->cmadd;
        $_SESSION['history']['cmup'] = (string) $HISTORY->cmup;
        $_SESSION['history']['cmdel'] = (string) $HISTORY->cmdel;
    }

    /**
    * Load into session vars all the content_management specific vars :
    * calls private methods
    */
    public function load_module_var_session($userData)
    {
        if (file_exists($_SESSION['config']['corepath'] . 'custom/'
            . $_SESSION['custom_override_id']
            . '/modules/content_management/xml/content_management_features.xml')
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom/'
                . $_SESSION['custom_override_id']
                . '/modules/content_management/xml/content_management_features.xml';
        } else {
            $path = 'modules/content_management/xml/content_management_features.xml';
        }
        $_SESSION['CMFeatures'] = array();
        /*$_SESSION['CMFeatures'] = functions::object2array(
            simplexml_load_file($path)
        );*/
        //functions::show_array($_SESSION['CMFeatures']);
    }
}
