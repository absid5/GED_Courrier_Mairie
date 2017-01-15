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
 * Maarch IVS - Input Validation System
 * 
 * @package MaarchIVS
 * @author  Cyril Vazquez (Maarch) <cyril.vazquez@maarch.org>
 */
class JsonConfigurationHandler
    implements ConfigurationHandlerInterface
{
    /**
     * The request definitions
     * @var array
     */
    protected $requestDefinitions;

    /**
     * The validation rules
     * @var array
     */
    protected $validationRules;

    /**
     * The conf error
     * @var string
     */
    protected $error;

    /**
     * Load a configuration source
     * @param string $configurationSource The json source file for configuration
     * 
     * @return bool
     */
    public function load($configurationSource) 
    {
        $configurationObject = json_decode(file_get_contents($configurationSource), false);

        if (!$configurationObject) {
            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    break;
                case JSON_ERROR_DEPTH:
                    $this->error = 'The maximum stack depth has been exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $this->error = 'Invalid or malformed JSON';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $this->error = 'Control character error, possibly incorrectly encoded';
                    break;
                case JSON_ERROR_SYNTAX:
                    $this->error = 'Syntax error';
                    break;
                case JSON_ERROR_UTF8:
                    $this->error = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                    break;
                case JSON_ERROR_RECURSION:
                    $this->error = 'One or more recursive references detected in the value to be encoded';
                    break;
                case JSON_ERROR_INF_OR_NAN:
                    $this->error = 'One or more NAN or INF values in the value to be encoded';
                    break;
                case JSON_ERROR_UNSUPPORTED_TYPE:
                    $this->error = 'A value of a type that cannot be encoded was given';
                    break;
                default:
                    $this->error = 'Unknown error';
            }

            if ($this->error) {
                return false;
            }
        }



        // Import request definitions
        $this->requestDefinitions = $configurationObject->requestDefinitions;

        // Import validation rules with an associative index on "name"
        foreach ($configurationObject->validationRules as $validationRule) {
            $key = $validationRule->name;
            $this->validationRules[$key] = $validationRule;
        }

        // Import data types with an associative index on "name"
        foreach ($configurationObject->dataTypes as $dataType) {
            $key = $dataType->name;
            $this->dataTypes[$key] = $dataType;
        }

        return true;
    }

    /**
     * Find the validation rules
     * @param string $method     The http method of request
     * @param string $path       The path of request
     * @param array  $parameters An associative array of request parameters
     * 
     * @return array $validationRule
     */
    public function getValidationRules($method, $path, $parameters=array())
    {
        $validationRules = array();

        foreach ($this->requestDefinitions as $requestDefinition) {
            // Missing validation rule name
            if (!isset($requestDefinition->validationRule)) {
                continue;
            }

            // Wrong method
            if (!in_array($method, explode('|', $requestDefinition->method))) {
                continue;
            }  

            // Wrong path
            if ($requestDefinition->path != $path) {
                continue;
            }

            // Check parameters
            if (isset($requestDefinition->parameters) || !empty($requestDefinition->parameters)) {
                foreach ($requestDefinition->parameters as $parameter) {
                    // Parameter not found
                    if (!array_key_exists($parameter->name, $parameters)) {
                        continue 2;
                    }

                    // Wrong parameter value
                    if (isset($parameter->value) && $parameters[$parameter->name] != $parameter->value) {
                        continue 2;
                    }
                }
            }

            $validationRuleName = $requestDefinition->validationRule;

            if ($validationRule = $this->getValidationRule($validationRuleName)) {
                $validationRules[] = $validationRule;
            }          
        }
        
        return $validationRules;
    }

    /**
     * Get a data type
     * @param string $name The rule name
     * 
     * @return object $dataType
     */
    public function getDataType($name)
    {
        if (!isset($this->dataTypes[$name])) {
            return false;
        }

        $jsonDataType = $this->dataTypes[$name];

        $dataType = new DataType($jsonDataType->name, $jsonDataType->base);

        $dataType->restriction = $this->getRestriction($jsonDataType->restriction);

        return $dataType;
    }

    /**
     * Get the error
     * 
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Find a validation rule
     * @param string $name The validation rule name
     * 
     * @return ValidationRule $validationRule
     */
    public function getValidationRule($name)
    {
        // Missing validation rule definition
        if (!isset($this->validationRules[$name])) {
            return;
        }

        // Get rule
        $jsonValidationRule = $this->validationRules[$name];

        $validationRule = new ValidationRule($name);

        if (isset($jsonValidationRule->mode)) {
            $validationRule->mode = $jsonValidationRule->mode;
        }

        foreach ($jsonValidationRule->parameters as $jsonParameter) {
            $validationRule->parameters[$jsonParameter->name] = $this->getValidationParameter($jsonParameter);
        }

        // Validation parameters extension
        if (isset($jsonValidationRule->extends) && isset($this->validationRules[$jsonValidationRule->extends])) {          
            if ($baseValidationRule = $this->getValidationRule($jsonValidationRule->extends)) {
                $validationRule->extend($baseValidationRule);
            }
        }

        return $validationRule;
    }

    protected function getValidationParameter($jsonParameter)
    {
        $validationParameter = new ValidationParameter($jsonParameter->name, $jsonParameter->type);

        if (isset($jsonParameter->fixed)) {
            $validationParameter->fixed = $jsonParameter->fixed;
        }

        // If base type, use restrictions
        if (in_array($jsonParameter->type, ValidationEngine::$baseTypes) && isset($jsonParameter->restriction)) {
            $validationParameter->restriction = $this->getRestriction($jsonParameter->restriction);
        }

        return $validationParameter;
    }

    protected function getRestriction($jsonRestriction)
    {
        $dataTypeRestriction = new DataTypeRestriction();
        
        foreach ($jsonRestriction as $name => $value) {
            $dataTypeRestriction->addFacet($name, $value);
        }
        
        return $dataTypeRestriction;
    }

}