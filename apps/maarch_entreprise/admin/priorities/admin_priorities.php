<?php
/*
*
*    Copyright 2008,2015 Maarch
*
*  This file is part of Maarch Framework.
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
*    along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*
*   @author <dev@maarch.org>
*/

$admin = new core_tools();
$admin->test_admin('admin_priorities', 'apps');
$_SESSION['m_admin']= array();
/****************Management of the location bar  ************/
$init = false;
if(isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true")
{
    $init = true;
}
$level = "";
if(isset($_REQUEST['level']) && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1))
{
    $level = $_REQUEST['level'];
}
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=admin_priorities&admin=priorities';
$page_label = _ADMIN_PRIORITIES;
$page_id = "admin_priorities";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
?>
<h1><i class="fa fa-hdd-o fa-2x"></i> <?php echo _ADMIN_PRIORITIES;?></h1>
<div id="inner_content" class="clearfix" align="center">
    <div class="block" style="width: 90%">
        <h2><?php echo 'Paramètrage des priorités';?></h2>
        <form id="prioritiesForm" name="prioritiesForm" method="post"
              action="<?php echo $_SESSION['config']['businessappurl'] . 'index.php?page=priorityManager&admin=priorities&mode=update'; ?>">
            <table width="45%" class="prioritiesTable" id="prioritiesTable">
                <tr>
                   <th width="33%" align="left">
                       <?php echo _PRIORITY_TITLE ;?>
                   </th>
                    <th width="33%" align="left">
                        <?php echo _PRIORITY_DAYS ;?>
                    </th>
                    <th width="33%" align="left">
                        <?php echo "Méthode de calcul" ;?>
                    </th>
                </tr>
                <?php
                for ($i = 0; $_SESSION['mail_priorities'][$i]; $i++) {
                    $wdays = ($_SESSION['mail_priorities_wdays'][$i] == 'true' ? '' : 'selected');
                    echo "<tr><td align='left'><input name='label_{$i}' id='label_{$i}' placeholder='Priority label' size='18' value='{$_SESSION['mail_priorities'][$i]}'></td>";
                    if ($_SESSION['mail_priorities_attribute'][$i] == 'false') {
                        echo "<td align='left'><input name='priority_{$i}' id='priority_{$i}' size='6' value='*'>";
                    } else {
                        echo "<td align='left'><input name='priority_{$i}' id='priority_{$i}' size='6' value='{$_SESSION['mail_priorities_attribute'][$i]}'>";
                    }
                    echo "</td><td align='left' data-index='{$i}'><select name='working_{$i}' id='working_{$i}'><option value='true'>" . _WORKING_DAYS . "</option><option value='false' {$wdays}>" . _CALENDAR_DAYS . "</option></select>";
                    echo "&nbsp; &nbsp; <i title='Supprimer la priorité' style='cursor: pointer' class='fa fa-trash fa-lg' onclick='deletePriority(this.parentNode)'></i></td></tr>";
                }
                ?>
                <tr style="display: none" id="priorityAddField">
                    <td align="left">
                        <input name='label_new0' id='label_new0' placeholder='Nom priorité' size='18'>
                    </td>
                    <td align="left">
                        <input name='priority_new0' id='priority_new0' size='6' value='*'>
                    </td>
                    <td align='left'>
                        <select name='working_new0' id='working_new0'>
                            <option value='true'><?php echo _WORKING_DAYS ;?></option>
                            <option value='false'><?php echo _CALENDAR_DAYS ;?></option>
                        </select>
                    </td>
                </tr>
                <tr id="priorityAddButton">
                    <td colspan="1" align="left">
                        <i title="Ajouter une priorié" style="cursor: pointer" class="fa fa-plus fa-2x" onclick="addNewRowPriority(this.parentNode.parentNode)"></i>
                        &nbsp;
                        <i id="minusButton" title="Enlever une priorié" style="cursor: pointer;display: none" class="fa fa-minus fa-2x" onclick="delNewRowPriority(this.parentNode.parentNode)"></i>
                    </td>
<!--                    <td id="minusButton" colspan="2" align="" style="display: none">-->
<!--                        <i title="Enlever une priorié" style="cursor: pointer" class="fa fa-minus fa-2x" onclick="delNewRowPriority(this.parentNode.parentNode)"></i>-->
<!--                    </td>-->
                </tr>
                <tr>
                    <td style="padding-bottom: 6%" colspan="3" align="center">
                        <em>Pour utiliser le délai de traitement lié au type de document, veuillez taper *</em>
                    </td>
                </tr>
                <tr class="buttons">
                    <td colspan="3" align="center">
                        <input class="button" type="submit" value="Valider">
                        <input class="button" type="button" value="<?php echo _CANCEL;?>"
                               onclick="javascript:window.location.href='<?php echo $_SESSION['config']['businessappurl']; ?>index.php?page=admin&reinit=true';">
                    </td>
                </tr>

            </table>
        </form>
    </div>
</div>
