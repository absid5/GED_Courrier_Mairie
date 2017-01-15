<?php
/*
*    Copyright 2008-2016 Maarch
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
* @brief   Contains all the functions to manage diffusion list
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup entities
*/
require_once 'modules/entities/entities_tables.php';
require_once 'core/core_tables.php';
/**
* @brief   Contains all the functions to manage diffusion list
*
* <ul>
* <li>Loads diffusion list from an model linked to an entity</li>
* <li>Updates the listinstance or listmodel table in the database</li>
* <li>Gets a diffusion list for a given resource identifier</li>
*</ul>
*
* @ingroup entities
*/
abstract class diffusion_list_Abstract extends functions
{
    #**************************************************************************
    # LISTMODELS
    # Administration and use of diffusion list templates
    #**************************************************************************
    public function select_listmodels(
        $objectType='entity_id'
    ) {
        $listmodels = array();
        $db = new Database();
        
        $query = 
            "SELECT distinct object_type, object_id, description, title"
            . " FROM " . ENT_LISTMODELS
            . " WHERE object_type = ?" 
            . " GROUP BY object_type, object_id, description, title  " 
            . " ORDER BY object_type ASC, object_id ASC";

        $stmt = $db->query($query,array($objectType));
        
        while ($listmodel = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if($listmodel['title'] == '')
                $listmodel['title'] = $listmodel['object_id'];
                
            $listmodels[] = $listmodel;
        }
        
        return $listmodels;
    }
        
    /**
    * Gets the diffusion list model for a given entity
    *
    * @param string $entityId Entity identifier
    * @param array $collId Collection identifier ('letterbox_coll' by default)
    * @return array $listmodel['dest] : Data of the dest_user
    *                                ['user_id'] : identifier of the dest_user
    *                                ['lastname'] : Lastname of the dest_user
    *                                ['firstname'] : firstname of the dest_user
    *                                ['entity_id'] : entity identifier of the dest_user
    *                                ['entity_label'] : entity label of the dest_user
    *                         ['copy'] : Data of the copies
    *                                ['users'][$i] : Users in copy data
    *                                       ['user_id'] : identifier of the user in copy
    *                                       ['lastname'] : Lastname of the user in copy
    *                                       ['firstname'] : firstname of the user in copy
    *                                       ['entity_id'] : entity identifier of the user in copy
    *                                       ['entity_label'] : entity label of the user in copy
    *                                ['entities'][$i] : Entities in copy data
    *                                       ['entity_id'] : entity identifier of the entity in copy
    *                                       ['entity_label'] : entity label of the entity in copy
    */
    public function get_listmodel(
        $objectType='entity_id', 
        $objectId
    ) {
        $objectId = $objectId;
        $objectType = $objectType;
        
        $db = new Database();
        $roles = $this->list_difflist_roles();
        $listmodel = array();
        
        if (empty($objectId) || empty($objectType)) 
            return $listmodel;
        
        # Load header
        $query = 
            "SELECT distinct object_type, object_id,  description, title"
            . " FROM " . ENT_LISTMODELS
            . " WHERE object_type = ?" 
                . "and object_id = ?"
            . " GROUP BY object_type, object_id,  description, title";
        $stmt = $db->query($query,array($objectType,$objectId));
        
        $listmodel = $stmt->fetch(PDO::FETCH_ASSOC);
        
        # Load list
        foreach($roles as $role_id => $role_label) {
            if($role_id == 'copy')
                $item_mode = 'cc';
            else 
                $item_mode = $role_id;
            
            # Users
            $stmt = $db->query(
                "SELECT l.item_id, l.item_mode, u.firstname, u.lastname, e.entity_id, e.entity_label, l.visible, l.process_comment "
                . " FROM " . ENT_LISTMODELS . " l "
                    . " JOIN " . USERS_TABLE . " u ON l.item_id = u.user_id " 
                    . " JOIN " . ENT_USERS_ENTITIES . " ue ON u.user_id = ue.user_id " 
                    . " JOIN " . ENT_ENTITIES . " e ON ue.entity_id = e.entity_id"
                . " WHERE "
                    . "ue.primary_entity = 'Y' "
                    . "and l.item_mode = ? "
                    . "and l.item_type = 'user_id' " 
                    . "and l.object_type = ? "
                    . "and l.object_id = ?"
                . "ORDER BY l.sequence",array($item_mode,$objectType,$objectId));
            while ($user = $stmt->fetchObject()) {
                if(!isset($listmodel[$role_id]))
                    $listmodel[$role_id] = array();
                if(!isset($listmodel[$role_id]['users']))
                    $listmodel[$role_id]['users'] = array();
                                
                array_push(
                    $listmodel[$role_id]['users'],
                    array(
                        'user_id' => functions::show_string($user->item_id),
                        'lastname' => functions::show_string($user->lastname),
                        'firstname' => functions::show_string($user->firstname),
                        'entity_id' => functions::show_string($user->entity_id),
                        'entity_label' => functions::show_string($user->entity_label),
                        'visible' => $user->visible,
                        'process_comment' => $user->process_comment
                    )
                );
            }
            
            # Entities
            $stmt = $db->query(
                "SELECT l.item_id, e.entity_label, l.item_mode, l.visible "
                . "FROM " . ENT_LISTMODELS . " l "
                    . "JOIN " . ENT_ENTITIES . " e ON l.item_id = e.entity_id "
                . "WHERE "
                    . " l.item_mode = ? "
                    . "and l.item_type = 'entity_id' "
                    . "and l.object_type = ? "
                    . "and l.object_id = ? "
                . "ORDER BY l.sequence ",array($item_mode,$objectType,$objectId)
            );

            while ($entity = $stmt->fetchObject()) {
                if(!isset($listmodel[$role_id]))
                    $listmodel[$role_id] = array();
                if(!isset($listmodel[$role_id]['entities']))
                    $listmodel[$role_id]['entities'] = array();
                    
                array_push(
                    $listmodel[$role_id]['entities'],
                    array(
                        'entity_id' => functions::show_string($entity->item_id),
                        'entity_label' => functions::show_string($entity->entity_label),
                        'visible' => $entity->visible
                    )
                );
            }
        }
        return $listmodel;
    }
    
