<?php

/*
*    Copyright 2008-2015 Maarch
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

/**
* @brief Contains functions to manage contacts
*
*
* @file
* @date $date$
* @version $Revision$
* @ingroup apps
*/

abstract class contacts_v2_Abstract extends Database
{
    /**
    * Return the contacts data in sessions vars
    *
    * @param string $mode add or up
    */
    public function contactinfo($mode)
    {
        // return the user information in sessions vars
        $func = new functions();
        $_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] =
            $_REQUEST['is_corporate'];
        if ($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'Y') {
            $_SESSION['m_admin']['contact']['SOCIETY'] = $func->wash(
                $_REQUEST['society'], 'no', _STRUCTURE_ORGANISM . ' ', 'yes', 0, 255
            );
            $_SESSION['m_admin']['contact']['LASTNAME'] = '';
            $_SESSION['m_admin']['contact']['FIRSTNAME'] = '';
            $_SESSION['m_admin']['contact']['FUNCTION'] = '';
            $_SESSION['m_admin']['contact']['TITLE'] = '';
        } else {
            $_SESSION['m_admin']['contact']['LASTNAME'] = $func->wash(
                $_REQUEST['lastname'], 'no', _LASTNAME, 'yes', 0, 255
            );
            $_SESSION['m_admin']['contact']['FIRSTNAME'] = $func->wash(
                $_REQUEST['firstname'], 'no', _FIRSTNAME, 'no', 0, 255
            );
            if ($_REQUEST['society'] <> '') {
                $_SESSION['m_admin']['contact']['SOCIETY'] = $func->wash(
                    $_REQUEST['society'], 'no', _STRUCTURE_ORGANISM . ' ', 'yes', 0, 255
                );
            } else {
                $_SESSION['m_admin']['contact']['SOCIETY'] = '';
            }
            if ($_REQUEST['function'] <> '') {
                $_SESSION['m_admin']['contact']['FUNCTION'] = $func->wash(
                    $_REQUEST['function'], 'no', _FUNCTION . ' ', 'yes', 0, 255
                );
            } else {
                $_SESSION['m_admin']['contact']['FUNCTION'] = '';
            }
            if ($_REQUEST['title'] <> '') {
                $_SESSION['m_admin']['contact']['TITLE'] = $func->wash(
                    $_REQUEST['title'], 'no', _TITLE2 . ' ', 'yes', 0, 255
                );
            } else {
                $_SESSION['m_admin']['contact']['TITLE'] = '';
            }
        }
        if ($_REQUEST['society_short'] <> '') {
            $_SESSION['m_admin']['contact']['SOCIETY_SHORT'] = $func->wash(
                $_REQUEST['society_short'], 'no', _SOCIETY_SHORT . ' ', 'yes', 0, 32
            );
        } else {
            $_SESSION['m_admin']['contact']['SOCIETY_SHORT'] = '';
        }

        $_SESSION['m_admin']['contact']['CONTACT_TYPE'] = $func->wash(
            $_REQUEST['contact_type'], 'no', _CONTACT_TYPE . ' ', 'yes', 0, 255
        );

        if ($_REQUEST['comp_data'] <> '') {
            $_SESSION['m_admin']['contact']['OTHER_DATA'] = $func->wash(
                $_REQUEST['comp_data'], 'no', _COMP_DATA . ' ', 'yes', 0, 255
            );
        } else {
            $_SESSION['m_admin']['contact']['OTHER_DATA'] = '';
        }

        if (isset($_REQUEST['owner']) && $_REQUEST['owner'] <> '') {
            if (preg_match('/\((.|\s|\d|\h|\w)+\)$/i', $_REQUEST['owner']) == 0) {
                $_SESSION['error'] = _CREATE_BY . ' ' . _WRONG_FORMAT . '.<br/>'
                                   . _USE_AUTOCOMPLETION;
            } else {
                $_SESSION['m_admin']['contact']['OWNER'] = str_replace(
                    ')', '', substr($_REQUEST['owner'],
                    strrpos($_REQUEST['owner'],'(')+1)
                );
                $_SESSION['m_admin']['contact']['OWNER'] = $func->wash(
                    $_SESSION['m_admin']['contact']['OWNER'], 'no',
                    _CREATE_BY . ' ', 'yes', 0, 32
                );
            }
        } else {
            $_SESSION['m_admin']['contact']['OWNER'] = '';
        }

