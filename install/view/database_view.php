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
    function checkDatabaseInfo(
        databaseserver,
        databaseserverport,
        databaseuser,
        databasepassword,
        databasetype,
        action
    )
    {
        $(document).ready(function() {
            var oneIsEmpty = false;
            if (databaseserver.length < 1) {
                var oneIsEmpty = true;
            }
            if (databaseserverport.length < 1) {
                var oneIsEmpty = true;
            }
            if (databaseuser.length < 1) {
                var oneIsEmpty = true;
            }
            if (databasepassword.length < 1) {
                var oneIsEmpty = true;
            }
            if (databasetype.length < 1) {
                var oneIsEmpty = true;
            }
            if (action.length < 1) {
                var oneIsEmpty = true;
            }

            if (oneIsEmpty) {
                $('#ajaxReturn_testConnect_ko').html('<?php echo _ONE_FIELD_EMPTY;?>');
                return;
            }
            $('.wait').css('display','block');
            $('#ajaxReturn_testConnect_ko').html('');

            ajaxDB(
                'database',
                  'databaseserver|'+databaseserver
                  +'|databaseserverport|'+databaseserverport
                  +'|databaseuser|'+databaseuser
                  +'|databasepassword|'+databasepassword
                  +'|databasetype|'+databasetype
                  +'|action|'+action,
                'ajaxReturn_testConnect',
                'false'
            );

        });
    }

    function checkCreateDB(
        databasename,
        action
    )
    {
        $(document).ready(function() {
            var oneIsEmpty = false;
            if (databasename.length < 1) {
                var oneIsEmpty = true;
            }
            if (action.length < 1) {
                var oneIsEmpty = true;
            }

            if (oneIsEmpty) {
                $('#ajaxReturn_createDB_ko').html('<?php echo _CHOOSE_A_NAME_FOR_DB;?>');
                $('.wait').css('display','none');
                return;
            }
            $('.wait').css('display','block');
            $('#ajaxReturn_createDB_ko').html('');

            ajaxDB(
                'database',
                  'databasename|'+databasename
                  +'|action|'+action,
                'ajaxReturn_createDB',
                'false'
            );
        });
    }

    function checkDataDB(
        value
    )
    {
        $(document).ready(function() {
            if (value != 'default') {
                $('#okDatas').css('display','block');
            } else {
                $('#okDatas').css('display','none');
            }
        });
    }

    function checkLoadDatas(
        dataFilename,
        action
    )
    {
        $(document).ready(function() {
            var oneIsEmpty = false;
            if (dataFilename.length < 1) {
                var oneIsEmpty = true;
            }
            if (action.length < 1) {
                var oneIsEmpty = true;
            }

            if (oneIsEmpty) {
                $('#ajaxReturn_loadDatas_ko').html('<?php echo _CHOOSE_DATASET_TO_IMPORT;?>');
                return;
            }
            $('.wait').css('display','block');
            $('#ajaxReturn_loadDatas_ok').html('');

            ajaxDB(
                'database',
                  'dataFilename|'+dataFilename
                  +'|action|'+action,
                'ajaxReturn_loadDatas',
                'false'
            );
        });
    }
</script>
<div class="ajaxReturn_testConnect">
    <div class="blockWrapper">
        <div class="titleBlock">
            <h2 onClick="slide('database');" style="cursor: pointer;">
                <?php echo _DATABASE_INFOS;?>
            </h2>
        </div>
        <div class="contentBlock" id="database">
            <p>
                <h6>
                    <?php echo _DATABASE_EXP;?>
                </h6>
                <form>
                    <table>
                        <tr>
                            <td>
                                <?php echo _DATABASESERVER;?>
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                <input type="text" id="databaseserver" name="databaseserver" value="localhost"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo _DATABASESERVERPORT;?>
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                <input type="text" id="databaseserverport" name="databaseserverport" value="5432"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo _DATABASEUSER;?>
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                <input type="text" id="databaseuser" name="databaseuser" value="postgres"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo _DATABASEPASSWORD;?>
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                <input type="password" id="databasepassword" name="databasepassword"/>
                            </td>
                        </tr>
                        <tr style="display: none;">
                            <td>
                                <?php echo _DATABASETYPE;?>
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                <input type="hidden" id="databasetype" name="databasetype" value="POSTGRESQL"/>
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
                                <input
                                  type="button"
                                  name="Submit"  value="<?php echo _SUBMIT;?>"
                                  onClick="
                                    checkDatabaseInfo(
                                      $('#databaseserver').val(),
                                      $('#databaseserverport').val(),
                                      $('#databaseuser').val(),
                                      $('#databasepassword').val(),
                                      $('#databasetype').val(),
                                      'testConnect'
                                    );
                                  "
                                />
                            </td>
                        </tr>
                    </table>
                </form>
                <br />
                <div id="ajaxReturn_testConnect_ko"></div>
                <div align="center">
                    <img src="img/wait.gif" width="100" class="wait" style="display: none; background-color: rgba(0, 0, 0, 0.2);"/>
                </div>
            </p>
        </div>
    </div>
