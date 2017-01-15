<?php

core_tools::load_lang();
$error = '';

function get_values_in_array($val)
{
    $tab = explode('$$',$val);
    $values = array();
    for($i=0; $i<count($tab);$i++)
    {
        $tmp = explode('#', $tab[$i]);
        if(isset($tmp[1]))
        {
            array_push($values, array('ID' => $tmp[0], 'VALUE' => trim($tmp[1])));
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
$coll_id = get_value_fields($values, 'coll_id');
$comment = get_value_fields($values, 'comment');
$target_all = get_value_fields($values, 'target_ALL');
$target_doc = get_value_fields($values, 'target_DOC');
$target_class = get_value_fields($values, 'target_CLASS');
$mode = get_value_fields($values, 'mode');

if(!isset($mode ) || $mode  == '')
{
    $error = _MODE_ERROR."<br/>";
    echo "{status : 3, error_txt : '".$error."'}";
    exit();
}

if(!isset($coll_id ) || $coll_id  == '')
{
    $error = _COLLECTION.' '._MANDATORY."<br/>";
}

if(!isset($comment) || $comment == '')
{
    $error = _COMMENTS_MANDATORY."<br/>";
}

if((!isset($target_all) || $target_all == '') && (!isset($target_doc) || $target_doc == '') && (!isset($target_class) || $target_class == '') )
{
    $error = _WHERE_CLAUSE_TARGET.' '._MANDATORY."<br/>";
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