    public function save_listmodel(
        $diffList, 
        $objectType = 'entity_id',
        $objectId,
        $title = false,
        $description = false
    ) {
        $db = new Database();
        $roles = $this->list_difflist_roles();
        
        require_once 'core/class/class_history.php';
        $hist = new history();
        
        //echo "bug"; print_r($description); exit;

        $objectType = trim($objectType);
        $objectId = trim($objectId);
        $title = trim($title);
        $description = trim($description);
        
        # Delete all and replace full list
        #**********************************************************************
        $stmt = $db->query(
            "delete from " . ENT_LISTMODELS 
            . " where "
                . "object_type = ? "
                . "and object_id = ? ",array($objectType,$objectId)
        );
        foreach($roles as $role_id => $role_label) {
            if($role_id == 'copy')
                $item_mode = 'cc';
            else 
                $item_mode = $role_id;
            
            # users
            #**********************************************************************
            for ($i=0, $l=count($diffList[$role_id]['users']);
                $i<$l; 
                $i++
            ) {
                $user = $diffList[$role_id]['users'][$i];
            //print_r($description); exit;
                $stmt = $db->query(
                    "insert into " . ENT_LISTMODELS
                        . " (coll_id, object_id, object_type, sequence, item_id, item_type, item_mode, listmodel_type, description, title, visible, process_comment ) "
                    . " values ("
                        . "'any', "
                        . "? , " 
                        . "?, ?, "
                        . "?, "
                        . "'user_id', "
                        . "?, "
                        . "null, "
                        . "?,"
                        . "?,"
                        . "?,"
                        . "?"
                    . ")",array($objectId,$objectType,$i,$user['user_id'],$item_mode,$description,$title,$user['visible'],$user['process_comment'])
                );
            }
            # Entities
            #**********************************************************************
            for ($i=0, $l=count($diffList[$role_id]['entities']); $i<$l ; $i++) {
                $entity = $diffList[$role_id]['entities'][$i];
                //print_r($description); exit;
                $stmt = $db->query(
                    "insert into " . ENT_LISTMODELS
                        . " (coll_id, object_id, object_type, sequence, item_id, item_type, item_mode, listmodel_type, description, title, visible ) "
                    . " values ("
                        . "'any', "
                        . "? , " 
                        . "?, ?, "
                        . "?, "
                        . "'entity_id', "
                        . "?, "
                        . "null, "
                        . "?, "
                        . "?,"
                        . "?"
                    . ")",array($objectId,$objectType,$i,$entity['entity_id'],$item_mode,$description,$title,$entity['visible'])
                );
            }
        }
    }
    
