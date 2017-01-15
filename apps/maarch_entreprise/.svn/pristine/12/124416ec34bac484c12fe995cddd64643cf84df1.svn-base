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
 * Maarch IVS - Validation Parameter
 * 
 * @package MaarchIVS
 * @author  Cyril Vazquez (Maarch) <cyril.vazquez@maarch.org>
 */
class ValidationParameter
{
    /**
     * Name of the parameter
     * @var string
     */
    public $name;

    /**
     * Fixed value
     * @var mixed
     */
    public $fixed;

    /**
     * Type of the parameter
     * @var string
     */
    public $type;

    /**
     * Restrictions on the type
     * @var ValidationTypeRestriction
     */
    public $restriction;

    /**
     * Constructor
     * @param string $name  The name of the parameter
     * @param string $type  The type of data
     * @param string $fixed A fixed value for the parameter
     *
     * @return void
     */
    public function __construct($name, $type, $fixed=null)
    {
        $this->name = $name;

        $this->type = $type;

        $this->fixed = $fixed;
    }

}