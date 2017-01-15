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
<div class="blockWrapper">
    <div class="titleBlock">
        <h2 onClick="slide('licence');" style="cursor: pointer;">
            <?php echo _LICENCE;?>
        </h2>
    </div>
    <div class="contentBlock" id="licence">
        <p>
            <div align="center">
                <div id="licenceOverflow" style="min-height: 131px; width: 880px; overflow: auto; background-color: rgba(255, 255, 255, 0.6);">
                    <br />
                    <?php echo $txtLicence;?>
                    <br />
                </div>
            </div>
        </p>
    </div>
</div>
<br />
<div class="blockWrapper">
    <div class="contentBlock" id="licence">
        <p>
            <div align="center">
                <input type="checkbox" id="checkboxLicence" onChange="checkLicence();"/>
                <label for="checkboxLicence">
                    <?php echo _OK_WITH_LICENCE;?>
                </label>
            </div>
        </p>
    </div>
</div>
<br />
<div class="blockWrapper">
    <div class="contentBlock" id="licence">
        <p>
            <div id="buttons">
                <div style="float: left;" class="previousButton" id="previous">
                    <a href="#" onClick="goTo('index.php?step=welcome');">
                        <?php echo _PREVIOUS_INSTALL;?>
                    </a>
                </div>
                <div style="float: right;" class="nextButton" id="next">
                    <span id="returnCheckLicence" style="display: none;">
                        <a href="#" onClick="goTo('index.php?step=prerequisites');">
                            <?php echo _NEXT_INSTALL;?>
                        </a>
                    </span>
                </div>
            </div>
            <br />
            <br />
        </p>
    </div>
</div>