    public function delete_listmodel(
        $objectType = 'entity_id',
        $objectId
    ) {
        $db = new Database();
       
        $objectType = trim($objectType);
        $objectId = trim($objectId);

        # Delete all and replace full list
        #**********************************************************************
        $stmt = $db->query(
            "delete from " . ENT_LISTMODELS 
            . " where "
                . "object_type = ? "
                . "and object_id = ? ",array($objectType,$objectId));
    
    }
    
    #**************************************************************************
    # LISTINSTANCE
    # Management of diffusion lists for documents and folders
    #**************************************************************************
    # Legacy load_list_db (for custom calls)
    function load_list_db(
        $diffList, 
        $params, 
        $listType = 'DOC', 
        $objectType = 'entity_id'
    ) {
        $collId = trim($params['coll_id']);
        $resId = $params['res_id'];
        
        if (isset($params['user_id']) && ! empty($params['user_id'])) {
            require_once 'modules/entities/class/class_manage_entities.php';
            $ent = new entity();
            $creatorUser = trim($params['user_id']);
            $primary = $ent->get_primary_entity($creatorUser);
            $creatorEntity = trim($primary['ID']);
        } else {
            $creatorUser = '';
            $creatorEntity = '';
        }
        
        $objectType = trim($objectType);
        
        if (isset($params['fromQualif']) && $params['fromQualif'] == true) {
            $fromQualif = true;
        } else {
            $fromQualif = false;
        }

        $this->save_listinstance(
            $diffList,
            $objectType,
            $collId,
            $resId,
            $creatorUser,
            $creatorEntity,
            $fromQualif
        );
    }
    
