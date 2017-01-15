<?php

require_once('core/class/PrintControler.php');

if (isset($_REQUEST['id']) && $_REQUEST['id'] <> '') {
	$print = new PrintControler($_REQUEST['id']);
} else {
	$print = new PrintControler();
}

if (!empty($_SESSION['error'])) {
    
    ?>
    <script language="javascript" >
        window.opener.location.reload();
        window.close();
    </script>
    <?php
    
} else {
	if (
		file_exists($_SESSION['config']['tmppath'] . $_SESSION['print']['filename']) 
		&& $_SESSION['print']['filename'] <> ''
	) {
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		//header('Cache-Control: public');
		//header('Content-Description: File Transfer');
		header('Content-Type: application/pdf');
		header('Content-Disposition: inline; filename=print_maarch.pdf;');
		//header('Content-Transfer-Encoding: binary');
		readfile($_SESSION['config']['tmppath'] . $_SESSION['print']['filename']);
		unlink($_SESSION['config']['tmppath'] . $_SESSION['print']['filename']);
	} else {
		echo _NO_DOC_OR_NO_RIGHTS;
	}
    exit;
}
