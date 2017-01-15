<?php

/*
*   Copyright 2008-2011 Maarch
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
* @brief List of policies for autocompletion
*
*
* @file
* @author  Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup life_cycle
*/

require_once ('modules/life_cycle/life_cycle_tables_definition.php');
require_once('core/admin_tools.php');
$db = new Database();
$stmt = $db->query("select cycle_id as tag from " . _LC_CYCLES_TABLE_NAME 
    . " where lower(cycle_id) like lower(?) order by cycle_id", array($_REQUEST['what'] . "%"));
At_showAjaxList($stmt, $_REQUEST['what']);
