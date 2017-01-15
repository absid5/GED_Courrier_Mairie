<?php

 

 function setConfigNotification_batch_config_Xml($from,$to,$host,$user,$pass,$type,$port,$auth,$charset,$smtpSecure)
    {

        $xmlconfig = simplexml_load_file(realpath('.').'/custom/maarch_courrier/modules/notifications/batch/config/config.xml');
        $CONFIG = $xmlconfig->CONFIG;
        $CONFIG->MaarchDirectory = realpath('.')."/";
        $chemin = $_SERVER['SERVER_ADDR'] . dirname($_SERVER['PHP_SELF']);
        $maarchUrl = rtrim($chemin, "install");
        $maarchUrl = $maarchUrl . 'cs_'.$_SESSION['config']['databasename'].'/';
        $CONFIG->MaarchUrl = $maarchUrl;
        $CONFIG->MaarchApps = 'maarch_entreprise';
        $CONFIG->TmpDirectory = realpath('.').'/modules/notifications/batch/tmp/';

        $MAILER = $xmlconfig->MAILER;
        $MAILER->type = $type;
        $MAILER->smtp_port = $port;
        $MAILER->smtp_host = $host;
        $MAILER->smtp_user = $user;
        $MAILER->smtp_password = $pass;
        if($auth == 1){
        $MAILER->smtp_auth = "true";
        }else{
        $MAILER->smtp_auth = "false";   
        }

        $LOG4PHP = $xmlconfig->LOG4PHP;
        $LOG4PHP->Log4PhpConfigPath = realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/apps/maarch_entreprise/xml/log4php.xml';


        $res = $xmlconfig->asXML();
        $fp = @fopen(realpath('.')."/custom/maarch_courrier/modules/notifications/batch/config/config.xml", "w+");
        if (!$fp) {
            return false;
            exit;
        }
        $write = fwrite($fp,$res);
        if (!$write) {
            return false;
            exit;
        }

    }

include($_SESSION['config']['corepath'] 
    . '/apps/maarch_entreprise/tools/mails/htmlMimeMail.php');





if ($return == false) {

		$return2['status'] = 2;
		//$return['text'] = 'Error données';
        $return2['text'] = "Authentication SMTP incorrect";

		$jsonReturn = json_encode($return2);

		echo $jsonReturn;
		exit;

  //echo("<p>" . $mail->getMessage() . "</p>");
} else {
	require_once 'install/class/Class_Install.php';
    
setConfigSendmail_batch_config_Xml($from,$to,$host,$username,$password,$type,$port,$auth,$charset,$smtpSecure);

setConfigNotification_batch_config_Xml($from,$to,$host,$user,$pass,$_REQUEST['smtpType'],$port,$auth,$charset,$smtpSecure);
        $return2['status'] = 2;
        $return2['text'] = 'Informations SMTP ok !!!! Regardez votre adresse email!!';

        $jsonReturn = json_encode($return2);

        echo $jsonReturn;
        exit;

}
?>
