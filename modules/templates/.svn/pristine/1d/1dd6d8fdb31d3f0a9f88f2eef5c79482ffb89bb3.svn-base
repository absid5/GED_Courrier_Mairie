<?php

/*
 *   Copyright 2008-2015 Maarch
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
 *   along with Maarch Framework. If not, see <http://www.gnu.org/licenses/>.
 */

/*
* @requires
*   $res_view   = Name of res view
*   $maarchApps = name of app
*   $maarchUrl  = Url to maarch (root url)
*   $recipient  = recipient of notification
*   $events     = array of events related to letterbox mails
*
* @returns
    [res_letterbox] = record of view + link to detail/doc page
*/

$dbDatasource = new Database();
$contacts = new contacts_v2();

$datasources['recipient'][0] = (array)$recipient;

$datasources['res_letterbox'] = array();
$datasources['contact'] = array();

foreach($events as $event) {
    $res = array();
    $arrayPDO = array();
    
    $select = "SELECT lb.*";
    $from = " FROM ".$res_view." lb ";
    $where = " WHERE ";
    
    switch($event->table_name) {
    case 'notes':
        $from .= " JOIN notes ON notes.identifier = lb.res_id";
        $where .= " notes.id = ? ";
        $arrayPDO = array_merge($arrayPDO,array($event->record_id));
        break;
    
    case 'listinstance':
        $from .= " JOIN listinstance li ON lb.res_id = li.res_id";
        $where .= " li.coll_id = ? AND listinstance_id = ? ";
        $arrayPDO = array_merge($arrayPDO, array($coll_id, $event->record_id));
        break;
        
    case 'res_letterbox':
    case 'res_view_letterbox':
    default:
        $where .= " lb.res_id = ? ";
        $arrayPDO = array_merge($arrayPDO, array($event->record_id));
    }

    $query = $select . $from . $where;
    
    if($GLOBALS['logger']) {
        $GLOBALS['logger']->write($query , 'DEBUG');
    }
    
    // Main document resource from view
    $stmt = $dbDatasource->query($query, $arrayPDO);
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Lien vers la page détail
    $urlToApp = str_replace('//', '/', $maarchUrl . '/apps/' . $maarchApps . '/index.php?');
    $res['linktodoc'] = $urlToApp . 'display=true&page=view_resource_controler&dir=indexing_searching&id=' . $res['res_id'];
    $res['linktodetail'] = $urlToApp . 'page=details&dir=indexing_searching&id=' . $res['res_id'];
    $res['linktoprocess'] = $urlToApp . 'page=view_baskets&module=basket&baskets=MyBasket&directLinkToAction&resid=' . $res['res_id'];

    // Insertion
    $datasources['res_letterbox'][] = $res;
    
	//multicontact
	$stmt = $dbDatasource->query("SELECT * FROM contacts_res WHERE res_id = ? AND contact_id = ? ", array($res['res_id'], $res['contact_id']));
	$datasources['res_letterbox_contact'][] = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($datasources['res_letterbox_contact'][0]['contact_id'] <> '') {
	    // $datasources['contact'] = array();
	    $stmt = $dbDatasource->query("SELECT * FROM view_contacts WHERE contact_id = ? and ca_id = ? ", array($datasources['res_letterbox_contact'][0]['contact_id'], $datasources['res_letterbox_contact'][0]['address_id']));
	    $myContact = $stmt->fetch(PDO::FETCH_ASSOC);
		$myContact['contact_title'] = $contacts->get_civility_contact($myContact['contact_title']);
	    $datasources['contact'][] = $myContact;

	// single Contact
	}else if (isset($res['contact_id']) && isset($res['address_id'])) {
	    $stmt = $dbDatasource->query("SELECT * FROM view_contacts WHERE contact_id = ? and ca_id = ? ", array($res['contact_id'], $res['address_id']));
	    $myContact = $stmt->fetch(PDO::FETCH_ASSOC);
	    $myContact['contact_title'] = $contacts->get_civility_contact($myContact['contact_title']);
	    $datasources['contact'][] = $myContact;
        
	} else {
        $stmt = $dbDatasource->query("SELECT * FROM view_contacts WHERE contact_id = 0");
        $myContact = $stmt->fetch(PDO::FETCH_ASSOC);
        $datasources['contact'][] = $myContact;
    }
}

$datasources['images'][0]['imgdetail'] = $maarchUrl . '/apps/' . $maarchApps . '/img/object.gif';
$datasources['images'][0]['imgdoc'] = $maarchUrl . '/apps/' . $maarchApps . '/img/picto_dld.gif';

?>
