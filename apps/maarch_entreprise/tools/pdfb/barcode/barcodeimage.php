<?php 
/*

+-----------------------------------------------------------------+
|   Created by Chirag Mehta - http://chir.ag/tech/download/pdfb   |
|-----------------------------------------------------------------|
|                      For PDFB Library                           |
+-----------------------------------------------------------------+

*/

  require("barcode.php");
  require("c39.php");
  require("c128a.php");
  require("c128b.php");
  require("c128c.php");
  require("i25.php");
  require("upca.php");

  function generateBarCodeJPEG($code, $type = "C128B", $width = BCD_DEFAULT_WIDTH, $height = BCD_DEFAULT_HEIGHT, $style = BCD_DEFAULT_STYLE, $xres = BCD_DEFAULT_XRES, $font = BCD_DEFAULT_FONT, $flushimg = false)
  {
    $style = $style ^ BCS_IMAGE_PNG | BCS_IMAGE_JPEG;
    return generateBarCodeImage($code, $type, $width, $height, $style, $xres, $font, $flushimg);
  }

  function generateBarCodePNG($code, $type = "C128B", $width = BCD_DEFAULT_WIDTH, $height = BCD_DEFAULT_HEIGHT, $style = BCD_DEFAULT_STYLE, $xres = BCD_DEFAULT_XRES, $font = BCD_DEFAULT_FONT, $flushimg = false)
  {
    $style = $style ^ BCS_IMAGE_JPEG | BCS_IMAGE_PNG;
    return generateBarCodeImage($code, $type, $width, $height, $style, $xres, $font, $flushimg);
  }

  function generateBarCodeImage($code, $type = "C128B", $width = BCD_DEFAULT_WIDTH, $height = BCD_DEFAULT_HEIGHT, $style = BCD_DEFAULT_STYLE, $xres = BCD_DEFAULT_XRES, $font = BCD_DEFAULT_FONT, $flushimg = false)
  {
    $obj = false;
    $barcodedata = false;

    $code = trim($code);
    if($code == "")
      return false;

    switch(strtoupper($type))
    {
      case "C39":
        $obj = new C39Object($width, $height, $style, $code);
        break;
      case "C128A":
        $obj = new C128AObject($width, $height, $style, $code);
        break;
      case "C128B":
        // default is C128B
      default:
        $obj = new C128BObject($width, $height, $style, $code);
        break;
      case "C128C":
        $obj = new C128CObject($width, $height, $style, $code);
        break;
      case "I25":
        $obj = new I25Object($width, $height, $style, $code);
        break;
      case "UPCA":
        $obj = new UPCAObject($width, $height, $style, $code);
        break;
    }

    if($obj)
    {
      $obj->SetFont($font);
      $obj->DrawObject($xres);

      if($flushimg)
      {
        $obj->FlushObject();
        $barcodedata = true;
      }
      else
      {
        $barcodedata = $obj->GetImage();
      }

      $obj->DestroyObject();
      unset($obj);
    }

    return $barcodedata;
  }

?>