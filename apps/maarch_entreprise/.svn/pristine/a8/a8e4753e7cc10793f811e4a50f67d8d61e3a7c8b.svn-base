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
 * Maarch IVS - Input Validation Engine
 * 
 * @package MaarchIVS
 * @author  Cyril Vazquez (Maarch) <cyril.vazquez@maarch.org>
 */
class ValidationEngine
{
    /**
     * The configuration handler
     * @var object 
     */
    protected $configurationHandler;

    /**
     * The error mode : log, error, exception
     * @var string
     */
    protected $errorMode = 'silent';

    /**
     * The current rule name
     * @var string
     */
    protected $currentRuleName;

    /**
     * The current parameter
     * @var string
     */
    protected $currentParameterName;

    /**
     * The current array item key
     * @var mixed
     */
    protected $currentItemKey;

    /**
     * The current data type name
     * @var string
     */
    protected $currentDataTypeName;

    /**
     * The current restriction facet
     * @var string
     */
    protected $currentRestrictionFacet;

    /**
     * The current restriction value
     * @var string
     */
    protected $currentRestrictionValue;

    /**
     * The validation rules
     * @var array 
     */
    protected $validationRules;

    /**
     * The data types
     * @var array 
     */
    protected $dataTypes;

    /**
     * The validation errors
     * @var array 
     */
    protected $errors;

    /**
     * The base types
     * @var array 
     */
    public static $baseTypes = array(
        // php types
        'string',
        'integer',
        'int',
        'float',
        'real',
        'double',
        'boolean',
        'bool',
        'email',
        'url',
        'ip',

        // php ctypes
        'alnum',
        'alpha',
        //'cntrl',
        'digit',
        'graph',
        'lower',
        'print',
        //'punct',
        'space',
        'upper',
        //'xdigit',

        // Custom types
        'base64',
        'language',
        'name',

        'array'
    );

    /**
     * Set the configuration handler for the configuration source type
     * @param string $configurationType The encoding of configuration
     *
     * @return void
     **/
    public function setConfigurationHandler($configurationType)
    {
        switch (strtolower($configurationType)) {
            case 'xml':
                require_once ('handler' . DIRECTORY_SEPARATOR . 'XmlConfigurationHandler.php');
                $this->configurationHandler = new XmlConfigurationHandler();
                break;

            case 'json':
                require_once ('handler' . DIRECTORY_SEPARATOR . 'JsonConfigurationHandler.php');
                $this->configurationHandler = new JsonConfigurationHandler();
                break;

            case 'php':
                require_once ('handler' . DIRECTORY_SEPARATOR . 'PhpConfigurationHandler.php');
                $this->configurationHandler = new PhpConfigurationHandler();
                break;
        }

    }

    /**
     * Set the error mode
     * @param string $errorMode An error mode
     */
    public function setErrorMode($errorMode)
    {
        $this->errorMode = $errorMode;
    }

    /**
     * Set the configuration source
     * @param mixed $configuration A MaarchIVS configuration source
     *
     * @return void
     **/
    public function loadConfiguration($configuration)
    {
        if (!$this->configurationHandler->load($configuration)) {
            $this->addError($this->configurationHandler->getError());

            return false;
        }

        return true;
    }

    /**
     * Validate a request
     *
     * @return void
     **/
    public function validate()
    {
        $this->errors = array();

        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['SCRIPT_NAME'];
        $parameters = $_GET;
        $this->validationRules = $this->configurationHandler->getValidationRules($method, $path, $parameters);

        if (count($this->validationRules) == 0) {
            $this->addError("No validation rule");
        } else {
            foreach ($this->validationRules as $validationRule) {
                $this->currentRuleName = $validationRule->name;
                $this->validateRule($validationRule);
            }
        }

        return (count($this->errors) == 0);
    }

    /**
     * Get validation error messages
     *
     * @return array
     **/
    public function getInfo()
    {
        return array(

            'requestMethod' => $_SERVER['REQUEST_METHOD'],
            'requestPath' => $_SERVER['SCRIPT_NAME'],
            'requestParameters' => $_GET,
            'requestBody' => $_REQUEST,
            'validationErrors' => $this->errors,
            'validationRules' => $this->validationRules, 
            'dataTypes' => $this->dataTypes,
        );
    }

    /*
        Non public methods
    */
    protected function validateRule($validationRule)
    {
        foreach ($_REQUEST as $name => $value) {
            $this->currentParameterName = $name;
            $this->currentDataTypeName = null;
            $this->currentItemKey = null;
            $this->currentRestrictionFacet = null;
            $this->currentRestrictionValue = null;
            
            if (empty($value)) {
                continue;
            }

            $currentValidationParameter = null;
            foreach ($validationRule->parameters as $parameterName => $validationParameter) {
                if ($name === $parameterName) {
                    $currentValidationParameter = $validationParameter;
                    break;
                } 

                if (fnmatch($parameterName, $name)) {
                    $currentValidationParameter = $validationParameter;
                    break;
                }
            }

            if (!isset($currentValidationParameter)) {
                switch ($validationRule->mode) {
                    case 'lax':
                        break;

                    case 'unset':
                        unset($_REQUEST[$name]);
                        break;

                    case 'error':
                        $this->addError("Unexpected parameter");
                        break;
                }

                continue;
            }

            $this->validateParameter($currentValidationParameter, $value);
        }
    }

