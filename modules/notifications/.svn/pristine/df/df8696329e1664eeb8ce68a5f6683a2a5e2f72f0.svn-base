<?php
/******************************************************************************
 BATCH PROCESS EVENT STACK

 Processes events from table event_stack
 
 1 - Groups events by 
    * Recipient
    * Event

 2 - Merge template for each recipient / event
 
 3 - Prepare e-mail and add to e-mail stack
 
******************************************************************************/

/* begin */
// load the config and prepare to process
include('load_process_event_stack.php');
$state = 'LOAD_NOTIFICATIONS';
while ($state <> 'END') {
    if (isset($logger)) {
        $logger->write('STATE:' . $state, 'INFO');
    }
    switch ($state) {
 
    /**********************************************************************/
    /*                          LOAD_NOTIFICATIONS                        */
    /* Load notification defsidentified with notification id              */
    /**********************************************************************/
    case 'LOAD_NOTIFICATIONS' :
        $logger->write("Loading configuration for notification id " . $notificationId, 'INFO');
        $notification = $notifications_controler->getByNotificationId($notificationId);
        if ($notification === FALSE) {
            Bt_exitBatch(1, "Notification '".$notificationId."' not found");
        }
        if ($notification->is_enabled === 'N') {
            Bt_exitBatch(100, "Notification '".$notificationId."' is disabled");
        }
        $state = 'LOAD_EVENTS';
        break;
        
    /**********************************************************************/
    /*                          LOAD_EVENTS                               */
    /* Checking if the stack has notifications to proceed                 */
    /**********************************************************************/
    case 'LOAD_EVENTS' :
        $logger->write("Loading events for notification sid " . $notification->notification_sid, 'INFO');
        $events = $events_controler->getEventsByNotificationSid($notification->notification_sid);
        $totalEventsToProcess = count($events);
        $currentEvent = 0;
        if ($totalEventsToProcess === 0) {
            Bt_exitBatch(0, 'No event to process');
        }
        $logger->write($totalEventsToProcess . ' events to process', 'INFO');
        $tmpNotifs = array();
        $state = 'MERGE_EVENT';
        break;
        
    /**********************************************************************/
    /*                  MERGE_EVENT                                       */
    /* Process event stack to get recipients                              */
    /**********************************************************************/
    case 'MERGE_EVENT' :
        foreach($events as $event) {
            $logger->write("Getting recipients using diffusion type '" .$notification->diffusion_type . "'", 'INFO');
            // Diffusion type specific recipients
            $recipients = array();
            $recipients = $diffusion_type_controler->getRecipients($notification, $event);
            // Diffusion type specific res_id
            $logger->write("Getting document ids using diffusion type '" .$notification->diffusion_type . "'", 'INFO');
            $res_id = false;
            if($event->table_name == $coll_table || $event->table_name == $coll_view) {
                $res_id = $event->record_id;
            } else {
                $res_id = $diffusion_type_controler->getResId($notification, $event);
            } 
            $event->res_id = $res_id;
            
            $nbRecipients = count($recipients);
            
            $logger->write($nbRecipients .' recipients found, checking active and absences', 'INFO');

            if ($notification->diffusion_type === 'dest_entity') {

                for($i=0; $i<$nbRecipients; $i++) {
                    $recipient = $recipients[$i];
                    $entity_id = $recipient->entity_id;
                    $logger->write('Recipient entity ' . $entity_id, 'INFO');
                    if($recipient->enabled != 'Y' || $recipient->mail == '') {
                        $logger->write($entity_id .' is disabled or mail is invalid, this notification will not be send', 'INFO');
                        unset($recipients[$i]);
                        continue;
                    }

                    if(!isset($tmpNotifs[$entity_id])) {
                        $tmpNotifs[$entity_id]['recipient'] = $recipient;
                        $tmpNotifs[$entity_id]['attach'] = $diffusion_type_controler->getAttachFor($notification, $entity_id);
                        $logger->write('Checking if attachment required for ' . $entity_id . ': ' . $tmpNotifs[$entity_id]['attach'], 'INFO');
                    }
                    $tmpNotifs[$entity_id]['events'][] = $event;
                }

            } else {

                for($i=0; $i<$nbRecipients; $i++) {
                    $recipient = $recipients[$i];
                    $user_id = $recipient->user_id;
                    $logger->write('Recipient ' . $user_id, 'INFO');
                    if($recipient->enabled == 'N' || $recipient->status == 'DEL') {
                        $logger->write($user_id .' is disabled or deleted, this notification will not be send', 'INFO');
                        unset($recipients[$i]);
                        continue;
                    }

                    if($recipient->status == 'ABS') {
                        $logger->write($user_id .' is absent, routing to replacent', 'INFO');
                        unset($recipients[$i]);
                        $query = "SELECT us.* FROM users us"
                            . " JOIN user_abs abs ON us.user_id = abs.new_user "
                            . " WHERE abs.user_abs = ? AND us.enabled='Y'";
                        $dbAbs = new Database();
                        $stmt = $dbAbs->query($query, array($user_id));
                        if($stmt->rowCount() > 0) {
                            //$recipient = $dbAbs->fetchObject($user_id);
                            $recipient = $stmt->fetchObject();
                            $user_id = $recipient->user_id;
                            $logger->write($user_id .' is the replacent', 'INFO');
                            $recipients[] = $recipient;
                        } else {
                            $logger->write('No replacent found (probably disabled)', 'INFO');
                            continue;
                        }
                    }
                    if(!isset($tmpNotifs[$user_id])) {
                        $tmpNotifs[$user_id]['recipient'] = $recipient;
                        $tmpNotifs[$user_id]['attach'] = $diffusion_type_controler->getAttachFor($notification, $user_id);
                        $logger->write('Checking if attachment required for ' . $user_id . ': ' . $tmpNotifs[$user_id]['attach'], 'INFO');
                    }
                    $tmpNotifs[$user_id]['events'][] = $event;
                }

            }

            if (count($recipients) === 0) {
                $logger->write('No recipient found' , 'WARNING');
                $events_controler->commitEvent($event->event_stack_sid, "INFO: no recipient found");
            }            
        } 
        $totalNotificationsToProcess = count($tmpNotifs);
        $logger->write($totalNotificationsToProcess .' notifications to process', 'INFO');
        switch($notification->notification_mode) {
        case 'EMAIL':
            $state = 'FILL_EMAIL_STACK';
            break;
        case 'RSS':
            $state = 'FILL_RSS_STACK';
            break;
        }
        break;
        
    /**********************************************************************/
    /*                      FILL_EMAIL_STACK                              */
    /* Merge template and fill notif_email_stack                          */
    /**********************************************************************/
    case 'FILL_EMAIL_STACK' :
        foreach($tmpNotifs as $user_id => $tmpNotif) {
            // Merge template with data and style
            $logger->write('Merging template #' . $notification->template_id 
                . ' ('.count($tmpNotif['events']).' events) for user ' . $user_id, 'INFO');
            
            $params = array(
                'recipient' => $tmpNotif['recipient'],
                'events' => $tmpNotif['events'],
                'notification' => $notification,
                'maarchUrl' => $maarchUrl,
                'maarchApps' => $maarchApps,
                'coll_id' => $coll_id,
                'res_table' => $coll_table,
                'res_view' => $coll_view
            );
            $html = $templates_controler->merge($notification->template_id, $params, 'content');
            if(strlen($html) === 0) {
                foreach($tmpNotif['events'] as $event) {
                    $events_controler->commitEvent($event->event_stack_sid, "FAILED: Error when merging template");
                }
                Bt_exitBatch(8, "Could not merge template with the data");
            }
            
            // Prepare e-mail for stack
            $sender = (string)$mailerParams->mailfrom;
            $recipient_mail = $tmpNotif['recipient']->mail;
            $subject = $notification->description;
            $html = $func->protect_string_db($html, '', 'no');
            $html = str_replace('&amp;', '&', $html);
            $html = str_replace('&', '#and#', $html);
            
            // Attachments
            $attachments = array();
            if($tmpNotif['attach']) {   
                $logger->write('Adding attachments', 'INFO');
                foreach($tmpNotif['events'] as $event) {
                    // Check if event is related to document in collection
                    if($event->res_id != '') {
                        $query = "SELECT "
                            . "ds.path_template ,"
                            . "mlb.path, "
                            . "mlb.filename " 
                            . "FROM ".$coll_view." mlb LEFT JOIN docservers ds ON mlb.docserver_id = ds.docserver_id "
                            . "WHERE mlb.res_id = ?";
                        $stmt = Bt_doQuery($db, $query, array($event->res_id));
                        $path_parts = $stmt->fetchObject();
                        $path = $path_parts->path_template . str_replace('#', '/', $path_parts->path) . $path_parts->filename;
                        $path = str_replace('//', '/', $path);
                        $path = str_replace('\\', '/', $path);
                        $attachments[] = $path;
                    }
                }
                $logger->write(count($attachments) . ' attachment(s) added', 'INFO');
            }
            
            $logger->write('Adding e-mail to email stack', 'INFO');
            if ($_SESSION['config']['databasetype'] == 'ORACLE') {
                $query = "DECLARE
                              vString notif_email_stack.html_body%type;
                            BEGIN
                              vString := '" . $html ."';
                              INSERT INTO " . _NOTIF_EMAIL_STACK_TABLE_NAME . "
                              (sender, recipient, subject, html_body, charset, attachments, module) 
                              VALUES (?, ?, ?, vString, ?, '".implode(',', $attachments)."', 'notifications');
                            END;";
                $arrayPDO = array($sender, $recipient_mail, $subject, $mailerParams->charset);
            } else {

                if(count($attachments) > 0) {
                    $query = "INSERT INTO " . _NOTIF_EMAIL_STACK_TABLE_NAME 
                        . " (sender, recipient, subject, html_body, charset, attachments, module) "
                        . "VALUES (?, ?, ?, ?, ?, '".implode(',', $attachments)."', 'notifications')";
                } else {
                    $query = "INSERT INTO " . _NOTIF_EMAIL_STACK_TABLE_NAME 
                        . " (sender, recipient, subject, html_body, charset, module) "
                        . "VALUES (?, ?, ?, ?, ?, 'notifications')";  
                }
                $arrayPDO = array($sender, $recipient_mail, $subject, $html, $mailerParams->charset);
                
            }
            //$logger->write('SQL query:' . $query, 'DEBUG');
            $db2 = new Database();
            $db2->query($query, $arrayPDO);
            
            foreach($tmpNotif['events'] as $event) {
                $events_controler->commitEvent($event->event_stack_sid, "SUCCESS");
            }
        } 
        $state = 'END';
        break;
    
    /**********************************************************************/
    /*                      FILL_EMAIL_STACK                              */
    /* Merge template and fill notif_email_stack                          */
    /**********************************************************************/
    case 'FILL_RSS_STACK' :
        foreach($tmpNotifs as $user_id => $tmpNotif) {
            // Merge template with data and style
            $logger->write('Adding RSS item ('.count($tmpNotif['events']).' events) for user ' . $user_id, 'INFO');
            
            foreach($tmpNotif['events'] as $event) {
                // Get dynamic url
                $url = str_replace('$id', $event->record_id, $notification->rss_url_template);

                // Inser into stack
                $query = "INSERT INTO " . _NOTIF_RSS_STACK_TABLE_NAME 
                    . " (rss_user_id, rss_event_stack_sid, rss_event_url) "
                    . "VALUES (?, ?, ?)";
                //$logger->write('SQL query:' . $query, 'DEBUG');
                $db2 = new Database();
                $db2->query($query, array($user_id, $event->event_stack_sid, $url));
                $events_controler->commitEvent($event->event_stack_sid, "SUCCESS");
            }
            
            
        } 
        $state = 'END';
        break;
    }

}

$logger->write('End of process', 'INFO');
Bt_logInDataBase(
    $totalEventsToProcess, 0, 'process without error'
);  
//unlink($GLOBALS['lckFile']);
exit($GLOBALS['exitCode']);
