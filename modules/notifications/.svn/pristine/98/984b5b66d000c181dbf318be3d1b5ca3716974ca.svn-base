<?php

/*
*   Copyright 2008-2016 Maarch
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

switch ($request) {
case 'form_content':
    require_once 'core/class/class_request.php' ;

    if($_SESSION['m_admin']['notification']['diffusion_type'] != 'dest_user_visa') { 
        $_SESSION['m_admin']['notification']['diffusion_properties'] = '';
    }
    //Get list of selected status
    $choosen_status_tab = explode(",",$_SESSION['m_admin']['notification']['diffusion_properties']);
    $choosen_status_sring = "'" . implode("','", $choosen_status_tab) . "'";


    //Get list of aff availables status
    $select["status"] = array();
    array_push($select["status"], 'id', 'label_status');
    $request = new request();
    $where = 'id NOT IN (?)';
    $what = '';
    $tab = $request->PDOselect(
        $select, $where, array($choosen_status_tab), $orderstr, $_SESSION['config']['databasetype']
    );
    $status_list = $tab;

	$form_content .= '<p class="sstit">' . _NOTIFICATIONS_DEST_USER_VISA_DIFF_TYPE_WITH_STATUS . '</p>';
    $form_content .= '<table>';
        $form_content .= '<tr>';
            $form_content .= '<td>';
                $form_content .= '<select name="statuseslist[]" id="statuseslist" size="7" ondblclick=\'moveclick(document.frmevent.elements["statuseslist[]"],document.frmevent.elements["diffusion_properties[]"]);\' multiple="multiple" >';
                foreach ($status_list as $this_status) {
                    $form_content .=  '<option value="'.$this_status[0]['value'].'" selected="selected" >'.$this_status[0]['value'].'</option>';
                }
                
                $form_content .= '</select><br/>';
                $form_content .= '<em><a href=\'javascript:selectall(document.forms["frmevent"].elements["statuseslist[]"]);\' >'._SELECT_ALL.'</a></em>';
            $form_content .= '</td>';
            $form_content .= '<td>';
            $form_content .= '<input type="button" class="button" value="'._ADD.'&gt;&gt;" onclick=\'Move(document.frmevent.elements["statuseslist[]"],document.frmevent.elements["diffusion_properties[]"]);\' />';
                $form_content .= '<br />';
                $form_content .= '<br />';
                $form_content .= '<input type="button" class="button" value="&lt;&lt;'._REMOVE.'"  onclick=\'Move(document.frmevent.elements["diffusion_properties[]"],document.frmevent.elements["statuseslist[]"]);selectall(document.forms["frmevent"].elements["diffusion_properties[]"]);\' />';
            $form_content .= '</td>';
            $form_content .= '<td>';
                $form_content .= '<select name="diffusion_properties[]" id="diffusion_properties" size="7" ondblclick=\'moveclick(document.frmevent.elements["diffusion_properties[]"],document.frmevent.elements["statuseslist"]);selectall(document.forms["frmevent"].elements["diffusion_properties[]"]);\' multiple="multiple" >';
                
                foreach ($choosen_status_tab as $this_status) {
                    if($this_status!=''){
                        $form_content .=  '<option value="'.$this_status.'" selected="selected" >'.$this_status.'</option>';
                    }
                }   
                $form_content .= '</select><br/>';
                $form_content .= '<em><a href=\'javascript:selectall(document.forms["frmevent"].elements["diffusion_properties[]"]);\' >'._SELECT_ALL.'</a></em>';
            $form_content .= '</td>';
        $form_content .= '</tr>';
    $form_content .= '</table>';
	break;

case 'recipients':
    $recipients = array();
    $dbRecipients = new Database();
    
    $select = "SELECT distinct us.*";
	$from = " FROM listinstance li JOIN users us ON li.item_id = us.user_id";
    $where = " WHERE li.coll_id = 'letterbox_coll' AND li.item_mode = 'visa' "
        . "and process_date IS NULL ";

    $arrayPDO = array(":recordid" => $event->record_id);
    switch($event->table_name) {
    case 'notes':
        $from .= " JOIN notes ON notes.coll_id = li.coll_id AND notes.identifier = li.res_id";
        $from .= " JOIN res_letterbox lb ON lb.res_id = notes.identifier";
		$where .= " AND notes.id = :recordid AND li.item_id != notes.user_id"
            . " AND ("
                . " notes.id not in (SELECT DISTINCT note_id FROM note_entities) "
                . " OR us.user_id IN (SELECT ue.user_id FROM note_entities ne JOIN "
                . " users_entities ue ON ne.item_id = ue.entity_id WHERE ne.note_id = :recordid)"
            . ")";
        if($notification->diffusion_properties!='') {
            $status_tab=explode(",",$notification->diffusion_properties);
            // $status_str=implode("','",$status_tab); 
            $where .= " AND lb.status in (:statustab)";
            $arrayPDO = array_merge($arrayPDO, array(":statustab" => $status_tab));
        }

        break;
    
    case 'res_letterbox':
    case 'res_view_letterbox':
        $from .= " JOIN res_letterbox lb ON lb.res_id = li.res_id";
        $where .= " AND lb.res_id = :recordid";
        if($notification->diffusion_properties!=''){
            $status_tab=explode(",",$notification->diffusion_properties);
            // $status_str=implode("','",$status_tab); 
            $where .= " AND lb.status in (:statustab)";
            $arrayPDO = array_merge($arrayPDO, array(":statustab" => $status_tab));
        }
        break;
    
    case 'listinstance':
    default:
		$from .= " JOIN res_letterbox lb ON lb.res_id = li.res_id";
        $where .= " AND listinstance_id = :recordid";
        if($notification->diffusion_properties!=''){
            $status_tab=explode(",",$notification->diffusion_properties);
            // $status_str=implode("','",$status_tab); 
            $where .= " AND lb.status in (:statustab)";
            $arrayPDO = array_merge($arrayPDO, array(":statustab" => $status_tab));
        }
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

    $select = "SELECT li.res_id";
    $from = " FROM listinstance li";
    $where = " WHERE li.coll_id = 'letterbox_coll'   ";
    
    $arrayPDO = array(":recordid" => $event->record_id);
    switch($event->table_name) {
    case 'notes':
        $from .= " JOIN notes ON notes.coll_id = li.coll_id AND notes.identifier = li.res_id";
		$from .= " JOIN res_letterbox lb ON lb.res_id = notes.identifier";
		$where .= " AND notes.id = :recordid AND li.item_id != notes.user_id";
        if($notification->diffusion_properties!=''){
            $status_tab=explode(",",$notification->diffusion_properties);
            // $status_str=implode("','",$status_tab); 
            $where .= " AND lb.status in (:statustab)";
            $arrayPDO = array_merge($arrayPDO, array(":statustab" => $status_tab));
        }
        break;
        
    case 'res_letterbox':
    case 'res_view_letterbox':
        $from .= " JOIN res_letterbox lb ON lb.res_id = li.res_id";
        $where .= " AND lb.res_id = :recordid";
        if($notification->diffusion_properties!=''){
            $status_tab=explode(",",$notification->diffusion_properties);
            // $status_str=implode("','",$status_tab); 
            $where .= " AND lb.status in (:statustab)";
            $arrayPDO = array_merge($arrayPDO, array(":statustab" => $status_tab));
        }
        break;
    
    case 'listinstance':
    default:
		$from .= " JOIN res_letterbox lb ON lb.res_id = li.res_id";
        $where .= " AND listinstance_id = :recordid";
        if($notification->diffusion_properties!=''){
            $status_tab=explode(",",$notification->diffusion_properties);
            // $status_str=implode("','",$status_tab); 
            $where .= " AND lb.status in (:statustab)";
            $arrayPDO = array_merge($arrayPDO, array(":statustab" => $status_tab));
        }
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
