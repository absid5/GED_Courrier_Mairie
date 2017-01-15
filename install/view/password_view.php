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
    function checkPassword(
        newSuperadminPass,
        newSuperadminPassTwo
    )
    {
        $(document).ready(function() {
            var oneIsEmpty = false;
            if (newSuperadminPass.length < 1) {
                var oneIsEmpty = true;
            }
            if (newSuperadminPassTwo.length < 1) {
                var oneIsEmpty = true;
            }

            if (oneIsEmpty) {
                $('#okAdminPass').css('display','none');
                $('#koAdminPass').html('<?php echo _FILL_ALL_PASSWORD_FIELDS;?>');
                return;
            }

            $('#koAdminPass').html('');

            if (newSuperadminPass != newSuperadminPassTwo) {
                $('#okAdminPass').css('display','none');
                $('#koAdminPass').html('<?php echo _PASSWORDS_ARE_DIFFERENTS;?>');
                return;
            }

            $('#koAdminPass').html('');
            $('#okAdminPass').css('display','block');
        });
    }
</script>

<div class="blockWrapper">
    <div class="titleBlock">
        <h2 onClick="slide('password');" style="cursor: pointer;">
            <?php echo _PASSWORD;?>
        </h2>
    </div>
    <div class="contentBlock" id="password">
        <p>
            <h6>
                <?php echo _PASSWORD_EXP;?>
            </h6>
            <form action="scripts/password.php" method="post">
                <table>
                    <tr>
                        <td>
                            <?php echo _NEW_ADMIN_PASS;?>
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <input type="password" name="newSuperadminPass" id="newSuperadminPass" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo _NEW_ADMIN_PASS_AGAIN;?>
                        </td>
                        <td>
                            :
                        </td>
                        <td>
                            <input type="password" name="newSuperadminPassTwo" id="newSuperadminPassTwo" onBlur="checkPassword($('#newSuperadminPass').val(), $(this).val());"/>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
                <span id="koAdminPass"></span>
            </form>
        </p>
    </div>
</div>
<br />
<div class="blockWrapper">
    <div class="contentBlock">
        <p>
            <div id="buttons">
                <div style="float: left;" class="previousButton" id="previous">
                    <a href="#" onClick="goTo('index.php?step=docservers');" style="display:none;">
                        <?php echo _PREVIOUS_INSTALL;?>
                    </a>
                </div>
                <div style="float: right;" class="nextButton" id="next">
                    <a href="#" onClick="goTo('index.php?step=config');" id="okAdminPass" style="display: none;">
                        <?php echo _NEXT_INSTALL;?>
                    </a>
                </div>
            </div>
            <br />
            <br />
        </p>
    </div>
</div>
