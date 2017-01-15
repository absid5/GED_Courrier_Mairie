<?php
/*
*   Copyright 2008-2012 Maarch
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

/**
* @brief class of install tools
*
* @file
* @author Arnaud Veber
* @date $date$
* @version $Revision$
* @ingroup install
*/

        $filename = realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/apps/maarch_entreprise/xml/config.xml';
        if (file_exists($filename)) {

        $xmlconfig = simplexml_load_file(realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/apps/maarch_entreprise/xml/config.xml');

        $CONFIG = $xmlconfig->CONFIG;

        $databaseserver = (string) $CONFIG->databaseserver;
        $databasetype = (string) $CONFIG->databaseserverport;
        $databasename = (string) $CONFIG->databasename;
        $databaseuser = (string) $CONFIG->databaseuser;
        $lang = (string) $CONFIG->lang;
        $nblinetoshow = (string) $CONFIG->nblinetoshow;
        $debug = (string) $CONFIG->debug;
        $applicationname = $CONFIG->applicationname;
        // var_dump((string) $CONFIG->applicationname);
        // var_dump($applicationname);
        $COLLECTION = $xmlconfig->COLLECTION;
        $path_to_lucene_index = (string) $COLLECTION->path_to_lucene_index;

        $xmlconfigSMTP = simplexml_load_file(realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/modules/notifications/batch/config/config.xml');

        $MAILER = $xmlconfigSMTP->MAILER;

        $type = (string) $MAILER->type;
        $smtp_host = (string) $MAILER->smtp_host;
        $smtp_port = (string) $MAILER->smtp_port;
        $smtp_user = (string) $MAILER->smtp_user;
        $smtp_auth = (string) $MAILER->smtp_auth;
        $smtp_secure = (string) $MAILER->smtp_secure;

?>
<script>
   
    function setconfig(url,applicationname){
        // alert(url);
        // alert(applicationname);

        $(document).ready(function() {
            var oneIsEmpty = false;
            if (applicationname.length < 1) {
                var oneIsEmpty = true;
            }
            

            if (oneIsEmpty) {
                $('#ajaxReturn_testConnect_ko').html('<?php echo _ONE_FIELD_EMPTY;?>');
                return;
             }
             $('.wait').css('display','block');
             $('#ajaxReturn_testConnect_ko').html('');
        //alert("ok");
            ajaxDB(
                'setConfig',
                  'applicationname|'+applicationname,
                'ajaxReturn_testConnect',
                'false'
            );

            if (oneIsEmpty) {
                $('#ajaxReturn_testConnect_ok').html('<?php echo "connexion ok";?>');
                return;
             }

        });
        
 

    }


</script>
<div class="blockWrapper">
    <div class="titleBlock">
        <h2 onClick="slide('configNotificationSendmail');" style="cursor: pointer;">
            <?php echo _CONFIG_INFO;?>
        </h2>
    </div>
    <div class="contentBlock" id="docservers">
        <p>
            <h6>
                <?php echo _CONFIG_EXP;?>
            </h6>
            <form>
                <table>
                    <tr>
                        <td>
                            <?php echo _DATABASESERVER;?>
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <input type="text" name="databaseserver" id="databaseserver" disabled="disabled" value= <?php echo $databaseserver; ?> />
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo _SMTP_PORT;?></td>
                        <td>:</td>
                        <td><input type="text" name="databasetype" id="databasetype" disabled="disabled" value= <?php echo $databasetype; ?> /></td>
                    </tr>
                    <tr>
                        <td><?php echo _DATABASENAME;?></td>
                        <td>:</td>
                        <td><input type="text" name="databasename" id="databasename" disabled="disabled" value=<?php echo $databasename; ?> /></td>
                    </tr>
                    <tr>
                        <td><?php echo _USER_BDD;?></td>
                        <td>:</td>
                        <td><input type="text" name="databaseuser" id="databaseuser" disabled="disabled" value=<?php echo $databaseuser; ?> /></td>
                    </tr>
                    <tr>
                        <td><?php echo _LANG;?></td>
                        <td>:</td>
                        <td><input type="text" name="lang" id="lang" disabled="disabled" value=<?php if($lang == 'fr'){echo 'Français';}elseif($lang == 'en'){echo 'English';} ?> /></td>
                    </tr>
                    <!--tr>
                        <td><?php echo _NBLINETOSHOW;?></td>
                        <td>:</td>
                        <td><input type="text" name="nblinetoshow" id="nblinetoshow" disabled="disabled" value=<?php echo $nblinetoshow; ?> /></td>
                    </tr>
                    <tr>
                        <td><?php echo _MODEDEBUG;?></td>
                        <td>:</td>
                        <td><input type="text" name="debug" id="debug" disabled="disabled" value=<?php echo $debug; ?> /></td>
                    </tr-->
                    <tr>
                        <td><?php echo _APPLICATIONNAME;?></td>
                        <td>:</td>
                        <td><input type="text" name="applicationname" id="applicationname" value="<?php echo (string) $applicationname; ?> "/></td>
                    </tr>
                    <tr>
                        <td><?php echo _PATH_TO_DOCSERVER;?></td>
                        <td>:</td>
                        <td><input type="text" name="smtpPassword" id="smtpPassword" disabled="disabled" value=<?php echo $path_to_lucene_index; ?> /></td>
                    </tr>

                    <tr>
                        <td></td>
                        <td></td>
                        <td>
    <input type="button" id="ajaxReturn_testConnect_button" onClick="setconfig('setConfig', $('#applicationname').val())"; value="<?php echo _SET_CONFIG;?>"/>
</td>
                    </tr>
                    
                    
                </table>
            </form>
            <br />
            <div id="ajaxReturn_testConnect_ko"></div>
            <div align="center">
                    <img src="img/wait.gif" width="100" class="wait" style="display: none; background-color: rgba(0, 0, 0, 0.2);"/>
            </div>
            <div id="ajaxReturn_testConnect_ok"></div>
        </p>
        <p>
            <h6>
                <?php echo _CONFIG_SMTP_EXP;?>
            </h6>
            <form>
                <table>
                    <tr>
                        <td>
                            <?php echo _TYPE;?>
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <input type="text" name="smtptype" id="smtptype" disabled="disabled" value= <?php echo $type; ?> />
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo _SMTP_HOST;?></td>
                        <td>:</td>
                        <td><input type="text" name="smtphost" id="smtphost" disabled="disabled" value= <?php echo $smtp_host; ?> /></td>
                    </tr>
                    <tr>
                        <td><?php echo _SMTP_PORT;?></td>
                        <td>:</td>
                        <td><input type="text" name="smtpport" id="smtpport" disabled="disabled" value=<?php echo $smtp_port; ?> /></td>
                    </tr>
                    <tr>
                        <td><?php echo _SMTP_USER;?></td>
                        <td>:</td>
                        <td><input type="text" name="smtpuser" id="smtpuser" disabled="disabled" value=<?php echo $smtp_user; ?> /></td>
                    </tr>
                    <tr>
                        <td><?php echo _SMTP_AUTH;?></td>
                        <td>:</td>
                        <td><input type="text" name="smtpauth" id="smtpauth" disabled="disabled" value=<?php echo $smtp_auth; ?> /></td>
                    </tr>

                    <tr>
                        <td></td>
                        <td></td>
                        
                    </tr>
                    
                    
                </table>
            </form>
            <br />
            <div id="ajaxReturn_testConnect_ko"></div>
            <div align="center">
                    <img src="img/wait.gif" width="100" class="wait" style="display: none; background-color: rgba(0, 0, 0, 0.2);"/>
            </div>
            <div id="ajaxReturn_testConnect_ok"></div>
        </p>
    </div>
</div>
<br />
<div class="blockWrapper">
    <div class="contentBlock" id="docservers">
        <p>
            <div id="buttons">
                <!--div style="float: left;" class="previousButton" id="previous">
                    <a href="#" onClick="goTo('index.php?step=docservers');" style="display:none;">
                        <?php echo _PREVIOUS;?>
                    </a>
                </div-->
                <!--div style="float: left;" class="previousButton" id="next">
                    <a href="#" onClick="goTo('index.php?step=password');" id="ajaxReturn_testConnect" >
                        <?php echo "Sauter étape";?>
                    </a>
                </div-->
                <!--div style="float: right;" class="nextButton" id="next">
                    <a href="#" onClick="goTo('index.php?step=password');" id="ajaxReturn_testConnect">
                        <?php echo _NEXT;?>
                    </a>
                </div-->
                <div style="float: right;" class="nextButton" id="next">
                    <a href="#" onClick="goTo('index.php?step=resume');">
                        <?php echo _NEXT_INSTALL;?>
                    </a>
                </div>
                
            </div>
            <br />
            <br />
        </p>
    </div>
</div>

<?php 

        }else{

            echo "fichier de configuration non trouvé : vérifier votre custom";

?>
<div class="blockWrapper">
    <div class="contentBlock" id="docservers">
        <p>
            <div id="buttons">
                <div style="float: left;" class="previousButton" id="previous">
                    <a href="#" onClick="goTo('index.php?step=database');" style="display:block;">
                        <?php echo _PREVIOUS;?>
                    </a>
                </div>
                <!--div style="float: left;" class="previousButton" id="next">
                    <a href="#" onClick="goTo('index.php?step=password');" id="ajaxReturn_testConnect" >
                        <?php echo "Sauter étape";?>
                    </a>
                </div-->
                <!--div style="float: right;" class="nextButton" id="next">
                    <a href="#" onClick="goTo('index.php?step=password');" id="ajaxReturn_testConnect">
                        <?php echo _NEXT;?>
                    </a>
                </div-->
                <!--div style="float: right;" class="nextButton" id="next">
                    <a href="#" onClick="goTo('index.php?step=resume');">
                        <?php echo _NEXT_INSTALL;?>
                    </a>
                </div-->
                
            </div>
            <br />
            <br />
        </p>
    </div>
</div>

<?php

        }

?>