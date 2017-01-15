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
    $form_content .= '<p class="sstit">' . _NOTIFICATIONS_CONTACT_DIFF_TYPE . '</p>';
    $form_content .= '<input type="hidden" name="'.$formId.'" id="'.$formId.'" value="contact">';
    break;

case 'recipients':
    $query = "SELECT contact_id as user_id, contact_email as mail"
        . " FROM res_view_letterbox " 
        . " WHERE (contact_email is not null or contact_email <> '') and res_id = ?";
    $dbRecipients = new Database();
    $stmt = $dbRecipients->query($query, array($event->record_id));
    $recipients = array();
    while($recipient = $stmt->fetchObject()) {
        $recipients[] = $recipient;
    }
    break;

case 'attach':
	$query = "SELECT contact_id as user_id, contact_email as mail"
        . " FROM res_view_letterbox " 
        . " WHERE (contact_email is not null or contact_email <> '') and res_id = ?";
	$attach = false;
	$dbAttach = new Database();
	$stmt = $dbAttach->query($query, array($event->record_id));
	if($stmt->rowCount() > 0) {
		$attach = true;
	}
	break;
}
