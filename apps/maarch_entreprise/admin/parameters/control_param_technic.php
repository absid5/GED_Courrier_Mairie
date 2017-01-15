<?php
/*
*
*   Copyright 2008,2015 Maarch
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
*
*   @author  Laurent Giovannoni <dev@maarch.org>
*/

$admin = new core_tools();
$admin->test_admin('admin_parameters', 'apps');
$_SESSION['m_admin']= array();
/****************Management of the location bar  ************/
$init = false;
if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true") {
    $init = true;
}
$level = "";
if(
    isset($_REQUEST['level']) && ($_REQUEST['level'] == 2 
    || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 
    || $_REQUEST['level'] == 1)
) {
    $level = $_REQUEST['level'];
}
$page_path = $_SESSION['config']['businessappurl'] 
    . 'index.php?page=control_param_technic&admin=parameters';
$page_label = _CONTROL_PARAM_TECHNIC;
$page_id = "control_param_technic";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/

//load XML configuration
if (file_exists(
    $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
    . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps'
    . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'config.xml'
)
) {
    $path = $_SESSION['config']['corepath'] . 'custom'
          . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
          . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
          . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . 'xml'
          . DIRECTORY_SEPARATOR . 'control_params.xml';
} else {
    $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
          . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
          . 'control_params.xml';
}

if (!file_exists($path)) {
    echo 'first create and configure ' . $path . '<br />';
    exit();
}

$xmlconfig = new DOMDocument();
$xmlconfig->load($path);
$MaarchCaptureGenParam = $xmlconfig->getElementsByTagName('MaarchCapture');
foreach ($MaarchCaptureGenParam as $MccParam) {
    //do nothing
}
$MccTestIt = $MccParam->getElementsByTagName('testIt')->item(0)->nodeValue;
$pathToMaarchCapture = $MccParam->getElementsByTagName('pathToMaarchCapture')->item(0)->nodeValue;

$func = new functions();

$pathToMailCapture = $pathToMaarchCapture . 'modules/MailCapture/';

echo '<br /><h2>' . _COMPONENT . ' : MaarchCapture/MailCapture</h2><br />';
echo '<div id="mailBoxDiv">';
echo '<ul class="fa-ul" style="font-size:14px;">';
if ($MccTestIt == 'false') {
    echo '<li>Component not configured to be tested.<br />';
    echo 'See ' . $path . ' to configure the test.<br /></li>';
} else {
    $arrayOfParams = array();
    $arrayOfParams = loadXmlParams($pathToMailCapture);
    $cptMailBox = 0;
    $_SESSION['mailAccounts'] = array();
    foreach ($arrayOfParams as $param) {
        echo '---------------------------------------'
            . '---------------------------------------<br />';
        echo '<br /><b>Config File : ' . $param->documentURI . '</b><br /><br />';
        $mailAccounts = array();
        $mailAccounts = $param->getElementsByTagName('accounts');
        foreach ($mailAccounts as $mailBox) {
            $returnTest = false;
            $_SESSION['mailAccounts'][$cptMailBox]['mailBoxUri'] = $mailBox->getElementsByTagName('mailbox')->item(0)->nodeValue;
            $_SESSION['mailAccounts'][$cptMailBox]['mailBoxUsername'] = $mailBox->getElementsByTagName('username')->item(0)->nodeValue;
            $_SESSION['mailAccounts'][$cptMailBox]['mailBoxPassword'] = $mailBox->getElementsByTagName('password')->item(0)->nodeValue;
            $paramsDetails = '<b><i>Test mailbox : </i></b>' . '<br />'
                . 'uri : ' . $_SESSION['mailAccounts'][$cptMailBox]['mailBoxUri'] . '<br />'
                . 'login : ' . $_SESSION['mailAccounts'][$cptMailBox]['mailBoxUsername'] . '<br />'
                . 'password : ***** <br />';
            ?>
            <li>
            <?php echo $paramsDetails;?>
            
            <span id="mailBox_<?php echo $cptMailBox;?>" name="mailBox_<?php echo $cptMailBox;?>">
                <i class="fa-li fa fa-spinner fa-spin" style="margin-left: -10px;position: inherit;margin-right: -7px;"></i>
            </span>
            <script language="javascript">
                var path_manage_script = '<?php echo $_SESSION["config"]["businessappurl"];?>'
                    + 'index.php?display=true&admin=parameters&page=ajaxMailBoxTest';

                new Ajax.Request(path_manage_script,
                {
                    method:'post',
                    parameters: {
                        mailBoxIndex : '<?php functions::xecho($cptMailBox);?>'
                    },
                    onSuccess: function(answer)
                    {
                        eval('response = ' + answer.responseText);
                        //console.log(response);
                        if (response.status == 'ok' ) {
                            $('mailBox_<?php echo $cptMailBox;?>').innerHTML = '<i class="fa fa-check fa-2x" style="color:#45AE52;"></i>'
                                + 'test mailbox success <br />';
                        } else {
                            $('mailBox_<?php echo $cptMailBox;?>').innerHTML = '<i class="fa fa-check fa-2x" style="color:red;"></i>'
                                + 'test mailbox failed <br /><br />error details : <br />' 
                                + response.errorDetails;
                        }
                        
                    }
                });
            </script>
            <?php
            echo '</li>';
            $cptMailBox++;
        }
        
    }
}
echo '</ul>';
echo '</div>';
/***********************************************************************************************/
/***********************************************************************************************/
/***********************************************************************************************/
echo '<br /><h2>' . _COMPONENT . ' : notifications/sendmail</h2> <br />';
echo '<div id="sendmailDiv">';
echo '<ul class="fa-ul" style="font-size:14px;">';
echo '<li>';