    function save_listinstance(
        $diffList,
        $difflistType = 'entity_id',
        $collId,
        $resId,
        $creatorUser = "",
        $creatorEntity = "",
        $fromQualif = false
    ) {

        // Fix : superadmin can edit visa workflow in detail
        if ($creatorUser == "superadmin") {
            $creatorEntity = "";
        }

        $oldListInst = $this->get_listinstance($resId, false, $collId);
/*        echo 'old<br/>';
        var_dump($oldListInst);
        echo 'new<br/>';
        var_dump($diffList);*/

        require_once 'core/class/class_history.php';
        $hist = new history();

        $roles = $this->list_difflist_roles();
        $db = new Database();
        $stmt = $db->query("SELECT listinstance_id FROM listinstance WHERE res_id = ? and coll_id = ? and difflist_type = ?",array($resId,$collId,$difflistType));

        $diffUser = false;
        $diffCopyUsers = false;
        $diffCopyEntities = false;

        foreach($roles as $role_id => $role_label) {
            if ($stmt->rowCount() > 0 && (isset($oldListInst[$role_id]) || isset($diffList[$role_id]) )) {

                //compare old and new difflist
                for($iOld=0; $iOld<count($oldListInst['users']); $iOld++){
                    foreach($oldListInst['users'][$iOld] as $key => $value){
                        if ($value != $diffList['users'][$iOld][$key]) {
                            $diffUser = true;
                            break;
                        }
                    }
                }

                if (!$diffUser && isset($oldListInst[$role_id]['users'])) {
                    if (count($oldListInst[$role_id]['users']) != count($diffList[$role_id]['users']) ) {
                        $diffCopyUsers = true;
                    }
                    for($iOld=0; $iOld<count($oldListInst[$role_id]['users']); $iOld++){
                        foreach($oldListInst[$role_id]['users'][$iOld] as $key => $value){
                            if ($value != $diffList[$role_id]['users'][$iOld][$key]) {
                                $diffCopyUsers = true;
                                break;
                            }
                        }
                    }
                }

                if (!$diffUser && !$diffCopyEntities && isset($oldListInst[$role_id]['entities'])) {
                    if (count($oldListInst[$role_id]['entities']) != count($diffList[$role_id]['entities']) ) {
                        $diffCopyEntities = true;
                    }
                    for($iOld=0; $iOld<count($oldListInst[$role_id]['entities']); $iOld++){
                        foreach($oldListInst[$role_id]['entities'][$iOld] as $key => $value){
                            if ($value != $diffList[$role_id]['entities'][$iOld][$key]) {
                                $diffCopyEntities = true;
                                break;
                            }
                        }
                    }
                }

                // check add/remove all copy users
                if ( (isset($diffList[$role_id]['users']) && !isset($oldListInst[$role_id]['users'])) || (!isset($diffList[$role_id]['users']) && isset($oldListInst[$role_id]['users']))) {
                    $diffCopyUsers = true;
                }

                // check add/remove all copy entities
                if ( (isset($diffList[$role_id]['entities']) && !isset($oldListInst[$role_id]['entities'])) || (!isset($diffList[$role_id]['entities']) && isset($oldListInst[$role_id]['entities']))) {
                    $diffCopyUsers = true;
                }

            }
        }

        if ($diffUser || $diffCopyUsers || $diffCopyEntities) {
            $this->save_listinstance_history($collId, $resId, $difflistType);
        }

        # Delete previous listinstance
        $stmt = $db->query(
            "DELETE FROM " . ENT_LISTINSTANCE
            . " WHERE coll_id = ?"
                . " AND res_id = ? AND difflist_type = ?",array($collId,$resId,$difflistType)
        );
        
        $roles = $this->list_difflist_roles();
        foreach($roles as $role_id => $role_label) {
            // Special value 'copy', item_mode = cc
            if($role_id == 'copy')
                $item_mode = 'cc';
            else 
                $item_mode = $role_id;
            
            $cptUsers = count($diffList[$role_id]['users']);
            for ($i=0;$i<$cptUsers;$i++) {
                $userFound = false;
                $userId = trim($diffList[$role_id]['users'][$i]['user_id']);
                $processComment = trim($diffList[$role_id]['users'][$i]['process_comment']);
                $processDate = trim($diffList[$role_id]['users'][$i]['process_date']);
                $visible = $diffList[$role_id]['users'][$i]['visible'];
                $viewed = (integer)$diffList[$role_id]['users'][$i]['viewed'];
                $cptOldUsers = count($oldListInst[$role_id]['users']);
                for ($h=0;$h<$cptOldUsers;$h++) {
                    if ($userId == $oldListInst[$role_id]['users'][$h]['user_id']) {
                        $userFound = true;
                        break;
                    } else {
                        //else
                    }
                }
                //Modification du dest_user dans la table res_letterbox
                if($role_id == 'dest' && $collId == 'letterbox_coll')
					$stmt = $db->query("update ".RES_LETTERBOX." set dest_user = ? where res_id = ?",array($userId,$resId));
				
				if ($processDate != ''){
					$stmt = $db->query(
						"insert into " . ENT_LISTINSTANCE
							. " (coll_id, res_id, listinstance_type, sequence, item_id, item_type, item_mode, added_by_user, added_by_entity, visible, viewed, difflist_type, process_comment, process_date) "
						. "values ("
							. "?, ?, "
							. "'DOC', ?, "
							. "?, " 
							. "'user_id' , "
							. "?, "
							. "?, "
							. "?, "
							. "?, ?, "
							. "?, "
							. "?, "
							. "?"
						. " )",array($collId,$resId,$i,$userId,$item_mode,$creatorUser,$creatorEntity,$visible,$viewed,$difflistType,$processComment,$processDate)
					);
				}
				else {
					$stmt = $db->query(
						"insert into " . ENT_LISTINSTANCE
							. " (coll_id, res_id, listinstance_type, sequence, item_id, item_type, item_mode, added_by_user, added_by_entity, visible, viewed, difflist_type, process_comment) "
						. "values ("
							. "?, ?, "
							. "'DOC', ?, "
							. "?, " 
							. "'user_id' , "
							. "?, "
							. "?, "
							. "?, "
							. "?, ?, "
							. "?, "
							. "?"
						. " )",array($collId,$resId,$i,$userId,$item_mode,$creatorUser,$creatorEntity,$visible,$viewed,$difflistType,$processComment)
					);
				}
					
                
                if (!$userFound || $fromQualif) {
                    # History
                    $listinstance_id = $db->lastInsertId('listinstance_id_seq');      
                    $hist->add(
                        ENT_LISTINSTANCE,
                        $listinstance_id,
                        'ADD',
                        'diff'.$role_id.'user',
                        'Diffusion du document '.$resId.' à '. $userId . ' en tant que "' . $role_id .'"',
                        $_SESSION['config']['databasetype'],
                        'entities'
                    );
                }
            }
            
            # CUSTOM ENTITY ROLES
            $cptEntities=count($diffList[$role_id]['entities']);
            for ($j=0;$j<$cptEntities;$j++) {
                $entityFound = false;
                $entityId = trim($diffList[$role_id]['entities'][$j]['entity_id']);
                $visible = $diffList[$role_id]['entities'][$j]['visible'];
                $viewed = (integer)$diffList[$role_id]['entities'][$j]['viewed'];
                $cptOldEntities = count($oldListInst[$role_id]['entities']);
                for ($g=0;$g<$cptOldEntities;$g++) {
                    if ($entityId == $oldListInst[$role_id]['entities'][$g]['entity_id']) {
                        $entityFound = true;
                        break;
                    } else {
                        //else
                    }
                }
                
                $stmt = $db->query(
                    "INSERT INTO " . ENT_LISTINSTANCE
                        . " (coll_id, res_id, listinstance_type, sequence, item_id, item_type, item_mode, added_by_user, added_by_entity, visible, viewed, difflist_type) "
                    . "VALUES ("
                        . "?, ?, "
                        . "'DOC', ?, "
                        . "?, " 
                        . "'entity_id' , "
                        . "?, "
                        . "?, "
                        . "?, "
                        . "?,?, "
                        . "?"
                    . " )",array($collId,$resId,$j,$entityId,$item_mode,$creatorUser,$creatorEntity,$visible,$viewed,$difflistType)
                );
                
                if (!$entityFound || $fromQualif) {
                    # History
                    $listinstance_id = $db->lastInsertId('listinstance_id_seq');      
                    $hist->add(
                        ENT_LISTINSTANCE,
                        $listinstance_id,
                        'ADD',
                        'diff'.$role_id.'user',
                        'Diffusion of document '.$resId.' to '. $entityId . ' as ' . $role_id,
                        $_SESSION['config']['databasetype'],
                        'entities'
                    );
                }
            }
        }
    }