    protected function validateParameter($validationParameter, $value)
    {
        $name = $validationParameter->name;

        // Check fixed value
        if (isset($validationParameter->fixed) && $value != $validationParameter->fixed) {
            $this->addError("Invalid fixed value");

            return;
        }

        // Check type
        $type = $validationParameter->type;

        $this->currentDataTypeName = $type;
        $this->currentItemKey = null;
        
        // Base type, check base + restrictions
        if (in_array($type, self::$baseTypes)) {
            // Validate base type
            if ($this->validateBaseType($type, $value) === false) {
                $this->addError("Invalid value for base type");
            }
        } else {
            // Validate type and base recursively
            if (!$dataType = $this->configurationHandler->getDataType($type)) {
                $this->addError("Undefined data type");

                return;
            }

            $this->validateDataType($dataType, $value);
        }

        // Validate parameter inline restrictions
        if (isset($validationParameter->restriction)) {
            $this->validateRestriction($validationParameter->restriction, $value);
        }
    }

    protected function validateDataType($dataType, $value)
    {
        $this->currentDataTypeName = $dataType->base;
        
        // Add dataType to the list of loaded types for debug
        if (!isset($this->dataTypes[$dataType->name])) {
            $this->dataTypes[$dataType->name] = $dataType;
        }

        // Base type, check base + restrictions
        if (in_array($dataType->base, self::$baseTypes)) {
            // Validate base type
            if ($this->validateBaseType($dataType->base, $value) === false) {
                $this->addError("Invalid value for base type");
            } 
        } else {
            // Validate type and base recursively
            if (!$baseDataType = $this->configurationHandler->getDataType($dataType->base)) {
                $this->addError("Undefined data type");

                return;
            }

            $this->validateDataType($baseDataType, $value);
        }

        $this->currentDataTypeName = $dataType->name;
        
        // Validate inline restrictions
        if (isset($dataType->restriction)) {
            $this->validateRestriction($dataType->restriction, $value);
        }
    }

    protected function validateBaseType($type, $value)
    {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $this->currentItemKey = $key;
                if ($this->validateBaseType($type, $item) === false) {
                    return false;
                }
            }

