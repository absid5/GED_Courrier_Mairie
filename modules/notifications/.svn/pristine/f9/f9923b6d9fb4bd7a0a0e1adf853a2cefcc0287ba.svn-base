<?php

/*
*    Copyright 2015 Maarch
*
*  This file is part of Maarch Framework.
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
*    along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief  schedule notifications controler
*
*
* @file
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->test_admin('admin_notif', 'notifications');
$core_tools->load_lang();

require_once 'modules/notifications/class/class_schedule_notifications.php';
$schedule = new ScheduleNotifications();

$checkResult = $schedule->checkCrontab($_POST['data']);

if ($checkResult === 0) {
	$return = $schedule->saveCrontab($_POST['data']);
} else {
	$_SESSION['error'] = _PB_CRON_COMMAND;
}

header(
    'location: ' . $_SESSION['config']['businessappurl']
    . 'index.php?page=schedule_notifications&module=notifications'
);
