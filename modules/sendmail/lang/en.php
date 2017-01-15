<?php
/*
 *
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

if (!defined("_SENDMAIL"))
    define("_SENDMAIL", "Send emails");
if (!defined("_SENDMAIL_COMMENT"))
    define("_SENDMAIL_COMMENT", "Send emails");
if (!defined("_EMAIL_LIST"))
    define("_EMAIL_LIST", "Emails");
if (!defined("_EMAIL_LIST_DESC"))
    define("_EMAIL_LIST_DESC", "Emails list");
if (!defined("_SENDED_EMAILS"))
    define("_SENDED_EMAILS", "Emails");
 
//STATUS
if (!defined("_EMAIL_DRAFT"))
    define("_EMAIL_DRAFT","Draft");
if (!defined("_EMAIL_WAIT"))
    define("_EMAIL_WAIT","Wainting to be send");
if (!defined("_EMAIL_IN_PROGRESS"))
    define("_EMAIL_IN_PROGRESS","Being send");
if (!defined("_EMAIL_SENT"))
    define("_EMAIL_SENT","Sent");
if (!defined("_EMAIL_ERROR"))
    define("_EMAIL_ERROR","Error when sending");
    
//FORM
if (!defined("_FROM"))
    define("_FROM","Sender");
if (!defined("_FROM_SHORT"))
    define("_FROM_SHORT","From");
if (!defined("_SEND_TO"))
    define("_SEND_TO","Recipient");
if (!defined("_SEND_TO_SHORT"))
    define("_SEND_TO_SHORT","to");
if (!defined("_COPY_TO"))
    define("_COPY_TO","Copy");
if (!defined("_COPY_TO_SHORT"))
    define("_COPY_TO_SHORT","Cc");
if (!defined("_COPY_TO_INVISIBLE"))
    define("_COPY_TO_INVISIBLE","Hidden copy");
if (!defined("_COPY_TO_INVISIBLE_SHORT"))
    define("_COPY_TO_INVISIBLE_SHORT","Cci");
if (!defined("_JOINED_FILES"))
    define("_JOINED_FILES","Attachment files");
if (!defined("_SHOW_OTHER_COPY_FIELDS"))
    define("_SHOW_OTHER_COPY_FIELDS","Show/hide fields Cc and Cci");
if (!defined("_EMAIL_OBJECT"))
    define("_EMAIL_OBJECT","Subject");
if (!defined("_HTML_OR_RAW"))
    define("_HTML_OR_RAW","Advanced formatting / Plain text");  
if (!defined("_DEFAULT_BODY"))
    define("_DEFAULT_BODY","Your email is ready to be send with this attachment file :");
if (!defined("_NOTES_FILE"))
    define("_NOTES_FILE", "Notes of document");
if (!defined("_EMAIL_WRONG_FORMAT"))
    define("_EMAIL_WRONG_FORMAT", "Email address is not in the right format");
     
//ERROR
if (!defined("_EMAIL_NOT_EXIST"))
    define("_EMAIL_NOT_EXIST", "This email doesn't exist");

//ADD
if (!defined("_NEW_EMAIL"))
    define("_NEW_EMAIL","New mail");
if (!defined("_CREATE_EMAIL"))
    define("_CREATE_EMAIL", "Create");
if (!defined("_EMAIL_ADDED"))
    define("_EMAIL_ADDED", "Email added");
    
//SEND
if (!defined("_SEND_EMAIL"))
    define("_SEND_EMAIL","Send"); 
if (!defined("_RESEND_EMAIL"))
    define("_RESEND_EMAIL","Resend");
    
//SAVE
if (!defined("_SAVE_EMAIL"))
    define("_SAVE_EMAIL","Save");
    
//READ
if (!defined("_READ_EMAIL"))
    define("_READ_EMAIL","Read email");
    
//TRANSFER
if (!defined("_TRANSFER_EMAIL"))
    define("_TRANSFER_EMAIL","Forward email");
    
//EDIT    
if (!defined("_EDIT_EMAIL"))
    define("_EDIT_EMAIL", "Edit email");
if (!defined("_SAVE_EMAIL"))
    define("_SAVE_EMAIL", "Save");
if (!defined("_SAVE_COPY_EMAIL"))
    define("_SAVE_COPY_EMAIL", "Save a copy");
if (!defined("_EMAIL_UPDATED"))
    define("_EMAIL_UPDATED", "Email updated");
 
//REMOVE 
if (!defined("_REMOVE_EMAIL"))
    define("_REMOVE_EMAIL", "Remove");
if (!defined("_REMOVE_EMAILS"))
    define("_REMOVE_EMAILS", "Remove emails");
if (!defined("_REALLY_REMOVE_EMAIL"))
    define("_REALLY_REMOVE_EMAIL", "Do you really want to remove");
if (!defined("_EMAIL_REMOVED"))
    define("_EMAIL_REMOVED", "Email removed");


if (!defined('_Label_ADD_TEMPLATE_MAIL'))
    define('_Label_ADD_TEMPLATE_MAIL', 'Model of email');
if (!defined('_ADD_TEMPLATE_MAIL'))
    define('_ADD_TEMPLATE_MAIL', 'Choose a template');
if (!defined("_EMAIL_OBJECT_ANSWER"))
    define("_EMAIL_OBJECT_ANSWER", "Answer to your email from");
if (!defined("_USE_MAIL_SERVICES"))
    define("_USE_MAIL_SERVICES", "Use emails services as sender");
if (!defined("_USE_MAIL_SERVICES_DESC"))
    define("_USE_MAIL_SERVICES_DESC", "Use emails services as sender");
if (!defined("_INCORRECT_SENDER"))
    define("_INCORRECT_SENDER", "Incorrect sender");
