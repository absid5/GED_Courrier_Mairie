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
require_once 'core/class/usergroups_controler.php';

switch($request) {
case 'form_content':
	$UsrGrpCtl = new usergroups_controler();
	$usergrouplist = $UsrGrpCtl->getAllUsergroups();
	
	$form_content .= '<input type="hidden" name="'.$formId.'" id="'.$formId.'" value="group">';
	$form_content .= '<p class="sstit">' . _NOTIFICATIONS_GROUP_DIFF_TYPE . '</p>';
	$form_content .= '<table>';
		$form_content .= '<tr>';
			$form_content .= '<td>';
				$form_content .= '<select name="'.$leftList.'[]" id="'.$leftList.'" size="7" ondblclick=\'moveclick(document.frmevent.elements["'.$leftList.'[]"],document.frmevent.elements["'.$rightList.'[]"]);\' multiple="multiple" >';
				foreach ($usergrouplist as $usergroup) {
					$form_content .=  '<option value="'.$usergroup->group_id.'" selected="selected" >'.$usergroup->group_desc.'</option>';
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
	$groups = "'". str_replace(",", "','", $notification->diffusion_properties) . "'";
	$query = "SELECT distinct us.*" 
		. " FROM usergroup_content ug "
		. "	LEFT JOIN users us ON us.user_id = ug.user_id" 
		. " WHERE ug.group_id in (".$groups.")";
	$dbRecipients = new Database();
	$stmt = $dbRecipients->query($query);
	$recipients = array();
	while($recipient = $stmt->fetchObject()) {
		$recipients[] = $recipient;
	}
	break;

case 'attach':
	$groups = "'". str_replace(",", "','", $notification->attachfor_properties) . "'";
	$query = "SELECT user_id" 
		. " FROM usergroup_content"
		. " WHERE group_id in (".$groups.")"
		. " AND user_id = ?";
	$attach = false;
	$dbAttach = new Database();
	$stmt = $dbAttach->query($query, array($user_id));
	if($stmt->rowCount() > 0) {
		$attach = true;
	}
	break;

}


