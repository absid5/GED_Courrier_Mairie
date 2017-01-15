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
 * Maarch IVS - Validation Error
 * 
 * @package MaarchIVS
 * @author  Cyril Vazquez (Maarch) <cyril.vazquez@maarch.org>
 */
class ValidationError
{
    /**
     * Name of the rule
     * @var string
     */
    public $rule;

    /**
     * The name of the parameter
     * @var string
     */
    public $parameter;

    /**
     * The key of array parameter item
     * @var mixed
     */
    public $key;

    /**
     * The name of the type
     * @var string
     */
    public $type;

    /**
     * The name of the restriction facet and its value
     * @var array
     */
    public $facet;

    /**
     * The value of the request parameter
     * @var string
     */
    public $value;

    /**
     * The error message
     * @var string
     */
    public $message;

}