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
    require_once("modules/entities/class/EntityControler.php");
} catch (Exception $e){
    functions::xecho($e->getMessage());
}

$entity_ctrl = new EntityControler();

$values = get_values_in_array($_REQUEST['form_values']);

$entity_id = get_value_fields($values, 'entity_id');
$role = get_value_fields($values, 'role');

$entity = $entity_ctrl->get($entity_id);

if(!isset($_SESSION['m_admin']['entity']['entities']))
    $_SESSION['m_admin']['entity']['entities'] = array();

array_push($_SESSION['m_admin']['entity']['entities'] , array('USER_ID' => '', 'ENTITY_ID' => $entity_id , 'LABEL' => functions::show_string($entity->__get('entity_label')),  'SHORT_LABEL' => functions::show_string($entity->__get('short_label')), 'PRIMARY' => 'N', 'ROLE' => functions::show_string($role)));

if(count($_SESSION['m_admin']['entity']['entities']) == 1)
    $_SESSION['m_admin']['entity']['entities'][0]['PRIMARY'] = 'Y';

echo "{status : 0, error_txt : '".$error."'}";
exit();
?>
