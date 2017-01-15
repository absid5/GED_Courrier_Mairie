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
/**
 * Interface for Maarch IVS configuration handlers
 * 
 * @package MaarchIVS
 * @author  Cyril Vazquez (Maarch) <cyril.vazquez@maarch.org>
 */
interface ConfigurationHandlerInterface
{

    /**
     * Load a configuration source
     * @param mixed $configurationSource The source for configuration
     * 
     * @return bool
     */
    public function load($configurationSource);

    /**
     * Get the list of validation rules to apply
     * @param string $method     The http method of request
     * @param string $path       The path of request
     * @param array  $parameters An associative array of request parameters
     * 
     * @return array The validationRule objects to apply
     */
    public function getValidationRules($method, $path, $parameters=array());

    /**
     * Get a validation rule 
     * @param string $name The name of the rule
     * 
     * @return ValidationRule The validationRule objects to apply
     */
    public function getValidationRule($name);

    /**
     * Get a data type
     * @param string $name The rule name
     * 
     * @return object $dataType
     */
    public function getDataType($name);
}