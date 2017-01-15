<?php 
/*

+-----------------------------------------------------------------+
|   Created by Chirag Mehta - http://chir.ag/tech/download/pdfb   |
|-----------------------------------------------------------------|
|                      For PDFB Library                           |
+-----------------------------------------------------------------+

*/

  /*
    Render for UPC-A
    The "UPC-A barcode" is by far the most common and well-known symbology, at least in the United States.
    An UPC-A barcode is the barcode you will find on virtually every consumer good on the shelves of your local supermarket,
    as well as books, magazines, and newspapers. It is commonly called simply a "UPC barcode" or "UPC Symbol."
    UPC-A encodes 11 digits of numeric (0 through 9) message data along with a trailing check digit,
    for a total of 12 digits of barcode data.
  */

  class UPCAObject extends BarcodeObject
  {
    var $mCharSetL, $mCharSetR, $mChars;

    function UPCAObject($Width, $Height, $Style, $Value)
    {
      $this->BarcodeObject($Width, $Height, $Style);
      $this->mValue   = $Value;
      $this->mChars   = "0123456789";
      $this->mCharSetL = array
       (
         /* 0 */ "0001101",
         /* 1 */ "0011001",
         /* 2 */ "0010011",
         /* 3 */ "0111101",
         /* 4 */ "0100011",
         /* 5 */ "0110001",
         /* 6 */ "0101111",
         /* 7 */ "0111011",
         /* 8 */ "0110111",
         /* 9 */ "0001011"
       );
      $this->mCharSetR = array
       (
         /* 0 */ "1110010",
         /* 1 */ "1100110",
         /* 2 */ "1101100",
         /* 3 */ "1000010",
         /* 4 */ "1011100",
         /* 5 */ "1001110",
         /* 6 */ "1010000",
         /* 7 */ "1000100",
         /* 8 */ "1001000",
         /* 9 */ "1110100"
       );
    }

    function GetCharIndex($char)
    {
      for($i=0;$i<10;$i++)
        if($this->mChars[$i] == $char)
          return $i;
      return -1;
    }

    function GetSize($xres)
    {
      $len = strlen($this->mValue);

      if($len == 0)
      {
        $this->mError = "Null value";
        return false;
      }

      for($i = 0; $i < $len; $i++)
      {
        if($this->GetCharIndex($this->mValue[$i]) == -1)
        {
          $this->mError = "UPCA not include the char '".$this->mValue[$i]."'";
          return false;
        }
      }

     // Start, Stop is 101
     // Middle Bar  is 01010
     $StartSize = $xres * 3;
     $StopSize  = $xres * 3;
     $MidSize   = $xres * 5;
     $CharSize  = $xres * 7; // Same for all chars

     return $CharSize * $len + $StartSize + $MidSize + $StopSize;
    }

    function DrawStart($DrawPos, $yPos, $ySize, $xres)
    {
      // Start code is '101'
      $this->DrawSingleBar($DrawPos, $yPos, $xres , $ySize);
      $DrawPos += $xres;
      $DrawPos += $xres;
      $this->DrawSingleBar($DrawPos, $yPos, $xres , $ySize);
      $DrawPos += $xres;
      return $DrawPos;
    }

    function DrawStop($DrawPos, $yPos, $ySize, $xres)
    {
      // Stop code is same as Start code
      return $this->DrawStart($DrawPos, $yPos, $ySize, $xres);
    }

    function DrawMiddle($DrawPos, $yPos, $ySize, $xres)
    {
      // Middle code is '01010'
      $DrawPos += $xres;
      $this->DrawSingleBar($DrawPos, $yPos, $xres , $ySize);
      $DrawPos += $xres;
      $DrawPos += $xres;
      $this->DrawSingleBar($DrawPos, $yPos, $xres , $ySize);
      $DrawPos += $xres;
      $DrawPos += $xres;
      return $DrawPos;
    }

    function DrawObject($xres)
    {
      $len = strlen($this->mValue);

      if(($size = $this->GetSize($xres)) == 0)
        return false;

      $cPos = 0;

      if($this->mStyle & BCS_ALIGN_CENTER)
        $sPos = (integer)(($this->mWidth - $size ) / 2);
      else if($this->mStyle & BCS_ALIGN_RIGHT)
        $sPos = $this->mWidth - $size;
      else
        $sPos = 0;

      // Total height of bar code -Bars only-
      if($this->mStyle & BCS_DRAW_TEXT)
        $ysize = $this->mHeight - BCD_DEFAULT_MAR_Y1 - BCD_DEFAULT_MAR_Y2 - $this->GetFontHeight($this->mFont);
      else
        $ysize = $this->mHeight - BCD_DEFAULT_MAR_Y1 - BCD_DEFAULT_MAR_Y2;

      $DrawPos = $this->DrawStart($sPos, BCD_DEFAULT_MAR_Y1 , $ysize, $xres);

      for($i = 0; $i < 6; $i++)
      {
        $c = $this->GetCharIndex($this->mValue[$i]);
        $cset  = $this->mCharSetL[$c];

        for($j = 0; $j < strlen($cset); $j++)
        {
          if(intval(substr($cset, $j, 1)) == 1)
            $this->DrawSingleBar($DrawPos, BCD_DEFAULT_MAR_Y1, $xres, $ysize);
          $DrawPos += $xres;
        }
      }

      $DrawPos = $this->DrawMiddle($DrawPos, BCD_DEFAULT_MAR_Y1, $ysize, $xres);

      for($i = 6; $i < $len; $i++)
      {
        $c = $this->GetCharIndex($this->mValue[$i]);
        $cset  = $this->mCharSetR[$c];

        for($j = 0; $j < strlen($cset); $j++)
        {
          if(intval(substr($cset, $j, 1)) == 1)
            $this->DrawSingleBar($DrawPos, BCD_DEFAULT_MAR_Y1, $xres, $ysize);
          $DrawPos += $xres;
        }
      }

      $DrawPos = $this->DrawStop($DrawPos, BCD_DEFAULT_MAR_Y1 , $ysize, $xres);

      // Draw text
      if($this->mStyle & BCS_DRAW_TEXT)
      {
        $mid = $sPos + $size/2;
        $len5 = (strlen($this->mCharSetL[$c])+1)*$xres*5;
        $ht = $this->GetFontHeight($this->mFont);

        ImageFilledRectangle($this->mImg,
                             $mid-$len5-$xres*2,
                             $ysize + BCD_DEFAULT_MAR_Y1 + BCD_DEFAULT_TEXT_OFFSET + BCD_DEFAULT_TEXT_FILL_OFFSET,
                             $mid-$xres*2,
                             $ysize + BCD_DEFAULT_MAR_Y1 + BCD_DEFAULT_TEXT_OFFSET + BCD_DEFAULT_TEXT_FILL_OFFSET + $ht,
                             $this->mBgcolor
                            );
        ImageFilledRectangle($this->mImg,
                             $mid+$xres*2,
                             $ysize + BCD_DEFAULT_MAR_Y1 + BCD_DEFAULT_TEXT_OFFSET + BCD_DEFAULT_TEXT_FILL_OFFSET,
                             $mid+$len5+$xres*2,
                             $ysize + BCD_DEFAULT_MAR_Y1 + BCD_DEFAULT_TEXT_OFFSET + BCD_DEFAULT_TEXT_FILL_OFFSET + $ht,
                             $this->mBgcolor
                            );

        $this->DrawChar(($this->mFont-2 > 1 ? $this->mFont-2 : 1), $sPos-$xres*3-$this->GetFontWidth($this->mFont > 1 ? $this->mFont - 1 : 1),
                        $ysize + BCD_DEFAULT_MAR_Y1 + BCD_DEFAULT_TEXT_OFFSET + BCD_DEFAULT_TEXT_FILL_OFFSET, $this->mValue[0]);

        $left = $mid-$len5;

        for ($i=1;$i<$len/2;$i++)
          $this->DrawChar($this->mFont, $left+($size/$len)*($i-1),
                          $ysize + BCD_DEFAULT_MAR_Y1 + BCD_DEFAULT_TEXT_OFFSET + BCD_DEFAULT_TEXT_FILL_OFFSET, $this->mValue[$i]);

        $left = $mid+$xres*4;

        for ($i=$len/2;$i<$len-1;$i++)
          $this->DrawChar($this->mFont, $left+($size/$len)*($i-$len/2),
                          $ysize + BCD_DEFAULT_MAR_Y1 + BCD_DEFAULT_TEXT_OFFSET + BCD_DEFAULT_TEXT_FILL_OFFSET, $this->mValue[$i]);

        $this->DrawChar(($this->mFont-2 > 1 ? $this->mFont-2 : 1), $sPos+$xres*6 + $size,
                        $ysize + BCD_DEFAULT_MAR_Y1 + BCD_DEFAULT_TEXT_OFFSET + BCD_DEFAULT_TEXT_FILL_OFFSET, $this->mValue[$len-1]);
      }

      return true;
    }
  }
?>