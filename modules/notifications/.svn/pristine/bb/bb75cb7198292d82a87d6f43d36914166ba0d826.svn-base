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

require_once 'core/core_tables.php';
require_once 'core/class/class_request.php';
require_once 'modules/entities/class/EntityControler.php';

switch($request) {
case 'form_content':
	$entities = new EntityControler();
	$entitylist = $entities->getAllEntities();
		
	$form_content .= '<input type="hidden" name="'.$formId.'" id="'.$formId.'" value="entity">';
	$form_content .= '<p class="sstit">' . _NOTIFICATIONS_ENTITY_DIFF_TYPE . '</p>';
	$form_content .= '<table>';
		$form_content .= '<tr>';
			$form_content .= '<td>';
				$form_content .= '<select name="'.$leftList.'[]" id="'.$leftList.'" size="7" ondblclick=\'moveclick(document.frmevent.elements["'.$leftList.'[]"],document.frmevent.elements["'.$rightList.'[]"]);\' multiple="multiple" >';
				foreach ($entitylist as $entity){
					$form_content .=  '<option value="'.$entity->entity_id.'" selected="selected" >'.$entity->entity_label.'</option>';
				}
				
				$form_content .= '</select><br/>';
				$form_content .= '<em><a href=\'javascript:selectall(document.forms["frmevent"].elements["'.$leftList.'[]"]);\' >'._SELECT_ALL.'</a></em>';
			$form_content .= '</td>';
			$form_content .= '<td>';
			$form_content .= '<input type="button" class="button" value="'._ADD.'&gt;&gt;" onclick=\'Move(document.frmevent.elements["'.$leftList.'[]"],document.frmevent.elements["'.$rightList.'[]"]);\' />';
                $form_content .= '<br />';
                $form_content .= '<br />';
                $form_content .= '<input type="button" class="button" value="&lt;&lt;'._REMOVE.'"  onclick=\'Move(document.frmevent.elements["'.$rightList.'[]"],document.frmevent.elements["'.$leftList.'[]"]);selectall(document.forms["frmevent"].elements["'.$rightList.'[]"]);\' />';
			$form_content .= '</td>';
			$form_content .= '<td>';
				$form_content .= '<select name="'.$rightList.'[]" id="'.$rightList.'" size="7" ondblclick=\'moveclick(document.frmevent.elements["'.$rightList.'[]"],document.frmevent.elements["'.$leftList.'"]);selectall(document.forms["frmevent"].elements["'.$rightList.'[]"]);\' multiple="multiple" >';
				$form_content .= '</select><br/>';
				$form_content .= '<em><a href=\'javascript:selectall(document.forms["frmevent"].elements["'.$rightList.'[]"]);\' >'._SELECT_ALL.'</a></em>';
			$form_content .= '</td>';
		$form_content .= '</tr>';
	$form_content .= '</table>';
	break;
	
case 'recipients':
	$entities = "'". str_replace(",", "','", $notification->diffusion_properties) . "'";
	$query = "SELECT distinct us.*" 
		. " FROM users_entities ue "
		. " LEFT JOIN users us ON us.user_id = ue.user_id "
		. " WHERE ue.entity_id in (".$entities.")";
	$dbRecipients = new Database();
	$stmt = $dbRecipients->query($query);
	$recipients = array();
	while($recipient = $stmt->fetchObject()) {
		$recipients[] = $recipient;
	}
	break;

case 'attach':

	$attach = false;
	if ($notification->diffusion_type === 'dest_entity') {
		$tmp_entities = explode(',', $notification->attachfor_properties);
		$attach = in_array($user_id, $tmp_entities);
	} else {
		$entities = "'". str_replace(",", "','", $notification->attachfor_properties) . "'";
		$query = "SELECT user_id"
			. " FROM users_entities"
			. " WHERE entity_id in (".$entities.")"
			. " AND user_id = ?";
		$dbAttach = new Database();
		$stmt = $dbAttach->query($query, array($user_id));
		if($stmt->rowCount() > 0) {
			$attach = true;
		}
	}
	break;
}

?>