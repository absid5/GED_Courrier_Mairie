<?php
/**
* File : load_diffusiontype_formcontent
*
* Script called by an ajax object to process the diffusion type change during
*
* @package  Maarch Entreprise Notifiation Modules
* @version 1.3
* @since 10/2005
* @license GPL v3
* @author Loïc Vinet  <dev@maarch.org>
*/

require_once 'modules' . DIRECTORY_SEPARATOR . 'notifications' . DIRECTORY_SEPARATOR
    . 'class' . DIRECTORY_SEPARATOR . 'diffusion_type_controler.php';

if ((! isset($_REQUEST['id_type']) || empty($_REQUEST['id_type']))) {
        // $_SESSION['error'] = _TYPE_EMPTY;
    
    echo "{status : 1, error_txt : '" . addslashes($_SESSION['error']) . "'}";
    exit();
}

if (empty($_REQUEST['origin'])) {
    $_SESSION['error'] = _ORIGIN . ' ' . _UNKNOWN;
    echo "{status : 2, error_txt : '" . addslashes($_SESSION['error']) . "'}";
    exit();
}
//--------------------------------------------------

$core = new core_tools();
$core->load_lang();
$dType = new diffusion_type_controler();
$diffType = array();
$diffType = $dType->getAllDiffusion();

foreach($diffType as $loadedType) {
	if ($loadedType->id == $_REQUEST['id_type']){
		if ($loadedType->script <> '') {		
			$request = 'form_content';
			$formId = 'diffusion_type';
			$leftList = 'diffusion_values';
			$rightList = 'diffusion_properties';
			$form_content = '';
			include_once($loadedType->script);
			echo "{status : 0, div_content : '" . addslashes($form_content) . "'}";
		} else {
			 echo "{status : 1, error_txt : '" . addslashes($_SESSION['error']) . "'}";
		}
	}
}	
exit();
