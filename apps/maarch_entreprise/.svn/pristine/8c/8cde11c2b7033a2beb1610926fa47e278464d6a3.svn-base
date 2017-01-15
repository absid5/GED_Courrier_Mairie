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
 * Maarch IVS - Validation Facets
 * 
 * @package MaarchIVS
 * @author  Cyril Vazquez (Maarch) <cyril.vazquez@maarch.org>
 */
class DataTypeRestriction
{
    public $minLength;
    public $maxLength;
    public $minInclusive;
    public $maxInclusive;
    public $minExclusive;    
    public $maxExclusive;
    public $length;
    public $enumeration;
    public $pattern;
    public $totalDigit;
    public $fractionDigit;

    /**
     * Add a facet
     * @param string $name  The facet name
     * @param mixed  $value The facet value
     */
    public function addFacet($name, $value)
    {
        switch ($name) {
            case 'minLength':
            case 'maxLength':
            case 'minInclusive':
            case 'maxInclusive':
            case 'minExclusive':    
            case 'maxExclusive':
                $this->{$name} = $value;  
                break;

            case 'length':
            case 'enumeration':
            case 'pattern':
            case 'totalDigit':
            case 'fractionDigit':
                if (is_array($value)) {
                    $this->{$name} = array_merge($this->{$name}, $value);
                } else {
                    $this->{$name}[] = $value;
                }
                break; 
        }
    }

    /**
     * Extend a base restriction
     * @param DataTypeRestriction $base
     */
    public function extend($base)
    {
        foreach ($base as $name => $value) {
            switch ($name) {
                case 'minLength':
                case 'maxLength':
                case 'minInclusive':
                case 'maxInclusive':
                case 'minExclusive':    
                case 'maxExclusive':
                    if (!isset($this->{$name})) {
                        $this->{$name} = $value;
                    }
                    break;

                case 'length':
                case 'enumeration':
                case 'pattern':
                case 'totalDigit':
                case 'fractionDigit':
                    $this->{$name} = array_merge($this->{$name}, $value); 
                    break; 
            }
        }
    }

}