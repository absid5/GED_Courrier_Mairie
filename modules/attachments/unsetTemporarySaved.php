<?php

/*
*   Copyright 2015 Maarch
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
* @brief Unset temporary saved attachments
*
* @file
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/

    $core = new core_tools();
    $core->test_user();

    $db = new Database();

    if ($_REQUEST['mode'] == 'add') {
    	$tableName = "res_attachments";
    } else if($_REQUEST['mode'] == 'edit'){
    	$tableName = "res_version_attachments";
    }

    $db->query("DELETE FROM ".$tableName." WHERE res_id = ? and status = 'TMP' and typist = ?", array($_SESSION['attachmentInfo']['inProgressResId'], $_SESSION['user']['UserId']));
    unset($_SESSION['attachmentInfo']);
