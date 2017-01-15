<?php

/******************************************************************************/
/* begin */
// load the config and prepare to process
include('load_stack_letterbox_alerts.php');

$state = 'LOAD_ALERTS_NOTIFS';
while ($state <> 'END') {
    if (isset($GLOBALS['logger'])) {
        $GLOBALS['logger']->write('STATE:' . $state, 'INFO');
    }
    switch ($state) {
    /**********************************************************************/
    /*                          LOAD_ALERTS_NOTIFS                               */
    /* Load parameters                                                    */
    /**********************************************************************/
    case 'LOAD_ALERTS_NOTIFS' :
        $query = "SELECT notification_sid, event_id FROM " 
        . _NOTIFICATIONS_TABLE_NAME 
        . " WHERE event_id IN ('alert1', 'alert2') ";
        $stmt = Bt_doQuery($db, $query);
        $totalAlertsToProcess = $stmt->rowCount();
        if ($totalAlertsToProcess === 0) {
            Bt_exitBatch(0, 'No alert parametered');
        }
        $logger->write($totalAlertsToProcess . " notifications parametered for mail alerts", 'INFO');
        $GLOBALS['alert_notifs'] = array();
        while ($alertRecordset = $stmt->fetchObject()) {
            $GLOBALS['alert_notifs'][$alertRecordset->event_id][] = $alertRecordset->notification_sid;
        }
    
        $state = 'LOAD_DOCTYPES';
        break;
    
    /**********************************************************************/
    /*                          LOAD_DOCTYPES                            */
    /* Load parameters                                                   */
    /**********************************************************************/
    case 'LOAD_DOCTYPES' :
        $query = "SELECT * FROM " . $collDoctypeExt;
        $stmt = Bt_doQuery($db, $query);
        $totalDocTypes = $stmt->rowCount();
        $GLOBALS['doctypes'] = array();
        while ($doctypeRecordset = $stmt->fetchObject()) {
            $GLOBALS['doctypes'][$doctypeRecordset->type_id] = $doctypeRecordset;
        }
        $logger->write($totalDocTypes . " document types parametered", 'INFO');
        $state = 'LIST_DOCS';
        break;
    /**********************************************************************/
    /*                          LIST_DOCS                                 */
    /* List the resources to proccess for alarms                          */
    /**********************************************************************/
    case 'LIST_DOCS' :
        $query = "SELECT res_id, type_id, process_limit_date, flag_alarm1, flag_alarm2" 
            . " FROM " . $collView
            . " WHERE closing_date IS null"
            . " AND status NOT IN ('CLO', 'DEL', 'END')"
            . " AND (flag_alarm1 = 'N' OR flag_alarm2 = 'N')"
            . " AND process_limit_date IS NOT NULL";
        $stmt = Bt_doQuery($GLOBALS['db'], $query);
        $totalDocsToProcess = $stmt->rowCount();
        $currentDoc = 0;
        if ($totalDocsToProcess === 0) {
            Bt_exitBatch(0, 'No document to process');
        }
        $logger->write($totalDocsToProcess . " documents to process (i.e. not closed, at least one alert to send)", 'INFO');
        $GLOBALS['docs'] = array();
        while ($DocRecordset = $stmt->fetchObject()) {
            $GLOBALS['docs'][] = $DocRecordset;
        }
        $state = 'A_DOC';
        break;
        
    /**********************************************************************/
    /*                          A_DOC                                     */
    /* Add notification to event_stack for each notif to be sent          */
    /**********************************************************************/
    case 'A_DOC' :
        if($currentDoc < $totalDocsToProcess) {
            $myDoc = $GLOBALS['docs'][$currentDoc];
            $myDoc->process_limit_date = str_replace("-", "/", $db->format_date($myDoc->process_limit_date));
            $logger->write("Processing document #" . $myDoc->res_id, 'INFO');
                
            $myDoctype = $GLOBALS['doctypes'][$myDoc->type_id];
            if(!$myDoctype) {
                Bt_exitBatch(1, 'Unknown document type ' . $myDoc->type_id);
            }
            $logger->write("Document type id is #" . $myDoc->type_id, 'INFO');
            
            // Alert 1 = limit - n days
            if($myDoc->flag_alarm1 != 'Y' && $myDoc->flag_alarm2 != 'Y') {
                $convertedDate = $alert_engine->dateFR2Time($myDoc->process_limit_date);
                $date = $alert_engine->WhenOpenDay($convertedDate, (integer)$myDoctype->delay1, true);
                $process_date = $db->dateformat($date, '-');
                echo PHP_EOL . "$myDoc->process_limit_date - " . (integer)$myDoctype->delay1 . " days : " . $process_date;
                $compare = $alert_engine->compare_date($process_date, date("d-m-Y"));
                echo PHP_EOL . $compare;
                if($compare == 'date2' || $compare == 'equal') {
                    $logger->write("Alarm 1 will be sent", 'INFO');
                    $info = 'Relance 1 pour traitement du document No' . $myDoc->res_id . ' avant date limite.';  
                    if(count($GLOBALS['alert_notifs']['alert1']) > 0) {
                        foreach($GLOBALS['alert_notifs']['alert1'] as $notification_sid) {
                            $query = "INSERT INTO " . _NOTIF_EVENT_STACK_TABLE_NAME
                                . " (notification_sid, table_name, record_id, user_id, event_info"
                                . ", event_date)" 
                                . " VALUES(" . $notification_sid . ", '" 
                                . $collView . "', '" . $myDoc->res_id . "', 'superadmin', '" . $info . "', " 
                                . $db->current_datetime() . ")";
                            Bt_doQuery($db, $query);
                        }
                    }
                    $query = "UPDATE " . $collExt
                        . " SET flag_alarm1 = 'Y', alarm1_date = " . $db->current_datetime()
                        . " WHERE res_id = " . $myDoc->res_id;
                    Bt_doQuery($db, $query);
                }
            }
            // Alert 2 = limit + n days
            if($myDoc->flag_alarm2 != 'Y') {
                $convertedDate = $alert_engine->dateFR2Time($myDoc->process_limit_date);
                $date = $alert_engine->WhenOpenDay($convertedDate, (integer)$myDoctype->delay2);
                $process_date = $db->dateformat($date, '-');
                echo PHP_EOL . "$myDoc->process_limit_date + " . (integer)$myDoctype->delay2 . " days : " . $process_date;
                $compare = $alert_engine->compare_date($process_date, date("d-m-Y"));
                echo PHP_EOL . $compare;
                if($compare == 'date2' || $compare == 'equal') {
                    $logger->write("Alarm 2 will be sent", 'INFO');
                    $info = 'Relance 2 pour traitement du document No' . $myDoc->res_id . ' apres date limite.';  
                    if(count($GLOBALS['alert_notifs']['alert2']) > 0) {
                        foreach($GLOBALS['alert_notifs']['alert2'] as $notification_sid) {
                            $query = "INSERT INTO " . _NOTIF_EVENT_STACK_TABLE_NAME
                                . " (notification_sid, table_name, record_id, user_id, event_info"
                                . ", event_date)" 
                                . " VALUES(" . $notification_sid . ", '" 
                                . $collView . "', '" . $myDoc->res_id . "', 'superadmin', '" . $info . "', " 
                                . $db->current_datetime() . ")";
                            Bt_doQuery($db, $query);
                        }
                    }
                    $query = "UPDATE " . $collExt
                        . " SET flag_alarm1 = 'Y', flag_alarm2 = 'Y', alarm2_date = " . $db->current_datetime()
                        . " WHERE res_id = " . $myDoc->res_id;
                    Bt_doQuery($db, $query);
                }
            }
            $currentDoc++;
            $state = 'A_DOC';
        } else {
            $state = 'END';
        }
        
        break;
    }
}

$GLOBALS['logger']->write('End of process', 'INFO');
Bt_logInDataBase(
    $totalDocsToProcess, 0, 'process without error'
);
//$GLOBALS['db']->disconnect();
unlink($GLOBALS['lckFile']);
exit($GLOBALS['exitCode']);
?>
