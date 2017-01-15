<?php

require_once 'modules/basket/class/class_modules_tools.php';

$b = new basket();

$_SESSION['category_id'] = $_REQUEST['category_id'];

$actions = $b->get_actions_from_current_basket(
    $_REQUEST['resId'], $_REQUEST['collId'], 'PAGE_USE', false
);
if (count($actions) > 0) {
    $frmStr .= '<b>' . _ACTIONS . ' : </b>';
    $frmStr .= '<select name="chosen_action" id="chosen_action">';


    $frmStr .= '<option value="">' . _CHOOSE_ACTION . '</option>';
    
    for ($indAct = 0; $indAct < count($actions); $indAct ++) {
        if ($indAct == 0 && $_SESSION['current_basket']['id'] == 'IndexingBasket' && count($actions) > 1) {

        } else {
            $frmStr .= '<option value="' . $actions[$indAct]['VALUE'] . '"';
            if ($indAct == 0) {
                $frmStr .= 'selected="selected"';
            }
            $frmStr .= '>' . $actions[$indAct]['LABEL'] . '</option>';            
        }

    }
    
    $frmStr .= '</select> ';
} else {
    $frmStr .= _NO_AVAILABLE_ACTIONS_FOR_THIS_BASKET;
    echo "{status : 2, error_txt : '" . addslashes($frmStr) . "'}";
    exit ();
}

echo "{status : 0, selectAction : '" . addslashes($frmStr) . "'}";
exit ();
