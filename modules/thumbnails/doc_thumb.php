<?php
	
	require_once "modules" . DIRECTORY_SEPARATOR . "thumbnails" . DIRECTORY_SEPARATOR
			. "class" . DIRECTORY_SEPARATOR
			. "class_modules_tools.php";
			
	$res_id = $_REQUEST['res_id'];
	$coll_id = $_REQUEST['coll_id'];
	
	$tnl = new thumbnails();
	$path = $tnl->getPathTnl($res_id, $coll_id);
	if (!is_file($path)){
		//$path = 'modules'. DIRECTORY_SEPARATOR . 'thumbnails' . DIRECTORY_SEPARATOR . 'no_thumb.png';
		exit();
	}
	$mime_type = 'image/png';	
	$date = mktime(0,0,0,date("m" ) + 2  ,date("d" ) ,date("Y" )  );
	$date = date("D, d M Y H:i:s", $date);
	$time = 30*12*60*60;
	header("Pragma: public");
	header("Expires: ".$date." GMT");
	header("Cache-Control: max-age=".$time.", must-revalidate");
	header("Content-Description: File Transfer");
	header("Content-Type: ".$mime_type);
	header("Content-Disposition: inline; filename=".$tnlFilename.";");
	header("Content-Transfer-Encoding: binary");
	readfile($path);
		
	exit();
	
?>