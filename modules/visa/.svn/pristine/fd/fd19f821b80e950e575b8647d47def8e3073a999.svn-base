<?php
	if (isset($_SESSION['sign']['encodedPinCode'])){
		echo "{status:1, pin : '". functions::xssafe($_SESSION['sign']['encodedPinCode']) ."', index_key : '". functions::xssafe($_SESSION['sign']['indexKey']) ."'}";		
	}
	else echo "{status:0}";		
?>