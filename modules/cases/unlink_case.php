<?php
/**
* File : unlink_case.php
*
* Dissociate case to document
*
* @package  Maarch Entreprise 1.5
* @version 1.0
* @since 10/2005
* @license GPL
* @author  Alex ORLUC  <dev@maarch.org>
*/
require_once("modules".DIRECTORY_SEPARATOR."cases".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR.'class_modules_tools.php');
$cases = new cases();

$res_id = $_POST['res_id'];
$case_id = $_POST['case_id'];
if($cases->detach_res($case_id, $res_id)==''){
    echo 'true';
}else{
   echo 'false'; 
}



