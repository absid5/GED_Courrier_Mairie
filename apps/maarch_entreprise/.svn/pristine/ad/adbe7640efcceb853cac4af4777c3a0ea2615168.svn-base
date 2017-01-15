<?php

/*
*    Copyright 2014-2015 Maarch
*
*  This file is part of Maarch Framework.
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
*    along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*
* @brief   Displays contacts list in search mode
*
* @file
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
*/

/*
* Fichier appelé par la function ajax multiLink. Elle permet de mettre en session les données lorsqu'on clique sur le checkbox. 
*/

//$_SESSION['stockCheckbox'] = null;
if(isset($_REQUEST['uncheckAll'])){
  unset($_SESSION['stockCheckbox']);
}else if (isset($_REQUEST['courrier_purpose'])) {
	  $key=false;
    # Append something onto the $name variable so that you can see that it passed through your PHP script.
   $courrier = functions::xssafe($_REQUEST['courrier_purpose']);

   if(!empty($_SESSION['stockCheckbox'])){
		$key = in_array($courrier, $_SESSION['stockCheckbox']);
		if($key ==true){
			unset($_SESSION['stockCheckbox'][array_search($courrier, $_SESSION['stockCheckbox'])]);
			$_SESSION['stockCheckbox']=array_values($_SESSION['stockCheckbox']);
			echo json_encode($_SESSION['stockCheckbox']);
			exit();
		}
   }

   if(empty($_SESSION['stockCheckbox'])){
   	$tableau[] = $courrier;
   	$_SESSION['stockCheckbox'] = $tableau;
   	echo json_encode($_SESSION['stockCheckbox']);
   }elseif($key==false and !empty($_SESSION['stockCheckbox'])){
   	array_push($_SESSION['stockCheckbox'],$courrier);
   	echo json_encode($_SESSION['stockCheckbox']);
   }
   
    # I'm sending back a json structure in case there are multiple pieces of information you'd like
    # to pass back.
    
  }

exit;
?>