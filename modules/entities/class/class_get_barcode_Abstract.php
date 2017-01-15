<?php 

/*
*   Copyright 2008-2016 Maarch
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
* File : class_get_barcode.php
*
* Frame able to list boxes in physical archives modules
*
* @package  Maarch  3.0
* @version 2.1
* @since 10/2005
* @license GPL
* @author  Loic Vinet <dev@maarch.org>

*/

abstract class barcocdeFPDF_Abstract extends FPDI {

  	function generateBarCode($type, $code, $hh=60, $hr, $hw, $showtype) 
  	{
    	$img_file_name = $_SESSION['user']['UserId'] . time() . rand() . ".png";
		
		$objCode = new pi_barcode();

		$objCode->setCode($code);

		$objCode->setType($type);

		$objCode->setSize($hh, $hw);
		  
		$objCode->setText($code);
		  
		$objCode->hideCodeType();
		  
		$objCode->setFiletype('PNG');               

		$objCode->writeBarcodeFile($_SESSION['config']['tmppath'] . $img_file_name);
		
		return  $img_file_name;
  	}
}
