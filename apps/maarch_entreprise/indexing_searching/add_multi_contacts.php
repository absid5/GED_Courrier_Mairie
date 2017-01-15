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
* @author   Yves Christian Kpakpo <dev@maarch.org>
* @date     $date$
* @version  $Revision$
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
require_once 'modules/notifications/notifications_tables_definition.php';
require_once "modules" . DIRECTORY_SEPARATOR . "sendmail" . DIRECTORY_SEPARATOR
    . "class" . DIRECTORY_SEPARATOR . "class_modules_tools.php";
require_once "apps" . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id'] 
	. DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR 
	. "class_multicontacts.php";
    
$core_tools     = new core_tools();
$request        = new request();
$sec            = new security();
$is             = new indexing_searching_app();
$users_tools    = new class_users();
$sendmail_tools = new sendmail();
$multicontacts  = new multicontacts();
$db             = new Database();

function _parse($text) {
    //...
    $text = str_replace("\r\n", PHP_EOL, $text);
    $text = str_replace("\r", PHP_EOL, $text);

    //
    $text = str_replace(PHP_EOL, "\\n ", $text);
    return $text;
}
function _parse_error($text) {
    //...
    $text = str_replace("###", "\\n ", $text);
    return $text;
}
    
$core_tools->load_lang();

$status = 0;
$error = $content = $js = $parameters = '';

$labels_array = array();

if (isset($_REQUEST['mode']) && !empty($_REQUEST['mode'])) {
    $mode = $_REQUEST['mode'];
} else {
    $error = _ERROR_IN_SENDMAIL_FORM_GENERATION;
    $status = 1;
}

//Path to actual script
$path_to_script = $_SESSION['config']['businessappurl']
		."index.php?display=true&dir=indexing_searching&page=add_multi_contacts&coll_id=".$collId;
 				
			
