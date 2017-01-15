<?php
$core_path = $_SESSION['config']['corepath'];
$core_path = str_replace("\\", "/", $core_path);
define('FPDF_FONTPATH',$core_path.'apps/maarch_entreprise/tools/pdflib/font/');
//above line is import to define, otherwise it gives an error : Could not include font metric file
require($core_path.'apps/maarch_entreprise/tools/pdflib/fpdf.php');


if(isset($argv[1])){
	$service = $argv[1];
	$dossier = $argv[2];
	$label = $argv[3];
}
else {
	$service = $_REQUEST['service'];
	$dossier = $_REQUEST['path'];
	$label = $_REQUEST['label'];
}


$pdf = new FPDF();
$pdf->AddPage();
$pdf->Cell(180,10,'',0,1, 'C');
$pdf->Cell(180,10,'',0,1, 'C');
$pdf->Cell(180,10,'',0,1, 'C');
$pdf->Cell(180,10,'',0,1, 'C');
$pdf->Cell(180,10,'',0,1, 'C');
$pdf->Cell(180,10,'',0,1, 'C');
$pdf->Cell(180,10,'',0,1, 'C');
$pdf->Cell(180,10,'',0,1, 'C');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(180,10, utf8_decode($label),1,1, 'C');

$filename = $dossier."/sep_".$service.".pdf";
$pdf->Output($filename, 'F');
?>