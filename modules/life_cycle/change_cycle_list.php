<?php

/**
 *Change cycle list according to the life cycle policy 
 * 
 * 
 * 
 */
require_once("core/class/class_core_tools.php");
require_once("modules/life_cycle/class/lc_cycles_controler.php");
$core_tools = new core_tools();
$core_tools->load_lang();
$lcCyclesControler = new lc_cycles_controler();
$cyclesArray = $lcCyclesControler->getAllIdByPolicy($_POST['policy_id']);
$selectCycle = '';
$selectCycle .= '<p>';
$selectCycle .= '<label for="cycle_id">'. _CYCLE_ID.' : </label>';
$selectCycle .= '<select name="cycle_id" id="cycle_id">';
$selectCycle .= '    <option value="">'._CYCLE_ID.'</option>';
for ($cptCycle = 0;$cptCycle < count($cyclesArray);$cptCycle++) {
    $selectCycle .= '<option value="'.$cyclesArray[$cptCycle].'"';
    if (isset($_SESSION['m_admin']['lc_cycle_steps']['cycle_id']) 
    && $_SESSION['m_admin']['lc_cycle_steps']
        ['cycle_id'] == $cyclesArray[$cptCycle]
    ) { 
        $selectCycle .= ' selected="selected"';
    }
    $selectCycle .= '>'.$cyclesArray[$cptCycle].'</option>';
}
$selectCycle .= '</select>';
$selectCycle .= '</p>';
echo "{status : 0, selectCycle : '" . addslashes($selectCycle) . "'}";
exit ();
