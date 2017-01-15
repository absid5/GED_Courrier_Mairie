<?php

require_once('core/class/class_core_tools.php');
$Core_Tools = new core_tools;
$Core_Tools->load_lang();
$db = new Database();
$return = '';
$arrayPDO = array();
if ($_REQUEST['society_label'] <> '') {
    $selectDuplicates = "SELECT contact_id, user_id, society, lower(society) as lowsoc, society_short,"
        . "is_corporate_person, lastname, firstname "
        . "from contacts_v2 "
        . "WHERE lower(society) in ("
        . "SELECT lower(society) FROM contacts_v2 GROUP BY lower(society) "
        . "     HAVING Count(lower(society)) > 1 and lower(society) <> '' ) and contact_id <> ? and lower(society) = lower(?) "
        . "order by lower(society)";
    $arrayPDO = array($_REQUEST['contact_id'], $_REQUEST['society_label']);
}
if ($_REQUEST['name'] <> '') {
    $selectDuplicates = "SELECT contact_id, lower(lastname||' '||firstname) as lastname_firstname, society, society_short,"
    . "is_corporate_person, lastname, firstname, title "
    . "from contacts_v2 "
    . "WHERE lower(lastname||' '||firstname) in ("
    . "SELECT lower(lastname||' '||firstname) as lastname_firstname FROM contacts_v2 GROUP BY lastname_firstname "
    . "     HAVING Count(lower(lastname||' '||firstname)) > 1 and lower(lastname||' '||firstname) <> ' ') and contact_id <> ? and lower(lastname||' '||firstname) = ? "
    . "order by lower(lastname||' '||firstname)";
    $arrayPDO = array($_REQUEST['contact_id'], $_REQUEST['name']);
}
if (isset($_REQUEST['contact_id'])) {
    //test if res attached to the contact
    $query = "SELECT res_id FROM res_view_letterbox WHERE (exp_contact_id = ? or dest_contact_id = ?) and status <> 'DEL'";
    $stmt = $db->query($query, array($_REQUEST['contact_id'], $_REQUEST['contact_id']));
    $flagResAttached = false;
    $return_db = $stmt->fetchObject();
    if ($return_db->res_id <> '') {
        $flagResAttached = true;
        $stmt = $db->query($selectDuplicates, $arrayPDO);

        $contactList = array();
        array_push($contactList, "Selectionner un contact");
        while($lineDoubl = $stmt->fetchObject()) {
            $stmt2 = $db->query("SELECT id FROM contact_addresses WHERE contact_id = ?", array($lineDoubl->contact_id));

            $result_address = $stmt2->fetchObject();

            if ($result_address->id <> '') {
                array_push($contactList, $lineDoubl->contact_id);
            }
        }
    }
    if ($flagResAttached) {
        $return .= _RES_ATTACHED . '. ' . _SELECT_CONTACT_TO_REPLACE;
        $return .= ' : <br/>'._NEW_CONTACT.' : <select onchange="loadAddressAttached(this.options[this.selectedIndex].value, '.$_REQUEST['contact_id'].')" id="selectContact_'
            . $_REQUEST['contact_id'] . '" name="selectContact_'
            . $_REQUEST['contact_id'] . '">"';
        for ($cpt=0;$cpt<count($contactList);$cpt++) {
            $return .= '<option value="' . $contactList[$cpt] . '">' 
                . $contactList[$cpt] . '</option>';
        }
        $return .= '</select>';

        $return .= '<br/>' . _NEW_ADDRESS. ' : <select id="selectContactAddress_'
            . $_REQUEST['contact_id'] . '" name="selectContactAddress_'
            . $_REQUEST['contact_id'] . '">"';
        $return .= '</select>';
        $return .= '<br/>';
    }
    $return .= _ARE_YOU_SURE_TO_DELETE_CONTACT;
    if ($flagResAttached) {
        $return .= '&nbsp;<input type="button" class="button" value="' . _YES . '"'
            . ' onclick="deleteContact(' . $_REQUEST['contact_id'] 
            . ', $(\'selectContact_' . $_REQUEST['contact_id'] . '\').value, $(\'selectContactAddress_' . $_REQUEST['contact_id'] . '\').value);" />';
    } else {
        $return .= '&nbsp;<input type="button" class="button" value="' . _YES . '"'
            . ' onclick="deleteContact(' . $_REQUEST['contact_id'] 
            . ', false, false);" />';
    }
    $return .= '&nbsp;<input type="button" class="button" value="' . _NO . '"'
        . ' onclick="new Effect.toggle(\'deleteContactDiv_\'+' 
        . $_REQUEST['contact_id'] . ', \'blind\' , {delay:0.2});" />';
    
    $status = 0;
} else {
    $status = 1;
    $return .= '<td colspan="8" style="background-color: red;">';
        $return .= '<p style="padding: 10px; color: black;">';
            $return .= 'Error loading documents';
        $return .= '</p>';
    $return .= '</td>';
}

echo "{status : " . $status . ", toShow : '" . addslashes($return) . "'}";
exit ();
