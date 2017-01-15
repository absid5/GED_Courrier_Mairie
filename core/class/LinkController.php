<?php
/*
*   Copyright 2008-2015 Maarch
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
* @brief  Contains the LinkController Class
*
*
* @file
* @author Arnaud Veber
* @date $date$
* @version $Revision$
* @ingroup core
*/

//Loads the require class
try {
    require_once('core/class/class_history.php');
} catch (Exception $e) {
    functions::xecho($e->getMessage()) . ' // ';
}

class LinkController
{
    private $previousId = ' ';
    private $level = 0;

    public function formatMap($arrayToFormat, $sens)
    {
        $this->level++;
        $core = new core_tools();
        $core->test_user();
        $return = '';

        foreach ($arrayToFormat as $key => $value) {
            $infos = $this->getDocInfos($key, $_SESSION['current_basket']['coll_id']);
            $infos['subject'] = preg_replace("/\r\n|\r|\n/",'<br/>',$infos['subject']);
            $return .= '<div id="ged_'.$key.$sens.'" class="linkDiv">';
                $return .= '<table style="width:100%;text-align:left;font-size:15px;cursor:pointer;" title="accéder à la fiche détaillée">';
                    $return .= '<tr>';
                        $status = $this->getStatus($infos['status']);
                        $img_class = substr($status['img_filename'], 0, 2);
                        $return .= '<td style="width:14%;text-align:center;" title="'.$status['label_status'].'" onclick="window.location.href=\'index.php?page=details&dir=indexing_searching&id='.$key.'\'">';
                            $return .= '<i class="'.$img_class.' '.$status['img_filename'].' '.$img_class.'-2x" ></i> ';
                        $return .= '</td>';
                        $return .= '<td colspan="2" onclick="window.location.href=\'index.php?page=details&dir=indexing_searching&id='.$key.'\'">';
                            $return .=  '<b>'.$infos['subject'].'</b>';
                        $return .= '</td>';
                    $return .= '</tr>';
                    $return .= '<tr>';
                        $return .= '<td style="width:14%;text-align:center;" onclick="window.location.href=\'index.php?page=details&dir=indexing_searching&id='.$key.'\'">';
                            $return .=  $infos['res_id'];
                        $return .= '</td>';
                        $return .= '<td style="font-size:12px;width:16%;" onclick="window.location.href=\'index.php?page=details&dir=indexing_searching&id='.$key.'\'">';
                        if ($_SESSION['current_basket']['coll_id'] == 'letterbox_coll') {
                            $return .= $_SESSION['coll_categories']['letterbox_coll'][$infos['category_id']];
                        } elseif ($_SESSION['current_basket']['coll_id'] == 'business_coll') {
                            $return .= $_SESSION['coll_categories']['business_coll'][$infos['category_id']];
                        }
                         $return .= '</td>';
                         $return .= '<td style="font-size:12px;width:14%" title="'._DOC_DATE.'" onclick="window.location.href=\'index.php?page=details&dir=indexing_searching&id='.$key.'\'">';
                                $return .= '<i class="fa fa-calendar-o fa-2x" style="font-size:10px;"></i> ';
                                $date = explode('-', substr($infos['doc_date'], 0, 10));
                                $return .= $date[2].' '.$date[1].' '.$date[0];
                        $return .= '</td>';
                        $return .= '<td style="font-size:12px;width:14%" title="'._DEST_USER.'" onclick="window.location.href=\'index.php?page=details&dir=indexing_searching&id='.$key.'\'">';
                                $return .= '<i class="fa fa-user fa-2x" style="font-size:10px;"></i> ';
                                $return .= $infos['dest_user'];
                        $return .= '</td>';
                        $return .= '<td style="font-size:12px;width:24%" title="'._DESTINATION.'" onclick="window.location.href=\'index.php?page=details&dir=indexing_searching&id='.$key.'\'">';
                                $return .= '<i class="fa fa-sitemap fa-2x" style="font-size:10px;"></i> ';
                                $return .= $infos['entity_label'];
                        $return .= '</td>';
                        if ($core->is_module_loaded('visa')) {
                            require_once "modules" . DIRECTORY_SEPARATOR . "visa" . DIRECTORY_SEPARATOR
                            . "class" . DIRECTORY_SEPARATOR
                            . "class_modules_tools.php";
                            $return .= '<td style="font-size:12px;width:16%" title="'._VISA_USERS.'">';

                            $visa = new visa();

                            $users_visa_list = $visa->getUsersCurrentVis($infos['res_id']);
                            if(!empty($users_visa_list)){
                                $users_visa_list = implode(', ', $users_visa_list);
                                $return .= '<i class="fa fa-certificate fa-2x" style="font-size:10px;"></i> ';
                                $return .= $users_visa_list;
                            }
                            $return .= '</td>';
                        }
                   
                        if ($core->test_service('add_links', 'apps', false) && $this->level <= 1) {
                            if ($sens == 'asc') {
                                $delParent = $key;
                                $delChild = $_SESSION['doc_id'];
                            } else {
                                $delParent = $_SESSION['doc_id'];
                                $delChild = $key;
                            }
                        $return .= '<td align="right">';
                
                        $return .= '<div align="center" class="iconDoc"><a href="index.php?display=true&dir=indexing_searching&page=view_resource_controler&id='.$infos['res_id'].'" target="_blank" title="'._VIEW_DOC.'"><i class="fa fa-download fa-2x" title="' . _VIEW_DOC . '"></i>';
                            
                      
                        $return .= '</td>';
                            $return .= '<td align="right">';
                                $return .= '<span onclick="';
                                    $return .= 'if(confirm(\'Voulez-vous supprimer la liaison ?\')){';
                                  $return .= 'addLinks(';
                                    $return .= '\''.$_SESSION['config']['businessappurl'].'index.php?page=add_links&display=true\', ';
                                    $return .= '\''.$delChild.'\' ,';
                                    $return .= '\''.$delParent.'\' ,';
                                    $return .= '\'del\',';

                                if ($_SESSION['current_basket']['coll_id'] == 'letterbox_coll') {
                                    $return .= '\'res_view_letterbox\'';
                                } elseif ($_SESSION['current_basket']['coll_id'] == 'business_coll') {
                                    $return .= '\'res_view_business\'';
                                } else {
                                    $return .= '\'\'';
                                }
                                $return .= ');}';
                                $return .= '">';
                                    $return .= '<i class="fa fa-unlink fa-2x" title="supprimer la liaison" style="cursor:pointer;"></i>';
                                $return .= '</span>';
                            $return .= '</td>';
                        }
                    $return .= '</tr>';
                $return .= '</table>';
                if (is_array($value)) {
                    $return .= $this->formatMap($value, $sens);
                }
            $return .= '</div>';
        }

        $this->level--;
        return $return;
    }

