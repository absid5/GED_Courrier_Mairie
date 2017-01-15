<?php
/*
*    Copyright 2008,2009 Maarch
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
* File : aj_add_this_tag.php
*
* Script called by an ajax object to delete jonction on a ressource and a tag
*
* @package  maarch
* @version 1
* @since 10/2005
* @license GPL v3
* @author Loic Vinet  <dev@maarch.org>
*/

try{
    require_once 'core/class/ActionControler.php';
    require_once 'core/class/ObjectControlerAbstract.php';
    require_once 'core/class/ObjectControlerIF.php';
    require_once 'core/class/class_request.php' ;
   	require_once 'modules/tags/class/TagControler.php' ;
	require_once 'modules/tags/tags_tables_definition.php';
} catch (Exception $e) {
    functions::xecho($e->getMessage());
}


$db = new Database();

$core = new core_tools();
$core->load_lang();
$tag = new tag_controler;


$a_search_tag = $_REQUEST['a_search_tag'];
$a_search_tag  = str_replace('\r', '', $a_search_tag);
$a_search_tag  = str_replace('\n', '', $a_search_tag);
// $a_search_tag  = str_replace('\'', ' ', $a_search_tag);
$a_search_tag  = str_replace('"', ' ', $a_search_tag);
$a_search_tag  = str_replace('\\', ' ', $a_search_tag);


//On découpe la chaine composée de virgules
$tabrr = array( CHR(13) => ",", CHR(10) => "," ); 
$a_search_tag = strtr($a_search_tag,$tabrr); 

$a_search_tag_arr = explode(',',$a_search_tag);
	
$a_search_tag_label = $a_search_tag_arr[0];
$a_search_tag_coll = $a_search_tag_arr[1];

//----------------------------------

$a_new_tag = $_REQUEST['a_new_tag'];
$a_new_tag   = str_replace('\r', '', $a_new_tag );
$a_new_tag   = str_replace('\n', '', $a_new_tag );
// $a_new_tag   = str_replace('\'', ' ', $a_new_tag );
$a_new_tag   = str_replace('"', ' ', $a_new_tag );
$a_new_tag   = str_replace('\\', ' ', $a_new_tag );
//On découpe la chaine composée de virgules
$tabrr = array( CHR(13) => ",", CHR(10) => "," ); 
$a_new_tag = strtr($a_new_tag,$tabrr); 

$a_new_tag_arr = explode(',',$a_new_tag);
	
$a_new_tag_label = $a_new_tag_arr[0];
$a_new_tag_coll = trim($a_new_tag_arr[1]);


$stmt = $db->query(
	"SELECT DISTINCT res_id from "._TAG_TABLE_NAME
	. " WHERE tag_label = ? and coll_id = ? "
	,array($a_search_tag_label,$a_search_tag_coll));

while ($result = $stmt->fetchObject()) {
	$tag -> delete_this_tag($result->res_id,$a_search_tag_coll,functions::protect_string_db($a_search_tag_label));
	$tag -> add_this_tag($result->res_id,$a_new_tag_coll,$a_new_tag_label);
}

//$query_verify = "select count()";
//$queryupdate = "update "._TAG_TABLE_NAME." set tag_label = '".$a_new_tag_label."', coll_id = '".$a_new_tag_coll."' where tag_label = '".$a_search_tag_label."' and coll_id = '".$a_search_tag_coll."' ";

//$db->query($query);

echo "{status : 0, value : 'ok'}";
exit();

?>
