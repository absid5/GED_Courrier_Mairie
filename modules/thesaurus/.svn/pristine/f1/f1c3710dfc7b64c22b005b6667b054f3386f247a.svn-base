<?php
/*
*    Copyright 2008,2016 Maarch
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
*/

/**
* Module : Thesaurus
* 
* This module is used to store ressources with any keywords
* V: 1.0
*
* @file
* @author Loic Vinet
* @date $date$
* @version $Revision$
*/

/* Affichage */
if ($mode == 'list') {
    $list = new list_show();
    $list->admin_list(
        $thesauruslist['tab'],
        count($thesauruslist['tab']),
        $thesauruslist['title'],
        'thesaurus_name',
        'manage_thesaurus_list_controller&mode=list',
        'thesaurus',
        'thesaurus_id',
        true,
        $thesauruslist['page_name_up'],
        $thesauruslist['page_name_val'],
        $thesauruslist['page_name_ban'],
        $thesauruslist['page_name_del'],
        $thesauruslist['page_name_add'],
        $thesauruslist['label_add'],
        false,
        false,
        _ALL_THESAURUS,
        _THESAURUS,
        'bookmark-o',
        true,
        true,
        false,
        true,
        $eventsList['what'],
        true,
        $thesauruslist['autoCompletionArray']
    );
    echo '<script>
    $(\'what\').onblur=function(){
        var str = $(\'what\').value;
        if(str.indexOf("(",-1)!=-1){
            str = str.split(\'\').reverse().join(\'\');
            index = str.indexOf("(",-1);
            str = str.substring(index+2, str.length);
            str = str.split(\'\').reverse().join(\'\');
            $(\'what\').value = str; 
        }
        
    };
</script>';
} elseif ($mode == 'up' || $mode == 'add') {
    //var_dump($_SESSION['m_admin']['thesaurus'])
    ?><h1><i class="fa fa-bookmark-o fa-2x"> </i>
        <?php
        if ($mode == 'up') {
            echo _MODIFY_THESAURUS;
        } elseif ($mode == 'add') {
            echo _ADD_THESAURUS;
        }?>
    </h1>
    <div id="inner_content" class="clearfix" align="center">
        <br />
    <?php
    if ($state == false) {
        echo '<br /><br /><br /><br />' . _THIS_EVENT . ' ' . _IS_UNKNOWN
        . '<br /><br /><br /><br />';
    } else { ?>
    <form name="frmevent" id="frmevent" method="post" action="<?php
            echo $_SESSION['config']['businessappurl'] . 'index.php?display=true'
            . '&amp;module=thesaurus&amp;page=manage_thesaurus_list_controller&amp;mode='
            . $mode;?>" class="forms addforms" style="width:100%;">
    <div class="block" style="width:50%;height:300px;float:left;">
        <div style="width:450px;">
            <input type="hidden" name="display" value="true" />
            <input type="hidden" name="admin" value="thesaurus" />
            <input type="hidden" name="page" value="manage_thesaurus_list_controler" />
            <input type="hidden" name="mode" value="<?php functions::xecho($mode);?>" />

            <input type="hidden" name="thesaurus_id" id="thesaurus_id" value="<?php functions::xecho($_SESSION['m_admin']['thesaurus']['thesaurus_id']);?>" />

            <input type="hidden" name="order" id="order" value="<?php
                functions::xecho($_REQUEST['order']);?>" />
            <input type="hidden" name="order_field" id="order_field" value="<?php
                functions::xecho($_REQUEST['order_field']);?>" />
            <input type="hidden" name="what" id="what" value="<?php
                functions::xecho($_REQUEST['what']);?>" />
            <input type="hidden" name="start" id="start" value="<?php
                functions::xecho($_REQUEST['start']);?>" />

            <p>
                <label for="thesaurus_parent_id" style="width:110px;"><?php echo _THESAURUS_PARENT_ID;?> : </label>
                <?php
                require_once 'modules' . DIRECTORY_SEPARATOR . 'thesaurus'
                            . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
                            . 'class_modules_tools.php';
                $thesaurus = new thesaurus();
                //$allThesaurusTree= array();
                //$allThesaurusTree = $thesaurus->getShortThesaurusTreeAdvanced($allThesaurusTree);    

                //$countAllThesaurus = count($allThesaurusTree);
                $thesaurus_parent_id = $thesaurus->getThesIdByLabel($_SESSION['m_admin']['thesaurus']['thesaurus_parent_id']);
                ?>
                 <input type="hidden" id="thesaurus_parent_id" value="<?php echo $thesaurus_parent_id; ?>" name="thesaurus_parent_id" style="width:260px;" onchange="load_specific_thesaurus(this.value);" />
                <input type="text" readonly="readonly" class="readonly" id="thesaurus_parent_label" value="<?php
                    echo functions::show_str(
                        $_SESSION['m_admin']['thesaurus']['thesaurus_parent_id']
                    );?>" name="thesaurus_parent_label" style="width:270px;" /> <i onclick="lauch_thesaurus_list_admin(this);" class="fa fa-search" title="parcourir le thésaurus" aria-hidden="true" style="cursor:pointer;"></i> <i onclick="document.getElementById('thesaurus_parent_id').value = '';document.getElementById('thesaurus_parent_label').value = '';" class="fa fa-eraser" title="<?php echo _RESET; ?>" aria-hidden="true" style="cursor:pointer;"></i>
                <!--<input name="thesaurus_parent_id" type="text"  id="thesaurus_parent_id" value="<?php
                    echo functions::show_str(
                        $_SESSION['m_admin']['thesaurus']['thesaurus_parent_id']
                    );?>"/>-->
            </p>

            <p>
                <label for="thesaurus_name" style="width:110px;"><?php echo _THESAURUS_NAME;?> : </label>
                <input name="thesaurus_name" type="text"  style="width:300px;" id="thesaurus_name" value="<?php
                    echo functions::show_str(
                        $_SESSION['m_admin']['thesaurus']['thesaurus_name']
                    );?>"/>
            </p>
                <p>
                <label for="used_for" style="width:110px;"><?php echo _USED_FOR;?> : </label>
                <input name="used_for" type="text"  style="width:300px;" id="used_for" value="<?php
                    echo functions::show_str(
                        $_SESSION['m_admin']['thesaurus']['used_for']
                    );?>"/>
            </p>
            <p>
                <label for="thesaurus_description" style="width:110px;"><?php echo _DESC;?> : </label>
                <textarea name="thesaurus_description" id="thesaurus_description" style="width:100%;height:150px;"><?php echo $_SESSION['m_admin']['thesaurus']['thesaurus_description']; ?></textarea>
            </p>

            <p class="buttons">
                <?php
                if ($mode == 'up') { ?>
                    <input class="button" type="submit" name="thesaurus_submit" value=
                    "<?php echo _MODIFY;?>" />
                    <?php
                } elseif ($mode == 'add') { ?>
                    <input type="submit" class="button"  name="thesaurus_submit" value=
                    "<?php echo _ADD;?>" />
                    <?php
                }
                ?>
                <input type="button" class="button"  name="cancel" value="<?php
                 echo _CANCEL;?>" onclick="javascript:window.location.href='<?php
                 echo $_SESSION['config']['businessappurl'];
                 ?>index.php?page=manage_thesaurus_list_controller&amp;mode=list&amp;module=thesaurus'"/>
            </p>
            </div>
        </div>
        <div class="block" style="margin-left:10px;width:20%;height:300px;float:right;">
            <h2 id ="thesaurus_name_specific"><?php echo _THESAURUS_LIST_SPECIFIC; ?></h2>
            <p id="thesaurus_list_specific_content" style="height:240px;overflow:hidden;overflow-y:auto;">
            <p>
        </div>
        <div class="block" style="width:20%;height:300px;float:right;">
            <h2><?php echo _THESAURUS_NAME_ASSOCIATE; ?></h2>
            <?php
            require_once 'modules' . DIRECTORY_SEPARATOR . 'thesaurus'
                        . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
                        . 'class_modules_tools.php';
            $thesaurus = new thesaurus();

            $thesaurus_name_associate = explode('/', $_SESSION['m_admin']['thesaurus']['thesaurus_name_associate']);

            ?>
            <select multiple="multiple" id="thesaurus_name_associate" name="thesaurus_name_associate[]" data-placeholder=" ">
            <?php
                if(!empty($thesaurus_name_associate[0])){
                    foreach ($thesaurus_name_associate as $key => $value) {
                        $thesaurus_name_associate_id = $thesaurus->getThesIdByLabel($value);
                        echo '<option title="'.functions::show_string($value).'" data-object_type="thesaurus_id" id="thesaurus_'.$thesaurus_name_associate_id.'"  value="' . $thesaurus_name_associate_id . '"';
                            echo ' selected="selected"'; 
                        echo '>' 
                            .  functions::show_string($value) 
                            . '</option>';

                    }
                } ?>
            </select> <i onclick="lauch_thesaurus_list_admin_assoc(this);" class="fa fa-search" title="parcourir le thésaurus" aria-hidden="true" style="cursor:pointer;"></i>
        </div>
        </form >
        <style type="text/css">#thesaurus_name_associate_chosen .chosen-drop{display:none;}.search-choice{padding: 5px !important;}</style>
        <script type="text/javascript">new Chosen($('thesaurus_name_associate'),{width: "95%", disable_search_threshold: 10});
        </script>
        <script type="text/javascript">document.getElementById("thesaurus_parent_id").onchange();</script>
    <?php
    }
    ?></div><?php
}
