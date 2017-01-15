<?php

if (
    file_exists('..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR. '..' 
                    . DIRECTORY_SEPARATOR . 'core'. DIRECTORY_SEPARATOR . 'init.php'
    )
) {
    include_once '../../../../core/init.php';
} else {
    include_once '../../core/init.php';
}

if (
    file_exists('custom'.DIRECTORY_SEPARATOR. $_SESSION['custom_override_id']
                . DIRECTORY_SEPARATOR . 'modules'. DIRECTORY_SEPARATOR . 'visa'
                . DIRECTORY_SEPARATOR . 'applet_controller.php'
    )
) {
    $path = 'custom/'. $_SESSION['custom_override_id'] .'/modules/visa/applet_controller.php';
} else {
    $path = 'modules/visa/applet_controller.php';
}


$_SESSION['cm']['resMaster'] = '';
$_SESSION['cm']['reservationId'] = '';

require_once 'core/class/class_functions.php';
require_once 'core/class/class_core_tools.php';
require_once 'core/class/class_db.php';
require_once 'core/class/class_request.php';
require_once 'core/class/class_security.php';
require_once 'core/class/class_resource.php';
require_once 'core/class/docservers_controler.php';
require_once 'modules/content_management/class/class_content_manager_tools.php';

$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();
$core_tools->load_js();
$function = new functions();
$sec = new security();
$cM = new content_management_tools();
$cMFeatures = array();
$cMFeatures = $cM->getCmParameters();

/* Values for objectType :
 * - resource
 * - attachment
 * - attachmentFromTemplate
 * - template
 * - templateStyle
*/
if (isset($_REQUEST['objectType'])) {
    $objectType = $_REQUEST['objectType'];
} else {
    $objectType = '';
}

if (isset($_REQUEST['objectTable'])) {
    $objectTable = $_REQUEST['objectTable'];
} else {
    $objectTable = '';
}
if (isset($_REQUEST['objectId'])) {
    $objectId = $_REQUEST['objectId'];
} else {
    $objectId = '';
}

$modeSign = $_REQUEST['modeSign'];

if ($objectType == '' || $objectTable == '' || $objectId == '' || $modeSign == '') {
    $_SESSION['error'] = _PARAM_MISSING_FOR_MAARCHCM_APPLET . ' ' 
    . $objectType . ' ' . $objectTable . ' ' . $objectId;
    //echo $_SESSION['error'];exit;
    header('location: ' . $_SESSION['config']['businessappurl'] 
        . 'index.php'
    );
    exit();
}

if (!isset($_SESSION['user']['pathToSignature']) ||$_SESSION['user']['pathToSignature'] == '') {
    $_SESSION['error'] = _IMG_SIGN_MISSING;
	echo $_SESSION['error'];exit;
}

if ($modeSign == '0' && (!isset($_SESSION['user']['thumbprint']) ||$_SESSION['user']['thumbprint'] == '')) {
    $_SESSION['error'] = _THUMBPRINT_MISSING;
	echo $_SESSION['error'];exit;
}

if (!isset($_SESSION['sign']['encodedPinCode'])){
	$pinCode = '0000';
	$index = '-1';
}
else {
	$pinCode = $_SESSION['sign']['encodedPinCode'];
	$index = $_SESSION['sign']['indexKey'];
}
/*
echo 'objectType : ' . $objectType . '<br>';
echo 'objectTable : ' . $objectTable . '<br>';
echo 'objectId : ' . $objectId . '<br>';
*/

$cookieKey = '';
$cptCook = 0;
foreach ($_COOKIE as $key => $value) {
    if ($cptCook == 0) {
        $cookieKey = $key . '=' . $value;
    }
    $cptCook++;
}

//init error session
$_SESSION['error'] = '';

?>
<div id="maarchcmdiv">
    <h3><?php echo _DISSMARTCARD_SIGNER_APPLET;?></h3>
    <br><?php echo _DONT_CLOSE;?><br /><br />
    <center><i <i class="fa fa-spinner fa-pulse fa-5x" title="<?php echo _DONT_CLOSE;?>"></i></center>
    <div id="maarchcm_error" class="error"></div>
    <applet ARCHIVE="<?php 
            echo $_SESSION['config']['coreurl'];?>modules/visa/dist/DisSmartCardSignerApplet.jar" 
        code="com.docimsol.applet.DisSmartCardSignerApplet" name="dissignerapplet" id="dissignerapplet" 
        WIDTH="1" HEIGHT="1" version = "1.0">
        <param name="url" value="<?php 
            echo $_SESSION['config']['coreurl'].$path;
        ?>">
        <param name="objectType" value="<?php functions::xecho($objectType);?>">
        <param name="objectTable" value="<?php functions::xecho($objectTable);?>">
        <param name="objectId" value="<?php functions::xecho($objectId);?>">
		
		<!--mis en commentaires par agnes 17 04 2015 -->
        <!--param name="userMaarch" value="<!--?php 
            //echo $cMFeatures['CONFIG']['userMaarchOnClient'];
        ?>"-->
        <!--param name="userMaarchPwd" value="<!--?php 
            //echo $cMFeatures['CONFIG']['userPwdMaarchOnClient'];
        ?>"-->
        <!--param name="psExecMode" value="<!--?php functions::xecho($cMFeatures['CONFIG']['psExecMode']);?>">
        <param name="mayscript" value="mayscript" /-->
		
		<!--nouveaux paramètres pour la signature -->
		<param name="thumbPrint" value="<?php functions::xecho($_SESSION['sign']['encoded_thumbprint']);?>">
        <param name="thumprintkeyIdx" value="<?php functions::xecho($_SESSION['sign']['indexKey_thumbprint']);?>">
        <param name="pinCode" value="<?php functions::xecho($pinCode);?>">
		<param name="pinCodeIdx" value="<?php functions::xecho($index);?>">
		<param name="timeStamp" value="0">
		<param name="reason" value="<?php echo utf8_decode($_SESSION['modules_loaded']['visa']['reason']);?>">
		<param name="location" value="<?php functions::xecho($_SESSION['modules_loaded']['visa']['location']);?>">
		
		<param name="exeSign" value="<?php functions::xecho($_SESSION['modules_loaded']['visa']['exeSign']);?>">
		<param name="mode" value="<?php echo $modeSign;?>">
		<param name="cookie" value="<?php echo $cookieKey;?>">
		
    </applet>
</div>
<p class="buttons">
    <input type="button" name="cancel" value="<?php 
        echo _CLOSE;
        ?>" class="button" onclick="window.close();"/>
</p>
