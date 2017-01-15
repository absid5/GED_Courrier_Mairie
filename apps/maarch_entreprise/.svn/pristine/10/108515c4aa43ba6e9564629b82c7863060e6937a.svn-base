<?php
/*
*   Copyright 2008-2014 Maarch
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
* @brief  compute the process limit with the doctype and the admission_date
*
* @file update_process_date.php
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup indexing_searching_mlb
*/
require_once('core/class/class_security.php');
require_once('apps/' . $_SESSION['config']['app_id'] . '/class/class_types.php');

$db = new Database();
$core = new core_tools();
$core->load_lang();
$type = new types();

if (!isset($_REQUEST['type_id']) || empty($_REQUEST['type_id'])) {
    echo "{status : 1, error_txt : '".addslashes(_DOCTYPE . ' ' . _IS_EMPTY)."'}";
    exit();
} else {
    $typeId = $_REQUEST['type_id'];
}

if (!isset($_REQUEST['admission_date']) || empty($_REQUEST['admission_date'])) {
    echo "{status : 1, error_txt : '".addslashes(_ADMISSION_DATE . ' ' . _IS_EMPTY)."'}";
    exit();
} else {
    $admissionDate = $_REQUEST['admission_date'];
}

if (!isset($_REQUEST['priority_id']) || $_REQUEST['priority_id'] == '') {
    echo "{status : 1, error_txt : '".addslashes(_PRIORITY . ' ' . _IS_EMPTY)."'}";
    exit();
} else {
    $priorityId = $_REQUEST['priority_id'];
    if ($_SESSION['mail_priorities_attribute'][$priorityId] <> 'false') {
        $priorityDelay = $_SESSION['mail_priorities_attribute'][$priorityId];
    }
    $wdays = 'workingDay';
    if (isset($_SESSION['mail_priorities_wdays'][$priorityId]) && $_SESSION['mail_priorities_wdays'][$priorityId] == 'false') {
        $wdays = 'calendar';
    }
}

//Process limit process date compute
//Bug fix if delay process is disabled in services
if ($core->service_is_enabled('param_mlb_doctypes')) {

    $stmt = $db->query("SELECT process_delay FROM " 
        . $_SESSION['tablename']['mlb_doctype_ext'] . " WHERE type_id = ?", 
        array($typeId)
    );
    $res = $stmt->fetchObject();
    $delay = $res->process_delay;
}

if ($priorityDelay <> '') {
    $delay = $priorityDelay;
}

if (isset($delay) && $delay > 0) {
    require_once('core/class/class_alert_engine.php');
    $alert_engine = new alert_engine();
    if (isset($admissionDate) && !empty($admissionDate)) {
        $convertedDate = $alert_engine->dateFR2Time(str_replace("-", "/", $admissionDate));
        $date = $alert_engine->WhenOpenDay($convertedDate, $delay, false, $wdays);
    } else {
        $date = $alert_engine->date_max_treatment($delay, false);
    }

    $process_date = functions::dateformat($date, '-');
    $tmpProcessDate = explode(" ", $process_date);
    $date = $tmpProcessDate[0];
    
    echo "{status : 0, process_date : '" . trim($date) . "'}";
    exit();
} else {
    echo "{status : 1}";
    exit();
}
