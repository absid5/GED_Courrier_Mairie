<?php
/*
*    Copyright 2008,2016 Maarch
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
* Module : Thesaurus
* 
* This module is used to store ressources with any thesaurus
* V: 1.0
*
* @file
* @author Alex Orluc
* @date $date$
* @version $Revision$
*/

try{
    require_once 'core/class/ObjectControlerAbstract.php';
    require_once 'core/class/ObjectControlerIF.php';
    require_once 'core/class/class_request.php' ;
    require_once 'modules/thesaurus/class/class_modules_tools.php' ;
} catch (Exception $e) {
    functions::xecho($e->getMessage());
}

//var_dump($_REQUEST['thesaurus_chosen']);exit();
$thesaurus = new thesaurus;

$thesaurus_resid_return = array();
$json_txt .= " 'thesaurus_chosen' : [";
for ($gethes_i = 0; $gethes_i <count($_REQUEST['thesaurus_chosen']); $gethes_i++) {
    $return_thesaurus_res_id = array();
    $return_thesaurus_res_id  = $thesaurus->getresarray_byId($_REQUEST['thesaurus_chosen'][$gethes_i]);
    $json_txt .= "'".addslashes($_REQUEST['thesaurus_chosen'][$gethes_i])."',";
    if ($return_thesaurus_res_id) {
        foreach ($return_thesaurus_res_id as $elem) {
            array_push($thesaurus_resid_return, $elem);
        }
    } else {
        array_push($thesaurus_resid_return, 0);
    }
    
}

foreach ($thesaurus_resid_return as $finalthesaurusearch) {
    
    $thesaurus_resid_in .= "'".$finalthesaurusearch."',";
}
$thesaurus_resid_in = substr($thesaurus_resid_in, 0, -1);
$where_request .= " res_id in (:thesaurus) and ";
$arrayPDO = array_merge($arrayPDO, array(":thesaurus" => $thesaurus_resid_return));

$json_txt = substr($json_txt, 0, -1);
$json_txt .= '],';
