<?php
/*
*    Copyright 2008,2012 Maarch
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
*/

/**
* Module : Tags
* 
* This module is used to store ressources with any keywords
* V: 1.0
*
* @file
* @author Loic Vinet
* @date $date$
* @version $Revision$
* 
* 
* Ajout d'un tag sur la ressource
*/


try{
    require_once 'core/class/ActionControler.php';
    require_once 'core/class/ObjectControlerAbstract.php';
    require_once 'core/class/ObjectControlerIF.php';
    require_once 'core/class/class_request.php' ;
   	require_once 'modules/tags/class/TagControler.php' ;
} catch (Exception $e) {
    functions::xecho($e->getMessage());
}




$db = new Database();
$core = new core_tools();
$core->load_lang();
$tag = new tag_controler;





$p_input_value = $_REQUEST['p_input_value'];
$p_input_value  = str_replace('\r', '', $p_input_value);
$p_input_value  = str_replace('\n', '', $p_input_value);
// $p_input_value  = str_replace('\'', ' ', $p_input_value);
$p_input_value  = str_replace('"', ' ', $p_input_value);
$p_input_value  = str_replace('\\', ' ', $p_input_value);


//On découpe la chaine composée de virgules
$tabrr = array( CHR(13) => ",", CHR(10) => "," ); 
$p_input_value = strtr($p_input_value,$tabrr); 

$new_tags = explode(',',$p_input_value);
	




foreach($new_tags as $new_tag){

	$stmt = $db->query(
    "SELECT tag_label as label FROM tags"
    . " WHERE tag_label = ?"
    ,array($new_tag));

	 $line = $stmt->fetchObject();
	 $label = $line->label; 

	if($label != null){
 
		if (trim($new_tag) <> '')
		{
			$result = $tag->add_this_tags_in_session($new_tag);
			//$result = $tag->add_this_tags($p_res_id, $p_coll_id, $new_tag);
			//if (!$result){
			//	echo "{status : 1, error_txt : '".addslashes(UNABLETOADDTAGS)."'}";
			//	exit();	
			//}
		}
		echo "{status : 0, value : 'ok'}";
		exit();
	}else{
		if($_SESSION['config']['lang'] == 'fr'){
		echo "{status : 1, value : 'fr'}";
		exit();	
		}elseif($_SESSION['config']['lang'] == 'en'){
		echo "{status : 1, value : 'en'}";
		exit();
		}
	}

}



?>