$_SESSION['sendmailAccounts'] = array();
$NotifSendmailGenParam = $xmlconfig->getElementsByTagName('notifications_sendmail');
foreach ($NotifSendmailGenParam as $notifSendmailParam) {
    //do nothing
}
$sendmailTestIt = $notifSendmailParam->getElementsByTagName('testIt')->item(0)->nodeValue;
$_SESSION['sendmailTo'] = $notifSendmailParam->getElementsByTagName('sendmailTo')->item(0)->nodeValue;
$sendmailConfPath = $notifSendmailParam->getElementsByTagName('sendmailConfPath')->item(0)->nodeValue;

if ($sendmailTestIt == 'false') {
    echo 'Component not configured to be tested.<br />';
    echo 'See ' . $path . ' to configure the test.<br />';
} else {
    
    $pathToNotifications = $_SESSION['config']['corepath'] . 'modules/notifications/batch/config/';
    $pathToSendmail = $_SESSION['config']['corepath'] . 'modules/sendmail/batch/config/';
    $arrayOfParams = array();
    $arrayOfParams = loadXmlParams($pathToNotifications);
    $arrayOfParams = loadXmlParams($pathToSendmail, $arrayOfParams);
    $cptSendmail = 0;

    foreach ($arrayOfParams as $param) {
        $cptSendmail++;
        //var_dump($param);
        echo '---------------------------------------'
            . '---------------------------------------<br />';
        echo '<br /><b>Config File : ' . $param->documentURI . '</b><br /><br />';
        $returnTest = false;
        $mailerParam = '';
        $mailerParam = $param->getElementsByTagName('MAILER');
        foreach ($mailerParam as $mailParam) {
            //var_dump($mailParam);
            //do nothing
        }
        
        //$sendmailType = $mailParam->getElementsByTagName('type')->item(0)->nodeValue;
        $_SESSION['sendmailAccounts'][$cptSendmail]['sendmailType'] = $mailParam->getElementsByTagName('type')->item(0)->nodeValue;
        if (
            $_SESSION['sendmailAccounts'][$cptSendmail]['sendmailType'] <> 'sendmail' && 
            $_SESSION['sendmailAccounts'][$cptSendmail]['sendmailType'] <> 'mail'
        ) {
            $sendmailDetails .= 'host : '. $mailParam->getElementsByTagName('smtp_host')->item(0)->nodeValue . '<br/>';
            $_SESSION['sendmailAccounts'][$cptSendmail]['sendmailHost'] = $mailParam->getElementsByTagName('smtp_host')->item(0)->nodeValue;

            $sendmailDetails .= 'port : '. $mailParam->getElementsByTagName('smtp_port')->item(0)->nodeValue . '<br/>';
            $_SESSION['sendmailAccounts'][$cptSendmail]['sendmailPort'] = $mailParam->getElementsByTagName('smtp_port')->item(0)->nodeValue;

            $sendmailDetails .= 'user : '. $mailParam->getElementsByTagName('smtp_user')->item(0)->nodeValue . '<br/>';
            $_SESSION['sendmailAccounts'][$cptSendmail]['sendmailUser'] = $mailParam->getElementsByTagName('smtp_user')->item(0)->nodeValue;

            $_SESSION['sendmailAccounts'][$cptSendmail]['sendmailPassword'] = $mailParam->getElementsByTagName('smtp_password')->item(0)->nodeValue;
            $sendmailDetails .= 'auth : '. $mailParam->getElementsByTagName('smtp_auth')->item(0)->nodeValue . '<br/>';

            $_SESSION['sendmailAccounts'][$cptSendmail]['sendmailAuth'] = $mailParam->getElementsByTagName('smtp_auth')->item(0)->nodeValue;
            $sendmailDetails .= 'secure : '. $mailParam->getElementsByTagName('smtp_secure')->item(0)->nodeValue . '<br/>';

            $_SESSION['sendmailAccounts'][$cptSendmail]['sendmailSecure'] = $mailParam->getElementsByTagName('smtp_secure')->item(0)->nodeValue;
            $sendmailDetails .= 'domains : '. $mailParam->getElementsByTagName('domains')->item(0)->nodeValue . '<br/>';

            $_SESSION['sendmailAccounts'][$cptSendmail]['sendmailDomains'] = $mailParam->getElementsByTagName('domains')->item(0)->nodeValue;
            $sendmailDetails .= 'charset : '. $mailParam->getElementsByTagName('charset')->item(0)->nodeValue . '<br/>';

            $_SESSION['sendmailAccounts'][$cptSendmail]['sendmailCharset'] = $mailParam->getElementsByTagName('charset')->item(0)->nodeValue;
        } else {
            $sendmailDetails = 'see more details at ' . $sendmailConfPath;
        }

        $paramsDetails = '<b><i>Test sendmail : </i></b>' . '<br />'
            . 'type : ' . $_SESSION['sendmailAccounts'][$cptSendmail]['sendmailType'] . '<br />'
            . 'sendmail to : ' . $_SESSION['sendmailTo']  . '<br />'
            . 'details : ' . $sendmailDetails . '<br />';
        echo $paramsDetails;

        ?>
         <span id="sendmail_<?php echo $cptSendmail;?>" name="sendmail_<?php echo $cptSendmail;?>">
                <i class="fa-li fa fa-spinner fa-spin" style="margin-left: -10px;position: inherit;margin-right: -7px;"></i>
            </span>
            <script language="javascript">
                var path_manage_script = '<?php echo $_SESSION["config"]["businessappurl"];?>'
                    + 'index.php?display=true&admin=parameters&page=ajaxSendmailTest';

                new Ajax.Request(path_manage_script,
                {
                    method:'post',
                    parameters: { 
                        sendmailIndex : '<?php functions::xecho($cptSendmail);?>'
                    },
                    onSuccess: function(answer)
                    {
                        eval('response = ' + answer.responseText);
                        //console.log(response);
                        if (response.status == 'ok' ) {
                            $('sendmail_<?php echo $cptSendmail;?>').innerHTML = '<i class="fa fa-check fa-2x" style="color:#45AE52;"></i>'
                                + 'test sendmail success <br />';
                        } else {
                            $('sendmail_<?php echo $cptSendmail;?>').innerHTML = '<i class="fa fa-check fa-2x" style="color:red;"></i>'
                                + 'test sendmail failed <br /><br />error details : <br />' 
                                + response.errorDetails;
                        }
                        
                    }
                });
            </script>
        <?php
    }
}
echo '</li></ul>';
echo '</div>';

function loadXmlParams ($pathToParams, $arrayOfParams = array())
{
    if (is_dir($pathToParams)) {
        $dir = opendir($pathToParams);
        while($file = readdir($dir)) {
            if (strtoupper(pathinfo($file, PATHINFO_EXTENSION)) == 'XML') {
                $pathToXml = $pathToParams . $file;
                $paramXml = new DOMDocument();
                $paramXml->load($pathToXml);
                array_push($arrayOfParams, $paramXml);
            }
        }
        closedir($dir);
    } else {
        functions::xecho ('path not exists : ' . $pathToParams);
    }
    return $arrayOfParams;
}

