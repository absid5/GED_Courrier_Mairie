<?php
/*********************************************************************************
** Get aditionnal data to merge template
**
*********************************************************************************/
$dbDatasource = new Database();

require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
. DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
. 'class_contacts_v2.php';
$contacts = new contacts_v2();

// Main document resource from view
$datasources['res_letterbox'] = array();
$stmt = $dbDatasource->query("SELECT * FROM " . $res_view . " WHERE res_id = ? ", array($res_id));
$doc = $stmt->fetch(PDO::FETCH_ASSOC);

$date = new DateTime($doc['doc_date']);
$doc['doc_date']=$date->format('d/m/Y');

$admission_date = new DateTime($doc['admission_date']);
$doc['admission_date']=$admission_date->format('d/m/Y');

$creation_date = new DateTime($doc['creation_date']);
$doc['creation_date']=$creation_date->format('d/m/Y');

$process_limit_date = new DateTime($doc['process_limit_date']);
$doc['process_limit_date']=$process_limit_date->format('d/m/Y');

$doc['category_id'] = html_entity_decode($_SESSION['coll_categories']['letterbox_coll'][$doc['category_id']]);

$doc['nature_id'] = $_SESSION['mail_natures'][$doc['nature_id']];

$datasources['res_letterbox'][] = $doc;


//multicontact
$stmt = $dbDatasource->query("SELECT * FROM contacts_res WHERE res_id = ? AND contact_id = ? ", array($doc['res_id'], $res_contact_id));
$datasources['res_letterbox_contact'][] = $stmt->fetch(PDO::FETCH_ASSOC);

if ($datasources['res_letterbox_contact'][0]['contact_id'] <> '') {
        // $datasources['contact'] = array();
    $stmt = $dbDatasource->query("SELECT * FROM view_contacts WHERE contact_id = ? and ca_id = ? ", array($datasources['res_letterbox_contact'][0]['contact_id'], $datasources['res_letterbox_contact'][0]['address_id']));
    $myContact = $stmt->fetch(PDO::FETCH_ASSOC);
    $myContact['contact_title'] = $contacts->get_civility_contact($myContact['contact_title']);
    $myContact['title'] = $contacts->get_civility_contact($myContact['title']);
    $datasources['contact'][] = $myContact;

    // single Contact
} else if (isset($res_contact_id) && isset($res_address_id) && is_numeric($res_contact_id)) {
    $stmt = $dbDatasource->query("SELECT * FROM view_contacts WHERE contact_id = ? and ca_id = ? ", array($res_contact_id, $res_address_id));
    $myContact = $stmt->fetch(PDO::FETCH_ASSOC);
    $myContact['contact_title'] = $contacts->get_civility_contact($myContact['contact_title']);
    $myContact['title'] = $contacts->get_civility_contact($myContact['title']);
    $datasources['contact'][] = $myContact;
    
} else if (!empty($res_contact_id) && !is_numeric($res_contact_id)) {
    $stmt = $dbDatasource->query("SELECT firstname, lastname, user_id, mail, phone, initials FROM users WHERE user_id = ?", [$res_contact_id]);
    $myContact = $stmt->fetch(PDO::FETCH_ASSOC);
    $datasources['contact'][] = $myContact;

} else {
    $stmt = $dbDatasource->query("SELECT * FROM view_contacts WHERE contact_id = 0");
    $myContact = $stmt->fetch(PDO::FETCH_ASSOC);
    $datasources['contact'][] = $myContact;
}

if (isset($datasources['contact'][0]['title']) && $datasources['contact'][0]['title'] == '')
    $datasources['contact'][0]['title'] = $datasources['contact'][0]['contact_title'];
if (isset($datasources['contact'][0]['firstname']) && $datasources['contact'][0]['firstname'] == '')
    $datasources['contact'][0]['firstname'] = $datasources['contact'][0]['contact_firstname'];
if (isset($datasources['contact'][0]['lastname']) && $datasources['contact'][0]['lastname'] == '')
    $datasources['contact'][0]['lastname'] = $datasources['contact'][0]['contact_lastname'];
if (isset($datasources['contact'][0]['function']) && $datasources['contact'][0]['function'] == '')
    $datasources['contact'][0]['function'] = $datasources['contact'][0]['contact_function'];
if (isset($datasources['contact'][0]['other_data']) && $datasources['contact'][0]['other_data'] == '')
    $datasources['contact'][0]['other_data'] = $datasources['contact'][0]['contact_other_data'];

// Notes
$datasources['notes'] = array();
$stmt = $dbDatasource->query("SELECT notes.*, users.firstname, users.lastname FROM notes left join users on notes.user_id = users.user_id WHERE coll_id = ? AND identifier = ? ", array($coll_id, $res_id));
while($note = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $datasources['notes'][] = $note;
}

// Attachments
$datasources['attachments'] = array();
$myAttachment['chrono'] = $chronoAttachment;

//thirds
$stmt = $dbDatasource->query("SELECT * FROM contacts_res WHERE res_id = ? AND mode = ? ", [$doc['res_id'], 'third']);
$datasources['thirds']= [];
$countThird = 1;
while ($third = $stmt->fetchObject()) {
    if (is_numeric($third->contact_id)) {
        $stmt2 = $dbDatasource->query("SELECT * FROM view_contacts WHERE contact_id = ? ", [$third->contact_id]);
        $thirdContact = $stmt2->fetchObject();
        if ($thirdContact) {
            $datasources['thirds'][0]['firstname' . $countThird] = ($thirdContact->contact_firstname ?: $thirdContact->firstname);
            $datasources['thirds'][0]['lastname' . $countThird] = ($thirdContact->contact_lastname ?: $thirdContact->lastname);
        }
    } else {
        $stmt2 = $dbDatasource->query("SELECT * FROM users WHERE user_id = ? ", [$third->contact_id]);
        $thirdContact = $stmt2->fetchObject();
        if ($thirdContact) {
            $datasources['thirds'][0]['firstname' . $countThird] = $thirdContact->firstname;
            $datasources['thirds'][0]['lastname' . $countThird] = $thirdContact->lastname;
        }
    }
    $countThird++;
}

