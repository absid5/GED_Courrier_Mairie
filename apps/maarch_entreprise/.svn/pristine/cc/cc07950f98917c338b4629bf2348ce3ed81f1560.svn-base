<?php
/*
 * Copyright (C) 2015 Maarch
 *
 * This file is part of Maarch IVS.
 *
 * Maarch IVS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Maarch IVS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Maarch IVS.  If not, see <http://www.gnu.org/licenses/>.
 */
require_once 'ValidationEngine.php';
require_once 'ValidationRule.php';
require_once 'ValidationParameter.php';
require_once 'ValidationError.php';
require_once 'ValidationException.php';
require_once 'DataType.php';
require_once 'DataTypeRestriction.php';
require_once 'handler' . DIRECTORY_SEPARATOR . 'ConfigurationHandlerInterface.php';
/**
 * Class Maarch IVS
 * 
 * @package MaarchIVS 
 */
class MaarchIVS
{
    /**
     * Constants for configuration encoding
     */
    const CONF_JSON = 'json';
    const CONF_XML  = 'xml';
    const CONF_PHP  = 'php';

    /**
     * Constants for error modes
     */
    const ERRMODE_SILENT    = 'silent';
    const ERRMODE_ERROR     = 'error';
    const ERRMODE_EXCEPTION = 'exception';

    /**
     * The validation engine
     * @var ValidationEngine
     */
    protected static $validationEngine;

    /**
     * Start the validation engine and set configuration
     * @param mixed  $configuration     The configuration in one of the handled encodings
     * @param string $configurationType The name of the configuration encoding
     * 
     * @return bool
     */
    public static function start($configuration, $configurationType)
    {
        self::$validationEngine = new ValidationEngine();

        self::$validationEngine->setConfigurationHandler($configurationType);

        self::$validationEngine->loadConfiguration($configuration);

        return true;
    }

    /**
     * Run the validation engine and return result
     * @param string $errorMode The mode of error reporting
     * 
     * @return bool
     */
    public static function run($errorMode='silent')
    {
        self::$validationEngine->setErrorMode($errorMode);

        return self::$validationEngine->validate();
    }

    /**
     * Get the validation engine errors
     * 
     * @return array
     */
    public static function debug()
    {
        return self::$validationEngine->getInfo();
    }

}