switch ($mode) {
    case 'up':
    case 'read':
    case 'transfer':
        if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
            $parameters .= '&id='.$_REQUEST['id'];
        } else {
            $error = $request->wash_html(_ID.' '._IS_EMPTY.'!','NONE');
            $status = 1;
             //Close the modal
            $js =  "window.parent.destroyModal('form_email');"; 
            break;
        }
    case 'add':
        if (empty($identifier)) {
            $error = $request->wash_html(_IDENTIFIER.' '._IS_EMPTY.'!','NONE');
            $status = 1;
             //Close the modal
            $js =  "window.parent.destroyModal('form_email');"; 
        } else {
            //Reset arry of adresses
            unset($_SESSION['adresses']);
            $_SESSION['adresses'] = array();
            //Show iframe
            $content .='<iframe name="form_mail" id="form_mail" src="'
                . $_SESSION['config']['businessappurl']
                . 'index.php?display=true&module=sendmail&page=mail_form&identifier='
                . $identifier.'&origin=document&coll_id='.$collId.'&mode='.$mode.$parameters.'" '
                . 'frameborder="0" width="100%" style="height:545px;padding:0px;overflow-x:hidden;overflow-y: auto;"></iframe>';
        }
    break;
        
    case 'added':
        if (empty($identifier)) {
            $error = $request->wash_html(_IDENTIFIER.' '._IS_EMPTY.'!','NONE');
            $status = 1;
        } else {
            if (isset($_SESSION['adresses']['to']) && count($_SESSION['adresses']['to']) > 0 ) {
                if (!empty($_REQUEST['object'])) {
                    // print_r($_REQUEST);exit;
                    
                    //Check adress for to
                    $to =  join(',', $_SESSION['adresses']['to']);
                    $error = $sendmail_tools->CheckEmailAdress($to);
                    
                    if (empty($error)) {
                        
                        //Check adress for cc
                        (isset($_SESSION['adresses']['cc']) && count($_SESSION['adresses']['cc']) > 0)? 
                            $cc =  join(',', $_SESSION['adresses']['cc']) : $cc = '';
                        $error = $sendmail_tools->CheckEmailAdress($cc);
                        
                        if (empty($error)) {
                        
                            //Check adress for cci
                            (isset($_SESSION['adresses']['cci']) && count($_SESSION['adresses']['cci']) > 0)? 
                                $cci =  join(',', $_SESSION['adresses']['cci']) : $cci = '';
                            $error = $sendmail_tools->CheckEmailAdress($cci);
                            
                            if (empty($error)) {
                            
                                //Data
                                $collId = $_REQUEST['coll_id'];
                                $to =  $to;
                                $cc = $cc;
                                $cci = $cci;
                                $object = $_REQUEST['object'];
                                (isset($_REQUEST['join_file']) 
                                    && count($_REQUEST['join_file']) > 0
                                )? $res_master_attached = 'Y' : $res_master_attached = 'N';
								//Version attached
                                if (isset($_REQUEST['join_attachment']) && count($_REQUEST['join_attachment']) > 0) {
                                    $attachment_list = join(',', $_REQUEST['join_attachment']);
                                }      
								//Attachments								
								if (isset($_REQUEST['join_version']) && count($_REQUEST['join_version']) > 0) {
                                    $version_list = join(',', $_REQUEST['join_version']);
                                }
								//Notes
                                if (isset($_REQUEST['notes']) && count($_REQUEST['notes']) > 0) {
                                    $note_list = join(',', $_REQUEST['notes']);
                                }

                                $userId = $_SESSION['user']['UserId'];
                                (!empty($_REQUEST['is_html']) && $_REQUEST['is_html'] == 'Y')? $isHtml = 'Y' : $isHtml = 'N';
                                //Body content
                                if ($isHtml == 'Y') {
                                    $body = $sendmail_tools->cleanHtml($_REQUEST['body_from_html']);
                                } else {
                                     $body = $_REQUEST['body_from_raw'];
                                }
                                
                                //Status
                                if ($_REQUEST['for'] == 'save') {
                                    $email_status = 'D';
                                } else if ($_REQUEST['for'] == 'send'){
                                    $email_status = 'W';
                                }
                                
                                //Query                 
                                $db->query(
                                    "INSERT INTO " . EMAILS_TABLE . "(coll_id, res_id, user_id, to_list, cc_list,
                                    cci_list, email_object, email_body, is_res_master_attached, res_version_id_list, 
                                    res_attachment_id_list, note_id_list, is_html, email_status, creation_date) VALUES (
                                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)",
                                array($collId, $identifier, $userId, $to, $cc, $cci, $object, $body, $res_master_attached, $version_list,
                                    $attachment_list, $note_list, $isHtml, $email_status)
                                );
                                
                                //Last insert ID from sequence
                                $id = $request->last_insert_id('app_emails_email_id_seq');
                                
                                //History
                                if ($_SESSION['history']['mailadd']) {
                                    $hist = new history();
                                    if (isset($_REQUEST['origin']) && $_REQUEST['origin'] == "folder") {
                                        $hist->add(
                                            $table, $identifier, "UP", 'folderup', _EMAIL_ADDED . _ON_FOLDER_NUM
                                            . $identifier . ' (' . $id . ') : "' . $request->cut_string($object, 254) .'"',
                                            $_SESSION['config']['databasetype'], 'sendmail'
                                        );
                                    } else if (isset($_REQUEST['origin']) && $_REQUEST['origin'] == "document") {
                                        $hist->add(
                                            $view, $identifier, "UP", 'resup',  _EMAIL_ADDED . _ON_DOC_NUM
                                            . $identifier . ' (' . $id . ') : "' . $request->cut_string($object, 254) .'"',
                                            $_SESSION['config']['databasetype'], 'sendmail'
                                        );
                                    }

                                    $hist->add(
                                        EMAILS_TABLE, $id, "ADD", 'mailadd', _EMAIL_ADDED . ' (' . $id . ')',
                                        $_SESSION['config']['databasetype'], 'sendmail'
                                    );
                                }
                                
                                //Reload and show message
                                $js =  $list_origin."window.parent.top.$('main_info').innerHTML = '"._EMAIL_ADDED."';"; 
                            } else {
                                $status = 1;
                            }
                        } else {
                            $status = 1;
                        }
                    } else {
                        $status = 1;
                    }
                } else {
                    $error = $request->wash_html(_EMAIL_OBJECT.' '._IS_EMPTY.'!','NONE');
                    $status = 1;
                }
            } else {
                $error = $request->wash_html(_SEND_TO.' '._IS_EMPTY.'!','NONE');
                $status = 1;
            }
        }
    break;
    
    case 'updated':
        if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
            //Email ID
            $id = $_REQUEST['id'];
            
            //Res ID
            if (empty($identifier)) {
                $error = $request->wash_html(_IDENTIFIER.' '._IS_EMPTY.'!','NONE');
                $status = 1;
            } else {
                if (isset($_SESSION['adresses']['to']) && count($_SESSION['adresses']['to']) > 0 ) {
                    if (!empty($_REQUEST['object'])) {
                        // print_r($_REQUEST);exit;
                        
                        //Check adress for to
                        $to =  join(',', $_SESSION['adresses']['to']);
                        $error = $sendmail_tools->CheckEmailAdress($to);
                        
                        if (empty($error)) {
                            
                            //Check adress for cc
                            (isset($_SESSION['adresses']['cc']) && count($_SESSION['adresses']['cc']) > 0)? 
                                $cc =  join(',', $_SESSION['adresses']['cc']) : $cc = '';
                            $error = $sendmail_tools->CheckEmailAdress($cc);
                            
                            if (empty($error)) {
                            
                                //Check adress for cci
                                (isset($_SESSION['adresses']['cci']) && count($_SESSION['adresses']['cci']) > 0)? 
                                    $cci =  join(',', $_SESSION['adresses']['cci']) : $cci = '';
                                $error = $sendmail_tools->CheckEmailAdress($cci);
                                
                                if (empty($error)) {
                                
                                    //Data
                                    $collId = $request->protect_string_db($_REQUEST['coll_id']);
                                    $to =  $to;
                                    $cc = $cc;
                                    $cci = $cci;
                                    $object = $_REQUEST['object'];
                                    (isset($_REQUEST['join_file']) 
                                        && count($_REQUEST['join_file']) > 0
                                    )? $res_master_attached = 'Y' : $res_master_attached = 'N';
                                    if (isset($_REQUEST['join_version']) && count($_REQUEST['join_version']) > 0) {
                                        $version_list = join(',', $_REQUEST['join_version']);
                                    }
                                    if (isset($_REQUEST['join_attachment']) && count($_REQUEST['join_attachment']) > 0) {
                                        $attachment_list = join(',', $_REQUEST['join_attachment']);
                                    }
                                    if (isset($_REQUEST['notes']) && count($_REQUEST['notes']) > 0) {
                                        $note_list = join(',', $_REQUEST['notes']);
                                    }

                                    $userId = $request->protect_string_db($_SESSION['user']['UserId']);
                                    (!empty($_REQUEST['is_html']) && $_REQUEST['is_html'] == 'Y')? $isHtml = 'Y' : $isHtml = 'N';
                                    //Body content
                                    if ($isHtml == 'Y') {
                                        $body = $sendmail_tools->cleanHtml($_REQUEST['body_from_html']);
                                    } else {
                                         $body = $_REQUEST['body_from_raw'];
                                    }
                                    //Status
                                    if ($_REQUEST['for'] == 'save') {
                                        $email_status = 'D';
                                    } else if ($_REQUEST['for'] == 'send'){
                                        $email_status = 'W';
                                    }
                                    
                                    //Query                 
                                    $db->query(
                                        "UPDATE " . EMAILS_TABLE . " SET to_list = ?, cc_list = ?, cci_list = ?, email_object = ?, email_body = ?, is_res_master_attached = ?, res_version_id_list = ?, "
                                            . "res_attachment_id_list = ?, note_id_list = ?, is_html = ?, email_status = ? where email_id = ? and res_id = ? and user_id = ?",
                                array($to, $cc, $cci, $object, $body, $res_master_attached, $version_list,
                                    $attachment_list, $note_list, $isHtml, $email_status, $id, $identifier, $userId)
                                    );
                                    
                                    //History
                                    if ($_SESSION['history']['mailup']) {
                                        $hist = new history();
                                        if (isset($_REQUEST['origin']) && $_REQUEST['origin'] == "folder") {
                                            $hist->add(
                                                $table, $identifier, "UP", 'folderup', _EMAIL_UPDATED . _ON_FOLDER_NUM
                                                . $identifier . ' (' . $id . ') : "' . $request->cut_string($object, 254) .'"',
                                                $_SESSION['config']['databasetype'], 'sendmail'
                                            );
                                        } else if (isset($_REQUEST['origin']) && $_REQUEST['origin'] == "document") {
                                            $hist->add(
                                                $view, $identifier, "UP", 'resup',  _EMAIL_UPDATED . _ON_DOC_NUM
                                                . $identifier . ' (' . $id . ') : "' . $request->cut_string($object, 254) .'"',
                                                $_SESSION['config']['databasetype'], 'sendmail'
                                            );
                                        }

                                        $hist->add(
                                            EMAILS_TABLE, $id, "UP", 'mailup', _EMAIL_UPDATED . ' (' . $id . ')',
                                            $_SESSION['config']['databasetype'], 'sendmail'
                                        );
                                    }
                                    
                                    //Reload and show message
                                    $js =  $list_origin."window.parent.top.$('main_info').innerHTML = '"._EMAIL_UPDATED."';"; 
                                } else {
                                    $status = 1;
                                }
                            } else {
                                $status = 1;
                            }
                        } else {
                            $status = 1;
                        }
                    } else {
                        $error = $request->wash_html(_EMAIL_OBJECT.' '._IS_EMPTY.'!','NONE');
                        $status = 1;
                    }
                } else {
                    $error = $request->wash_html(_SEND_TO.' '._IS_EMPTY.'!','NONE');
                    $status = 1;
                }
            }
            
        } else {
            $error = $request->wash_html(_ID.' '._IS_EMPTY.'!','NONE');
            $status = 1;
             //Close the modal
            $js =  "window.parent.destroyModal('form_email');"; 
        }
    break;
    
    case 'del':
        if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
            
            //Keep object
            $object = $request->protect_string_db($_REQUEST['object']);
            
            //Delete mail
            $db->query("DELETE FROM " . EMAILS_TABLE . " WHERE email_id = ?", array($id));
            
            //Delete email from stack too ???
            $db->query("DELETE FROM " . _NOTIF_EVENT_STACK_TABLE_NAME 
                . " WHERE table_name = '" . EMAILS_TABLE . "' and record_id = ?", array($id));
            
            //History
            if ($_SESSION['history']['mailadd']) {
                $hist = new history();
                if (isset($_REQUEST['origin']) && $_REQUEST['origin'] == "folder") {
                    $hist->add(
                        $table, $identifier, "UP", 'folderup', _EMAIL_REMOVED . _ON_FOLDER_NUM
                        . $identifier . ' (' . $id . ') : "' . $request->cut_string($object, 254) .'"',
                        $_SESSION['config']['databasetype'], 'sendmail'
                    );
                } else if (isset($_REQUEST['origin']) && $_REQUEST['origin'] == "document") {
                    $hist->add(
                        $view, $identifier, "UP", 'resup',  _EMAIL_REMOVED . _ON_DOC_NUM
                        . $identifier . ' (' . $id . ') : "' . $request->cut_string($object, 254) .'"',
                        $_SESSION['config']['databasetype'], 'sendmail'
                    );
                }

                $hist->add(
                    EMAILS_TABLE, $id, "DEL", 'maildel', _EMAIL_REMOVED . ' (' . $id . ') : "' 
                    . $request->cut_string($object, 254) .'"',
                    $_SESSION['config']['databasetype'], 'sendmail'
                );
            }
            
            //Reload and show message
            $js =  $list_origin."window.parent.top.$('main_info').innerHTML = '"._EMAIL_REMOVED."';";
            
        } else {
            $error = $request->wash_html(_ID.' '._IS_EMPTY.'!','NONE');
            $status = 1;
            //Close the modal
            $js =  "window.parent.destroyModal('form_email');"; 
        }
    break;
    case 'adress':
        if (isset($_REQUEST['for']) && isset($_REQUEST['field']) && isset($_REQUEST['contact'])) {
            //
            if (isset($_REQUEST['contactid']) && !empty($_REQUEST['contactid'])) {
                //Clean up email
                $email = trim($_REQUEST['contact']);
                //Reset session adresses if necessary
                if (!isset($_SESSION['adresses'][$_REQUEST['field']])) $_SESSION['adresses'][$_REQUEST['field']] = array();
                if (!isset($_SESSION['adresses']['contactid'])) $_SESSION['adresses']['contactid'] = array();
                if (!isset($_SESSION['adresses']['addressid'])) $_SESSION['adresses']['addressid'] = array();
                //For ADD
                if ($_REQUEST['for'] == 'add') {
                    array_push($_SESSION['adresses'][$_REQUEST['field']], $email);
                    array_push($_SESSION['adresses']['contactid'], $_REQUEST['contactid']);
                    array_push($_SESSION['adresses']['addressid'], $_REQUEST['addressid']);
                //For DEL
                } else if ($_REQUEST['for'] == 'del') {
                    //unset adress in array
                    //unset($_SESSION['adresses'][$_REQUEST['field']][$_REQUEST['index']]);
                    array_splice($_SESSION['adresses'][$_REQUEST['field']], $_REQUEST['index'], 1);
                    array_splice($_SESSION['adresses']['contactid'], $_REQUEST['index'], 1);
					array_splice($_SESSION['adresses']['addressid'], $_REQUEST['index'], 1);
                    //If no adresse for field, unset the entire sub-array
                    if (count($_SESSION['adresses'][$_REQUEST['field']]) == 0) { 
                        unset($_SESSION['adresses'][$_REQUEST['field']]);
                        unset($_SESSION['adresses']['contactid']);
                        unset($_SESSION['adresses']['addressid']);
						//array_splice($_SESSION['adresses'], 0);
                    }
                }
                //Get content
                $content = $multicontacts->updateContactsInputField($path_to_script, $_SESSION['adresses'], $_REQUEST['field']);
            } else {
                $error = $request->wash_html(_SENDER.' '._IS_EMPTY.'!','NONE');
                $status = 1;
            }
        } else {
            $error = $request->wash_html(_UNKNOW_ERROR.'!','NONE');
            $status = 1;
        }
    break;
}

echo "{status : " . $status . ", content : '" . addslashes(_parse($content)) . "', error : '" . addslashes(_parse_error($error)) . "', exec_js : '".addslashes($js)."'}";
exit ();
?>