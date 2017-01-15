<?php
/**
* File : maarch_credits.php
*
* Show all contributors for Maarch.
* Thanks a lot for your help!!
*
* @package  Maarch FrameWork 3.0
* @version 2.1
* @since 10/2005
* @license GPL
* @author  Loic Vinet <dev@maarch.org>
*/

require_once('core/class/class_security.php');
$core_tools = new core_tools();

/****************Management of the location bar  ************/
$init = false;
if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true") {
    $init = true;
}
$level = "";
if (
    isset($_REQUEST['level']) 
    && ($_REQUEST['level'] == 2 
        || $_REQUEST['level'] == 3 
        || $_REQUEST['level'] == 4 
        || $_REQUEST['level'] == 1
    )
) {
    $level = $_REQUEST['level'];
}
$page_path = $_SESSION['config']['businessappurl'] .
    'index.php?page=boxes&module=maarch_credits';
$page_label = _MAARCH_CREDITS;
$page_id = "maarch_credits";
$core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
?>

<h1><i class="fa fa-info-circle fa-2x"></i> <?php echo _MAARCH_CREDITS;?>
</h1>
<div id="inner_content" class="clearfix">
    <div class="maarch_credits_left_box" style="height:520px;">
        <h3><?php echo _MAARCH_CREDITS;?></h3>
        <hr/>
        <p><em>Copyright &copy; 2008-2015 Maarch SAS.</em></p>
        <p><?php echo _MAARCH_LICENCE;?> 
            <a target="blank" href="http://www.gnu.org/licenses/gpl-3.0-standalone.html">licence GNU GPLv3</a>.</p>
        <div>
            <ul>
                <li><?php echo _OFFICIAL_WEBSITE;?> : <a target="blank" href="http://www.maarch.com">www.maarch.com</a></li>
                <li><?php echo _COMMUNITY;?> : <a target="blank" href="http://forum.maarch.org">forum.maarch.org</a></li>
                <li><?php echo _DOCUMENTATION;?> : <a target="blank" href="http://wiki.maarch.org/Accueil">wiki.maarch.org</a></li>
            </ul>
        </div>
        <p>&nbsp;</p>
        <h3><?php echo _EXTERNAL_COMPONENTS;?></h3>
        <hr/>
        <em><?php echo _THANKS_TO_EXT_DEV;?></em>
        <p>&nbsp;</p>
        <ul>
            <li><a target="blank" href="http://www.chartjs.org/">Chart.js</a></li>
            <li><a target="blank" href="http://fortawesome.github.io/Font-Awesome/">Font Awesome</a></li>
            <li><a target="blank" href="http://www.fpdf.org/">Fpdf</a></li>
            <li><a target="blank" href="http://www.setasign.de/products/pdf-php-solutions/fpdi/">fpdi</a></li>
            <li><a target="blank" href="http://logging.apache.org/log4php/">log4php</a></li>
            <li><a target="blank" href="http://chir.ag/tech/download/pdfb">Pdfb</a></li>
            <li><a target="blank" href="http://www.foolabs.com/xpdf/">Pdftotext</a></li>
            <li><a target="blank" href="http://www.prototypejs.org/">Prototype</a></li>
            <li><a target="blank" href="http://script.aculo.us/">Script.aculo.us</a></li>
            <li>Tabricator</li>
            <li><a target="blank" href="http://tafel.developpez.com">Tafel Tree</a></li>
            <li><a target="blank" href="http://www.tinybutstrong.com/">Tiny But Strong</a></li>
            <li><a target="blank" href="http://www.tinymce.com/">TinyMCE</a></li>
            <li><a target="blank" href="http://framework.zend.com/">Zend Lucene Search</a></li>
        </ul>
    </div>

    <div class="credits_list block" style="height:520px;">
        <h3>Credits</h3>
        <p>&nbsp;</p>
        <ul>
            <li>Florian AZIZIAN</li>
            <li>Damien BUREL</li>
            <li>Bruno CARLIN</li>
            <li>Carole COTIN</li>
            <li>Driss DEMIRAY</li>
            <li>Gaël DE VILLEBLANCHE</li>
            <li>Mathieu DONZEL</li>
            <li>Jean-Louis ERCOLANI</li>
            <li>Claire FIGUERAS</li>
            <li>Laurent GIOVANNONI</li>
            <li>Henri QUENEAU</li>
            <li>Kader KANE</li>
            <li>Yves-Christian KPAKPO</li>
            <li>Sébastien NANABONDJA</li>
            <li>Fodé NDIAYE</li>
            <li>Cédric NDOUMBA</li>
            <li>Alex ORLUC</li>
            <li>Thomas PENARUIZ</li>
            <li>Alexandre STEFANOVIC</li>
            <li>Serge THIERRY-MIEG</li>
            <li>Cyril VAZQUEZ</li>
            <li>Arnaud VEBER</li>
            <li>Loic VINET</li>
            <li>&nbsp;</li>
            <li><em><?php echo _THANKS_TO_COMMUNITY;?></em></li>
        </ul>
        <p>&nbsp;</p>
        <div class="img_credits_maarch_box">
            <img src="<?php 
                echo $_SESSION['config']['businessappurl'];
                ?>static.php?filename=maarch_box.png" />
        </div>
    </div>
</div>
<p style="clear:both"></p>