    public function getMap($parentId, $collection, $sens)
    {
        if (!empty($parentId) && !empty($collection)) {
            if ($sens == 'asc') {
                $links = $this->getLinksAsc($parentId, $collection);
            } else {
                $links = $this->getLinksDesc($parentId, $collection);
            }
            $linksArray = explode('||', $links);

            for ($i=0; $i<count($linksArray); $i++) {
                if ($linksArray[$i] != '' ) {
                    if (!preg_match("/".' ' . $linksArray[$i] . ' '."/", $this->previousId)) {
                        $this->previousId .= $parentId . ' ';
                        $return[$linksArray[$i]] = $this->getMap($linksArray[$i], $collection, $sens);
                    }
                } else {
                    $return = 'last';
                }
            }
        }
        return $return;
    }

    private function getLinksDesc($parentId, $collection)
    {
        $db = new Database;
        $query = "SELECT res_child FROM res_linked WHERE coll_id=? AND res_parent=?";
        $stmt = $db->query($query, array($collection, $parentId));
        if ($stmt) {
            $i = 0;
            $links = '';
            while ($row = $stmt->fetchObject()) {
                $links .= $row->res_child.'||';
                $i++;
            }
            if ($i > 0) {
                $return = substr($links, 0, -2);
            }
        } else {
            $return = 'Problème lors de la requête : '.$query;
        }

        return $return;
    }

    private function getLinksAsc($parentId, $collection)
    {
        $db = new Database;
        $query = "SELECT res_parent FROM res_linked WHERE coll_id=? AND res_child=?";
        $stmt = $db->query($query, array($collection, $parentId));
        if ($stmt) {
            $i = 0;
            $links = '';
            while ($row = $stmt->fetchObject()) {
                $links .= $row->res_parent.'||';
                $i++;
            }
            if ($i > 0) {
                $return = substr($links, 0, -2);
            }
        } else {
            $return = 'Problème lors de la requête : '.$query;
        }

        return $return;
    }

    private function getDocInfos($id, $collection)
    {
        if ($collection == 'letterbox_coll') {
            $view = 'res_view_letterbox';
        } elseif ($collection == 'business_coll') {
            $view = 'res_view_business';
        } else {
            $view = '';
        }
        if ($view <> '') {
            $db = new Database;
            $query = "SELECT * FROM ".$view." WHERE res_id = ?";
            $stmt = $db->query($query, array($id));
            if ($stmt) {
                $i = 0;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $return = $row;
                    $i++;
                }
            }
        } else {
            $return = false;
        }
        return $return;
    }

    public function getStatus($status)
    {
        //$return->label_status = 'BAD';
        //$return->img_filename = '';
        $db = new Database();
        $query = "SELECT label_status, img_filename FROM status WHERE id = ?";
        $stmt = $db->query($query,array($status));
        if ($stmt) {
            $i = 0;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $return = $row;
                $i++;
            }
        }

        return $return;
    }

    public function nbDirectLink($id, $collection, $sens)
    {
        $i = 0;
        $db = new Database();
        if ($sens == 'desc' || $sens == 'all') {
            $query = "SELECT res_child FROM res_linked WHERE coll_id=? AND res_parent=?";
            $stmt = $db->query($query,array($collection,$id));
            if ($stmt) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $i++;
                }
            }
        }
        if ($sens == 'asc' || $sens == 'all') {
            $query = "SELECT res_parent FROM res_linked WHERE coll_id=? AND res_child=?";
            $stmt = $db->query($query,array($collection,$id));
            if ($stmt) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $i++;
                }
            }
        }

        return $i;
    }
}
