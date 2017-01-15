<?php

/*
*   Copyright 2010 Maarch
*
*   This file is part of Maarch Framework.
*
*   Maarch Framework is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Maarch Framework is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with Maarch Framework. If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief life cycle Administration summary Page
*
* life cycle Administration summary Page
*
* @file
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup life_cycle
*/

$admin = new core_tools();
$admin->test_admin('admin_life_cycle', 'life_cycle');
/****************Management of the location bar  ************/
$init = false;
if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true") {
    $init = true;
}
$level = "";
if (isset($_REQUEST['level']) && ($_REQUEST['level'] == 2 
    || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 
    || $_REQUEST['level'] == 1)
) {
    $level = $_REQUEST['level'];
}
$page_path = $_SESSION['config']['businessappurl']
    .'index.php?page=life_cycle_administration&module=life_cycle';
$page_label = _ADMIN_LIFE_CYCLE_SHORT;
$page_id = "life_cycle_administration";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
unset($_SESSION['m_admin']);
?>
<h1><i class="fa fa-recycle fa-2x"></i> 
<?php echo _ADMIN_LIFE_CYCLE_SHORT;?></h1>
<div id="inner_content" class="clearfix">
<div class="block">
<h2 style="text-align:center;"><?php echo _ADMIN_LIFE_CYCLE;?></h1></h2>
    <div class="admin_item" title="
    <?php echo _MANAGE_LC_POLICIES;?>" 
    onclick="
    window.top.location='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=lc_policies_management_controler&mode=list&module=life_cycle';">
        <div>
                <i class="fa fa-recycle fa-4x"></i><br />
                <strong><?php echo _MANAGE_LC_POLICIES;?></strong>
        </div>
    </div>
    <div class="admin_item" title="
    <?php echo _MANAGE_LC_CYCLES;?>" 
    onclick="window.top.location='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=lc_cycles_management_controler&mode=list&module=life_cycle';">
        <div>
                <i class="fa fa-recycle fa-4x"></i><br />
                <strong><?php echo _MANAGE_LC_CYCLES;?></strong>
        </div>
    </div>
    <div class="admin_item" title="
    <?php echo _MANAGE_LC_CYCLE_STEPS;?>" onclick="window.top.location=
    '<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=lc_cycle_steps_management_controler&mode=list&module=life_cycle';">
        <div>
                <i class="fa fa-recycle fa-4x"></i><br />
                <strong><?php echo _MANAGE_LC_CYCLE_STEPS;?></strong>
        </div>
    </div>
    <div class="clearfix"></div>
    </div>
    </div>
</div>
