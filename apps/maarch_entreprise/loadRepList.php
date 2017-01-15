<?php

/*
*
*    Copyright 2008,2015 Maarch
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
*   @author <dev@maarch.org>
*/

require_once('core/class/class_core_tools.php');
require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR
            ."class".DIRECTORY_SEPARATOR."class_users.php";
$Core_Tools = new core_tools;
$Core_Tools->load_lang();

$users = new class_users();

$return = '';

if (isset($_REQUEST['res_id_master'])) {

    $status = 0;
    $return .= '<td colspan="7" style="background-color: #FFF;">';
        $return .= '<div align="center">';
            $return .= '<table width="100%" style="background-color: rgba(100, 200, 213, 0.2);">';
                $return .= '<tr style="font-weight: bold;">';
                    $return .= '<th style="font-weight: bold; color: black;" width="150px">';
                        $return .= _CHRONO_NUMBER;
                    $return .= '</th>';
                    $return .= '<th style="font-weight: bold; color: black;" width="130px">';
                        $return .= _STATUS;
                    $return .= '</th>';
                    $return .= '<th style="font-weight: bold; color: black;" width="180px">';
                        $return .= _ATTACHMENT_TYPE;
                    $return .= '</th>';
                    $return .= '<th style="font-weight: bold; color: black;" width="120px">';
                        $return .= _CREATION_DATE;
                    $return .= '</th>';
                    $return .= '<th style="font-weight: bold; color: black;" width="120px">';
                        $return .= _BACK_DATE;
                    $return .= '</th>';
                    $return .= '<th style="font-weight: bold; color: black;">';
                        $return .= _SUBJECT;
                    $return .= '</th>';
                    $return .= '<th style="font-weight: bold; color: black;" width="180px">';
                        $return .= _AUTHOR;
                    $return .= '</th>';
                    $return .= '<th style="font-weight: bold; color: black;" width="40px">';
                        $return .= _CONSULT;
                    $return .= '</th>';
                $return .= '</tr>';


                $db = new Database();

                $query = "SELECT * FROM res_view_attachments 
                            WHERE res_id_master = ? 
                            AND status NOT IN ('DEL', 'OBS') AND attachment_type NOT IN ('converted_pdf', 'print_folder') AND coll_id = ?  AND (status <> 'TMP' or (typist = ? and status = 'TMP')) 
                            ORDER BY creation_date desc";
                $arrayPDO = array($_REQUEST['res_id_master'], $_SESSION['collection_id_choice'], $_SESSION['user']['UserId']);
                $stmt = $db->query($query, $arrayPDO);

                while ($return_db = $stmt->fetchObject()) {
                    if (!empty($_REQUEST['option']) && $_REQUEST['option'] == 'FT') {
                        if ($return_db->format != 'pdf') {
                            $stmtFullText = $db->query('SELECT res_id FROM res_view_attachments WHERE filename = ? and attachment_type = ? and path = ? ORDER BY relation desc',
                                [str_replace('.' . $return_db->format, '.pdf', $return_db->filename), 'converted_pdf', $return_db->path]);
                            $lineFullText = $stmtFullText->fetchObject();
                            if ($lineFullText && $lineFullText->res_id != 0)
                                $resIdConverted = $lineFullText->res_id;
                        }
                         $stmt2 = $db->query(
                        "SELECT count(*) as total FROM res_view_attachments WHERE res_id = ? and status not in ('DEL','OBS','TMP') and lower(translate(title,'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ','aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')) like lower(?)", array($return_db->res_id,$_SESSION['searching']['where_request_parameters'][':subject'])
                        );
                        $res_attach = $stmt2->fetchObject();

                        if ((!empty($_SESSION['fullTextAttachments']['attachments']) && in_array($return_db->res_id, $_SESSION['fullTextAttachments']['attachments'])) ||
                            (!empty($_SESSION['fullTextAttachments']['versionAttachments']) && in_array($return_db->res_id_version, $_SESSION['fullTextAttachments']['versionAttachments']))
                        ) {
                            $return .= '<tr style="border: 1px solid;color: #009dc5;font-weight: bold" style="background-color: #FFF;">';
                        } else if (!empty($resIdConverted) && !empty($_SESSION['fullTextAttachments']['attachments']) && in_array($resIdConverted, $_SESSION['fullTextAttachments']['attachments'])) {
                            $return .= '<tr style="border: 1px solid;color: #009dc5;font-weight: bold" style="background-color: #FFF;">';
                        } elseif($res_attach->total > 0){
                            $return .= '<tr style="border: 1px solid;color: #009dc5;font-weight: bold" style="background-color: #FFF;">';
                        } else {
                            $return .= '<tr style="border: 1px solid;" style="background-color: #FFF;">';

                        }
                    } else if (!empty($_REQUEST['option']) && $_REQUEST['option'] == 'baskets'
                                && $return_db->status == 'EXP_RTURN' && $return_db->validation_date && $return_db->validation_date < date('Y-m-d')) {
                        $return .= '<tr style="border: 1px solid;color: red;" style="background-color: #FFF;">';
                    } else {
                        $return .= '<tr style="border: 1px solid;" style="background-color: #FFF;">';
                    }
                        $return .= '<td>';
                            $return .= '&nbsp;&nbsp;';
                            $return .= functions::xssafe($return_db->identifier);
                        $return .= '</td>';
                        $return .= '<td>';
                            $return .= '&nbsp;&nbsp;';
                            $query = "SELECT label_status FROM status WHERE id =?";
                            $arrayPDO = array($return_db->status);
                            $stmt2 = $db->query($query, $arrayPDO);
                            while ($status_db = $stmt2->fetchObject()) {
                                $return .= functions::xssafe($status_db->label_status);
                            }
                        $return .= '</td>';
                        $return .= '<td>';
                            $return .= '&nbsp;&nbsp;';
                            $attachment_types_valeur = $return_db->attachment_type;
                            $return .= functions::xssafe($_SESSION['attachment_types'][$attachment_types_valeur]);
                        $return .= '</td>';
                        $return .= '<td>';
                            $return .= '&nbsp;&nbsp;';
                            sscanf(substr($return_db->creation_date, 0, 10), "%4s-%2s-%2s", $date_Y, $date_m, $date_d);
                            switch ($date_m)
                            {
                                case '01': $date_m_txt = _JANUARY; break;
                                case '02': $date_m_txt = _FEBRUARY; break;
                                case '03': $date_m_txt = _MARCH; break;
                                case '04': $date_m_txt = _APRIL; break;
                                case '05': $date_m_txt = _MAY; break;
                                case '06': $date_m_txt = _JUNE; break;
                                case '07': $date_m_txt = _JULY; break;
                                case '08': $date_m_txt = _AUGUST; break;
                                case '09': $date_m_txt = _SEPTEMBER; break;
                                case '10': $date_m_txt = _OCTOBER; break;
                                case '11': $date_m_txt = _NOVEMBER; break;
                                case '12': $date_m_txt = _DECEMBER; break;
                                default: $date_m_txt = $date_m;
                            }
                            $return .= functions::xssafe($date_d.' '.$date_m_txt.' '.$date_Y);
                        $return .= '</td>';
                        $return .= '<td>';
                            $return .= '&nbsp;&nbsp;';
                            if ($return_db->validation_date) {
                                sscanf(substr($return_db->validation_date, 0, 10), "%4s-%2s-%2s", $date_Y, $date_m, $date_d);
                                switch ($date_m)
                                {
                                    case '01': $date_m_txt = _JANUARY; break;
                                    case '02': $date_m_txt = _FEBRUARY; break;
                                    case '03': $date_m_txt = _MARCH; break;
                                    case '04': $date_m_txt = _APRIL; break;
                                    case '05': $date_m_txt = _MAY; break;
                                    case '06': $date_m_txt = _JUNE; break;
                                    case '07': $date_m_txt = _JULY; break;
                                    case '08': $date_m_txt = _AUGUST; break;
                                    case '09': $date_m_txt = _SEPTEMBER; break;
                                    case '10': $date_m_txt = _OCTOBER; break;
                                    case '11': $date_m_txt = _NOVEMBER; break;
                                    case '12': $date_m_txt = _DECEMBER; break;
                                    default: $date_m_txt = $date_m;
                                }
                                $return .= functions::xssafe($date_d.' '.$date_m_txt.' '.$date_Y);
                            } else {
                                $return .= '-';
                            }
                        $return .= '</td>';
                        $return .= '<td>';
                            $return .= '&nbsp;&nbsp;';
                            $return .= functions::xssafe($return_db->title);
                        $return .= '</td>';
                        $return .= '<td>';
                            $return .= '&nbsp;&nbsp;';
                            $current_user = $users->get_user($return_db->typist);
                            $return .= functions::xssafe($current_user['firstname']) 
                                . ' ' . functions::xssafe($current_user['lastname']);
                        $return .= '</td>';
                        $return .= '<td>';
                            $return .= '&nbsp;&nbsp;';
                            $return .= '<a ';
                            $return .= 'href="';
                            if ($return_db->res_id <> 0) {
                                $id = $return_db->res_id;
                            } else {
                                $id = $return_db->res_id_version;
                            }
                              $return .= 'index.php?display=true&module=attachments&page=view_attachment&id='.$id.'&res_id_master='
                                . functions::xssafe($_REQUEST['res_id_master']);
                            $return .= '" ';
                            $return .= 'target="_blank" ';
                            $return .= '>';
                                $return .= '<i class="fa fa-download fa-2x" title="'._VIEW_DOC.'"></i>';
                            $return .= '</a>';
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
            $return .= 'Error loading attachments';
        $return .= '</p>';
    $return .= '</td>';
}


//usleep(900000);

echo "{status : " . $status . ", toShow : '" . addslashes($return) . "'}";
exit ();
