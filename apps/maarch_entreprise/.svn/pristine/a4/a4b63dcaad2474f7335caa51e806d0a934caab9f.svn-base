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

/**
* @brief  Frame to choose a file to index
*
* @file choose_file.php
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup indexing_searching_mlb
*/

$core_tools = new core_tools();
$core_tools->load_lang();
$func = new functions();
$core_tools->load_html();
$core_tools->load_header('', true, false);
$upFileOK = false;
$_SESSION['with_file'] = false;
?>
    <body>
    <?php
    $_SESSION['upfile']['error'] = 0;
    if (isset($_FILES['file']['error']) && $_FILES['file']['error'] == 1) {
        $_SESSION['upfile']['error'] = $_FILES['file']['error'];
        if ($_SESSION['upfile']['error'] == 1) {
            ?>
            <script language="javascript" type="text/javascript">
                var test = window.top.document.getElementById('file_iframe');
                if (test != null)
                {
                    test.src = '<?php
                        echo $_SESSION['config']['businessappurl'];
                        ?>index.php?display=true&dir=indexing_searching&page=file_iframe&#navpanes=0';
                }
            </script>
            <?php
        }
    } elseif (!empty($_FILES['file']['tmp_name']) && $_FILES['file']['error'] <> 1) {
        $extension = explode(".",$_FILES['file']['name']);
        $count_level = count($extension)-1;
        $the_ext = $extension[$count_level];
        $fileNameOnTmp = 'tmp_file_' . $_SESSION['user']['UserId']
            . '_' . rand() . '.' . strtolower($the_ext);
        $filePathOnTmp = $_SESSION['config']['tmppath'] . $fileNameOnTmp;
        if (!is_uploaded_file($_FILES['file']['tmp_name'])) {
                $_SESSION['error'] = _FILE_NOT_SEND . ". " . _TRY_AGAIN
                    . ". " . _MORE_INFOS . " (<a href=\"mailto:"
                    . $_SESSION['config']['adminmail'] . "\">"
                    . $_SESSION['config']['adminname'] . "</a>)";
        } else {
            require_once 'core/docservers_tools.php';
            $arrayIsAllowed = array();
            $arrayIsAllowed = Ds_isFileTypeAllowed($_FILES['file']['tmp_name'], strtolower($the_ext));
            if ($arrayIsAllowed['status'] == false) {
                $_SESSION['error'] = _WRONG_FILE_TYPE . ' ' . $arrayIsAllowed['mime_type'];
                $_SESSION['upfile'] = array();
            } elseif (!@move_uploaded_file($_FILES['file']['tmp_name'], $filePathOnTmp)) {
                $_SESSION['error'] = _FILE_NOT_SEND . ". " . _TRY_AGAIN . ". "
                    . _MORE_INFOS . " (<a href=\"mailto:"
                    . $_SESSION['config']['adminmail'] . "\">"
                    . $_SESSION['config']['adminname'] . "</a>)";
            } else {
                $_SESSION['upfile']['size'] = $_FILES['file']['size'];
                $_SESSION['upfile']['mime'] = $_FILES['file']['type'];
                $_SESSION['upfile']['local_path'] = $filePathOnTmp;
                //$_SESSION['upfile']['name'] = $_FILES['file']['name'];
                $_SESSION['upfile']['name'] = $fileNameOnTmp;
                $_SESSION['upfile']['format'] = $the_ext;
                $upFileOK = true;
            }
        }
    } elseif ($_REQUEST['with_file'] == 'true') {
        $_SESSION['with_file'] = true;
        $pathToFile = 'apps/' . $_SESSION['config']['app_id'] . '/_no_file.pdf';

        if (is_file('custom/'.$_SESSION['custom_override_id'].'/'.$pathToFile)) {
        $pathToFile = 'custom/'.$_SESSION['custom_override_id'].'/'.$pathToFile;
        }
        
        $_SESSION['upfile']['size'] = filesize($pathToFile);
        $_SESSION['upfile']['mime'] = 'application/pdf';
        $fileNameOnTmp = 'tmp_file_' . $_SESSION['user']['UserId']
            . '_' . rand() . '.pdf';
        $_SESSION['upfile']['name'] = $fileNameOnTmp;
        $filePathOnTmp = $_SESSION['config']['tmppath'] . $fileNameOnTmp;
        $_SESSION['upfile']['local_path'] = $filePathOnTmp;
        if (copy($pathToFile, $filePathOnTmp)) {
            $upFileOK = true;
        }
    } elseif ($_REQUEST['with_file'] == 'false') {
        $_SESSION['upfile'] = array();
        $upFileOK = true;
    }
    //if ($upFileOK) {
        ?>
        <script language="javascript" type="text/javascript">
            function refreshFrame(frameId) {
                frameId.src = '<?php
                    echo $_SESSION['config']['businessappurl'];
                    ?>index.php?display=true&dir=indexing_searching&page=file_iframe';
            }

            var test = window.top.document.getElementById('file_iframe');
            if (test.src == '<?php
                echo $_SESSION['config']['businessappurl'];
                ?>index.php?display=true&dir=indexing_searching&page=file_iframe&#navpanes=0') {
                //test.location.refresh();
                //test.src = '';
                refreshFrame(test);
            }

            if (test != null) {
                //fix pb with toolbar of pdf
                test.src = '<?php
                    echo $_SESSION['config']['businessappurl'];
                    ?>index.php?display=true&dir=indexing_searching&page=file_iframe&#navpanes=0';
            }
        </script>
        <?php
    //}
    ?>
    <form name="select_file_form" id="select_file_form" method="get" enctype="multipart/form-data" action="<?php
        echo $_SESSION['config']['businessappurl'];
        ?>index.php?display=true&dir=indexing_searching&page=choose_file" class="forms">
        <input type="hidden" name="display" value="true" />
        <input type="hidden" name="dir" value="indexing_searching" />
        <input type="hidden" name="page" value="choose_file" />
        <p>
            <label for="file" style="width:90%;margin-right: -12px;margin-top: -2px">
            <?php
            if (!empty($_SESSION['upfile']['local_path']) && empty($_SESSION['error'])) { ?>
                <i class="fa fa-check-square fa-2x" title="<?php echo _DOWNLOADED_FILE; ?>"></i>
                <input type="button" id="fileButton" onclick="$$('#file')[0].click();" class="button"
                       value="<?php if($_REQUEST['with_file'] == 'true'){ echo _WITHOUT_FILE; } else {echo _DOWNLOADED_FILE;}?>"
                       style="width: 90%;margin: 0px;margin-top: -2px;font-size: 15px;text-align: center;">
            <?php } else { ?>
                <i class="fa fa-remove fa-2x" title="<?php echo _NO_FILE_SELECTED; ?>"></i>
                <input type="button" id="fileButton" onclick="$$('#file')[0].click()" class="button" value="<?php echo _CHOOSE_FILE; ?>" style="width: 90%;margin: 0px;margin-top: -2px;font-size: 15px;text-align: center;">
            <?php } ?>
            </label>
            <?php
            if($_REQUEST['with_file'] == 'true'){ ?>
                <i class="fa fa-ban fa-2x" id="with_file_icon" onclick="$$('#with_file')[0].click();" title="<?php echo _WITHOUT_FILE; ?> (actif)" style="cursor:pointer;"></i>
            <?php }else{ ?>
                <i class="fa fa-ban fa-2x" id="with_file_icon" onclick="$$('#with_file2')[0].click();" title="<?php echo _WITHOUT_FILE; ?>" style="cursor:pointer;"></i>
            <?php } ?>

            <input type="file" name="file" id="file" onchange="$('with_file').value='false';this.form.method = 'post';this.form.submit();"
                   value="<?php $_REQUEST['with_file'] = 'false';
                            if (isset($_SESSION['file_path'])) {
                                echo $_SESSION['file_path'];
                            } ?>"
                   style="width:200px;margin-left:33px;display:none;" />
        </p>
        <p style="display:none;">
            <label for="with_file">
                <?php echo _WITHOUT_FILE;?>
            </label>
            <div align="center" style="display:none;">
                <?php echo _YES;?>
                <input <?php if ($_REQUEST['with_file'] == 'true') { echo 'checked="checked"';} ?>
                    type="radio" name="with_file" id="with_file2" value="true" onclick="this.form.method = 'post';this.form.submit();" />
                <?php echo _NO;?>
                <input <?php if ($_REQUEST['with_file'] == 'false' || $_REQUEST['with_file'] == '') { echo 'checked="checked"';} ?>
                    type="radio" name="with_file" id="with_file" value="false" onclick="this.form.method = 'post';this.form.submit();" />
            </div>
        </p>
    </form>
    <?php $core_tools->load_js();?>
    </body>
</html>
