<?php
/**
* File : sous_dossier_del.php
*
* Delete a subfolder
*
* @package  Maarch PeopleBox 1.0
* @version 2.1
* @since 06/2006x
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*/
$core_tools = new core_tools();
$core_tools->test_admin('admin_architecture', 'apps');
$core_tools->load_lang();

$db = new Database();

if(isset($_GET['id']))
{
    $id = addslashes(functions::wash($_GET['id'], "no", _THE_SUBFOLDER));
}
else
{
    $id = "";
}

$stmt = $db->query("SELECT doctypes_second_level_label FROM ".$_SESSION['tablename']['doctypes_second_level']." WHERE doctypes_second_level_id = ?", array($id));

if($stmt->rowCount() == 0)
{
    $_SESSION['error'] = _SUBFOLDER.' '._UNKNOWN.".";
    header("location: ".$_SESSION['config']['businessappurl']."index.php?page=subfolders&order=".$_REQUEST['order']."&order_field=".$_REQUEST['order_field']."&start=".$_REQUEST['start']."&what=".$_REQUEST['what']);
    exit();
}
else
{
    $info = $stmt->fetchObject();

    $db->query("UPDATE ".$_SESSION['tablename']['doctypes_second_level']." SET enabled = 'N' WHERE doctypes_second_level_id = ?", array($id));

    $stmt = $db->query("SELECT type_id FROM ".$_SESSION['tablename']['doctypes']." WHERE doctypes_second_level_id = ?", array($id));

    while($res = $stmt->fetchObject())
    {
        //delete the doctypes from the foldertypes_doctypes table
        $db->query("DELETE FROM  ".$_SESSION['tablename']['fold_foldertypes_doctypes']."  WHERE doctype_id = ? ", array($res->type_id));
    }
    // delete the doctypes
    $db->query("UPDATE ".$_SESSION['tablename']['doctypes']." SET enabled = 'N' WHERE doctypes_second_level_id = ? ", array($id));

    if($_SESSION['history']['subfolderdel'] == "true")
    {
        require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
        $users = new history();
        $users->add($_SESSION['tablename']['doctypes_second_level'], $id,"DEL",'subfolderdel', _DEL_SUBFOLDER." ".strtolower(_NUM).$id."", $_SESSION['config']['databasetype']);
    }
    $_SESSION['info'] = _SUBFOLDER_DELETED;
        unset($_SESSION['m_admin']);
    header("location: ".$_SESSION['config']['businessappurl']."index.php?page=subfolders&order=".$_REQUEST['order']."&order_field=".$_REQUEST['order_field']."&start=".$_REQUEST['start']."&what=".$_REQUEST['what']);
    exit();
}
?>
