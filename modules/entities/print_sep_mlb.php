<?php
/**
* File : print_sep_mlb_form.php
*
* script to print standard separator
*
* @package  Maarch FrameWork 3.0
* @version 2.1
* @since 10/2005
* @license GPL
* @author Yves Christian KPAKPO<dev@maarch.org>
* @author Laurent Giovannoni<dev@maarch.org>
*/

require_once('core/class/class_functions.php');
require_once('core/class/class_db.php');
require_once('core/class/class_request.php');
require_once('core/class/class_core_tools.php');
require_once('apps/maarch_entreprise/tools/pdfb/barcode/pi_barcode.php');
require_once('apps/maarch_entreprise/tools/pdfb/fpdf_1_7/fpdf.php');
require_once('apps/maarch_entreprise/tools/pdfb/fpdf_1_7/fpdi.php');
require_once('modules/entities/class/class_get_barcode.php');
require_once('apps/maarch_entreprise/tools/phpqrcode/qrlib.php');

if ($_REQUEST['typeBarcode'] == '') {
    $_REQUEST['typeBarcode'] = 'C39';
}
$core_tools = new core_tools();
$core_tools->load_lang();
//Init
$ingoing = $ingoing_label = $priority = $priority_label = $entity = $entity_label = '';
if($_REQUEST['print_generic']) {
    $_REQUEST['entitieslist'] = array();
    $_REQUEST['entitieslist'][0] = 'COURRIER';
}
if(isset($_REQUEST['entitieslist']) && !empty($_REQUEST['entitieslist'])) {
    $db = new Database();
    $pdf= new fpdi();//create a new document PDF
    $cab_pdf= new barcocdeFPDF();//create a new document PDF
    //$pdf->DisplayPreferences('HideMenubar,HideToolbar,HideWindowUI');
    $title = _PRINT_SEP_TITLE;
    //to begin, create a img file to generate CAB
    for ($i=0; $i<count($_REQUEST['entitieslist']); $i++) {
        //Entity ID
        $entity = $_REQUEST['entitieslist'][$i];
        //Label
        $stmt = $db->query("select entity_label FROM " 
            . $_SESSION['tablename']['ent_entities'] 
            . " WHERE entity_id =?",array($entity)
        );
        $res = $stmt->fetchObject();
        $entity_label = html_entity_decode($res->entity_label);
        //Ingoing
        if ($_REQUEST['typeBarcode'] == 'C39') {
            $code = "*MAARCH " . $entity . "*";
            $type = 'C39';
        } else if($_REQUEST['typeBarcode'] == 'QRCODE'){
            $code = "*MAARCH " . $entity . "*";
            // create a QR Code with this text and display it
            $filename_QR = $_SESSION['config']['tmppath'].DIRECTORY_SEPARATOR.$_SESSION['user']['UserId'] . time() . rand() ."_QRCODE.png";
            QRcode::png($entity,$filename_QR, QR_ECLEVEL_L, 4);

        }else{
            $code = "MAARCH " . $entity;
            $type = $_REQUEST['typeBarcode'];
        }


        $pdf->addPage(); //Add a blank page
        $pdf->SetFont('Arial','B',20);
        $pdf->Cell(250,20,$title,0,1, 'C');
        $pdf->Cell(250,20,$code,0,1, 'C');
        $pdf->Cell(180,10,'',0,1, 'C');

        if($_REQUEST['typeBarcode'] == 'QRCODE'){
            $pdf->Image($filename_QR, 0, 0, 80);
        }else{
            $p_cab = $cab_pdf->generateBarCode($type, $code, 40, '', '', '');
            $pdf->Image($_SESSION['config']['tmppath'].DIRECTORY_SEPARATOR.$p_cab, 40, 50, 120);
        }
        
        $pdf->Cell(180,10,'',0,1, 'C');
        $pdf->Cell(180,10,'',0,1, 'C');
        $pdf->Cell(180,10,'',0,1, 'C');
        $pdf->Cell(180,10,'',0,1, 'C');
        $pdf->Cell(180,10,'',0,1, 'C');
        $pdf->Cell(180,10,'',0,1, 'C');
        $pdf->Cell(180,10,'',0,1, 'C');
        $pdf->Cell(180,10,'',0,1, 'C');
        $pdf->Cell(180,10, $core_tools->wash_html(utf8_decode(_ENTITY), "NO_ACCENT"),1,1, 'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(180,10, utf8_decode($entity_label),1,1, 'C');
    }
    //$pdf->AutoPrint(true);
    $pdf->Output($_SESSION['config']['tmppath'].DIRECTORY_SEPARATOR.$_SESSION['user']['UserId'] . ".PDF");
    ?>
    <center>
        <?php echo _PRINT_SEP_WILL_BE_START;?>

        <iframe src="<?php 
            echo $_SESSION['config']['businessappurl'];
        ?>index.php?display=true&module=entities&page=print_my_sep&try=<?php 
            functions::xecho($_REQUEST['try']); 
        ?>" name="print_my_sep" id="print_my_sep" frameborder="1" width="100%" height="800"></iframe>
        <br>
    </center>
    <?php
} else {
    ?>
    <center>
        <?php echo _PRINT_SEP_CHOOSE_DEPARTMENT_FIRST;?>
    </center>
    <?php
}
?>