//visa
$stmt = $dbDatasource->query("SELECT * FROM listinstance WHERE res_id = ? AND difflist_type = ? ", [$doc['res_id'], 'VISA_CIRCUIT']);
$datasources['visa']= [];
$countVisa = 1;
while ($visa = $stmt->fetchObject()) {
    $stmt2 = $dbDatasource->query("SELECT * FROM users WHERE user_id = ? ", [$visa->item_id]);
    $visaContact = $stmt2->fetchObject();
    $stmt3 = $dbDatasource->query("SELECT en.entity_id, en.entity_label FROM entities en, users_entities ue WHERE ue.user_id = ? AND primary_entity = ? AND ue.entity_id = en.entity_id", [$visa->item_id, 'Y']);
    $visaEntity = $stmt3->fetchObject();
    if ($visaContact) {
        if ($visa->item_mode == 'sign') {
            $datasources['visa'][0]['firstnameSign'] = $visaContact->firstname;
            $datasources['visa'][0]['lastnameSign'] = $visaContact->lastname;
            $datasources['visa'][0]['entitySign'] = str_replace($visaEntity->entity_id . ': ', '', $visaEntity->entity_label);
        } else {
            $datasources['visa'][0]['firstname' . $countVisa] = $visaContact->firstname;
            $datasources['visa'][0]['lastname' . $countVisa] = $visaContact->lastname;
            $datasources['visa'][0]['entity' . $countVisa] = str_replace($visaEntity->entity_id . ': ', '', $visaEntity->entity_label);
            $countVisa++;
        }
    }

}

//AVIS CICUIT
$stmt = $dbDatasource->query("SELECT * FROM listinstance WHERE res_id = ? AND difflist_type = ?  ORDER BY sequence ASC", [$doc['res_id'], 'AVIS_CIRCUIT']);
$datasources['avis']= [];
$countVisa = 1;
$i = 1;
while ($avis = $stmt->fetchObject()) {
    $stmt2 = $dbDatasource->query("SELECT * FROM users WHERE user_id = ? ", [$avis->item_id]);
    $avisContact = $stmt2->fetchObject();
    $stmt3 = $dbDatasource->query("SELECT en.entity_id, en.entity_label FROM entities en, users_entities ue WHERE ue.user_id = ? AND primary_entity = ? AND ue.entity_id = en.entity_id", [$avis->item_id, 'Y']);
    $stmt4 = $dbDatasource->query("SELECT note_text FROM notes WHERE user_id = ? AND identifier = ? AND note_text LIKE ? ORDER BY date_note ASC", [$avis->item_id, $doc['res_id'], '[Avis n°%']);

    $avisEntity = $stmt3->fetchObject();
    $avisContent = $stmt4->fetchObject();
    if ($avisContact) {
        if ($avis->item_mode == 'avis') {
            $datasources['avis'][0]['firstname'.$i] = $avisContact->firstname;
            $datasources['avis'][0]['lastname'.$i] = $avisContact->lastname;
            $datasources['avis'][0]['entity'.$i] = str_replace($avisEntity->entity_id . ': ', '', $avisEntity->entity_label);
            if($avisContent){
                $datasources['avis'][0]['note'.$i] = $avisContent->note_text;
            }
            
        }
    }
    $i++;
}

// Transmissions
$datasources['transmissions'] = [];
if (isset($_SESSION['transmissionContacts'])) {

    if (isset($_SESSION['upfileTransmissionNumber']) && $_SESSION['transmissionContacts'][$_SESSION['upfileTransmissionNumber']]) {
        $curNb = $_SESSION['upfileTransmissionNumber'];
        foreach($_SESSION['transmissionContacts'][$curNb] as $key => $value) {
            if ($key == 'title')
                $datasources['transmissions'][0]['currentContact_' . $key] = $contacts->get_civility_contact($value);
            else
                $datasources['transmissions'][0]['currentContact_' . $key] = $value;
        }
    }

    for ($nb = 1; $_SESSION['transmissionContacts'][$nb]; $nb++) {
        foreach($_SESSION['transmissionContacts'][$nb] as $key => $value) {
            if ($key == 'title')
                $datasources['transmissions'][0][$key . $nb] = $contacts->get_civility_contact($value);
            else
                $datasources['transmissions'][0][$key . $nb] = $value;
        }
    }
}

$img_file_name = $_SESSION['config']['tmppath'].$_SESSION['user']['UserId'].time().rand()."_barcode_attachment.png";

require_once('apps/maarch_entreprise/tools/pdfb/barcode/pi_barcode.php');
$objCode = new pi_barcode();

$objCode->setCode($chronoAttachment);
$objCode->setType('C128');
$objCode->setSize(30, 50);

$objCode->setText($chronoAttachment);

$objCode->hideCodeType();

$objCode->setFiletype('PNG');               

$objCode->writeBarcodeFile($img_file_name);

$myAttachment['chronoBarCode'] = $img_file_name;
$datasources['attachments'][] = $myAttachment;
