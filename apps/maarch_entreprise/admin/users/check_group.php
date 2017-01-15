<?php

$error = '';

function get_values_in_array($val)
{
    $val = str_replace("&#039;", "'", $val);
    $tab = explode('$$',$val);
    $values = array();
    for($i=0; $i<count($tab);$i++)
    {
        if(isset($tab[$i]))
        {
            $tmp = explode('#', $tab[$i]);
            if(isset($tmp[1]))
            {
                $values[] = array('ID' => $tmp[0], 'VALUE' => trim($tmp[1]));
            }
        }
    }
    return $values;
}

function get_value_fields($values, $field)
{
    for($i=0; $i<count($values);$i++)
    {
        if($values[$i]['ID'] == $field)
        {
            return  $values[$i]['VALUE'];
        }
    }
    return false;
}

if(!isset($_REQUEST['form_values']) || empty($_REQUEST['form_values']))
{
    $error = _ERROR_FORM_VALUES." check<br/>";
    echo "{status : 2, error_txt : '".$error."'}";
    exit();
}

$values = get_values_in_array($_REQUEST['form_values']);
$group_id = get_value_fields($values, 'group_id');
$role = get_value_fields($values, 'role');

if(!isset($group_id ) || $group_id  == '')
{
    $error = _NO_GROUP_SELECTED."!";
}

if(!empty($error))
{
    echo "{status : 1, error_txt : '".$error."'}";
}
else
{
    echo "{status : 0, error_txt : '".$error."'}";
}
 exit();
