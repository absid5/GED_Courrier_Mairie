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
include('load_basket_event_stack.php');
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
        $db = new Database();
        $secCtrl = new SecurityControler();
        $entities = new entities();

        $stmt = $db->query("select * from baskets WHERE flag_notif = 'Y'"
        );

        while ($line = $stmt->fetchObject()) {
            $exceptUsers[$line->basket_id] = array();
            if($line->except_notif != '' || $line->except_notif != null){
                $arrayExceptNotif = explode(',', $line->except_notif);
                $exceptUsers[$line->basket_id]=$arrayExceptNotif;
            }
            $stmt2 = $db->query("select group_id from groupbasket WHERE basket_id = '".$line->basket_id."'");
            //echo $line->basket_id."\n";
            while ($line2 = $stmt2->fetchObject()) {
                //echo "_".$line2->group_id."\n";
                $stmt3 = $db->query("select usergroup_content.user_id from usergroup_content,users WHERE group_id = '".$line2->group_id."' and users.status='OK' and usergroup_content.user_id=users.user_id");
                $baskets_notif = array();

                while ($line3 = $stmt3->fetchObject()) {
                    //echo "__".$line3->user_id."\n";
                    $whereClause = $secCtrl->process_security_where_clause(
                            $line->basket_clause, $line3->user_id
                        );
                    $whereClause = $entities->process_where_clause(
                            $whereClause, $line3->user_id
                        );                    
                    $stmt4 = $db->query("select * from res_view_letterbox ".$whereClause);

                    while ($line4 = $stmt4->fetchObject()) {
                        $stmt6 = $db->query("SELECT user_id FROM notif_event_stack WHERE record_id = '".$line4->res_id."' and  event_info like '%".$line->basket_id."%' and user_id = '".$line3->user_id."'");
                        $line6 = $stmt6->fetchObject();
                        if ($line6) {
                            # code...
                        }else{
                            $info = "Notification [".$line->basket_id."] pour ".$line3->user_id;
                            $stmt5 = $db->query("INSERT INTO notif_event_stack(table_name,notification_sid,record_id,user_id,event_info,event_date) VALUES('res_letterbox','500','".$line4->res_id."','".$line3->user_id."','".$info."',CURRENT_DATE)");                       
                            preg_match_all( '#\[(\w+)]#', $info, $result );
                            $basket_id = $result[1];
                            if(!in_array($basket_id[0], $baskets_notif)){
                                $baskets_notif[] = $basket_id[0];
                            }
                        }
                    }
                }
            
            }
        }
        $logger->write("Loading events for notification sid " . $notification->notification_sid, 'INFO');
        $events = $events_controler->getEventsByNotificationSid('500');
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
            preg_match_all( '#\[(\w+)]#', $event->event_info, $result );
            $basket_id = $result[1];
            $logger->write("Basket => " .$basket_id[0], 'INFO');

            // Diffusion type specific res_id
            //$logger->write("Getting document ids in basket ... '", 'INFO');
            $res_id = false;
            if($event->table_name == $coll_table || $event->table_name == $coll_view) {
                $res_id = $event->record_id;
            } else {
                $res_id = $diffusion_type_controler->getResId($notification, $event);
            } 
            $event->res_id = $res_id;
        
                    $logger->write('Document => ' . $res_id, 'INFO');
                    $user_id = $event->user_id;
                    $logger->write('Recipient => ' . $user_id, 'INFO');


                    if(!isset($tmpNotifs[$user_id])) {
                        $query = "SELECT * from users where user_id = ?";
                        $arrayPDO = array($user_id);
                        $db2 = new Database();
                        $stmt=$db2->query($query, $arrayPDO);
                        $tmpNotifs[$user_id]['recipient'] = $stmt->fetchObject();
                        //$tmpNotifs[$user_id]['recipient'] = $user_id;
                        $tmpNotifs[$user_id]['attach'] = $diffusion_type_controler->getAttachFor($notification, $user_id);
                        //$logger->write('Checking if attachment required for ' . $user_id . ': ' . $tmpNotifs[$user_id]['attach'], 'INFO');
                    }
                    preg_match_all( '#\[(\w+)]#', $event->event_info, $result );
                    $basket_id = $result[1];
                    //print_r($basket_id);
                    $tmpNotifs[$user_id]['baskets'][$basket_id[0]]['events'][] = $event;

                    //print_r($tmpNotifs[$user_id]);
          
        }
        $totalNotificationsToProcess = count($tmpNotifs);
        $logger->write($totalNotificationsToProcess .' notifications to process', 'INFO');

    /**********************************************************************/
    /*                      FILL_EMAIL_STACK                              */
    /* Merge template and fill notif_email_stack                          */
    /**********************************************************************/
        foreach($tmpNotifs as $user_id => $tmpNotif) {
            foreach ($tmpNotif['baskets'] as $key => $basket_list) {
                $basketId = $key;
                $stmt6 = $db->query("SELECT basket_name FROM baskets WHERE basket_id = '".$key."'");
                $line6 = $stmt6->fetchObject();
                $subject = $line6->basket_name;
            
            // Merge template with data and style
            $logger->write('Merging template #' . $notification->template_id 
                . ' to basket '.$subject.' for user ' . $user_id . ' ('.count($basket_list['events']).' documents)', 'INFO');
            
            $params = array(
                'recipient' => $tmpNotif['recipient'],
                'events' => $basket_list['events'],
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
            //$subject = $notification->description;
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
            if(in_array($user_id, $exceptUsers[$basketId])){
                $logger->write('Notification disabled for '.$user_id, 'WARNING');
            }else{

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
            }
            foreach($basket_list['events'] as $event) {
                if(in_array($event->user_id, $exceptUsers[$basketId])){
                    $events_controler->commitEvent($event->event_stack_sid, "WARNING : Notification disabled for ".$event->user_id);  

                }else{
                    $events_controler->commitEvent($event->event_stack_sid, "SUCCESS");
                }
            }
            }
        } 
        $state = 'END';
    }
}

$logger->write('End of process', 'INFO');
Bt_logInDataBase(
    $totalEventsToProcess, 0, 'process without error'
);  
//unlink($GLOBALS['lckFile']);
exit($GLOBALS['exitCode']);
