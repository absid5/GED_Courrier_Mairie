<?php
/*
*    Copyright 2008,2009 Maarch
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
* @brief  Frame to show the file to index (action index_mlb.php)
*
* @file file_iframe.php
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup indexing_searching_mlb
*/
$func = new functions();
$core = new core_tools();
$core->test_user();
$core->load_lang();
require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
    . 'class_indexing_searching_app.php';
$is = new indexing_searching_app();

$extension = explode(".",$_SESSION['upfile']['name']);
$count_level = count($extension)-1;
$the_ext = $extension[$count_level];
$_SESSION['upfile']['format'] = $the_ext;

$extList = $is->filetypes_showed_indexation();
if (isset($_SESSION['upfile']['format'])) {
    $showFile = $is->show_index_frame($_SESSION['upfile']['format']);
    $ext = strtolower($_SESSION['upfile']['format']);
} else {
    $showFile = false;
    $ext = '';
}

if ($_SESSION['origin'] == "scan") {
	if (file_exists(
	    $_SESSION['config']['tmppath'] . 'tmp_file_'
	    . $_SESSION['upfile']['md5'] . '.' . $ext
	)
	) {
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Type: ".$_SESSION['upfile']['mime']);
		header(
			"Content-Disposition: inline; filename=" . basename('maarch') . "."
		    . $ext . ";"
		);
		header("Content-Transfer-Encoding: binary");
		$loc = $_SESSION['config']['tmppath'] . 'tmp_file_'
		     . $_SESSION['upfile']['md5'] . '.' . $ext;
		readfile($loc);
	} else {
		echo "<br/>PROBLEM DURING FILE SEND";
	}
	exit();
} else if (isset($_SESSION['upfile']['mime'])
    && ! empty($_SESSION['upfile']['mime'])
    && isset($_SESSION['upfile']['format'])
    && ! empty($_SESSION['upfile']['format'])
    && $_SESSION['upfile']['error'] <> 1
) {
	if ($showFile) {
		$mimeType = $is->get_mime_type($_SESSION['upfile']['format']);
		//print_r($_SESSION['upfile']);exit;
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Type: ".$mimeType);
		header(
			"Content-Disposition: inline; filename=" . basename('maarch') . "."
		    . $ext . ";"
		);
		header("Content-Transfer-Encoding: binary");
		$ext = strtolower($_SESSION['upfile']['format']);
		if (file_exists($_SESSION['upfile']['local_path'])) {
			$loc = $_SESSION['upfile']['local_path'];
			readfile($loc);
		}
		exit();
	} else {
        $core->load_html();
        $core->load_header();
        //time = $core->get_session_time_expire();
		?>
		<body background="<?php
		echo $_SESSION['config']['businessappurl'];
		?>static.php?filename=bg_home_home.gif" style="background-repeat:no-repeat;background-position:center">
		<?php
   		$ext = strtolower($_SESSION['upfile']['format']);
        if (file_exists($_SESSION['upfile']['local_path'])) {
			echo "<br/><br/><div class=\"error\">"
			    . _FILE_LOADED_BUT_NOT_VISIBLE . _ONLY_FILETYPES_AUTHORISED
			    . " <br/><ul>";
		    for ($i = 0; $i < count($extList); $i ++) {
			    echo "<li>" . $extList[$i] . "</li>";
		    }
			echo "</ul></div>";
		} else {
			echo "<br/><br/><div class=\"error\">"
			    . _PROBLEM_LOADING_FILE_TMP_DIR . ".</div>";
		}
	    ?>
        &nbsp;
        </body>
        </html>
        <?php
	}
} else {
     $core->load_html();
     $core->load_header();
	?>
    <body background="<?php
    echo $_SESSION['config']['businessappurl'];
    ?>static.php?filename=bg_home_home.gif" style="background-repeat:no-repeat;background-position:center">
	<?php
	if (isset($_SESSION['upfile']['error'])
	    && $_SESSION['upfile']['error'] == 1
	) {
	    $filesize = $func->return_bytes(ini_get("upload_max_filesize"));
		echo "<br/><br/><div class=\"error\">" . _MAX_SIZE_UPLOAD_REACHED
		    . " (" . round($filesize / 1024, 2) . "Ko Max)</div>";
	} else {
	    echo "<br/><br/><div class=\"advertissement\">" . $_SESSION['error']
	        . " " . _ONLY_FILETYPES_AUTHORISED . " :<br/><ul>";
		$displayedExtList = '';
		$extension_array = array();
		for ($i = 0; $i < count($extList); $i ++) {
			if (!array_search($extList[$i], $extension_array)) {
				$extension_array[] = $extList[$i];
				$displayedExtList .= $extList[$i].", ";
			}
		}

		echo "<li>" . substr($displayedExtList, 0 , - 2) . "</li>";
		echo "</ul></div>";
	}
	$_SESSION['error'] = '';
	?>
    &nbsp;
    </body>
    </html>
    <?php
}
