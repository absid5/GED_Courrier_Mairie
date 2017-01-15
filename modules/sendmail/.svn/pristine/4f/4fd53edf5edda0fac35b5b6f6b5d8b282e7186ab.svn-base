<?php
/*
*
*    Copyright 2013 Maarch
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

/**
* @brief    Script to return ajax result
*
* @file     sendmail_ajax_content.php
* @author   Yves Christian Kpakpo <dev@maarch.org>
* @date     $date$
* @version  $Revision$
* @ingroup  sendmail
*/

require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php";
require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php";
require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
    . 'class_indexing_searching_app.php';
require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
    . 'class_users.php';

require_once 'modules/sendmail/sendmail_tables.php';
require_once "modules" . DIRECTORY_SEPARATOR . "sendmail" . DIRECTORY_SEPARATOR
    . "class" . DIRECTORY_SEPARATOR . "class_modules_tools.php";
require_once 'modules/sendmail/class/class_email_signatures.php';
    
$core_tools     = new core_tools();
$request        = new request();
$sec            = new security();
$is             = new indexing_searching_app();
$users_tools    = new class_users();
$sendmail_tools = new sendmail();
$db             = new Database();

$parameters = '';

if (isset($_REQUEST['mode']) && !empty($_REQUEST['mode'])) {
    $mode = $_REQUEST['mode'];
} else {
    echo _ERROR_IN_SENDMAIL_FORM_GENERATION;
    exit;
}

//Identifier of the element wich is noted
$identifier = '';
if (isset($_REQUEST['identifier']) && ! empty($_REQUEST['identifier'])) {
    $identifier = trim($_REQUEST['identifier']);
}

//Collection
if (isset($_REQUEST['coll_id']) && ! empty($_REQUEST['coll_id'])) {
    $collId = trim($_REQUEST['coll_id']);
    $parameters .= '&coll_id='.$_REQUEST['coll_id'];
	$view = $sec->retrieve_view_from_coll_id($collId);
    $table = $sec->retrieve_table_from_coll($collId);
}

