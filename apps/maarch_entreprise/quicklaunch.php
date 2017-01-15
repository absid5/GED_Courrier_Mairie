<?php

/*
*   Copyright 2008-2015 Maarch
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
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/*
* @brief  Access to the baskets
*
*
* @file
* @author Loic Vinet <dev@maarch.org>
* @author Claire Figueras <dev@maarch.org>
* @author Lauren Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/

require_once('core/class/class_request.php');
$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->test_service('quicklaunch', "apps");
?>
<div id="welcome_box_left_quick_lunch">
    <!-- QUICK SEARCH ON COLLECTION -->
    <?php
    if($core_tools->test_service('adv_search_mlb', "apps",false) == true){
        if (count($_SESSION['user']['security']) > 0 || $_SESSION['user']['UserId'] == 'superadmin') {
        ?>
        <form name="choose_query" id="choose_query"  method="post" action="" class="<?php functions::xecho($class_for_form);?>" >
            <div class="block">
                <h2><?php echo _QUICK_SEARCH;?> :</h2>
            <table>
                <tr>
                    <td>
                        <select id="collection" name="collection" onchange="updateActionForm(this.options[this.selectedIndex].value);">
                            <?php
                            foreach ($_SESSION['user']['security'] as $key => $value) {
                                if ($key == 'letterbox_coll' || $key == 'business_coll' || $key == 'rm_coll' || $key == 'res_coll') {
                                    echo '<option id="' . functions::xssafe($key) . '" value="' . functions::xssafe($key) . '">' 
                                        . functions::xssafe($value['DOC']['label_coll']) .'</option>';
                                }
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        &nbsp;
                        <input id = "text" name = "welcome" id="welcome" size="42" autocomplete = "off">
                        <input type="hidden" name="meta[]" value="baskets_clause#baskets_clause#select_simple" />
                        <input type="hidden" name="meta[]" value="welcome#welcome#welcome" />
                        <input type="hidden" name="baskets_clause" value="true" />
                        <input class="button" type="submit"  value="<?php echo _SEARCH;?>" title="<?php echo _HELP_GLOBAL_SEARCH;?>" name ="Submit" >
                    </td>
                <tr/>
            </table>
            </div>
        </form>
        <script language="javascript">
            if ($('collection').value) {
                updateActionForm($('collection').value);
            }
            function updateActionForm(collId)
            {
                var targetAction;
                if (collId == 'letterbox_coll') {
                    targetAction = 'index.php?display=true&dir=indexing_searching&page=search_adv_result';
                } else {
                    window.alert('no global search for this collection');
                }
                //console.log(targetAction);
                $('choose_query').action = targetAction;
            }
        </script>
        <?php
        }
    }
    ?>
</div>

<div id="welcome_box_right">
    <div class="block">

    <h2><?php echo _QUICKLAUNCH;?> :</h2>
<?php
    $core_tools->build_quicklaunch($_SESSION['quicklaunch']);
   
    ?>

    </div>

    <div class="block" style="margin-top:14px;">
    <h2><?php echo _VIEW_LAST_COURRIERS;?> :</h2>
        <div id="welcome_box_right_last_mails">
        <div style="display: none;" id="loading"><i class="fa fa-spinner fa-spin fa-2x"></i></div>

        <script language="javascript">
        <?php 
        echo "loadList('";
        echo $_SESSION['config']['businessappurl'];
        echo "index.php?display=true&dir=indexing_searching&page=loadFiveLastMails','divList2', true, true);";
        ?>
        </script>
        <div id="divList2" name="history_recent2"></div>
        </div>
    </div>

  
</div>