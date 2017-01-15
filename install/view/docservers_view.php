<?php
/*
*   Copyright 2008-2012 Maarch
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
* @brief class of install tools
*
* @file
* @author Arnaud Veber
* @date $date$
* @version $Revision$
* @ingroup install
*/
?>
<script>
    function createDocservers(
        docserverRoot
    )
    {
        $(document).ready(function() {
            var oneIsEmpty = false;
            if (docserverRoot.length < 1) {
                var oneIsEmpty = true;
            }

            if (oneIsEmpty) {
                $('#ajaxReturn_createDocservers_ko').html('<?php echo _MUST_CHOOSE_DOCSERVERS_ROOT;?>');
                $('#ajaxReturn_createDocservers_button').css('display', 'block');
                return;
            }
            $('#ajaxReturn_createDocservers_ko').html('');

            ajaxDB(
                'docservers',
                  'docserverRoot|'+docserverRoot,
                'ajaxReturn_createDocservers',
                'false'
            );

        });
    }
</script>
<div class="blockWrapper">
    <div class="titleBlock">
        <h2 onClick="slide('docservers');" style="cursor: pointer;">
            <?php echo _DOCSERVERS;?>
        </h2>
    </div>
    <div class="contentBlock" id="docservers">
        <p>
            <h6>
                <?php echo _DOCSERVERS_EXP;?>
            </h6>
            <form>
                <table>
                    <tr>
                        <td>
                            <?php echo _DOCSERVER_ROOT;?>
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <input type="text" name="docserverRoot" id="docserverRoot"/>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>
                            <input type="button" id="ajaxReturn_createDocservers_button" onClick="$(this).css('display', 'none');createDocservers($('#docserverRoot').val());"; value="<?php echo _CREATE_DOCSERVERS;?>"/>
                        </td>
                    </tr>
                </table>
            </form>
            <br />
            <div id="ajaxReturn_createDocservers_ko"></div>
        </p>
    </div>
</div>
<br />
<div class="blockWrapper">
    <div class="contentBlock" id="docservers">
        <p>
            <div id="buttons">
                <div style="float: left;" class="previousButton" id="previous">
                    <a href="#" onClick="goTo('index.php?step=database');" style="display:none;">
                        <?php echo _PREVIOUS_INSTALL;?>
                    </a>
                </div>
                <div style="float: right;" class="nextButton" id="next">
                    <a href="#" onClick="goTo('index.php?step=smtp');" id="ajaxReturn_createDocservers" style=" display: none;">
                        <?php echo _NEXT_INSTALL;?>
                    </a>
                </div>
            </div>
            <br />
            <br />
        </p>
    </div>
</div>

