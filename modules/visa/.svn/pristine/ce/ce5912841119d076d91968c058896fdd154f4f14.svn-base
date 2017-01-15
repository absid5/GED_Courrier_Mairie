<?php
require_once 'core/class/class_core_tools.php';
require_once 'core/class/class_db.php';
require_once 'core/core_tables.php';
require_once 'modules/attachments/attachments_tables.php';
require_once 'core/class/docservers_controler.php';
require_once 'core/docservers_tools.php';
require_once 'core/class/class_resource.php';

function writeLogIndex($EventInfo)
{
    $logFileOpened = fopen($_SESSION['config']['corepath'] . '/modules/visa/log/signFile_' . date('Y') . '_' . date('m'). '_' . date('d') . '.log', 'a');
    fwrite($logFileOpened, '[' . date('d') . '/' . date('m') . '/' . date('Y')
        . ' ' . date('H') . ':' . date('i') . ':' . date('s') . '] ' . $EventInfo
        . "\r\n"
    );
    fclose($logFileOpened);
}
//print_r($_REQUEST);exit;
$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();

if (!isset($_SESSION['user']['pathToSignature']) ||$_SESSION['user']['pathToSignature'] == '') {
    $_SESSION['error'] = _IMG_SIGN_MISSING;
	echo "{status:1, error : '". _IMG_SIGN_MISSING ."'}";
	exit;
}

if (!empty($_REQUEST['id']) && !empty($_REQUEST['collId'])){
	$objectId = $_REQUEST['id'];
	$tableName = 'res_view_attachments';
	$db = new Database();
    if (isset($_REQUEST['isVersion'])) {

        $stmt = $db->query("select res_id_version, format, res_id_master, title, identifier, type_id, attachment_type from "
            . $tableName 
            . " where attachment_type NOT IN ('converted_pdf','print_folder') and res_id_version = ?", array($objectId));

    } elseif (isset($_REQUEST['isOutgoing'])) {

        $stmt = $db->query("select res_id, format, res_id_master, title, identifier, type_id, attachment_type from " 
            . $tableName 
            . " where attachment_type = ? and res_id = ?", array('outgoing_mail', $objectId));
    } else {
        $stmt = $db->query("select res_id, format, res_id_master, title, identifier, type_id, attachment_type from ".$tableName." where (attachment_type NOT IN ('converted_pdf','print_folder')) and res_id = ?", array($objectId));
    }
	
    if ($stmt->rowCount() < 1) {
    	echo "{status:1, error : '". _FILE . ' ' . _UNKNOWN ."'}";
		exit;
		//$_SESSION['error'] = _FILE . ' ' . _UNKNOWN;
    } 
	else {
		$line = $stmt->fetchObject();
		$_SESSION['visa']['last_resId_signed']['res_id'] = $line->res_id_master;
		$_SESSION['visa']['last_resId_signed']['title'] = $line->title;
		$_SESSION['visa']['last_resId_signed']['identifier'] = $line->identifier;
		$_SESSION['visa']['last_resId_signed']['type_id'] = $line->type_id;
		
		include 'modules/visa/retrieve_attachment_from_cm.php';
		
		
		//java -jar C:\Temp\SigniText.jar C:\Temp\blowagie\Modele.pdf C:\Temp\blowagie\extracted\images\Modele-1.jpg 140 114 C:\Temp\blowagie\images
		if (!file_exists($fileOnDs)){
			echo "{status:1, error : 'Fichier $fileOnDs non present'}";
			exit;
		}
		$cmd = "java -jar " 
			. escapeshellarg($_SESSION['config']['corepath'] . "modules/visa/dist/SignPdf.jar") . " " 
			. escapeshellarg($fileOnDs) . " " 
			. escapeshellarg($_SESSION['user']['pathToSignature']) . " " 
			. escapeshellarg($_SESSION['modules_loaded']['visa']['width_blocsign']) . " " 
			. escapeshellarg($_SESSION['modules_loaded']['visa']['height_blocsign']) . " " 
			. escapeshellarg($_SESSION['config']['tmppath']);

		//echo $cmd;
		exec($cmd);
		
		$tmpFileName = pathinfo($fileOnDs, PATHINFO_BASENAME);
		$fileExtension = "pdf";
		
		include 'modules/visa/save_attach_res_from_cm.php';
		
		echo "{status:0, new_id : $id}";
		exit;
	}
} else {
	$_SESSION['error'] = _ATTACHMENT_ID_AND_COLL_ID_REQUIRED;
}
exit;

?>
