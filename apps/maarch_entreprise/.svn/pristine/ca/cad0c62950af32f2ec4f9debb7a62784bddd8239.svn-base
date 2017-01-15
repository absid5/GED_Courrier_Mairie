<?php 

/*

+-----------------------------------------------------------------+
|   Created by Chirag Mehta - http://chir.ag/tech/download/pdfb   |
|-----------------------------------------------------------------|
|                      For PDFB Library                           |
+-----------------------------------------------------------------+

*/

  define('FPDF_FONTPATH','pdfb/fpdf_fpdi/font/');
  require('fpdf_fpdi/previous_version/fpdi.php');
  require('barcode/barcodeimage.php');

  class PDFB extends FPDI
  {
    var $barcodeData;
    var $freaddata;
    var $freadcursor;

    // $barcode = any alphanumber string or UPC-A valid numeric code
    // $type = one of "C39", "C128A", "C128B", "C128C", "I25", "UPCA"
    // $x, $y = position on the PDF page
    // $w, $h = dimensions of the BarCode image
    // $sx, $sy = X & Y scaling of the BarCode image
    // $xres = thickness of the Bars in the Barcode - X-Resolution (1,2,3)
    // $font = Font size for BarCode value (1,2,3,4,5)
    // $link = URL to link the BarCode to
    // $format = "PNG" (default) or "JPEG"

    // If you wish to make high-resolution barcodes:
    // -> use $xres = 2 or $xres = 3
    // -> use $font = 5
    // -> use $w, $h = large dimensions
    // -> use $sx, $sy = (0.5, 0.5) or (0.25, 0.25) to scale it down to desire size.
    // Then zooming into the image you will see that it's quite scalable and high-resolution

    function BarCode($barcode, $type="", $x=0, $y=0, $w=0, $h=0, $sx=1, $sy=1, $xres=2, $font=5, $link="", $format="PNG")
    {
      $barcode = substr(trim($barcode), 0, 32);
      $type = strtoupper(trim($type));
      if($w == 0) $w = 216/$this->k;
      if($h == 0) $h = 144/$this->k;
      $bw = $w*$this->k;
      $bh = $h*$this->k;

      if($barcode == "")
        $this->Error('Invalid Barcode Text.');
      if($bw < 10 || $bw > 4096 || $bh < 10 || $bh > 4096)
        $this->Error('Invalid Barcode Size: ' . $w . "x" . $h);
      if($bw*$sx < 10 || $bw*$sx > 4096 || $bh*$sy < 10 || $bh*$sy > 4096)
        $this->Error('Invalid Barcode Scaling: ' . $sx . "x" . $sy);

      // If Bar Code hasn't been generated yet, generate it
      if(!isset($this->barcodeData[$barcode]))
      {
        if($format == "PNG")
        {
          $data = generateBarCodePNG($barcode, $type, $bw, $bh, BCD_DEFAULT_STYLE, $xres, $font, false);
          $this->barcodeData[$barcode] = $this->_parsepngfromstring($data);
        }
        else
        {
          $data = generateBarCodeJPEG($barcode, $type, $bw, $bh, BCD_DEFAULT_STYLE, $xres, $font, false);
          $this->barcodeData[$barcode] = array('w'=>$bw,'h'=>$bh,'cs'=>'DeviceRGB','bpc'=>8,'f'=>'DCTDecode','data'=>$data);
        }
      }

      // If Bar Code generated, show it
      if(isset($this->barcodeData[$barcode]))
      {
        $this->Image($barcode, $x, $y, $w*$sx, $h*$sy, "barcode", $link);
      }
      else
        $this->Error('Error creating Bar Code Image: ' . $barcode);
    }

    function _parsebarcode($barcode)
    {
      if(!isset($this->barcodeData[$barcode]))
        $this->Error('Error reading Bar Code Image: ' . $barcode);
      return $this->barcodeData[$barcode];
    }

    function _parseread($ln)
    {
      $st = substr($this->freaddata, $this->freadcursor, $ln);
      $this->freadcursor += $ln;
      return $st;
    }

    function _parsepngfromstring($f)
    {
      $this->freaddata = $f;
      $this->freadcursor = 0;

      //Check signature
      if($this->_parseread(8)!=chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10))
        $this->Error('Not a PNG file: '.$file);
      //Read header chunk
      $this->_parseread(4);
      if($this->_parseread(4)!='IHDR')
        $this->Error('Incorrect PNG info.');
      $w=$this->_parsereadint();
      $h=$this->_parsereadint();
      $bpc=ord($this->_parseread(1));
      if($bpc>8)
        $this->Error('16-bit depth not supported.');
      $ct=ord($this->_parseread(1));
      if($ct==0)
        $colspace='DeviceGray';
      elseif($ct==2)
        $colspace='DeviceRGB';
      elseif($ct==3)
        $colspace='Indexed';
      else
        $this->Error('Alpha channel not supported.');
      if(ord($this->_parseread(1))!=0)
        $this->Error('Unknown compression method.');
      if(ord($this->_parseread(1))!=0)
        $this->Error('Unknown filter method.');
      if(ord($this->_parseread(1))!=0)
        $this->Error('Interlacing not supported.');
      $this->_parseread(4);
      $parms='/DecodeParms <</Predictor 15 /Colors '.($ct==2 ? 3 : 1).' /BitsPerComponent '.$bpc.' /Columns '.$w.'>>';
      //Scan chunks looking for palette, transparency and image data
      $pal='';
      $trns='';
      $data='';
      do
      {
        $n=$this->_parsereadint();
        $type=$this->_parseread(4);
        if($type=='PLTE')
        {
          //Read palette
          $pal=$this->_parseread($n);
          $this->_parseread(4);
        }
        elseif($type=='tRNS')
        {
          //Read transparency info
          $t=$this->_parseread($n);
          if($ct==0)
            $trns=array(ord(substr($t,1,1)));
          elseif($ct==2)
            $trns=array(ord(substr($t,1,1)),ord(substr($t,3,1)),ord(substr($t,5,1)));
          else
          {
            $pos=strpos($t,chr(0));
            if($pos!==false)
              $trns=array($pos);
          }
          $this->_parseread(4);
        }
        elseif($type=='IDAT')
        {
          //Read image data block
          $data.=$this->_parseread($n);
          $this->_parseread(4);
        }
        elseif($type=='IEND')
          break;
        else
          $this->_parseread($n+4);
      }
      while($n);
      if($colspace=='Indexed' && empty($pal))
        $this->Error('Missing palette in '.$file);
      return array('w'=>$w,'h'=>$h,'cs'=>$colspace,'bpc'=>$bpc,'f'=>'FlateDecode','parms'=>$parms,'pal'=>$pal,'trns'=>$trns,'data'=>$data);
    }

    function _parsereadint()
    {
      //Read a 4-byte integer from file
      $a=unpack('Ni',$this->_parseread(4));
      return $a['i'];
    }

    function closeParsers()
    {
      if($this->parsers)
        foreach($this->parsers as $parser)
          $parser->closeFile();
    }
  }

?>