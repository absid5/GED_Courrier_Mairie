<?php

/*
*    Copyright 2008,20015 Maarch
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
* File : entity_del.php
*
* Delete an entity
*
* @package  Maarch Framework 3.0
* @version 1
* @since 03/2009
* @license GPL
* @author  Cedric Ndoumba  <dev@maarch.org>
* @author  Claire Figueras  <dev@maarch.org>
* @author  Laurent Giovannoni  <dev@maarch.org>
*/

$admin = new core_tools();
$admin->test_admin('manage_entities', 'entities');
$_SESSION['m_admin']= array();
/****************Management of the location bar  ************/
$init = false;
if(isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true")
{
    $init = true;
}
$level = "";
if(isset($_REQUEST['level']) && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1))
{
    $level = $_REQUEST['level'];
}
$page_path = '';
$page_label = _ENTITY_DELETION;
$page_id = "entity_del";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
require_once('modules'.DIRECTORY_SEPARATOR.'entities'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_manage_entities.php');
require("modules/entities/entities_tables.php");
$admin = new core_tools();
$ent = new entity();
$db = new Database();
$admin->load_lang();
$admin->test_admin('manage_entities', 'entities');
$entities = $ent->getShortEntityTree(array());
$order = $_REQUEST['order'];
$order_field = $_REQUEST['order_field'];
$start = $_REQUEST['start'];
$what = $_REQUEST['what'];
$label = '';
if(isset($_REQUEST['id']))
{
    $s_id = addslashes($db->wash($_REQUEST['id'], "alphanum", _THE_ENTITY));
    $label = $ent->getentitylabel($s_id);
}
else
{
    $s_id = "";
}
if(isset($_REQUEST['valid']))
{
    $documents = true;
    if(!empty($_REQUEST['doc_entity_id']))
    {
        for($i=0;$i<count($_SESSION['collections']);$i++)
        {
            // Skip this test if view doesn't have a column named res_id or destination
            if(!$db->test_column($_SESSION['collections'][$i]['view'], 'res_id')) continue;
            if(!$db->test_column($_SESSION['collections'][$i]['view'], 'destination')) continue;
            $db = new Database();
            if(isset($_SESSION['collections'][$i]['table']) && !empty($_SESSION['collections'][$i]['table']))
            {
                if ($_SESSION['collections'][$i]['view'] == 'rm_documents_view') {
                    $stmt = $db->query("UPDATE rm_organizations SET entity_id = ? WHERE entity_id = ?", array($_REQUEST['doc_entity_id'],$s_id));
                } else {
                    $stmt = $db->query("UPDATE ".$_SESSION['collections'][$i]['table']." SET destination = ? WHERE destination = ? AND status <> 'DEL'", array($_REQUEST['doc_entity_id'],$s_id));
                }
                //$db->show();
            }
        }
        $stmt = $db->query('SELECT user_id FROM ' . ENT_USERS_ENTITIES . ' WHERE entity_id = ? AND primary_entity = ? AND user_id IN (SELECT user_id FROM users_entities WHERE entity_id = ?)',
            [$s_id, 'Y', $_REQUEST['doc_entity_id']]);

        while($doubleUser = $stmt->fetchObject())
        {
            $db->query('DELETE FROM ' . ENT_USERS_ENTITIES . ' WHERE user_id = ? AND entity_id = ?', [$doubleUser->user_id, $_REQUEST['doc_entity_id']]);
        }

        $stmt = $db->query("UPDATE ".ENT_USERS_ENTITIES." SET entity_id = ?"
            ." WHERE entity_id = ? AND user_id NOT IN (SELECT DISTINCT(user_id) FROM " . ENT_USERS_ENTITIES 
            . " WHERE entity_id = ?)", array($_REQUEST['doc_entity_id'],$s_id,$_REQUEST['doc_entity_id']));
        
        $stmt = $db->query("DELETE FROM " . ENT_USERS_ENTITIES . " WHERE entity_id = ?",array($s_id));
        $stmt = $db->query("SELECT entity_id FROM ".ENT_ENTITIES." WHERE parent_entity_id = ?",array($s_id));
        $db = new Database();
        while($lineEnt=$stmt->fetchObject())
        {
            //si la nouvelle entité (l'entité remplaçante) est une entité fille de l'entité à supprimer alors l'entité remplaçante récupère l'entité mère de l'entité à supprimer
            if($lineEnt->entity_id == $_REQUEST['doc_entity_id'])
            {
                $stmt2 = $db->query("SELECT parent_entity_id FROM ".ENT_ENTITIES." WHERE entity_id = ?", array($s_id));
                $lineParentEnt = $stmt2->fetchObject();
                $stmt2 = $db->query("UPDATE ".ENT_ENTITIES." SET parent_entity_id = ? WHERE entity_id = ?",array($lineParentEnt->parent_entity_id,$lineEnt->entity_id));
                //$db2->show();
            }
            else
            {
                $stmt2 = $db->query("UPDATE ".ENT_ENTITIES." SET parent_entity_id = ? WHERE entity_id = ?",array($_REQUEST['doc_entity_id'],$lineEnt->entity_id));
                //$db2->show();
            }
        }
        //exit;
    }
    elseif(empty($_REQUEST['doc_entity_id']))
    {
        $_SESSION['error'] .= _ENTITY_MANDATORY_FOR_REDIRECTION."<br>";
        $documents = false;
    }
    if($documents)
    {
        if($_REQUEST['doc_entity_id'] <> "")
        {
            $entity_id_up = $_REQUEST['doc_entity_id'];
        }
        if($admin->is_module_loaded('basket'))
        {
            //groupbasket_redirect
            $stmt = $db->query("UPDATE ".$_SESSION['tablename']['ent_groupbasket_redirect']." SET entity_id = ? WHERE entity_id = ?",array($entity_id_up,$s_id));
             //listinstance
            $stmt = $db->query("UPDATE ".$_SESSION['tablename']['ent_listinstance']." SET item_id = ? WHERE item_id = ? and item_type = 'entity_id'",array($entity_id_up,$s_id));
            //$db->show();
            //listmodels
            $stmt = $db->query("DELETE FROM ".$_SESSION['tablename']['ent_listmodels']." WHERE object_id = ?",array($s_id));
            //$db->show();
        }
        //$db->show();
        if($admin->is_module_loaded('templates'))
        {
            //templates_association
            $stmt = $db->query("UPDATE ".$_SESSION['tablename']['temp_templates_association']." SET value_field = ? WHERE value_field = ? and what = 'destination'",array($entity_id_up,$s_id));
            //$db->show();
        }
        
        //exit;
        $ent->adminentity($s_id, 'del');
    }
    else
    {
        $ent->formDeleteEntity($s_id, $label, $entities, $admin);
    }
}
else
{
    $ent->formDeleteEntity($s_id, $label, $entities, $admin);
}
?>
