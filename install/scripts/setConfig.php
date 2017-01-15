<?php

function setConfigXmlofApps($applicationname)
    {
    $xmlconfig = simplexml_load_file(realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/apps/maarch_entreprise/xml/config.xml');

    $CONFIG = $xmlconfig->CONFIG;
    $CONFIG->applicationname = $applicationname;

    $res = $xmlconfig->asXML();
        $fp = @fopen(realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/apps/maarch_entreprise/xml/config.xml', "w+");
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

if (empty($_REQUEST['applicationname'])) {

		$return2['status'] = 2;
        $return2['text'] = _SET_CONFIG_KO;

		$jsonReturn = json_encode($return2);

		echo $jsonReturn;
		exit;

  //echo("<p>" . $mail->getMessage() . "</p>");
} else {

	require_once 'install/class/Class_Install.php';
    
setConfigXmlofApps($_REQUEST['applicationname']);

//setConfigNotification_batch_config_Xml($from,$to,$host,$user,$pass,$_REQUEST['smtpType'],$port,$auth,$charset,$smtpSecure);
        $return2['status'] = 2;
        $return2['text'] = _SET_CONFIG_OK;

        $jsonReturn = json_encode($return2);

        echo $jsonReturn;
        exit;

}
?>
