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
        <h2 onClick="slide('chooseLanguage');" style="cursor: pointer;">
            <?php echo _CHOOSE_LANGUAGE;?>
        </h2>
    </div>
    <div class="contentBlock" id="chooseLanguage">
        <p>
            <form action="scripts/language.php" method="post">
                <select name="languageSelect" id="languageSelect" onChange="checkLanguage(this.value)">
                    <option value="default">Select a language</option>
                    <?php
                        for($i=0; $i<count($listLang);$i++) {
                            echo '<option ';
                              echo 'value="'.$listLang[$i].'"';
                            echo '>';
                                if ($listLang[$i] == "fr") {
                                    echo "French";
                                } else if ($listLang[$i] == "en") {
                                    echo "English";
                                } else {
                                    echo $listLang[$i];
                                }
                            echo '</option>';
                        }
                    ?>
                </select>
            </form>
        </p>
    </div>
</div>
<br />
<div class="blockWrapper">
    <div class="contentBlock" id="chooseLanguage">
        <p>
            <div id="buttons">
                <div style="float: left;" class="previousButton">
                </div>
                <div style="float: right;" class="nextButton" id="next">
                    <span id="returnCheckLanguage" style="display: none;">
                        <a href="#" onClick="$('form').submit();">
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
