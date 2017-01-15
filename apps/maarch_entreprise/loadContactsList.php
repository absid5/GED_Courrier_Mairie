<?php

/*
*   Copyright 2008-2015 Maarch
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

require_once('core/class/class_core_tools.php');
$Core_Tools = new core_tools;
$Core_Tools->load_lang();

$return = '';

if (isset($_REQUEST['res_id'])) {
    $status = 0;
    $return .= '<td>';
        $return .= '<div align="center">';
            $return .= '<table width="100%">';

                $db = new Database();
                      
                $query = "SELECT c.is_corporate_person, c.is_private, c.contact_firstname, c.contact_lastname, c.firstname, c.lastname, c.society, c.society_short, c.contact_purpose_label, c.address_num, c.address_street, c.address_complement, c.address_town, c.address_postal_code, c.address_country, cres.mode ";
                        $query .= "FROM view_contacts c, contacts_res cres  ";
                        $query .= "WHERE cres.coll_id = 'letterbox_coll' AND cres.res_id = ? AND cast (c.contact_id as varchar) = cres.contact_id AND c.ca_id = cres.address_id ORDER BY cres.mode ASC";
                $arrayPDO = array($_REQUEST['res_id']);
                $stmt = $db->query($query, $arrayPDO);

                $fetch = '';
                while ($res = $stmt->fetchObject()) {

                    $return .= '<tr>';
                        $return .= '<td style="background: transparent; border: 0px dashed rgb(200, 200, 200);">';
                            
                                $return .= '<div style="text-align: left; background-color: rgb(230, 230, 230); padding: 3px; margin-left: 20px; margin-top: -6px;">';
                                    if($res->mode == 'third'){
                                        $return .= '<span style="font-size:10px;color:#16ADEB;">'._THIRD_DEST.'</span> - ';
                                    }else{
                                        $return .= '<span style="font-size:10px;color:#16ADEB;">'._CONTACT.'</span> - ';
                                    }

                                    if ($res->is_corporate_person == 'Y') {
                                        $return .= functions::xssafe($res->society) . ' ' ;
                                        if (!empty ($res->society_short)) {
                                            $return .= '('.functions::xssafe($res->society_short).') ';
                                        }
                                    } else {
                                        $return .= functions::xssafe($res->contact_lastname) 
                                            . ' ' . functions::xssafe($res->contact_firstname) . ' ';
                                        if (!empty ($res->society)) {
                                            $return .= '(' . functions::xssafe($res->society) . ') ';
                                        }                        
                                    }
                                    if ($res->is_private == 'Y') {
                                        $return .= '('._CONFIDENTIAL_ADDRESS.')';
                                    } else {
                                        $return .= "- " . functions::xssafe($res->contact_purpose_label)." : ";
                                        if (!empty($res->lastname) || !empty($res->firstname)) {
                                            $return .= functions::xssafe($res->lastname) 
                                                . ' ' . functions::xssafe($res->firstname);
                                        }
                                        if (!empty($res->address_num) || !empty($res->address_street) || !empty($res->address_town) || !empty($res->address_postal_code)) {
                                            $return .= ', ' . functions::xssafe($res->address_num) . ' ' 
                                                . functions::xssafe($res->address_street) . ' ' 
                                                . functions::xssafe($res->address_postal_code) . ' ' 
                                                . functions::xssafe(strtoupper($res->address_town));
                                        }         
                                    }
          
                                $return .= '</div>';
                            
                        $return .= '</td>';
                    $return .= '</tr>';
                }
                      
                $query = "SELECT u.firstname, u.lastname, u.user_id, cres.mode ";
                        $query .= "FROM users u, contacts_res cres  ";
                        $query .= "WHERE cres.coll_id = 'letterbox_coll' AND cres.res_id = ? AND cast (u.user_id as varchar) = cres.contact_id";
                $arrayPDO = array($_REQUEST['res_id']);
                $stmt = $db->query($query, $arrayPDO);

                $fetch = '';
                while ($res = $stmt->fetchObject()) {

                    $return .= '<tr>';
                        $return .= '<td style="background: transparent; border: 0px dashed rgb(200, 200, 200);">';
                            
                                $return .= '<div style="text-align: left; background-color: rgb(230, 230, 230); padding: 3px; margin-left: 20px; margin-top: -6px;">';
                                    if($res->mode == 'third'){
                                        $return .= '<span style="font-size:10px;color:#16ADEB;">'._THIRD_DEST.' (interne)</span> - ';
                                    }else{
                                        $return .= '<span style="font-size:10px;color:#16ADEB;">'._CONTACT.' (interne)</span> - ';
                                    } 
                                        $return .= functions::xssafe($res->firstname) . ' ' . functions::xssafe($res->lastname);
                                                
                                $return .= '</div>';
                                //$return .= '<br />';
                            
                        $return .= '</td>';
                    $return .= '</tr>';
                }
            $return .= '</table>';
            $return .= '<br />';
        $return .= '</div>';
    $return .= '</td>';
} else {
    $status = 1;
    $return .= '<td colspan="6" style="background-color: red;">';
        $return .= '<p style="padding: 10px; color: black;">';
            $return .= 'Erreur lors du chargement des notes';
        $return .= '</p>';
    $return .= '</td>';
}

echo "{status : " . $status . ", toShow : '" . addslashes($return) . "'}";
exit ();