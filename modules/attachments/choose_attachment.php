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
* @file choose_attachment.php
* @date $date$
* @version $Revision$
*/

$core_tools = new core_tools();
$core_tools->load_lang();
$func = new functions();
$core_tools->load_html();
$core_tools->load_header('', true, false);
$upFileOK = false;
?>
    <body>
    <?php
    $_SESSION['upfile']['error'] = 0;
    if (isset($_FILES['file']['error']) && $_FILES['file']['error'] == 1) {
        $_SESSION['upfile']['error'] = $_FILES['file']['error'];
        if ($_SESSION['upfile']['error'] == 1) {
 			$_SESSION['error'] = 'La taille du fichier telecharge excede la valeur de upload_max_filesize';
        }
    } elseif (!empty($_FILES['file']['tmp_name']) && $_FILES['file']['error'] <> 1) {
        $_SESSION['error'] = '';
    	$_SESSION['upfile']['tmp_name'] = $_FILES['file']['tmp_name'];
        $extension = explode(".",$_FILES['file']['name']);
        $name_without_ext = substr($_FILES['file']['name'], 0, strrpos($_FILES['file']['name'], "."));
        echo '<script>window.parent.document.getElementById(\'title\').value=\''.$name_without_ext.'\';</script>';
        $count_level = count($extension)-1;
        $the_ext = $extension[$count_level];
        $fileNameOnTmp = 'tmp_file_' . $_SESSION['user']['UserId']
            . '_' . rand() . '.' . strtolower($the_ext);
            $_SESSION['upfile']['fileNameOnTmp'] = $fileNameOnTmp;
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
                echo '<script>window.parent.document.getElementById(\'viewframevalid_attachment\').src=\''.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=file_iframe&#navpanes=0'.$_SESSION['upfile']['local_path'].'\';</script>';
            }
        }
    }
    ?>
    <form name="select_file_form" id="select_file_form" method="get" enctype="multipart/form-data" action="<?php
        echo $_SESSION['config']['businessappurl'];
        ?>index.php?display=true&module=attachments&page=choose_attachment" class="forms">
        <input type="hidden" name="display" value="true" />
        <input type="hidden" name="dir" value="indexing_searching" />
        <input type="hidden" name="page" value="choose_attachment" />
        <?php
            if (!empty($_SESSION['upfile']['local_path']) && empty($_SESSION['error'])) { ?>
                <i class="fa fa-check-square fa-2x" title="<?php echo _DOWNLOADED_FILE; ?>"></i>
                <input type="button" id="fileButton" onclick="$$('#file')[0].click();" class="button"
                       value="<?php if($_REQUEST['with_file'] == 'true'){ echo _WITHOUT_FILE; } else {echo $_FILES['file']['name']; }?>"
                       title="<?php if($_REQUEST['with_file'] == 'true'){ echo _WITHOUT_FILE; } else {echo $_FILES['file']['name']; }?>"
                       style="width: 85%;margin: 0px;margin-top: -10px;font-size: 12px;text-align: center;text-overflow: ellipsis;overflow: hidden;">
            <?php } elseif (!empty($_SESSION['error'])) { ?>
                <i class="fa fa-remove fa-2x" title="<?php echo $_SESSION['error']; ?>"></i>
                <input type="button" id="fileButton" onclick="$$('#file')[0].click();" class="button" value="<?php echo $_SESSION['error']; ?>" style="width: 85%;margin: 0px;margin-top: -10px;font-size: 12px;text-align: center;text-overflow: ellipsis;overflow: hidden;">
            <?php } else { ?>
                <i class="fa fa-remove fa-2x" title="<?php echo _NO_FILE_SELECTED; ?>"></i>
                <input type="button" id="fileButton" onclick="$$('#file')[0].click();" class="button" value="<?php echo _CHOOSE_FILE; ?>" style="width: 85%;margin: 0px;margin-top: -10px;font-size: 12px;text-align: center;text-overflow: ellipsis;overflow: hidden;">
            <?php } ?>
        <p style="display:none">
        <!-- window.parent.$('title').value = this.value.substring(0,this.value.indexOf('.')); -->
            <input type="file" name="file" id="file" onchange="$('with_file').value='false';this.form.method = 'post';this.form.submit();" value="<?php
                if (isset($_SESSION['file_path'])) {
                    echo $_SESSION['file_path'];
                } ?>" style="width:200px" />
        </p>
        <p style="display:none">
            <div align="center">
            	<input type="radio" name="with_file" id="with_file" value="false" onclick="this.form.method = 'post';this.form.submit();" />
            </div>
        </p>
    </form>
    <?php $core_tools->load_js();?>
    </body>
</html>
!