</div>
<div class="ajaxReturn_createDB">
    <div class="blockWrapper" id="ajaxReturn_testConnect" style="display: none;">
        <div class="titleBlock">
            <h2 onClick="slide('createdatabase');" style="cursor: pointer;">
                <?php echo _DATABASE_CREATE;?>
            </h2>
        </div>
        <div class="contentBlock" id="createdatabase">
            <p>
                <h6>
                    <?php echo _DATABASE_ADD_INF;?>
                </h6>
                <div id="ajaxReturn_testConnect_ok"></div>
                <form>
                    <table>
                        <tr>
                            <td>
                                <?php echo _DATABASENAME;?>
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                <input type="text" name="databasename" id="databasename" />
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
                                <input type="button" onclick="$('.wait').css('display','block');checkCreateDB($('#databasename').val(), 'createdatabase');" value="<?php echo _CREATE_DATABASE;?>" />
                            </td>
                        </tr>
                    </table>
                </form>
                <br />
                <div id="ajaxReturn_createDB_ko"></div>
                <div align="center">
                    <img src="img/wait.gif" width="100" class="wait" style="display: none; background-color: rgba(0, 0, 0, 0.2);"/>
                </div>
            </p>
        </div>
    </div>
</div>
<div class="ajaxReturn_loadDatas">
    <div class="blockWrapper" id="ajaxReturn_createDB" style="display: none;">
        <div class="titleBlock">
            <h2 onClick="slide('database');" style="cursor: pointer;">
                <?php echo _DATASET_CHOICE;?>
            </h2>
        </div>
        <div class="contentBlock">
            <p>
                <h6>
                    <?php echo _DATASET_EXP;?>
                </h6>
                <div id="ajaxReturn_createDB_ok"></div>
                <form>
                    <table>
                        <tr>
                            <td>
                                <?php echo _DATASET;?>
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                <select onChange="checkDataDB($(this).val());" id="dataFilename">
                                    <option value="default"><?php echo _CHOOSE;?></option>
                                    <?php
                                        for($i=0; $i<count($listSql);$i++) {
                                            echo '<option ';
                                              echo 'value="'.$listSql[$i].'"';
                                            echo '>';
                                                echo $listSql[$i];
                                            echo '</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                            <td id="returnCheckDataClassic" style="display: none;">
                                ORIENTÉ ARCHIVAGE
                            </td>
                            <td id="returnCheckDataMlb" style="display: none;">
                                ORIENTÉ COURRIER
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <input type="button" onclick="$('.wait').css('display','block');checkLoadDatas($('#dataFilename').val(), 'loadDatas');" value="<?php echo _LOAD_DATA;?>" style="display: none;" id="okDatas"/>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </form>
                <br />
                <div id="ajaxReturn_loadDatas_ko"></div>
                <div align="center">
                    <img src="img/wait.gif" width="100" class="wait" style="display: none; background-color: rgba(0, 0, 0, 0.2);"/>
                </div>
            </p>
        </div>
    </div>
</div>
<br />
<div class="blockWrapper">
    <div class="contentBlock">
        <p>
            <div id="buttons">
                <div style="float: left;" class="previousButton" id="previous">
                    <a href="#" onClick="goTo('index.php?step=prerequisites');" style="display: none;">
                        <?php echo _PREVIOUS_INSTALL;?>
                    </a>
                </div>
                <div style="float: right;" class="nextButton" id="next">
                    <a href="#" onClick="goTo('index.php?step=docservers');" id="ajaxReturn_loadDatas" style=" display: none;">
                        <?php echo _NEXT_INSTALL;?>
                    </a>
                </div>
            </div>
            <br />
            <br />
        </p>
    </div>
</div>
