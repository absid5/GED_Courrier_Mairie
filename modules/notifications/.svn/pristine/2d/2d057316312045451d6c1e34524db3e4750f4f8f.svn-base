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
	$query = "SELECT distinct us.* "
		. " FROM listinstance li JOIN users us ON li.item_id = us.user_id " 
            . " JOIN notes ON notes.coll_id = li.coll_id AND notes.identifier = li.res_id "
		. " WHERE notes.coll_id = 'letterbox_coll' AND notes.id = ? AND item_type='user_id' AND item_mode = 'cc'"
        . " AND li.item_id != notes.user_id";

	$dbRecipients = new Database();
	$stmt = $dbRecipients->query($query, array($event->record_id));
	$recipients = array();
	while($recipient = $stmt->fetchObject()) {
		$recipients[] = $recipient;
	}
	break;
	
case 'attach':
	$attach = false;
	break;
}
?>
