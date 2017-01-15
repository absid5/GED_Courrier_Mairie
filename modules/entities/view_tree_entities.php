<?php
/**
* File : view_tree_entities.php
*
* Entities Administration view tree
*
* @package  Maarch Framework 3.0
* @version 1
* @since 03/2009
* @license GPL
* @author  C�dric Ndoumba  <dev@maarch.org>
*/

require_once("modules/entities/entities_tables.php");
$admin = new core_tools();
$admin->test_admin('manage_entities', 'entities');
$db = new Database();
/****************Management of the location bar  ************/
$init = false;
if(isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true")
{
    $init = true;
}
$level = "";
if (isset($_REQUEST['level']) && ($_REQUEST['level'] == 2
    || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4
    || $_REQUEST['level'] == 1)
) {
    $level = $_REQUEST['level'];
}
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=view_tree_entities&module=entities';
$page_label = _ENTITY_TREE;
$page_id = "view_tree_entities";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
unset($_SESSION['m_admin']);
$_SESSION['tree_entities'] = array();

$stmt = $db->query("select entity_id, entity_label from ".ENT_ENTITIES." where parent_entity_id = '' or parent_entity_id is null order by entity_label");
while($res = $stmt->fetchObject())
{
    array_push($_SESSION['tree_entities'], array("ID" => $res->entity_id, "LABEL" => $res->entity_label));
}
?>
<h1><i class="fa fa-code-fork fa-2x"></i> <?php echo _ENTITY_TREE;?></h1>
<div id="inner_content" class="clearfix">
    <table width="100%" border="0">
        <tr>
            <td>
                <iframe name="choose_tree" id="choose_tree" width="550" height="40" frameborder="0" scrolling="no" src="<?php echo $_SESSION['config']['businessappurl']."index.php?display=true&module=entities&page=choose_tree";?>" style="width:100%;"></iframe>
            </td>
        </tr>
        <tr>
            <td>
                <iframe name="show_trees" class="block" id="show_trees" width="550" height="600" frameborder="0" scrolling="auto" src="<?php echo $_SESSION['config']['businessappurl']."index.php?display=true&module=entities&page=show_trees";?>" style="width:99%;"></iframe>
            </td>
        </tr>
    </table>
</div>
