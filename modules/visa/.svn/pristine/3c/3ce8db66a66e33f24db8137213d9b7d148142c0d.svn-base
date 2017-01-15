<?php
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

if (!empty($_REQUEST['pinCode'])) {
	$_SESSION['sign']['indexKey'] = rand(0,20);
	$encoded = encrypt($_REQUEST['pinCode'], $tabKey[$_SESSION['sign']['indexKey']]);
	$_SESSION['sign']['encodedPinCode'] = $encoded;
	
	if ($encoded == ''){
		$_SESSION['sign']['encodedPinCode'] = $_REQUEST['pinCode'];
		$_SESSION['sign']['indexKey'] = '-1';
	}
}

echo "{status:1}";	

?>