<?php
/*
listmodel_type_id : $('listmodel_type_id').value,
listmodel_type_label : $('listmodel_type_label').value,
*/
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("core/core_tables.php");
$db = new Database();

$mode = $_REQUEST['mode']; 
$id = $_REQUEST['id']; 
$description = $_REQUEST['description'];
$param_value_string = $_REQUEST['param_value_string'];
$param_value_int = $_REQUEST['param_value_int'];
$param_value_date = $_REQUEST['param_value_date'];

$type = $_REQUEST['type'];

switch($type) {
case 'string':
    $column = 'param_value_string';
    $value = (string)$param_value_string;
    break;
    
case 'int':
    $column = 'param_value_int';
    $value = (integer)$param_value_int;
    break;

case 'date':
    $column = 'param_value_date';
    $value = $param_value_date;
    break;
}


# If no error, proceed
switch($_REQUEST['mode']) {
case 'add':
    $res = $db->query(
        "INSERT INTO " . PARAM_TABLE
            . " (id, description, ".$column.")"
            . " values (?, ?, ? )",
    array($id, $description, $value)
    );
    break;
    
case 'up':
    $res = $db->query(
        "UPDATE " . PARAM_TABLE 
        . " SET "
            . "description = ?, "
            . $column. " = ? "
        . "where id = ?",
        array($description, $value, $id)
    );
    break;
    
case 'del':
    $res = $db->query(
        "DELETE FROM " . PARAM_TABLE 
        . " WHERE id = ?",
        array($id)
    );
    break;

}