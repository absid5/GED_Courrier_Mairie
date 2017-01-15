<?php
# AJAX script to list objects to be linked with list model

function asSelect(
    $items,
    $objectId = false
) {
    $return = "<select id='objectId' style='width:300px;'>";
    
    foreach($items as $id => $label) {
        $return .= "<option value='".$id."'";
        if($id == $objectId) $return .= " selected='true' ";
        $return .= ">";
            if($id) $return .=  $id . ' ';
            $return .= $label;
            
        $return .= "</option>";
    }
    $return .= "</select>";
    return $return;
}

require_once 'core/class/class_core_tools.php';
$core = new core_tools();
$core->load_lang();

require_once 'modules/entities/class/class_manage_listdiff.php';
$difflist = new diffusion_list();

$mode = $_REQUEST['mode'];
$objectType = $_REQUEST['objectType'];
$objectId = $_REQUEST['objectId'];

if(!$objectType) {
    echo ""; 
    return;
}

switch($objectType) {
case 'entity_id':   
    require_once 'modules/entities/class/class_manage_entities.php';
    $ent = new entity();
    $entity_ids = $ent->get_all_entities_id_user();
    $entities = array();
    for($i=0, $l=count($entity_ids);
        $i<$l;
        $i++
    ) {
        $entity_id = substr($entity_ids[$i], 1, -1);
        $existinglist = 
            $difflist->get_listmodel(
                'entity_id',
                $entity_id
            );
        if(!$existinglist) {
            $entities[$entity_id] = $ent->getentitylabel($entity_id); 
        }
    }
    if(count($entities) > 0)
        echo asSelect($entities, $objectId);
    else {   
        echo asSelect(array("" => _ALL_OBJECTS_ARE_LINKED));
    }    
    break;
case 'VISA_CIRCUIT':   
    require_once 'modules/entities/class/class_manage_entities.php';
    $ent = new entity();
    $entity_ids = $ent->get_all_entities_id_user();
    $entities = array();
    for($i=0, $l=count($entity_ids);
        $i<$l;
        $i++
    ) {
        $entity_id = substr($entity_ids[$i], 1, -1);
        $existinglist = 
            $difflist->get_listmodel(
                'VISA_CIRCUIT',
                $entity_id
            );
        if(!$existinglist) {
            $entities[$entity_id] = $ent->getentitylabel($entity_id); 
        }
    }
    if(count($entities) > 0)
        echo asSelect($entities, $objectId);
    else {   
        echo asSelect(array("" => _ALL_OBJECTS_ARE_LINKED));
    }    
    break;

case 'AVIS_CIRCUIT':   
    require_once 'modules/entities/class/class_manage_entities.php';
    $ent = new entity();
    $entity_ids = $ent->get_all_entities_id_user();
    $entities = array();
    for($i=0, $l=count($entity_ids);
        $i<$l;
        $i++
    ) {
        $entity_id = substr($entity_ids[$i], 1, -1);
        $existinglist = 
            $difflist->get_listmodel(
                'VISA_CIRCUIT',
                $entity_id
            );
        if(!$existinglist) {
            $entities[$entity_id] = $ent->getentitylabel($entity_id); 
        }
    }
    if(count($entities) > 0)
        echo asSelect($entities, $objectId);
    else {   
        echo asSelect(array("" => _ALL_OBJECTS_ARE_LINKED));
    }    
    break;
     
case 'type_id':
    require_once 'core/class/class_db_pdo.php';
    require_once 'core/core_tables.php';
    $db = new Database();
    $stmt = $db->query("SELECT type_id, description FROM  " . DOCTYPES_TABLE);
    while($doctype = $stmt->fetchObject()) {
        $type_id = $doctype->type_id;
        $existinglist = 
            $difflist->get_listmodel(
                'type_id',
                $type_id
            );
        if(!$existinglist) {
            $doctypes[$type_id] = $doctype->description; 
        }
    }
    if(count($doctypes) > 0)
        echo asSelect($doctypes, $objectId);
    else    
        echo asSelect(array("" => _ALL_OBJECTS_ARE_LINKED));
    
    break;
    
case 'foldertype_id':
    require_once 'core/class/class_db.php';
    require_once 'modules/folder/folder_tables.php';
    $db = new Database();
    $stmt = $db->query("SELECT foldertype_id, foldertype_label FROM  " . FOLD_FOLDERTYPES_TABLE);
    while($foldertype = $stmt->fetchObject()) {
        $foldertype_id = $foldertype->foldertype_id;
        $existinglist = 
            $difflist->get_listmodel(
                'foldertype_id',
                $foldertype_id
            );
        if(!$existinglist) {
            $foldertypes[$foldertype_id] = $foldertype->foldertype_label; 
        }
    }
    if(count($foldertypes) > 0)
        echo asSelect($foldertypes, $objectId);
    else    
        echo asSelect(array("" => _ALL_OBJECTS_ARE_LINKED));
    break;
    
case 'user_defined_id':
default:
    if(!$objectId) 
        $objectId = $objectType . '_' . strtoupper(base_convert(date('U'), 10, 36));
    echo "<input type='text' id='objectId' style='width:300px;' value='$objectId' />";
    break;
}