            return true;
        } else {
            switch($type) {
                case 'string':
                    return true;

                case 'int':
                case 'integer':
                    return filter_var($value, FILTER_VALIDATE_INT);
                    
                case 'float':
                case 'real':
                case 'double':
                    return filter_var($value, FILTER_VALIDATE_FLOAT);

                case 'boolean':
                case 'bool':
                    return !is_null(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));

                case 'email':
                    return filter_var($value, FILTER_VALIDATE_EMAIL);

                case 'ip':
                    return filter_var($value, FILTER_VALIDATE_IP);

                case 'url':
                    return filter_var($value, FILTER_VALIDATE_URL);

                case 'alnum':
                    return ctype_alnum($value);
                case 'alpha':
                    return ctype_alpha($value);
                case 'digit':
                    return ctype_digit($value);
                case 'graph':
                    return ctype_graph($value);
                case 'lower':
                    return ctype_lower($value);
                case 'print':
                    return ctype_print($value);
                case 'space':
                    return ctype_space($value);
                case 'upper':
                    return ctype_upper($value);
                
                case 'base64':
                    return preg_match('/^[A-Z_\-]+$/', $value);
                case 'language':
                    return preg_match('/^[a-zA-Z]{1,8}(\-[a-zA-Z0-9]{1,8})*$/', $value);
                case 'name':
                    return preg_match('/^[a-zA-Z_]+[a-zA-Z0-9_]*$/', $value);

                case 'array':
                    return is_array($value);
            }
        }
    }

    protected function validateRestriction($restriction, $value)
    {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $this->currentItemKey = $key;
                $this->validateRestriction($restriction, $item);
            }
        } else {
            // String validation
            // Validate min length
            if (isset($restriction->minLength) && strlen($value) < $restriction->minLength) {
                $this->currentRestrictionFacet = 'minLength';
                $this->currentRestrictionValue = $restriction->minLength;
                $this->addError("Length id below the minimal length");
            }

            // Validate max length
            if (isset($restriction->maxLength) && strlen($value) > $restriction->maxLength) {
                $this->currentRestrictionFacet = 'maxLength';
                $this->currentRestrictionValue = $restriction->maxLength;
                $this->addError("Length exceeds the maximal length");
            }

            // Validate length
            if (isset($restriction->length) && !$this->validateLength($restriction->length, $value)) {
                $this->currentRestrictionFacet = 'length';
                $this->currentRestrictionValue = implode(', ', $restriction->length);
                $this->addError("Length is not allowed");
            }

            // Validate enumeration
            if (isset($restriction->enumeration) && !in_array($value, $restriction->enumeration)) {
                $this->currentRestrictionFacet = 'enumeration';
                $this->currentRestrictionValue = implode(', ', $restriction->enumeration);
                $this->addError("Value is not allowed");
            }

            // Validate pattern(s)
            if (isset($restriction->pattern) && !$this->validatePattern($restriction->pattern, $value)) {
                $this->currentRestrictionFacet = 'pattern';
                $this->currentRestrictionValue = implode(', ', $restriction->pattern);
                $this->addError("Format is not allowed");
            }

            // Number validation
            // Validate minExclusive
            if (isset($restriction->minExclusive) && $value <= $restriction->minExclusive) {
                $this->currentRestrictionFacet = 'minExclusive';
                $this->currentRestrictionValue = $restriction->minExclusive;
                $this->addError("Value is below the minimal value");
            }

            // Validate minInclusive
            if (isset($restriction->minInclusive) && $value < $restriction->minInclusive) {
                $this->currentRestrictionFacet = 'minInclusive';
                $this->currentRestrictionValue = $restriction->minInclusive;
                $this->addError("Value exceeds the maximal value");
            }

            // Validate maxExclusive
            if (isset($restriction->maxExclusive) && $value >= $restriction->maxExclusive) {
                $this->currentRestrictionFacet = 'maxExclusive';
                $this->currentRestrictionValue = $restriction->maxExclusive;
                $this->addError("Value is below the minimal value");
            }

            // Validate maxInclusive
            if (isset($restriction->maxInclusive) && $value > $restriction->maxInclusive) {
                $this->currentRestrictionFacet = 'maxInclusive';
                $this->currentRestrictionValue = $restriction->maxInclusive;
                $this->addError("Value exceeds the maximal value");
            }

            // Validate total digit positions
            if (isset($restriction->totalDigit) && !$this->validateTotalDigit($restriction->totalDigit, $value)) {
                $this->currentRestrictionFacet = 'totalDigit';
                $this->currentRestrictionValue = implode(', ', $restriction->totalDigit);
                $this->addError("Too many digits");
            }

            // Validate fraction digit (decimal)
            if (isset($restriction->fractionDigit) &&   !$this->validateFractionDigit($restriction->fractionDigit, $value)) {
                $this->currentRestrictionFacet = 'fractionDigit';
                $this->currentRestrictionValue = implode(', ', $restriction->fractionDigit);
                $this->addError("Too many decimal digits");
            }
        }
    }

    protected function validatePattern($patterns, $value)
    {
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
    }

    protected function validateLength($lengths, $value)
    {
        if (in_array(strlen($value), $length)) {
            return true;
        }

        return false;
    }

    protected function validateTotalDigit($totalDigits, $value)
    {
        if (in_array(preg_match_all("/[0-9]/", $value), $totalDigits)) {
            return true;
        }

        return false;
    }

    protected function validateFractionDigit($fractionDigits, $value)
    {
        if (in_array(strlen(substr(strrchr($value, ""), 1)), $fractionDigits)) {
            return true;
        }

        return false;
    }

    protected function addError($message)
    {
        $error = new ValidationError();
        $error->message = $message;

        $error->rule = $this->currentRuleName;
        $error->parameter = $this->currentParameterName;
        $error->key = $this->currentItemKey;
        $error->type = $this->currentDataTypeName;
        $error->facet = array($this->currentRestrictionFacet, $this->currentRestrictionValue);
        $error->value = $_REQUEST[$this->currentParameterName];

        $this->errors[] = $error;

        $errmsg = $message;
        if ($error->parameter) {
            $errmsg .= ' for parameter ' . $error->parameter;
        }
        if ($error->key) {
            $errmsg .= ' at key ' . $error->key;
        }
        if ($error->rule) {
            $errmsg .= ', in rule ' . $error->rule;
        }
        if ($error->type) {
            $errmsg .= ' for type ' . $error->type;
        }
        if ($error->facet) {
            $errmsg .= ', facet ' . $error->facet[0];
        }
        if ($error->facet[1]) {
            $errmsg .= ' expected ' . implode(' or ', (array) $error->facet[1]);
        }

        switch ($this->errorMode) {
            case 'silent':
                break;

            case 'error':
                trigger_error($errmsg, E_USER_ERROR);
                break;

            case 'exception':
                throw new ValidationException($errmsg);
                break;
        }
    }

}