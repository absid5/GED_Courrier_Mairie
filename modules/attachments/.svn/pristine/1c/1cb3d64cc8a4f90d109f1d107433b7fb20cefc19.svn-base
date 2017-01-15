<?php

/*
*   Copyright 2015 Maarch
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
* @brief   Chrono for attachments
*
* Open a modal box to displays the indexing form, make the form checks and loads
* the result in database. Used by the core (manage_action.php page).
*
* @file
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/

    require_once 'core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'class_request.php';
    require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
        . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'class_chrono.php';
    require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");

    $core = new core_tools();
    $core->test_user();
    $db = new Database();
    $sec = new security();

        $view = $sec->retrieve_view_from_coll_id($_SESSION['collection_id_choice']);
        if(empty($view))
        {
            $view = $sec->retrieve_table_from_coll($_SESSION['collection_id_choice']);
        }

        $stmt = $db->query("SELECT category_id FROM ".$view." WHERE res_id = ? ", array($_SESSION['doc_id']));
        $resMaster = $stmt->fetchObject();

        $category_id = $resMaster->category_id;

        $nb_attachment = 0;

        // Check if reponse project was already attached to this outgoing document.
        if ($category_id == "outgoing") {
                $stmt = $db->query("SELECT res_id FROM res_view_attachments WHERE res_id_master = ? and (attachment_type = 'response_project' or attachment_type = 'outgoing_mail') and status <> 'DEL' and status <> 'OBS'"
                                    ,array($_SESSION['doc_id']));
                $nb_attachment = $stmt->rowCount();
        }

        if ($category_id == "incoming" || ($category_id == "outgoing" && $nb_attachment > 0)) {
            if (isset($_SESSION['save_chrono_number']) && $_SESSION['save_chrono_number'] <> "") {
                echo "{status: 1, chronoNB: '".$_SESSION['save_chrono_number']."'}";
            } else {
                $chronoX = new chrono();
                $myVars = array(
                    'category_id' => 'outgoing',
                    'entity_id' => $_SESSION['user']['primaryentity']['id']
                );

                $myChrono = $chronoX->generate_chrono('outgoing', $myVars);
                $_SESSION['save_chrono_number'] = $myChrono;
                echo "{status: 1, chronoNB: '".functions::xssafe($myChrono)."'}";
            }
        } else if ($category_id == "outgoing" && $nb_attachment == 0) {
            $stmt = $db->query("SELECT alt_identifier FROM ".$view." WHERE res_id = ?", array($_SESSION['doc_id']));
            $chronoMaster = $stmt->fetchObject();
            echo "{status: 1, chronoNB: '".functions::xssafe($chronoMaster->alt_identifier)."'}";
        }

