<?php
/**
* File : add_users_entities.php
*
* Form to add an entity to a user, pop up page
*
* @package  Maarch Framework 3.0
* @version 1
* @since 03/2009
* @license GPL
* @author  Cédric Ndoumba  <dev@maarch.org>
*/
try{
    require_once("modules/entities/class/EntityControler.php");
} catch (Exception $e){
    functions::xecho($e->getMessage());
}
core_tools::load_lang();
//core_tools::test_admin('manage_entities', 'entities');

require_once('modules/entities/class/class_manage_entities.php');;
$ent = new entity();
$entity_ctrl = new EntityControler();
//$except = array();
/* If you want that a user can not belong to an entity and one of this entity subentity, decomment these lines
for($i = 0; $i < count($_SESSION['m_admin']['entity']['entities']); $i++)
{
    $except[] = $_SESSION['m_admin']['entity']['entities'][$i]['ENTITY_ID'];
}
*/


$entities = array();
//$entities = $entity_ctrl->getAllEntities(); // To do : recup l'arborescence des entités
$entities = $entity_ctrl->getAllEntities();
//var_dump($entities);
$EntitiesIdExclusion = array();
if ($_SESSION['user']['UserId'] != 'superadmin') {
    $my_tab_entities_id = $ent->get_all_entities_id_user($_SESSION['user']['entities']);
    $my_tab_entities_id = array_unique($my_tab_entities_id);
    //var_dump($my_tab_entities_id);
    if (count($my_tab_entities_id) > 0) {
        $countEntities = count($entities);
        for ($cptAllEnt = 0;$cptAllEnt<$countEntities;$cptAllEnt++) {
            //$result = array_search("'" . $entities[$cptAllEnt]->__get('entity_id') . "'", $my_tab_entities_id);
            //var_dump($result);
            if (!is_integer(array_search("'" . $entities[$cptAllEnt]->__get('entity_id') . "'", $my_tab_entities_id))) {
                array_push($EntitiesIdExclusion, $entities[$cptAllEnt]->__get('entity_id'));
                unset($entities[$cptAllEnt]);
            }
        }
    }
} else {
    $entities = $entity_ctrl->getAllEntities();
}
$entities = array_values($entities);

$allEntitiesTree= array();
$allEntitiesTree = $ent->getShortEntityTreeAdvanced(
    $allEntitiesTree, 'all', '', $EntitiesIdExclusion, 'all'
);
//var_dump($allEntitiesTree);

function in_session_array($entity_id)
{
    for($i=0; $i<count($_SESSION['m_admin']['entity']['entities']);$i++)
    {
        if(trim($entity_id) == trim($_SESSION['m_admin']['entity']['entities'][$i]['ENTITY_ID']))
            return true;
    }
    return false;
}

?>
<div class="popup_content">
<h2 class="tit"><?php echo USER_ADD_ENTITY;?></h2>
<form name="chooseEntity" id="chooseEntity" method="get" action="#" class="forms" style="height:350px;">
<p>
    <label for="entity_id" style="width:90%;"> <?php echo _CHOOSE_ENTITY;?> : </label>
    <select name="entity_id" id="entity_id" size="30" style="width: auto" >
    <?php
		
        $countAllEntities = count($allEntitiesTree);
        for ($cptEntities = 0;$cptEntities < $countAllEntities;$cptEntities++) {
            if (!$allEntitiesTree[$cptEntities]['KEYWORD']) {
                $optionStr .= '<option title="' 
                    .  str_replace('&emsp;', '', $ent->show_string($allEntitiesTree[$cptEntities]['SHORT_LABEL'])) 
                    . '" data-object_type="entity_id" value="' 
                    . $allEntitiesTree[$cptEntities]['ID'] . '"';
                if ($allEntitiesTree[$cptEntities]['DISABLED']) {
                    $optionStr .= ' disabled="disabled" class="disabled_entity"';
                } else {
                     //$optionStr .= ' style="font-weight:bold;"';
                }
                $optionStr .=  '>' 
                    .  $ent->show_string($allEntitiesTree[$cptEntities]['SHORT_LABEL']) 
                    . '</option>';
            }
        }
        echo $optionStr;
		
		
    ?>
    </select>
    <script type="text/javascript">new Chosen($('entity_id'),{width: "95%", disable_search_threshold: 10, search_contains: true});</script>
</p>
<br/>
<p>
    <label for="role"><?php echo _ROLE;?> : </label>
    <input type="text"  name="role" id="role" style="width:90%;" />
</p>
<br/>
<p class="buttons" style="position:absolute;bottom:5px;margin:0px;padding:0px;left:0px;text-align:center;width:100%;">
    <input type="button" name="Submit" value="<?php echo _VALIDATE;?>" class="button" onclick="checkUserEntity('chooseEntity', '<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&module=entities&page=check_user_entities';?>', '<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&module=entities&page=manage_user_entities';?>', '<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&module=entities&page=users_entities_form';?>');"  />
    <input type="button" name="cancel" value="<?php echo _CANCEL;?>" class="button"  onclick="destroyModal('add_user_entities');"/>
</p>

</form>
</div>
