<?php
$error = '';
function getValuesInArray($val)
{
    $tab = explode('$$', $val);
    $values = array();
    for ($i = 0; $i < count($tab); $i ++) {
        $tmp = explode('#', $tab[$i]);
        if (isset($tmp[1])) {
            array_push(
                $values,
                array(
                	'ID' => $tmp[0],
                	'VALUE' => trim($tmp[1])
                )
            );
        }
    }
    return $values;
}

function getValueFields($values, $field)
{
    for ($i = 0; $i < count($values); $i ++) {
        if ($values[$i]['ID'] == $field) {
            return  $values[$i]['VALUE'];
        }
    }
    return false;
}

if (! isset($_REQUEST['form_values']) || empty($_REQUEST['form_values'])) {
    $error = _ERROR_FORM_VALUES . "<br/>";
    echo "{status : 1, error_txt : '" . $error . "'}";
    exit();
}

try {
    include 'apps/'.$_SESSION['config']['app_id'].'/security_bitmask.php';
    include 'core/manage_bitmask.php';
    include 'core/class/class_security.php';
} catch (Exception $e) {
    functions::xecho($e->getMessage());
}

$values = getValuesInArray($_REQUEST['form_values']);

$collId = getValueFields($values, 'coll_id');
$comment = getValueFields($values, 'comment');
$where = getValueFields($values, 'where');
$startDate = getValueFields($values, 'start_date');
$stopDate = getValueFields($values, 'stop_date');
$mode = getValueFields($values, 'mode');

$targetAll = getValueFields($values, 'target_ALL');
$targetDoc = getValueFields($values, 'target_DOC');
$targetClass = getValueFields($values, 'target_CLASS');
$target = 'ALL';
if (isset($targetAll) && ! empty($targetAll)) {
    $target = $targetAll;
} else if (isset($targetDoc) && ! empty($targetDoc)) {
    $target = $targetDoc;
} else if (isset($targetClass) && ! empty($targetClass)) {
    $target = $targetClass;
}

$bitmask = 0;
for ($i = 0; $i < count($_ENV['security_bitmask']); $i ++) {
    $tmp = getValueFields($values, $_ENV['security_bitmask'][$i]['ID']);
    if (isset($tmp) && $tmp == 'true') {
        $bitmask = set_right($bitmask, $_ENV['security_bitmask'][$i]['ID']);
    }
}

if ($mode == 'up') {
    for ($i = 0; $i < count($_SESSION['m_admin']['groups']['security']); $i ++) {
        if ($_SESSION['m_admin']['groups']['security'][$i]['COLL_ID'] == $collId) {
            $_SESSION['m_admin']['groups']['security'][$i]['WHERE_CLAUSE'] = $where;
            $_SESSION['m_admin']['groups']['security'][$i]['COMMENT'] = $comment;
            $_SESSION['m_admin']['groups']['security'][$i]['WHERE_TARGET'] = $target;
            $_SESSION['m_admin']['groups']['security'][$i]['RIGHTS_BITMASK'] = $bitmask;
            $_SESSION['m_admin']['groups']['security'][$i]['START_DATE'] = $startDate;
            $_SESSION['m_admin']['groups']['security'][$i]['STOP_DATE'] = $stopDate;
            break;
        }
    }
} else {
    $sec = new security();
    $ind = $sec->get_ind_collection($collId);
    $groupId = '';
    if (isset($_SESSION['m_admin']['groups']['group_id'])) {
        $groupId = $_SESSION['m_admin']['groups']['group_id'];
    }
    $secId = count($_SESSION['m_admin']['groups']['security']);
    array_push(
        $_SESSION['m_admin']['groups']['security'] ,
        array(
        	'SECURITY_ID' => $secId,
        	'GROUP_ID' => $groupId ,
        	'COLL_ID' => $collId ,
        	'IND_COLL_SESSION' => $ind,
        	'WHERE_CLAUSE' => $where,
        	'COMMENT' => $comment,
        	'WHERE_TARGET' => $target,
        	'RIGHTS_BITMASK' => $bitmask,
        	'START_DATE' => $startDate,
        	'STOP_DATE' => $stopDate
        )
    );
    $_SESSION['m_admin']['load_security'] = false;
}
echo "{status : 0, error_txt : '" . $error . "'}";
exit();

