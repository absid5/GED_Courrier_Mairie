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
* @brief List of templates for autocompletion
*
*
* @file
* @author  Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup templates
*/
require_once 'modules/templates/templates_tables_definition.php';
require_once 'core/admin_tools.php';
$db = new Database();
if ($_SESSION['config']['databasetype'] == 'POSTGRESQL') {
	
    $stmt = $db->query("select template_label as tag from " 
               . _TEMPLATES_TABLE_NAME . " where template_label ilike ? order by template_label", 
               array('%' . $_REQUEST['what'].'%')
            );
} else {
    $stmt = $db->query("select template_label as tag from " 
               . _TEMPLATES_TABLE_NAME . " where template_label like ? order by template_label",
               array($_REQUEST['what'] . '%')
            );
}
At_showAjaxList($stmt, $_REQUEST['what']);
