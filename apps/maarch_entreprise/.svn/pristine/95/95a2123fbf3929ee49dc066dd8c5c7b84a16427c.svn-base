<?php

/*
*   Copyright 2008-2012 Maarch
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
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief Contains the docserver controler page
*
*
* @file
* @author Arnaud Veber
* @author Laurent Giovannoni
* @date $date$
* @version $Revision$
* @ingroup admin
*/

//SPECIFIC PROCESS
//columns to show in the list
$showCols = array(
    'docserver_id'       => array(
        'functionFormat' => '',
        'cssStyle'       => ''
    ),
    'device_label'       => array(
        'functionFormat' => '',
        'cssStyle'       => ''
    ), 
    'docserver_type_id'  => array(
        'functionFormat' => '',
        'cssStyle'       => ''
    ),
    'is_readonly'        => array(
        'functionFormat' => 'isBoolean',
        'cssStyle'       => 'text-align: center;'
    ),
    'enabled'            => array(
        'functionFormat' => 'isBoolean',
        'cssStyle'       => 'text-align: center;'
    ),
);
if (isset($_REQUEST['orderField'])) {
    $showCols[$_REQUEST['orderField']]['cssStyle'] .= 'background-image: url(static.php?filename=black_0.1.png); ';
}

//actions to show in the list
$actions = array('create', 'read', 'update', 'delete');

include_once 'apps/' . $_SESSION['config']['app_id'] . '/admin/' 
    . 'docservers/new_docservers_view.php';