    function save_listinstance_history($coll_id, $res_id, $difflistType){

        $db = new Database();
        $db->query("INSERT INTO listinstance_history (coll_id, res_id, updated_by_user, updated_date) VALUES (?, ?, ?, current_timestamp)",array($coll_id,$res_id,$_SESSION['user']['UserId']));
        $listinstance_history_id = $db->lastInsertId('listinstance_history_id_seq'); 
        
        $stmt = $db->query("SELECT * FROM listinstance WHERE res_id = ? and coll_id = ? and difflist_type = ?",array($res_id,$coll_id,$difflistType));
        
        while ($resListinstance = $stmt->fetchObject()){
            $stmt2 = $db->query(
                "INSERT INTO listinstance_history_details 
                    (listinstance_history_id, coll_id, res_id, listinstance_type, sequence, item_id, item_type, item_mode, added_by_user, added_by_entity, visible, viewed, difflist_type, process_comment) "
                . "VALUES (?,?, ?, "
                    . "'DOC', ?, "
                    . "?, " 
                    . "? , "
                    . "?, "
                    . "?, "
                    . "?, "
                    . "?,?, "
                    . "?, "
                    . "?"
                . " )",array($listinstance_history_id,$resListinstance->coll_id,$res_id,$resListinstance->sequence,$resListinstance->item_id,$resListinstance->item_type,$resListinstance->item_mode,$resListinstance->added_by_user,$resListinstance->added_by_entity,$resListinstance->visible,$resListinstance->viewed,$resListinstance->difflist_type,$resListinstance->process_comment)
            );
        }
    }
    
