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
        <h2 onClick="slide('resume');" style="cursor: pointer;">
            <?php echo _RESUME;?>
        </h2>
    </div>
    <div class="contentBlock" id="resume">
        <p>
            <?php echo _INSTALL_SUCCESS;?><br />
            <br />
            <div align="center">
                <div class="fb-like" data-href="http://www.facebook.com/pages/Maarch/53918706268" data-send="true" data-width="850" data-show-faces="true"></div>
            </div>
        </p>
    </div>
</div>
<br />
<div class="blockWrapper">
    <div class="contentBlock" id="resume">
        <p>
            <div id="buttons">
                <div style="float: left;" class="previousButton" id="previous">
                    <a href="#" onClick="goTo('index.php?step=password');">
                        <?php echo _PREVIOUS_INSTALL;?>
                    </a>
                </div>
                <div style="float: right;" class="nextButton" id="start">
                    <a href="#" onClick="goTo('final.php');">
                        <?php echo _START_MEP_1_3;?>
                    </a>
                </div>
            </div>
            <br />
            <br />
        </p>
    </div>
</div>
