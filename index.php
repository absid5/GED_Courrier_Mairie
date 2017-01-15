<?php
/*
*   Copyright 2012-2015 Maarch
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

/****************************************************************************/
/*                                                                          */
/*                                                                          */
/*               THIS PAGE CAN NOT BE OVERWRITTEN IN A CUSTOM         	    */
/*                                                                          */
/*                                                                          */
/* **************************************************************************/

/**
* @brief Maarch root file
*
* @file
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

require_once('core/class/class_functions.php');
include_once('core/init.php');
require_once('core/class/class_core_tools.php');
$func = new functions();
$core = new core_tools();
$_SESSION['custom_override_id'] = $core->get_custom_id();
/**** retrieve HTTP_REQUEST FROM SSO ****/
$_SESSION['HTTP_REQUEST'] = $_REQUEST;
if (!file_exists('installed.lck') && is_dir('install')) {
    header('location: install/index.php');
    exit;
}
if (isset($_GET['origin']) && $_GET['origin'] == 'scan') {
    header('location: apps/'.$_SESSION['businessapps'][0]['appid'].'/reopen.php');
} else {
    $_SESSION['config']['app_id'] = $_SESSION['businessapps'][0]['appid'];
    header('location: apps/'.$_SESSION['config']['app_id']
        . '/index.php?display=true&page=login');
}
