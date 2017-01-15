<?php

if (isset($_POST['branch_id'])) {
    require_once('modules/entities/class/class_manage_entities.php');
    require("modules/entities/entities_tables.php");
    $core_tools = new core_tools();
    $core_tools->load_lang();

    $children = array();
    $db = new Database();
    
    $stmt = $db->query("select u.user_id, u.lastname, u.firstname, u.enabled, ue.entity_id as entity_id from " . ENT_USERS_ENTITIES 
        . " ue," . ENT_ENTITIES . " e, " . $_SESSION['tablename']['users'] 
        . " u where e.parent_entity_id = ?" 
        . " and e.parent_entity_id = ue.entity_id and u.user_id = ue.user_id and u.status <> 'DEL' order by u.lastname, u.firstname",array($_POST['branch_id']));
    if ($stmt->rowCount() > 0) {
        while ($res = $stmt->fetchObject()) {
            $canhavechildren = 'canhavechildren:false, ';
            if (!is_integer(array_search("'" . $res->entity_id . "'", $_SESSION['EntitiesIdExclusion'])) || count($_SESSION['EntitiesIdExclusion']) == 0) {
                //Condition qui vérifie si l'utilisateur est actif ou pas. Si pas actif, il est affiché en rouge 
                if($res->enabled == 'N'){
                    $labelValue = '<span class="entity_tree_element_ok">' . functions::show_string('<a style="color:red;" href="index.php?page=users_management_controler&mode=up&admin=users&id='
                            . $res->user_id . '" target="_top">' . $res->lastname . ' ' . $res->firstname . '</a>', true) . '</span>';
                }else{
                    $labelValue = '<span class="entity_tree_element_ok">' . functions::show_string('<a href="index.php?page=users_management_controler&mode=up&admin=users&id='
                            . $res->user_id . '" target="_top">' . $res->lastname . ' ' . $res->firstname . '</a>', true) . '</span>';  
                }
            } else {
                $labelValue = '<small><i>' . functions::show_string($res->lastname . ' ' . $res->firstname, true) . '</i></small>';
            }
            array_push(
                $children, 
                array(
                    'id' => $res->user_id . '_' . $_POST['branch_id'], 
                    'tree' => $_SESSION['entities_chosen_tree'], 
                    'key_value' => $res->user_id, 
                    'label_value' => $res->user_id . ' - ' . $labelValue, 
                    'canhavechildren' => '', 'is_entity' => 'false'
                )
            );
        }
    } else {
        $stmt = $db->query("select u.user_id, u.lastname, u.firstname, ue.entity_id as entity_id  from " 
            . ENT_USERS_ENTITIES . " ue, " . $_SESSION['tablename']['users'] 
            . " u where ue.entity_id = ?" 
            . "  and u.user_id = ue.user_id and u.status <> 'DEL' order by u.lastname, u.firstname",array($_POST['branch_id']));

        if ($stmt->rowCount() > 0) {
            while ($res = $stmt->fetchObject()) {
                $canhavechildren = 'canhavechildren:false, ';
                if (!is_integer(array_search("'" . $res->entity_id . "'", $_SESSION['EntitiesIdExclusion'])) || count($_SESSION['EntitiesIdExclusion']) == 0) {
                    $labelValue = '<span class="entity_tree_element_ok">' . functions::show_string('<a href="index.php?page=users_management_controler&mode=up&admin=users&id='
                                . $res->user_id . '" target="_top">' . $res->lastname . ' ' . $res->firstname . '</a>', true) . '</span>';
                } else {
                    $labelValue = '<small><i>' . functions::show_string($res->lastname . ' ' . $res->firstname, true) . '</i></small>';
                }
                array_push(
                    $children, 
                    array(
                        'id' => $res->user_id . '_' . $_POST['branch_id'], 
                        'tree' => $_SESSION['entities_chosen_tree'], 
                        'key_value' => $res->user_id, 
                        'label_value' => $res->user_id . ' - ' . $labelValue, 
                        'canhavechildren' => '', 
                        'is_entity' => 'false'
                    )
                );
            }
        }
    }
    $stmt = $db->query("select entity_id, entity_label from " 
        . ENT_ENTITIES . " where parent_entity_id = ? order by entity_label",array($_POST['branch_id']));

    if ($stmt->rowCount() > 0) {
        while ($res = $stmt->fetchObject()) {
            $canhavechildren = '';
            $canhavechildren = 'canhavechildren:true, ';
            if (!is_integer(array_search("'" . $res->entity_id . "'", $_SESSION['EntitiesIdExclusion'])) || count($_SESSION['EntitiesIdExclusion']) == 0) {
                $labelValue = '<span class="entity_tree_element_ok"><a href="index.php?page=entity_up&module=entities&id=' 
                            . $res->entity_id . '" target="_top">' . functions::show_string($res->entity_label, true) . '</a></span>';
            } else {
                $labelValue = '<small><i>' . functions::show_string($res->entity_label, true) . '</i></small>';
            }
            array_push(
                $children, 
                array(
                    'id' => $res->entity_id, 
                    'tree' => $_SESSION['entities_chosen_tree'], 
                    'key_value' => $res->entity_id, 
                    'label_value' => functions::show_string($res->entity_id, true) . ' - ' . $labelValue, 
                    'canhavechildren' => $canhavechildren, 
                    'is_entity' => 'true'
                )
            );
        }
    }
    if (count($children) > 0) {
        echo '[';
        for ($i=0;$i< count($children)-1;$i++) {
            echo "{id:'" . $children[$i]['id'] . "', " 
                . $children[$i]['canhavechildren'] . "txt:'" 
                . addslashes($children[$i]['label_value']) 
                . "', is_entity : ".$children[$i]['is_entity']."},";
        }
        // affichage du derniere élément
        echo "{id:'" . $children[$i]['id'] . "', " 
            . $children[$i]['canhavechildren'] . "txt:'" 
            . addslashes($children[$i]['label_value']) 
            . "', is_entity : " . $children[$i]['is_entity']."}";
        echo ']';
    } else {
        echo "[{id:'no_user_" . functions::xssafe($_POST['branch_id']) 
            . "', canhavechildren:false, txt:'<em>(" 
            . addslashes(_NO_USER) . ")</em>', is_entity : false}]";
    }
}
