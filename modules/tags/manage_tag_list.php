<?php
/*
*    Copyright 2008,2014 Maarch
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
* Module : Tags
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
        $tagslist['tab'],
        count($tagslist['tab']),
        $tagslist['title'],
        'tag_label',
        'manage_tag_list_controller&mode=list',
        'tags','tag_label',
        true,
        $tagslist['page_name_up'],
        $tagslist['page_name_val'],
        $tagslist['page_name_ban'],
        $tagslist['page_name_del'],
        $tagslist['page_name_add'],
        $tagslist['label_add'],
        false,
        false,
        _ALL_TAGS,
        _TAG,
        'tags',
        true,
        true,
        false,
        true,
        $eventsList['what'],
        true,
        $tagslist['autoCompletionArray']
    );
} elseif ($mode == 'up' || $mode == 'add') {
    ?><h1><i class="fa fa-tags fa-2x"> </i>
        <?php
        if ($mode == 'up') {
            echo _MODIFY_TAG;
        } elseif ($mode == 'add') {
            echo _ADD_TAG;
        }?>
    </h1>
    <div id="inner_content" class="clearfix" align="center">
        <br />
    <?php
    if ($state == false) {
        echo '<br /><br /><br /><br />' . _THIS_EVENT . ' ' . _IS_UNKNOWN
        . '<br /><br /><br /><br />';
    } else { ?>
    <div class="block">
        <form name="frmevent" id="frmevent" method="post" action="<?php
            echo $_SESSION['config']['businessappurl'] . 'index.php?display=true'
            . '&amp;module=tags&amp;page=manage_tag_list_controller&amp;mode='
            . $mode;?>" class="forms addforms">
            <input type="hidden" name="display" value="true" />
            <input type="hidden" name="admin" value="tags" />
            <input type="hidden" name="page" value="manage_tag_list_controler" />
            <input type="hidden" name="mode" value="<?php functions::xecho($mode);?>" />

            <input type="hidden" name="tag_label" id="tag_label" value="<?php functions::xecho($_SESSION['m_admin']['tag']['tag_label']);?>" />

            <input type="hidden" name="order" id="order" value="<?php
                functions::xecho($_REQUEST['order']);?>" />
            <input type="hidden" name="order_field" id="order_field" value="<?php
                functions::xecho($_REQUEST['order_field']);?>" />
            <input type="hidden" name="what" id="what" value="<?php
                functions::xecho($_REQUEST['what']);?>" />
            <input type="hidden" name="start" id="start" value="<?php
                functions::xecho($_REQUEST['start']);?>" />

            <p>
                <label for="label"><?php echo _NAME_TAGS;?> : </label>
                <input name="tag_label" type="text"  id="tag_label_id" value="<?php
                    echo functions::show_str(
                        $_SESSION['m_admin']['tag']['tag_label']
                    );?>"/>
            </p>

           <?php
            if ($mode == 'up') { ?>
                <p>
                    <label for="label"><?php echo _COLL_ID;?> : </label>
                    <span><?php
                        echo functions::show_str(
                            $_SESSION['m_admin']['tag']['tag_coll']
                        );?>
                    </span>
                </p>
            <?php
            } else {
                $arrayColl = $_SESSION['m_admin']['tags']['coll_id'];
                ?>
                <p>
                    <label for="collection"><?php echo _COLLECTION;?> : </label>
                    <select disabled name="collection" id="collection" >
                        <!--<option value="" ><?php echo _CHOOSE_COLLECTION;?></option>-->
                    <?php
                    for ($i = 0; $i < count($arrayColl); $i ++) {
                        ?>
                        <option  value="<?php
                        functions::xecho($arrayColl[$i]['id']);
                        ?>" <?php
                        if (isset($_SESSION['m_admin']['doctypes']['COLL_ID'])
                            && $_SESSION['m_admin']['doctypes']['COLL_ID'] == $arrayColl[$i]['id']
                        ) {
                            echo 'selected="selected"';
                        }
                        ?> ><?php functions::xecho($arrayColl[$i]['label']);?></option>
                        <?php
                    }

                    ?>
                    </select>
                </p>
            <?php
            }

            if ($mode == 'up') { ?>
                <p>
                    <label for="label"><?php echo _NB_DOCS_FOR_THIS_TAG;?> : </label>
                    <span><?php
                        echo functions::show_str(
                            $_SESSION['m_admin']['tag']['tag_count']
                        );?>
                    </span>
                </p>
            <?php
            }
            ?>

            <p class="buttons">
                <?php
                if ($mode == 'up') { ?>
                    <input class="button" type="submit" name="tag_submit" value=
                    "<?php echo _MODIFY_TAG;?>" />
                    <?php
                } elseif ($mode == 'add') { ?>
                    <input type="submit" class="button"  name="tag_submit" value=
                    "<?php echo _ADD;?>" />
                    <?php
                }
                ?>
                <input type="button" class="button"  name="cancel" value="<?php
                 echo _CANCEL;?>" onclick="javascript:window.location.href='<?php
                 echo $_SESSION['config']['businessappurl'];
                 ?>index.php?page=manage_tag_list_controller&amp;mode=list&amp;module=tags'"/>

                <?php
                if ($mode == 'up') {
                    ?>
                    <hr/>
                    <h3><?php echo _TAGOTHER_OPTIONS;?></h3>
                    <p>
                        <label for="label"><?php echo _TAG_FUSION_ACTIONLABEL;?> : </label>
                        <select name="tagfusion" id="tagfusion">
                        <?php
                            foreach ($_SESSION['tmp_all_tags'] as $tmp_selectvalue_tag) {
                                ?>
                                <option value="<?php functions::xecho($tmp_selectvalue_tag['tag_label'].",".$tmp_selectvalue_tag['coll_id']);?>">
                                    <?php functions::xecho($tmp_selectvalue_tag['tag_label']." ::".$tmp_selectvalue_tag['coll_id']);?>
                                </option>
                                <?php
                            }
                        ?>
                        </select>

                       <input type="button" class="button"  name="cancel" style="border-radius:8px;font-size:8px;"
                       onclick = "tag_fusion('<?php echo addslashes($_SESSION['m_admin']['tag']['tag_label']).','
                       .$_SESSION['m_admin']['tag']['tag_coll'];?>',
                        $('tagfusion').value, <?php functions::xecho($route_tag_fusion_tags);?>,'<?php
                        echo _TAGFUSION_GOODRESULT;?>' , '<?php
                        echo $_SESSION['config']['businessappurl'] . 'index.php?display=true'
                        . '&amp;module=tags&amp;page=manage_tag_list_controller&amp;mode=list'
                       ?>');" value="<?php echo _TAGFUSION;?> ">

                    </p>
                    <?php
                } ?>
            </p>
        </form >
        </div>
    <?php
    }
    ?></div><?php
}
