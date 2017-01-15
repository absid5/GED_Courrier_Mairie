<?php

require_once('core/class/class_core_tools.php');
$Core_Tools = new core_tools;
$Core_Tools->load_lang();

$return = '';

if (isset($_REQUEST['contact_id'])) {

    $status = 0;
    $return .= '<td colspan="8" style="background-color: #FFF;">';
        $return .= '<div align="center" width="100%">';
            $return .= '<table width="100%" style="background-color: rgba(100, 200, 213, 0.2);">';
                $return .= '<tr style="font-weight: bold;">';
                    $return .= '<th style="font-weight: bold; color: black;">&nbsp;</th>';
                    $return .= '<th style="font-weight: bold; color: black;">';
                        $return .= _STATUS;
                    $return .= '</th>';
                    $return .= '<th style="font-weight: bold; color: black;">';
                        $return .= _DATE;
                    $return .= '</th>';
                    $return .= '<th style="font-weight: bold; color: black;">';
                        $return .= _SUBJECT;
                    $return .= '</th>';
                    $return .= '<th style="font-weight: bold; color: black;">';
                        $return .= _DEPARTMENT_DEST;
                    $return .= '</th>';
                    $return .= '<th style="font-weight: bold; color: black;">';
                        $return .= _DOCTYPE;
                    $return .= '</th>';
                    $return .= '<th style="font-weight: bold; color: black;">';
                        $return .= _ID;
                    $return .= '</th>';
                    $return .= '<th style="font-weight: bold; color: black;">&nbsp;</th>';
                $return .= '</tr>';


                $db = new Database();

                $query = "SELECT * FROM res_view_letterbox WHERE (exp_contact_id = ? or dest_contact_id = ?) and status <> 'DEL'";

                $stmt = $db->query($query, array($_REQUEST['contact_id'], $_REQUEST['contact_id']));
                $cptDocs = 0;
                while ($return_db = $stmt->fetchObject()) {
                    if ($cptDocs < 10) {
                    $return .= '<tr style="border: 1px solid;" style="background-color: #FFF;">';
                        $return .= '<td>';
                            $return .= '&nbsp;&nbsp;';
                            $return .= '<a ';
                            $return .= 'href="';
                               $return .= 'index.php?'
                                . 'dir=indexing_searching'
                                . '&page=details'
                                . '&collid=letterbox_coll&id=' 
                                . $return_db->res_id;
                            $return .= '" ';
                            $return .= 'target="_blank" ';
                            $return .= '>';
                                $return .= '<i class="fa fa-info-circle fa-2x" title="'._DETAILS.'"></i>';
                            $return .= '</a>';
                        $return .= '</td>';
                        $return .= '<td>';
                            $return .= '&nbsp;&nbsp;';

                            $query = "SELECT label_status FROM status " 
                                . "WHERE id = ?";
                            $stmt2 = $db->query($query, array($return_db->status));
                            while ($status_db = $stmt2->fetchObject()) {
                                $return .= $status_db->label_status;
                            }
                        $return .= '</td>';
                        $return .= '<td>';
                            $return .= '&nbsp;&nbsp;';
                            $return .= functions::format_date_db($return_db->creation_date);
                        $return .= '</td>';
                        $return .= '<td>';
                            $return .= '&nbsp;&nbsp;';
                            $return .= functions::cut_string($return_db->subject, 40);
                        $return .= '</td>';
                        $return .= '<td>';
                            $return .= '&nbsp;&nbsp;';
                            $return .= functions::xssafe($return_db->entity_label);
                        $return .= '</td>';
                        $return .= '<td>';
                            $return .= '&nbsp;&nbsp;';
                            $return .= functions::xssafe($return_db->type_label);
                        $return .= '</td>';
                        $return .= '<td>';
                            $return .= '&nbsp;&nbsp;';
                            $return .= functions::xssafe($return_db->res_id);
                        $return .= '</td>';
                        $return .= '<td>';
                            $return .= '&nbsp;&nbsp;';
                            $return .= '<a ';
                            $return .= 'href="';
                              $return .= 'index.php?display=true'
                                . '&dir=indexing_searching'
                                . '&page=view_resource_controler'
                                . '&collid=letterbox_coll&id=' 
                                . $return_db->res_id;
                            $return .= '" ';
                            $return .= 'target="_blank" ';
                            $return .= '>';
                                $return .= '<i class="fa fa-download fa-2x" title="'._VIEW_DOC.'"></i>';
                            $return .= '</a>';
                        $return .= '</td>';
                    $return .= '</tr>';
                    } else {
                        $return .= '<tr><td>...<tr></td>';
                        break;
                    }
                    $cptDocs++;
                }

            $return .= '</table>';
            $return .= '<br />';
        $return .= '</div>';
    $return .= '</td>';
} else {
    $status = 1;
    $return .= '<td colspan="8" style="background-color: red;">';
        $return .= '<p style="padding: 10px; color: black;">';
            $return .= 'Error loading documents';
        $return .= '</p>';
    $return .= '</td>';
}


//usleep(900000);

echo "{status : " . $status . ", toShow : '" . addslashes($return) . "'}";
exit ();