        $_SESSION['m_admin']['contact']['order'] = $_REQUEST['order'];
        $_SESSION['m_admin']['contact']['order_field'] = $_REQUEST['order_field'];
        $_SESSION['m_admin']['contact']['what'] = $_REQUEST['what'];
        $_SESSION['m_admin']['contact']['start'] = $_REQUEST['start'];
    }

    public function is_exists($mode, $mycontact){
        $query = $this->query_contact_exists($mode);
        $db = new Database();
        $stmt = $db->query($query['query'], $query['params']);
        if($stmt->rowCount() > 0){
            if($mode <> 'up'){
                $_SESSION['error'] = _THE_CONTACT.' '._ALREADY_EXISTS;
            }

            if($mycontact == 'iframe'){
                $path_contacts_confirm = $_SESSION['config']['businessappurl'] . 'index.php?display=false&page=contacts_v2_confirm&popup';
            } else {
                $path_contacts_confirm = $_SESSION['config']['businessappurl'] . 'index.php?page=contacts_v2_confirm';
            }
            header(
                'location: ' . $path_contacts_confirm.'&mode='.$mode.'&mycontact='.$mycontact
            );
            exit;
        }
    }

    public function query_contact_exists($mode){

        $query = '';
        if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'N'){
            $query = "SELECT contact_id, contact_type, society, contact_firstname, contact_lastname, contact_enabled FROM view_contacts 
                WHERE lower(contact_firstname) = lower(?)
                  and lower(contact_lastname) = lower(?)";
            $arrayPDO = array($_SESSION['m_admin']['contact']['FIRSTNAME'], $_SESSION['m_admin']['contact']['LASTNAME']);

        } else if ($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'Y'){
            $query = "SELECT contact_id, contact_type, society, contact_firstname, contact_lastname, contact_enabled FROM view_contacts 
                WHERE lower(society) = lower(?)";
            $arrayPDO = array($_SESSION['m_admin']['contact']['SOCIETY']);
        }
        if ($mode == 'up'){
            $query .= " and contact_id <> ?";
            $arrayPDO = array_merge($arrayPDO, array($_SESSION['m_admin']['contact']['ID']));
        }
        return array("query" => $query, "params" => $arrayPDO);    
    }


    /**
    * Add ou modify contact in the database
    *
    * @param string $mode up or add
    */
    public function addupcontact($mode, $admin = true, $confirm = 'N', $mycontact = 'N')
    {
        $db = new Database();
        // add ou modify users in the database
        if($confirm == 'N'){
            $this->contactinfo($mode);
        }
        if (empty($_SESSION['error']) && $confirm == 'N') {
            $this->is_exists($mode, $mycontact);
        }
        $order = $_SESSION['m_admin']['contact']['order'];
        $order_field = $_SESSION['m_admin']['contact']['order_field'];
        $what = $_SESSION['m_admin']['contact']['what'];
        $start = $_SESSION['m_admin']['contact']['start'];

        if ($mode == 'add') {
            $path_contacts = $_SESSION['config']['businessappurl']
                           . 'index.php?page=contact_addresses_add&order='
                           . $order . '&order_field=' . $order_field . '&start='
                           . $start . '&what=' . $what;
        } else {
            $path_contacts = $_SESSION['config']['businessappurl']
                           . 'index.php?page=contacts_v2&order='
                           . $order . '&order_field=' . $order_field . '&start='
                           . $start . '&what=' . $what;                
        }

        $path_contacts_add_errors = $_SESSION['config']['businessappurl']
                                  . 'index.php?page=contacts_v2_add';
        $path_contacts_up_errors = $_SESSION['config']['businessappurl']
                                 . 'index.php?page=contacts_v2_up';
        if (! $admin) {
            if ($mode == 'add') {
                $path_contacts = $_SESSION['config']['businessappurl']
                               . 'index.php?page=contact_addresses_add&mycontact=Y&order='
                               . $order . '&order_field=' . $order_field . '&start='
                               . $start . '&what=' . $what;
            } else {
                $path_contacts = $_SESSION['config']['businessappurl']
                               . 'index.php?page=my_contacts&dir=my_contacts&load&order='
                               . $order . '&order_field=' . $order_field . '&start='
                               . $start . '&what=' . $what;                
            }

            $path_contacts_add_errors = $_SESSION['config']['businessappurl']
                                      . 'index.php?page=my_contact_add&dir='
                                      . 'my_contacts&load';
            $path_contacts_up_errors = $_SESSION['config']['businessappurl']
                                     . 'index.php?page=my_contact_up&dir='
                                     . 'my_contacts&load';
        }
        if ($mycontact == 'iframe') {
            if ($mode == 'add') {
                $path_contacts = $_SESSION['config']['businessappurl']
                                          . 'index.php?display=false&dir=my_contacts&page=create_address_iframe';
                $path_contacts_add_errors = $_SESSION['config']['businessappurl']
                                          . 'index.php?display=false&dir=my_contacts&page=create_contact_iframe';
            } else if ($mode == 'up') {
                $path_contacts =  $_SESSION['config']['businessappurl']
                                        . 'index.php?display=false&dir=my_contacts&page=info_contact_iframe&contactid='.$_SESSION['contact']['current_contact_id'].'&addressid='.$_SESSION['contact']['current_address_id'].'&created=Y';
                $path_contacts_up_errors = $_SESSION['config']['businessappurl']
                                        . 'index.php?display=false&dir=my_contacts&page=info_contact_iframe&contactid='.$_SESSION['contact']['current_contact_id'].'&addressid='.$_SESSION['contact']['current_address_id'];
            }
        }
        if (! empty($_SESSION['error'])) {
            if ($mode == 'up') {
                if (! empty($_SESSION['m_admin']['contact']['ID'])) {
                    header(
                        'location: ' . $path_contacts_up_errors . '&id='
                        . $_SESSION['m_admin']['contact']['ID']
                    );
                    exit;
                } else {
                    header('location: ' . $path_contacts);
                    exit;
                }
            }
            if ($mode == 'add') {
                header('location: ' . $path_contacts_add_errors);
                exit;
            }
        } else {
            if ($mode == 'add') {
                if($_SESSION['user']['UserId'] == 'superadmin'){
                    $entity_id = 'SUPERADMIN';
                } else {
                    $entity_id = $_SESSION['user']['primaryentity']['id'];
                }
                $query = 'INSERT INTO ' . $_SESSION['tablename']['contacts_v2']
                       . ' ( contact_type, lastname , firstname , society , society_short, function , '
                       . 'other_data,'
                       . " title, is_corporate_person, user_id, entity_id, creation_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, current_timestamp)";

                $db->query($query, array($_SESSION['m_admin']['contact']['CONTACT_TYPE'], $_SESSION['m_admin']['contact']['LASTNAME'], $_SESSION['m_admin']['contact']['FIRSTNAME']
                            , $_SESSION['m_admin']['contact']['SOCIETY'], $_SESSION['m_admin']['contact']['SOCIETY_SHORT'], $_SESSION['m_admin']['contact']['FUNCTION'], $_SESSION['m_admin']['contact']['OTHER_DATA']
                            , $_SESSION['m_admin']['contact']['TITLE'], $_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'], $_SESSION['user']['UserId'], $entity_id));
                if($_SESSION['history']['contactadd'])
                {
                    $stmt = $db->query("SELECT contact_id, creation_date FROM ".$_SESSION['tablename']['contacts_v2']
                        ." WHERE lastname = ? and firstname = ? and society = ? and function = ? and is_corporate_person = ? order by creation_date desc"
                        , array($_SESSION['m_admin']['contact']['LASTNAME'], $_SESSION['m_admin']['contact']['FIRSTNAME'], $_SESSION['m_admin']['contact']['SOCIETY']
                            , $_SESSION['m_admin']['contact']['FUNCTION'], $_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON']));
                    $res = $stmt->fetchObject();
                    $id = $res->contact_id;
                    if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'Y')
                    {
                        $msg =  _CONTACT_ADDED.' : '.functions::protect_string_db($_SESSION['m_admin']['contact']['SOCIETY']);
                    }
                    else
                    {
                        $msg =  _CONTACT_ADDED.' : '.functions::protect_string_db($_SESSION['m_admin']['contact']['LASTNAME'].' '.$_SESSION['m_admin']['contact']['FIRSTNAME']);
                    }
                    require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_history.php');
                    $hist = new history();
                    $hist->add($_SESSION['tablename']['contacts_v2'], $id,"ADD",'contacts_v2_add',$msg, $_SESSION['config']['databasetype']);
                }
                    $stmt = $db->query("SELECT contact_id, creation_date FROM ".$_SESSION['tablename']['contacts_v2']
                        ." WHERE lastname = ? and firstname = ? and society = ? and function = ? and is_corporate_person = ? order by creation_date desc"
                        , array($_SESSION['m_admin']['contact']['LASTNAME'], $_SESSION['m_admin']['contact']['FIRSTNAME'], $_SESSION['m_admin']['contact']['SOCIETY']
                            , $_SESSION['m_admin']['contact']['FUNCTION'], $_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON']));
                    $res = $stmt->fetchObject();
                    $id = $res->contact_id;
                    $_SESSION['contact']['current_contact_id'] = $id;
                
                $_SESSION['info'] = _CONTACT_ADDED;
                header("location: ".$path_contacts);
                exit;
            }
            elseif($mode == "up")
            {
                $query = "UPDATE ".$_SESSION['tablename']['contacts_v2']
                    ." SET update_date = current_timestamp, contact_type = ?, lastname = ?, firstname = ?,society = ?,society_short = ?,function = ?, other_data = ?, title = ?, is_corporate_person = ?";
                $query .= " WHERE contact_id = ?";
                $arrayPDO = array($_SESSION['m_admin']['contact']['CONTACT_TYPE'], $_SESSION['m_admin']['contact']['LASTNAME'], $_SESSION['m_admin']['contact']['FIRSTNAME']
                    , $_SESSION['m_admin']['contact']['SOCIETY'], $_SESSION['m_admin']['contact']['SOCIETY_SHORT'], $_SESSION['m_admin']['contact']['FUNCTION']
                    , $_SESSION['m_admin']['contact']['OTHER_DATA'], $_SESSION['m_admin']['contact']['TITLE'], $_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'], $_SESSION['m_admin']['contact']['ID']);

                $db->query($query, $arrayPDO);
                if($_SESSION['history']['contactup'])
                {
                    if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'Y')
                    {
                        $msg =  _CONTACT_MODIFIED.' : '.functions::protect_string_db($_SESSION['m_admin']['contact']['SOCIETY']);
                    }
                    else
                    {
                        $msg =  _CONTACT_MODIFIED.' : '.functions::protect_string_db($_SESSION['m_admin']['contact']['LASTNAME'].' '.$_SESSION['m_admin']['contact']['FIRSTNAME']);
                    }
                    require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_history.php');
                    $hist = new history();
                    $hist->add($_SESSION['tablename']['contacts_v2'], $_SESSION['m_admin']['contact']['ID'],"UP",'contacts_v2_up',$msg, $_SESSION['config']['databasetype']);
                }
                $this->clearcontactinfos();
                $_SESSION['info'] = _CONTACT_MODIFIED;
                if (isset($_SESSION['fromContactTree']) && $_SESSION['fromContactTree'] == "yes") {
                    unset($_SESSION['fromContactTree']);
                    header("location: ".$_SESSION['config']['businessappurl']. 'index.php?page=view_tree_contacts');
                    exit(); 
                } else {
                    header("location: ".$path_contacts);
                    exit();                    
                }
            }
        }
    }

    /**
    * Form to modify a contact v2
    *
    * @param  $string $mode up or add
    * @param int  $id  $id of the contact to change
    */
    public function formcontact($mode,$id = "", $admin = true, $iframe = false)
    {
        $db = new Database();
        if (preg_match("/MSIE 6.0/", $_SERVER["HTTP_USER_AGENT"]))
        {
            $browser_ie = true;
            $display_value = 'block';
        }
        elseif(preg_match('/msie/i', $_SERVER["HTTP_USER_AGENT"]) && !preg_match('/opera/i', $_SERVER["HTTP_USER_AGENT"]) )
        {
            $browser_ie = true;
            $display_value = 'block';
        }
        else
        {
            $browser_ie = false;
            $display_value = 'table-row';
        }
        $func = new functions();
        $state = true;
        if(!isset($_SESSION['m_admin']['contact']))
        {
            $this->clearcontactinfos();
        }
        if( $mode <> "add")
        {

            $query = "SELECT * FROM ".$_SESSION['tablename']['contacts_v2']." WHERE contact_id = ?";

            $stmt = $db->query($query, array($id));

            if($stmt->rowCount() == 0)
            {
                $_SESSION['error'] = _THE_CONTACT.' '._ALREADY_EXISTS;
                $state = false;
            }
            else
            {
                $_SESSION['m_admin']['contact'] = array();
                $line = $stmt->fetchObject();
                $_SESSION['m_admin']['contact']['ID'] = $line->contact_id;
                $_SESSION['m_admin']['contact']['TITLE'] = functions::show_string($line->title);
                $_SESSION['m_admin']['contact']['LASTNAME'] = functions::show_string($line->lastname);
                $_SESSION['m_admin']['contact']['FIRSTNAME'] = functions::show_string($line->firstname);
                $_SESSION['m_admin']['contact']['SOCIETY'] = functions::show_string($line->society);
                $_SESSION['m_admin']['contact']['SOCIETY_SHORT'] = functions::show_string($line->society_short);
                $_SESSION['m_admin']['contact']['FUNCTION'] = functions::show_string($line->function);
                $_SESSION['m_admin']['contact']['OTHER_DATA'] = functions::show_string($line->other_data);
                $_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] = functions::show_string($line->is_corporate_person);
                $_SESSION['m_admin']['contact']['CONTACT_TYPE'] = $line->contact_type;
                $_SESSION['m_admin']['contact']['OWNER'] = $line->user_id;
                if($admin && !empty($_SESSION['m_admin']['contact']['OWNER']))
                {
                    $stmt = $db->query("SELECT lastname, firstname FROM ".$_SESSION['tablename']['users']." WHERE user_id = ?",
                        array($_SESSION['m_admin']['contact']['OWNER']));
                    $res = $stmt->fetchObject();
                    $_SESSION['m_admin']['contact']['OWNER'] = $res->lastname.', '.$res->firstname.' ('.$_SESSION['m_admin']['contact']['OWNER'].')';
                }
            }
        }
        else if($mode == 'add' && !isset($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON']))
        {
            $_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] = 'Y';
        }
        require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_business_app_tools.php");
        $business = new business_app_tools();
        $tmp = $business->get_titles();
        $titles = $tmp['titles'];

        if($iframe != true){
            echo '<h1>';
            if($mode == "up") {
                ?><i class="fa fa-edit fa-2x"></i><?php
                echo '&nbsp;' . _MODIFY_CONTACT;
            }
            elseif($mode == "add") {
                ?><i class="fa fa-plus fa-2x"></i><?php
                echo '&nbsp;' . _ADD_NEW_CONTACT;
            }
            elseif($mode == "view") {
                ?><i class="fa fa-users fa-2x"></i><?php
                echo '&nbsp;' . _VIEW;
            }
            echo '</h1><br/><div class="block">';
        }else{
            echo '<div class="block"><h2>';
            if($mode == "up") {
                echo _CONTACT;
            }
            elseif($mode == "add") {
                echo _ADD_NEW_CONTACT;
            }
            elseif($mode == "view") {
                ?><i class="fa fa-users fa-2x"></i><?php
                echo '&nbsp;' . _VIEW;
            }
            echo '</h2>';
        }
        ?>
        <div id="inner_content_contact" class="clearfix" align="center" style="margin-bottom:15px;width:100% !important;"> 
            <?php
            if($state == false)
            {
                echo "<br /><br /><br /><br />"._THE_CONTACT." "._UNKOWN."<br /><br /><br /><br />";
            }
            else
            {
                $can_add_contact = ($admin ? "" : "Y");
                $action = $_SESSION['config']['businessappurl']."index.php?display=true&page=contacts_v2_up_db";
                if(!$admin)
                {
                    $action = $_SESSION['config']['businessappurl']."index.php?display=true&dir=my_contacts&page=my_contact_up_db";
                    if($iframe){
                        $action = $_SESSION['config']['businessappurl']."index.php?display=true&dir=my_contacts&page=my_contact_up_db&mycontact=iframe";
                    }
                }
                ?>
                <form name="frmcontact" id="frmcontact" method="post" action="<?php functions::xecho($action);?>" class="forms" style="width:700px">
                    <input type="hidden" name="display"  value="true" />
                    <?php if(!$admin)
                    {?>
                        <input type="hidden" name="dir"  value="my_contacts" />
                        <input type="hidden" name="page"  value="my_contact_up_db" />
                <?php   }
                    else
                    {?>
                        <input type="hidden" name="admin"  value="contacts_v2" />
                        <input type="hidden" name="page"  value="contacts_v2_up_db" />
                <?php 

                   }?>
                    <input type="hidden" name="order" id="order" value="<?php if(isset($_REQUEST['order'])) {functions::xecho($_REQUEST['order']);}?>" />
                    <input type="hidden" name="order_field" id="order_field" value="<?php if(isset($_REQUEST['order_field'])) { functions::xecho($_REQUEST['order_field']);}?>" />
                    <input type="hidden" name="what" id="what" value="<?php if(isset($_REQUEST['what'])){functions::xecho($_REQUEST['what']);}?>" />
                    <input type="hidden" name="start" id="start" value="<?php if(isset($_REQUEST['start'])){functions::xecho($_REQUEST['start']);}?>" />
                <table id="frmcontact_table">
                    <tr>
                        <td>&nbsp;</td>
                        <td class="indexing_field">
                            <input type="radio"  class="check" name="is_corporate"  value="Y" <?php if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'Y'){?> checked="checked"<?php } ?>/ onclick="javascript:show_admin_contacts( true, '<?php functions::xecho($display_value);?>');setContactType('corporate', '<?php echo ($can_add_contact);?>')" id="corpo_yes"><span onclick="$('corpo_yes').click();" onmouseover="this.style.cursor='pointer';"><?php echo _IS_CORPORATE_PERSON;?></span>
                            <input type="radio"  class="check" name="is_corporate" value="N" <?php if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'N'){?> checked="checked"<?php } ?> onclick="javascript:show_admin_contacts( false, '<?php functions::xecho($display_value);?>');setContactType('no_corporate', '<?php echo ($can_add_contact);?>')" id="corpo_no"><span onclick="$('corpo_no').click();" onmouseover="this.style.cursor='pointer';"><?php echo _INDIVIDUAL;?></span>
                        </td>
                        <td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </td>
                        <td>&nbsp;</td>
                    </tr>
                <?php if($admin && $mode == "up")
                {
                    ?>
                    <tr>
                        <td>
                            <label for="owner"><?php echo _CREATE_BY;?> : </label>
                        </td>
                        <td class="indexing_field"><input disabled name="owner" type="text"  id="owner" value="<?php functions::xecho($func->show_str($_SESSION['m_admin']['contact']['OWNER']));?>"/><div id="show_user" class="autocomplete"></div>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <?php
                }?>
                    <tr id="contact_types_tr" >
                        <td><?php echo _CONTACT_TYPE;?> :</td>
                        <td class="indexing_field">
                            <select name="contact_type" id="contact_type" 
                                <?php if($mode == "add"){ 
                                    ?> onchange="getContacts('<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&dir=my_contacts&page=getContacts', this.options[this.selectedIndex].value, 'view');" <?php 
                                } ?>
                                >
                                <option value=""><?php echo _CHOOSE_CONTACT_TYPES;?></option>

                            </select></td>
                        <td><span class="red_asterisk" style="visibility:visible;" id="contact_types_mandatory"><i class="fa fa-star"></i></span></td>
                    </tr>
                    <?php
                    if (defined('_EXAMPLE_SELECT_CONTACT_TYPE') && _EXAMPLE_SELECT_CONTACT_TYPE <> "") { ?>
                        <tr>
                            <td colspan="3"><i>&nbsp;<?php echo _EXAMPLE_SELECT_CONTACT_TYPE;?></i></td>
                        </tr>
              <?php } ?>

                    <tr id="contacts_created_tr" style="display:none">
                                    <td><?php echo _CONTACT_ALREADY_CREATED;?> : </td>
                                    <td class="indexing_field">
                                        <select id="contacts_created">
                                        </select>
                                    </td>
                                    <td><span><strong><em><?php echo _CONTACT_ALREADY_CREATED_INFORMATION;?></em></strong></span></td>

                                <?php
                                if (defined('_HELP_SELECT_CONTACT_CREATED') && _HELP_SELECT_CONTACT_CREATED <> "") { ?>
                                    <tr>
                                        <td colspan="3"><i>&nbsp;<?php echo _HELP_SELECT_CONTACT_CREATED;?></i></td>
                                    </tr>
                          <?php } ?>
                    </tr>

                    <tr>
                        <td><label for="society"><?php echo _STRUCTURE_ORGANISM;?> : </label></td>
                        <td class="indexing_field"><input name="society" type="text"  id="society" value="<?php if(isset($_SESSION['m_admin']['contact']['SOCIETY'])){ functions::xecho($func->show_str($_SESSION['m_admin']['contact']['SOCIETY'])); }?>"/></td>
                        <td class="indexing_field" style="display:<?php if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'N'){ echo 'none';}else{ functions::xecho($display_value);}?>"><span class="red_asterisk" style="visibility:visible;" id="society_mandatory"><i class="fa fa-star"></i></span></td>
                    </tr>
                    <tr>
                        <td><?php echo _SOCIETY_SHORT;?> :</td>
                        <td class="indexing_field"><input name="society_short" type="text"  id="society_short" value="<?php if(isset($_SESSION['m_admin']['contact']['SOCIETY_SHORT'])){ functions::xecho($func->show_str($_SESSION['m_admin']['contact']['SOCIETY_SHORT'])); }?>"/></td>
                    </tr>
                    <tr id="title_p" style="display:<?php if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'Y'){ echo 'none';}else{ functions::xecho($display_value);}?>">
                        <td><label for="title"><?php echo _TITLE2;?> : </label></td>
                        <td class="indexing_field"><select name="title" id="title" >
                            <option value=""><?php echo _CHOOSE_TITLE;?></option>
                            <?php
                            foreach(array_keys($titles) as $key)
                            {
                                ?><option value="<?php functions::xecho($key);?>" <?php

                                if((!isset($_SESSION['m_admin']['contact']['TITLE']) || empty($_SESSION['m_admin']['contact']['TITLE']))&& $key == $_SESSION['default_mail_title'])
                                {
                                     echo 'selected="selected"';
                                }
                                elseif(isset($_SESSION['m_admin']['contact']['TITLE']) && $key == $_SESSION['m_admin']['contact']['TITLE'] )
                                {
                                    echo 'selected="selected"';
                                }
                                ?>><?php functions::xecho($titles[$key]);?></option><?php
                            }?>
                        </select></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr id="lastname_p" style="display:<?php if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'Y'){ echo 'none';}else{ functions::xecho($display_value);}?>">
                        <td><label for="lastname"><?php echo _LASTNAME;?> : </label></td>
                        <td class="indexing_field"><input name="lastname" type="text" onfocus="$('rule_lastname').style.display='table-row'" onblur="$('rule_lastname').style.display='none';" onkeyup="this.value=this.value.toUpperCase()" id="lastname" value="<?php if(isset($_SESSION['m_admin']['contact']['LASTNAME'])){ functions::xecho($func->show_str($_SESSION['m_admin']['contact']['LASTNAME']));} ?>"/></td>
                        <td><span id="lastname_mandatory" class="red_asterisk" style="visibility:none;"><i class="fa fa-star"></i></span></td>
                        <td>&nbsp;</td>
                        <tr style="display:none;" id="rule_lastname">
                            <td colspan="2" align="right"><i><?php echo _WRITE_IN_UPPER;?></i></td>
                        </tr>
                    </tr>
                    <tr id="firstname_p" style="display:<?php if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'Y'){ echo 'none';}else{ functions::xecho($display_value);}?>">
                        <td><label for="firstname"><?php echo _FIRSTNAME;?> : </label></td>
                        <td class="indexing_field"><input name="firstname" type="text" id="firstname" onkeyup="this.value=capitalizeFirstLetter(this.value)" value="<?php if(isset($_SESSION['m_admin']['contact']['FIRSTNAME'])){ functions::xecho($func->show_str($_SESSION['m_admin']['contact']['FIRSTNAME']));} ?>"/></td>
                        <td><span id="firstname_mandatory" class="red_asterisk" style="visibility:hidden;"><i class="fa fa-star"></i></span></td>
                    </tr>
                    <tr id="function_p" style="display:<?php if(isset($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON']) && $_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'Y'){ echo 'none';}else{ functions::xecho($display_value);}?>">
                        <td><label for="function"><?php echo _FUNCTION;?> : </label></td>
                        <td class="indexing_field"><input name="function" type="text" id="function" value="<?php if(isset($_SESSION['m_admin']['contact']['FUNCTION'])){functions::xecho($func->show_str($_SESSION['m_admin']['contact']['FUNCTION']));} ?>"/></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><?php echo _COMP_DATA;?>&nbsp;:</td>
                        <td class="indexing_field"><textarea name="comp_data" id="comp_data"><?php if(isset($_SESSION['m_admin']['contact']['OTHER_DATA'])){functions::xecho($func->show_str($_SESSION['m_admin']['contact']['OTHER_DATA'])); }?></textarea></td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
                        <input name="mode" type="hidden" value="<?php echo $mode;?>" />
                        <br/>
                        <em style="display:none">(<?php echo _YOU_SHOULD_ADD_AN_ADDRESS;?>)</em>
                    <p>

                        <input class="button" type="submit" name="Submit" value="<?php echo _VALIDATE;?>" />

                    <?php
                    $cancel_target = $_SESSION['config']['businessappurl'].'index.php?page=contacts_v2';
                    if(!$admin) {
                        $cancel_target = $_SESSION['config']['businessappurl'].'index.php?page=my_contacts&amp;dir=my_contacts&amp;load';
                    }
                    if($iframe) { ?>
                        <input type="button" class="button"  name="cancel" value="<?php echo _CANCEL;?>" 

                <?php   if($_SESSION['info_contact_popup'] == "true"){?>
                            onclick="self.close();" 
                <?php   } else if ($_SESSION['AttachmentContact'] == "1") {?>
                            onclick="new Effect.BlindUp(parent.document.getElementById('create_contact_div_attach'));new Effect.BlindUp(parent.document.getElementById('info_contact_div_attach'));simpleAjax('<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&page=unsetAttachmentContact');return false;" <?php
                        } else { ?>
                            onclick="new Effect.BlindUp(parent.document.getElementById('create_contact_div'));new Effect.BlindUp(parent.document.getElementById('info_contact_div'));return false;"
                <?php   } ?>
                        />
              <?php } else {
                        if ($mode == 'view') { ?>
                            <input type="button" class="button"  name="cancel" value="<?php echo _BACK_TO_RESULTS_LIST;?>" onclick="history.go(-1);" />
                    <?php } else {
                                if (isset($_SESSION['fromContactTree']) && $_SESSION['fromContactTree'] == "yes"){
                                    ?><input type="button" class="button"  name="cancel" value="<?php echo _CANCEL;?>" onclick="window.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=view_tree_contacts';" /><?php
                                    // $_SESSION['fromContactTree'] = "";
                                } else {?>
                                    <input type="button" class="button"  name="cancel" value="<?php echo _CANCEL;?>" onclick="javascript:window.location.href='<?php functions::xecho($cancel_target);?>';" />                 
                    <?php       }
                            }           
                    }
                    ?>
                    </p>
                </form>

                <script type="text/javascript">setContactType("<?php if(isset($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON']) && $_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'N' ){ echo 'no_corporate'; } else { echo 'corporate'; }?>", '<?php echo ($can_add_contact);?>');</script>

            <?php
                if($mode=="up" && $admin)
                {
                    ?><script type="text/javascript">launch_autocompleter('<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&page=users_autocomplete_list', 'owner', 'show_user');</script><?php
                }
            }
            ?>
        </div>
    <?php
        if($iframe != true){
            echo '</div><br/>';
        }
    }

    public function chooseContact($clean = true){

        $db = new Database();
        if ($clean) {
            $this->clearcontactinfos();
        }
        ?>
        <div class="block"><h2></i>
            <?php
                echo '&nbsp;' . _ADD_ADDRESS_TO_CONTACT;
            ?>
        </h2>
        <br/>
            <span style="margin-left:30px;">
                <?php echo '&nbsp;'. _ADD_ADDRESS_TO_CONTACT_DESC;?>
            </span>
            <br/>
            <br/>
                <form class="forms" method="post" style="margin-left:30px;width:80%;">
                    <table style="margin:auto;">
                        <tr>
                            <td><?php echo '&nbsp;'. _TYPE_OF_THE_CONTACT;?></td>
                            <td>
                                <select id="contact_type_selected" onchange="getContacts('<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&dir=my_contacts&page=getContacts', this.options[this.selectedIndex].value, 'set');">
                                    <option value="all"><?php echo _ALL;?></option>
                                    <?php
                                        $stmt = $db->query("SELECT id, label FROM contact_types ORDER BY label");
                                        while ($res_label = $stmt->fetchObject()){
                                            ?><option value="<?php functions::xecho($res_label->id);?>"><?php functions::xecho($res_label->label);?></option>
                                        <?php
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo '&nbsp;'. _WHICH_CONTACT;?></td>
                            <td>                                
                                <select id="contactSelect">
                                    <option value=""><?php echo _CHOOSE_A_CONTACT;?></option>
                                    <?php
                                        $stmt = $db->query("SELECT contact_id, society, firstname, lastname, is_corporate_person FROM contacts_v2 WHERE enabled = 'Y' ORDER BY is_corporate_person desc, society, lastname");
                                        while ($res_contact = $stmt->fetchObject()){
                                            ?><option value="<?php functions::xecho($res_contact->contact_id);?>"><?php
                                            if ($res_contact->is_corporate_person == "Y") {
                                                functions::xecho($res_contact->society);
                                            } else if ($res_contact->is_corporate_person == "N") {
                                                functions::xecho($res_contact->lastname .' '. $res_contact->firstname);
                                            } ?>
                                            </option>
                                        <?php
                                        }
                                    ?>
                                </select>
                                <span class="red_asterisk"><i class="fa fa-star"></i></span>
                            </td>
                        </tr>
                    </table>
                    <div style="text-align:center;"><input class="button" type="button" value="<?php echo _CHOOSE_THIS_CONTACT;?>" onclick="putInSessionContact('<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&dir=my_contacts&page=put_in_session');" /></div>
                </form>
                </div>
                </div>
            <!-- <input id="contactid" type="hidden"/> -->
            <?php

    }

    /**
    * Clear the session variables of the edmit 's administration
    *
    */
    protected function clearcontactinfos()
    {
        // clear the session variable
        unset($_SESSION['m_admin']);
    }

    /**
    * Clear the session variables of the edmit 's administration
    *
    */
    protected function clearaddressinfos()
    {
        // clear the session variable
        unset($_SESSION['m_admin']['address']);
    }
    
    /**
    * delete a contact in the database
    *
    * @param string $id contact identifier
    */
    public function delcontact($id, $admin = true)
    {
        $db = new Database();
        $element_found = false;
        $nb_docs = 0;
        $tables = array();
        $_SESSION['m_admin']['contact'] = array();
        $order = $_REQUEST['order'];
        $order_field = $_REQUEST['order_field'];
        $start = $_REQUEST['start'];
        $what = $_REQUEST['what'];
        $path_contacts = $_SESSION['config']['businessappurl']."index.php?page=contacts_v2&order=".$order."&order_field=".$order_field."&start=".$start."&what=".$what;
        if(!$admin)
        {
            $path_contacts = $_SESSION['config']['businessappurl']."index.php?page=my_contacts&dir=my_contacts&load&order=".$order."&order_field=".$order_field."&start=".$start."&what=".$what;
        }
        
        if(!empty($id))
        {
            $stmt = $db->query("SELECT res_id FROM ".$_SESSION['collections'][0]['view'] 
                . " WHERE exp_contact_id = ? or dest_contact_id = ?",
                array($id, $id));
            if($stmt->rowCount() > 0)$nb_docs = $nb_docs + $stmt->rowCount();

                $stmt = $db->query("SELECT contact_id FROM contacts_res WHERE contact_id = ?", array($id));
                if($stmt->rowCount() > 0)$nb_docs = $nb_docs + $stmt->rowCount();
                         
            if ($nb_docs == 0)
            {
                $query = "SELECT contact_id FROM ".$_SESSION['tablename']['contacts_v2']." WHERE contact_id = ? ";
                $arrayPDO = array($id);
                if(!$admin)
                {
                    $query .= " and user_id = ?";
                    $arrayPDO = array_merge($arrayPDO, array($_SESSION['user']['UserId']));
                }
                $stmt = $db->query($query, $arrayPDO);
                if($stmt->rowCount() == 0)
                {
                    $_SESSION['error'] = _CONTACT.' '._UNKNOWN;
                }
                else
                {
                    $res = $stmt->fetchObject();
                    $db->query("DELETE FROM " . $_SESSION['tablename']['contacts_v2'] . " WHERE contact_id = ?", array($id));
                    $db->query("DELETE FROM " . $_SESSION['tablename']['contact_addresses'] . " WHERE contact_id = ?", array($id));
                    if($_SESSION['history']['contactdel'])
                    {
                        require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_history.php');
                        $hist = new history();
                        $hist->add($_SESSION['tablename']['contacts_v2'], $id,"DEL","contactdel",_CONTACT_DELETED.' : '.$id, $_SESSION['config']['databasetype']);
                        $hist->add($_SESSION['tablename']['contact_addresses'], $id,"DEL","contact_addresses_del", _ADDRESS_DEL." ".strtolower(_NUM).$id."", $_SESSION['config']['databasetype']);
                    }
                    $_SESSION['info'] = _CONTACT_DELETED;
                }
            }
            else
            {
                ?> 
                <br>
                <div id="main_error">
                    <b><?php
                        echo _WARNING_MESSAGE_DEL_CONTACT;
                    ?></b>
                </div>
                <br>
                <br>
                
                <h1>
                    <i class="fa fa-remove fa-2x"></i>
                    <?php echo _CONTACT_DELETION;?>
                </h1>
                
                <form name="entity_del" id="entity_del" method="post" class="forms">
                    <input type="hidden" value="<?php functions::xecho($id);?>" name="id">
                    <h2 class="tit"><?php echo _CONTACT_REAFFECT." : <i>".$label."</i>";?></h2>
                    <?php
                    if($nb_docs > 0)
                    {
                        echo "<br><b> - ".$nb_docs."</b> "._DOC_SENDED_BY_CONTACT;
                        
                        ?>
                        <br>
                        <br>
                        <input type="hidden" value="documents" name="documents">
                            <td>
                                <label for="contact_list"><?php echo _NEW_CONTACT;?> : </label>
                            </td>
                            <td class="indexing_field">
                                <input name="contact_list" type="text"  id="contact_list" value=""/>
                                <div id="show_contact" class="autocomplete">
                                    <script type="text/javascript">
                                        initList_hidden_input('contact_list', 'show_contact', '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&page=contacts_v2_list_by_name&id=<?php functions::xecho($id);?>', 'what', '2', 'contact');
                                    </script>
                                </div>
                                <input type="hidden" id="contact" name="contact" />
                            </td>
                        <br>
                        <br>
                            <td>
                                <label for="address_list"><?php echo _NEW_ADDRESS;?> : </label>
                            </td>
                            <td class="indexing_field">
                                <input name="address_list" type="text"  id="address_list" value=""/>
                                <div id="show_address" class="autocomplete">
                                    <script type="text/javascript">
                                        initList_hidden_input_before('address_list', 'show_address', '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&page=contact_addresses_list_by_name', 'what', '2', 'address', 'idContact', 'contact');
                                    </script>
                                </div>
                                <input type="hidden" id="address" name="address" />
                            </td>
                            
                        <br/>                     
                        <br/>
                        <p class="buttons">
                            <input type="submit" value="<?php echo _DEL_AND_REAFFECT;?>" name="valid" class="button" onclick="return(confirm('<?php echo _REALLY_DELETE;  if(isset($page_name) && $page_name == "users"){ echo $complete_name;} elseif(isset($admin_id)){ echo " ".$admin_id; }?> ?\n\r\n\r<?php echo _DEFINITIVE_ACTION;?>'));"/>
                            <input type="button" value="<?php echo _CANCEL;?>" onclick="window.location.href='<?php functions::xecho($path_contacts);?>';" class="button" />
                        </p>
                      <?php
                    }
                    ?>
                </form>
                <?php
                exit;
            }
        } else {
            $_SESSION['error'] = _CONTACT.' '._EMPTY;
        }
        
        ?>
        <script type="text/javascript">
            window.location.href="<?php functions::xecho($path_contacts);?>";
        </script>
        <?php
        exit;
    }

    function get_contact_information_from_view($category_id, $contact_lastname="", $contact_firstname="", $contact_society="", $user_lastname="", $user_firstname="")
    {
        if ($category_id == 'incoming')
        {
            $prefix = "<b>"._TO_CONTACT_C."</b>";
        }
        elseif ($category_id == 'outgoing'  || $category_id == 'internal')
        {
            $prefix = "<b>"._FOR_CONTACT_C."</b>";
        }
        else {
            $prefix = '';
        }
        if($contact_lastname <> "")
        {
            $lastname = $contact_lastname;
            $firstname = $contact_firstname;
        }
        else
        {
            $lastname = $user_lastname;
            $firstname = $user_firstname;
        }
        if($contact_society <> "")
        {
            if ($firstname =='' && $lastname == '')
            {
                $society = $contact_society;
            }
            else
            {
                $society = " (".$contact_society.") ";
            }
        }
        else
            $society = "";
        $the_contact =$prefix." ".$firstname." ".$lastname." ".$society;
        return $the_contact;
    }

    /**
    * Form to modify or add an address v2
    *
    * @param  $string $mode up or add
    * @param int  $id  $id of the contact to change
    */
    public function formaddress($mode,$id = "", $admin = true, $iframe = "")
    {
        $db = new Database();
        if (preg_match("/MSIE 6.0/", $_SERVER["HTTP_USER_AGENT"]))
        {
            $browser_ie = true;
            $display_value = 'block';
        }
        elseif(preg_match('/msie/i', $_SERVER["HTTP_USER_AGENT"]) && !preg_match('/opera/i', $_SERVER["HTTP_USER_AGENT"]) )
        {
            $browser_ie = true;
            $display_value = 'block';
        }
        else
        {
            $browser_ie = false;
            $display_value = 'table-row';
        }
        $func = new functions();
        $state = true;
        if(!isset($_SESSION['m_admin']['address']) && !isset($_SESSION['m_admin']['contact']))
        {
            $this->clearcontactinfos();
        }
        if( $mode <> "add")
        {
            $query = "SELECT * FROM ".$_SESSION['tablename']['contact_addresses']." WHERE id = ?";
            $arrayPDO = array($id);
            $core_tools = new core_tools();
            if(!$admin && !$core_tools->test_service('update_contacts', 'apps', false))
            {
                $query .= " and user_id = ?";
                $arrayPDO = array_merge($arrayPDO, array($_SESSION['user']['UserId']));
            }
            $stmt = $db->query($query, $arrayPDO);

            if($stmt->rowCount() == 0)
            {
                $_SESSION['error'] = _THE_ADDRESS.' '._ALREADY_EXISTS;
                $state = false;
            }
            else
            {
                if (!isset($_SESSION['address_up_error'])) {
                    $_SESSION['m_admin']['address'] = array();
                    $line = $stmt->fetchObject();
                    $_SESSION['m_admin']['address']['ID'] = $line->id;
                    $_SESSION['m_admin']['address']['CONTACT_ID'] = $line->contact_id;
                    $_SESSION['m_admin']['address']['TITLE'] = functions::show_string($line->title);
                    $_SESSION['m_admin']['address']['LASTNAME'] = functions::show_string($line->lastname);
                    $_SESSION['m_admin']['address']['FIRSTNAME'] = functions::show_string($line->firstname);
                    $_SESSION['m_admin']['address']['FUNCTION'] = functions::show_string($line->function);
                    $_SESSION['m_admin']['address']['OTHER_DATA'] = functions::show_string($line->other_data);
                    $_SESSION['m_admin']['address']['OWNER'] = $line->user_id;
                    $_SESSION['m_admin']['address']['DEPARTEMENT'] = functions::show_string($line->departement);
                    $_SESSION['m_admin']['address']['CONTACT_PURPOSE_ID'] = $line->contact_purpose_id;
                    $_SESSION['m_admin']['address']['OCCUPANCY'] = functions::show_string($line->occupancy);
                    $_SESSION['m_admin']['address']['ADD_NUM'] = functions::show_string($line->address_num);
                    $_SESSION['m_admin']['address']['ADD_STREET'] = functions::show_string($line->address_street);
                    $_SESSION['m_admin']['address']['ADD_COMP'] = functions::show_string($line->address_complement);
                    $_SESSION['m_admin']['address']['ADD_TOWN'] = functions::show_string($line->address_town);
                    $_SESSION['m_admin']['address']['ADD_CP'] = functions::show_string($line->address_postal_code);
                    $_SESSION['m_admin']['address']['ADD_COUNTRY'] = functions::show_string($line->address_country);
                    $_SESSION['m_admin']['address']['PHONE'] = functions::show_string($line->phone);
                    $_SESSION['m_admin']['address']['MAIL'] = functions::show_string($line->email);
                    $_SESSION['m_admin']['address']['WEBSITE'] = functions::show_string($line->website);
                    $_SESSION['m_admin']['address']['IS_PRIVATE'] = functions::show_string($line->is_private);
                    $_SESSION['m_admin']['address']['SALUTATION_HEADER'] = functions::show_string($line->salutation_header);
                    $_SESSION['m_admin']['address']['SALUTATION_FOOTER'] = functions::show_string($line->salutation_footer);
                } else {
                    unset($_SESSION['address_up_error']);
                }
                if($admin && !empty($_SESSION['m_admin']['address']['OWNER']))
                {
                    $stmt = $db->query("SELECT lastname, firstname FROM ".$_SESSION['tablename']['users']." WHERE user_id = ?", array($_SESSION['m_admin']['address']['OWNER']));
                    $res = $stmt->fetchObject();
                    $_SESSION['m_admin']['address']['OWNER'] = $res->lastname.', '.$res->firstname.' ('.$_SESSION['m_admin']['address']['OWNER'].')';
                }
            }
        }
        else if($mode == 'add' && !isset($_SESSION['m_admin']['address']['IS_PRIVATE']))
        {
            $_SESSION['m_admin']['address']['IS_PRIVATE'] = 'N';
        }
        require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_business_app_tools.php");
        $business = new business_app_tools();
        $tmp = $business->get_titles();
        $titles = $tmp['titles'];

        $contact_purposes = array();

        $stmt = $db->query("SELECT id, label FROM ".$_SESSION['tablename']['contact_purposes']);
        while($res = $stmt->fetchObject()){
            $contact_purposes[$res->id] = functions::show_string($res->label); 
        }

        if($iframe != true){
            echo '<h1>';
            if($mode == "up")
            {?><i class="fa fa-edit fa-2x"></i> <?php
                echo _MODIFY_ADDRESS;
            }
            elseif($mode == "add")
            {?><i class="fa fa-plus fa-2x"></i> <?php
                echo _ADDITION_ADDRESS;
            }
            echo '</h1>';
        }else{
            echo '<div class="block"><h2>';
            if($mode == "up")
            {
                echo _MODIFY_ADDRESS;
            }
            elseif($mode == "add")
            {
                echo _ADDITION_ADDRESS;
            }
            echo '</h2>';
        }


        if($iframe != true){
                echo '<div id="inner_content_contact" class="clearfix" align="center">';
            }else{
                echo '<div id="inner_content_contact" class="clearfix" align="center">';
            } 
            if($state == false)
            {
                echo "<br /><br /><br /><br />"._THE_ADDRESS." "._UNKOWN."<br /><br /><br /><br />";
            }
            else
            {
                $this->get_contact_form();
                $action = $_SESSION['config']['businessappurl']."index.php?display=true&page=contact_addresses_up_db";
                $fieldAddressClass = "address_modification_field";
                $fieldSalutationClass = "salutation_modification_field";
                if(!$admin)
                {
                    $action = $_SESSION['config']['businessappurl']."index.php?display=true&page=contact_addresses_up_db&mycontact=Y";
                }
                if($iframe == "iframe"){
                    $action = $_SESSION['config']['businessappurl']."index.php?display=false&page=contact_addresses_up_db&mycontact=iframe";
                    $fieldAddressClass = "address_modification_field_frame";
                    $fieldSalutationClass = "salutation_modification_field_frame";
                } else if($iframe == "iframe_add_up") {
                    $action = $_SESSION['config']['businessappurl']."index.php?display=false&page=contact_addresses_up_db&mycontact=iframe_add_up";
                } else if($iframe == "fromContactIframe"){
                    $action = $_SESSION['config']['businessappurl']."index.php?display=false&page=contact_addresses_up_db&mycontact=fromContactIframe";
                }
                if (isset($_SESSION['contact_address']['fromContactAddressesList']) && $_SESSION['contact_address']['fromContactAddressesList'] <> "") {
                    $action = $_SESSION['config']['businessappurl'].'index.php?display=true&page=contact_addresses_up_db&fromContactAddressesList';
                }
                ?>
                <form name="frmcontact" id="frmcontact" method="post" action="<?php echo($action);?>" class="forms">
                    <input type="hidden" name="display"  value="true" />
                    <?php if(!$admin)
                    {?>
                        <input type="hidden" name="dir"  value="my_contacts" />
                        <input type="hidden" name="page"  value="my_contact_up_db" />
                <?php   }
                    else
                    {?>
                        <input type="hidden" name="admin"  value="contacts_v2_up" />
                        <input type="hidden" name="page"  value="contact_addresses_up_db" />
                <?php   }?>
                    <input type="hidden" name="order" id="order" value="<?php if(isset($_REQUEST['order'])) {functions::xecho($_REQUEST['order']);}?>" />
                    <input type="hidden" name="order_field" id="order_field" value="<?php if(isset($_REQUEST['order_field'])) { functions::xecho($_REQUEST['order_field']);}?>" />
                    <input type="hidden" name="what" id="what" value="<?php if(isset($_REQUEST['what'])){functions::xecho($_REQUEST['what']);}?>" />
                    <input type="hidden" name="start" id="start" value="<?php if(isset($_REQUEST['start'])){ functions::xecho($_REQUEST['start']);}?>" />
                <table width="65%">
                    <tr align="left">
                        <td colspan="4" onclick="new Effect.toggle('address_div', 'blind', {delay:0.2});
                        whatIsTheDivStatus('address_div', 'divStatus_address_div');"><label>
                            <span id="divStatus_address_div" style="color:#1C99C5;">>></span>&nbsp;
                            <b><?php echo _ADDRESS;?> </b></label>
                        </td>
                    </tr>
                </table>
                <div id="address_div"  style="display:inline">
                    <table width="65%" id="frmaddress_table1" style="position:relative;">
                    <?php
                    if ($mode == "add") { ?>
                        <tr id="previous_address_tr" >
                            <td></td>
                            <td>
                                <select onchange="setPreviousAddress(this.options[this.selectedIndex].value)" class="<?php echo $fieldAddressClass;?>">
                                    <option value=""><?php echo _USE_PREVIOUS_ADDRESS;?></option>
                                    <?php
                                        $stmt = $db->query("SELECT distinct address_num, address_street, address_complement, address_postal_code, address_town, address_country, website FROM contact_addresses WHERE contact_id = ?", array($_SESSION['m_admin']['contact']['ID']) );
                                        while ($result = $stmt->fetchObject()) {
                                            if ($result->address_num <> "" || $result->address_street <> "" || $result->address_postal_code <> "" || $result->address_town <> "" || $result->address_country <> "") {
                                                $pipeAddress = $result->address_num . "||" . $result->address_street . "||" . $result->address_complement  . "||" . $result->address_postal_code . "||" . $result->address_town . "||" . $result->address_country . "||" . $result->website;
                                                $AddressReplacePipe = str_replace("||", " ", $pipeAddress);
                                                ?>
                                                <option value="<?php echo $pipeAddress ;?>">
                                                    <?php echo $AddressReplacePipe;?>
                                                </option><?php
                                            }
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr> <?php
                    }
                    ?>
                        <tr id="contact_purposes_tr" >
                            <td><label for="contact_purposes"><?php echo _CONTACT_PURPOSE;?>&nbsp;:&nbsp;</label>

                            </td>
                            <td>

                                <input class="<?php echo $fieldAddressClass;?>" name="new_id" id="new_id" onfocus="$('rule_purpose').style.display='table-row'" onblur="purposeCheck();$('rule_purpose').style.display='none'";
                                    <?php if(isset($_SESSION['m_admin']['address']['CONTACT_PURPOSE_ID']) && $_SESSION['m_admin']['address']['CONTACT_PURPOSE_ID'] <> '')
                                        {
                                            echo 'value="'.functions::xssafe($this->get_label_contact($_SESSION['m_admin']['address']['CONTACT_PURPOSE_ID'],$_SESSION['tablename']['contact_purposes'])).'"';
                                        } else {
                                            echo 'value="'._MAIN_ADDRESS.'"';
                                        } 
                                    ?>
                                />
                                <div id="show_contact" class="autocomplete">
                                    <script type="text/javascript">
                                        initList_hidden_input('new_id', 'show_contact', '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&page=contact_purposes_list_by_name&id=<?php functions::xecho($id);?>', 'what', '2', 'contact_purposes');
                                    </script>
                                </div>
                                <input type="hidden" id="contact_purposes" name="contact_purposes"                             
                                    <?php if(isset($_SESSION['m_admin']['address']['CONTACT_PURPOSE_ID']) && $_SESSION['m_admin']['address']['CONTACT_PURPOSE_ID'] <> '')
                                        {
                                            echo 'value="'.functions::xssafe($_SESSION['m_admin']['address']['CONTACT_PURPOSE_ID']).'"';
                                        } 
                                    ?>
                                />
                                <span class="red_asterisk" style="visibility:visible;" id="contact_purposes_mandatory"><i class="fa fa-star"></i></span>
                            </td>
                        </tr>
                        <tr style="display:none;" id="rule_purpose">
                            <td>&nbsp;</td>
                            <td align="left" ><i><?php echo _EXAMPLE_PURPOSE;?></i></td>
                        </tr>
                        <tr id="purpose_to_create" style="display:none">
                            <td colspan="3">
                                <em><?php echo _CONTACT_PURPOSE_WILL_BE_CREATED;?></em>
                            </td>
                        </tr>
                        
                        <tr id="departement_p">
                            <td><label for="departement"><?php echo _SERVICE;?>&nbsp;: </label></td>
                            <td>
                                <input class="<?php echo $fieldAddressClass;?>" name="departement" type="text" onfocus="$('rule_departement').style.display='table-row'" onblur="$('rule_departement').style.display='none';" id="departement" value="<?php if(isset($_SESSION['m_admin']['address']['DEPARTEMENT'])){ functions::xecho($func->show_str($_SESSION['m_admin']['address']['DEPARTEMENT']));} ?>"/>
                                <span class="blue_asterisk" style="visibility:visible;">*</span>
                            </td>
                        </tr>
                        
                        <tr id="title_p" style="display:<?php if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'N'){ echo 'none';}else{ functions::xecho($display_value);}?>">
                            <td><label for="title"><?php echo _TITLE2;?>&nbsp;: </label></td>
                            <td>
                                <select class="<?php echo $fieldAddressClass;?>" name="title" id="title" >
                                    <option value=""><?php echo _CHOOSE_TITLE;?></option>
                                    <?php
                                    foreach(array_keys($titles) as $key)
                                    {
                                        ?><option value="<?php functions::xecho($key);?>" <?php

                                        if((!isset($_SESSION['m_admin']['address']['TITLE']) || empty($_SESSION['m_admin']['address']['TITLE']))&& $key == $_SESSION['default_mail_title'])
                                        {
                                             echo 'selected="selected"';
                                        }
                                        elseif(isset($_SESSION['m_admin']['address']['TITLE']) && $key == $_SESSION['m_admin']['address']['TITLE'] )
                                        {
                                            echo 'selected="selected"';
                                        }
                                        ?>><?php functions::xecho($titles[$key]);?></option><?php
                                    }?>
                                </select>
                            </td>
                        </tr>
                        <tr id="lastname_p" style="display:<?php if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'N'){ echo 'none';}else{ functions::xecho($display_value);}?>">
                            <td><label for="lastname"><?php echo _LASTNAME;?> : </label></td>
                            <td>
                                <input class="<?php echo $fieldAddressClass;?>" name="lastname" type="text" onkeyup="this.value=this.value.toUpperCase()" onfocus="$('rule_lastname').style.display='table-row'" onblur="$('rule_lastname').style.display='none';" id="lastname" value="<?php if(isset($_SESSION['m_admin']['address']['LASTNAME'])){ functions::xecho($func->show_str($_SESSION['m_admin']['address']['LASTNAME']));} ?>"/>
                            </td>
                        </tr>
                        <tr style="display:none;" id="rule_lastname">
                            <td>&nbsp;</td>
                            <td align="left"><i><?php echo _WRITE_IN_UPPER;?></i></td>
                        </tr>
                        <tr id="firstname_p" style="display:<?php if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'N'){ echo 'none';}else{ functions::xecho($display_value);}?>">
                            <td><label for="firstname"><?php echo _FIRSTNAME;?>&nbsp;: </label></td>
                            <td>
                                <input class="<?php echo $fieldAddressClass;?>" name="firstname" type="text"  id="firstname" onkeyup="this.value=capitalizeFirstLetter(this.value)" value="<?php if(isset($_SESSION['m_admin']['address']['FIRSTNAME'])){ functions::xecho($func->show_str($_SESSION['m_admin']['address']['FIRSTNAME']));} ?>"/>
                            </td>
                        </tr>
                        <tr id="function_p" style="display:<?php if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'N'){ echo 'none';}else{ functions::xecho($display_value);}?>">
                            <td><label for="function"><?php echo _FUNCTION;?>&nbsp;: </label></td>
                            <td>
                                <input class="<?php echo $fieldAddressClass;?>" name="function" type="text"  id="function" value="<?php if(isset($_SESSION['m_admin']['address']['FUNCTION'])){functions::xecho($func->show_str($_SESSION['m_admin']['address']['FUNCTION']));} ?>"/>
                                <span class="blue_asterisk" style="visibility:visible;">*</span>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo _OCCUPANCY;?> : </td>
                            <td>
                                <input class="<?php echo $fieldAddressClass;?>" name="occupancy" type="text"  id="occupancy" value="<?php if(isset($_SESSION['m_admin']['address']['OCCUPANCY'])){functions::xecho($func->show_str($_SESSION['m_admin']['address']['OCCUPANCY'])); }?>"/>
                                <span class="blue_asterisk" style="visibility:visible;">*</span>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="num"><?php echo _NUM;?> : </label></td>
                            <td>
                                <input class="<?php echo $fieldAddressClass;?>" name="num" type="text"  id="num" value="<?php if(isset($_SESSION['m_admin']['address']['ADD_NUM'])){functions::xecho($func->show_str($_SESSION['m_admin']['address']['ADD_NUM'])); }?>"/>
                                <span class="blue_asterisk" style="visibility:visible;">*</span>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="street"><?php echo _STREET;?> : </label></td>
                            <td>
                                <input class="<?php echo $fieldAddressClass;?>" name="street" type="text"  id="street" value="<?php if(isset($_SESSION['m_admin']['address']['ADD_STREET'])){ functions::xecho($func->show_str($_SESSION['m_admin']['address']['ADD_STREET'])); }?>"/>
                                <span class="blue_asterisk" style="visibility:visible;">*</span>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="add_comp"><?php echo _COMPLEMENT;?>&nbsp;: </label></td>
                            <td>
                                <input class="<?php echo $fieldAddressClass;?>" name="add_comp" type="text"  id="add_comp" value="<?php if(isset($_SESSION['m_admin']['address']['ADD_COMP'])){ functions::xecho($func->show_str($_SESSION['m_admin']['address']['ADD_COMP'])); }?>"/>
                                <span class="blue_asterisk" style="visibility:visible;">*</span>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo _POSTAL_CODE;?>&nbsp;:</td>
                            <td>
                                <input class="<?php echo $fieldAddressClass;?>" name="cp" type="text" id="cp" value="<?php if(isset($_SESSION['m_admin']['address']['ADD_CP'])){functions::xecho($func->show_str($_SESSION['m_admin']['address']['ADD_CP'])); }?>"/>
                                <span class="blue_asterisk" style="visibility:visible;">*</span>
                                <div id="show_town" class="autocomplete">
                                    <script type="text/javascript">
                                        initList_hidden_input2('cp', 'show_town', '<?php $_SESSION['config']['businessappurl'];?>index.php?display=true&dir=indexing_searching&page=ajaxShowVille&id=<?php functions::xecho($id);?>', 'what', '2', 'town', 'cp');
                                    </script>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="town"><?php echo _TOWN;?> : </label></td>
                            <td>
                                <input class="<?php echo $fieldAddressClass;?>" name="town" type="text" id="town" onfocus="$('rule_town').style.display='table-row'" onblur="$('rule_town').style.display='none';"value="<?php if(isset($_SESSION['m_admin']['address']['ADD_TOWN'])){functions::xecho($func->show_str($_SESSION['m_admin']['address']['ADD_TOWN'])); }?>"/>
                                <span class="blue_asterisk" style="visibility:visible;">*</span>
                            </td>
                            <div id="show_postal_code" class="autocomplete">
                                <script type="text/javascript">
                                    initList_hidden_input3('town', 'show_postal_code', '<?php $_SESSION['config']['businessappurl'];?>index.php?display=true&dir=indexing_searching&page=ajaxShowCodePostal&id=<?php functions::xecho($id);?>', 'what', '2', 'cp', 'town');
                                </script>
                            </div>
                        </tr>
                        <tr style="display:none;" id="rule_town">
                            <td>&nbsp;</td>
                            <td align="left"><i><?php echo _WRITE_IN_UPPER;?></i></td>
                        </tr>
                        <tr>
                            <td><label for="country"><?php echo _COUNTRY;?> : </label></td>
                            <td>
                                <input class="<?php echo $fieldAddressClass;?>" name="country" type="text" onkeyup="this.value=this.value.toUpperCase()" onfocus="$('rule_country').style.display='table-row'" onblur="$('rule_country').style.display='none';" id="country" value="<?php if(isset($_SESSION['m_admin']['address']['ADD_COUNTRY'])){ functions::xecho($func->show_str($_SESSION['m_admin']['address']['ADD_COUNTRY'])); }?>"/>
                                <span class="blue_asterisk" style="visibility:visible;">*</span>
                            </td>
                        </tr>
                        <tr style="display:none;" id="rule_country">
                            <td>&nbsp;</td>
                            <td align="left"><i><?php echo _WRITE_IN_UPPER;?></i></td>
                        </tr>
                        <tr >
                            <td><label for="phone"><?php echo _PHONE;?>&nbsp;: </label></td>
                            <td>
                                <input class="<?php echo $fieldAddressClass;?>" name="phone" type="text" onfocus="$('rule_phone').style.display='table-row'" onblur="$('rule_phone').style.display='none';" id="phone" value="<?php if(isset($_SESSION['m_admin']['address']['PHONE'])){functions::xecho($func->show_str($_SESSION['m_admin']['address']['PHONE']));} ?>"/>
                                <span class="blue_asterisk" style="visibility:visible;">*</span>
                            </td>
                        </tr>
                        <tr style="display:none;" id="rule_phone">
                            <td>&nbsp;</td>
                            <td align="left"><i><?php echo _FORMAT_PHONE;?></i></td>
                        </tr>
                        <tr>
                            <td><label for="mail"><?php echo _MAIL;?>&nbsp;: </label></td>
                            <td>
                                <input class="<?php echo $fieldAddressClass;?>" name="mail" type="text" id="mail" value="<?php if(isset($_SESSION['m_admin']['address']['MAIL'])){ functions::xecho($func->show_str($_SESSION['m_admin']['address']['MAIL']));} ?>"/>
                                <span class="blue_asterisk" style="visibility:visible;">*</span>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo _WEBSITE;?>&nbsp;:</td>
                            <td><input class="<?php echo $fieldAddressClass;?>" name="website" type="text" id="website" value="<?php if(isset($_SESSION['m_admin']['address']['WEBSITE'])){ functions::xecho($func->show_str($_SESSION['m_admin']['address']['WEBSITE']));} ?>"/></td>
                        </tr>
                        <tr>
                            <td><?php echo _COMP_DATA;?>&nbsp;: </td>
                            <td><textarea class="<?php echo $fieldAddressClass;?>" name="comp_data" id="comp_data"><?php if(isset($_SESSION['m_admin']['address']['OTHER_DATA'])){functions::xecho($func->show_str($_SESSION['m_admin']['address']['OTHER_DATA'])); }?></textarea></td>
                        </tr>
                        <tr>
                            <td><?php echo _IS_PRIVATE;?>&nbsp;: </td>
                            <td>
                                <input class="<?php echo $fieldAddressClass;?>" type="radio"  class="check" name="is_private" value="Y" <?php if($_SESSION['m_admin']['address']['IS_PRIVATE'] == 'Y'){?> checked="checked"<?php } ?> /><?php echo _YES;?>
                                <input type="radio"  class="check" name="is_private" value="N" <?php if($_SESSION['m_admin']['address']['IS_PRIVATE'] == 'N' OR $_SESSION['m_admin']['address']['IS_PRIVATE'] <> 'Y'){?> checked="checked"<?php } ?> /><?php echo _NO;?>
                            </td>
                        </tr>
                        <tr style="line-height: 20px">
                            <td colspan="4"><?php echo _HELP_PRIVATE;?></td>
                        </tr>
                    </table>
                </div>
                <br>
                    <table width="65%">
                        <tr align="left">
                            <td colspan="4" onclick="new Effect.toggle('salutation_div', 'blind', {delay:0.2});
                        whatIsTheDivStatus('salutation_div', 'divStatus_salutation_div');"><label>
                            <span id="divStatus_salutation_div" style="color:#1C99C5;">>></span>&nbsp;<b><?php echo _SALUTATION;?> </b></label></td>
                        </tr>
                    </table>
                <div id="salutation_div" style="display:inline">
                    <table width="65%" id="frmaddress_table2">
                        <tr>
                            <td><?php echo _SALUTATION_HEADER;?>&nbsp;: </td>
                            <td>&nbsp;</td>
                            <td>
                                <textarea class="<?php echo $fieldSalutationClass;?>" name="salutation_header" id="salutation_header"><?php if(isset($_SESSION['m_admin']['address']['SALUTATION_HEADER'])){functions::xecho($func->show_str($_SESSION['m_admin']['address']['SALUTATION_HEADER'])); }?></textarea>
                                <span class="blue_asterisk" style="visibility:visible;">*</span>
                            </td>
                        </tr>
                        <tr style="width: 30px;">
                            <td><?php echo _SALUTATION_FOOTER;?>&nbsp;: </td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td>
                                <textarea class="<?php echo $fieldSalutationClass;?>" name="salutation_footer" id="salutation_footer"><?php if(isset($_SESSION['m_admin']['address']['SALUTATION_FOOTER'])){functions::xecho($func->show_str($_SESSION['m_admin']['address']['SALUTATION_FOOTER'])); }?></textarea>
                                <span class="blue_asterisk" style="visibility:visible;">*</span>
                            </td>
                        </tr>
                    </table>
                </div>
                        <input name="mode" type="hidden" value="<?php echo $mode;?>" />
                    <p>

                        <input class="button" type="submit" name="Submit" value="<?php echo _VALIDATE;?>" />
                        <?php

                    $cancel_target = $_SESSION['config']['businessappurl'].'index.php?page=contacts_v2_up';
                    if(!$admin)
                    {
                        $cancel_target = $_SESSION['config']['businessappurl'].'index.php?page=my_contact_up&amp;dir=my_contacts&amp;load';
                    }
                    if($iframe == 'iframe')
                    {
                        $cancel_target = $_SESSION['config']['businessappurl'].'index.php?display=false&page=create_contact_iframe&dir=my_contacts';
                    } else if($iframe == "fromContactIframe"){
                        $cancel_target = $_SESSION['config']['businessappurl'].'index.php?display=false&dir=my_contacts&page=info_contact_iframe&seeAllAddresses&contactid='.$_SESSION['contact']['current_contact_id'].'&addressid='.$_SESSION['contact']['current_address_id'];
                    }
                    if (isset($_SESSION['contact_address']['fromContactAddressesList']) && $_SESSION['contact_address']['fromContactAddressesList'] <> "") {
                        $cancel_target = $_SESSION['config']['businessappurl'].'index.php?page=contact_addresses_list';
                        $_SESSION['contact_address']['fromContactAddressesList'] = "";
                    }

                    if($iframe == 'iframe_add_up'){
                        $see_all_addresses = $_SESSION['config']['businessappurl'].'index.php?display=false&dir=my_contacts&page=info_contact_iframe&seeAllAddresses&contactid='.$_SESSION['contact']['current_contact_id'].'&addressid='.$_SESSION['contact']['current_address_id'];
                        if ($_SESSION['AttachmentContact'] == "1") {
                            ?>
                            <input type="button" class="button"  name="cancel" value="<?php echo _CANCEL;?>" <?php
                            $core_tools = new core_tools();
                            if($core_tools->test_service('my_contacts', 'apps', false)){
                                ?>onclick="new Effect.BlindUp(parent.document.getElementById('create_contact_div_attach'));new Effect.BlindUp(parent.document.getElementById('info_contact_div_attach'));simpleAjax('<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&page=unsetAttachmentContact');return false;" <?php
                            } else { 
                                ?>onclick="new Effect.BlindUp(parent.document.getElementById('info_contact_div_attach'));simpleAjax('<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&page=unsetAttachmentContact');return false;" <?php
                            } ?>
                             /> <?php
                        } else {
                            ?>
                            <input type="button" class="button"  name="cancel" value="<?php echo _CANCEL;?>"  <?php
                            $core_tools = new core_tools();
                            if($core_tools->test_service('my_contacts', 'apps', false)){
                                ?>onclick="new Effect.BlindUp(parent.document.getElementById('create_contact_div'));new Effect.BlindUp(parent.document.getElementById('info_contact_div'));return false;" <?php
                            } else { 
                                ?>onclick="new Effect.BlindUp(parent.document.getElementById('info_contact_div'));return false;" <?php
                            } ?>
                             /> 
                        <?php } ?>
                        <input type="button" class="button"  name="cancel" value="<?php echo _SEE_ALL_ADDRESSES;?>" onclick="javascript:window.location.href='<?php echo($see_all_addresses);?>';" />
                        <?php
                    } else {
                        ?><input type="button" class="button"  name="cancel" value="<?php echo _CANCEL;?>" onclick="javascript:window.location.href='<?php echo($cancel_target);?>';" /><?php
                    }
                    ?>
                    </p>
                </form>
            <?php
                if($mode=="up" && $admin)
                {
                    ?><script type="text/javascript">launch_autocompleter('<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&page=users_autocomplete_list', 'owner', 'show_user');</script><?php
                }
            }
            ?>
        </div>
    <?php
    }

    /**
    * Add ou modify address in the database
    *
    * @param string $mode up or add
    */
    public function addupaddress($mode, $admin = true, $iframe = false)
    {
        $db = new Database();
        // add ou modify users in the database
        $this->addressinfo($mode);
        $order = $_SESSION['m_admin']['address']['order'];
        $order_field = $_SESSION['m_admin']['address']['order_field'];
        $what = $_SESSION['m_admin']['address']['what'];
        $start = $_SESSION['m_admin']['address']['start'];

        $path_contacts = $_SESSION['config']['businessappurl']
                       . 'index.php?page=contacts_v2_up&order='
                       . $order . '&order_field=' . $order_field . '&start='
                       . $start . '&what=' . $what;
        $path_contacts_add_errors = $_SESSION['config']['businessappurl']
                                  . 'index.php?page=contact_addresses_add';
        $path_contacts_up_errors = $_SESSION['config']['businessappurl']
                                 . 'index.php?page=contact_addresses_up';
        if (! $admin) {
            $path_contacts = $_SESSION['config']['businessappurl']
                           . 'index.php?dir=my_contacts&page=my_contact_up&load&order='
                           . $order . '&order_field=' . $order_field . '&start='
                           . $start . '&what=' . $what;
            $path_contacts_add_errors = $_SESSION['config']['businessappurl']
                                      . 'index.php?page=contact_addresses_add&mycontact=Y';
            $path_contacts_up_errors = $_SESSION['config']['businessappurl']
                                     . 'index.php?page=contact_addresses_up&mycontact=Y';
        }
        if ($iframe) {
            if($mode == 'add') {
                if($iframe == 1){
                    $path_contacts = $_SESSION['config']['businessappurl']
                                              . 'index.php?display=false&dir=my_contacts&page=create_contact_iframe&created=Y';
                    $path_contacts_add_errors = $_SESSION['config']['businessappurl']
                                              . 'index.php?display=false&dir=my_contacts&page=create_address_iframe';
                } else if($iframe == 2) {
                    $path_contacts = $_SESSION['config']['businessappurl']
                                          . 'index.php?display=false&dir=my_contacts&page=info_contact_iframe&contactid='.$_SESSION['contact']['current_contact_id'].'&addressid='.$_SESSION['contact']['current_address_id'];
                    $path_contacts_add_errors = $_SESSION['config']['businessappurl']
                                              . 'index.php?display=false&dir=my_contacts&page=create_address_iframe&iframe=iframe_up_add';
                } else if($iframe == 3) {
                    $path_contacts = $_SESSION['config']['businessappurl']
                                          . 'index.php?display=false&dir=my_contacts&page=info_contact_iframe&created=add&contactid='.$_SESSION['contact']['current_contact_id'].'&addressid='.$_SESSION['contact']['current_address_id'];
                    $path_contacts_add_errors = $_SESSION['config']['businessappurl']
                                              . 'index.php?display=false&dir=my_contacts&page=create_address_iframe&iframe=iframe_up_add';
                }
            } else if($mode == 'up') {
                if ($iframe == 3) {
                    $path_contacts = $_SESSION['config']['businessappurl']
                                          . 'index.php?display=false&dir=my_contacts&page=info_contact_iframe&created=Y&contactid='.$_SESSION['contact']['current_contact_id'].'&addressid='.$_SESSION['contact']['current_address_id'];
                } else {
                    $path_contacts = $_SESSION['config']['businessappurl']
                                          . 'index.php?display=false&dir=my_contacts&page=info_contact_iframe&created=Y&contactid='.$_SESSION['contact']['current_contact_id'].'&addressid='.$_SESSION['contact']['current_address_id'];
                }

                $path_contacts_up_errors = $_SESSION['config']['businessappurl']
                                          . 'index.php?display=false&dir=my_contacts&page=update_address_iframe';
            }

        }
        if (isset($_SESSION['contact_address']['fromContactAddressesList']) && $_SESSION['contact_address']['fromContactAddressesList'] <> "") {
            $path_contacts = $_SESSION['config']['businessappurl'] . 'index.php?page=contact_addresses_list';
            $path_contacts_up_errors = $_SESSION['config']['businessappurl'] . 'index.php?page=contact_addresses_up&fromContactAddressesList';
            $_SESSION['contact_address']['fromContactAddressesList'] = "";
        }
        if (! empty($_SESSION['error'])) {
            if ($mode == 'up') {
                if (! empty($_SESSION['m_admin']['address']['ID'])) {
                    $_SESSION['address_up_error'] = "true";
                    header(
                        'location: ' . $path_contacts_up_errors . '&id='
                        . $_SESSION['m_admin']['address']['ID']
                    );
                    exit;
                } else {
                    header('location: ' . $path_contacts);
                    exit;
                }
            }
            if ($mode == 'add') {
                header('location: ' . $path_contacts_add_errors);
                exit;
            }
        } else {
            if ($_SESSION['m_admin']['address']['CONTACT_PURPOSE_ID'] == "") {

                $stmt = $db->query("SELECT id FROM contact_purposes WHERE label = ?", array($_SESSION['m_admin']['address']['CONTACT_PURPOSE_NAME']));
                if ($stmt->rowCount() == 0) {
                    $db->query("INSERT INTO contact_purposes (label) VALUES (?)", array($_SESSION['m_admin']['address']['CONTACT_PURPOSE_NAME']));
                    $stmt = $db->query("SELECT id FROM contact_purposes WHERE label = ?", array($_SESSION['m_admin']['address']['CONTACT_PURPOSE_NAME']));
                }

                $res_purpose = $stmt->fetchObject();
                $_SESSION['m_admin']['address']['CONTACT_PURPOSE_ID'] = $res_purpose->id;
            } else if($_SESSION['m_admin']['address']['CONTACT_PURPOSE_ID'] <> "" && $_SESSION['m_admin']['address']['CONTACT_PURPOSE_NAME'] <> ""){
                $stmt = $db->query("SELECT id FROM contact_purposes WHERE label = ?", array($_SESSION['m_admin']['address']['CONTACT_PURPOSE_NAME']));
                $res_purpose = $stmt->fetchObject();
                if ($res_purpose->id != $_SESSION['m_admin']['address']['CONTACT_PURPOSE_ID']) {
                    $db->query("INSERT INTO contact_purposes (label) VALUES (?)", array($_SESSION['m_admin']['address']['CONTACT_PURPOSE_NAME']));
                    $stmt = $db->query("SELECT id FROM contact_purposes WHERE label = ?", array($_SESSION['m_admin']['address']['CONTACT_PURPOSE_NAME']));
                    $res_purpose = $stmt->fetchObject();
                    $_SESSION['m_admin']['address']['CONTACT_PURPOSE_ID'] = $res_purpose->id;
                }
            }
            if ($mode == 'add') {
                if($_SESSION['user']['UserId'] == 'superadmin'){
                    $entity_id = 'SUPERADMIN';
                } else {
                    $entity_id = $_SESSION['user']['primaryentity']['id'];
                }
                $query = 'INSERT INTO ' . $_SESSION['tablename']['contact_addresses']
                        . ' (  contact_id, contact_purpose_id, departement, lastname , firstname , function , '
                        . 'phone , email , address_num, address_street, '
                        . 'address_complement, address_town, '
                        . 'address_postal_code, address_country, other_data,'
                        . " title, is_private, website, occupancy, user_id, entity_id, salutation_header, salutation_footer) VALUES (?, ?, 
                            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $arrayPDO = array($_SESSION['contact']['current_contact_id'], $_SESSION['m_admin']['address']['CONTACT_PURPOSE_ID'], $_SESSION['m_admin']['address']['DEPARTEMENT'],
                    $_SESSION['m_admin']['address']['LASTNAME'], $_SESSION['m_admin']['address']['FIRSTNAME'], $_SESSION['m_admin']['address']['FUNCTION'], $_SESSION['m_admin']['address']['PHONE'],
                    $_SESSION['m_admin']['address']['MAIL'], $_SESSION['m_admin']['address']['ADD_NUM'], $_SESSION['m_admin']['address']['ADD_STREET'], $_SESSION['m_admin']['address']['ADD_COMP'],
                    $_SESSION['m_admin']['address']['ADD_TOWN'], $_SESSION['m_admin']['address']['ADD_CP'], $_SESSION['m_admin']['address']['ADD_COUNTRY'], $_SESSION['m_admin']['address']['OTHER_DATA'],
                    $_SESSION['m_admin']['address']['TITLE'], $_SESSION['m_admin']['address']['IS_PRIVATE'], $_SESSION['m_admin']['address']['WEBSITE'], $_SESSION['m_admin']['address']['OCCUPANCY'],
                    $_SESSION['user']['UserId'], $entity_id, $_SESSION['m_admin']['address']['SALUTATION_HEADER'], $_SESSION['m_admin']['address']['SALUTATION_FOOTER']);

                $db->query($query, $arrayPDO);
                if($_SESSION['history']['addressadd'])
                {
                    $stmt = $db->query("SELECT id FROM ".$_SESSION['tablename']['contact_addresses']." WHERE 
                        lastname = ? and firstname = ? and society = ? and function = ? and is_corporate_person = ?", 
                        array($_SESSION['m_admin']['address']['LASTNAME'], $_SESSION['m_admin']['address']['FIRSTNAME'], $_SESSION['m_admin']['address']['SOCIETY'], $_SESSION['m_admin']['address']['FUNCTION'], $_SESSION['m_admin']['address']['IS_CORPORATE_PERSON']));
                    $res = $stmt->fetchObject();
                    $id = $res->contact_id;
                    if($_SESSION['m_admin']['address']['IS_CORPORATE_PERSON'] == 'Y')
                    {
                        $msg =  _ADDRESS_ADDED.' : '.functions::protect_string_db($_SESSION['m_admin']['address']['SOCIETY']);
                    }
                    else
                    {
                        $msg =  _ADDRESS_ADDED.' : '.functions::protect_string_db($_SESSION['m_admin']['address']['LASTNAME'].' '.$_SESSION['m_admin']['address']['FIRSTNAME']);
                    }
                    require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_history.php');
                    $hist = new history();
                    $hist->add($_SESSION['tablename']['contact_addresses'], $id,"ADD",'contact_addresses_add',$msg, $_SESSION['config']['databasetype']);
                }

                if($iframe){
                    $this->clearcontactinfos();
                }

                $this->clearaddressinfos();
                $_SESSION['info'] = _ADDRESS_ADDED;
                header("location: ".$path_contacts);
                exit;
            }
            elseif($mode == "up")
            {
                $query = "UPDATE ".$_SESSION['tablename']['contact_addresses']." 
                      SET contact_purpose_id = ?
                        , departement = ?
                        , firstname = ?
                        , lastname = ?
                        , title = ?
                        , function = ?
                        , phone = ?
                        , email = ?
                        , occupancy = ?
                        , address_num = ?
                        , address_street = ?
                        , address_complement = ?
                        , address_town = ?
                        , address_postal_code = ?
                        , address_country = ?
                        , website = ?
                        , other_data = ?
                        , is_private = ?
                        , salutation_header = ?
                        , salutation_footer = ?";

                $query .=" WHERE id = ?";

                $arrayPDO = array($_SESSION['m_admin']['address']['CONTACT_PURPOSE_ID'], $_SESSION['m_admin']['address']['DEPARTEMENT'], $_SESSION['m_admin']['address']['FIRSTNAME'],
                    $_SESSION['m_admin']['address']['LASTNAME'], $_SESSION['m_admin']['address']['TITLE'], $_SESSION['m_admin']['address']['FUNCTION'], $_SESSION['m_admin']['address']['PHONE'],
                    $_SESSION['m_admin']['address']['MAIL'], $_SESSION['m_admin']['address']['OCCUPANCY'], $_SESSION['m_admin']['address']['ADD_NUM'], $_SESSION['m_admin']['address']['ADD_STREET'], $_SESSION['m_admin']['address']['ADD_COMP'],
                    $_SESSION['m_admin']['address']['ADD_TOWN'], $_SESSION['m_admin']['address']['ADD_CP'], $_SESSION['m_admin']['address']['ADD_COUNTRY'], $_SESSION['m_admin']['address']['WEBSITE'], 
                    $_SESSION['m_admin']['address']['OTHER_DATA'], $_SESSION['m_admin']['address']['IS_PRIVATE'], $_SESSION['m_admin']['address']['SALUTATION_HEADER'], $_SESSION['m_admin']['address']['SALUTATION_FOOTER'],
                    $_SESSION['m_admin']['address']['ID']);


                $db->query($query, $arrayPDO);
                if($_SESSION['history']['contactup'])
                {
                    $msg =  _ADDRESS_EDITED.' : '.functions::protect_string_db($_SESSION['m_admin']['address']['SOCIETY']).' '.functions::protect_string_db($_SESSION['m_admin']['address']['LASTNAME'].' '.$_SESSION['m_admin']['address']['FIRSTNAME']);
                    require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_history.php');
                    $hist = new history();
                    $hist->add($_SESSION['tablename']['contacts_v2'], $_SESSION['m_admin']['address']['ID'],"UP",'contacts_v2_up',$msg, $_SESSION['config']['databasetype']);
                }
                $this->clearcontactinfos();
                $_SESSION['info'] = _ADDRESS_EDITED;
                header("location: ".$path_contacts);
                exit();
            }
        }
    }

    /**
    * Return the address data in sessions vars
    *
    * @param string $mode add or up
    */
    public function addressinfo($mode)
    {
        // return the user information in sessions vars
        $func = new functions();
        if ($_REQUEST['title'] <> '') {
            $_SESSION['m_admin']['address']['TITLE'] = $func->wash(
                $_REQUEST['title'], 'no', _TITLE2 . ' ', 'yes', 0, 255
            );
        } else {
            $_SESSION['m_admin']['address']['TITLE'] = '';
        }

        if ($_REQUEST['contact_purposes'] <> '') {
            $_SESSION['m_admin']['address']['CONTACT_PURPOSE_ID'] = $func->wash(
                $_REQUEST['contact_purposes'], 'no', _CONTACT_PURPOSE . ' ', 'yes', 0, 255
            );
        }  else {
            $_SESSION['m_admin']['address']['CONTACT_PURPOSE_ID'] = '';
        }

        $_SESSION['m_admin']['address']['CONTACT_PURPOSE_NAME'] = $func->wash(
            $_REQUEST['new_id'], 'no', _CONTACT_PURPOSE . ' ', 'yes', 0, 255
        );


        if ($_REQUEST['departement'] <> '') {
            $_SESSION['m_admin']['address']['DEPARTEMENT'] = $func->wash(
                $_REQUEST['departement'], 'no', _DEPARTEMENT . ' ', 'yes', 0, 255
            );
        } else {
            $_SESSION['m_admin']['address']['DEPARTEMENT'] = '';
        }

        if ($_REQUEST['lastname'] <> '') {
            $_SESSION['m_admin']['address']['LASTNAME'] = $func->wash(
                $_REQUEST['lastname'], 'no', _LASTNAME . ' ', 'yes', 0, 255
            );
        } else {
            $_SESSION['m_admin']['address']['LASTNAME'] = '';
        }

        if ($_REQUEST['firstname'] <> '') {
            $_SESSION['m_admin']['address']['FIRSTNAME'] = $func->wash(
                $_REQUEST['firstname'], 'no', _FIRSTNAME . ' ', 'yes', 0, 255
            );
        } else {
            $_SESSION['m_admin']['address']['FIRSTNAME'] = '';
        }

        if ($_REQUEST['function'] <> '') {
            $_SESSION['m_admin']['address']['FUNCTION'] = $func->wash(
                $_REQUEST['function'], 'no', _FUNCTION . ' ', 'yes', 0, 255
            );
        } else {
            $_SESSION['m_admin']['address']['FUNCTION'] = '';
        }

        if ($_REQUEST['num'] <> '') {
            $_SESSION['m_admin']['address']['ADD_NUM'] = $func->wash(
                $_REQUEST['num'], 'no', _NUM . ' ', 'yes', 0, 32
            );
        } else {
            $_SESSION['m_admin']['address']['ADD_NUM'] = '';
        }

        if ($_REQUEST['street'] <> '') {
            $_SESSION['m_admin']['address']['ADD_STREET'] = $func->wash(
                $_REQUEST['street'], 'no', _STREET . ' ', 'yes', 0, 255
            );
        } else {
            $_SESSION['m_admin']['address']['ADD_STREET'] = '';
        }

        if ($_REQUEST['add_comp'] <> '') {
            $_SESSION['m_admin']['address']['ADD_COMP'] = $func->wash(
                $_REQUEST['add_comp'], 'no', ADD_COMP . ' ', 'yes', 0, 255
            );
        } else {
            $_SESSION['m_admin']['address']['ADD_COMP'] = '';
        }

        if ($_REQUEST['town'] <> '') {
            $_SESSION['m_admin']['address']['ADD_TOWN'] = $func->wash(
                $_REQUEST['town'], 'no', _TOWN . ' ', 'yes', 0, 255
            );
        } else {
            $_SESSION['m_admin']['address']['ADD_TOWN'] = '';
        }
        if ($_REQUEST['cp'] <> '') {
            $_SESSION['m_admin']['address']['ADD_CP'] = $func->wash(
                $_REQUEST['cp'], 'no', _POSTAL_CODE, 'yes', 0, 255
            );
        } else {
            $_SESSION['m_admin']['address']['ADD_CP'] = '';
        }
        if ($_REQUEST['country'] <> '') {
            $_SESSION['m_admin']['address']['ADD_COUNTRY'] = $func->wash(
                $_REQUEST['country'], 'no', _COUNTRY, 'yes', 0, 255
            );
        } else {
            $_SESSION['m_admin']['address']['ADD_COUNTRY'] = '';
        }
        if ($_REQUEST['phone'] <> '') {
            $_SESSION['m_admin']['address']['PHONE'] = $func->wash(
                $_REQUEST['phone'], 'phone', _PHONE, 'yes', 0, 20
            );
        } else {
            $_SESSION['m_admin']['address']['PHONE'] = '';
        }
        if ($_REQUEST['mail'] <> '') {
            $_SESSION['m_admin']['address']['MAIL'] = $func->wash(
                $_REQUEST['mail'], 'mail', _MAIL, 'yes', 0, 255
            );
        } else {
            $_SESSION['m_admin']['address']['MAIL'] = '';
        }
        if ($_REQUEST['comp_data'] <> '') {
            $_SESSION['m_admin']['address']['OTHER_DATA'] = $func->wash(
                $_REQUEST['comp_data'], 'no', _COMP_DATA, 'yes', 0, 255
            );
        } else {
            $_SESSION['m_admin']['address']['OTHER_DATA'] = '';
        }
        if ($_REQUEST['website'] <> '') {
            $_SESSION['m_admin']['address']['WEBSITE'] = $func->wash(
                $_REQUEST['website'], 'no', _WEBSITE, 'yes', 0, 255
            );
        } else {
            $_SESSION['m_admin']['address']['WEBSITE'] = '';
        }
        if ($_REQUEST['occupancy'] <> '') {
            $_SESSION['m_admin']['address']['OCCUPANCY'] = $func->wash(
                $_REQUEST['occupancy'], 'no', _OCCUPANCY, 'yes', 0, 1024
            );
        } else {
            $_SESSION['m_admin']['address']['occupancy'] = '';
        }
        if ($_REQUEST['salutation_header'] <> '') {
            $_SESSION['m_admin']['address']['SALUTATION_HEADER'] = $func->wash(
                $_REQUEST['salutation_header'], 'no', _SALUTATION_HEADER, 'yes', 0, 255
            );
        } else {
            $_SESSION['m_admin']['address']['SALUTATION_HEADER'] = '';
        }
        if ($_REQUEST['salutation_footer'] <> '') {
            $_SESSION['m_admin']['address']['SALUTATION_FOOTER'] = $func->wash(
                $_REQUEST['salutation_footer'], 'no', _SALUTATION_FOOTER, 'yes', 0, 255
            );
        } else {
            $_SESSION['m_admin']['address']['SALUTATION_FOOTER'] = '';
        }
         $_SESSION['m_admin']['address']['IS_PRIVATE'] =
            $_REQUEST['is_private'];

        if (isset($_REQUEST['owner']) && $_REQUEST['owner'] <> '') {
            if (preg_match('/\((.|\s|\d|\h|\w)+\)$/i', $_REQUEST['owner']) == 0) {
                $_SESSION['error'] = _OWNER . ' ' . _WRONG_FORMAT . '.<br/>'
                                   . _USE_AUTOCOMPLETION;
            } else {
                $_SESSION['m_admin']['address']['OWNER'] = str_replace(
                    ')', '', substr($_REQUEST['owner'],
                    strrpos($_REQUEST['owner'],'(')+1)
                );
                $_SESSION['m_admin']['address']['OWNER'] = $func->wash(
                    $_SESSION['m_admin']['address']['OWNER'], 'no',
                    _OWNER . ' ', 'yes', 0, 32
                );
            }
        } else {
            $_SESSION['m_admin']['address']['OWNER'] = '';
        }

        $_SESSION['m_admin']['address']['order'] = $_REQUEST['order'];
        $_SESSION['m_admin']['address']['order_field'] = $_REQUEST['order_field'];
        $_SESSION['m_admin']['address']['what'] = $_REQUEST['what'];
        $_SESSION['m_admin']['address']['start'] = $_REQUEST['start'];
    }

    /**
    * Return the label from an id
    *
    * @param int $contact_type_id
    * @param string $table
    */
    public function get_label_contact($contact_type_id, $table){
        $db = new Database();
        $stmt = $db->query('SELECT label FROM '.$table . ' WHERE id = ?',array($contact_type_id));
        $res = $stmt->fetchObject();
        return functions::show_string($res->label);
    }

    public function get_civility_contact($title){
        // $core = new core_tools();
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps'
            . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
            . 'entreprise.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                  . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                  . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
                  . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . 'xml'
                  . DIRECTORY_SEPARATOR . 'entreprise.xml';
        } else {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                  . 'entreprise.xml';
        }
        $xml = simplexml_load_file($path);
        // $xml = simplexml_load_file('apps'.DIRECTORY_SEPARATOR.'maarch_entreprise'.DIRECTORY_SEPARATOR.'xml'.DIRECTORY_SEPARATOR.'entreprise.xml');
        if ($xml <> false) {
            $result = $xml->xpath('/ROOT/titles');
            foreach ($result as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    if($value2->id==$title){
                        $title_value=(string)$value2->label;
                    }
                }
            }
        }
        return functions::show_string($title_value);
    }

    public function type_purpose_address_del($id, $admin = true, $tablename, $mode='contact_type', $deleted_sentence, $warning_sentence, $title, $reaffect_sentence, $new_sentence, $choose_sentence, $page_return, $page_del, $name){
        $nb_elements = 0;
        $db = new Database();
        $order = $_REQUEST['order'];
        $order_field = $_REQUEST['order_field'];
        $start = $_REQUEST['start'];
        $what = $_REQUEST['what'];
        $path = $_SESSION['config']['businessappurl']."index.php?page=".$page_return."&order=".$order."&order_field=".$order_field."&start=".$start."&what=".$what;
        $path_del = $_SESSION['config']['businessappurl']."index.php?page=".$page_del."&order=".$order."&order_field=".$order_field."&start=".$start."&what=".$what;
        if(!$admin)
        {
            if ($mode == 'contact_address'){
                $path = $_SESSION['config']['businessappurl']."index.php?page=my_contact_up&dir=my_contacts&load&order=".$order."&order_field=".$order_field."&start=".$start."&what=".$what;
            }
        }
        
        if(!empty($id))
        {
            if ($mode == 'contact_type') {
                $stmt = $db->query("SELECT contact_id FROM ".$_SESSION['tablename']['contacts_v2'] 
                . " WHERE contact_type = ?", array($id));
            } else if ($mode == 'contact_purpose'){
                $stmt = $db->query("SELECT id FROM ".$_SESSION['tablename']['contact_addresses']
                    . " WHERE contact_purpose_id = ?", array($id));
            } else if ($mode == 'contact_address'){
                $stmt = $db->query("SELECT address_id FROM mlb_coll_ext WHERE address_id = ?", array($id));
            }
            
            if($stmt->rowCount() > 0)$nb_elements = $nb_elements + $stmt->rowCount();

            if ($mode == 'contact_address'){
                $stmt = $db->query("SELECT address_id FROM contacts_res WHERE address_id = ?", array($id));
                if($stmt->rowCount() > 0)$nb_elements = $nb_elements + $stmt->rowCount();
            }
                ?>

                <div class="error">
                    <b><?php
                        functions::xecho($_SESSION['error']);
                        $_SESSION['error'] = "";
                    ?></b>
                </div>
                <br>

                <h1><i class="fa fa-remove fa-2x"></i>
                    <?php
                        functions::xecho($title); 
                    ?>
                </h1><?php

            if ($nb_elements == 0 && $mode != "contact_address" )
            {
                $db->query("DELETE FROM ".$tablename." WHERE id = ?", array($id));

                if($_SESSION['history'][$page_del] == "true")
                {
                    require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
                    $users = new history();
                    $users->add($tablename, $id,"DEL",$page_del, $title." ".strtolower(_NUM).$id."", $_SESSION['config']['databasetype']);
                }

                $_SESSION['error'] = $deleted_sentence;

                unset($_SESSION['m_admin']);
                ?>
                    <script type="text/javascript">
                        window.location.href="<?php functions::xecho($path);?>";
                    </script>   
                <?php

            } else if ($nb_elements > 0) { ?> 

                <div id="main_error">
                    <b><?php
                        functions::xecho($warning_sentence);
                    ?></b>
                </div>
                <br>
                <br>
                
                <form name="contact_type_del" id="contact_type_del" method="post" class="forms" action="<?php functions::xecho($path_del);?>">
                    <input type="hidden" value="<?php functions::xecho($id);?>" name="id">
                    <h2 class="tit"><?php functions::xecho($reaffect_sentence) . " : <i>".$label."</i>";?></h2>
                    <?php
                    if($nb_elements > 0)
                    {
                        if ($mode == 'contact_type') {
                            echo "<br><b> - ".$nb_elements."</b> "._CONTACTS;
                        } else if ($mode == 'contact_purpose'){
                            echo "<br><b> - ".$nb_elements."</b> "._ADDRESSES;
                        } else if ($mode == 'contact_address'){
                            echo "<br><b> - ".$nb_elements."</b> "._DOC_S;
                        }                                              
                    ?>
                        <br>
                        <br>

                        <?php
                            if($mode == 'contact_address'){ 

                                $stmt = $db->query("SELECT * FROM ".$_SESSION['tablename']['contacts_v2'] 
                                . " WHERE contact_id = ?", array($_SESSION['contact']['current_contact_id']));                                
                                while($line = $stmt->fetchObject())
                                {
                                    $CurrentContact = $this->get_label_contact($line->contact_type, $_SESSION['tablename']['contact_types']) . ' : ';
                                    if($line->is_corporate_person == 'N'){
                                        $CurrentContact = functions::show_string($line->lastname)." ".functions::show_string($line->firstname);
                                        if($line->society <> ''){
                                            $CurrentContact .= ' ('.$line->society.')';
                                        }
                                    } else {
                                        $CurrentContact .= $line->society;
                                        if($line->society_short <> ''){
                                            $CurrentContact .= ' ('.$line->society_short.')';
                                        }
                                    }
                                }
                                ?> 

                                <td>
                                    <label for="contact"><?php echo _NEW_CONTACT;?> : </label>
                                </td>
                                
                                <td class="indexing_field">
                                    <input name="new_contact" id="new_contact" value="<?php functions::xecho($CurrentContact);?>" onchange="erase_contact_external_id('new_contact', 'new_contact_id')"/>
                                    <div id="show_contact_label" class="autocomplete">
                                        <script type="text/javascript">
                                            initList_hidden_input('new_contact', 'show_contact_label', '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&page=contacts_v2_list_by_name', 'what', '2', 'new_contact_id');
                                        </script>
                                    </div>
                                    <input type="hidden" id="new_contact_id" name="new_contact_id" value="<?php functions::xecho($_SESSION['contact']['current_contact_id']);?>"/>
                                </td>
                                <br>
                                <br>
                                
                                <?php
                            } ?>

                        <td>
                            <label for="contact"><?php functions::xecho($new_sentence);?> : </label>
                        </td>
                        <td class="indexing_field">
                            <?php 
                            if($mode == 'contact_address'){
                                $_SESSION['contact']['current_address_id'] = $id;
                                ?> 
                                <input name="new_id" id="new_id" value=""/>
                                    <div id="show_contact" class="autocomplete">
                                        <script type="text/javascript">
                                            initList_hidden_input_before('new_id', 'show_contact', '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&page=contact_addresses_list_by_name', 'what', '2', 'new', 'reaffectAddress', 'new_contact_id');
                                        </script>
                                    </div>
                                    <input type="hidden" id="new" name="new" />
                                <?php
                            } else if($mode == 'contact_purpose'){
                                ?> <input name="new_id" id="new_id" value=""/>
                                    <div id="show_contact" class="autocomplete">
                                        <script type="text/javascript">
                                            initList_hidden_input('new_id', 'show_contact', '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&page=contact_purposes_list_by_name&id=<?php functions::xecho($id);?>', 'what', '2', 'new');
                                        </script>
                                    </div>
                                    <input type="hidden" id="new" name="new" />
                                <?php
                            }else{
                                $stmt = $db->query("SELECT id, label FROM ".$tablename." WHERE id <> ?", array($id));

                                while ($res = $stmt->fetchObject()) {
                                    $array[$res->id] = functions::protect_string_db($res->label);
                                }
                            ?>
                                <select name="new" id="new">
                                    <option value=""><?php functions::xecho($choose_sentence);?></option>
                                    <?php
                                    foreach($array as $key => $label){
                                        ?><option value="<?php functions::xecho($key);?>">
                                            <?php functions::xecho($label);
                                        ?></option><?php
                                    }
                                    ?>
                                </select>
                            <?php 
                            } ?>
                        </td>
                            
                        <br/>  
                        <br/>
                    <p class="buttons">
                        <input type="submit" value="<?php echo _DEL_AND_REAFFECT;?>" name="valid" class="button" onclick="return(confirm('<?php echo _REALLY_DELETE;  if(isset($page_name) && $page_name == "users"){ functions::xecho($complete_name);} elseif(isset($admin_id)){ functions::xecho(" ".$admin_id); }?> ?\n\r\n\r<?php echo _DEFINITIVE_ACTION;?>'));"/>
                        <input type="button" value="<?php echo _CANCEL;?>" onclick="window.location.href='<?php functions::xecho($path);?>';" class="button" />
                    </p>
                      <?php
                    }
                    ?>
                </form>
                
                <hr/>
                <?php 
            }

            if ($mode == 'contact_address'){ ?>
                <br>
                <br>
                
                <h2><i class="fa fa-share"></i>
                    <?php
                        echo _MOVE_CONTACT_ADDRESS; 
                    ?>
                </h2>

                <br>
                <?php echo _INFO_MOVE_CONTACT_ADDRESS;?>

                    <br>
                    <br>
                    <form name="contact_type_del" id="contact_type_del" method="post" class="forms" action="<?php functions::xecho($path_del);?>">
                        <input type="hidden" value="<?php functions::xecho($id);?>" name="id">
                        <td>
                            <label for="new_contact_reaffect"><?php echo _NEW_CONTACT;?> : </label>
                        </td>
                        
                        <td class="indexing_field">
                            <input name="new_contact_reaffect" id="new_contact_reaffect" value="" onchange="erase_contact_external_id('new_contact_reaffect', 'new_contact_id_reaffect')"/>
                            <div id="show_contact_label_reaffect" class="autocomplete">
                                <script type="text/javascript">
                                    initList_hidden_input('new_contact_reaffect', 'show_contact_label_reaffect', '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&page=contacts_v2_list_by_name&id=<?php functions::xecho($_SESSION['contact']['current_contact_id']);?>', 'what', '2', 'new_contact_id_reaffect');
                                </script>
                            </div>
                            <input type="hidden" id="new_contact_id_reaffect" name="new_contact_id_reaffect"/>
                        </td>
                        <br/>  
                        <br/>
                        <p class="buttons">
                            <input type="submit" value="<?php echo _MOVE;?>" name="move" class="button" onclick="return(confirm('<?php echo _REALLY_MOVE;  if(isset($page_name) && $page_name == "users"){ functions::xecho($complete_name);} elseif(isset($admin_id)){ functions::xecho(" ".$admin_id); }?> ?\n\r\n\r<?php echo _DEFINITIVE_ACTION;?>'));"/>
                            
                            <?php if($nb_elements == 0){ ?>
                                    <input type="submit" value="<?php echo _DELETE_CONTACT_ADDRESS;?>" name="delete" class="button" onclick="return(confirm('<?php echo _REALLY_DELETE;  if(isset($page_name) && $page_name == "users"){functions::xecho($complete_name);} elseif(isset($admin_id)){ functions::xecho(" ".$admin_id); }?> ?\n\r\n\r<?php echo _DEFINITIVE_ACTION;?>'));"/>
                                <?php }
                            ?>

                            <input type="button" value="<?php echo _CANCEL;?>" onclick="window.location.href='<?php functions::xecho($path);?>';" class="button" />
                        </p>
                    </form>
                    <br>
                    <br>

            <?php
            }
            exit;
            
        } else {
            $_SESSION['error'] = $name.' '._EMPTY;
        }
        
        ?>
        <script type="text/javascript">
            window.location.href="<?php functions::xecho($path);?>";
        </script>
        <?php
        exit;        
    }

    /**
    * Contact form with every field disabled
    *
    */
    public function get_contact_form(){

        $func = new functions();
        $business = new business_app_tools();
        ?>
        <form class="forms">
            <table width="65%">
                <tr align="left">
                    <td colspan="4" onclick="new Effect.toggle('info_contact_div', 'blind', {delay:0.2});
                        whatIsTheDivStatus('info_contact_div', 'divStatus_contact_div');resetInlineDisplay('info_contact_div');">
                        <label>
                            <span id="divStatus_contact_div" style="color:#1C99C5;">>></span>&nbsp;<b><?php echo _CONTACT;?></b>
                        </label>
                    </td>
                </tr>
            </table>
            <div id="info_contact_div" style="display:inline;">
                <table width="65%" >
                    <tr >
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" align="left">
                            <input disabled type="radio"  class="check" name="is_corporate"  value="Y" <?php if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'Y'){?> checked="checked"<?php } ?> /><?php echo _IS_CORPORATE_PERSON;?>
                            <input disabled type="radio"  class="check" name="is_corporate" value="N" <?php if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'N'){?> checked="checked"<?php } ?> /><?php echo _INDIVIDUAL;?>
                        </td>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                    <tr id="contact_types_tr" >
                        <td><?php echo _CONTACT_TYPE;?> : </td>
                        <td>&nbsp;</td>
                        <td align="left"><input disabled name="contact_types" type="text"  id="contact_types" value="<?php if(isset($_SESSION['m_admin']['contact']['CONTACT_TYPE'])){ functions::xecho($this->get_label_contact($_SESSION['m_admin']['contact']['CONTACT_TYPE'], $_SESSION['tablename']['contact_types'])); }?>"/></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><?php echo _STRUCTURE_ORGANISM;?> : </td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" align="left"><input disabled name="society" type="text"  id="society" value="<?php if(isset($_SESSION['m_admin']['contact']['SOCIETY'])){ functions::xecho($func->show_str($_SESSION['m_admin']['contact']['SOCIETY'])); }?>"/></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><?php echo _SOCIETY_SHORT;?> : </td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" align="left"><input disabled name="society_short" type="text"  id="society_short" value="<?php if(isset($_SESSION['m_admin']['contact']['SOCIETY_SHORT'])){ functions::xecho($func->show_str($_SESSION['m_admin']['contact']['SOCIETY_SHORT'])); }?>"/></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr id="title_p" style="display:<?php if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'Y'){ echo 'none';}else{ functions::xecho($display_value);}?>">
                        <td><?php echo _TITLE2;?> : </td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" align="left"><input disabled name="title" type="text"  id="title" value="<?php if(isset($_SESSION['m_admin']['contact']['TITLE'])){ functions::xecho($business->get_label_title($_SESSION['m_admin']['contact']['TITLE'])); }?>"/></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr id="lastname_p" style="display:<?php if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'Y'){ echo 'none';}else{ functions::xecho($display_value);}?>">
                        <td><?php echo _LASTNAME;?> : </td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" align="left"><input disabled name="lastname" type="text"  id="lastname" value="<?php if(isset($_SESSION['m_admin']['contact']['LASTNAME'])){ functions::xecho($func->show_str($_SESSION['m_admin']['contact']['LASTNAME']));} ?>"/></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr id="firstname_p" style="display:<?php if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'Y'){ echo 'none';}else{ functions::xecho($display_value);}?>">
                        <td><?php echo _FIRSTNAME;?> : </td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" align="left"><input disabled name="firstname" type="text"  id="firstname" value="<?php if(isset($_SESSION['m_admin']['contact']['FIRSTNAME'])){ functions::xecho($func->show_str($_SESSION['m_admin']['contact']['FIRSTNAME']));} ?>"/></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr id="function_p" style="display:<?php if(isset($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON']) && $_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'Y'){ echo 'none';}else{ functions::xecho($display_value);}?>">
                        <td><?php echo _FUNCTION;?> : </td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" align="left"><input disabled name="function" type="text"  id="function" value="<?php if(isset($_SESSION['m_admin']['contact']['FUNCTION'])){functions::xecho($func->show_str($_SESSION['m_admin']['contact']['FUNCTION']));} ?>"/></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><?php echo _COMP_DATA;?>&nbsp;: </td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" align="left"><textarea disabled name="comp_data"   id="comp_data"><?php if(isset($_SESSION['m_admin']['contact']['OTHER_DATA'])){functions::xecho($func->show_str($_SESSION['m_admin']['contact']['OTHER_DATA'])); }?></textarea></td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
        </form>
        <?php
    }

    public function get_address_form(){
        $func = new functions();
        $business = new business_app_tools();
        ?>
        <form class="forms">
            <table width="65%">
                <tr align="left">
                    <td colspan="4" onclick="new Effect.toggle('address_div', 'blind', {delay:0.2});
                    whatIsTheDivStatus('address_div', 'divStatus_address_div');"><label>
                        <span id="divStatus_address_div" style="color:#1C99C5;">>></span>&nbsp;<b><?php echo _ADDRESS;?></b></label>
                    </td>
                </tr>
            </table>
            <div id="address_div"  style="display:inline">
                <table width="65%" >
        <?php if($_SESSION['m_admin']['address']['IS_PRIVATE'] == 'N'){ ?>
                    <tr id="contact_purposes_tr" >
                        <td><label for="contact_purposes"><?php echo _CONTACT_PURPOSE;?>&nbsp;: </label>
                        </td>
                        <td>&nbsp;</td>
                        <td class="indexing_field">
                            <input class="contact_field_margin" disabled name="new_id" id="new_id" value="<?php functions::xecho($this->get_label_contact($_SESSION['m_admin']['address']['CONTACT_PURPOSE_ID'], $_SESSION['tablename']['contact_purposes']));?>"/>
                        </td>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                    </tr>                    
                    <tr id="departement_p" >
                        <td><label for="departement"><?php echo _SERVICE;?>&nbsp;: </label></td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" ><input class="contact_field_margin" disabled name="departement" type="text"  id="departement" value="<?php if(isset($_SESSION['m_admin']['address']['DEPARTEMENT'])){ functions::xecho($func->show_str($_SESSION['m_admin']['address']['DEPARTEMENT']));} ?>"/></td>
                    </tr>
            <?php } ?>                     
                    <tr id="title_p" <?php if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'N'){ echo 'style="display:none"';}?>>
                        <td><?php echo _TITLE2;?> : </td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" ><input class="contact_field_margin" disabled disabled name="title" type="text"  id="title" value="<?php if(isset($_SESSION['m_admin']['contact']['TITLE'])){ functions::xecho($business->get_label_title($_SESSION['m_admin']['contact']['TITLE'])); }?>"/></td>
                    </tr>
                    <tr id="lastname_p" <?php if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'N'){ echo 'style="display:none"';}?>>
                        <td><label for="lastname"><?php echo _LASTNAME;?>&nbsp;: </label></td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" ><input class="contact_field_margin" disabled name="lastname" type="text"  id="lastname" value="<?php if(isset($_SESSION['m_admin']['address']['LASTNAME'])){ functions::xecho($func->show_str($_SESSION['m_admin']['address']['LASTNAME']));} ?>"/></td>
                    </tr>
                    <tr id="firstname_p" <?php if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'N'){ echo 'style="display:none"';}?>>
                        <td><label for="firstname"><?php echo _FIRSTNAME;?>&nbsp;: </label></td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" ><input class="contact_field_margin" disabled name="firstname" type="text"  id="firstname" value="<?php if(isset($_SESSION['m_admin']['address']['FIRSTNAME'])){ functions::xecho($func->show_str($_SESSION['m_admin']['address']['FIRSTNAME']));} ?>"/></td>
                    </tr>
                    <tr id="function_p" <?php if($_SESSION['m_admin']['contact']['IS_CORPORATE_PERSON'] == 'N'){ echo 'style="display:none"';}?>>
                        <td><label for="function"><?php echo _FUNCTION;?>&nbsp;: </label></td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" ><input class="contact_field_margin" disabled name="function" type="text"  id="function" value="<?php if(isset($_SESSION['m_admin']['address']['FUNCTION'])){functions::xecho($func->show_str($_SESSION['m_admin']['address']['FUNCTION']));} ?>"/></td>
                    </tr>
        <?php if($_SESSION['m_admin']['address']['IS_PRIVATE'] == 'N'){ ?>
                    <tr>
                        <td><?php echo _OCCUPANCY;?>&nbsp;: </td>
                        <td>&nbsp;</td>
                        <td class="indexing_field"><input class="contact_field_margin" disabled name="occupancy" type="text"  id="occupancy" value="<?php if(isset($_SESSION['m_admin']['address']['OCCUPANCY'])){functions::xecho($func->show_str($_SESSION['m_admin']['address']['OCCUPANCY'])); }?>"/></td>
                    </tr>
                    <tr>
                        <td><label for="num"><?php echo _NUM;?> : </label></td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" ><input class="contact_field_margin" disabled name="num" type="text"  id="num" value="<?php if(isset($_SESSION['m_admin']['address']['ADD_NUM'])){functions::xecho($func->show_str($_SESSION['m_admin']['address']['ADD_NUM'])); }?>"/></td>
                    </tr>
                    <tr>
                        <td><label for="street"><?php echo _STREET;?>&nbsp;: </label></td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" ><input class="contact_field_margin" disabled name="street" type="text"  id="street" value="<?php if(isset($_SESSION['m_admin']['address']['ADD_STREET'])){ functions::xecho($func->show_str($_SESSION['m_admin']['address']['ADD_STREET'])); }?>"/></td>
                    </tr>
                    <tr>
                        <td><label for="add_comp"><?php echo _COMPLEMENT;?>&nbsp;: </label></td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" ><input class="contact_field_margin" disabled name="add_comp" type="text"  id="add_comp" value="<?php if(isset($_SESSION['m_admin']['address']['ADD_COMP'])){ functions::xecho($func->show_str($_SESSION['m_admin']['address']['ADD_COMP'])); }?>"/></td>
                    </tr>
                    <tr>
                        <td><?php echo _POSTAL_CODE;?>&nbsp;: </td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" ><input class="contact_field_margin" disabled name="cp" type="text" id="cp" value="<?php if(isset($_SESSION['m_admin']['address']['ADD_CP'])){functions::xecho($func->show_str($_SESSION['m_admin']['address']['ADD_CP'])); }?>"/></td>
                    </tr>
                    <tr>
                        <td><label for="town"><?php echo _TOWN;?>&nbsp;: </label></td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" ><input class="contact_field_margin" disabled name="town" type="text" id="town" value="<?php if(isset($_SESSION['m_admin']['address']['ADD_TOWN'])){ functions::xecho($func->show_str($_SESSION['m_admin']['address']['ADD_TOWN']));} ?>"/></td>
                    </tr>
                    <tr>
                        <td><label for="country"><?php echo _COUNTRY;?>&nbsp;: </label></td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" ><input class="contact_field_margin" disabled name="country" type="text"  id="country" value="<?php if(isset($_SESSION['m_admin']['address']['ADD_COUNTRY'])){ functions::xecho($func->show_str($_SESSION['m_admin']['address']['ADD_COUNTRY'])); }?>"/></td>
                    </tr>
                    <tr >
                        <td><label for="phone"><?php echo _PHONE;?>&nbsp;: </label></td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" ><input class="contact_field_margin" disabled name="phone" type="text"  id="phone" value="<?php if(isset($_SESSION['m_admin']['address']['PHONE'])){functions::xecho($func->show_str($_SESSION['m_admin']['address']['PHONE']));} ?>"/></td>
                    </tr>
                    <tr>
                        <td><label for="mail"><?php echo _MAIL;?>&nbsp;: </label></td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" ><input class="contact_field_margin" disabled name="mail" type="text" id="mail" value="<?php if(isset($_SESSION['m_admin']['address']['MAIL'])){ functions::xecho($func->show_str($_SESSION['m_admin']['address']['MAIL']));} ?>"/></td>
                    </tr>
            <?php } ?>            
                    <tr>
                        <td><?php echo _WEBSITE;?>&nbsp;: </td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" ><input class="contact_field_margin" disabled name="website" type="text" id="website" value="<?php if(isset($_SESSION['m_admin']['address']['WEBSITE'])){ functions::xecho($func->show_str($_SESSION['m_admin']['address']['WEBSITE']));} ?>"/></td>
                    </tr>   
                    <tr>
                        <td><?php echo _COMP_DATA;?>&nbsp;: </td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" ><textarea class="contact_field_margin" disabled name="comp_data"   id="comp_data"><?php if(isset($_SESSION['m_admin']['address']['OTHER_DATA'])){functions::xecho($func->show_str($_SESSION['m_admin']['address']['OTHER_DATA'])); }?></textarea></td>
                    </tr>
                    <tr>
                        <td><?php echo _IS_PRIVATE;?>&nbsp;: </td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" >
                            <input type="radio" disabled class="check" name="is_private" value="Y" <?php if($_SESSION['m_admin']['address']['IS_PRIVATE'] == 'Y'){?> checked="checked"<?php } ?> /><?php echo _YES;?>
                            <input type="radio" disabled class="check" name="is_private" value="N" <?php if($_SESSION['m_admin']['address']['IS_PRIVATE'] == 'N' OR $_SESSION['m_admin']['address']['IS_PRIVATE'] <> 'Y'){?> checked="checked"<?php } ?> /><?php echo _NO;?>
                        </td>
                    </tr>
                </table>
            </div>
        <?php if($_SESSION['m_admin']['address']['IS_PRIVATE'] == 'N'){ ?>
                <table width="65%">
                    <tr align="left">
                        <td colspan="4" onclick="new Effect.toggle('salutation_div', 'blind', {delay:0.2});
                    whatIsTheDivStatus('salutation_div', 'divStatus_salutation_div');"><label>
                        <span id="divStatus_salutation_div" style="color:#1C99C5;">>></span>&nbsp;<b><?php echo _SALUTATION;?></b></label></td>
                    </tr>
                </table>
            <div id="salutation_div" style="display:inline">
                <table width="65%">
                    <tr>
                        <td><?php echo _SALUTATION_HEADER;?>&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" ><textarea class="contact_field_margin" disabled name="salutation_header" id="salutation_header"><?php if(isset($_SESSION['m_admin']['address']['SALUTATION_HEADER'])){functions::xecho($func->show_str($_SESSION['m_admin']['address']['SALUTATION_HEADER'])); }?></textarea></td>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                        <td><?php echo _SALUTATION_FOOTER;?>&nbsp;: </td>
                        <td>&nbsp;</td>
                        <td class="indexing_field" ><textarea class="contact_field_margin" disabled name="salutation_footer" id="salutation_footer"><?php if(isset($_SESSION['m_admin']['address']['SALUTATION_FOOTER'])){functions::xecho($func->show_str($_SESSION['m_admin']['address']['SALUTATION_FOOTER'])); }?></textarea></td>
                    </tr>
                </table>
            </div>
            <?php } ?>
        </form>
    <?php
    }

    function contactEnabled($userId, $mode) {
        $db = new Database();
        $db->query("UPDATE contacts_v2 SET enabled = ? WHERE contact_id = ?", array($mode, $userId));
        $db->query("UPDATE contact_addresses SET enabled = ? WHERE contact_id = ?", array($mode, $userId));
    }

    function addressEnabled($addressId, $mode) {
        $db = new Database();
        $db->query("UPDATE contact_addresses SET enabled = ? WHERE id = ?", array($mode, $addressId));
    }

}
?>