    /**
    * Gets a diffusion list for a given resource identifier
    *
    * @param string $resId Resource identifier
    * @param string $collId Collection identifier, 'letterbox_coll' by default
    * @return array $listinstance['dest] : Data of the dest_user
    *                                ['user_id'] : identifier of the dest_user
    *                                ['lastname'] : Lastname of the dest_user
    *                                ['firstname'] : firstname of the dest_user
    *                                ['entity_id'] : entity identifier of the dest_user
    *                                ['entity_label'] : entity label of the dest_user
    *                         ['copy'] : Data of the copies
    *                                ['users'][$i] : Users in copy data
    *                                       ['user_id'] : identifier of the user in copy
    *                                       ['lastname'] : Lastname of the user in copy
    *                                       ['firstname'] : firstname of the user in copy
    *                                       ['entity_id'] : entity identifier of the user in copy
    *                                       ['entity_label'] : entity label of the user in copy
    *                                ['entities'][$i] : Entities in copy data
    *                                       ['entity_id'] : entity identifier of the entity in copy
    *                                       ['entity_label'] : entity label of the entity in copy
    **/
    public function get_listinstance(
        $resId, 
        $modeCc = false, 
        $collId = 'letterbox_coll',
        $typeList = 'entity_id'
    ) {
        $db = new Database();
        $roles = $this->list_difflist_roles();
        
        $listinstance = array();       
        
        if (empty($resId) || empty($collId)) {
            return $listinstance;
        }
        
        # Load header
        $query = 
            "SELECT distinct coll_id, res_id, difflist_type"
            . " FROM " . ENT_LISTINSTANCE
            . " WHERE coll_id = ? "
                . "and res_id = ? GROUP BY coll_id, res_id, difflist_type";
                $stmt = $db->query($query,array($collId,$resId));

        $listinstance = $stmt->fetch(PDO::FETCH_ASSOC);
        if($listinstance['difflist_type'] == "")
            $listinstance['difflist_type'] = 'entity_id';
        
        # DEST USER
        /*if (! $modeCc) {
            $stmt = $db->query(
                "select l.item_id, u.firstname, u.lastname, e.entity_id, "
                . "e.entity_label, l.visible, l.viewed from " . ENT_LISTINSTANCE . " l, "
                . USERS_TABLE . " u, " . ENT_ENTITIES . " e, "
                . ENT_USERS_ENTITIES . " ue "
                . " where l.coll_id = '" . $collId . "' "
                ." and l.item_mode = 'dest' "
                . "and l.item_type = 'user_id' and l.sequence = 0 "
                . "and l.item_id = u.user_id and u.user_id = ue.user_id "
                . "and e.entity_id = ue.entity_id and ue.primary_entity = 'Y' "
                . "and l.res_id = " . $resId
            );

            $res = $stmt->fetchObject();
           
            $listinstance['dest'] = array(
                'user_id' => $this->show_string($res->item_id),
                'lastname' => $this->show_string($res->lastname),
                'firstname' => $this->show_string($res->firstname),
                'entity_id' => $this->show_string($res->entity_id),
                'entity_label' => $this->show_string($res->entity_label),
                'visible' => $this->show_string($res->visible),
                'viewed' => $this->show_string($res->viewed)
            );
        }*/
        
        # OTHER ROLES USERS
        #**********************************************************************
        $stmt = $db->query(
            "select l.item_id, u.firstname, u.lastname, e.entity_id, "
            . "e.entity_label, l.visible, l.viewed, l.item_mode, l.difflist_type, l.process_date, l.process_comment  from "
            . ENT_LISTINSTANCE . " l, " . USERS_TABLE
            . " u, " . ENT_ENTITIES . " e, " . ENT_USERS_ENTITIES
            . " ue where l.coll_id = '" . $collId . "' "
            . " and l.item_type = 'user_id' and l.item_id = u.user_id "
            . " and l.item_id = ue.user_id and ue.user_id=u.user_id "
            . " and e.entity_id = ue.entity_id and l.difflist_type = ? and l.res_id = ? and ue.primary_entity = 'Y' order by l.sequence ",array($typeList,$resId)
        );
        //$this->show();
        while ($res = $stmt->fetchObject()) {
            if($res->item_mode == 'cc') 
                $role_id = 'copy';
            else 
                $role_id = $res->item_mode;
                
            if(!isset($listinstance[$role_id]['users']))
                $listinstance[$role_id]['users'] = array();
            array_push(
                $listinstance[$role_id]['users'],
                array(
                    'user_id' => functions::show_string($res->item_id),
                    'lastname' => functions::show_string($res->lastname),
                    'firstname' => functions::show_string($res->firstname),
                    'entity_id' => functions::show_string($res->entity_id),
                    'entity_label' => functions::show_string($res->entity_label),
                    'visible' => functions::show_string($res->visible),
                    'viewed' => functions::show_string($res->viewed),
                    'difflist_type' => functions::show_string($res->difflist_type),
                    'process_date' => functions::show_string($res->process_date),
                    'process_comment' => functions::show_string($res->process_comment)
                )
            );
        }

        # OTHER ROLES ENTITIES
        #**********************************************************************
        $stmt = $db->query(
            "select l.item_id,  e.entity_label, l.visible, l.viewed, l.item_mode, l.difflist_type, l.process_date, l.process_comment  from " . ENT_LISTINSTANCE
            . " l, " . ENT_ENTITIES . " e where l.coll_id =  ? "
            . "and l.item_type = 'entity_id' and l.item_id = e.entity_id "
            . "and l.res_id = ? order by l.sequence ",array($collId,$resId)
        );

        while ($res = $stmt->fetchObject()) {
            if($res->item_mode == 'cc') 
                $role_id = 'copy';
            else 
                $role_id = $res->item_mode;
                
            if(!isset($listinstance[$role_id]['entities']))
                $listinstance[$role_id]['entities'] = array();
            array_push(
                $listinstance[$role_id]['entities'],
                array(
                    'entity_id' => functions::show_string($res->item_id),
                    'entity_label' => functions::show_string($res->entity_label),
                    'visible' => functions::show_string($res->visible),
                    'viewed' => functions::show_string($res->viewed),
                    'difflist_type' => functions::show_string($res->difflist_type),
					 'process_date' => functions::show_string($res->process_date),
                    'process_comment' => functions::show_string($res->process_comment)
                )
            );
        }
        
        return $listinstance;
    }

