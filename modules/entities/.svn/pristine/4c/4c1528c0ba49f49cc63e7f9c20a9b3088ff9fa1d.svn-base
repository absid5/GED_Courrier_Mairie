<?php
/**
* File : entities_list_by_label.php
*
* List of entities for autocompletion
*
* @package  Maarch Framework 3.0
* @version 3
* @since 10/2005
* @license GPL
* @author Cédric Ndoumba <dev@maarch.org>
*/

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once('modules'.DIRECTORY_SEPARATOR.'entities'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_manage_entities.php');
require_once("modules/entities/entities_tables.php");
$ent = new entity();
$db = new Database();

$select = "select entity_label from ".ENT_ENTITIES;
$where = " where (lower(entity_label) like lower(?) ";
$where .= " or lower(short_label) like lower(?) ";
$where .= " or lower(entity_id) like lower(?)) ";

if($_SESSION['user']['UserId'] != 'superadmin')
{
    $my_tab_entities_id = $ent->get_all_entities_id_user($_SESSION['user']['entities']);
    if (count($my_tab_entities_id)>0)
    {
        $where.= ' and entity_id in ('.join(',', $my_tab_entities_id).')';
    }
}

$sql = $select.$where." order by entity_id";

$stmt = $db->query($sql,array("%".$_REQUEST['what']."%","%".$_REQUEST['what']."%","%".$_REQUEST['what']."%"));

$entities = array();
while($line = $stmt->fetchObject())
{
    array_push($entities, $line->entity_label);
}

echo "<ul>\n";
$authViewList = 0;
foreach($entities as $entity)
{
    if($authViewList >= 10)
    {
        $flagAuthView = true;
    }
    // if(stripos($entity, $_REQUEST['what']) === 0)
    // {
        echo "<li>".$entity."</li>\n";
        if(isset($flagAuthView) && $flagAuthView)
        {
            echo "<li>...</li>\n";
            break;
        }
        $authViewList++;
    // }
}
echo "</ul>";
