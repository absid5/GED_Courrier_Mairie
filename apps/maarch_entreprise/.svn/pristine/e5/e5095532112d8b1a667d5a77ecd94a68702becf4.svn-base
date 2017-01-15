<?php 
/*
Barcode Render Class for PHP using the GD graphics library
Copyright (C) 2001  Karim Mribti

   Version  0.0.7a  2001-04-01

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

Copy of GNU Lesser General Public License at: http://www.gnu.org/copyleft/lesser.txt

Source code home page: http://www.mribti.com/barcode/
Contact author at: barcode@mribti.com

+-----------------------------------------------------------------+
|   Modified by Chirag Mehta - http://chir.ag/tech/download/pdfb  |
|-----------------------------------------------------------------|
|                      For PDFB Library                           |
+-----------------------------------------------------------------+

*/

/***************************** base class ********************************************/

  /* Styles */
  define("BCS_BORDER"                  ,    1);
  define("BCS_TRANSPARENT"             ,    2);
  define("BCS_ALIGN_CENTER"            ,    4);
  define("BCS_ALIGN_LEFT"              ,    8);
  define("BCS_ALIGN_RIGHT"             ,   16);
  define("BCS_IMAGE_JPEG"              ,   32);
  define("BCS_IMAGE_PNG"               ,   64);
  define("BCS_DRAW_TEXT"               ,  128);
  define("BCS_STRETCH_TEXT"            ,  256);
  define("BCS_REVERSE_COLOR"           ,  512);
  define("BCS_I25_DRAW_CHECK"          , 2048); // For the I25 Only

  /* Default values */
  define("BCD_DEFAULT_BACKGROUND_COLOR", 0xFFFFFF);
  define("BCD_DEFAULT_FOREGROUND_COLOR", 0x000000);
  define("BCD_DEFAULT_STYLE"           , BCS_ALIGN_CENTER | BCS_IMAGE_PNG | BCS_DRAW_TEXT | BCS_STRETCH_TEXT);
  define("BCD_DEFAULT_WIDTH"           , 216);
  define("BCD_DEFAULT_HEIGHT"          , 144);
  define("BCD_DEFAULT_FONT"            ,   5);
  define("BCD_DEFAULT_XRES"            ,   2);
  /* Margins */
  define("BCD_DEFAULT_MAR_Y1"          ,  10);
  define("BCD_DEFAULT_MAR_Y2"          ,  10);
  define("BCD_DEFAULT_TEXT_OFFSET"     ,   2);
  define("BCD_DEFAULT_TEXT_FILL_OFFSET", -10);
  /* For the I25 Only */
  define("BCD_I25_NARROW_BAR"          ,   1);
  define("BCD_I25_WIDE_BAR"            ,   2);
  /* For the C39 Only */
  define("BCD_C39_NARROW_BAR"          ,   1);
  define("BCD_C39_WIDE_BAR"            ,   2);
  /* For Code 128 */
  define("BCD_C128_BAR_1"              ,   1);
  define("BCD_C128_BAR_2"              ,   2);
  define("BCD_C128_BAR_3"              ,   3);
  define("BCD_C128_BAR_4"              ,   4);

  class BarcodeObject
  {
    var $mWidth, $mHeight, $mStyle, $mBgcolor, $mBrush;
    var $mImg, $mFont;
    var $mError;

    function BarcodeObject($Width = BCD_DEFAULT_WIDTH, $Height = BCD_DEFAULT_HEIGHT, $Style = BCD_DEFAULT_STYLE)
    {
      $this->mWidth   = $Width;
      $this->mHeight  = $Height;
      $this->mStyle   = $Style;
      $this->mFont    = BCD_DEFAULT_FONT;
      $this->mImg     = ImageCreate($this->mWidth, $this->mHeight);

      $dbColor        = $this->mStyle & BCS_REVERSE_COLOR ? BCD_DEFAULT_FOREGROUND_COLOR : BCD_DEFAULT_BACKGROUND_COLOR;
      $dfColor        = $this->mStyle & BCS_REVERSE_COLOR ? BCD_DEFAULT_BACKGROUND_COLOR : BCD_DEFAULT_FOREGROUND_COLOR;

      $this->mBgcolor = ImageColorAllocate($this->mImg, ($dbColor & 0xFF0000) >> 16, ($dbColor & 0x00FF00) >> 8 , $dbColor & 0x0000FF);
      $this->mBrush   = ImageColorAllocate($this->mImg, ($dfColor & 0xFF0000) >> 16, ($dfColor & 0x00FF00) >> 8 , $dfColor & 0x0000FF);

      if(!($this->mStyle & BCS_TRANSPARENT))
        ImageFill($this->mImg, $this->mWidth, $this->mHeight, $this->mBgcolor);
    }

    function DrawObject($xres)
    {
      // Abstract function
      return false;
    }

    function DrawBorder()
    {
      ImageRectangle($this->mImg, 0, 0, $this->mWidth-1, $this->mHeight-1, $this->mBrush);
    }

    function DrawChar($Font, $xPos, $yPos, $Char)
    {
      ImageString($this->mImg,$Font,$xPos,$yPos,$Char,$this->mBrush);
    }

    function DrawText($Font, $xPos, $yPos, $Char)
    {
      ImageString($this->mImg,$Font,$xPos,$yPos,$Char,$this->mBrush);
    }

    function DrawSingleBar($xPos, $yPos, $xSize, $ySize)
    {
      if($xPos>=0 && $xPos<=$this->mWidth  && ($xPos+$xSize)<=$this->mWidth && $yPos>=0 && $yPos<=$this->mHeight && ($yPos+$ySize)<=$this->mHeight)
      {
        for($i=0;$i<$xSize;$i++)
          ImageLine($this->mImg, $xPos+$i, $yPos, $xPos+$i, $yPos+$ySize, $this->mBrush);
        return true;
      }
      return false;
    }

    function GetError()
    {
      return $this->mError;
    }

    function GetFontHeight($font)
    {
      return ImageFontHeight($font);
    }

    function GetFontWidth($font)
    {
      return ImageFontWidth($font);
    }

    function SetFont($font)
    {
      $this->mFont = $font;
    }

    function GetStyle()
    {
      return $this->mStyle;
    }

    function SetStyle($Style)
    {
      $this->mStyle = $Style;
    }

    function GetImage()
    {
      if(($this->mStyle & BCS_BORDER))
        $this->DrawBorder();

      ob_start();

      if($this->mStyle & BCS_IMAGE_PNG)
        ImagePng($this->mImg);
      else if($this->mStyle & BCS_IMAGE_JPEG)
        ImageJpeg($this->mImg, "", 100);

      $imagedata = ob_get_contents();
      ob_end_clean();

      return $imagedata;
    }

    function FlushObject()
    {
      if(($this->mStyle & BCS_BORDER))
        $this->DrawBorder();

      if($this->mStyle & BCS_IMAGE_PNG)
      {
        Header("Content-Type: image/png");
        ImagePng($this->mImg);
      }
      else if($this->mStyle & BCS_IMAGE_JPEG)
      {
        Header("Content-Type: image/jpeg");
        ImageJpeg($this->mImg);
      }
    }

    function DestroyObject()
    {
      ImageDestroy($this->mImg);
    }
  }

?>