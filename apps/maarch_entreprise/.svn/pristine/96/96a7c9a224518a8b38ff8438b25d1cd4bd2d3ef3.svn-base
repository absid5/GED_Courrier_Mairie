<?php

/*
*   Copyright 2008-2015 Maarch
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
* File : keywords_help.php
*
* Help for keywords
*
* @package  Maarch Letterbox 3.0
* @version 2.1
* @since 10/2005
* @license GPL
* @author  Loïc Vinet  <dev@maarch.org>
*/

function show_helper($mode)
{
    $core_tools = new core_tools();
    $core_tools->load_lang();
    $core_tools->load_html();
    ?>
    <div class="block small_text" >


    <h3><i class ="fa fa-info-circle fa-3x" ></i> <?php echo _HELP_KEYWORDS;?></h3>
    <?php  

        echo "<p align='right'>";
            echo "<b><u>"._HELP_BY_CORE.":</u></b><br/><br/>";
        echo "</p>";
        echo "<p>";
            echo "<b>@user : </b><em>"._HELP_KEYWORD0."</em>";
        echo "</p>";
        echo "<p>";
            echo "<b>@email : </b><em>" . _HELP_KEYWORD_EMAIL . "</em>";
        echo "</p><br/>";

    if($core_tools->is_module_loaded('entities') == true)
    {
            echo "<p align='right'>";
                echo "<b><u>"._HELP_BY_ENTITY.":</u></b><br/><br/>";
            echo "</p>";
            echo "<p align='justify'>";
                echo "<p><b>@my_entities : </b><em>"._HELP_KEYWORD1."</em></p>";
                echo "<p><b>@my_primary_entity : </b><em>"._HELP_KEYWORD2."</em></p>";
                echo "<p><b>@subentities[('entity_1',...,'entity_n')] : </b><em>"._HELP_KEYWORD3."</em><br/></p>";
                echo "<p><b>@parent_entity['entity_id'] : <em></b>"._HELP_KEYWORD4."</em><br/></p>";
                echo "<p><b>@sisters_entities['entity_id'] : <em></b>"._HELP_KEYWORD5."</em><br/></p>";
                echo "<p><b>@entity_type['type'] : <em></b>"._HELP_KEYWORD9."</em><br/></p>";
                echo "<p><b>@all_entities : <em></b>"._HELP_KEYWORD6."</em><br/></p>";
                echo "<p><b>@immediate_children['entity_1',..., 'entity_id'] : </b><em>"._HELP_KEYWORD7."</em><br/></p>";
                echo "<p><b>@ancestor_entities['entity_id'][depth] : </b><em>"._HELP_KEYWORD8."</em><br/></p>";
                echo "<br/>"._HELP_KEYWORD_EXEMPLE_TITLE."<br/><br/>";
                echo "<div style='border:1px black solid; padding:3px;'><b>"._HELP_KEYWORD_EXEMPLE."</b></div>";
            echo "</p>";
    }
    echo "</div>";
    echo "<div class='block_end'>&nbsp;</div>";
    if($mode == 'popup')
    {
        echo '<br/><div align="center"><input type="button" class="button" name="close" value="'._CLOSE_WINDOW.'" onclick="self.close();"</div>';
    }
}

if(isset($_REQUEST['mode']))
{
    $mode = trim($_REQUEST['mode']);
}
else
{
    $mode = '';
}

$core_tools = new core_tools();
$core_tools->load_lang();
$core_tools->load_html();
$core_tools->load_header();
echo '<div id="header">';
show_helper($mode);
echo '</div></body></html>';


