<?php

require_once 'core/class/class_core_tools.php';
require_once 'core/class/class_db.php';
require_once 'modules/attachments/attachments_tables.php';

$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();


$tabKey = array(                        // Tableau contenant les 20 clé de cyptages
			"4A3GdU+0v91aT9nm",
			"kyCJfwxl9dpFvvHE",
			"WsE,0TRu56h3pp82",
			"8pcaf6r8JLT,Umz:",
			"ap2znvTS69ebmSPR",
			"jdkyCJfwxl9dpFvv",
			"Jfwxl9dpFvvHEI69",
			"pQ*2k23S5ywSkRs!",
			"bMFABR07ypWHnh:b",
			"3v+ze:RjUXhHkG?k",
			"gRGhBiTtETxVrAsJ",
			"KEfQRkD0YuZ67dR9",
			"8Y2X8KxN!IjMCgk3",
			"oPzxdErYWplXw7Nv",
			"jOC8nxDdKiW,nOFs",
			"YIAEDxt?GdykTkZ0",
			"LDwZ8HXWI0wA2ZDy",
			"?PSzdIcAhScEnerK",
			":V4rm9rFdOSmdWdj",
			"FNSOj0RUGP93zj2r"
		);
				
function encrypt($text, $key) { // fonction retournant la variable cryptée à partir d'une variable ($text) à crypter et d'une cle de cryptage
	$iv = "5AFTI85aDzR5570098ZezT9MmACTazR8"; // le vecteur d'initialisation
	if (in_array('mcrypt', get_loaded_extensions())){
		$crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_CBC, $iv);
		return base64_encode($crypttext);
	}
	else return '';
}
	
function decrypt($input, $key) {// fonction retournant la variable décryptée à partir d'une variable ($input) à décrypter et d'une cle de cryptage
        $iv = "5AFTI85aDzR5570098ZezT9MmACTazR8"; // le vecteur d'initialisation
		if (in_array('mcrypt', get_loaded_extensions())){
			$dectext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($input), MCRYPT_MODE_CBC, $iv);
			return $dectext;
		}
		else return '';
    }
	
if (
    file_exists(
        $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
        . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'modules'
        . DIRECTORY_SEPARATOR . 'visa' . DIRECTORY_SEPARATOR . 'applet_launcher.php'
    )
) {
    $path = 'custom/'. $_SESSION['custom_override_id'] .'/modules/visa/applet_launcher.php';
} else {
    $path = 'modules/visa/applet_launcher.php';
}

$_SESSION['sign']['indexKey_thumbprint'] = rand(0,20);
$encoded_tp = encrypt($_SESSION['user']['thumbprint'], $tabKey[$_SESSION['sign']['indexKey_thumbprint']]);
$_SESSION['sign']['encoded_thumbprint'] = $encoded_tp;

if ($encoded_tp == ''){
	$_SESSION['sign']['encoded_thumbprint'] = $_SESSION['user']['thumbprint'];
	$_SESSION['sign']['indexKey_thumbprint'] = '-1';
}
	


if (!empty($_REQUEST['id']) && !empty($_REQUEST['collId']) && isset($_REQUEST['modeSign'])) {
    $id = $_REQUEST['id'];
    $modeSign = $_REQUEST['modeSign'];
	$tableName = 'res_view_attachments';
	$db = new Database();
    if (isset($_REQUEST['isVersion'])) $stmt = $db->query("select res_id_version, format, res_id_master, title, identifier, type_id from ".$tableName." where attachment_type = ? and res_id_version = ?", array('response_project', $id));
    else if (isset($_REQUEST['isOutgoing'])) $stmt = $db->query("select res_id, format, res_id_master, title, identifier, type_id from ".$tableName." where attachment_type = ? and res_id = ?", array('outgoing_mail', $id));
    else $stmt = $db->query("select res_id, format, res_id_master, title, identifier, type_id from ".$tableName." where attachment_type = ? and res_id = ?", array('response_project', $id));
	
    if ($stmt->rowCount() < 1) {
        echo _FILE . ' ' . _UNKNOWN.".<br/>";
    } 
	else {
        $line = $stmt->fetchObject();
		$_SESSION['visa']['last_resId_signed']['res_id'] = $line->res_id_master;
		$_SESSION['visa']['last_resId_signed']['title'] = $line->title;
		$_SESSION['visa']['last_resId_signed']['identifier'] = $line->identifier;
		$_SESSION['visa']['last_resId_signed']['type_id'] = $line->type_id;
		
            $core_tools->load_html();
            $core_tools->load_header();
            //$core_tools->load_js();
            ?>
            <body>
                <div id="container">
                    <div id="content">
                        <div class="error" id="divError" name="divError"></div>
                        <script language="javascript">
                            loadAppletSign('<?php 
								echo $_SESSION['config']['coreurl'] .''.$path;
								?>?objectType=ans_project&objectId=<?php 
                                echo $id;
                                ?>&objectTable=<?php 
                                echo RES_ATTACHMENTS_TABLE;
                                ?>&modeSign=<?php 
                                echo $modeSign;
                                ?>');
                        </script>
                    </div>
                </div>
            </body>
            </html>
            <?php    
    }
} else {
    echo _ATTACHMENT_ID_AND_COLL_ID_REQUIRED;
}
exit;
