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

$select = "select distinct(description) from ".ENT_LISTMODELS;
$where = " where (lower(description) like lower(?) ";
$where .= " or lower(object_id) like lower(?)) ";

if($_SESSION['user']['UserId'] != 'superadmin')
{
    $my_tab_entities_id = $ent->get_all_entities_id_user($_SESSION['user']['entities']);
    if (count($my_tab_entities_id)>0)
    {
        $where.= ' and object_id in ('.join(',', $my_tab_entities_id).')';
    }
}

$sql = $select.$where." order by description";
//var_dump($sql);
$stmt = $db->query($sql,array("%".$_REQUEST['what']."%","%".$_REQUEST['what']."%"));
$listModels = array();
while($line = $stmt->fetchObject())
{
    array_push($listModels, $line->description);
}

echo "<ul>\n";
$authViewList = 0;
foreach($listModels as $description)
{
    if($authViewList >= 10)
    {
        $flagAuthView = true;
    }
    // if(stripos($entity, $_REQUEST['what']) === 0)
    // {
        echo "<li>".$description."</li>\n";
        if(isset($flagAuthView) && $flagAuthView)
        {
            echo "<li>...</li>\n";
            break;
        }
        $authViewList++;
    // }
}
echo "</ul>";
