<?php
$error = '';
function get_values_in_array($val)
{
    $val = str_replace("&#039;", "'", $val);
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
    $error = _ERROR_FORM_VALUES."<br/>";
    echo "{status : 1, error_txt : '".$error."'}";
    exit();
}

try{
    require_once("core/class/usergroups_controler.php");
} catch (Exception $e){
    functions::xecho($e->getMessage());
}
$ugc = new usergroups_controler();
$values = get_values_in_array($_REQUEST['form_values']);

$group_id = get_value_fields($values, 'group_id');
$role = get_value_fields($values, 'role');

$group = $ugc->get($group_id);
array_push($_SESSION['m_admin']['users']['groups'] , array('USER_ID' => '', 'GROUP_ID' => $group_id , 'LABEL' => $group->__get('group_desc'), 'PRIMARY' => 'N', 'ROLE' => functions::show_string($role)));

if(count($_SESSION['m_admin']['users']['groups']) == 1)
{
    $_SESSION['m_admin']['users']['groups'][0]['PRIMARY'] = 'Y';
}

echo "{status : 0, error_txt : '".$error."'}";
exit();
?>
