<?php

/*
*    Copyright 2008-2015 Maarch
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

switch ($request) {
case 'form_content':
//Affichage du formulaire/interface dans l'administration des notification => Envoi Ajax
    $form_content .= '<p class="sstit">' . _NOTIFICATIONS_COPY_LIST_DIFF_TYPE . '</p>';
    break;

case 'recipients':
    $recipients = array();
    $dbRecipients = new Database();
    $arrayPDO = array(":recordid" => $event->record_id);
    
    // Copy to users
    $select = "SELECT distinct us.*";
    $from = " FROM listinstance li "
        . " JOIN users us ON li.item_id = us.user_id";
    $where = " WHERE li.coll_id = 'letterbox_coll'   AND li.item_mode = 'cc'"
        . " AND item_type='user_id'";
    
    switch($event->table_name) {
    case 'notes':
        $from .= " JOIN notes ON notes.coll_id = li.coll_id AND notes.identifier = li.res_id";
		$from .= " JOIN res_letterbox lb ON lb.res_id = notes.identifier";
        $where .= " AND notes.id = :recordid AND li.item_id != notes.user_id"
            . " AND ("
                . " notes.id not in (SELECT DISTINCT note_id FROM note_entities) "
                . " OR us.user_id IN (SELECT ue.user_id FROM note_entities ne JOIN users_entities ue ON ne.item_id = ue.entity_id WHERE ne.note_id = :recordid)"
            . ")";
		$where .= " AND lb.status not in ('VAL', 'VAL1', 'VAL2', 'QUAL', 'INIT', 'RET', 'DEL', 'END')";
        break;
    
    case 'res_letterbox':
    case 'res_view_letterbox':
        $from .= " JOIN res_letterbox lb ON lb.res_id = li.res_id";
        $where .= " AND lb.res_id = :recordid AND lb.status not in ('VAL', 'VAL1', 'VAL2', 'QUAL', 'INIT', 'RET', 'DEL', 'END')";
        break;
    
    case 'listinstance':
    default:
        $from .= " JOIN res_letterbox lb ON lb.res_id = li.res_id";
        $where .= " AND listinstance_id = :recordid AND lb.status not in ('VAL', 'VAL1', 'VAL2', 'QUAL', 'INIT', 'RET', 'DEL', 'END')";
    }
    
    $query = $select . $from . $where;
        
    if($GLOBALS['logger']) {
        $GLOBALS['logger']->write($query , 'DEBUG');
    }
    
    $stmt = $dbRecipients->query($query, $arrayPDO);
    
    while($recipient = $stmt->fetchObject()) {
        $recipients[] = $recipient;
    }
    
    $arrayPDO = array(":recordid" => $event->record_id);
    // Copy to entities
    $select = "SELECT distinct us.*";
    $from = " FROM listinstance li "
        . " LEFT JOIN users_entities ue ON li.item_id = ue.entity_id "
        . " JOIN users us ON ue.user_id = us.user_id";
    $where = " WHERE li.coll_id = 'letterbox_coll'   AND li.item_mode = 'cc'"
        . " AND item_type='entity_id'";
    
    switch($event->table_name) {
    case 'notes':
        $from .= " JOIN notes ON notes.coll_id = li.coll_id AND notes.identifier = li.res_id";
		$from .= " JOIN res_letterbox lb ON lb.res_id = notes.identifier";
        $where .= " AND notes.id = :recordid AND li.item_id != notes.user_id"
            . " AND ("
                . " notes.id not in (SELECT DISTINCT note_id FROM note_entities) "
                . " OR us.user_id IN (SELECT ue.user_id FROM note_entities ne JOIN users_entities ue ON ne.item_id = ue.entity_id WHERE ne.note_id = :recordid)"
            . ")";
		$where .= " AND lb.status not in ('VAL', 'VAL1', 'VAL2', 'QUAL', 'INIT', 'RET', 'DEL', 'END')";
        break;
    
    case 'res_letterbox':
    case 'res_view_letterbox':
        $from .= " JOIN res_letterbox lb ON lb.res_id = li.res_id";
        $where .= " AND lb.res_id = :recordid AND lb.status not in ('VAL', 'VAL1', 'VAL2', 'QUAL', 'INIT', 'RET', 'DEL', 'END')";
        break;
    
    case 'listinstance':
    default:
		$from .= " JOIN res_letterbox lb ON lb.res_id = li.res_id";
        $where .= " AND listinstance_id = :recordid AND lb.status not in ('VAL', 'VAL1', 'VAL2', 'QUAL', 'INIT', 'RET', 'DEL', 'END')";
    }
    
    $query = $select . $from . $where;
        
    if($GLOBALS['logger']) {
        $GLOBALS['logger']->write($query , 'DEBUG');
    }
    
    $stmt = $dbRecipients->query($query, $arrayPDO);
    
    while($recipient = $stmt->fetchObject()) {
        $recipients[] = $recipient;
    }
    break;
    
case 'attach':
    $attach = false;
    break;
  
case 'res_id':
    $arrayPDO = array(":recordid" => $event->record_id);
    $select = "SELECT li.res_id";
    $from = " FROM listinstance li";
    $where = " WHERE li.coll_id = 'letterbox_coll'   ";
    
    switch($event->table_name) {
    case 'notes':
        $from .= " JOIN notes ON notes.coll_id = li.coll_id AND notes.identifier = li.res_id";
		$from .= " JOIN res_letterbox lb ON lb.res_id = notes.identifier";
        $where .= " AND notes.id = :recordid AND li.item_id != notes.user_id";
		$where .= " AND lb.status not in ('VAL', 'VAL1', 'VAL2', 'QUAL', 'INIT', 'RET', 'DEL', 'END')";
        break;
        
    case 'res_letterbox':
    case 'res_view_letterbox':
        $from .= " JOIN res_letterbox lb ON lb.res_id = li.res_id";
        $where .= " AND lb.res_id = :recordid AND lb.status not in ('VAL', 'VAL1', 'VAL2', 'QUAL', 'INIT', 'RET', 'DEL', 'END')";
        break;
    
    case 'listinstance':
    default:
		$from .= " JOIN res_letterbox lb ON lb.res_id = li.res_id";
        $where .= " AND listinstance_id = :recordid AND lb.status not in ('VAL', 'VAL1', 'VAL2', 'QUAL', 'INIT', 'RET', 'DEL', 'END')";
    }
    
    $query = $query = $select . $from . $where;
    
    if($GLOBALS['logger']) {
        $GLOBALS['logger']->write($query , 'DEBUG');
    }
    
    $dbResId = new Database();
    $stmt = $dbResId->query($query, $arrayPDO);
    $res_id_record = $stmt->fetchObject();
    $res_id = $res_id_record->res_id;
    break;

}
?>
