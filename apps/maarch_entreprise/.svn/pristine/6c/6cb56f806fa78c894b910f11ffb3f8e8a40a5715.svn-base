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
 * Maarch IVS - Validation Rule
 * 
 * @package MaarchIVS
 * @author  Cyril Vazquez (Maarch) <cyril.vazquez@maarch.org>
 */
class ValidationRule
{
    /**
     * Name of the rule
     * @var string
     */
    public $name;

    /**
     * Missing parameter behaviour
     * @var string
     */
    public $mode;

    /**
     * Parameters
     * @var array
     */
    public $parameters;

    /**
     * Constructor
     * @param string $name The name of the rule
     * @param string $mode The missing parameter behaviour
     *
     * @return void
     **/
    public function __construct($name, $mode='error')
    {
        $this->name = $name;

        $this->mode = $mode;
    }

    /**
     * Add a parameter
     * @param string $name The parameter name
     *
     * @return ValidationParameter
     **/
    public function addParameter($name)
    {
        $parameter = new ValidationParameter($name);

        $this->parameters[$name] = $parameter;

        return $parameter;
    }

    /**
     * Extend a base validation rule
     * @param ValidationRule $base
     */
    public function extend($base)
    {
        foreach ($base->parameters as $name => $baseParameter) {
            // New parameter added
            if (!isset($this->parameters[$name])) {
                $this->parameters[$name] = $baseParameter;
            }
        }
    }

}