    public function set_main_dest(
        $dest, 
        $collId, 
        $resId,
        $listinstanceType = 'DOC', 
        $itemType = 'user_id', 
        $viewed
    ) {
        $db = new Database();
        $stmt = $db->query(
            "select item_id from " . ENT_LISTINSTANCE . " where res_id = ? and coll_id = ? and sequence = 0 and item_type = ? and item_mode = 'dest'",array($resId,$collId,$itemType)
        );
        if ($stmt->rowCount() == 1) {
            $stmt = $db->query(
                "update " . ENT_LISTINSTANCE . " set item_id = ?, viewed = ? where res_id = ? and coll_id = ? and sequence = 0 and item_type = ? and item_mode = 'dest'",array($dest,$viewed,$resId,$collId,$itemType)
            );
        } else {
            $stmt = $db->query(
                "insert into " . ENT_LISTINSTANCE . " (coll_id, res_id, "
                . "item_id, item_type, item_mode, sequence, "
                . "added_by_user, added_by_entity, viewed) values (?, ?, ?, ?, 'dest', 0, ?,?, ?)",array($collId,$resId,$dest,$itemType,$_SESSION['user']['UserId'],$_SESSION['primaryentity']['id'],$viewed)
            );
        }
    }
    
    #**************************************************************************
    # DIFFLIST_ROLES
    # Administration and management of roles
    #************************************************************************** 
    #  Get list of available roles for list models and diffusion lists definition
    public function list_difflist_roles() 
    {
        $roles = array();
        
        require_once 'core' . DIRECTORY_SEPARATOR . 'core_tables.php';
        
        /*$query = 
            "SELECT distinct ug.group_id, ug.group_desc "
            . " FROM " . USERGROUPS_TABLE . " ug "
            . " LEFT JOIN " . USERGROUP_CONTENT_TABLE . " ugc "
                . " ON ug.group_id = ugc.group_id "
            . " WHERE ug.enabled = 'Y'";
        
        if($user_id)
            $query .= " AND ugc.user_id = ?";
        
        $query .= " GROUP BY ug.group_id, ug.group_desc";
        $query .= " ORDER BY ug.group_desc ASC";
        
        $db = new Database();
        
        if ($user_id) {
            $stmt = $db->query($query,array($user_id));
        } else {
            $stmt = $db->query($query);
        }*/

        //load roles difflist
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'modules'
            . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
            . 'roles.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'modules'
            . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
            . 'roles.xml';
        } else {
            $path = 'modules' . DIRECTORY_SEPARATOR . 'entities'
                  . DIRECTORY_SEPARATOR . 'xml'
                  . DIRECTORY_SEPARATOR . 'roles.xml';
        }


        $xmlroles = simplexml_load_file($path);

