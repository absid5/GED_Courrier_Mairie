<?php

$parent_id = $_REQUEST['parent_id'];


$values = '[';
for($i=1; $i<=300; $i++)
{
    $values .= "{ 'id':'".$parent_id.'_'.$i."','label':'".$parent_id.".".$i."', 'toolTip':'tooltip ".$parent_id.".".$i."'},";
}
$values = preg_replace('/,$/', ']', $values);
header('Content-type: application/json');
echo $values;
?>