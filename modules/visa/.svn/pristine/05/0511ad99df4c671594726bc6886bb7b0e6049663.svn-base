<?php
/**
* File : view_attachement.php
&page=view_attachment&res_id_master=194&id=387
*/

require_once "core/class/class_security.php";
require_once 'modules/attachments/attachments_tables.php';
require_once 'core/core_tables.php';
require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
    . 'class_indexing_searching_app.php';
require_once "core/class/class_history.php";

$core = new core_tools();
$core->test_user();
$core->load_lang();
$function = new functions();
$sec = new security();
$_SESSION['error'] = "";
if (isset($_GET['id'])) {
	if ($_GET['id'] == "last"){
		$sId = $_SESSION['new_id'];
		unset($_SESSION['new_id']);
	}
    else $sId = $_GET['id'];
} else {
    $sId = "";
}
$sId = $function->wash($_GET['id'], "num", _THE_DOC);
if (! empty($_SESSION['error'])) {
    header("location: " . $_SESSION['config']['businessappurl'] . "index.php");
    exit();
} else {
    $db = new Database();
	
    $stmt = $db->query(
        "SELECT coll_id, res_id_master 
            FROM res_view_attachments 
            WHERE (res_id = ? OR res_id_version = ?) AND res_id_master = ? ORDER BY relation desc",array($sId, $sId, $_REQUEST['res_id_master'])
    );
    $res = $stmt->fetchObject();
    $collId = $res->coll_id;
    $resIdMaster = $res->res_id_master;

    $where2 = "";
    foreach (array_keys($_SESSION['user']['security']) as $key) {
        if ($collId == $key) {
            $where2 = " and ( " . $_SESSION['user']['security'][$key]['DOC']['where']
                    . " ) ";
        }
    }

    $table = $sec->retrieve_table_from_coll($collId);
    $stmt = $db->query(
        "SELECT res_id FROM $table WHERE res_id = ?", array($resIdMaster)
    );
    //$db->show();
    if ($stmt->rowCount() == 0) {
        $_SESSION['error'] = _THE_DOC . " " . _EXISTS_OR_RIGHT;
        header(
            "location: " . $_SESSION['config']['businessappurl'] . "index.php"
        );
        exit();
    } else {
        $stmt = $db->query(
            "SELECT docserver_id, path, filename, format 
                FROM res_view_attachments 
                WHERE (res_id = ? OR res_id_version = ?) AND res_id_master = ? ORDER BY relation desc", array($sId, $sId, $_REQUEST['res_id_master'])
        );

        if ($stmt->rowCount() == 0) {
            $_SESSION['error'] = _THE_DOC . " " . _EXISTS_OR_RIGHT;
            header(
                "location: " . $_SESSION['config']['businessappurl']
                . "index.php"
            );
            exit();
        } else {
            $line = $stmt->fetchObject();
            $docserver = $line->docserver_id;
            $path = $line->path;
            $filename = $line->filename;
            $format = "pdf";
            $pdfFilename = str_ireplace($line->format, $format, $filename);
            
            $stmt = $db->query(
                "select path_template from " . _DOCSERVERS_TABLE_NAME
                . " where docserver_id = ?",array($docserver)
            );
            //$db->show();
            $lineDoc = $stmt->fetchObject();
            $docserver = $lineDoc->path_template;
            $file = $docserver . $path . $pdfFilename;
            $file = str_replace("#", DIRECTORY_SEPARATOR, $file);
			
			//$file = str_replace(pathinfo($file, PATHINFO_EXTENSION), "pdf",$file);
			
            if (strtoupper($format) == "MAARCH") {
                if (file_exists($file)) {
                    $myfile = fopen($file, "r");

                    $data = fread($myfile, filesize($file));
                    fclose($myfile);
                    $content = stripslashes($data);
                    $core->load_html();
                    $core->load_header();
                    ?>
                    <body id="validation_page" onload="javascript:moveTo(0,0);resizeTo(screen.width, screen.height);">
                    <div id="model_content" style="width:100%;"  >

                    <?php functions::xecho($content);?>

                    </div>
                    </body>
                    </html> <?php
                } else {
                    $_SESSION['error'] = _NO_DOC_OR_NO_RIGHTS . "...";
                    ?><script type="text/javascript">window.opener.top.location.href='index.php';self.close();</script><?php
                }
            } else {
                require_once 'core/docservers_tools.php';
                $arrayIsAllowed = array();
                $arrayIsAllowed = Ds_isFileTypeAllowed($file);
                if ($arrayIsAllowed['status']) {
                    if ($_SESSION['history']['attachview'] == "true") {
                        $hist = new history();
                        $hist->add(
                            $table, $sId, "VIEW", 'attachview', _VIEW_DOC_NUM . "" . $sId,
                            $_SESSION['config']['databasetype'], 'apps'
                        );
                    }
                    header("Pragma: public");
                    header("Expires: 0");
                    header(
                        "Cache-Control: must-revalidate, post-check=0, pre-check=0"
                    );
                    header("Cache-Control: public");
                    header("Content-Description: File Transfer");
                    header("Content-Type: " . $arrayIsAllowed['mime_type']);
                    header(
                        "Content-Disposition: inline; filename="
                        . basename('maarch.' . $format) . ";"
                    );
                    header("Content-Transfer-Encoding: binary");
                    readfile($file);
                    exit();
                } else {
                    echo _FORMAT . ' ' . _UNKNOWN;
                    exit();
                }
            }
        }
    }
}

?>