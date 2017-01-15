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
class XmlConfigurationHandler
    implements ConfigurationHandlerInterface
{
    /**
     * The configuration
     * @var DOMDocument
     */
    protected $configurationDocument;

    /**
     * The configuration
     * @var DOMXpath
     */
    protected $configurationXPath;

    /**
     * Load a configuration source
     * @param string $configurationSource The xml source file for configuration
     * 
     * @return bool
     */
    public function load($configurationSource) 
    {
        $this->configurationDocument = new DOMDocument();

        $this->configurationDocument->load($configurationSource);

        $this->configurationDocument->xinclude();

        $this->configurationXPath = new DOMXPath($this->configurationDocument);

        return true;
    }

    /**
     * Get the validation rule associated with the request
     * @param string $method     The http method of request
     * @param string $path       The path of request
     * @param array  $parameters An associative array of request parameters
     * 
     * @return array $validationRule
     */
    public function getValidationRules($method, $path, $parameters=array())
    {
        $validationRules = array();
        $query = "//requestDefinition[contains(@method, '$method') and contains('$path', @path)]";
        $requestDefinitionElements = $this->configurationXPath->query($query);

        foreach ($requestDefinitionElements as $requestDefinitionElement) {
            if (!$requestDefinitionElement->hasAttribute('validationRule')) {
                continue;
            }

            $parameterElements = $requestDefinitionElement->getElementsByTagName('parameter');

            if ($parameterElements->length) {
                foreach ($parameterElements as $parameterElement) {
                    $name = (string) $parameterElement->getAttribute('name');
                    $value = (string) $parameterElement->getAttribute('value');
                    if (!array_key_exists($name, $parameters)) {
                        continue 2;
                    }

                    if (!empty($value) && $parameters[$name] != $value) {
                        continue 2;
                    }
                }
            }

            $validationRuleName = $requestDefinitionElement->getAttribute('validationRule');
            if ($validationRule = $this->getValidationRule($validationRuleName)) {
                $validationRules[] = $validationRule;
            }
        }

        return $validationRules;
    }

    /**
     * Get a validation rule 
     * @param string $name The name of the rule
     * 
     * @return ValidationRule The validationRule objects to apply
     */
    public function getValidationRule($name)
    {
        if (!$validationRuleElement = $this->configurationXPath->query("//validationRule[@name='$name']")->item(0)) {
            return;
        }

        $validationRule = new ValidationRule($name);

        if ($validationRuleElement->hasAttribute('mode')) {
            $validationRule->mode = $validationRuleElement->getAttribute('mode');
        }

        foreach ($validationRuleElement->getElementsByTagName('parameter') as $parameterElement) {
            $validationParameter = $this->getValidationParameter($parameterElement);
            $validationRule->parameters[$validationParameter->name] = $validationParameter;
        }

        if ($validationRuleElement->hasAttribute('extends')) {
            if ($baseValidationRule = $this->getValidationRule($validationRuleElement->getAttribute('extends'))) {
                $validationRule->extend($baseValidationRule);
            }
        }

        return $validationRule;
    }

    /**
     * Get a data type
     * @param string $name The rule name
     * 
     * @return object $dataType
     */
    public function getDataType($name)
    {
        if (!$dataTypeElement = $this->configurationXPath->query("//dataType[@name='$name']")->item(0)) {
            return;
        }

        $name = $dataTypeElement->getAttribute('name');
        $base = $dataTypeElement->getAttribute('base');

        $dataType = new DataType($name, $base);

        $dataType->restriction = $this->getRestriction($dataTypeElement);

        return $dataType;
    }

    protected function getValidationParameter($parameterElement)
    {
        $name = $parameterElement->getAttribute('name');
        $type = $parameterElement->getAttribute('type');
        $validationParameter = new ValidationParameter($name, $type);
                               
        if ($parameterElement->hasAttribute('fixed')) {
            $validationParameter->fixed = $parameterElement->getAttribute('fixed');
        }
        
        // If base type, use restrictions
        if (in_array($type, ValidationEngine::$baseTypes) && $parameterElement->getElementsByTagName('*')->length) {
            $validationParameter->restriction = $this->getRestriction($parameterElement);
        }

        return $validationParameter;
    }

    protected function getRestriction($element)
    {
        $dataTypeRestriction = new DataTypeRestriction();
        
        foreach ($element->getElementsByTagName('*') as $facetElement) {
            $name = $facetElement->nodeName;
            $value = $facetElement->getAttribute('value');
            $dataTypeRestriction->addFacet($name, $value);
        }

        return $dataTypeRestriction;
    }

}