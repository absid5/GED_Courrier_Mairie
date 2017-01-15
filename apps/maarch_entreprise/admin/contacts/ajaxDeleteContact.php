<?php

require_once('core/class/class_core_tools.php');
$Core_Tools = new core_tools;
$Core_Tools->load_lang();
$db = new Database();
$return = '';
$status = 0;
if (isset($_REQUEST['contactId'])) {
    $deleteContactQuery = "DELETE FROM contacts_v2 WHERE contact_id = ? ";
    if ($_REQUEST['replacedContactId'] == '') {
        $status = 1;
    } elseif ($_REQUEST['replacedAddressId'] == '') {
        $status = 1;
    } elseif (
        $_REQUEST['replacedContactId'] <> 'false' 
        && $_REQUEST['replacedContactId'] <> ''
        && $_REQUEST['replacedAddressId'] <> 'false' 
        && $_REQUEST['replacedAddressId'] <> ''
    ) {
        $replaceQueryExpContact = "UPDATE mlb_coll_ext SET exp_contact_id = ?, address_id = ? WHERE exp_contact_id = ?";
        $db->query($replaceQueryExpContact, array($_REQUEST['replacedContactId'], $_REQUEST['replacedAddressId'], $_REQUEST['contactId']));
        $replaceQueryDestContact = "UPDATE mlb_coll_ext SET dest_contact_id = ?, address_id = ? WHERE dest_contact_id = ?";
        $db->query($replaceQueryDestContact, array($_REQUEST['replacedContactId'], $_REQUEST['replacedAddressId'], $_REQUEST['contactId']));
        $db->query("UPDATE contacts_res SET contact_id = ?, address_id = ? WHERE contact_id = ?",
            array($_REQUEST['replacedContactId'], $_REQUEST['replacedAddressId'], $_REQUEST['contactId']));
    }
    
    if ($status == 0) {
        $db->query($deleteContactQuery, array($_REQUEST['contactId']));
        $db->query("DELETE FROM contact_addresses WHERE contact_id = ?", array($_REQUEST['contactId']));
    }
}

echo "{status : " . $status . ", toShow : '" . addslashes($return) . "'}";
exit ();
