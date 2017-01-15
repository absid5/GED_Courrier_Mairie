<?php

/*
*   Copyright 2008-2015 Maarch
*
*   This file is part of Maarch Framework.
*
*   Maarch Framework is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Maarch Framework is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief  watermark a pdf
*
* @file watermark.php
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup indexing_searching
*/
require_once 'apps/maarch_entreprise/class/class_pdf.php';

if ($table == '') {
	$table = 'res_view_letterbox';
}
if (isset($watermarkForAttachments) && $watermarkForAttachments) {
    $filePathOnTmp = $file;
    $filePathOnTmpResult = $file;
    $watermarkTab = $_SESSION['modules_loaded']['attachments']['watermark'];
    $s_id = $sId;
} else {
    $filePathOnTmp = $viewResourceArr['file_path'];
    $filePathOnTmpResult = $viewResourceArr['file_path'];
    $watermarkTab = $_SESSION['features']['watermark'];
}

if ($watermarkTab['text'] == '') {
    $watermark = 'watermark by ' . $_SESSION['user']['UserId'];
} elseif ($watermarkTab['text'] <> '') {
	$watermark = $watermarkTab['text'];
	preg_match_all('/\[(.*?)\]/i', $watermarkTab['text'], $matches);
    $date_now = '';
    $sqlArr = array();
    for ($z=0;$z<count($matches[1]);$z++) {
    	$currentText = '';
    	if ($matches[1][$z] == 'date_now') {
    		$currentText = date('d-m-Y');
    	} elseif ($matches[1][$z] == 'hour_now') {
    		$currentText = date('H:m:i');
    	} else {
		    $dbView = new Database();
		    $query = " select " . $matches[1][$z] 
		        . " as thecolumn from " . $table . " where res_id = ?";
		    $stmt = $dbView->query($query, array($s_id));
		    $returnQuery = $stmt->fetchObject();
		    $currentText = $returnQuery->thecolumn;
    	}
    	$watermark = str_replace(
    		'[' . $matches[1][$z] . ']', 
    		$currentText, 
    		$watermark
    	);
    }  
}
$positionDefault = array();
$position = array();
$positionDefault['X'] = 50;
$positionDefault['Y'] = 450;
$positionDefault['angle'] = 30;
$positionDefault['opacity'] = 0.5;
if ($watermarkTab['position'] == '') {
    $position = $positionDefault;
} else {
    $arrayPos = explode(',', $watermarkTab['position']);
    if (count($arrayPos) == 4) {
        $position['X'] = trim($arrayPos[0]);
        $position['Y'] = trim($arrayPos[1]);
        $position['angle'] = trim($arrayPos[2]);
        $position['opacity'] = trim($arrayPos[3]);
    } else {
        $position = $positionDefault;
    }
}
$fontDefault = array();
$font = array();
$fontDefault['fontName'] = 'helvetica';
$fontDefault['fontSize'] = '10';
if ($watermarkTab['font'] == '') {
    $font = $fontDefault;
} else {
    $arrayFont = explode(',', $watermarkTab['font']);
    
    if (count($arrayFont) == 2) {
        $font['fontName'] = trim($arrayFont[0]);
        $font['fontSize'] = trim($arrayFont[1]);
    } else {
        $font = $fontDefault;
    }
}
$colorDefault = array();
$color = array();
$colorDefault['color1'] = '192';
$colorDefault['color2'] = '192';
$colorDefault['color3'] = '192';
if ($watermarkTab['text_color'] == '') {
    $color = $colorDefault;
} else {
    $arrayColor = explode(',', $watermarkTab['text_color']);
    if (count($arrayColor) == 3) {
        $color['color1'] = trim($arrayColor[0]);
        $color['color2'] = trim($arrayColor[1]);
        $color['color3'] = trim($arrayColor[2]);
    } else {
        $color = $colorDefault;
    }
}
// Create a PDF object and set up the properties
$pdf = new PDF("p", "pt", "A4");
$pdf->SetAuthor("MAARCH");
$pdf->SetTitle("MAARCH document");
$pdf->SetTextColor($color['color1'],$color['color2'],$color['color3']);

$pdf->SetFont($font['fontName'], '', $font['fontSize']);
//$stringWatermark = substr($watermark, 0, 11);
//$stringWatermark = $watermark;
$stringWatermark = explode(',', $watermark);
// Load the base PDF into template
$nbPages = $pdf->setSourceFile($filePathOnTmp);
//For each pages add the watermark
for ($cpt=1;$cpt<=$nbPages;$cpt++) {
    $tplidx = $pdf->ImportPage($cpt);
    $specs = $pdf->getTemplateSize($tplidx);
     //Add new page & use the base PDF as template
    $pdf->addPage($specs['h'] > $specs['w'] ? 'P' : 'L');
    $pdf->useTemplate($tplidx);
    //Set opacity
    $pdf->SetAlpha($position['opacity']);
    //Add Watermark
     for ($i=0; $i< 5; $i++) {
		//$position['Y'] = $position['Y']+10;
		$pdf->TextWithRotation(
			$position['X'], 
			$position['Y'], 
			utf8_decode($stringWatermark[$i]), 
			$position['angle']
		);
	}
}
$pdf->Output($filePathOnTmpResult, "F");

