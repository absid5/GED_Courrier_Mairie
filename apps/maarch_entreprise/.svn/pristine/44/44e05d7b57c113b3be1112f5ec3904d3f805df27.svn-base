<?php
if($_REQUEST['origin'] != 'graph'){
    require_once('core/class/ExportControler.php');
    $export = new ExportControler();
}
if (!empty($_SESSION['error'])) {
    
    ?>
    <script language="javascript" >
        window.opener.location.reload();
        window.close();
    </script>
    <?php
    
} else {
    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    //header('Cache-Control: public');
    //header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: inline; filename=export_maarch.csv;');
    //header('Content-Transfer-Encoding: binary');
    readfile($_SESSION['config']['tmppath'] . $_SESSION['export']['filename']);
    unlink($_SESSION['config']['tmppath'] . $_SESSION['export']['filename']);
    exit;
}
