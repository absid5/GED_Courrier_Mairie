<?php
/*
* @requires
*   $res_view	= Name of res view
*   $maarchApps = name of app
*   $maarchUrl	= Url to maarch (root url)
* 	$recipient	= recipient of notification
*	$events 	= array of events related to letterbox mails
*
* @returns
	[notes] = detail of notes added
*/

$dbDatasource = new Database();

$datasources['recipient'][0] = (array)$recipient;

$datasources['notes'] = array();

foreach($events as $event) {
	$note = array();
	
	// Query
    switch($event->table_name) {
    case 'notes':
        $query = "SELECT mlb.*, notes.*, users.* "
            . "FROM " . $res_view . " mlb "
            . "JOIN notes on notes.identifier = mlb.res_id "
            . "JOIN users on users.user_id = notes.user_id "
            . "WHERE notes.coll_id = ? "
            . "AND notes.id = ? ";
        $arrayPDO = array($coll_id, $event->record_id);
        break;
    
    case "res_letterbox" :
    case "res_view_letterbox" :
        $query = "SELECT mlb.*, "
            . "notes.*, "
            . "users.* " 
            . "FROM listinstance li JOIN " . $res_view . " mlb ON mlb.res_id = li.res_id "
            . "JOIN notes on li.coll_id=notes.coll_id AND notes.identifier = li.res_id "
            . "JOIN users on users.user_id = notes.user_id "
            . "WHERE li.coll_id = ? "
            . "AND li.item_id = ? "
            . "AND li.item_mode = 'dest' "
            . "AND li.item_type = 'user_id' "
            . "AND li.res_id = ? ";
        $arrayPDO = array($coll_id, $recipient->user_id, $event->record_id);
        break;
    }
    
    if($GLOBALS['logger']) {
        $GLOBALS['logger']->write($query , 'DEBUG');
    }
    
	$stmt = $dbDatasource->query($query, $arrayPDO);
	$note = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Lien vers la page détail
    $urlToApp = str_replace('//', '/', $maarchUrl . '/apps/' . $maarchApps . '/index.php?');
    $note['linktodoc'] = $urlToApp . 'display=true&page=view_resource_controler&dir=indexing_searching&id=' . $note['res_id'];
    $note['linktodetail'] = $urlToApp . 'page=details&dir=indexing_searching&id=' . $note['res_id'];
    $note['linktoprocess'] = $urlToApp . 'page=view_baskets&module=basket&baskets=MyBasket&directLinkToAction&resid=' . $note['res_id'];
    
	// Insertion
	$datasources['notes'][] = $note;
}

$datasources['images'][0]['imgdetail'] = str_replace('//', '/', $maarchUrl . '/apps/' . $maarchApps . '/img/object.gif');
$datasources['images'][0]['imgdoc'] = str_replace('//', '/', $maarchUrl . '/apps/' . $maarchApps . '/img/picto_dld.gif');

?>