        foreach ($xmlroles->ROLES as $key => $value) {
            foreach ($value as $key2 => $role) {
                $id = (string)$role->id;
                $label = (string)$role->label;
                if (!empty($label) && defined($label) && constant($label) <> NULL) {
                $label = constant($label);
            }
                $roles[$id] = $label;
            }
        }

		
       /* while($usergroup = $stmt->fetchObject()) {
            $group_id = $usergroup->group_id;
            $roles[$group_id] = $usergroup->group_desc;
        }*/
        
        return $roles;
    }
    
    #**************************************************************************
    # DIFFLIST_TYPES
    # Administration and management of types of list
    #**************************************************************************
    #  Get list of available list types / labels
    public function list_difflist_types()
    {
        $db = new Database();
        $stmt = $db->query('select * from ' . ENT_DIFFLIST_TYPES);
        //$this->show();
        $types = array();
                        
        while ($type = $stmt->fetchObject()) { 
            $types[(string) $type->difflist_type_id] = $type->difflist_type_label;
        }
        return $types;
    }
    
    # Get given listmodel type object
    public function get_difflist_type(
        $difflist_type_id
    ) {       
        $db = new Database();
        $stmt = $db->query(
            'SELECT * FROM ' . ENT_DIFFLIST_TYPES
            . " WHERE difflist_type_id = ?",array($difflist_type_id) 
        );
        
        $difflist_type = $stmt->fetchObject();
       
        return $difflist_type;
    }
    
    public function get_difflist_type_roles(
        $difflist_type
    ) {
        $roles = array();
        
        $db = new Database();
        
        $role_ids = explode(' ', $difflist_type->difflist_type_roles);


        for($i=0, $l=count($role_ids);
            $i<$l;
            $i++
        ) {
            $role_id = $role_ids[$i];
            $role_label = diffusion_list::get_difflist_type_roles_label($role_id);
            $roles[$role_id] = $role_label;
        }
        return $roles;
        
    }

    public function get_difflist_type_roles_label(
        $role_id
    ) {
        $roles = diffusion_list::list_difflist_roles();

        foreach ($roles as $id => $label) {
            if($role_id == $id)
                $role_label = $label;
        }

        return $role_label;
        
    }
    
    public function insert_difflist_type(
        $difflist_type_id,
        $difflist_type_label,
        $difflist_type_roles,
        $allow_entities
    ) {
        $db = new Database();
        $stmt = $db->query(
            "insert into " . ENT_DIFFLIST_TYPES
                . " (difflist_type_id, difflist_type_label, difflist_type_roles, allow_entities)"
                . " values (" 
                    . "?,"
                    . "?,"
                    . "?,"
                    . "?"
                    . ")",array($difflist_type_id,$difflist_type_label,$difflist_type_roles,$allow_entities)
        );
    }
    
    public function update_difflist_type(
        $difflist_type_id,
        $difflist_type_label,
        $difflist_type_roles,
        $allow_entities
    ) {
        $db = new Database();
        $stmt = $db->query(
            "update " . ENT_DIFFLIST_TYPES 
                . " set "
                    . " difflist_type_label = ?,"
                    . " difflist_type_roles = ?,"
                    . " allow_entities = ?"
                . " where difflist_type_id = ?",array($difflist_type_label,$difflist_type_roles,$allow_entities,$difflist_type_id)
        );
    }
    
    public function delete_difflist_type(
        $difflist_type_id
    ) {
        $db = new Database();
        $stmt = $db->query(
            'DELETE FROM ' . ENT_DIFFLIST_TYPES
            . " WHERE difflist_type_id = ?",array($difflist_type_id) 
        );
    }
        
    #**************************************************************************
    # GROUPBASKET_DIFFLIST_TYPES
    # Types of lists available for a given group in basket
    #**************************************************************************   
    #  Get list of available list model types for a given groupbasket
    public function list_groupbasket_difflist_types(
        $group_id,
        $basket_id,
        $action_id
    ) {
        $types = array();
        $db = new Database();
        $stmt = $db->query(
            "select difflist_type_id from " . ENT_GROUPBASKET_DIFFLIST_TYPES
            . " where group_id = ?" 
                . " and basket_id = ?"
                . " and action_id = ?",array($group_id,$basket_id,$action_id)
        );
        
        $types = array();
                
        while ($type = $stmt->fetchObject()) { 
            $types[] = (string) $type->difflist_type_id;
        }
        return $types;
    }

    
}