//Keep some origin parameters
if (isset($_REQUEST['size']) && !empty($_REQUEST['size'])) $parameters .= '&size='.$_REQUEST['size'];
if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {
    $parameters .= '&order='.$_REQUEST['order'];
    if (isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field'])) $parameters .= '&order_field='.$_REQUEST['order_field'];
}
if (isset($_REQUEST['what']) && !empty($_REQUEST['what'])) $parameters .= '&what='.$_REQUEST['what'];
if (isset($_REQUEST['template']) && !empty($_REQUEST['template'])) $parameters .= '&template='.$_REQUEST['template'];
if (isset($_REQUEST['start']) && !empty($_REQUEST['start'])) $parameters .= '&start='.$_REQUEST['start'];

//Keep the origin
$origin = '';
if (isset($_REQUEST['origin']) && !empty($_REQUEST['origin'])) {
    //
    $origin = $_REQUEST['origin'];
}

//Path to actual script
$path_to_script = $_SESSION['config']['businessappurl']
    ."index.php?display=true&module=sendmail&page=sendmail_ajax_content&identifier="
    .$identifier."&origin=".$origin.$parameters;
    
$core_tools->load_lang();
$core_tools->load_html();
$core_tools->load_header('', true, false);           
?><body><?php
$core_tools->load_js(); 
//ADD
if ($mode == 'add') {
    $content .= '<div class="block">';
    $content .= '<form name="formEmail" id="formEmail" method="post" action="#">';
    $content .= '<input type="hidden" value="'.$identifier.'" name="identifier" id="identifier">';
    $content .= '<input type="hidden" value="Y" name="is_html" id="is_html">';
    $content .= '<table border="0" align="left" width="100%" cellspacing="5">';
    $content .= '<tr>';
    $content .= '<td align="right" nowrap width="10%"><b>'.ucfirst(_FROM_SHORT).' </b></td><td>';

    $userEntitiesMails = array();

    if ($core_tools->test_service('use_mail_services', 'sendmail', false)) {
        $userEntitiesMails = $sendmail_tools->getAttachedEntitiesMails($_SESSION['user']['UserId']);
    }

    $content .='<select name="sender_email" id="sender_email">
                    <option value="'.$_SESSION['user']['Mail'].'" selected="selected">'.functions::xssafe($_SESSION['user']['FirstName']) . ' ' . functions::xssafe($_SESSION['user']['LastName']) . ' (' . $_SESSION['user']['Mail'] . ')</option>';
    foreach ($userEntitiesMails as $key => $value) {
        $content .= '<option value="'.$key.'" >' . $value . '</option>';
    }
    $content .='</select>';
    $content .='</td>';
    $content .= '</tr>';
    $content .= '<tr>';
    $content .= '<td align="right" >'._EMAIL.'</label></td>';
    $content .= '<td colspan="2"><input type="text" name="email" id="email" value="" class="emailSelect" />';
    $content .= '<div id="adressList" class="autocomplete"></div>';
    $content .= '<script type="text/javascript">addEmailAdress(\'email\', \'adressList\', \''
        .$_SESSION['config']['businessappurl']
        .'index.php?display=true&module=sendmail&page=adresss_autocomletion\', \'what\', \'2\');</script>';
    $content .= ' <select name="target" id="target">'
        .'<option id="target_target_to" value="to">'._SEND_TO_SHORT.'</option>'
        .'<option id="target_cc" value="cc">'._COPY_TO_SHORT.'</option>'
        .'<option id="target_cci" value="cci">'._COPY_TO_INVISIBLE_SHORT.'</option>'
        .'</select>';
    $content .=' <input type="button" name="add" value="&nbsp;'._ADD
                    .'&nbsp;" id="valid" class="button" onclick="updateAdress(\''.$path_to_script
                    .'&mode=adress\', \'add\', document.getElementById(\'email\').value, '
                    .'document.getElementById(\'target\').value, false, \''.(addslashes(_EMAIL_WRONG_FORMAT)).'\');" />&nbsp;';
    $content .= '</td>';
    $content .= '</tr>';
    $content .= '<tr>';
    $content .= '<td align="right" nowrap width="10%"><span class="red_asterisk"><i class="fa fa-star"></i></span> <label>'
        ._SEND_TO_SHORT.'</label></td>';

    $exp_contact_id = null;
    $dest_contact_id = null;
    $exp_user_id = null;
    $dest_user_id = null;
    $adresse_mail = null;
    $db = new Database();
    $stmt = $db->query("SELECT res_id, category_id, address_id, exp_user_id, dest_user_id, admission_date
                FROM mlb_coll_ext 
                WHERE (( exp_contact_id is not null 
                or dest_contact_id is not null 
                or exp_user_id is not null 
                or dest_user_id is not null) 
                and  res_id = ?)", array($_SESSION['doc_id']));
    $res = $stmt->fetchObject();
    
    $res_id = $res->res_id;
    $category_id = $res->category_id;
    $address_id = $res->address_id;
    $exp_user_id = $res->exp_user_id;
    $dest_user_id = $res->dest_user_id;
    $admission_date = $res->admission_date;

    if ($res_id != null) {
        $stmt = $db->query("SELECT subject FROM res_letterbox WHERE res_id = ?", array($res_id));
        $rawSubject = $stmt->fetchObject();
        $subject = $rawSubject->subject;
    }
    if($address_id != null){
        $stmt = $db->query("SELECT email FROM contact_addresses WHERE id = ?", array($address_id));
        $adr = $stmt->fetchObject();
        $adress_mail = $adr->email;
    }elseif($exp_user_id != null){
        $stmt = $db->query("SELECT mail FROM users WHERE user_id = ?", array($exp_user_id));
        $adr = $stmt->fetchObject();
        $adress_mail = $adr->mail;
    }elseif($dest_user_id != null){
        $stmt = $db->query("SELECT mail FROM users WHERE user_id = ?", array($dest_user_id));
        $adr = $stmt->fetchObject();
        $adress_mail = $adr->mail;
    }
    if($adress_mail != null and ($_SESSION['user']['UserId'] != $exp_user_id and $_SESSION['user']['UserId'] != $dest_user_id)){
    $content .= '<td width="90%" colspan="2"><div name="to" id="to" class="emailInput"><div id="loading_to" style="display:none;"></div><div class="email_element" id="0_'.$adress_mail.'">'.$adress_mail.'&nbsp;<div class="email_delete_button" id="0" onclick="updateAdress(\''.$_SESSION['config']['coreurl'].'apps/maarch_entreprise/index.php?display=true&amp;module=sendmail&amp;page=sendmail_ajax_content&amp;identifier=106&amp;origin=document&amp;coll_id=letterbox_coll&amp;size=full&amp;mode=adress\', \'del\', \''.$adress_mail.'\', \'to\', this.id);"
         alt=\"Supprimer\" title=\"Supprimer\">x</div></div></div>'
        .'<div id="loading_to" style="display:none;"><i class="fa fa-spinner fa-spin" title="loading..."></div></div></td>';
    $_SESSION['adresses']['to'][0] = $adress_mail;
        }else{
    $content .= '<td width="90%" colspan="2"><div name="to" id="to" class="emailInput">'
        .'<div id="loading_to" style="display:none;"><i class="fa fa-spinner fa-spin" title="loading..."></div></div></td>';
             }

    $content .= '</tr>';
    $content .= '<tr><td colspan="3"><a href="javascript://" '
		.'onclick="new Effect.toggle(\'tr_cc\', \'blind\', {delay:0.2});'
		.'new Effect.toggle(\'tr_cci\', \'blind\', {delay:0.2});">'
		._SHOW_OTHER_COPY_FIELDS.'</a></td></tr>';
    $content .= '<tr id="tr_cc" style="display:none">';
    $content .= '<td align="right" nowrap><label>'._COPY_TO_SHORT.'</label></td>';
    $content .= '<td colspan="2"><div name="cc" id="cc" class="emailInput">'
        .'<div id="loading_cc" style="display:none;"><i class="fa fa-spinner fa-spin" title="loading..."></div></div></td>';
    $content .= '</tr>';
    $content .= '<tr id="tr_cci" style="display:none">';
    $content .= '<td align="right" nowrap><label>'._COPY_TO_INVISIBLE_SHORT.'</label></td>';
    $content .= '<td colspan="2"><div name="cci" id="cci" class="emailInput">'
        .'<div id="loading_cci" style="display:none;"><i class="fa fa-spinner fa-spin" title="loading..."></div></div></td>';
    $content .= '</tr>';
    $content .= '<tr>';
    $content .= '<td align="right" nowrap><span class="red_asterisk"><i class="fa fa-star"></i></span><label> '._EMAIL_OBJECT.' </label></td>';

    $content .= '<td colspan="2">';
    if ($category_id === 'outgoing')
        $content .= '<input name="object" id="object" class="emailInput" type="text" value="' . $subject . '" />';
    else
        $content .= '<input name="object" id="object" class="emailInput" type="text" value="' . _EMAIL_OBJECT_ANSWER . ' ' . functions::format_date_db($admission_date).'" />';

    $content .= '</td></tr>';
    $content .= '</table><br />';
    $content .='<hr />';
    $content .= '<h4 onclick="new Effect.toggle(\'joined_files\', \'blind\', {delay:0.2});'
        . 'whatIsTheDivStatus(\'joined_files\', \'divStatus_joined_files\');" '
        . 'class="categorie" style="width:90%;" onmouseover="this.style.cursor=\'pointer\';">';
    $content .= ' <span id="divStatus_joined_files" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span>&nbsp;' 
        . _JOINED_FILES;
    $content .= '</h4>';
    
    $all_joined_files = "\n \n";
    $content .= '<div id="joined_files" style="display:none">';
    //Document
    $joined_files = $sendmail_tools->getJoinedFiles($collId, $table, $identifier);
    if (count($joined_files) >0) {
        $content .='<br/>';
        $content .='<div style="color:rgb(22, 173, 235);font-weight:bold;">'._DOC.'</div>';
        for($i=0; $i < count($joined_files); $i++) {
            //Get data
            $id = $joined_files[$i]['id']; 
            $description = $joined_files[$i]['label'];
            $format = $joined_files[$i]['format'];
            $format = $joined_files[$i]['format'];
            $mime_type = $is->get_mime_type($joined_files[$i]['format']);
            $att_type = $joined_files[$i]['format'];
            $filesize = $joined_files[$i]['filesize']/1024;
            ($filesize > 1)? $filesize = ceil($filesize).' Ko' :  $filesize = round($filesize,2).' Octets';
			//Show data
			$version = '';
            $content .= "<table cellspacing=\"3\" id=\"main_document\" style=\"border-collapse:collapse;width:100%;\"><tr>";
            if($joined_files[$i]['is_version'] === true){
				//Version
				$version = ' - '._VERSION.' '.$joined_files[$i]['version'] ;
				//Contents
				$content .= "<th style=\"width:25px;border: dashed 1px grey;border-right:none;vertical-align:middle;\" alt=\"".$description
					. "\" title=\"".$description
					. "\"><input type=\"checkbox\" id=\"join_file_".$id
					. "_V".$joined_files[$i]['version']."\" name=\"join_version[]\""
					. " class=\"check\" value=\""
					. $id."\" ></th>"
					. "<td style=\"cursor:pointer;border: dashed 1px grey;border-left:none;padding:5px;text-align:left;\"";
                $content .= ' onclick="clickAttachments('.$id.')" ';
                $content .= "><strong>" . $description . "</strong> <span style=\"font-size: 10px;color: grey;\">(" . $att_type . " - " . $filesize .")</span></</td>";
			} else {
				$content .= "<th style=\"width:25px;border: dashed 1px grey;border-right:none;vertical-align:middle;\" alt=\"".$description
					. "\" title=\"".$description
					. "\"><input type=\"checkbox\" id=\"join_file_".$id."\" name=\"join_file[]\""
					. " class=\"check\" value=\""
					. $id."\" ></th>"
					. "<td style=\"cursor:pointer;border: dashed 1px grey;border-left:none;padding:5px;text-align:left;\"";
                $content .= ' onclick="clickAttachments('.$id.')" ';
                $content .= "><strong>" . $description . "</strong> <span style=\"font-size: 10px;color: grey;\">(" . $att_type . " - " . $filesize .")</span></td>";
            }
            $content .= "</tr></table>";
			$filename = $sendmail_tools->createFilename($description.$version, $format);
            $all_joined_files .= $description.': '.$filename.PHP_EOL;
        }
    }
    
    //Attachments
    if ($core_tools->is_module_loaded('attachments')) { 
        $attachment_files = $sendmail_tools->getJoinedFiles($collId, $table, $identifier, true);
        if (count($attachment_files) >0) {
            $content .='<br/>';
            $content .='<div style="color:rgb(22, 173, 235);font-weight:bold;">'._ATTACHMENTS.'</div>';
            $content .= "<table cellspacing=\"3\" id=\"show_pj_mail\" style=\"border-collapse:collapse;width:100%;\">";
            //var_dump($attachment_files);
            for($i=0; $i < count($attachment_files); $i++) {
                $it = $i+1;
                //nouvelle ligne toutes les 3 infos
                //if($it%4 == 0 || $it == 1){
                    $content .= "<tr style=\"vertical-align:top;\">";
                //}

                //Get data
                $id = $attachment_files[$i]['id']; 
                $id_converted = $attachment_files[$i]['converted_pdf']; 
                $description = $attachment_files[$i]['label'];
                if (strlen($description) > 73) {
                    $description = substr($description, 0, 70);
                    $description .= "...";
                }
                $format = $attachment_files[$i]['format'];
                $mime_type = $is->get_mime_type($attachment_files[$i]['format']);
                $att_type = $attachment_files[$i]['format'];
                $filesize = $attachment_files[$i]['filesize']/1024;
                $attachment_type = $_SESSION['attachment_types'][$attachment_files[$i]['attachment_type']];
                $chrono = $attachment_files[$i]['identifier'];
                $dest_society = $attachment_files[$i]['society'];
                $dest_firstname = $attachment_files[$i]['firstname'];
                $dest_lastname = $attachment_files[$i]['lastname'];
                ($filesize > 1)? $filesize = ceil($filesize).' Ko' :  $filesize = $filesize.' Octets';
                
                $content .= "<th style=\"width:25px;border: dashed 1px grey;border-right:none;vertical-align:middle;\" alt=\"".$description
                    . "\" title=\"".$description
                    . "\"><input style=\"margin-left: 3px\" type=\"checkbox\" id=\"join_file_".$id."\" name=\"join_attachment[]\""
                    . " class=\"check\" value=\""
                    . $id."\"";
                
                    /*if ($_SESSION['attachment_types_attach_in_mail'][$attachment_type]) {
                        $content .= " checked=\"checked\" ";
                    }*/
                    /*
                    avec la condition ci-dessous, toutes les réponses signées sont cochées lorsqu'on veut envoyer le courrier par mail
                    */
                    if ($attachment_type == _SIGNED_RESPONSE) {
                        $content .= " checked=\"checked\" ";
                    }
                $content .= "/></th>";

                if(!$id_converted){
                    $content .= "<td style=\"cursor:pointer;border: dashed 1px grey;border-left:none;padding:5px;\"";
                    $content .= ' onclick="clickAttachments('.$id.')" ';
                }else{
                    $content .= "<td style=\"border: dashed 1px grey;border-left:none;padding:5px;\"";
                }

                $content .= "><span style=\"font-size: 10px;color: rgb(22, 173, 235);\">" . $attachment_type . "</span> <span style=\"font-size: 10px;color: grey;\">(" . $att_type . " - " . $filesize .")</span><br/><strong>" . $description . "</strong>";
                if($id_converted){
                    $content .= " (<input style=\"margin: 0px\" title=\"envoyer la version PDF\" type=\"checkbox\" id=\"join_file_".$id_converted."\" name=\"join_attachment[]\""
                    . " class=\"check\" value=\""
                    . $id_converted."\" />version pdf)";
                }
                $content .= "<br/>";
                if ($chrono != "")
                    $content .= "<span style='font-size: 10px;color: rgb(22, 173, 235);font-style:italic;'>" . $chrono . "</span> - ";
                $content .= "<span style='font-size: 10px;color: grey;font-style:italic;'>" . $dest_firstname . " " . $dest_lastname. " " . $dest_society . "</span>";
                $content .= "</td>";   

                //if($it%3 == 0 && $it != 1){
                    $content .= "</tr>";
                //}

				$filename = $sendmail_tools->createFilename($description, $format);

                // $all_joined_files .= $description.': '.$filename.PHP_EOL;
            }
            $content .= "</table>";
        }
    }
    //Notes            
    if ($core_tools->is_module_loaded('notes')) {
        require_once "modules" . DIRECTORY_SEPARATOR . "notes" . DIRECTORY_SEPARATOR
            . "class" . DIRECTORY_SEPARATOR
            . "class_modules_tools.php";
        $notes_tools    = new notes();
        $user_notes = $notes_tools->getUserNotes($identifier, $collId);
        if (count($user_notes) >0) {
            $content .='<br/>';
            $content .='<div style="color:rgb(22, 173, 235);font-weight:bold;">'._NOTES.'</div>';
            $content .= "<table cellspacing=\"3\" style=\"border-collapse:collapse;width:100%;\">";
            for($i=0; $i < count($user_notes); $i++) {
                $it = $i+1;
                //nouvelle ligne toutes les 3 infos
                //if($it%4 == 0 || $it == 1){
                    $content .= "<tr style=\"vertical-align:top;\">";
                //}

                //Get data
                $id = $user_notes[$i]['id']; 
                $noteShort = $request->cut_string($user_notes[$i]['label'], 50);
                $note = $user_notes[$i]['label'];
                $userArray = $users_tools->get_user($user_notes[$i]['author']);
                $date = $request->dateformat($user_notes[$i]['date']);
                
                $content .= "<th style=\"width:25px;border: dashed 1px grey;border-right:none;vertical-align:middle;\" alt=\"".$note
                    . "\" title=\"".$note
                    . "\"><input type=\"checkbox\" id=\"note_".$id."\" name=\"notes[]\""
                    . " class=\"check\" value=\""
                    . $id."\"></th><td style=\"cursor:pointer;border: dashed 1px grey;border-left:none;padding:5px;\"";
                $content .= ' onclick="clickAttachmentsNotes('.$id.')" ';
                $content .= "title=\"".$note."\"><span style=\"font-size: 10px;color: rgb(22, 173, 235);\">".$userArray['firstname']." ".$userArray['lastname']." </span><span style=\"font-size: 10px;color: grey;\">".$date."</span><br/>"
                    ."<strong>". $noteShort."</strong></td>"; 

                //if($it%3 == 0 && $it != 1){
                    $content .= "</tr>";
                //}
            }
            
            // $all_joined_files .= _NOTES.": notes_".$identifier."_".date(dmY).".html\n";
            $content .= "</table>";
        }
    }
    
    $content .= '</div>';
    $content .='<hr />';
    $content .= '<tr>';
    $content .= '<td><label style="padding-right:10px">' . _Label_ADD_TEMPLATE_MAIL . '</label></td>';
    $content .= '<select name="templateMail" id="templateMail" style="width:200px" '
                . 'onchange="addTemplateToEmail($(\'templateMail\').value, \''
                            . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
                            . '&module=templates&page=templates_ajax_content_for_mails&id=' . $_REQUEST['identifier'] . '\');">';

    $content .= '<option value="">' . _ADD_TEMPLATE_MAIL . '</option>';
    
    $stmt = $db->query("select template_id, template_label, template_content from templates where template_target = 'sendmail'");
    while ( $result=$stmt->fetchObject()) {
        $content .= "<option value='" . $result->template_id ."'>" . $result->template_label . "</option>";
    }
    $content .= '</select>';
    $content .= '<label style="margin-left: 15%;padding-right:10px">' . 'Signature de mail' . '</label>';
    $emailSignaturesClass = new EmailSignatures();

    $mailSignatures = $emailSignaturesClass->getForCurrentUser();
    $content .= '<script type="text/javascript">var mailSignaturesJS = ' . json_encode($mailSignatures) . ';</script>';
    $content .= '<select style="width: 20%;" name="selectSignatures" id ="selectSignatures" onchange="changeSignature(this.options[this.selectedIndex], mailSignaturesJS)">';
    $content .= '<option value="none" data-nb="-1" selected >Sans signature</option>';
    for ($i = 0; $mailSignatures[$i]; $i++) {
        $content .= '<option value="' . $mailSignatures[$i]['id'] . '" data-nb="' . $i . '">' . $mailSignatures[$i]['title'] . '</option>';
    }
    $content .= '</select>';
    $content .= '</tr></br></br>';

    //Body
    $displayHtml = 'block';
    $displayRaw = 'none';
    $content .='<script type="text/javascript">var mode="html";</script>';
     //Show/hide html VS raw mode
    $content .= '<a href="javascript://" onclick="switchMode(\'show\');"><em>'._HTML_OR_RAW.'</em></a>';
    
    //load tinyMCE editor
    ob_start();
    include('modules/sendmail/load_editor.php');
    $content .= ob_get_clean();
    ob_end_flush();
    $content .='<div id="html_mode" style="display:'.$displayHtml.'">';
    $content .= '<textarea name="body_from_html" id="body_from_html" style="width:100%" rows="15" cols="60">'
        ._DEFAULT_BODY.$sendmail_tools->rawToHtml($all_joined_files).'</textarea>';
    $content .='</div>';
    
    //raw text arera
    $content .='<div id="raw_mode" style="display:'.$displayRaw.'">';
    $content .= '<textarea name="body_from_raw" id="body_from_raw" class="emailInput" cols="60" rows="14">'
        ._DEFAULT_BODY.$sendmail_tools->htmlToRaw($all_joined_files).'</textarea>';
    $content .='</div>';
    
    //Buttons
    $content .='<hr style="margin-top:2px;" />';
    $content .='<div align="center">';
    //Send
    $content .=' <input type="button" name="valid" value="&nbsp;'._SEND_EMAIL
                .'&nbsp;" id="valid" class="button" onclick="validEmailForm(\''
                .$path_to_script.'&mode=added&for=send\', \'formEmail\');" />&nbsp;';
    //Save
    $content .=' <input type="button" name="valid" value="&nbsp;'._SAVE_EMAIL
                .'&nbsp;" id="valid" class="button" onclick="validEmailForm(\''
                .$path_to_script.'&mode=added&for=save\', \'formEmail\');" />&nbsp;';
    //Cancel
    $content .='<input type="button" name="cancel" id="cancel" class="button" value="'
                ._CANCEL.'" onclick="window.parent.destroyModal(\'form_email\');"/>';
    $content .='</div>';
    $content .= '</form>';
    $content .= '</div>';

//UPDATE OR TRANSFER
} else if ($mode == 'up' || $mode == 'transfer') {
 
    if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
        
        $id = $_REQUEST['id'];
        $emailArray = $sendmail_tools->getEmail($id);

        //Check if mail exists
        if (count($emailArray) > 0 ) {
            $content .= '<div class="block">';
            $content .= '<form name="formEmail" id="formEmail" method="post" action="#">';
            $content .= '<input type="hidden" value="'.$identifier.'" name="identifier" id="identifier">';
            $content .= '<input type="hidden" value="'.$id.'" name="id" id="id">';
            $content .= '<input type="hidden" value="'.$emailArray['isHtml'].'" name="is_html" id="is_html">';
            $content .= '<table border="0" align="left" width="100%" cellspacing="5">';
            $content .= '<tr>';
			$content .= '<td align="right" nowrap width="10%"><b>'.ucfirst(_FROM_SHORT).' </b></td><td>';

            $userEntitiesMails = array();

            if ($core_tools->test_service('use_mail_services', 'sendmail', false)) {
                $userEntitiesMails = $sendmail_tools->getAttachedEntitiesMails($_SESSION['user']['UserId']);
            }

            $content .='<select name="sender_email" id="sender_email">
                            <option value="'.$_SESSION['user']['Mail'].'" ';

            if ($emailArray['sender_email'] == $_SESSION['user']['Mail']) {
                $content .= ' selected="selected" ';
            }

            $content .= '>'.functions::xssafe($_SESSION['user']['FirstName']) . ' ' . functions::xssafe($_SESSION['user']['LastName']) . ' (' . $_SESSION['user']['Mail'] . ')</option>';
            foreach ($userEntitiesMails as $key => $value) {
                $content .= '<option value="'.$key.'" ';

                if ($emailArray['sender_email'] == $key) {
                    $content .= ' selected="selected" ';
                }
                $content .= '>' . $value . '</option>';
            }
            $content .='</select>';
            $content .='</td>';

            $content .= '</tr>';
            $content .= '<tr>';
            $content .= '<td align="right">'._EMAIL.'</label></td>';
            $content .= '<td colspan="2"><input type="text" name="email" id="email" value="" class="emailSelect" />';
            $content .= '<div id="adressList" class="autocomplete"></div>';
            $content .= '<script type="text/javascript">addEmailAdress(\'email\', \'adressList\', \''
                .$_SESSION['config']['businessappurl']
                .'index.php?display=true&module=sendmail&page=adresss_autocomletion\', \'what\', \'2\');</script> ';
            $content .= '<select name="target" id="target">'
                .'<option id="target_target_to" value="to">'._SEND_TO_SHORT.'</option>'
                .'<option id="target_cc" value="cc">'._COPY_TO_SHORT.'</option>'
                .'<option id="target_cci" value="cci">'._COPY_TO_INVISIBLE_SHORT.'</option>'
                .'</select>';
            $content .=' <input type="button" name="add" value="&nbsp;'._ADD
                            .'&nbsp;" id="valid" class="button" onclick="updateAdress(\''.$path_to_script
                            .'&mode=adress\', \'add\', document.getElementById(\'email\').value, '
                            .'document.getElementById(\'target\').value, false, \''.(addslashes(_EMAIL_WRONG_FORMAT)).'\');" />&nbsp;';
            $content .= '</td>';
            $content .= '</tr>';
            //To
            if (count($emailArray['to']) > 0) {
                $_SESSION['adresses']['to'] = array();
                $_SESSION['adresses']['to'] = $emailArray['to'];
            }
            $content .= '<tr>';
            $content .= '<td align="right" nowrap width="10%"><span class="red_asterisk"><i class="fa fa-star"></i></span> <label>'
                ._SEND_TO_SHORT.'</label></td>';
            $content .= '<td width="90%" colspan="2"><div name="to" id="to" class="emailInput">';
            $content .= $sendmail_tools->updateAdressInputField($path_to_script, $_SESSION['adresses'], 'to');
            $content .= '</div></td>';
            $content .= '</tr>';
            //CC
            if (count($emailArray['cc']) > 0) {
                $_SESSION['adresses']['cc'] = array();
                $_SESSION['adresses']['cc'] = $emailArray['cc'];
            }
			$content .= '<tr><td colspan="3"><a href="javascript://" '
				.'onclick="new Effect.toggle(\'tr_cc\', \'blind\', {delay:0.2});'
				.'new Effect.toggle(\'tr_cci\', \'blind\', {delay:0.2});">'
				._SHOW_OTHER_COPY_FIELDS.'</a></td></tr>';
			$content .= '<tr id="tr_cc" style="display:none">';
            $content .= '<td align="right" nowrap><label>'._COPY_TO_SHORT.'</label></td>';
            $content .= '<td colspan="2"><div name="cc" id="cc" class="emailInput">';
            $content .= $sendmail_tools->updateAdressInputField($path_to_script, $_SESSION['adresses'], 'cc');
            $content .= '</div></td>';
            $content .= '</tr>';
            //CCI
            if (count($emailArray['cci']) > 0) {
                $_SESSION['adresses']['cci'] = array();
                $_SESSION['adresses']['cci'] = $emailArray['cci'];
            }
            $content .= '<tr id="tr_cci" style="display:none">';
            $content .= '<td align="right" nowrap><label>'._COPY_TO_INVISIBLE_SHORT.'</label></td>';
            $content .= '<td colspan="2"><div name="cci" id="cci" class="emailInput">';
            $content .= $sendmail_tools->updateAdressInputField($path_to_script, $_SESSION['adresses'], 'cci');
            $content .= '</div></td>';
            $content .= '</tr>';
            //Object
            $content .= '<tr>';
            $content .= '<td align="right" nowrap><span class="red_asterisk"><i class="fa fa-star"></i></span> <label>'._EMAIL_OBJECT.' </label></td>';
            $content .= '<td colspan="2"><input name="object" id="object" class="emailInput" type="text" value="'
                .(($mode == 'transfer')? 'Fw: '.$emailArray['object'] : $emailArray['object']).'" /></td>';
            $content .= '</tr>';
            $content .= '</table><br />';
            $content .='<hr />';
            //Show hide joined info
            $content .= '<h4 onclick="new Effect.toggle(\'joined_files\', \'blind\', {delay:0.2});'
                . 'whatIsTheDivStatus(\'joined_files\', \'divStatus_joined_files\');" '
                . 'class="categorie" style="width:90%;" onmouseover="this.style.cursor=\'pointer\';">';
            $content .= ' <span id="divStatus_joined_files" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span>&nbsp;' 
                . _JOINED_FILES;
            $content .= '</h4>';
            //
            $content .= '<div id="joined_files" style="display:none">';
            //Document
            $joined_files = $sendmail_tools->getJoinedFiles($collId, $table, $identifier);
            if (count($joined_files) >0) {
                $content .='<br/>';
                $content .='<div style="color:rgb(22, 173, 235);font-weight:bold;">'._DOC.'</div>';
                for($i=0; $i < count($joined_files); $i++) {
                    //Get data
                    $id = $joined_files[$i]['id']; 
                    $description = $joined_files[$i]['label'];
                    $format = $joined_files[$i]['format'];
                    $format = $joined_files[$i]['format'];
                    $mime_type = $is->get_mime_type($joined_files[$i]['format']);
                    $att_type = $joined_files[$i]['format'];
                    $filesize = $joined_files[$i]['filesize']/1024;
					($filesize > 1)? $filesize = ceil($filesize).' Ko' :  $filesize = round($filesize,2).' Octets';

                    //Show data
					$version = '';
					$content .= "<table cellspacing=\"3\" id=\"main_document\" style=\"border-collapse:collapse;width:100%;\"><tr>";
                    if($joined_files[$i]['is_version'] === true){
                        //Version
                        $version = ' - '._VERSION.' '.$joined_files[$i]['version'] ;
                        //Contents
                        $content .= "<th style=\"width:25px;border: dashed 1px grey;border-right:none;vertical-align:middle;\" alt=\"".$description
                            . "\" title=\"".$description
                            . "\"><input type=\"checkbox\" id=\"join_file_".$id
                            . "_V".$joined_files[$i]['version']."\" name=\"join_version[]\"";
                            //Checked?
                            (in_array($id, $emailArray['version']))? $checked = ' checked="checked"' : $checked = '';
                        $content .= " ".$checked
                            . " class=\"check\" value=\""
                            . $id."\" ></th>"
                            . "<td style=\"cursor:pointer;border: dashed 1px grey;border-left:none;padding:5px;text-align:left;\"";
                        $content .= ' onclick="clickAttachments('.$id.')" ';
                        $content .= "><strong>" . $description . "</strong> <span style=\"font-size: 10px;color: grey;\">(" . $att_type . " - " . $filesize .")</span></</td>";
                    } else {
                        $content .= "<th style=\"width:25px;border: dashed 1px grey;border-right:none;vertical-align:middle;\" alt=\"".$description
                            . "\" title=\"".$description
                            . "\"><input type=\"checkbox\" id=\"join_file_".$id."\" name=\"join_file[]\"";
                            ($emailArray['resMasterAttached'] == 'Y')? $checked = ' checked="checked"' : $checked = '';
                        $content .= " ".$checked
                            . " class=\"check\" value=\""
                            . $id."\" ></th>"
                            . "<td style=\"cursor:pointer;border: dashed 1px grey;border-left:none;padding:5px;text-align:left;\"";
                        $content .= ' onclick="clickAttachments('.$id.')" ';
                        $content .= "><strong>" . $description . "</strong> <span style=\"font-size: 10px;color: grey;\">(" . $att_type . " - " . $filesize .")</span></td>";
                    }
                    $content .= "</tr></table>";
                    //Filename
					$filename = $sendmail_tools->createFilename($description.$version, $format);
                    $all_joined_files .= $description.': '.$filename.PHP_EOL;
                }
            }
            
            //Attachments
            if ($core_tools->is_module_loaded('attachments')) {
                $attachment_files = $sendmail_tools->getJoinedFiles($collId, $table, $identifier, true);
                if (count($attachment_files) >0) {
                    $content .='<br/>';
                    $content .='<div style="color:rgb(22, 173, 235);font-weight:bold;">'._ATTACHMENTS.'</div>';
                    $content .= "<table cellspacing=\"3\" id=\"show_pj_mail\" style=\"border-collapse:collapse;width:100%;\">";
                    for($i=0; $i < count($attachment_files); $i++) {
                        $it = $i+1;
                        //nouvelle ligne toutes les 3 infos
                        //if($it%4 == 0 || $it == 1){
                            $content .= "<tr style=\"vertical-align:top;\">";
                        //}

                        //Get data
                        $id = $attachment_files[$i]['id']; 
                        $id_converted = $attachment_files[$i]['converted_pdf']; 
                        $description = $attachment_files[$i]['label'];
                        if (strlen($description) > 73) {
                            $description = substr($description, 0, 70);
                            $description .= "...";
                        }
                        $format = $attachment_files[$i]['format'];
                        $mime_type = $is->get_mime_type($attachment_files[$i]['format']);
                        $att_type = $attachment_files[$i]['format'];
                        $filesize = $attachment_files[$i]['filesize']/1024;
                        $attachment_type = $_SESSION['attachment_types'][$attachment_files[$i]['attachment_type']];
                        $chrono = $attachment_files[$i]['identifier'];
                        $dest_society = $attachment_files[$i]['society'];
                        $dest_firstname = $attachment_files[$i]['firstname'];
                        $dest_lastname = $attachment_files[$i]['lastname'];
                        ($filesize > 1)? $filesize = ceil($filesize).' Ko' :  $filesize = $filesize.' Octets';
                        
                        $content .= "<th style=\"width:25px;border: dashed 1px grey;border-right:none;vertical-align:middle;\" alt=\"".$description
                            . "\" title=\"".$description
                            . "\"><input style=\"margin-left: 3px\" type=\"checkbox\" id=\"join_file_".$id."\" name=\"join_attachment[]\"";
                            (in_array($id, $emailArray['attachments']))? $checked = ' checked="checked"' : $checked = '';
                        $content .= " ".$checked    
                            . " class=\"check\" value=\""
                            . $id."\"";
                        
                            /*if ($_SESSION['attachment_types_attach_in_mail'][$attachment_type]) {
                                $content .= " checked=\"checked\" ";
                            }*/
                            /*
                            avec la condition ci-dessous, toutes les réponses signées sont cochées lorsqu'on veut envoyer le courrier par mail
                            */
                            if ($attachment_type == _SIGNED_RESPONSE) {
                                $content .= " checked=\"checked\" ";
                            }
                        $content .= "/></th>";

                        if(!$id_converted){
                            $content .= "<td style=\"cursor:pointer;border: dashed 1px grey;border-left:none;padding:5px;\"";
                            $content .= ' onclick="clickAttachments('.$id.')" ';
                        }else{
                            $content .= "<td style=\"border: dashed 1px grey;border-left:none;padding:5px;\"";
                        }

                        $content .= "><span style=\"font-size: 10px;color: rgb(22, 173, 235);\">" . $attachment_type . "</span> <span style=\"font-size: 10px;color: grey;\">(" . $att_type . " - " . $filesize .")</span><br/><strong>" . $description . "</strong>";
                        if($id_converted){
                            $content .= " (<input style=\"margin: 0px\" title=\"envoyer la version PDF\" type=\"checkbox\" id=\"join_file_".$id_converted."\" name=\"join_attachment[]\""
                            . " class=\"check\"";

                            (in_array($id_converted, $emailArray['attachments']))? $checked = ' checked="checked"' : $checked = '';
                            $content .= " ".$checked   
                            . " value=\""
                            . $id_converted."\" />version pdf)";
                        }
                        $content .= "<br/><span style='font-size: 10px;color: rgb(22, 173, 235);font-style:italic;'>";
                        if ($chrono != "")
                            $content .= "<span style='font-size: 10px;color: rgb(22, 173, 235);font-style:italic;'>" . $chrono . "</span> - ";
                        $content .= "<span style='font-size: 10px;color: grey;font-style:italic;'>" . $dest_firstname . " " . $dest_lastname. " " . $dest_society . "</span>";
                        $content .= "</td>";   

                        //if($it%3 == 0 && $it != 1){
                            $content .= "</tr>";
                        //}
                        //Filename
						$filename = $sendmail_tools->createFilename($description, $format);
                        $all_joined_files .= $description.': '.$filename.PHP_EOL;
                    }
                    $content .= "</table>";
                }
            }
            
            //Notes            
            if ($core_tools->is_module_loaded('notes')) {
                require_once "modules" . DIRECTORY_SEPARATOR . "notes" . DIRECTORY_SEPARATOR
                    . "class" . DIRECTORY_SEPARATOR
                    . "class_modules_tools.php";
                $notes_tools    = new notes();
                $user_notes = $notes_tools->getUserNotes($identifier, $collId);
                if (count($user_notes) >0) {
                    $content .='<br/>';
                    $content .='<div style="color:rgb(22, 173, 235);font-weight:bold;">'._NOTES.'</div>';
                    $content .= "<table cellspacing=\"3\" style=\"border-collapse:collapse;width:100%;\">";
                    for($i=0; $i < count($user_notes); $i++) {
                        $it = $i+1;
                        //nouvelle ligne toutes les 3 infos
                        //if($it%4 == 0 || $it == 1){
                            $content .= "<tr style=\"vertical-align:top;\">";
                        //}

                        //Get data
                        $id = $user_notes[$i]['id']; 
                        $noteShort = $request->cut_string($user_notes[$i]['label'], 50);
                        $note = $user_notes[$i]['label'];
                        $userArray = $users_tools->get_user($user_notes[$i]['author']);
                        $date = $request->dateformat($user_notes[$i]['date']);
                        
                        $content .= "<th style=\"width:25px;border: dashed 1px grey;border-right:none;vertical-align:middle;\" alt=\"".$note
                            . "\" title=\"".$note
                            . "\"><input type=\"checkbox\" id=\"note_".$id."\" name=\"notes[]\"";

                        (in_array($id, $emailArray['notes']))? $checked = ' checked="checked"' : $checked = '';

                        $content .= " ".$checked
                            . " class=\"check\" value=\""
                            . $id."\"></th><td style=\"cursor:pointer;border: dashed 1px grey;border-left:none;padding:5px;\"";
                        $content .= ' onclick="clickAttachmentsNotes('.$id.')" ';
                        $content .= "title=\"".$note."\"><span style=\"font-size: 10px;color: rgb(22, 173, 235);\">".$userArray['firstname']." ".$userArray['lastname']." </span><span style=\"font-size: 10px;color: grey;\">".$date."</span><br/>"
                            ."<strong>". $noteShort."</strong></td>"; 

                        //if($it%3 == 0 && $it != 1){
                            $content .= "</tr>";
                        //}
                    }
                    
                    // $all_joined_files .= _NOTES.": notes_".$identifier."_".date(dmY).".html\n";
                    $content .= "</table>";
                    //Filename
                    $filename = "notes_".$identifier."_".date(dmY).".html";
                    $all_joined_files .= _NOTES.': '.$filename.PHP_EOL;
                }
            }
            $content .= '</div>';
            $content .='<hr />';

            $content .= '<tr>';
            $content .= '<td><label style="padding-right:10px">' . _Label_ADD_TEMPLATE_MAIL . '</label></td>';
            $content .= '<select name="templateMail" id="templateMail" style="width:200px" '
                        . 'onchange="addTemplateToEmail($(\'templateMail\').value, \''
                                    . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
                                    . '&module=templates&page=templates_ajax_content_for_mails&id=' . $_REQUEST['identifier'] . '\');">';

            $content .= '<option value="">' . _ADD_TEMPLATE_MAIL . '</option>';
            
            $stmt = $db->query("select template_id, template_label, template_content from templates where template_target = 'sendmail'");
            while ( $result=$stmt->fetchObject()) {
                $content .= "<option value='" . $result->template_id ."'>" . $result->template_label . "</option>";
            }
            $content .='</select>';
            $content .= '<label style="margin-left: 15%;padding-right:10px">' . 'Signature de mail' . '</label>';
            $emailSignaturesClass = new EmailSignatures();

            $mailSignatures = $emailSignaturesClass->getForCurrentUser();
            $content .= '<script type="text/javascript">var mailSignaturesJS = ' . json_encode($mailSignatures) . ';</script>';
            $content .= '<select style="width: 20%;" name="selectSignatures" id ="selectSignatures" onchange="changeSignature(this.options[this.selectedIndex], mailSignaturesJS)">';
            $content .= '<option value="none" data-nb="-1" selected >Sans signature</option>';
            for ($i = 0; $mailSignatures[$i]; $i++) {
                $content .= '<option value="' . $mailSignatures[$i]['id'] . '" data-nb="' . $i . '">' . $mailSignatures[$i]['title'] . '</option>';
            }
            $content .= '</select>';
            $content .= '</tr></br></br>';


            //Body
            if ($emailArray['isHtml'] == 'Y') {
                $displayRaw = 'none';
                $displayHtml = 'block';
                $textAreaMode = 'html';
            } else {
                $displayRaw = 'block';
                $displayHtml = 'none';
                $textAreaMode = 'raw';
            }
            $content .='<script type="text/javascript">var mode="'.$textAreaMode.'";</script>';
            //Show/hide html VS raw mode
            $content .= '<a href="javascript://" onclick="switchMode(\'show\');"><em>'._HTML_OR_RAW.'</em></a>';
            
            //load tinyMCE editor
            ob_start();
            include('modules/sendmail/load_editor.php');
            $content .= ob_get_clean();
            ob_end_flush();
            $content .='<div id="html_mode" style="display:'.$displayHtml.'">';
            $content .= '<textarea name="body_from_html" id="body_from_html" style="width:100%" rows="15" cols="60">'
                .$sendmail_tools->rawToHtml($emailArray['body']).'</textarea>';
            $content .='</div>';
            
            //raw textarera
            $content .='<div id="raw_mode" style="display:'.$displayRaw.'">';
            $content .= '<textarea name="body_from_raw" id="body_from_raw" class="emailInput" cols="60" rows="14">'
                .$sendmail_tools->htmlToRaw($emailArray['body']).'</textarea>';
            $content .='</div>';
            
            //Buttons
            $content .='<hr style="margin-top:5px;margin-bottom:2px;" />';
            $content .='<div align="center">';
            
            if ($emailArray['status'] <> 'S') {
                //Send button
                $content .=' <input type="button" name="valid" value="&nbsp;'._SEND_EMAIL
                    .'&nbsp;" id="valid" class="button" onclick="validEmailForm(\''
                    .$path_to_script.'&mode=updated&for=send\', \'formEmail\');" />&nbsp;';
                //Save button    
                $content .=' <input type="button" name="valid" value="&nbsp;'._SAVE_EMAIL
                    .'&nbsp;" id="valid" class="button" onclick="validEmailForm(\''
                    .$path_to_script.'&mode=updated&for=save\', \'formEmail\');" />&nbsp;';                     
                //Delete button    
                $content .=' <input type="button" name="valid" value="&nbsp;'._REMOVE_EMAIL
                    .'&nbsp;" id="valid" class="button" onclick="if(confirm(\''
                    ._REALLY_DELETE.': '.$request->cut_string($emailArray['object'], 50)
                    .' ?\')) validEmailForm(\''.$path_to_script
                    .'&mode=del\', \'formEmail\');" />&nbsp;';
            } else {
                //Re-send button
                $content .=' <input type="button" name="valid" value="&nbsp;'._RESEND_EMAIL
                    .'&nbsp;" id="valid" class="button" onclick="validEmailForm(\''
                    .$path_to_script.'&mode=added&for=send\', \'formEmail\');" />&nbsp;';
                //Save copy button
                $content .=' <input type="button" name="valid" value="&nbsp;'._SAVE_COPY_EMAIL
                    .'&nbsp;" id="valid" class="button" onclick="validEmailForm(\''
                    .$path_to_script.'&mode=added&for=save\', \'formEmail\');" />&nbsp;';    
            }
            
            //Cancel button
            $content .='<input type="button" name="cancel" id="cancel" class="button" value="'
                        ._CANCEL.'" onclick="window.parent.destroyModal(\'form_email\');"/>';
            $content .='</div>';
            $content .= '</form>';
            $content .= '</div>';
        } else {
            $content = $request->wash_html($id.': '._EMAIL_DONT_EXIST.'!','NONE');
        }
    } else {
        $content = $request->wash_html(_ID.' '._IS_EMPTY.'!','NONE');
    }
} else if ($mode == 'read') {
    if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
        
        $id = $_REQUEST['id'];
        $emailArray = $sendmail_tools->getEmail($id, false);

        //Check if mail exists
        if (count($emailArray) > 0 ) {
			$usermailArray = $users_tools->get_user($emailArray['userId']);
		
            $content .= '<div class="block">';
            $content .= '<table border="0" align="left" width="100%" cellspacing="5">';
            $content .= '<tr>';

			$content .= '<td width="10%" align="right" nowrap><b>'.ucfirst(_FROM_SHORT).' </b></td><td width="90%" colspan="2">';

            $mailEntities = $sendmail_tools->getAttachedEntitiesMails();

            if (in_array($emailArray['sender_email'], array_keys($mailEntities))) {
                $content .= $mailEntities[$emailArray['sender_email']];
            } else if ($emailArray['sender_email'] == $usermailArray['mail']) {
                 $content .= $usermailArray['firstname'] . " " . $usermailArray['lastname'] . " (".$emailArray['sender_email'].")";
            } else {
                $content .= $sendmail_tools->explodeSenderEmail($emailArray['sender_email']);
            }

            $content .= '<br/></td>';
            $content .= '</tr>';
            //To
            if (count($emailArray['to']) > 0) {
                $_SESSION['adresses']['to'] = array();
                $_SESSION['adresses']['to'] = $emailArray['to'];
            }
            $content .= '<tr>';
            $content .= '<td align="right" nowrap width="10%"><span class="red_asterisk"><i class="fa fa-star"></i></span> <label>'
                ._SEND_TO_SHORT.'</label></td>';
            $content .= '<td width="90%" colspan="2"><div name="to" id="to" class="emailInput">';
            $content .= $sendmail_tools->updateAdressInputField($path_to_script, $_SESSION['adresses'], 'to', true);
            $content .= '</div></td>';
            $content .= '</tr>';
            //CC
            if (count($emailArray['cc']) > 0) {
                $_SESSION['adresses']['cc'] = array();
                $_SESSION['adresses']['cc'] = $emailArray['cc'];
            }
            $content .= '<tr>';
            $content .= '<td align="right" nowrap><label>'._COPY_TO_SHORT.'</label></td>';
            $content .= '<td colspan="2"><div name="cc" id="cc" class="emailInput">';
            $content .= $sendmail_tools->updateAdressInputField($path_to_script, $_SESSION['adresses'], 'cc', true);
            $content .= '</div></td>';
            $content .= '</tr>';
            //CCI
            if (count($emailArray['cci']) > 0) {
                $_SESSION['adresses']['cci'] = array();
                $_SESSION['adresses']['cci'] = $emailArray['cci'];
            }
            $content .= '<tr>';
            $content .= '<td align="right" nowrap><label>'._COPY_TO_INVISIBLE_SHORT.'</label></td>';
            $content .= '<td colspan="2"><div name="cci" id="cci" class="emailInput">';
            $content .= $sendmail_tools->updateAdressInputField($path_to_script, $_SESSION['adresses'], 'cci', true);
            $content .= '</div></td>';
            $content .= '</tr>';   
            //Object
            $content .= '<tr>';
            $content .= '<td align="right" nowrap><span class="red_asterisk"><i class="fa fa-star"></i></span> <label>'._EMAIL_OBJECT.' </label></td>';
            $content .= '<td colspan="2"><div name="object" id="object" class="emailInput">'
                .$emailArray['object'].'</div></td>';
            $content .= '</tr>';
            $content .= '</table><br />';

            $content .='<hr />';
            //Show hide joined info
            $content .= '<h4 onclick="new Effect.toggle(\'joined_files\', \'blind\', {delay:0.2});'
                . 'whatIsTheDivStatus(\'joined_files\', \'divStatus_joined_files\');" '
                . 'class="categorie" style="width:90%;" onmouseover="this.style.cursor=\'pointer\';">';
            $content .= ' <span id="divStatus_joined_files" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span>&nbsp;' 
                . _JOINED_FILES;
            $content .= '</h4>';
            //
            $content .= '<div id="joined_files" style="display:none">';
            //Document
            $joined_files = $sendmail_tools->getJoinedFiles($collId, $table, $identifier);
            if (count($joined_files) >0) {
                $content .='<br/>';
                $content .='<div style="color:rgb(22, 173, 235);font-weight:bold;">'._DOC.'</div>';
                for($i=0; $i < count($joined_files); $i++) {
                    //Get data
                    $id = $joined_files[$i]['id']; 
                    $description = $joined_files[$i]['label'];
                    $format = $joined_files[$i]['format'];
                    $format = $joined_files[$i]['format'];
                    $mime_type = $is->get_mime_type($joined_files[$i]['format']);
                    $att_type = $joined_files[$i]['format'];
                    $filesize = $joined_files[$i]['filesize']/1024;
                    ($filesize > 1)? $filesize = ceil($filesize).' Ko' :  $filesize = round($filesize,2).' Octets';

                    //Show data
                    $version = '';
                    $content .= "<table cellspacing=\"3\" id=\"main_document\" style=\"border-collapse:collapse;width:100%;\"><tr>";
                    if($joined_files[$i]['is_version'] === true){
                        //Version
                        $version = ' - '._VERSION.' '.$joined_files[$i]['version'] ;
                        //Contents
                        $content .= "<th style=\"width:25px;border: dashed 1px grey;border-right:none;vertical-align:middle;\" alt=\"".$description
                            . "\" title=\"".$description
                            . "\"><input type=\"checkbox\" disabled=\"disabled\" id=\"join_file_".$id
                            . "_V".$joined_files[$i]['version']."\" name=\"join_version[]\"";
                            //Checked?
                            (in_array($id, $emailArray['version']))? $checked = ' checked="checked"' : $checked = '';
                        $content .= " ".$checked
                            . " class=\"check\" value=\""
                            . $id."\" ></th>"
                            . "<td style=\"cursor:pointer;border: dashed 1px grey;border-left:none;padding:5px;text-align:left;\"";
                        $content .= ' onclick="clickAttachments('.$id.')" ';
                        $content .= "><strong>" . $description . "</strong> <span style=\"font-size: 10px;color: grey;\">(" . $att_type . " - " . $filesize .")</span></</td>";
                    } else {
                        $content .= "<th style=\"width:25px;border: dashed 1px grey;border-right:none;vertical-align:middle;\" alt=\"".$description
                            . "\" title=\"".$description
                            . "\"><input type=\"checkbox\" disabled=\"disabled\" id=\"join_file_".$id."\" name=\"join_file[]\"";
                            ($emailArray['resMasterAttached'] == 'Y')? $checked = ' checked="checked"' : $checked = '';
                        $content .= " ".$checked
                            . " class=\"check\" value=\""
                            . $id."\" ></th>"
                            . "<td style=\"cursor:pointer;border: dashed 1px grey;border-left:none;padding:5px;text-align:left;\"";
                        $content .= ' onclick="clickAttachments('.$id.')" ';
                        $content .= "><strong>" . $description . "</strong> <span style=\"font-size: 10px;color: grey;\">(" . $att_type . " - " . $filesize .")</span></td>";
                    }
                    $content .= "</tr></table>";
                    //Filename
                    $filename = $sendmail_tools->createFilename($description.$version, $format);
                    $all_joined_files .= $description.': '.$filename.PHP_EOL;
                }
            }
            
            //Attachments
            if ($core_tools->is_module_loaded('attachments')) {
                $attachment_files = $sendmail_tools->getJoinedFiles($collId, $table, $identifier, true);
                if (count($attachment_files) >0) {
                    $content .='<br/>';
                    $content .='<div style="color:rgb(22, 173, 235);font-weight:bold;">'._ATTACHMENTS.'</div>';
                    $content .= "<table cellspacing=\"3\" id=\"show_pj_mail\" style=\"border-collapse:collapse;width:100%;\">";
                    for($i=0; $i < count($attachment_files); $i++) {
                        $it = $i+1;
                        //nouvelle ligne toutes les 3 infos
                        //if($it%4 == 0 || $it == 1){
                            $content .= "<tr style=\"vertical-align:top;\">";
                        //}

                        //Get data
                        $id = $attachment_files[$i]['id']; 
                        $id_converted = $attachment_files[$i]['converted_pdf']; 
                        $description = $attachment_files[$i]['label'];
                        if (strlen($description) > 73) {
                            $description = substr($description, 0, 70);
                            $description .= "...";
                        }
                        $format = $attachment_files[$i]['format'];
                        $mime_type = $is->get_mime_type($attachment_files[$i]['format']);
                        $att_type = $attachment_files[$i]['format'];
                        $filesize = $attachment_files[$i]['filesize']/1024;
                        $attachment_type = $_SESSION['attachment_types'][$attachment_files[$i]['attachment_type']];
                        $chrono = $attachment_files[$i]['identifier'];
                        $dest_society = $attachment_files[$i]['society'];
                        $dest_firstname = $attachment_files[$i]['firstname'];
                        $dest_lastname = $attachment_files[$i]['lastname'];
                        ($filesize > 1)? $filesize = ceil($filesize).' Ko' :  $filesize = $filesize.' Octets';
                        
                        $content .= "<th style=\"width:25px;border: dashed 1px grey;border-right:none;vertical-align:middle;\" alt=\"".$description
                            . "\" title=\"".$description
                            . "\"><input style=\"margin-left: 3px\" disabled=\"disabled\" type=\"checkbox\" id=\"join_file_".$id."\" name=\"join_attachment[]\"";
                            (in_array($id, $emailArray['attachments']))? $checked = ' checked="checked"' : $checked = '';
                        $content .= " ".$checked    
                            . " class=\"check\" value=\""
                            . $id."\"";
                        
                            /*if ($_SESSION['attachment_types_attach_in_mail'][$attachment_type]) {
                                $content .= " checked=\"checked\" ";
                            }*/
                            /*
                            avec la condition ci-dessous, toutes les réponses signées sont cochées lorsqu'on veut envoyer le courrier par mail
                            */
                            if ($attachment_type == _SIGNED_RESPONSE) {
                                $content .= " checked=\"checked\" ";
                            }
                        $content .= "/></th>";

                        if(!$id_converted){
                            $content .= "<td style=\"cursor:pointer;border: dashed 1px grey;border-left:none;padding:5px;\"";
                            $content .= ' onclick="clickAttachments('.$id.')" ';
                        }else{
                            $content .= "<td style=\"border: dashed 1px grey;border-left:none;padding:5px;\"";
                        }

                        $content .= "><span style=\"font-size: 10px;color: rgb(22, 173, 235);\">" . $attachment_type . "</span> <span style=\"font-size: 10px;color: grey;\">(" . $att_type . " - " . $filesize .")</span><br/><strong>" . $description . "</strong>";
                        if($id_converted){
                            $content .= " (<input style=\"margin: 0px\" title=\"envoyer la version PDF\" disabled=\"disabled\" type=\"checkbox\" id=\"join_file_".$id_converted."\" name=\"join_attachment[]\""
                            . " class=\"check\"";

                            (in_array($id_converted, $emailArray['attachments']))? $checked = ' checked="checked"' : $checked = '';
                            $content .= " ".$checked   
                            . " value=\""
                            . $id_converted."\" />version pdf)";
                        }
                        $content .= "<br/><span style='font-size: 10px;color: rgb(22, 173, 235);font-style:italic;'>";
                        if ($chrono != "")
                            $content .= "<span style='font-size: 10px;color: rgb(22, 173, 235);font-style:italic;'>" . $chrono . "</span> - ";
                        $content .= "<span style='font-size: 10px;color: grey;font-style:italic;'>" . $dest_firstname . " " . $dest_lastname. " " . $dest_society . "</span>";
                        $content .= "</td>";   

                        //if($it%3 == 0 && $it != 1){
                            $content .= "</tr>";
                        //}
                        //Filename
                        $filename = $sendmail_tools->createFilename($description, $format);
                        $all_joined_files .= $description.': '.$filename.PHP_EOL;
                    }
                    $content .= "</table>";
                }
            }
            
            //Notes            
            if ($core_tools->is_module_loaded('notes')) {
                require_once "modules" . DIRECTORY_SEPARATOR . "notes" . DIRECTORY_SEPARATOR
                    . "class" . DIRECTORY_SEPARATOR
                    . "class_modules_tools.php";
                $notes_tools    = new notes();
                $user_notes = $notes_tools->getUserNotes($identifier, $collId);
                if (count($user_notes) >0) {
                    $content .='<br/>';
                    $content .='<div style="color:rgb(22, 173, 235);font-weight:bold;">'._NOTES.'</div>';
                    $content .= "<table cellspacing=\"3\" style=\"border-collapse:collapse;width:100%;\">";
                    for($i=0; $i < count($user_notes); $i++) {
                        $it = $i+1;
                        //nouvelle ligne toutes les 3 infos
                        //if($it%4 == 0 || $it == 1){
                            $content .= "<tr style=\"vertical-align:top;\">";
                        //}

                        //Get data
                        $id = $user_notes[$i]['id']; 
                        $noteShort = $request->cut_string($user_notes[$i]['label'], 50);
                        $note = $user_notes[$i]['label'];
                        $userArray = $users_tools->get_user($user_notes[$i]['author']);
                        $date = $request->dateformat($user_notes[$i]['date']);
                        
                        $content .= "<th style=\"width:25px;border: dashed 1px grey;border-right:none;vertical-align:middle;\" alt=\"".$note
                            . "\" title=\"".$note
                            . "\"><input type=\"checkbox\" disabled=\"disabled\" id=\"note_".$id."\" name=\"notes[]\"";

                        (in_array($id, $emailArray['notes']))? $checked = ' checked="checked"' : $checked = '';

                        $content .= " ".$checked
                            . " class=\"check\" value=\""
                            . $id."\"></th><td style=\"cursor:pointer;border: dashed 1px grey;border-left:none;padding:5px;\"";
                        $content .= ' onclick="clickAttachmentsNotes('.$id.')" ';
                        $content .= "title=\"".$note."\"><span style=\"font-size: 10px;color: rgb(22, 173, 235);\">".$userArray['firstname']." ".$userArray['lastname']." </span><span style=\"font-size: 10px;color: grey;\">".$date."</span><br/>"
                            ."<strong>". $noteShort."</strong></td>"; 

                        //if($it%3 == 0 && $it != 1){
                            $content .= "</tr>";
                        //}
                    }
                    
                    // $all_joined_files .= _NOTES.": notes_".$identifier."_".date(dmY).".html\n";
                    $content .= "</table>";
                    //Filename
                    $filename = "notes_".$identifier."_".date(dmY).".html";
                    $all_joined_files .= _NOTES.': '.$filename.PHP_EOL;
                }
            }
            $content .= '</div>';
            $content .='<hr />';
            //Body (html or raw mode)
            if ($emailArray['isHtml'] == 'Y') { 
                $content .='<script type="text/javascript">var mode="html";</script>';
                //load tinyMCE editor
                ob_start();
                include('modules/sendmail/load_editor.php');
                $content .= ob_get_clean();
                ob_end_flush();
                $content .='<div id="html_mode" style="display:block">';
                $content .= '<textarea name="body_from_html" id="body_from_html" style="width:100%" '
                    .'rows="15" cols="60" readonly="readonly">'
                    .$sendmail_tools->rawToHtml($emailArray['body']).'</textarea>';
                $content .='</div>';
            } else {
                $content .='<script type="text/javascript">var mode="raw";</script>';
                //raw textarera
                $content .='<div id="raw_mode" style="display:block">';
                $content .= '<textarea name="body_from_raw" id="body_from_raw" class="emailInput" '
                    .'cols="60" rows="14" readonly="readonly">'
                    .$sendmail_tools->htmlToRaw($emailArray['body']).'</textarea>';
                $content .='</div>';
            }
                        
            //Buttons
            $content .='<hr style="margin-top:2px;" />';
            $content .='<div align="center">';
            //Close button
            $content .='<input type="button" name="cancel" id="cancel" class="button" value="'
                        ._CLOSE.'" onclick="window.parent.destroyModal(\'form_email\');"/>';
            $content .='</div>';
            $content .= '</div>';
        } else {
            $content = $request->wash_html($id.': '._EMAIL_DONT_EXIST.'!','NONE');
        }
    } else {
        $content = $request->wash_html(_ID.' '._IS_EMPTY.'!','NONE');
    }
}
echo $content;

?></body></html>
